<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class CheckPaymentStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->next_payment_date && now()->greaterThan($user->next_payment_date)) {

            $reporterDivisionIds = $user->division_id ? [$user->division_id] : [];

            $admins = Admin::where('role_id', 4)
                ->whereHas('divisions', function ($q) use ($reporterDivisionIds) {
                    $q->whereIn('division_id', $reporterDivisionIds);
                })
                ->get(['id', 'name', 'phone']);

            return response()->view('user.deactivated', [
                'admins' => $admins
            ]);
        }

        return $next($request);
    }
}
