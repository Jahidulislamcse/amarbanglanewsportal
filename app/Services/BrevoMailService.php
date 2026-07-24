<?php

namespace App\Services;

class BrevoMailService
{
    public static function send($toEmail, $toName, $subject, $htmlContent)
    {
        $apiKey = trim(config('services.brevo.key') ?? '');

        $payload = [
            "sender" => [
                "name" => "Amar Bangla 24",
                "email" => "amarbangla24media@gmail.com"
            ],
            "to" => [[
                "email" => $toEmail,
                "name" => $toName
            ]],
            "subject" => $subject,
            "htmlContent" => $htmlContent
        ];

        $ch = curl_init("https://api.brevo.com/v3/smtp/email");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "api-key: " . $apiKey,
                "content-type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        \Log::info("BREVO HTTP CODE: " . $httpCode);
        \Log::info("BREVO RESPONSE: " . $response);

        return $response;
    }
}