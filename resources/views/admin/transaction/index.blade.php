@extends('layouts.admin')

@section('content')
<div class="content-area">

    <div class="row row-cards-one">

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg1" style="padding: 10px 18px; margin-bottom: 15px; min-height: 85px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05); border-radius: 6px;">
                <div class="left">
                    <h5 class="title" style="font-size: 13px; margin-bottom: 2px; opacity: 0.9;">{{ __('Lifetime Income') }}</h5>
                    <span class="number" style="font-size: 26px; font-weight: 800; line-height: 1.2;">{{ number_format($totalIncome, 2) }}</span>
                </div>
                <div class="right">
                    <div class="icon" style="font-size: 28px; opacity: 0.85; line-height: 1; margin: 0;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg2" style="padding: 10px 18px; margin-bottom: 15px; min-height: 85px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05); border-radius: 6px;">
                <div class="left">
                    <h5 class="title" style="font-size: 13px; margin-bottom: 2px; opacity: 0.9;">{{ __('Lifetime Expense') }}</h5>
                    <span class="number" style="font-size: 26px; font-weight: 800; line-height: 1.2;">{{ number_format($totalExpense, 2) }}</span>
                </div>
                <div class="right">
                    <div class="icon" style="font-size: 28px; opacity: 0.85; line-height: 1; margin: 0;">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg3" style="padding: 10px 18px; margin-bottom: 15px; min-height: 85px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05); border-radius: 6px;">
                <div class="left">
                    <h5 class="title" style="font-size: 13px; margin-bottom: 2px; opacity: 0.9;">{{ __('Lifetime Balance') }}</h5>
                    <span class="number" style="font-size: 26px; font-weight: 800; line-height: 1.2;">
                        {{ number_format($totalIncome - $totalExpense, 2) }}
                    </span>
                </div>
                <div class="right">
                    <div class="icon" style="font-size: 28px; opacity: 0.85; line-height: 1; margin: 0;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if($month)
        @php
            $dateObj = DateTime::createFromFormat('Y-m', $month);
            $formattedMonth = $dateObj ? $dateObj->format('F Y') : $month;
        @endphp
        <div class="row row-cards-one mt-2">
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg1" style="background: linear-gradient(135deg, #ff7b00 0%, #ffae00 100%); padding: 10px 18px; margin-bottom: 15px; min-height: 85px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.04); border-radius: 6px;">
                    <div class="left">
                        <h6 class="title" style="font-size: 13px; margin-bottom: 2px; opacity: 0.9;">{{ __('Monthly Income') }} ({{ $formattedMonth }})</h6>
                        <span class="number" style="font-size: 26px; font-weight: 800; line-height: 1.2;">{{ number_format($monthlyIncome, 2) }}</span>
                    </div>
                    <div class="right">
                        <div class="icon" style="font-size: 28px; opacity: 0.8; line-height: 1; margin: 0;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg2" style="background: linear-gradient(135deg, #0072ff 0%, #00c6ff 100%); padding: 10px 18px; margin-bottom: 15px; min-height: 85px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.04); border-radius: 6px;">
                    <div class="left">
                        <h6 class="title" style="font-size: 13px; margin-bottom: 2px; opacity: 0.9;">{{ __('Monthly Expense') }} ({{ $formattedMonth }})</h6>
                        <span class="number" style="font-size: 26px; font-weight: 800; line-height: 1.2;">{{ number_format($monthlyExpense, 2) }}</span>
                    </div>
                    <div class="right">
                        <div class="icon" style="font-size: 28px; opacity: 0.8; line-height: 1; margin: 0;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg3" style="background: linear-gradient(135deg, #00a86b 0%, #00e676 100%); padding: 10px 18px; margin-bottom: 15px; min-height: 85px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.04); border-radius: 6px;">
                    <div class="left">
                        <h6 class="title" style="font-size: 13px; margin-bottom: 2px; opacity: 0.9;">{{ __('Monthly Balance') }} ({{ $formattedMonth }})</h6>
                        <span class="number" style="font-size: 26px; font-weight: 800; line-height: 1.2;">
                            {{ number_format($monthlyIncome - $monthlyExpense, 2) }}
                        </span>
                    </div>
                    <div class="right">
                        <div class="icon" style="font-size: 28px; opacity: 0.8; line-height: 1; margin: 0;">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
            <h5>
                {{ __('Transactions') }}
                @if($type)
                    — {{ ucfirst($type) }}
                @endif
            </h5>
            
            @if (Auth::guard('admin')->user()->sectionCheck('transaction'))  
                <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                    {{ __('Add New') }}
                </a>
            @endif
        </div>

        <div class="card-body">
            <div class="card mb-4">
                @if (Auth::guard('admin')->user()->sectionCheck('transaction'))
                    <div class="card-header">
                        <button class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="collapse"
                            data-bs-target="#categoryBox">
                            Manage Categories
                        </button>
                    </div>
                 @endif
            
                <div id="categoryBox" class="collapse mt-3">
                    <div class="card-body">
                        <form action="{{ route('transaction-categories.store') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="New category" required>
                                <button class="btn btn-primary ml-1">Add</button>
                            </div>
                        </form>
                
                        @if($transactionCategories->count())
                            @foreach($transactionCategories as $category)
                                <div class="d-flex align-items-center ">
                                    <form action="{{ route('transaction-categories.update', $category->id) }}" method="POST" class="d-flex flex-grow-1 ">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}" class="form-control form-control-sm " required>
                                        <button class="btn btn-sm mb-2 btn-success ml-1">Save</button>
                                    </form>
                
                                    <form action="{{ route('transaction-categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger ml-1 mb-2" onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </div>
                            @endforeach
                        @else
                            <p>No categories found.</p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap: 15px;">
                <form action="{{ route('transactions.index') }}" method="GET" class="d-flex align-items-center flex-wrap" style="gap: 15px; margin: 0;">
                    @if($type)
                        <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                    @if($categoryId)
                        <input type="hidden" name="category_id" value="{{ $categoryId }}">
                    @endif

                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <strong>Filter by Month:</strong>
                        <select name="month" class="form-control form-control-sm" onchange="this.form.submit()" style="width: auto; min-width: 150px;">
                            <option value="">All Time</option>
                            @foreach($availableMonths as $m)
                                @php
                                    $dateObj = DateTime::createFromFormat('Y-m', $m);
                                    $formattedMonth = $dateObj ? $dateObj->format('F Y') : $m;
                                @endphp
                                <option value="{{ $m }}" {{ $month === $m ? 'selected' : '' }}>
                                    {{ $formattedMonth }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($month)
                    <div style="display: inline-flex; gap: 8px;">
                        <a href="{{ route('transactions.pdf', array_filter(['type' => $type, 'category_id' => $categoryId, 'month' => $month])) }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-file-pdf mr-1"></i> Download PDF
                        </a>
                        <a href="{{ route('transactions.pdf.summary', array_filter(['type' => $type, 'category_id' => $categoryId, 'month' => $month])) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-file-pdf mr-1"></i> Download Summary 
                        </a>
                    </div>
                @endif
            </div>

            <div class="my-3">
                <span class="mr-2"><strong>Filter by Type:</strong></span>
                <a href="{{ route('transactions.index', array_filter(['category_id' => $categoryId, 'month' => $month])) }}"
                   class="btn btn-sm {{ empty($type) ? 'btn-dark' : 'btn-outline-dark' }}">
                    All
                </a>
            
                <a href="{{ route('transactions.index', array_filter(['type' => 'income', 'category_id' => $categoryId, 'month' => $month])) }}"
                   class="btn btn-sm {{ $type === 'income' ? 'btn-success' : 'btn-outline-success' }}">
                    Income
                </a>
            
                <a href="{{ route('transactions.index', array_filter(['type' => 'expense', 'category_id' => $categoryId, 'month' => $month])) }}"
                   class="btn btn-sm {{ $type === 'expense' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Expense
                </a>
            </div>

            <div class="mb-4 d-flex align-items-center flex-wrap" style="gap: 8px;">
                <span class="mr-2"><strong>Filter by Category:</strong></span>
                <a href="{{ route('transactions.index', array_filter(['type' => $type, 'category_id' => 'all', 'month' => $month])) }}" 
                   class="btn btn-sm {{ $categoryId === 'all' || empty($categoryId) ? 'btn-dark' : 'btn-outline-dark' }}">
                    {{ $month ? 'Month All Transactions (Grouped)' : 'All Categories' }}
                </a>

                @foreach($transactionCategories as $category)
                    <a href="{{ route('transactions.index', array_filter(['type' => $type, 'category_id' => $category->id, 'month' => $month])) }}" 
                       class="btn btn-sm {{ $categoryId == $category->id ? 'btn-dark' : 'btn-outline-secondary' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
            
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Bearer') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sl = $transactions->firstItem();
                        $currentCategoryName = null;
                        $currentCategoryId = null;
                    @endphp
                    @foreach($transactions as $transaction)
                        @if($categoryId === 'all' && $month)
                            @php
                                $rowCategoryId = $transaction->category_id;
                                $rowCategoryName = optional($transaction->trcategory)->name ?? 'Uncategorized';
                            @endphp
                            @if($rowCategoryName !== $currentCategoryName)
                                @if($currentCategoryName !== null)
                                    <tr style="background-color: #fcfcfc; font-weight: bold; border-top: 1px solid #dee2e6; border-bottom: 2px solid #dee2e6;">
                                        <td colspan="6" class="text-right" style="font-size: 14px; color: #495057; padding: 12px 18px;">
                                            Total {{ $currentCategoryName }}:
                                        </td>
                                        <td style="font-size: 15px; color: #212529; padding: 12px 18px;">
                                            {{ number_format($categoryTotals[$currentCategoryId] ?? 0, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                                @php
                                    $currentCategoryName = $rowCategoryName;
                                    $currentCategoryId = $rowCategoryId;
                                    $sl = 1;
                                @endphp
                                <tr>
                                    <td colspan="8" class="font-weight-bold" style="background-color: #f1f3f5; color: #495057; font-size: 15px; border-bottom: 2px solid #dee2e6; padding: 12px 18px;">
                                        <i class="fas fa-tags mr-1"></i> {{ $currentCategoryName }}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>
                            {{ $transaction->title }}
                            @if($transaction->order_id)
                                <span class="badge badge-secondary" style="font-size: 10px; margin-left: 5px; font-weight: bold; background-color: #6c757d; color: #fff;">
                                    #{{ $transaction->order_id }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $transaction->bearer }}</td>
                        <td>{{ optional($transaction->trcategory)->name ?? 'Uncategorized' }}</td>
                        <td>
                            <span class="badge badge-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                         
                        <td>{{ number_format($transaction->amount, 2) }}</td>
                         @if (Auth::guard('admin')->user()->sectionCheck('transaction'))  
                            <td>
                                <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-sm btn-info">
                                    Edit
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        @endif
                        
                    </tr>
                    @endforeach

                    @if($transactions->isNotEmpty() && ($categoryId !== 'all' || $month))
                        @php
                            $lastTransaction = $transactions->last();
                            $lastCategoryId = $lastTransaction->category_id;
                            $lastCategoryName = optional($lastTransaction->trcategory)->name ?? 'Uncategorized';
                        @endphp
                        <tr style="background-color: #fcfcfc; font-weight: bold; border-top: 1px solid #dee2e6; border-bottom: 2px solid #dee2e6;">
                            <td colspan="6" class="text-right" style="font-size: 14px; color: #495057; padding: 12px 18px;">
                                Total {{ $categoryId === 'all' ? $lastCategoryName : (optional($transactions->first()->trcategory)->name ?? 'Uncategorized') }}:
                            </td>
                            <td style="font-size: 15px; color: #212529; padding: 12px 18px;">
                                {{ number_format($categoryTotals[$categoryId === 'all' ? $lastCategoryId : $categoryId] ?? 0, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    </div>

</div>


@endsection
