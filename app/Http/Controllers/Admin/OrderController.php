<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

        return view('admin.orders.index', compact('orders'));
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
}
