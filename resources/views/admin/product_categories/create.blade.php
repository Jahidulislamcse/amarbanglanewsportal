<form method="POST" action="{{ route('admin.productCategories.store') }}">
    @csrf
    <div class="form-group">
        <label>{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
</form>
