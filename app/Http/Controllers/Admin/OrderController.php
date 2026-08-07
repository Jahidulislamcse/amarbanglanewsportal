<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'payment'])
            ->latest();

        $query->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->search, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('transaction_id', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($user) use ($request) {
                        $user->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%")
                            ->orWhere('phone', 'like', "%{$request->search}%");
                    })
                    ->orWhereHas('items.product', function ($product) use ($request) {
                        $product->where('name', 'like', "%{$request->search}%");
                    });
            });
        });

        $orders = $query->paginate(20);
        $contact = \App\Models\Contact::first();

        return view('admin.orders.index', compact('orders', 'contact'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function createManual()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders.create_manual', compact('products'));
    }

    public function searchUsers(Request $request)
    {
        $search = $request->query('q');
        
        $users = User::where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        })
        ->limit(15)
        ->get(['id', 'name', 'email', 'phone', 'address']);

        return response()->json($users);
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone_number' => 'nullable|string|max:30',
            'address' => 'required|string|max:1000',
            'delivery_zone' => 'required|in:inside,outside,office',
            'status' => 'required|in:pending,paid,shipped,delivered,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string|max:30',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        $deliveryZone = $request->delivery_zone;

        if ($deliveryZone === 'office') {
            $deliveryCharge = 0;
            $zoneLabel = 'Collect from Office';
        } elseif ($deliveryZone === 'inside') {
            $deliveryCharge = 80;
            $zoneLabel = 'Inside Dhaka';
        } else {
            $deliveryCharge = 120;
            $zoneLabel = 'Outside Dhaka';
        }

        $productAmount = 0;
        $productNames = [];
        $checkoutItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $qty = intval($item['qty']);
            $price = floatval($item['price']);
            $size = $item['size'] ?? null;

            if ($product->stock < $qty) {
                return back()->with('error', "{$product->name} does not have enough stock.")
                             ->withInput();
            }

            $productAmount += $price * $qty;
            $productNames[] = $product->name . ' x ' . $qty;

            $checkoutItems[] = [
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
                'size' => $size,
            ];
        }

        $totalAmount = $productAmount + $deliveryCharge;
        $transactionId = 'MANUAL-ADM-' . now()->format('YmdHis') . random_int(1000, 9999);
        $fullAddress = $request->address . " [Zone: {$zoneLabel}]";

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'total_amount' => $totalAmount,
                'phone_number' => $request->phone_number ?: $user->phone,
                'address' => $fullAddress,
                'status' => $request->status,
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $product->decrement('stock', intval($item['qty']));

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => intval($item['qty']),
                    'price' => floatval($item['price']),
                    'size' => $item['size'] ?? null,
                ]);
            }

            $firstItem = $request->items[0];
            $firstProduct = Product::find($firstItem['product_id']);

            $payment = ProductPayment::create([
                'user_id' => $user->id,
                'product_id' => $firstProduct->id,
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'product_name' => implode(', ', $productNames),
                'quantity' => count($request->items),
                'unit_price' => floatval($firstItem['price']),
                'amount' => $totalAmount,
                'phone_number' => $request->phone_number ?: $user->phone,
                'address' => $fullAddress,
                'status' => 'paid',
                'gateway_response' => [
                    'checkout_items' => $checkoutItems,
                ],
                'payment_type' => 'product_purchase',
                'paid_at' => now(),
            ]);

            // Create ledger transactions:
            
            // 1. Income: Product Sells
            $category = TransactionCategory::firstOrCreate([
                'name' => 'Product Sells'
            ]);

            Transaction::create([
                'type'             => 'income',
                'title'            => 'Product Sells',
                'bearer'           => $user->name . ' (' . ($order->phone_number ?? $user->phone ?? 'N/A') . ')',
                'amount'           => $totalAmount,
                'transaction_date' => now()->toDateString(),
                'category_id'      => $category->id,
                'order_id'         => $order->id,
                'note'             => 'Product Sells: ' . implode(', ', $productNames) . '. Transaction ID: ' . $transactionId . ' (Manual Order)',
            ]);

            // 2. Expense: Product Buying Cost
            $totalBuyingPrice = 0;
            foreach ($order->items as $item) {
                $buyingPrice = optional($item->product)->buying_price ?? 0;
                $totalBuyingPrice += $buyingPrice * $item->quantity;
            }

            if ($totalBuyingPrice > 0) {
                $expenseCategory = TransactionCategory::firstOrCreate([
                    'name' => 'Product Buying Cost'
                ]);

                Transaction::create([
                    'type'             => 'expense',
                    'title'             => 'Product Buying Cost',
                    'bearer'           => $user->name . ' (' . ($order->phone_number ?? $user->phone ?? 'N/A') . ')',
                    'amount'           => $totalBuyingPrice,
                    'transaction_date' => now()->toDateString(),
                    'category_id'      => $expenseCategory->id,
                    'order_id'         => $order->id,
                    'note'             => 'Buying cost of products for Order ID: ' . $order->id . ' (Transaction ID: ' . $transactionId . ')',
                ]);
            }

            // 3. Expense: sell commission (from referral commission created via Order boot)
            $commission = \App\Models\ProductCommission::where('order_id', $order->id)->first();
            if ($commission && $commission->commission_amount > 0) {
                $referrerUser = User::find($commission->referrer_id);
                if ($referrerUser) {
                    $commissionCategory = TransactionCategory::firstOrCreate([
                        'name' => 'sell commission'
                    ]);

                    Transaction::create([
                        'type'             => 'expense',
                        'title'            => 'sell commission',
                        'bearer'           => $referrerUser->name . ' (' . ($referrerUser->phone ?? 'N/A') . ')',
                        'amount'           => $commission->commission_amount,
                        'transaction_date' => now()->toDateString(),
                        'category_id'      => $commissionCategory->id,
                        'order_id'         => $order->id,
                        'note'             => 'Sell commission for Order ID: ' . $order->id . ' paid to referrer ' . $referrerUser->name . ' (Transaction ID: ' . $transactionId . ')',
                    ]);
                }
            }

            // 4. Expense: Courier Cost
            if ($deliveryCharge > 0) {
                $courierCategory = TransactionCategory::firstOrCreate([
                    'name' => 'Courier Cost'
                ]);

                Transaction::create([
                    'type'             => 'expense',
                    'title'            => 'Courier Cost',
                    'bearer'           => $user->name . ' (' . ($order->phone_number ?? $user->phone ?? 'N/A') . ')',
                    'amount'           => $deliveryCharge,
                    'transaction_date' => now()->toDateString(),
                    'category_id'      => $courierCategory->id,
                    'order_id'         => $order->id,
                    'note'             => 'Courier cost for Order ID: ' . $order->id . ' (Transaction ID: ' . $transactionId . ')',
                ]);
            }

            // 5. Update package1_purchased status if applicable
            if ($user->package1_purchased == 0) {
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

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Manual order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create order: ' . $e->getMessage())->withInput();
        }
    }
}
