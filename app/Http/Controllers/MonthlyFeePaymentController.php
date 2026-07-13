<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EpsGateway;
use App\Models\Fee;
use App\Models\User;
use App\Models\MonthlyFeePayment;
use Carbon\Carbon;

class MonthlyFeePaymentController extends Controller
{
    public function pay(EpsGateway $eps)
    {
        $user = auth()->user();

        $fee = Fee::first();

        if (!$fee) {
            return back()->with('error', 'Fee configuration not found.');
        }

        $transactionId = 'MF' . now()->format('YmdHis') . rand(1000, 9999);

        MonthlyFeePayment::create([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $fee->rep_monthly_fee,
            'status' => 'pending',
        ]);

        $payment = $eps->initialize([
            'order_id' => $transactionId,
            'transaction_id' => $transactionId,
            'amount' => $fee->rep_monthly_fee,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '',
            'customer_address' => 'Dhaka',
            'customer_city' => 'Dhaka',
            'customer_state' => 'Dhaka',
            'customer_postcode' => '1200',
            'product_name' => 'Reporter Monthly Fee',
        ]);

        $redirectUrl = data_get($payment, 'RedirectURL');

        return $redirectUrl
            ? redirect()->away($redirectUrl)
            : back()->with('error', 'Payment initialization failed.');
    }

    public function success(Request $request, EpsGateway $eps)
{
    // 🔥 READ RAW BODY (CRITICAL FIX)
    $raw = file_get_contents("php://input");

    $data = json_decode($raw, true);

    // fallback if not JSON
    if (!is_array($data)) {
        $data = $request->all();
    }

    \Log::info('EPS RAW FINAL DEBUG', [
        'request_all' => $request->all(),
        'raw_body' => $raw,
        'parsed' => $data
    ]);

    // 🔥 FIXED EXTRACTION (CASE SAFE)
    $transactionId =
        $data['MerchantTransactionId']
        ?? $data['merchantTransactionId']
        ?? $data['TransactionId']
        ?? $data['transactionId']
        ?? null;

    if (!$transactionId) {
        return response()->json([
            'error' => true,
            'message' => 'Transaction ID missing',
            'data' => $data
        ]);
    }

    // 🔥 FIND PAYMENT
    $payment = MonthlyFeePayment::where('transaction_id', $transactionId)->first();

    if (!$payment) {
        return response()->json([
            'error' => true,
            'message' => 'Payment record not found',
            'transaction_id' => $transactionId
        ]);
    }

    try {
        $verify = $eps->verify($transactionId);

        $status = strtolower($data['Status'] ?? $verify['Status'] ?? '');

        if ($status === 'success') {

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'gateway_response' => json_encode($data),
            ]);

            $user = $payment->user;

            if ($user) {
                $user->next_payment_date = $user->next_payment_date
                    ? Carbon::parse($user->next_payment_date)->addMonth()
                    : now()->addMonth();

                $user->save();
            }

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Payment successful');
        }

        $payment->update([
            'status' => 'failed',
            'gateway_response' => json_encode($data),
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with('error', 'Payment failed');

    } catch (\Exception $e) {

        \Log::error('EPS VERIFY ERROR', [
            'error' => $e->getMessage()
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with('error', $e->getMessage());
    }
}

    public function fail(Request $request)
    {
        $transactionId =
            $request->post('MerchantTransactionId')
            ?? $request->input('MerchantTransactionId');

        MonthlyFeePayment::where('transaction_id', $transactionId)
            ->update(['status' => 'failed']);

        return redirect()
            ->route('user.dashboard')
            ->with('error', 'Payment failed.');
    }

    public function cancel(Request $request)
    {
        $transactionId =
            $request->post('MerchantTransactionId')
            ?? $request->input('MerchantTransactionId');

        MonthlyFeePayment::where('transaction_id', $transactionId)
            ->update(['status' => 'cancelled']);

        return redirect()
            ->route('user.dashboard')
            ->with('error', 'Payment cancelled.');
    }
}