@extends('layouts.admin')

@section('styles')
<style>
    .preview-img {
    position: relative;
    display: inline-block;
    margin: 10px;
}

.preview-img img {
    border-radius: 5px;
}

.preview-img .remove-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff4d4f;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-weight: bold;
    cursor: pointer;
    line-height: 18px;
}
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <h4 class="heading">{{ __('Add Product') }}</h4>
    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>{{ __('Category') }}</label>
            <select name="category_id" class="form-control" required>
                @foreach($product_categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Description') }}</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        
        <div class="form-group">
            <label>{{ __('Buying Price') }}</label>
            <input type="number" step="0.01" name="buying_price" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Selling Price') }}</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Stock') }}</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Status') }}</label>
            <select name="is_active" class="form-control" required>
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Inactive') }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Package') }}</label>
            <select name="package" class="form-control">
                <option value="">{{ __('-- No Package --') }}</option>
                <option value="package1">Package 1</option>
                <option value="package2">Package 2</option>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Images') }}</label>
            <input type="file" name="images[]" multiple class="form-control" id="imageInput">
        </div>
        
        <div id="imagePreview"></div>


        <button class="btn btn-primary">{{ __('Save') }}</button>
    </form>
</div>

<script>
document.getElementById('createImages').addEventListener('change', function(e){
    let preview = document.getElementById('createPreview');
    preview.innerHTML = '';

    Array.from(e.target.files).forEach(file => {
        let reader = new FileReader();
        reader.onload = function(event){
            let img = document.createElement('img');
            img.src = event.target.result;
            img.width = 100;
            img.height = 100;
            img.style.margin = '5px';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});
</script>

<script>
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function() {
    imagePreview.innerHTML = '';

    Array.from(this.files).forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
            const html = `
                <div class="preview-img" id="img-${index}">
                    <img src="${e.target.result}" width="120" height="120">
                    <button type="button" class="remove-btn" onclick="removeImage(${index})">×</button>
                </div>
            `;
            imagePreview.innerHTML += html;
        }

        reader.readAsDataURL(file);
    });
});

function removeImage(index) {
    const dt = new DataTransfer();
    const files = imageInput.files;

    Array.from(files).forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    imageInput.files = dt.files;
    document.getElementById(`img-${index}`).remove();
}
</script>


@endsection
