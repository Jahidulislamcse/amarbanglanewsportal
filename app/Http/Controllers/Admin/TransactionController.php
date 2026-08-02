<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $month = $request->get('month'); // format: YYYY-MM
        
        $transactionCategories = TransactionCategory::orderBy('name')->get();

        if ($categoryId !== 'all') {
            if (!$categoryId && $transactionCategories->isNotEmpty()) {
                $categoryId = $transactionCategories->first()->id;
            }
        }
    
        $query = Transaction::query();
    
        if ($type) {
            $query->where('type', $type);
        }

        if ($month) {
            $query->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }
        
        if ($categoryId === 'all') {
            $query->select('transactions.*')
                  ->leftJoin('transaction_categories', 'transaction_categories.id', '=', 'transactions.category_id')
                  ->orderBy('transaction_categories.name', 'asc')
                  ->orderBy('transactions.transaction_date', 'desc');
        } else {
            $query->orderBy('transactions.transaction_date', 'desc');
        }
    
        $transactions = $query
        ->with('trcategory') 
        ->paginate(300)
        ->withQueryString();

        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');

        $monthlyIncome = 0;
        $monthlyExpense = 0;

        if ($month) {
            $monthlyIncome = Transaction::where('type', 'income')
                ->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month])
                ->sum('amount');

            $monthlyExpense = Transaction::where('type', 'expense')
                ->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month])
                ->sum('amount');
        }

        $categoryTotalsQuery = Transaction::query();
        if ($type) {
            $categoryTotalsQuery->where('type', $type);
        }
        if ($month) {
            $categoryTotalsQuery->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }
        $categoryTotals = $categoryTotalsQuery->groupBy('category_id')
            ->select('category_id')
            ->selectRaw('SUM(amount) as total_amount')
            ->pluck('total_amount', 'category_id')
            ->toArray();
    
        $availableMonths = Transaction::selectRaw("DISTINCT DATE_FORMAT(transaction_date, '%Y-%m') as month_val")
            ->whereNotNull('transaction_date')
            ->orderBy('month_val', 'desc')
            ->pluck('month_val');
 
        return view('admin.transaction.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'monthlyIncome',
            'monthlyExpense',
            'type',
            'categoryId',
            'month',
            'transactionCategories',
            'availableMonths',
            'categoryTotals'
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

    public function downloadPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $month = $request->get('month');

        $query = Transaction::query();
        if ($type) {
            $query->where('type', $type);
        }
        if ($month) {
            $query->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($categoryId === 'all') {
            $query->select('transactions.*')
                  ->leftJoin('transaction_categories', 'transaction_categories.id', '=', 'transactions.category_id')
                  ->orderBy('transaction_categories.name', 'asc')
                  ->orderBy('transactions.transaction_date', 'desc');
        } else {
            $query->orderBy('transactions.transaction_date', 'desc');
        }

        $transactions = $query->with('trcategory')->get();

        $monthlyIncomeQuery = Transaction::where('type', 'income');
        if ($month) {
            $monthlyIncomeQuery->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }
        $monthlyIncome = $monthlyIncomeQuery->sum('amount');

        $monthlyExpenseQuery = Transaction::where('type', 'expense');
        if ($month) {
            $monthlyExpenseQuery->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }
        $monthlyExpense = $monthlyExpenseQuery->sum('amount');

        $categoryTotalsQuery = Transaction::query();
        if ($type) {
            $categoryTotalsQuery->where('type', $type);
        }
        if ($month) {
            $categoryTotalsQuery->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }
        $categoryTotals = $categoryTotalsQuery->groupBy('category_id')
            ->select('category_id')
            ->selectRaw('SUM(amount) as total_amount')
            ->pluck('total_amount', 'category_id')
            ->toArray();

        $formattedMonth = 'All Time';
        if ($month) {
            $dateObj = \DateTime::createFromFormat('Y-m', $month);
            $formattedMonth = $dateObj ? $dateObj->format('F Y') : $month;
        }

        $gs = \App\Models\GeneralSettings::find(1);

        $pdf = Pdf::loadView('admin.transaction.pdf', compact(
            'transactions',
            'monthlyIncome',
            'monthlyExpense',
            'type',
            'categoryId',
            'month',
            'formattedMonth',
            'categoryTotals',
            'gs'
        ));

        $fileName = 'transactions_' . ($month ?: 'all_time') . '.pdf';
        return $pdf->download($fileName);
    }

    public function downloadSummaryPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $type = $request->get('type');
        $categoryId = $request->get('category_id');
        $month = $request->get('month');

        $categoryTotalsQuery = Transaction::query()
            ->leftJoin('transaction_categories', 'transaction_categories.id', '=', 'transactions.category_id')
            ->select('transactions.category_id', 'transactions.type')
            ->selectRaw('COALESCE(transaction_categories.name, "Uncategorized") as category_name')
            ->selectRaw('SUM(transactions.amount) as total_amount');

        if ($month) {
            $categoryTotalsQuery->whereRaw("DATE_FORMAT(transactions.transaction_date, '%Y-%m') = ?", [$month]);
        }

        if ($type) {
            $categoryTotalsQuery->where('transactions.type', $type);
        }

        if ($categoryId && $categoryId !== 'all') {
            $categoryTotalsQuery->where('transactions.category_id', $categoryId);
        }

        $categoryTotals = $categoryTotalsQuery->groupBy('transactions.category_id', 'transactions.type', 'transaction_categories.name')
            ->orderBy('category_name', 'asc')
            ->get();

        $monthlyIncomeQuery = Transaction::where('type', 'income');
        if ($month) {
            $monthlyIncomeQuery->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }
        $monthlyIncome = $monthlyIncomeQuery->sum('amount');

        $monthlyExpenseQuery = Transaction::where('type', 'expense');
        if ($month) {
            $monthlyExpenseQuery->whereRaw("DATE_FORMAT(transaction_date, '%Y-%m') = ?", [$month]);
        }
        $monthlyExpense = $monthlyExpenseQuery->sum('amount');

        $formattedMonth = 'All Time';
        if ($month) {
            $dateObj = \DateTime::createFromFormat('Y-m', $month);
            $formattedMonth = $dateObj ? $dateObj->format('F Y') : $month;
        }

        $gs = \App\Models\GeneralSettings::find(1);

        $pdf = Pdf::loadView('admin.transaction.pdf_summary', compact(
            'categoryTotals',
            'monthlyIncome',
            'monthlyExpense',
            'type',
            'categoryId',
            'month',
            'formattedMonth',
            'gs'
        ));

        $fileName = 'transaction_summary_' . ($month ?: 'all_time') . '.pdf';
        return $pdf->download($fileName);
    }
}
