<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Forgot;
use App\Models\Admin;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Classes\GeniusMailer;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }
    
    public function loginForm(){
        return view('admin.login');
    }

    public function login(Request $request){

            $rules = [
                'email'    => 'required|email',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(),$rules);

            if($validator->fails()){
                return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
            }

        $admin = Admin::where('email', $request->email)->first();
        if ($admin && \Hash::check($request->password, $admin->password)) {
            if ($admin->verify == 0) {
                return response()->json(array('errors' => [ 0 => 'Your Email is not Verified!' ]));
            }
            if ($admin->status == 0) {
                return response()->json(array('errors' => [ 0 => 'Your account is inactive.' ]));
            }

            // Generate OTP
            $otp = rand(100000, 999999);
            $expiresAt = now()->addMinutes(5);

            // Store verification details in session
            session()->put('admin_login_temp_id', $admin->id);
            session()->put('admin_login_otp', $otp);
            session()->put('admin_login_otp_expires_at', $expiresAt);
            session()->put('admin_login_remember', $request->remember ? true : false);

            // Log OTP for testing and support
            \Log::info("Admin login OTP generated: {$otp} for Admin ID: {$admin->id}");

            // Mask Email: abcd@domain.com -> ab*****@domain.com
            $parts = explode('@', $admin->email);
            $maskedEmail = substr($parts[0], 0, 2) . '*****@' . $parts[1];

            // Mask Phone: 01711775263 -> 017*****263
            $phone = $admin->phone ? $admin->phone : '';
            $maskedPhone = strlen($phone) >= 6 
                ? substr($phone, 0, 3) . '*****' . substr($phone, -3)
                : '*****';

            return response()->json([
                'otp_selection_required' => true,
                'email' => $maskedEmail,
                'phone' => $maskedPhone
            ]);
        } else {
            return response()->json(array('errors' => [0 => 'Credentail Doesn,t Match']));
        }
    }

    public function sendOtp(Request $request)
    {
        $rules = [
            'channel' => 'required|in:email,phone'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $tempId = session()->get('admin_login_temp_id');
        $otp = session()->get('admin_login_otp');
        $expiresAt = session()->get('admin_login_otp_expires_at');

        if (!$tempId || !$otp || !$expiresAt) {
            return response()->json(array('errors' => [ 0 => 'Session expired. Please try logging in again.' ]));
        }

        $admin = Admin::find($tempId);
        if (!$admin) {
            return response()->json(array('errors' => [ 0 => 'Admin account not found.' ]));
        }

        if ($request->channel == 'phone') {
            if (empty($admin->phone)) {
                return response()->json(array('errors' => [ 0 => 'Admin phone number is not configured.' ]));
            }

            $message = "Your Amar Bangla admin login OTP is: " . $otp . ". Expires in 5 minutes.";
            
            $userPhone = preg_replace('/[^0-9]/', '', $admin->phone);
            if (substr($userPhone, 0, 1) == "0") {
                $userPhone = "88" . $userPhone;
            }

            $smsService = new \App\Services\SmsService();
            $smsSent = $smsService->send($userPhone, $message);

            if ($smsSent === false) {
                return response()->json(array('errors' => [ 0 => 'Failed to send SMS OTP. Please try again.' ]));
            }
        } else {
            if (empty($admin->email)) {
                return response()->json(array('errors' => [ 0 => 'Admin email is not configured.' ]));
            }

            $html = "
                <h2>Amar Bangla 24 - Admin Login OTP</h2>
                <h1>{$otp}</h1>
                <p>This OTP is valid for 5 minutes.</p>
            ";

            \App\Services\BrevoMailService::send(
                $admin->email,
                $admin->name,
                'Admin Login OTP',
                $html
            );
        }

        return response()->json(['otp_sent' => true]);
    }

    public function verifyOtp(Request $request)
    {
        $rules = [
            'otp' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $tempId = session()->get('admin_login_temp_id');
        $savedOtp = session()->get('admin_login_otp');
        $expiresAt = session()->get('admin_login_otp_expires_at');
        $remember = session()->get('admin_login_remember');

        if (!$tempId || !$savedOtp || !$expiresAt) {
            return response()->json(array('errors' => [ 0 => 'Session expired. Please try logging in again.' ]));
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['admin_login_temp_id', 'admin_login_otp', 'admin_login_otp_expires_at', 'admin_login_remember']);
            return response()->json(array('errors' => [ 0 => 'OTP has expired. Please try logging in again.' ]));
        }

        if ($request->otp == $savedOtp) {
            Auth::guard('admin')->loginUsingId($tempId, $remember);

            // Set login timestamp for 24-hour auto logout
            session(['admin_login_time' => time()]);

            session()->forget(['admin_login_temp_id', 'admin_login_otp', 'admin_login_otp_expires_at', 'admin_login_remember']);

            return response()->json(route('admin.dashboard'));
        } else {
            return response()->json(array('errors' => [ 0 => 'Invalid OTP code. Please check and try again.' ]));
        }
    }

    public function showForgotForm()
    {
      return view('admin.forgot');
    }

    public function forgot(Request $request)
    {
      $gs = GeneralSettings::findOrFail(1);
      $input =  $request->all();

      if (Admin::where('email', '=', $request->email)->count() > 0) {
        // user found
        $admin = Admin::where('email', '=', $request->email)->firstOrFail();
        $autopass = Str::random(8);
        $input['password'] = bcrypt($autopass);
        $admin->update($input);
        $subject = "Reset Password Request";
        $msg = "Your New Password is : ".$autopass;
        if($gs->is_smtp == 1)
        {
            $data = [
                    'to' => $request->email,
                    'subject' => $subject,
                    'body' => $msg,
            ];
  
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);                
        }
        else
        {
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($request->email,$subject,$msg,$headers);            
        }
        return response()->json('Your Password Reseted Successfully. Please Check your email for new Password.');
        }
        else{
        // user not found
        return response()->json(array('errors' => [ 0 => 'No Account Found With This Email.' ]));    
        }  
    }
}
