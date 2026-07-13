<form method="POST" action="{{ route('admin.productCategories.update', $category->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>{{ __('Name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
    </div>

    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
</form>
