<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    public function index()
    {
        $categories = TransactionCategory::orderBy('name')->get();
        return view('admin.transaction.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        TransactionCategory::create($request->only('name'));

        return back()->with('success', 'Category added');
    }

    public function update(Request $request, TransactionCategory $transactionCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $transactionCategory->update($request->only('name'));

        return back()->with('success', 'Category updated');
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        $transactionCategory->delete();

        return back()->with('success', 'Category deleted');
    }
}
