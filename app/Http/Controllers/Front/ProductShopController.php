<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductShopController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with('primaryImage')
            ->paginate(12);

        return view('front.shop.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('images')
            ->firstOrFail();

        return view('front.shop.show', compact('product'));
    }
}
