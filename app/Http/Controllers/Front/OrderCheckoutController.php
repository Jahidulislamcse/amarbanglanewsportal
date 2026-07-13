<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $request->total,
            'status' => 'pending'
        ]);

        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['qty'],
                'price' => $item['price']
            ]);
        }

        return redirect()->back()->with('success', 'Order placed');
    }
}
