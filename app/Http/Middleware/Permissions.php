<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$data)
    {
        if(Auth::guard('admin')->check()){
            $user = Auth::guard('admin')->user();
            if($user->id == 1){
                return $next($request);
            }
            $sections = $user->role ? json_decode($user->role->section, true) : null;
            \Log::info("Permissions checking data: " . $data . " for admin " . $user->name . " (role_id: " . $user->role_id . "). Role section raw: " . ($user->role ? $user->role->section : 'null') . ", Decoded: " . json_encode($sections) . ", Check result: " . ($user->sectionCheck($data) ? 'true' : 'false'));
            if ($data === 'user_management' && $user->sectionCheck('reporter_approve')) {
                return $next($request);
            }
            if($user->sectionCheck($data)){
                return $next($request);
            }
        }
        return redirect()->route('admin.dashboard')->with('unsuccess',"You don't have access to the " . str_replace('_', ' ', $data) . " section"); 
    }
}
