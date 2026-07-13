@extends('layouts.admin')

@section('content')
<div class="content-area ">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Products') }}</h4>
                <div class="btn-area mt-4 mb-4">
                    <a href="{{ route('admin.products.create') }}" class="add-btn">
                        <i class="fas fa-plus"></i>{{ __('Add New Product') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <div class="table-responsiv">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Buying Price') }}</th>
                                    <th>{{ __('Selling Price') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        @if($product->primaryImage)
                                            <img src="{{ asset('assets/images/products/' . $product->primaryImage->image_path) }}" width="70" height="70">
                                        @else
                                            <img src="{{ asset('assets/images/noimage.png') }}" width="70" height="70">
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->buying_price }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                        <a href="{{ route('admin.products.delete', $product->id) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
