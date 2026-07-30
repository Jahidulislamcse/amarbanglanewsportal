<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $transactionCategories = TransactionCategory::orderBy('name')->get();

        if (!$categoryId && $transactionCategories->isNotEmpty()) {
            $categoryId = $transactionCategories->first()->id;
        }
    
        $query = Transaction::query();
    
        if ($type) {
            $query->where('type', $type);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    
        $transactions = $query
        ->with('trcategory') 
        ->orderBy('transaction_date', 'desc')
        ->paginate(300)
        ->withQueryString();

        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');

        return view('admin.transaction.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'type',
            'categoryId',
            'transactionCategories'
        ));

    }

    public function create()
    {
        $transactionCategories = TransactionCategory::orderBy('name')->get();

    return view('admin.transaction.create', compact('transactionCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'bearer' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'note' => 'nullable|string'
        ]);
    
        Transaction::create($request->all());
    
        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction added successfully');
    }

    public function edit(Transaction $transaction)
    {
        $transactionCategories = TransactionCategory::orderBy('name')->get();
        return view('admin.transaction.edit', compact('transaction', 'transactionCategories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'bearer' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'category_id' => 'nullable|exists:transaction_categories,id',
            'note' => 'nullable|string'
        ]);
    
        $transaction->update($request->all());
    
        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction updated successfully');
    }


    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }
}
