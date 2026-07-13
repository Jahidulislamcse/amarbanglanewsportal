<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $product_categories = ProductCategory::latest()->get();
        return view('admin.product_categories.index', compact('product_categories'));
    }

    public function store(Request $request)
    {
        ProductCategory::create([
            'name' => $request->name,
            'slug' => $request->slug 
                ? Str::slug($request->slug)
                : Str::slug($request->name),
        ]);
    
        return back()->with('success', 'Category created');
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
    
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name),
        ]);
    
        return back()->with('success', 'Category updated');
    }


    public function destroy($id)
    {
        ProductCategory::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted');
    }
}

