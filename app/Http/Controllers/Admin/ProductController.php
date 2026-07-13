<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product_categories = ProductCategory::all();
        return view('admin.products.create', compact('product_categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'name'        => 'required',
            'description' => 'required',
            'buying_price'       => 'required|numeric',
            'price'       => 'required',
            'stock'       => 'required',
            'is_active'   => 'required',
            'images.*'    => 'mimes:jpeg,jpg,png,svg',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $data = new Product();
        $data->category_id  = $request->category_id;
        $data->name         = $request->name;
        $data->slug         = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $data->description  = $request->description;
        $data->buying_price        = $request->buying_price;
        $data->price        = $request->price;
        $data->stock        = $request->stock;
        $data->is_active    = $request->is_active;
        $data->save();
    
        // Save images like your example
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $name = time() . $image->getClientOriginalName();
                $image->move('assets/images/products/', $name);
    
                $data->images()->create([
                    'image_path' => $name,
                    'is_primary' => $key == 0 ? 1 : 0,
                ]);
            }
        }
    
        return redirect()->route('admin.products.index')
            ->with('success', 'Product added successfully');
    }


    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $product_categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'product_categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        $rules = [
            'category_id' => 'required',
            'name'        => 'required',
            'buying_price'       => 'required|numeric',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'images.*'    => 'mimes:jpeg,jpg,png,svg|max:2048'
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $data = $request->except('images', 'remove_images');
        $data['slug'] = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $product->update($data);

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $img_id) {
                $img = ProductImage::find($img_id);
                if ($img) {
                    // delete file from folder
                    if (file_exists(public_path('assets/images/products/' . $img->image_path))) {
                        unlink(public_path('assets/images/products/' . $img->image_path));
                    }
                    $img->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->images as $key => $image) {
                $name = time() . '_' . $image->getClientOriginalName();
                $image->move('assets/images/products/', $name);
    
                $product->images()->create([
                    'image_path' => $name,
                    'is_primary' => false
                ]);
            }
        }

        $primary = $product->images()->first();
        if ($primary) {
            $product->images()->update(['is_primary' => false]);
            $primary->update(['is_primary' => true]);
        }
    
        return back()->with('success', 'Product updated');
    }


    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Product deleted');
    }
}

