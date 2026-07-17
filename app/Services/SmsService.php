<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function send($to, $message)
    {
        try {
            // Clean phone number: remove non-digit characters
            $to = preg_replace('/[^0-9]/', '', $to);

            // Prefix with country code '88' if it starts with '01' (11 digits)
            if (strlen($to) === 11 && strpos($to, '01') === 0) {
                $to = '88' . $to;
            }

            $url = env('SMS_API_URL') ?: 'http://isms.digitalsquare.ltd:5683/sendtext';
            $apiKey = env('SMS_API_KEY') ?: 'b92bebd8370a62da';
            $secretKey = env('SMS_SECRET_KEY') ?: 'e3388ffc';
            $callerId = env('SMS_SENDER_ID') ?: '8809643214620';

            return Http::timeout(10)->post($url, [
                'apikey'         => $apiKey,
                'secretkey'      => $secretKey,
                'callerID'       => $callerId,
                'toUser'         => $to,
                'messageContent' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }
}
