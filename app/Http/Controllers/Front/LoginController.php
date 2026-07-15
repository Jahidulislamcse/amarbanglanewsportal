<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $gs = GeneralSettings::findOrFail(1);
    
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    
        $loginField = trim($request->email);
        $password = $request->password;
    
        $user = null;
    
        // EMAIL LOGIN
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
    
            $user = \App\Models\User::where('email', $loginField)->first();
    
        } else {
    
            // PHONE LOGIN (normalize last 11 digits)
            $phone = preg_replace('/[^0-9]/', '', $loginField);
            $phone = substr($phone, -11);
    
            $user = \App\Models\User::whereRaw("RIGHT(phone, 11) = ?", [$phone])->first();
        }
    
        if (!$user || !\Hash::check($password, $user->password)) {
            return response()->json(['errors' => ["Credentials Doesn't Match !"]]);
        }
    
        if ($user->verified == 0) {
            return response()->json(['errors' => ['Your Email is not Verified!']]);
        }

        if ($user->is_ban == 1) {
            return response()->json(['errors' => ['Your account is disabled! Please contact authority.']]);
        }
    
        Auth::login($user, $request->remember);
    
        if ($user->is_reader == 1) {
            return response()->json(route('reader.dashboard'));
        }
    
        return response()->json(route('user.dashboard'));
    }


    public function logout(){
        auth()->logout();
        return redirect('/');
    }
}
