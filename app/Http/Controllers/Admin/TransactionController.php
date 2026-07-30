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

        $incomeQuery = Transaction::where('type', 'income');
        $expenseQuery = Transaction::where('type', 'expense');

        if ($categoryId) {
            $incomeQuery->where('category_id', $categoryId);
            $expenseQuery->where('category_id', $categoryId);
        }
    
        $totalIncome = $incomeQuery->sum('amount');
        $totalExpense = $expenseQuery->sum('amount');
    
        $transactionCategories = TransactionCategory::orderBy('name')->get();

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
