<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function send($to, $message)
    {
        try {
            return Http::asForm()->timeout(5)->post(env('SMS_API_URL'), [
                'api_key'  => env('SMS_API_KEY'),
                'type'     => 'text',
                'number'   => $to,
                'senderid' => env('SMS_SENDER_ID'),
                'message'  => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }
}
