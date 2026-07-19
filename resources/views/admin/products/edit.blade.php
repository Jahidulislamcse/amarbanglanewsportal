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
        <h4 class="heading">{{ __('Edit Product') }}</h4>
    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>{{ __('Category') }}</label>
            <select name="category_id" class="form-control" required>
                @foreach($product_categories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Description') }}</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>
        
        <div class="form-group">
            <label>{{ __('Buying Price') }}</label>
            <input type="number" step="0.01" name="buying_price" value="{{ $product->buying_price }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Selling Price') }}</label>
            <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Stock') }}</label>
            <input type="number" name="stock" value="{{ $product->stock }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>{{ __('Status') }}</label>
            <select name="is_active" class="form-control" required>
                <option value="1" {{ $product->is_active ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Package') }}</label>
            <select name="package" class="form-control">
                <option value="" {{ !$product->package ? 'selected' : '' }}>{{ __('-- No Package --') }}</option>
                <option value="package1" {{ $product->package === 'package1' ? 'selected' : '' }}>Package 1</option>
                <option value="package2" {{ $product->package === 'package2' ? 'selected' : '' }}>Package 2</option>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Add Images') }}</label>
            <input type="file" name="images[]" multiple class="form-control" id="imageInputEdit">
        </div>
        
        <div id="imagePreviewEdit"></div>
        
        <hr>
        
        <div class="form-group">
            <label>{{ __('Existing Images') }}</label>
            <div class="row">
                @foreach($product->images as $img)
                    <div class="col-md-2 mb-2">
                        <img src="{{ asset('assets/images/products/' . $img->image_path) }}" width="100" height="100" class="img-thumbnail">
                        <div class="form-check">
                            <input type="checkbox" name="remove_images[]" value="{{ $img->id }}">
                            <label>{{ __('Remove') }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div id="editPreview" class="row"></div>

        <button class="btn btn-primary">{{ __('Update') }}</button>
    </form>
</div>

<script>
document.getElementById('editImages').addEventListener('change', function(e){
    let preview = document.getElementById('editPreview');
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
const imageInputEdit = document.getElementById('imageInputEdit');
const imagePreviewEdit = document.getElementById('imagePreviewEdit');

imageInputEdit.addEventListener('change', function() {
    imagePreviewEdit.innerHTML = '';

    Array.from(this.files).forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = function(e) {
            const html = `
                <div class="preview-img" id="edit-img-${index}">
                    <img src="${e.target.result}" width="120" height="120">
                    <button type="button" class="remove-btn" onclick="removeEditImage(${index})">×</button>
                </div>
            `;
            imagePreviewEdit.innerHTML += html;
        }

        reader.readAsDataURL(file);
    });
});

function removeEditImage(index) {
    const dt = new DataTransfer();
    const files = imageInputEdit.files;

    Array.from(files).forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    imageInputEdit.files = dt.files;
    document.getElementById(`edit-img-${index}`).remove();
}
</script>


@endsection
