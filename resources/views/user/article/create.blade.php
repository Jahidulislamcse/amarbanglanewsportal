@extends('layouts.user')

@section('content')

<style>
.select-cover-img {
    border: 3px solid transparent;
}
.select-cover-img.active {
    border-color: #28a745;
}
.cover-scroll-container {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 5px;
}
</style>

<div class="content-area">

@if($blockUser)

<div id="packageGateOverlay" style="
    position: fixed; inset: 0; z-index: 1040;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(3px);
"></div>

<div class="modal fade show d-block" id="packageGateModal" tabindex="-1"
     aria-labelledby="packageGateModalLabel" aria-modal="true"
     style="z-index:1050;">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">

            <div class="modal-header text-white" style="background: linear-gradient(135deg,#c0392b,#e74c3c); padding:20px 28px;">
                <div>
                    <h5 class="modal-title font-weight-bold mb-1" id="packageGateModalLabel">
                        <i class="fas fa-lock mr-2"></i> সংবাদ যোগ করতে নির্ধারিত প্যাকেজ সংগ্রহ করুন
                    </h5>
                    <p class="mb-0" style="font-size:13px; opacity:.9; color:white;">
                        আপনার সাংবাদিকতার পরিচয়কে আরও পেশাদার করুন। অফিসিয়াল আইডি কার্ড, ভিজিটিং কার্ডসহ প্রয়োজনীয় সাংবাদিকতা সামগ্রী এবং আরও সংবাদ প্রকাশের সুবিধা পেতে নিচের প্যাকেজটি অর্ডার করুন।
                    </p>
                </div>
            </div>

            <div class="modal-body" style="padding:24px 28px; background:#f8f9fa;">

                @if($package1Products->count())
                <h6 class="font-weight-bold text-dark mb-3" style="font-size:15px;">
                    <i class="fas fa-box-open mr-1 text-danger"></i> প্যাকেজে — অন্তর্ভুক্ত পণ্যসমূহ
                </h6>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered bg-white mb-0" style="border-radius:10px; overflow:hidden;">
                        <thead style="background:#c0392b; color:#fff;">
                            <tr>
                                <th style="width:70px;">ছবি</th>
                                <th>পণ্যের নাম</th>
                                <th class="text-right" style="width:120px;">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $packageTotal = 0; @endphp
                            @foreach($package1Products as $prod)
                                @php
                                    $packageTotal += $prod->price;
                                    $imgSrc = $prod->primaryImage
                                        ? asset('assets/images/products/' . $prod->primaryImage->image_path)
                                        : asset('assets/images/noimage.png');
                                @endphp
                                <tr>
                                    <td class="text-center align-middle" style="padding:8px;">
                                        <img src="{{ $imgSrc }}" width="55" height="55"
                                             style="object-fit:cover; border-radius:6px; border:1px solid #dee2e6;">
                                    </td>
                                    <td class="align-middle font-weight-bold" style="font-size:14px;">
                                        {{ $prod->name }}
                                    </td>
                                    <td class="align-middle text-right text-success font-weight-bold" style="font-size:15px;">
                                        ৳ {{ number_format($prod->price, 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background:#fff3f3;">
                            <tr>
                                <td colspan="2" class="text-right font-weight-bold text-dark" style="font-size:15px; padding:10px 12px;">
                                    মোট পণ্য মূল্য:
                                </td>
                                <td class="text-right font-weight-bold text-danger" style="font-size:16px; padding:10px 12px;">
                                    ৳ {{ number_format($packageTotal, 0) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="bg-white rounded p-4 border" style="border-radius:12px !important;">
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-credit-card mr-1 text-danger"></i> এখনই অর্ডার করুন
                    </h6>

                    <form action="{{ route('product.pay') }}" method="POST" id="packageGatePayForm">
                        @csrf

                        @foreach($package1Products as $prod)
                            <input type="hidden" name="product_ids[]" value="{{ $prod->id }}">
                            <input type="hidden" name="quantities[]" value="1">
                        @endforeach

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold" style="font-size:13px;">ফোন নম্বর *</label>
                                <input type="text" name="phone_number" class="form-control"
                                       value="{{ auth()->user()->phone ?? '' }}"
                                       placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold" style="font-size:13px;">ডেলিভারি জোন *</label>
                                <select name="delivery_zone" id="pgDeliveryZone" class="form-control" required>
                                    <option value="inside" data-charge="80">ঢাকার ভিতরে (৳ 80)</option>
                                    <option value="outside" data-charge="120" selected>ঢাকার বাইরে (৳ 120)</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="font-weight-bold" style="font-size:13px;">ডেলিভারি ঠিকানা *</label>
                                <textarea name="address" class="form-control" rows="3"
                                          placeholder="আপনার সম্পূর্ণ ঠিকানা লিখুন (গ্রাম, থানা, জেলা)" required></textarea>
                                <small class="text-muted">🚚 ডেলিভারি চার্জ: ঢাকার ভিতরে ৮০ টাকা, বাইরে ১২০ টাকা।</small>
                            </div>
                        </div>

                        <div class="bg-light rounded p-3 mb-3 border d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark">সর্বমোট (পণ্য + ডেলিভারি):</span>
                            <span class="font-weight-bold text-danger" style="font-size:18px;" id="pgGrandTotal">
                                ৳ {{ number_format($packageTotal + 120, 0) }}
                            </span>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-danger btn-lg font-weight-bold px-5"
                                    style="border-radius:30px; letter-spacing:.5px;">
                                <i class="fas fa-shopping-cart mr-2"></i> এখনই অর্ডার করুন ও অনলাইনে পেমেন্ট করুন
                            </button>
                        </div>
                    </form>
                </div>

                @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    এই মুহূর্তে Package 1-এ কোনো পণ্য নেই। অনুগ্রহ করে পরে আবার চেষ্টা করুন।
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('pgDeliveryZone') && document.getElementById('pgDeliveryZone').addEventListener('change', function() {
    const charge = parseInt(this.options[this.selectedIndex].dataset.charge || '120', 10);
    const products = {{ $packageTotal ?? 0 }};
    document.getElementById('pgGrandTotal').textContent = '৳ ' + (products + charge).toLocaleString('en-BD');
});
</script>

@endif

    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Add News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}">{{ __('News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('includes.admin.form-both')
    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <form id="geniusformdata2" action="{{ route('user.article.store')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="is_pending">
        <input type="hidden" name="post_type">

        <div class="row">
            <div class="col-lg-8">
                <div class="add-product-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product-description">
                                <div class="body-area">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Language') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <select name="language_id" id="article_language_id">
                                                <option value="1" selected>বাংলা</option>
                                            </select>
                                        </div>
                                    </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Title') }} *</h4>
                                                    <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field" name="title"
                                                    placeholder="{{ __('Title') }}" id="title" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Slug') }} *</h4>
                                                    <p class="sub-heading">{{ __('In English') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field" id="slug" name="slug"
                                                    placeholder="{{ __('Slug') }}" value="">
                                                    <p id="slugCheck"></p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Keyword meta tag') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="input-field textarea" name="meta_tag" placeholder="{{__('Keyword meta tag')}}"></textarea>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Tags') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="{{__('Tags')}}" class="tags input-field" id="tags" name="tags" value="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{__('Description')}} *
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 description">
                                                <textarea class="nic-edit" name="description" placeholder="{{__('Description')}}" id="description"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{__('Current Featured Image')}} </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="img-upload custom-image-upload">
                                                    <div class="img-upload custom-image-upload text-center">

                                                        <img id="preview-img"
                                                             src="{{ asset('assets/admin/images/upload.png') }}"
                                                             class="img-thumbnail mb-2"
                                                             style="max-width:100%; max-height:250px; object-fit:contain;">
                                                    
                                                        <div>
                                                            <label for="image-upload" class="btn btn-primary btn-sm">
                                                                <i class="icofont-upload-alt"></i> {{ __('Upload Image') }}
                                                            </label>
                                                            <input type="file" name="image_big" id="image-upload" style="display:none;">
                                                        </div>
                                                    
                                                    </div>
                                                        <p class="text">{{__('Prefered Size: (600x600) or Square Sized Image')}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="row mt-2">
                                            <div class="col-lg-12">
                                                <p class="text">{{ __('Image Note: Like.. "Collected" etc.') }}</p>
                                                <input type="text" class="input-field" name="image_note" placeholder="{{ __('Add a note about this image') }}">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 categoryDiv" style="display:none;">
                    <div class="col-lg-12">
                        <div class="add-product-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">
                                        <div class="body-area">

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Category') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <select name="category_id" id="article_parent_id">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Sub Category') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <select name="subcategories_id" id="subcategory_id">
                                                    <option value="">{{__('Please add a subcategory(if any)')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="add-product-content">
                            <div class="row">
                                <div class="col-lg-12">
                                     <div class="product-description">
                                    <div class="body-area">  

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Add to Feature') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                            <div class="custom-control custom-radio d-inline-block mr-5">
                                                <input type="radio" class="custom-control-input" id="is_feature1"  name="is_feature" value="1">
                                                <label class="custom-control-label" for="is_feature1">{{__('Yes')}}</label>
                                            </div>   
                                            <div class="custom-control custom-radio d-inline-block">
                                                <input type="radio" class="custom-control-input" id="is_feature2"  name="is_feature" value="0" checked>
                                                <label class="custom-control-label" for="is_feature2">{{__('No')}}</label>
                                            </div>   
                                            </div>
                                        </div>

                                     

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Add to Breaking News') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
											    <input type="hidden"  name="is_slider" value="0">
												<input type="hidden"  name="slider_right" value="0">
                                                <div class="custom-control custom-radio d-inline-block mr-5">
                                                    <input type="radio" class="custom-control-input" id="is_trending" name="is_trending" value="1">
                                                    <label class="custom-control-label" for="is_trending">{{__('Yes')}}</label>
                                                </div>  

                                                <div class="custom-control custom-radio d-inline-block mr-5">
                                                    <input type="radio" class="custom-control-input" id="is_trending2" name="is_trending" value="0" checked>
                                                    <label class="custom-control-label" for="is_trending2">{{__('No')}}</label>
                                                </div>  

                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
									
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="add-product-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">
                                        <div class="body-area">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Schedule News') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <input type="checkbox" class="schedule" name="schedule_post" value="1">
                                                </div>
                                            </div>

                                            <div class="row datepick" style="display:none;">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading"></h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input id="from" class="input-field" type="text" name="schedule_post_date" autocomplete="off" placeholder="{{__('Start Date')}}" required="" value="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">

                                                </div>
                                                <div class="col-lg-12">
                                                    <input type="submit" data-draft="1" class="btn btn-warning submit-btn1 saveAsDraft" value="Save as Drafts">
													<?php if(auth()->user()->is_approve==1){?>
                                                    <input type="submit" data-draft="0" class="btn btn-success submit-btn1 addPost" value="Save News">
													<?php }?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade-scale" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="top-area">
                <div class="row">
                    <div class="col-sm-6 text-right">
                        <div class="upload-img-btn">
                            <label id="article_gallery"><i class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> {{ __('Done') }}</a>
                    </div>
                    <div class="col-sm-12 text-center">( <small>{{ __('You can upload multiple Images.') }}</small> )</div>
                </div>
            </div>
            <div class="gallery-images">
                <div class="selected-image">
                    <div class="row">


                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/admin/js/user_create.js') }}"></script>
<script src="{{ asset('assets/admin/js/tagify.js') }}"></script>

<script>
    $("#article_language_id").trigger('change');
    $('.tags').tagify();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const fileInput = document.getElementById('image-upload');
        const previewImg = document.getElementById('preview-img');

        if (fileInput && previewImg) {

            fileInput.addEventListener('change', function () {

                if (this.files && this.files[0]) {

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                    };

                    reader.readAsDataURL(this.files[0]);

                } else {

                    previewImg.src = "{{ asset('assets/admin/images/upload.png') }}";

                }

            });

        }

    });
</script>

@endsection