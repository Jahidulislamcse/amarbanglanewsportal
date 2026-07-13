@extends('layouts.admin')

@section('content')
<div class="content-area">

    <div class="row row-cards-one">

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg1">
                <div class="left">
                    <h5 class="title">{{ __('Total Income') }}</h5>
                    <span class="number">{{ number_format($totalIncome, 2) }}</span>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg2">
                <div class="left">
                    <h5 class="title">{{ __('Total Expense') }}</h5>
                    <span class="number">{{ number_format($totalExpense, 2) }}</span>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg3">
                <div class="left">
                    <h5 class="title">{{ __('Balance') }}</h5>
                    <span class="number">
                        {{ number_format($totalIncome - $totalExpense, 2) }}
                    </span>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

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

            <div class="my-3">
                <a href="{{ route('transactions.index') }}"
                   class="btn btn-sm {{ empty($type) ? 'btn-dark' : 'btn-outline-dark' }}">
                    All
                </a>
            
                <a href="{{ route('transactions.index', ['type' => 'income']) }}"
                   class="btn btn-sm {{ $type === 'income' ? 'btn-success' : 'btn-outline-success' }}">
                    Income
                </a>
            
                <a href="{{ route('transactions.index', ['type' => 'expense']) }}"
                   class="btn btn-sm {{ $type === 'expense' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Expense
                </a>
            </div>
            
            <table class="table table-hover">
                <thead>
                    <tr>
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
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>{{ $transaction->title }}</td>
                        <td>{{ $transaction->bearer }}</td>
                        <td>{{ $transaction->trcategory->name }}</td>
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
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    </div>

</div>
@endsection
