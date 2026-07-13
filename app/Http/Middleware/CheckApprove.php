<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApprove
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_approve != 1) {
            return redirect()->back()->with('error', 'You are not approved yet.');
        }

        return $next($request);
    }
}
