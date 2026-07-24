<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Fee;
use App\Models\UserOthersInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Services\BrevoMailService;
use App\Services\SmsService;

class ReaderController extends Controller
{
    public function registerReader(Request $request)
    {
        $ref = $request->ref;
        return view('frontend.registration_reader', compact('ref'));
    }

    public function register(Request $request)
    {
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if ($phone !== '' && substr($phone, 0, 1) == "0") {
            $phone = "88" . $phone;
        }
        $request->merge(['phone' => $phone]);

        $rules = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|regex:/^[0-9\-\+\s\(\)]+$/|unique:users,phone',
            'otp_via'  => 'required|in:email,phone',
            'password' => 'required|min:4|confirmed',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            if (User::where('email', $request->email)->exists()) {
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
            $phoneExists = User::where('phone', $phone13)
                ->orWhere('phone', $phone11)
                ->orWhere('phone', 'like', '%' . $phone11)
                ->exists();

            if ($phoneExists) {
                return response()->json([
                    'errors' => ['phone' => ['This phone number is already registered.']]
                ]);
            }
        
            $userPhone = preg_replace('/[^0-9]/', '', $request->phone);
            if (substr($userPhone, 0, 1) == "0") {
                $userPhone = "88" . $userPhone;
            }
        
            $otp = rand(1000, 9999);
        
            Cache::put('reader_reg_' . $request->email, [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $userPhone,
                'plain_password' => $request->password,
                'password' => bcrypt($request->password),
                'otp' => $otp,
                'referrer_code' => $request->referrer_code
            ], now()->addMinutes(10));
        
            if ($request->otp_via === 'phone') {
        
                $message = "Your OTP for আমার বাংলা 24 registration is: {$otp}. Valid for 10 minutes.";
                $smsSent = (new SmsService())->send($userPhone, $message);
                if ($smsSent === false) {
                    throw new \Exception("SMS gateway error occurred when sending verification code.");
                }
        
            } else {
        
                $html = "
                    <h2>Amar Bangla 24 - OTP Verification</h2>
                    <p>Your OTP for registration is:</p>
                    <h1 style='letter-spacing:5px'>{$otp}</h1>
                    <p>This OTP is valid for 10 minutes.</p>
                ";
        
                $mailSent = BrevoMailService::send(
                    $request->email,
                    $request->name,
                    'Your Registration OTP',
                    $html
                );

                if (!$mailSent || !is_string($mailSent) || strpos($mailSent, 'error') !== false || strpos($mailSent, 'unauthorized') !== false) {
                    throw new \Exception("Email dispatch failed. Please verify your email configuration or try SMS verification.");
                }
            }
        
            return response()->json([
                'otp_sent' => true,
                'contact' => $request->email
            ]);
        } catch (\Throwable $e) {
            \Log::error("Reader OTP Send Exception: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            $message = $e->getMessage();
            $userFriendlyMessage = "System error during registration request. (Details: " . $message . ")";

            if (stripos($message, 'sms') !== false) {
                $userFriendlyMessage = "We are experiencing issues sending verification SMS. Please try selecting Email verification instead. (Details: " . $message . ")";
            } elseif (stripos($message, 'email') !== false || stripos($message, 'mail') !== false || stripos($message, 'brevo') !== false || stripos($message, 'smtp') !== false) {
                $userFriendlyMessage = "We are experiencing issues sending verification Email. Please try selecting Phone/SMS verification instead. (Details: " . $message . ")";
            } elseif (stripos($message, 'database') !== false || stripos($message, 'sql') !== false || stripos($message, 'connection') !== false) {
                $userFriendlyMessage = "Our server database is temporarily busy. Please wait a moment and try again. (Details: " . $message . ")";
            }

            return response()->json([
                'error' => $userFriendlyMessage
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $fees = Fee::first();
        
            // Cache key is EMAIL (set during register)
            $cacheKey = 'reader_reg_' . $request->contact;
            $cachedData = Cache::get($cacheKey);
        
            if (!$cachedData) {
                return response()->json(['error' => 'OTP expired or invalid']);
            }
        
            if ($cachedData['otp'] != $request->otp) {
                return response()->json(['error' => 'Invalid OTP']);
            }
    
            if (User::where('email', $cachedData['email'])->exists()) {
                return response()->json(['error' => 'This email is already registered']);
            }
            $phone = preg_replace('/[^0-9]/', '', $cachedData['phone']);
            if ($phone !== '' && substr($phone, 0, 1) == "0") {
                $phone = "88" . $phone;
            }
            $phone13 = $phone;
            $phone11 = str_starts_with($phone, '88') ? substr($phone, 2) : $phone;
            $phoneExists = User::where('phone', $phone13)
                ->orWhere('phone', $phone11)
                ->orWhere('phone', 'like', '%' . $phone11)
                ->exists();
            if ($phoneExists) {
                return response()->json(['error' => 'This phone number is already registered']);
            }
        
            $referrer = null;
            if (!empty($cachedData['referrer_code'])) {
                $referrer = User::where('affilate_code', $cachedData['referrer_code'])->first();
        
                if ($referrer && $fees && $referrer->views > 9) {
                    $referrer->increment('referral_earning', $fees->common_reffer_commission);
                    $referrer->increment('balance', $fees->common_reffer_commission);
                }
            }
        
            $user = User::create([
                'name' => $cachedData['name'],
                'email' => $cachedData['email'],
                'phone' => $cachedData['phone'],
                'password' => $cachedData['password'],
                'verified' => 1,
                'is_reader' => 1,
                'email_verified' => 'Yes',
                'affilate_code' => $this->generateAffiliateCode(),
                'referred_by' => $referrer->id ?? null,
            ]);
            
            UserOthersInfo::create([
                'user_id' => $user->id,
                'password' => $cachedData['plain_password'],
            ]);
        
            Cache::forget($cacheKey);
            Auth::guard('web')->login($user);
        
            return response()->json([
                'success' => true,
                'url' => route('reader.dashboard')
            ]);
        } catch (\Throwable $e) {
            \Log::error("Reader OTP Verify Exception: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            $message = $e->getMessage();
            $userFriendlyMessage = "System error during OTP verification. (Details: " . $message . ")";

            if (stripos($message, 'database') !== false || stripos($message, 'sql') !== false || stripos($message, 'connection') !== false) {
                $userFriendlyMessage = "Our server database is temporarily busy. Please wait a moment and try again. (Details: " . $message . ")";
            }

            return response()->json([
                'error' => $userFriendlyMessage
            ]);
        }
    }

    private function generateAffiliateCode()
    {
        do {
            $code = mt_rand(10000000, 99999999);
        } while (User::where('affilate_code', $code)->exists());

        return $code;
    }

    public function login()
    {
        return view('frontend.reader_login');
    }
}