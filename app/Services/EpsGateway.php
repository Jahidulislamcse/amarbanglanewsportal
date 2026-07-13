<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EpsGateway
{
    private array $config;

    public function __construct()
    {
        $this->config = config('services.eps');
    }

    private function hash(string $data): string
    {
        return base64_encode(hash_hmac(
            'sha512',
            $data,
            $this->config['hash_key'],
            true
        ));
    }

    public function token(): string
    {
        $username = trim($this->config['username']);
        $password = trim($this->config['password']);
    
        $response = Http::withHeaders([
            'x-hash' => $this->hash($username),
            'Content-Type' => 'application/json',
        ])->post($this->config['base_url'] . '/Auth/GetToken', [
            'userName' => $username,
            'password' => $password,
        ]);
    
        if (!$response->successful() || empty($response->json('token'))) {
            throw new \Exception('EPS token failed: ' . $response->body());
        }
    
        return $response->json('token');
    }

    public function initialize(array $order): array
    {
        $token = $this->token();
        $transactionId = trim($order['transaction_id']);
    
        $payload = [
            'merchantId' => $this->config['merchant_id'],
            'storeId' => $this->config['store_id'],
            'CustomerOrderId' => $order['order_id'],
            'merchantTransactionId' => $transactionId,
            'transactionTypeId' => 1,
            'financialEntityId' => 0,
            'transitionStatusId' => 0,
            'totalAmount' => $order['amount'],
            'ipAddress' => request()->ip(),
            'version' => '1',
    
            'successUrl' => route('eps.success'),
            'failUrl' => route('eps.fail'),
            'cancelUrl' => route('eps.cancel'),
    
            'customerName' => $order['customer_name'],
            'customerEmail' => $order['customer_email'],
            'customerAddress' => $order['customer_address'],
            'customerAddress2' => '',
            'customerCity' => $order['customer_city'],
            'customerState' => $order['customer_state'],
            'customerPostcode' => $order['customer_postcode'],
            'customerCountry' => 'BD',
            'customerPhone' => $order['customer_phone'],
    
            'shippingMethod' => 'NO',
            'noOfItem' => '1',
            'productName' => $order['product_name'],
            'productProfile' => 'general',
            'productCategory' => 'Demo',
        ];
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'x-hash' => $this->hash($transactionId),
            'Content-Type' => 'application/json',
        ])->post($this->config['base_url'] . '/EPSEngine/InitializeEPS', $payload);
    
        \Log::info('EPS initialize payload', [
            'transaction_id' => $transactionId,
            'amount' => $order['amount']
        ]);
        \Log::info('EPS initialize raw response', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    
        $data = $response->json();

        if (!$response->successful() || !$data) {
            throw new \Exception('EPS initialize failed: ' . $response->body());
        }
        
        return $data;
    }

    public function verify(string $merchantTransactionId): array
    {
        $token = retry(2, fn () => $this->token(), 200);

        $response = retry(3, function () use ($token, $merchantTransactionId) {
            return Http::timeout(15)->withToken($token)->withHeaders([
                'x-hash' => $this->hash($merchantTransactionId),
            ])->get($this->config['base_url'] . '/EPSEngine/CheckMerchantTransactionStatus', [
                'merchantTransactionId' => $merchantTransactionId,
            ]);
        }, 1000);

        if (!$response->successful()) {
            throw new \Exception('EPS verify failed: ' . $response->body());
        }

        return $response->json();
    }
}