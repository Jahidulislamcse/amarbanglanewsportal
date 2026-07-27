<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStatusMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {

            $admin = Auth::guard('admin')->user(); // from admins table

            if ($admin->status == 0) {
                Auth::guard('admin')->logout();
                session()->forget('admin_login_time');

                return redirect()->route('admin.loginForm')
                    ->with('error', 'Your account is inactive.');
            }

            // Auto logout check (8 hours = 28800 seconds for division admin, 24 hours = 86400 for others)
            if (session()->has('admin_login_time')) {
                $loginTime = session('admin_login_time');
                $maxSessionTime = ($admin->role_id == 4) ? 28800 : 86400;
                if (time() - $loginTime > $maxSessionTime) {
                    if ($admin->role_id == 4) {
                        $admin->update(['in_charge' => false]);
                    }
                    Auth::guard('admin')->logout();
                    session()->forget('admin_login_time');
                    $msg = ($admin->role_id == 4)
                        ? 'Your session has expired after 8 hours. Please log in again.'
                        : 'Your session has expired after 24 hours. Please log in again.';
                    return redirect()->route('admin.loginForm')->with('error', $msg);
                }
            } else {
                // Set initial login time for existing sessions
                session(['admin_login_time' => time()]);
            }
        }

        return $next($request);
    }
}
