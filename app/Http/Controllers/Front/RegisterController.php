<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache;
use App\Services\BrevoMailService;
use App\Models\Fee;
use App\Models\UserOthersInfo;
use App\Models\GeneralSettings;
use App\Models\Role;
use App\Models\Language;
use Toastr;
use Auth;
use App\Classes\GeniusMailer;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;

use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function login(){
        $this->code_image();
        return view('frontend.login');
    }
	
	   
    public function selectRegistrationType()
    {

        $this->code_image();
        return view('frontend.register_select');
    }
       	 
   	public function demo(){
       dd('hi');
    }

   
   	 public function registerReader(){
       
        return view('frontend.registration');
    }
    
    
	 public function registration(){
        $this->code_image();
        return view('frontend.registration');
    }
    
    
	public function getDivisions(Request $request) {
	   $divisions = is_division($request->get('article_language_id'));
       return response()->json($divisions);
    }
	public function getDistricts(Request $request) {
		
	
		$division_id = $request->get('division_id');
		
		 if(session()->has('language')){
            $default_language = Language::find(session()->get('language'));
        }else{

            $default_language = Language::where('is_default',1)->first();
        }
		
		if($request->get('article_language_id') && $request->get('article_language_id')>0){
			  $districts = is_district($request->get('article_language_id'),$division_id);
		}else{
			 $districts = is_district($default_language->id,$division_id);
		}
       
	
        return response()->json($districts);
    }

    public function getThanas(Request $request) {
		$district_id = $request->get('district_id');
		
		 if(session()->has('language')){
            $default_language = Language::find(session()->get('language'));
        }else{

            $default_language = Language::where('is_default',1)->first();
        }
		
		if($request->get('article_language_id') && $request->get('article_language_id')>0){
			 $thanas = is_thana($request->get('article_language_id'),$district_id);
		}else{
			 $thanas = is_thana($default_language->id,$district_id);
		}
		
		 

        return response()->json($thanas);
    }

    public function getUnions(Request $request) {
		$thana_id = $request->get('thana_id');
		 if(session()->has('language')){
            $default_language = Language::find(session()->get('language'));
        }else{

            $default_language = Language::where('is_default',1)->first();
        }
		
		if($request->get('article_language_id') && $request->get('article_language_id')>0){
			  $unions = is_union($request->get('article_language_id'),$thana_id);
		}else{
			 $unions = is_union($default_language->id,$thana_id);
		}
		

        return response()->json($unions);
    }
	
	
	 public function LogReg(){
        $this->code_image();
        return view('frontend.log-reg');
    }
    
    public function sendOtp(Request $request)
    {
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if ($phone !== '' && substr($phone, 0, 1) == "0") {
            $phone = "88" . $phone;
        }
        $request->merge(['phone' => $phone]);

        $gs = GeneralSettings::findOrFail(1);
    
        if($gs->is_capcha == 1)
        {
            $rules=[
                'name'=> 'required',
                'phone'=> 'required|unique:users',
                'email'=> 'required|email|unique:users',
                'address'=> 'required',
                'father_name'=> 'required',
                'mother_name'=> 'required',
                'eduaction'=> 'required',
                'education_year'=> 'required',
                'nid_no'=> 'required',
                'dob'=> 'required',
                'blood'=> 'required',
                'division_id'=> 'required',
                'district_id'=> 'required',
                'thana_id'=> 'required',
                'union_id'=> 'nullable',
                'password'=> 'required|min:4|confirmed',
                'report_type'=> 'required',
                'reporter_area'=> 'required',
                'has_experience' => 'required|in:0,1',
                'experience_organization' => 'required_if:has_experience,1|max:255',
                'experience_designation' => 'required_if:has_experience,1|max:255',
                'experience' => 'nullable|string',
                'otp_via' => 'required|in:phone,email',
                'g-recaptcha-response' => 'required|captcha',
                'nid' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
                'nid_back' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
                'signature' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
                'photo' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048'
            ];
        }
        else
        {
            $rules=[
                'name'=> 'required',
                'phone'=> 'required|unique:users',
                'email'=> 'required|email|unique:users',
                'address'=> 'required',
                'father_name'=> 'required',
                'mother_name'=> 'required',
                'eduaction'=> 'required',
                'education_year'=> 'required',
                'nid_no'=> 'required',
                'dob'=> 'required',
                'blood'=> 'required',
                'division_id'=> 'required',
                'district_id'=> 'required',
                'thana_id'=> 'required',
                'union_id'=> 'nullable',
                'password'=> 'required|min:4|confirmed',
                'report_type'=> 'required',
                'reporter_area'=> 'required',
                'otp_via' => 'required|in:phone,email',
                'nid' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
                'nid_back' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
                'signature' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
                'photo' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048'
            ];
        }
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json([
                'errors' => ['email' => ['This email is already registered.']]
            ]);
        }

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if ($phone !== '' && substr($phone, 0, 1) == "0") {
            $phone = "88" . $phone;
        }
        $phone13 = $phone;
        $phone11 = str_starts_with($phone, '88') ? substr($phone, 2) : $phone;
        $phoneExists = \App\Models\User::where('phone', $phone13)
            ->orWhere('phone', $phone11)
            ->orWhere('phone', 'like', '%' . $phone11)
            ->exists();

        if ($phoneExists) {
            return response()->json([
                'errors' => ['phone' => ['This phone number is already registered.']]
            ]);
        }
    
        $otp = rand(1000,9999);
    
        $data = $request->except([
            '_token',
            'password_confirmation',
            'otp_via',
            'nid',
            'nid_back',
            'photo',
            'signature'
        ]);
        
        $data['plain_password'] = $request->password;
    
        if ($file = $request->file('nid')) {
    
            $nidName =
                uniqid().'_nid.'.
                $file->getClientOriginalExtension();
    
            $file->move(
                storage_path('app/temp'),
                $nidName
            );
    
            $data['nid_temp'] = $nidName;
        }

        if ($file = $request->file('nid_back')) {

            $nidBackName =
                uniqid().'_nid_back.'.
                $file->getClientOriginalExtension();

            $file->move(
                storage_path('app/temp'),
                $nidBackName
            );

            $data['nid_back_temp'] = $nidBackName;
        }
    
        if ($file = $request->file('photo')) {
    
            $photoName =
                uniqid().'_photo.'.
                $file->getClientOriginalExtension();
    
            $file->move(
                storage_path('app/temp'),
                $photoName
            );
    
            $data['photo_temp'] = $photoName;
        }
    
        if ($file = $request->file('signature')) {
    
            $signatureName =
                uniqid().'_signature.'.
                $file->getClientOriginalExtension();
    
            $file->move(
                storage_path('app/temp'),
                $signatureName
            );
    
            $data['signature_temp'] = $signatureName;
        }
        
        $referrer = null;

        if ($request->filled('ref')) {
        
            $referrer = User::where('affilate_code', $request->ref)->first();
        
            $data['referrer_code'] = $request->ref;
            $data['referred_by'] = $referrer?->id;
        }
    
        Cache::put(
            'register_otp_'.$request->email,
            [
                'otp' => $otp,
                'data' => $data
            ],
            now()->addMinutes(10)
        );
    
        $userPhone = preg_replace('/[^0-9]/', '', $request->phone);
    
        if (substr($userPhone, 0, 1) == "0") {
            $userPhone = "88" . $userPhone;
        }
    
        if($request->otp_via == 'phone')
        {
            $message =
            "Your OTP for Amar Bangla 24 registration is: {$otp}. Valid for 10 minutes.";
    
            (new SmsService())->send(
                $userPhone,
                $message
            );
        }
        else
        {
            $html = "
                <h2>Amar Bangla 24 - OTP Verification</h2>
                <h1>{$otp}</h1>
                <p>This OTP is valid for 10 minutes.</p>
            ";
    
            BrevoMailService::send(
                $request->email,
                $request->name,
                'Registration OTP',
                $html
            );
        }
    
        return response()->json([
            'otp_sent' => true,
            'contact' => $request->email
        ]);
    }
    
    public function verifyOtp(Request $request)
    {
        $cacheKey = 'register_otp_'.$request->contact;
    
        $cached = Cache::get($cacheKey);
    
        if(!$cached){
            return response()->json([
                'error' => 'OTP expired or invalid'
            ]);
        }
    
        if($cached['otp'] != $request->otp){
            return response()->json([
                'error' => 'Invalid OTP'
            ]);
        }
    
        $input = $cached['data'];
        
        if (\App\Models\User::where('email', $input['email'])->exists()) {
            return response()->json(['error' => 'This email is already registered']);
        }
        $phone = preg_replace('/[^0-9]/', '', $input['phone']);
        if ($phone !== '' && substr($phone, 0, 1) == "0") {
            $phone = "88" . $phone;
        }
        $phone13 = $phone;
        $phone11 = str_starts_with($phone, '88') ? substr($phone, 2) : $phone;
        $phoneExists = \App\Models\User::where('phone', $phone13)
            ->orWhere('phone', $phone11)
            ->orWhere('phone', 'like', '%' . $phone11)
            ->exists();
        if ($phoneExists) {
            return response()->json(['error' => 'This phone number is already registered']);
        }
    
        $plainPassword = $input['plain_password'];
    
        unset($input['plain_password']);
    
        $input['password'] = bcrypt($plainPassword);
    
        $input['token'] =
            md5(time().$input['name'].$input['email']);
    
        $input['report_type'] =
            json_encode($input['report_type']);
    
        $input['verified'] = 1;
        $input['email_verified'] = 'Yes';
    
        $referrer = null;
    
        if(!empty($input['referrer_code']))
        {
            $referrer = User::where(
                'affilate_code',
                $input['referrer_code']
            )->first();
    
            $input['referred_by'] =
                $referrer->id ?? null;
        }
    
        $input['affilate_code'] =
            $this->generateAffiliateCode();
    
        if(!empty($input['nid_temp']))
        {
            $nidName = time().'_'.$input['nid_temp'];
            $oldPath = storage_path('app/temp/'.$input['nid_temp']);
            $newPath = public_path('assets/images/admin/'.$nidName);
            if (file_exists($oldPath)) {
                try {
                    if (!file_exists(dirname($newPath))) {
                        mkdir(dirname($newPath), 0777, true);
                    }
                    rename($oldPath, $newPath);
                } catch (\Exception $e) {
                    \Log::error("Failed to move NID file: " . $e->getMessage());
                }
            }
            $input['nid'] = $nidName;
            unset($input['nid_temp']);
        }

        if(!empty($input['nid_back_temp']))
        {
            $nidBackName = time().'_'.$input['nid_back_temp'];
            $oldPath = storage_path('app/temp/'.$input['nid_back_temp']);
            $newPath = public_path('assets/images/admin/'.$nidBackName);
            if (file_exists($oldPath)) {
                try {
                    if (!file_exists(dirname($newPath))) {
                        mkdir(dirname($newPath), 0777, true);
                    }
                    rename($oldPath, $newPath);
                } catch (\Exception $e) {
                    \Log::error("Failed to move NID Back file: " . $e->getMessage());
                }
            }
            $input['nid_back'] = $nidBackName;
            unset($input['nid_back_temp']);
        }
    
        if(!empty($input['photo_temp']))
        {
            $photoName = time().'_'.$input['photo_temp'];
            $oldPath = storage_path('app/temp/'.$input['photo_temp']);
            $newPath = public_path('assets/images/admin/'.$photoName);
            if (file_exists($oldPath)) {
                try {
                    if (!file_exists(dirname($newPath))) {
                        mkdir(dirname($newPath), 0777, true);
                    }
                    rename($oldPath, $newPath);
                } catch (\Exception $e) {
                    \Log::error("Failed to move Photo file: " . $e->getMessage());
                }
            }
            $input['photo'] = $photoName;
            unset($input['photo_temp']);
        }
    
        
        if (empty($input['has_experience'])) {

            $input['experience_organization'] = null;
            $input['experience_designation'] = null;
            $input['experience'] = null;
        }
    
    
        $signatureTemp = $input['signature_temp'] ?? null;
        unset($input['signature_temp']);

        $author = new User();
        $author->fill($input)->save();

        if (!empty($signatureTemp)) {
            $oldPath = storage_path('app/temp/' . $signatureTemp);
            $signatureName = $author->id . '.png';
            $newPath = public_path('assets/images/admin/' . $signatureName);
            if (file_exists($oldPath)) {
                try {
                    if (!file_exists(dirname($newPath))) {
                        mkdir(dirname($newPath), 0777, true);
                    }
                    rename($oldPath, $newPath);
                } catch (\Exception $e) {
                    \Log::error("Failed to move Signature file: " . $e->getMessage());
                }
            }
        }
    
        if($referrer)
        {
            $fees = Fee::first();
    
            if($fees && $referrer->views > 9)
            {
                $referrer->increment(
                    'referral_earning',
                    $fees->common_reffer_commission
                );
    
                $referrer->increment(
                    'balance',
                    $fees->common_reffer_commission
                );
            }
        }
    
        UserOthersInfo::create([
            'user_id' => $author->id,
            'password' => $plainPassword
        ]);
    
        $userPhone = preg_replace('/[^0-9]/', '', $author->phone);
    
        if (substr($userPhone, 0, 1) == "0") {
            $userPhone = "88".$userPhone;
        }
    
        $message =
        "Welcome, {$author->name}! Your registration on আমার বাংলা 24 has been successfully completed. You will be notified once your information has been verified.";
    
        (new SmsService())->send(
            $userPhone,
            $message
        );
    
        Cache::forget($cacheKey);
    
        Auth::guard('web')->login($author);
    
        session()->flash('registration_success_popup', true);

        return response()->json([
            'success' => true,
            'url' => route('user.dashboard')
        ]);
    }
    
    private function generateAffiliateCode()
    {
        do {
    
            $code = mt_rand(
                10000000,
                99999999
            );
    
        } while(
            User::where(
                'affilate_code',
                $code
            )->exists()
        );
    
        return $code;
    }

    public function register(Request $request, SmsService $sms){
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if ($phone !== '' && substr($phone, 0, 1) == "0") {
            $phone = "88" . $phone;
        }
        $request->merge(['phone' => $phone]);

        $gs = GeneralSettings::findOrFail(1);

    	if($gs->is_capcha == 1)
    	{
            $rules=[
                'name'=> 'required',
				'phone'=> 'required|unique:users',
                'email'=> 'required|email|unique:users',
				'address'=> 'required',
				'father_name'=> 'required',
				'mother_name'=> 'required',
				'eduaction'=> 'required',
				'education_year'=> 'required',
				'nid_no'=> 'required',
				'dob'=> 'required',
				'blood'=> 'required',
				'division_id'=> 'required',
				'district_id'=> 'required',
				'thana_id'=> 'required',
				'union_id'=> 'nullable',
                'password'=> 'required|min:4|confirmed',
				'report_type'=> 'required',
				'reporter_area'=> 'required',
                'g-recaptcha-response' => 'required|captcha',
				'nid' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
				'nid_back' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
				'signature' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
				'photo' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048'
            ];
        }
        else
        {
			
            $rules=[
			    'name'=> 'required',
				'phone'=> 'required|unique:users',
                'email'=> 'required|email|unique:users',
				'address'=> 'required',
				'father_name'=> 'required',
				'mother_name'=> 'required',
				'eduaction'=> 'required',
				'education_year'=> 'required',
				'nid_no'=> 'required',
				'dob'=> 'required',
				'blood'=> 'required',
				'division_id'=> 'required',
				'district_id'=> 'required',
				'thana_id'=> 'required',
				'union_id'=> 'nullable',
                'password'=> 'required|min:4|confirmed',
				'report_type'=> 'required',
				'reporter_area'=> 'required',
				'nid' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
				'nid_back' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
				'signature' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048',
				'photo' => 'required|image|mimes:jpeg,jpg,png,svg|max:2048'
				
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json([
                'errors' => ['email' => ['This email is already registered.']]
            ]);
        }

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if ($phone !== '' && substr($phone, 0, 1) == "0") {
            $phone = "88" . $phone;
        }
        $phone13 = $phone;
        $phone11 = str_starts_with($phone, '88') ? substr($phone, 2) : $phone;
        $phoneExists = \App\Models\User::where('phone', $phone13)
            ->orWhere('phone', $phone11)
            ->orWhere('phone', 'like', '%' . $phone11)
            ->exists();

        if ($phoneExists) {
            return response()->json([
                'errors' => ['phone' => ['This phone number is already registered.']]
            ]);
        }

        $gs = GeneralSettings::findOrFail(1);
        $author  = new User();
        $input = $request->all();
        $input['password'] =bcrypt($request['password']);
        $input['token'] = md5(time().$request->name.$request->email);
		$input['report_type'] =json_encode($request->report_type);
		
		$input['verified'] = 1;
		$input['email_verified'] ='Yes' ;
		
        $token  = $input['token'];
		
		if ($file = $request->file('nid'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            $input['nid'] = $name;
        }

		if ($file = $request->file('nid_back'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            $input['nid_back'] = $name;
        }
		
		if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            //@unlink('assets/images/admin/'.$data->photo);
            $input['photo'] = $name;
        }

        $author->fill($input)->save();
	  

			if ($file = $request->file('signature'))
			{
				$insertedId = $author->id;
				$customExtension = 'png';
				$name = $insertedId .  '.' . $customExtension;
				$file->move('assets/images/admin/',$name);
			}
          if ($author) {
                $userPhone = preg_replace('/[^0-9]/', '', $request->phone); 
                if (substr($userPhone, 0, 1) == "0") {
                    $userPhone = "88" . $userPhone;
                }
            
               $message = "Welcome, {$request->name}! Your registration on আমার বাংলা 24 has been successfully completed. You will be notified once your information has been verified.";
            
                $smsService = new \App\Services\SmsService();
                $smsService->send($userPhone, $message);
                    
             Auth::guard('web')->login($author);
             session()->flash('registration_success_popup', true);
             $data['succes']=1;
    		 $data['url']=route('user.dashboard');
    		  return response()->json($data);
          }
          else{
			  $data['succes']=0;
			  $data['url']="";
              return response()->json($data);
          }

        /*if($gs->is_verification_email == 1)
        {
            $to = $request->email;
            $subject = 'Verify your email address.';
            $msg = "Dear Customer,<br> We noticed that you need to verify your email address. <a href=".url('register/verify/'.$token).">Simply click here to verify. </a>";
            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            }
            else
            {
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to,$subject,$msg,$headers);
            }
            return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address.');
        }*/

    }

    public function token($token)
    {
          $user = User::where('token',$token)->first();
          if($user){
              $user->status = 1;
              $user->verify = 1;
              $user->token  = NULL;
              $user->update();

              Auth::guard('web')->login($user);
              Toastr::success('You are welcome!','success');
              return redirect()->route('frontend.index');
          }
          else{
              Toastr::error('Token mismatch!','error');
              return redirect('/');
          }
    }

    private function  code_image()
    {
        $actual_path = str_replace('project','',base_path());
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image,0,0,200,50,$background_color);

        $pixel = imagecolorallocate($image, 0,0,255);
        for($i=0;$i<500;$i++)
        {
            imagesetpixel($image,rand()%200,rand()%50,$pixel);
        }

        $font = $actual_path.'assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length-1)];
        $word='';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length=6;// No. of character in image
        for ($i = 0; $i< $cap_length;$i++)
        {
            $letter = $allowed_letters[rand(0, $length-1)];
            imagettftext($image, 25, 1, 35+($i*25), 35, $text_color, $font, $letter);
            $word.=$letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for($i=0;$i<500;$i++)
        {
            imagesetpixel($image,rand()%200,rand()%50,$pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, $actual_path."assets/images/capcha_code.png");
    }
}
