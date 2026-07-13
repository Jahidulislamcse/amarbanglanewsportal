@extends('layouts.admin')

@section('content')
<div class="content-area ">
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Add Transaction') }}</h5>
        </div>

        <div class="card-body py-4">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>{{ __('Category') }}</label>
                    <select name="category_id" class="form-control" required>
                        <option value="" disabled selected>
                            -- Select Category --
                        </option>
                
                        @foreach($transactionCategories as $transactionCategory)
                            <option value="{{ $transactionCategory->id }}">
                                {{ $transactionCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('Type') }}</label>
                    <select name="type" class="form-control" required>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('Title') }}</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>{{ __('Bearer') }}</label>
                    <input type="text" name="bearer" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>{{ __('Amount') }}</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>{{ __('Date') }}</label>
                    <input type="date" name="transaction_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>{{ __('Note') }}</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>

                <button class="btn btn-primary">{{ __('Save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
