<?php

namespace App\Http\Controllers;

use App\Services\EpsGateway;
use Illuminate\Http\Request;
use App\Models\MonthlyFeePayment;
use App\Models\User;
use App\Models\Fee;
use App\Models\PackageUpgradePayment;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\Order;
use App\Models\Book;
use App\Models\BookPurchase;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TeamGenerationCommission;
use Illuminate\Support\Facades\DB;

class EpsPaymentController extends Controller
{

    public function pay(EpsGateway $eps)
    {
        $transactionId = now()->format('YmdHis') . random_int(1000, 9999);

        $payment = $eps->initialize([
            'order_id' => 'ORDER-' . $transactionId,
            'transaction_id' => $transactionId,
            'amount' => 5,
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_address' => 'Dhaka',
            'customer_city' => 'Dhaka',
            'customer_state' => 'Dhaka',
            'customer_postcode' => '1200',
            'customer_phone' => '01700000000',
            'product_name' => 'Test Payment',
        ]);

        $redirectUrl = data_get($payment, 'RedirectURL');

        return $redirectUrl
            ? redirect()->away(trim($redirectUrl))
            : response()->json([
                'error' => true,
                'message' => 'Invalid EPS redirect URL',
                'payment' => $payment
            ]);
    }

    public function monthlyPay(EpsGateway $eps)
    {
        $user = auth()->user();

        $transactionId = 'MF' . now()->format('YmdHis') . random_int(1000, 9999);
        $fee = Fee::first();

        if (!$fee) {
            return back()->with('error', 'Fee settings not found.');
        }

        $amount = $fee->rep_monthly_fee;

        $response = $eps->initialize([
            'order_id' => 'MF-' . $transactionId,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_address' => 'Dhaka',
            'customer_city' => 'Dhaka',
            'customer_state' => 'Dhaka',
            'customer_postcode' => '1200',
            'customer_phone' => $user->phone ?? '',
            'product_name' => 'Monthly Fee',
        ]);

        $redirectUrl = data_get($response, 'RedirectURL');

        if (!$redirectUrl) {
            return back()->with('error', 'Unable to initialize payment.');
        }

        $this->storePendingPayment($transactionId, 'monthly_fee', [
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'payment_type' => 'monthly_fee',
        ]);

        return redirect()->away(trim($redirectUrl));
    }
    
    public function packageUpgradePay(Request $request, EpsGateway $eps)
    {
        $user = auth()->user();
    
        $package = $request->package;
    
        $fee = Fee::first();
    
        if (!$fee) {
            return back()->with('error', 'Fee settings not found.');
        }
    
        $packagePrices = [
            'executive' => $fee->executive_package_price,
            'vip'       => $fee->vip_package_price,
        ];
    
        if (!isset($packagePrices[$package])) {
            return back()->with('error', 'Invalid package selected.');
        }
    
        $amount = $packagePrices[$package];
    
        $transactionId = 'UPG' . now()->format('YmdHis') . random_int(1000, 9999);
    
        $response = $eps->initialize([
            'order_id'         => $transactionId,
            'transaction_id'   => $transactionId,
            'amount'           => $amount,
            'customer_name'    => $user->name,
            'customer_email'   => $user->email,
            'customer_address' => 'Dhaka',
            'customer_city'    => 'Dhaka',
            'customer_state'   => 'Dhaka',
            'customer_postcode'=> '1200',
            'customer_phone'   => $user->phone ?? '',
            'product_name'     => strtoupper($package) . ' Package Upgrade',
        ]);
    
        $redirectUrl = data_get($response, 'RedirectURL');

        if (!$redirectUrl) {
            return back()->with('error', 'Unable to initialize payment.');
        }

        $this->storePendingPayment($transactionId, 'package_upgrade', [
            'user_id'        => $user->id,
            'transaction_id' => $transactionId,
            'package'        => $package,
            'amount'         => $amount,
            'phone_number'   => $request->phone_number,
        ]);

        return redirect()->away(trim($redirectUrl));
    }

    public function productPay(Request $request, EpsGateway $eps)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'nullable|array',
            'phone_number' => 'nullable|string|max:30',
            'address'      => 'required|string|max:1000',
            'delivery_zone'=> 'nullable|string|in:inside,outside',
            'sizes'        => 'nullable|array',
        ]);

        $user = auth()->user();
        $requestedItems = $this->normalizeProductItems($request);

        if (empty($requestedItems)) {
            return back()->with('error', 'Please select at least one product.');
        }

        $products = Product::where('is_active', true)
            ->whereIn('id', array_keys($requestedItems))
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($requestedItems)) {
            return back()->with('error', 'One or more selected products are unavailable.');
        }

        $deliveryZone = $request->input('delivery_zone', 'outside');
        $deliveryCharge = $deliveryZone === 'inside' ? 80 : 120;
        $amount = $deliveryCharge;
        $productNames = [];

        foreach ($requestedItems as $productId => $quantity) {
            $product = $products[$productId];

            if ($product->stock < $quantity) {
                return back()->with('error', "{$product->name} does not have enough stock.");
            }

            $amount += $product->price * $quantity;
            $productNames[] = $product->name . ' x ' . $quantity;
        }

        $transactionId = 'PRD' . now()->format('YmdHis') . random_int(1000, 9999);
        $firstProduct = $products->first();

        $response = $eps->initialize([
            'order_id'         => $transactionId,
            'transaction_id'   => $transactionId,
            'amount'           => $amount,
            'customer_name'    => $user->name,
            'customer_email'   => $user->email,
            'customer_address' => 'Dhaka',
            'customer_city'    => 'Dhaka',
            'customer_state'   => 'Dhaka',
            'customer_postcode'=> '1200',
            'customer_phone'   => $user->phone ?? $request->phone_number ?? '',
            'product_name'     => implode(', ', $productNames),
        ]);

        $redirectUrl = data_get($response, 'RedirectURL');

        if (!$redirectUrl) {
            return back()->with('error', 'Unable to initialize payment.');
        }

        $zoneLabel = $deliveryZone === 'inside' ? 'Inside Dhaka' : 'Outside Dhaka';
        $fullAddress = $request->address . " [Zone: {$zoneLabel}]";

        $this->storePendingPayment($transactionId, 'product_purchase', [
            'user_id'        => $user->id,
            'product_id'     => $firstProduct->id,
            'order_id'       => null,
            'transaction_id' => $transactionId,
            'product_name'   => implode(', ', $productNames),
            'quantity'       => array_sum($requestedItems),
            'unit_price'     => $firstProduct->price,
            'amount'         => $amount,
            'phone_number'   => $request->phone_number,
            'address'        => $fullAddress,
            'gateway_response' => [
                'checkout_items' => $this->getCheckoutItemsForPayment($products, $requestedItems, $request->input('sizes', [])),
            ],
            'payment_type'   => 'product_purchase',
        ]);

        return redirect()->away(trim($redirectUrl));
    }

    public function bookPay(Request $request, Book $book, EpsGateway $eps)
    {
        $request->validate([
            'phone_number' => 'required|string|max:255',
            'operator' => 'nullable|string|max:30',
        ]);

        if (!$book->status) {
            return back()->with('error', 'This book is not available.');
        }

        $user = auth()->user();
        $transactionId = 'BOOK' . now()->format('YmdHis') . random_int(1000, 9999);

        $response = $eps->initialize([
            'order_id'         => $transactionId,
            'transaction_id'   => $transactionId,
            'amount'           => $book->price,
            'customer_name'    => $user->name,
            'customer_email'   => $user->email,
            'customer_address' => 'Dhaka',
            'customer_city'    => 'Dhaka',
            'customer_state'   => 'Dhaka',
            'customer_postcode'=> '1200',
            'customer_phone'   => $request->phone_number ?: ($user->phone ?? ''),
            'product_name'     => $book->title,
        ]);

        $redirectUrl = data_get($response, 'RedirectURL');

        if (!$redirectUrl) {
            return back()->with('error', 'Unable to initialize payment.');
        }

        $this->storePendingPayment($transactionId, 'book_purchase', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'transaction_id' => $transactionId,
            'phone_number' => $request->phone_number,
            'operator' => $request->operator,
            'amount' => $book->price,
            'payment_type' => 'book_purchase',
        ]);

        return redirect()->away(trim($redirectUrl));
    }

    public function coursePay(Request $request, Course $course, EpsGateway $eps)
    {
        $request->validate([
            'phone_number' => 'required|string|max:255',
            'operator' => 'nullable|string|max:30',
        ]);

        if (!$course->status) {
            return back()->with('error', 'This course is not available.');
        }

        $user = auth()->user();
        $transactionId = 'CRS' . now()->format('YmdHis') . random_int(1000, 9999);

        $response = $eps->initialize([
            'order_id'         => $transactionId,
            'transaction_id'   => $transactionId,
            'amount'           => $course->price,
            'customer_name'    => $user->name,
            'customer_email'   => $user->email,
            'customer_address' => 'Dhaka',
            'customer_city'    => 'Dhaka',
            'customer_state'   => 'Dhaka',
            'customer_postcode'=> '1200',
            'customer_phone'   => $request->phone_number ?: ($user->phone ?? ''),
            'product_name'     => $course->title,
        ]);

        $redirectUrl = data_get($response, 'RedirectURL');

        if (!$redirectUrl) {
            return back()->with('error', 'Unable to initialize payment.');
        }

        $this->storePendingPayment($transactionId, 'course_purchase', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'transaction_id' => $transactionId,
            'phone_number' => $request->phone_number,
            'operator' => $request->operator,
            'amount' => $course->price,
            'payment_type' => 'course_purchase',
        ]);

        return redirect()->away(trim($redirectUrl));
    }

    public function success(Request $request, EpsGateway $eps)
    {
        $data = array_merge(
            $request->all(),
            json_decode(file_get_contents('php://input'), true) ?? []
        );
    
        $transactionId =
            $data['MerchantTransactionId']
            ?? $data['merchantTransactionId']
            ?? $data['TransactionId']
            ?? $data['transactionId']
            ?? $request->query('MerchantTransactionId')
            ?? $request->query('merchantTransactionId')
            ?? $request->query('TransactionId')
            ?? $request->query('transactionId');
    
        if (!$transactionId) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Transaction ID missing');
        }
    
        $payment = $this->findPaymentByTransaction($transactionId);
        $pendingPayment = $payment ? null : $this->getPendingPayment($transactionId);
    
        if (!$payment && !$pendingPayment) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Payment not found');
        }
    
        try {
            $verify = $eps->verify($transactionId);
    
            $status = strtolower($verify['Status'] ?? '');
    
            if ($status !== 'success') {
                if ($payment) {
                    $payment->update([
                        'status' => 'failed'
                    ]);
                }

                $this->forgetPendingPayment($transactionId);
    
                return redirect()
                    ->route('user.dashboard')
                    ->with('error', 'Payment failed');
            }

            $alreadyPaid = $payment ? $this->isPaymentAlreadySuccessful($payment) : false;

            $gatewayResponse = $verify;

            if ($payment instanceof ProductPayment) {
                $gatewayResponse['checkout_items'] = data_get($payment->gateway_response, 'checkout_items', []);
            }

            if ($payment) {
                $payment->update([
                    'status' => $this->getSuccessfulPaymentStatus($payment),
                    'paid_at' => now(),
                    'gateway_response' => $gatewayResponse,
                ]);
            } else {
                $payment = $this->createSuccessfulPaymentFromPending($pendingPayment, $gatewayResponse);
            }
    
            if (!$alreadyPaid) {
                $this->handlePaymentSuccess($payment);
            }

            $this->forgetPendingPayment($transactionId);
    
            $route = 'user.dashboard';
    
            if ($payment instanceof PackageUpgradePayment) {
                $route = 'reader.dashboard';
            }
    
            return redirect()
                ->route($route)
                ->with('success', $this->getSuccessMessage($payment));
    
        } catch (\Exception $e) {
    
            \Log::error('EPS VERIFY ERROR', [
                'message' => $e->getMessage()
            ]);
    
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Payment verification failed');
        }
    }
    
    private function handlePaymentSuccess($payment): void
    {
        if ($payment instanceof PackageUpgradePayment) {
            $user = User::with(['referrer'])->find($payment->user_id);
            if ($user) {
                $user->update([
                    'reader_type' => $payment->package
                ]);

                $category = TransactionCategory::firstOrCreate([
                    'name' => 'Package Upgrade'
                ]);

                Transaction::create([
                    'type'             => 'income',
                    'title'            => 'Package Upgrade',
                    'bearer'           => $user->name . ' (' . $user->phone . ')',
                    'amount'           => $payment->amount,
                    'transaction_date' => now()->toDateString(),
                    'category_id'      => $category->id,
                    'note'             => 'User upgraded to ' . $payment->package . ' (Automatic Payment)',
                ]);

                // Direct Referral Commission
                $referrer = $user->referrer;
                if ($referrer) {
                    $commissionAmount = 0;
                    if ($referrer->reader_type === 'free') {
                        $commissionAmount = 5;
                    } elseif ($referrer->reader_type === 'executive') {
                        $commissionAmount = 10;
                    } elseif ($referrer->reader_type === 'vip') {
                        $fee = Fee::first();
                        if ($fee && $fee->referral_commission > 0) {
                            $commissionAmount = ($payment->amount * $fee->referral_commission) / 100;
                        }
                    }

                    if ($commissionAmount > 0) {
                        $referrer->increment('referral_earning', $commissionAmount);
                        $referrer->increment('balance', $commissionAmount);
                    }
                }

                // Team Generation Commission
                if ($payment->package === 'vip') {
                    $fee = Fee::first();
                    if ($fee) {
                        $genRates = [
                            1 => $fee->team_gen_1_rate ?? 0,
                            2 => $fee->team_gen_2_rate ?? 0,
                            3 => $fee->team_gen_3_rate ?? 0,
                            4 => $fee->team_gen_4_rate ?? 0,
                            5 => $fee->team_gen_5_rate ?? 0,
                        ];

                        $currentUser = $user;
                        for ($generation = 1; $generation <= 5; $generation++) {
                            $upline = $currentUser->referrer;
                            if (!$upline) {
                                break;
                            }
                            $currentUser = $upline;
                            $rate = $genRates[$generation];
                            if ($rate <= 0) {
                                continue;
                            }
                            $commission = ($payment->amount * $rate) / 100;

                            TeamGenerationCommission::firstOrCreate(
                                [
                                    'receiver_user_id'   => $upline->id,
                                    'source_user_id'     => $user->id,
                                    'upgrade_request_id' => null,
                                    'generation'         => $generation,
                                ],
                                [
                                    'package'          => $payment->package,
                                    'upgrade_amount'   => $payment->amount,
                                    'rate'             => $rate,
                                    'commission'       => $commission,
                                ]
                            );

                            $upline->increment('team_gen_' . $generation, $commission);
                            if ($upline->reader_type === 'vip') {
                                $upline->increment('balance', $commission);
                            }
                        }
                    }
                }
            }
        }
    
        if ($payment instanceof MonthlyFeePayment) {
            $user = User::find($payment->user_id);
        
            if ($user) {
                $user->update([
                    'next_payment_date' => \Carbon\Carbon::parse(
                        $user->next_payment_date ?? now()
                    )->addMonth(),
                ]);
            }
        }

        if ($payment instanceof ProductPayment) {
            $order = $this->createOrderForProductPayment($payment);
            $items = $order->items;

            if ($items && $items->count()) {
                foreach ($items as $item) {
                    Product::where('id', $item->product_id)
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);
                }
            } else {
                Product::where('id', $payment->product_id)
                    ->where('stock', '>=', $payment->quantity)
                    ->decrement('stock', $payment->quantity);
            }

            // Check if user has now purchased all Package 1 products
            $user = User::find($payment->user_id);
            if ($user && $user->package1_purchased == 0) {
                $package1ProductIds = Product::where('package', 'package1')
                    ->where('is_active', true)
                    ->pluck('id')
                    ->toArray();

                if (!empty($package1ProductIds)) {
                    $purchasedProductIds = OrderItem::whereHas('order.payment', function ($q) {
                        $q->where('status', 'paid');
                    })
                    ->whereHas('order', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->pluck('product_id')
                    ->unique()
                    ->toArray();

                    $missingIds = array_diff($package1ProductIds, $purchasedProductIds);
                    if (empty($missingIds)) {
                        $user->update(['package1_purchased' => 1]);
                    }
                }
            }
        }

        if ($payment instanceof BookPurchase) {
            $payment->update(['status' => 'approved']);
        }

        if ($payment instanceof CoursePurchase) {
            $payment->update(['status' => 'approved']);
        }
    }

    private function getSuccessMessage($payment): string
    {
        $paymentType = $payment->payment_type
            ?? ($payment instanceof PackageUpgradePayment ? 'package_upgrade' : null);

        return match ($paymentType) {
    
            'monthly_fee' =>
                'Monthly fee payment successful.',
    
            'package_upgrade' =>
                'Package upgraded successfully.',

            'product_purchase' =>
                'Product payment successful.',

            'book_purchase' =>
                'Book payment successful.',

            'course_purchase' =>
                'Course payment successful.',
    
            default =>
                'Payment successful.',
        };
    }

    private function findPaymentByTransaction(string $transactionId)
    {
        return PackageUpgradePayment::where('transaction_id', $transactionId)->first()
            ?: ProductPayment::where('transaction_id', $transactionId)->first()
            ?: MonthlyFeePayment::where('transaction_id', $transactionId)->first()
            ?: BookPurchase::where('transaction_id', $transactionId)->first()
            ?: CoursePurchase::where('transaction_id', $transactionId)->first();
    }

    private function createSuccessfulPaymentFromPending(array $pendingPayment, array $gatewayResponse)
    {
        $attributes = $pendingPayment['attributes'];
        $type = $pendingPayment['type'];
        $modelClass = $this->getPaymentModelClass($type);
        $existingGatewayResponse = $attributes['gateway_response'] ?? [];

        if ($type === 'product_purchase') {
            $gatewayResponse['checkout_items'] = data_get($existingGatewayResponse, 'checkout_items', []);
        }

        unset($attributes['gateway_response']);

        $payment = new $modelClass();
        $attributes['status'] = $this->getSuccessfulPaymentStatus($payment);
        $attributes['paid_at'] = now();
        $attributes['gateway_response'] = $gatewayResponse;

        return $modelClass::create($attributes);
    }

    private function getPaymentModelClass(string $type): string
    {
        return match ($type) {
            'monthly_fee' => MonthlyFeePayment::class,
            'package_upgrade' => PackageUpgradePayment::class,
            'product_purchase' => ProductPayment::class,
            'book_purchase' => BookPurchase::class,
            'course_purchase' => CoursePurchase::class,
            default => throw new \InvalidArgumentException('Unsupported EPS payment type.'),
        };
    }

    private function getSuccessfulPaymentStatus($payment): string
    {
        return ($payment instanceof BookPurchase || $payment instanceof CoursePurchase)
            ? 'approved'
            : 'paid';
    }

    private function isPaymentAlreadySuccessful($payment): bool
    {
        return in_array($payment->status, ['paid', 'approved'], true);
    }

    private function storePendingPayment(string $transactionId, string $type, array $attributes): void
    {
        session()->put($this->pendingPaymentSessionKey($transactionId), [
            'type' => $type,
            'attributes' => $attributes,
        ]);
    }

    private function getPendingPayment(string $transactionId): ?array
    {
        return session()->get($this->pendingPaymentSessionKey($transactionId));
    }

    private function forgetPendingPayment(string $transactionId): void
    {
        session()->forget($this->pendingPaymentSessionKey($transactionId));
    }

    private function pendingPaymentSessionKey(string $transactionId): string
    {
        return 'eps_pending_payments.' . $transactionId;
    }

    private function createOrderForProductPayment(ProductPayment $payment): Order
    {
        return DB::transaction(function () use ($payment) {
            $lockedPayment = ProductPayment::whereKey($payment->id)->lockForUpdate()->first();

            if ($lockedPayment->order_id) {
                return $lockedPayment->order()->with('items')->first();
            }

            $order = Order::create([
                'user_id' => $lockedPayment->user_id,
                'transaction_id' => $lockedPayment->transaction_id,
                'total_amount' => $lockedPayment->amount,
                'phone_number' => $lockedPayment->phone_number,
                'address' => $lockedPayment->address,
                'status' => 'pending',
            ]);

            foreach ($this->getProductPaymentCheckoutItems($lockedPayment) as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'size' => $item['size'] ?? null,
                ]);
            }

            $lockedPayment->update(['order_id' => $order->id]);
            $payment->order_id = $order->id;

            return $order->load('items');
        });
    }

    private function getProductPaymentCheckoutItems(ProductPayment $payment): array
    {
        $items = data_get($payment->gateway_response, 'checkout_items', []);

        if (!empty($items)) {
            return array_values(array_map(function ($item) {
                return [
                    'product_id' => (int) $item['product_id'],
                    'quantity' => max(1, (int) $item['quantity']),
                    'price' => (float) $item['price'],
                    'size' => $item['size'] ?? null,
                ];
            }, $items));
        }

        return [[
            'product_id' => $payment->product_id,
            'quantity' => $payment->quantity,
            'price' => $payment->unit_price,
            'size' => null,
        ]];
    }

    private function getCheckoutItemsForPayment($products, array $requestedItems, ?array $sizes = []): array
    {
        $items = [];

        foreach ($requestedItems as $productId => $quantity) {
            $product = $products[$productId];

            $size = data_get($sizes, $productId);
            if (!$size && $product->slug === 'ti-sart') {
                $size = request()->input('tshirt_size');
            }

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'size' => $size,
            ];
        }

        return $items;
    }

    private function normalizeProductItems(Request $request): array
    {
        $items = [];

        if ($request->filled('product_id')) {
            $items[(int) $request->product_id] = max(1, (int) $request->quantity);
        }

        // Cross-sell suggested product
        if ($request->filled('suggested_product_id') && $request->input('add_suggested_product') == 1) {
            $items[(int) $request->suggested_product_id] = max(1, (int) $request->suggested_quantity);
        }

        foreach ((array) $request->input('product_ids', []) as $productId) {
            $productId = (int) $productId;
            $quantity = (int) data_get($request->input('quantities', []), $productId, 1);

            if ($productId > 0 && $quantity > 0) {
                $items[$productId] = ($items[$productId] ?? 0) + $quantity;
            }
        }

        return $items;
    }

    public function fail(Request $request)
    {
        $txn = $request->query('MerchantTransactionId')
            ?? $request->query('merchantTransactionId')
            ?? $request->query('TransactionId')
            ?? $request->query('transactionId')
            ?? $request->input('MerchantTransactionId')
            ?? $request->input('merchantTransactionId')
            ?? $request->input('TransactionId')
            ?? $request->input('transactionId');

        if ($txn) {
            optional($this->findPaymentByTransaction($txn))->update(['status' => 'failed']);
            $this->forgetPendingPayment($txn);
        }

        return redirect()
            ->route('user.dashboard')
            ->with('error', 'Payment failed');
    }

    public function cancel(Request $request)
    {
        $txn = $request->query('MerchantTransactionId')
            ?? $request->query('merchantTransactionId')
            ?? $request->query('TransactionId')
            ?? $request->query('transactionId')
            ?? $request->input('MerchantTransactionId')
            ?? $request->input('merchantTransactionId')
            ?? $request->input('TransactionId')
            ?? $request->input('transactionId');

        if ($txn) {
            optional($this->findPaymentByTransaction($txn))->update(['status' => 'cancelled']);
            $this->forgetPendingPayment($txn);
        }

        return redirect()
            ->route('user.dashboard')
            ->with('error', 'Payment cancelled');
    }
}
