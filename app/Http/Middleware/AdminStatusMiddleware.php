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

            // Auto logout check (24 hours = 86400 seconds)
            if (session()->has('admin_login_time')) {
                $loginTime = session('admin_login_time');
                if (time() - $loginTime > 86400) {
                    Auth::guard('admin')->logout();
                    session()->forget('admin_login_time');
                    return redirect()->route('admin.loginForm')->with('error', 'Your session has expired after 24 hours. Please log in again.');
                }
            } else {
                // Set initial login time for existing sessions
                session(['admin_login_time' => time()]);
            }

            $admin = Auth::guard('admin')->user(); // from admins table

            if ($admin->status == 0) {
                Auth::guard('admin')->logout();
                session()->forget('admin_login_time');

                return redirect()->route('admin.loginForm')
                    ->with('error', 'Your account is inactive.');
            }
        }

        return $next($request);
    }
}
