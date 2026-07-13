<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Services\BrevoMailService;
use App\Services\SmsService;

class ForgotController extends Controller
{
    public function showforgotform()
    {
        return view('frontend.forgot');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'login' => 'required'
        ]);

        $login = trim($request->login);

        $user = User::where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$user) {
            return response()->json([
                'errors' => ['User not found']
            ], 422);
        }

        $otp = rand(1000, 9999);

        Cache::put('forgot_otp_' . $user->id, [
            'otp' => $otp
        ], now()->addMinutes(10));

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            $html = "
                <h2>Amar Bangla 24 - Password Reset OTP</h2>
                <p>Your OTP is:</p>
                <h1 style='letter-spacing:5px'>{$otp}</h1>
                <p>Valid for 10 minutes.</p>
            ";

            BrevoMailService::send(
                $user->email,
                $user->name,
                'Password Reset OTP',
                $html
            );

        } else {

            $phone = preg_replace('/[^0-9]/', '', $user->phone);

            if (substr($phone, 0, 1) == "0") {
                $phone = "88" . $phone;
            }

            $message = "Your OTP for password reset is: {$otp}. Valid for 10 minutes.";

            (new SmsService())->send($phone, $message);
        }

        return response()->json([
            'otp_sent' => true,
            'message' => 'OTP sent successfully'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'login' => 'required',
            'otp' => 'required',
            'password' => 'required|min:4|confirmed',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }
    
        $login = trim($request->login);
    
        $user = User::where('email', $login)
            ->orWhere('phone', $login)
            ->first();
    
        if (!$user) {
            return response()->json([
                'errors' => ['User not found']
            ], 422);
        }
    
        $cache = Cache::get('forgot_otp_' . $user->id);
    
        if (!$cache || $cache['otp'] != $request->otp) {
            return response()->json([
                'errors' => ['Invalid OTP']
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        \App\Models\UserOthersInfo::updateOrCreate(
            ['user_id' => $user->id],
            ['password' => $request->password]
        );
    
        Cache::forget('forgot_otp_' . $user->id);
    
        return response()->json([
            'message' => 'Password reset successful'
        ]);
    }
}