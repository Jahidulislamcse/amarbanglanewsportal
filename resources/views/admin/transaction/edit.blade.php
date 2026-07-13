@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Edit Transaction') }}</h5>
        </div>

        <div class="card-body py-4">
            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>{{ __('Category') }}</label>
                    <select name="category_id" class="form-control" required>
                        <option value="" disabled>
                            -- Select Category --
                        </option>
                
                        @foreach($transactionCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ (int)$transaction->category_id === (int)$category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label>{{ __('Type') }}</label>
                    <select name="type" class="form-control">
                        <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" name="title" value="{{ $transaction->title }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>{{ __('Bearer') }}</label>
                    <input type="text" name="bearer" value="{{ $transaction->title }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ __('Amount') }}</label>
                    <input type="number" step="0.01" name="amount" value="{{ $transaction->amount }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ __('Date') }}</label>
                    <input type="date" name="transaction_date" value="{{ $transaction->transaction_date }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>{{ __('Note') }}</label>
                    <textarea name="note" class="form-control">{{ $transaction->note }}</textarea>
                </div>

                <button class="btn btn-primary">{{ __('Update') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
