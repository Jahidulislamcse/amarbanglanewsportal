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

@@if($blockUser)
{{-- ============================================================
     PACKAGE 1 GATE CUSTOM HIGH-FIDELITY OVERLAY
     ============================================================ --}}
<div id="packageGateOverlay" style="
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 999999;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
    font-family: 'Outfit', 'Inter', 'SolaimanLipi', sans-serif;
">
    <div style="
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 800px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        margin: auto;
        border: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        flex-direction: column;
        animation: pgModalFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    ">
        {{-- Header --}}
        <div style="
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            padding: 28px 32px;
            color: #ffffff;
        ">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
                <div style="
                    background: rgba(255, 255, 255, 0.2);
                    width: 44px; height: 44px;
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 20px;
                ">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <h4 style="margin: 0; font-weight: 700; font-size: 22px; color: #ffffff;">
                    আরও সংবাদ প্রকাশ করতে প্যাকেজ সংগ্রহ করুন
                </h4>
            </div>
            <p style="margin: 0; font-size: 14px; opacity: 0.95; line-height: 1.6; color: #ffe4e6; font-weight: 500;">
                আপনি ইতিমধ্যে ১০টি সংবাদ প্রকাশ করেছেন। আমাদের সাথে থাকার জন্য আপনাকে আন্তরিক ধন্যবাদ! আরও সংবাদ প্রকাশ করতে এবং আপনার সাংবাদিকতার পরিচয়কে আরও পেশাদার করতে (যেমন: অফিসিয়াল আইডি কার্ড ও ভিজিটিং কার্ড) অনুগ্রহ করে নিচের প্যাকেজটি সংগ্রহ করুন।
            </p>
        </div>

        {{-- Body --}}
        <div style="padding: 32px; background: #f8fafc; overflow-y: auto; max-height: 70vh; text-align: left;">
            @if($package1Products->count())
                <h6 style="font-weight: 700; color: #1e293b; margin: 0 0 16px 0; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-box-open text-rose-600"></i> প্যাকেজে অন্তর্ভুক্ত সামগ্রীসমূহ
                </h6>

                {{-- Products List --}}
                <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 12px 16px; font-weight: 600; color: #475569; font-size: 13px; width: 80px;">ছবি</th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #475569; font-size: 13px;">পণ্যের নাম</th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #475569; font-size: 13px; text-align: right; width: 150px;">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $packageTotal = 0;
                                $hasExcluded = false;
                            @endphp
                            @foreach($package1Products as $prod)
                                @php
                                    $isPurchased = in_array($prod->id, $purchasedProductIds);
                                    if (!$isPurchased) {
                                        $packageTotal += $prod->price;
                                    } else {
                                        $hasExcluded = true;
                                    }
                                    $imgSrc = $prod->primaryImage
                                        ? asset('assets/images/products/' . $prod->primaryImage->image_path)
                                        : asset('assets/images/noimage.png');
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9; {{ $isPurchased ? 'background-color: #f8fafc;' : '' }}">
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                        <img src="{{ $imgSrc }}" width="50" height="50"
                                             style="object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; {{ $isPurchased ? 'opacity: 0.5;' : '' }}">
                                    </td>
                                    <td style="padding: 12px 16px; vertical-align: middle; font-weight: 600; color: {{ $isPurchased ? '#94a3b8' : '#1e293b' }}; font-size: 14px;">
                                        {{ $prod->name }}
                                        @if($isPurchased)
                                            <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 9999px; margin-left: 8px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-check-circle" style="font-size: 10px;"></i> ইতোমধ্যে ক্রয়কৃত (বাদ দেওয়া হয়েছে)
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; vertical-align: middle; text-align: right; font-weight: 700; font-size: 14px;">
                                        @if($isPurchased)
                                            <del style="color: #94a3b8; margin-right: 8px;">৳{{ number_format($prod->price, 0) }}</del>
                                            <span style="color: #ef4444;">৳০</span>
                                        @else
                                            <span style="color: #10b981;">৳{{ number_format($prod->price, 0) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #fff1f2; border-top: 1px solid #ffe4e6;">
                                <td colspan="2" style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b; font-size: 14px;">
                                    মোট পণ্যের মূল্য:
                                </td>
                                <td style="padding: 16px; text-align: right; font-weight: 800; color: #e11d48; font-size: 16px;">
                                    ৳{{ number_format($packageTotal, 0) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Checkout Form --}}
                <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <h6 style="font-weight: 700; color: #1e293b; margin: 0 0 20px 0; font-size: 15px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <i class="fas fa-shopping-basket text-rose-600"></i> ডেলিভারি ও পেমেন্ট তথ্য প্রদান করুন
                    </h6>

                    <form action="{{ route('product.pay') }}" method="POST" id="packageGatePayForm">
                        @csrf

                        @foreach($package1Products as $prod)
                            @if(!in_array($prod->id, $purchasedProductIds))
                                <input type="hidden" name="product_ids[]" value="{{ $prod->id }}">
                                <input type="hidden" name="quantities[]" value="1">
                            @endif
                        @endforeach

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 6px;">ফোন নম্বর *</label>
                                <input type="text" name="phone_number" class="form-control"
                                       value="{{ auth()->user()->phone ?? '' }}"
                                       placeholder="01XXXXXXXXX" required
                                       style="height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; width: 100%;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 6px;">ডেলিভারি জোন *</label>
                                <select name="delivery_zone" id="pgDeliveryZone" class="form-control" required
                                        style="height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; width: 100%;">
                                    <option value="inside" data-charge="80">ঢাকার ভিতরে (৳ ৮০)</option>
                                    <option value="outside" data-charge="120" selected>ঢাকার বাইরে (৳ ১২০)</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 6px;">ডেলিভারি ঠিকানা *</label>
                            <textarea name="address" class="form-control" rows="3"
                                      placeholder="আপনার সম্পূর্ণ ডেলিভারি ঠিকানা লিখুন (গ্রাম, ডাকঘর, থানা, জেলা)" required
                                      style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; width: 100%; padding: 10px;"></textarea>
                            <span style="display: block; font-size: 12px; color: #64748b; margin-top: 6px; font-weight: 500;">
                                🚚 ডেলিভারি চার্জ: ঢাকার ভিতরে ৮০ টাকা, ঢাকার বাইরে ১২০ টাকা।
                            </span>
                        </div>

                        <div style="background: #f1f5f9; border-radius: 12px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border: 1px dashed #cbd5e1;">
                            <span style="font-weight: 700; color: #334155; font-size: 14px;">সর্বমোট পরিশোধযোগ্য মূল্য (পণ্য + ডেলিভারি চার্জ):</span>
                            <span style="font-weight: 800; color: #e11d48; font-size: 20px;" id="pgGrandTotal">
                                ৳{{ number_format($packageTotal + 120, 0) }}
                            </span>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" style="
                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                color: #ffffff;
                                font-weight: 700;
                                font-size: 16px;
                                border: none;
                                padding: 14px 40px;
                                border-radius: 30px;
                                cursor: pointer;
                                box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
                                display: inline-flex;
                                align-items: center;
                                gap: 10px;
                                transition: all 0.2s ease;
                            ">
                                <i class="fas fa-shopping-cart"></i> অর্ডার সম্পন্ন করুন ও পেমেন্ট করুন
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; text-align: center; color: #b45309; font-weight: 600;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i> এই মুহূর্তে প্যাকেজে কোনো পণ্য নেই। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes pgModalFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
document.getElementById('pgDeliveryZone') && document.getElementById('pgDeliveryZone').addEventListener('change', function() {
    const charge = parseInt(this.options[this.selectedIndex].dataset.charge || '120', 10);
    const products = {{ $packageTotal ?? 0 }};
    document.getElementById('pgGrandTotal').textContent = '৳' + (products + charge).toLocaleString('en-BD');
});
</script>
@endif� অনলাইনে পেমেন্ট করুন
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