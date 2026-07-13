@extends('layouts.user')
<style>

    .reporter-widget .reporter-chip {
        position: fixed;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        z-index: 1500;
    }
    
    .reporter-widget .reporter-chip .btn {
        writing-mode: vertical-rl;
        text-orientation: mixed;
        padding: 12px 8px;
        min-height: 140px;
        border-radius: 10px 0 0 10px;
        background: #E61B2E;
        color: #fff;
        font-weight: 600;
        box-shadow: -2px 2px 10px rgba(0,0,0,.25);
    }
    
        .offer-wrap {
        margin-top: 25px
    }

    .offer-box {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: .3s;
        height: 100%;
    }

    .offer-box:hover {
        transform: translateY(-6px)
    }

    .offer-head {
        padding: 12px 18px;
        font-weight: 600;
        color: #fff;
        font-size: 15px;
    }
    
        .offer-body {
        padding: 18px;
        font-size: 14px;
        color: #444
    }

    .offer-body ul {
        padding-left: 18px;
        line-height: 1.9
    }

    .offer-highlight {
        font-weight: 700;
        color: #000
    }
    
        .head-promo {
        background: #fd7e14
    }
    
    .reporter-widget .chip-images {
        display: none; 
    }
    
    .reporter-widget.open .chip-images {
        display: flex;
    }
        
    .reporter-widget .reporter-chip .btn:hover {
        background-color: #FCB900;
    }
    
    .reporter-widget .reporter-chip .btn .pull-indicator {
        font-size: 18px;
        color: #fff;
        position: absolute;
        left: -15px; 
        top: 50%;
        transform: translateY(-50%) rotate(180deg); 
        transition: transform 0.3s ease;
    }
    
    .reporter-widget .reporter-panel.show ~ .reporter-chip .btn .pull-indicator {
        transform: translateY(-50%) rotate(0deg); 
    }

    .reporter-widget .reporter-chip .chip-images {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }
    
    .reporter-widget .reporter-chip .chip-images img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .reporter-widget .reporter-chip .chip-images img:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
 
    .reporter-widget .reporter-panel {
        position: fixed;
        top: 0;
        right: -300px; 
        width: 280px;
        height: 100%;
        background: #E61B2E;
        color: #fff;
        z-index: 1400;
        transition: right 0.3s ease;
        box-shadow: -2px 0 8px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
    }
    
    .reporter-widget .reporter-panel.show {
        right: 0;
    }
    
    .reporter-widget .reporter-panel .header {
        padding: 1rem;
        font-weight: 600;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: black;
    }
    
    .reporter-widget .reporter-panel .body {
        padding: 1rem;
        overflow-y: auto;
        flex: 1;
    }
    
    .reporter-widget .reporter-panel .reporter-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background-color: rgba(255,255,255,0.1);
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background 0.2s;
    }
    
    .reporter-widget .reporter-panel .reporter-item:hover {
        background-color: rgba(255,255,255,0.2);
    }
    
    .reporter-widget .reporter-panel .reporter-item img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
    }
    
    .reporter-widget .reporter-panel .reporter-info p {
        font-size: 13px;
        font-weight: 500;
        margin: 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    
    .reporter-widget .reporter-panel .reporter-item p,
    .reporter-widget .reporter-panel .reporter-info small,
    .reporter-widget .reporter-panel .no-reporters {
        color: #fff; 
    }
    
    .reporter-widget .reporter-panel .reporter-info small {
        font-size: 12px;
    }
    .reporter-item {
        position: relative;
        padding-top: 12px;
    }

    .rank-badge {
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        z-index: 5;
    }

    .reporter-item:nth-child(1) .rank-badge {
        background: gold;
        color: #000;
    }
    .reporter-item:nth-child(2) .rank-badge {
        background: silver;
        color: #000;
    }
    .reporter-item:nth-child(3) .rank-badge {
        background: #cd7f32; 
    }

    .reporter-widget {
        position: relative;
    }

    .reporter-chip {
        transition: right 0.3s ease;
    }

    .reporter-widget.open .reporter-chip {
        right: 280px; 
    }

    .pull-indicator {
        transition: transform 0.3s ease;
    }
    
    .reporter-widget.open .pull-indicator {
        transform: translateY(-50%) rotate(0deg);
    }

    .course-wrapper{
        margin: auto;
    }
    
    .course-title{
        font-size: 22px;
        font-weight: 600;
        color:#2c3e50;
    }
    
    .module-item{
        border-left: 3px solid #0d6efd;
        padding-left: 18px;
        margin-bottom: 35px;
        position: relative;
    }
    
    .module-item::before{
        content:'';
        position:absolute;
        left:-8px;
        top:6px;
        width:14px;
        height:14px;
        background:#0d6efd;
        border-radius:50%;
    }
    
    .module-title{
        font-size:16px;
        font-weight:600;
        color:#34495e;
    }
    
    .video-box iframe{
        width:100%;
        height:360px;
        border-radius:10px;
        border:1px solid #e9ecef;
    }
    
    .quiz-box{
        background:#f8f9fa;
        border:1px solid #e9ecef;
        border-radius:8px;
        padding:15px;
    }
</style>

@section('content')
<div class="content-area">

    <div class="reporter-widget" id="reporterWidget">
        <div class="reporter-chip">
            <button class="btn btn-primary" id="reporterToggleBtn">
                {{ __('Best Reporters Last week') }}
                
            </button>
        
            <div class="chip-images">
                @foreach($topReporters as $reporter)
                    <img 
                        src="{{ $reporter->photo ? asset('assets/images/admin/'.$reporter->photo) : asset('assets/images/default-user.png') }}" 
                        alt="{{ $reporter->name }}" 
                        title="{{ $reporter->name }}"
                    />
                @endforeach
            </div>
        </div>


    
       <div class="reporter-panel" id="reporterPanel">
            <div class="header">
                <span>{{ __('Best Reporters Last Week') }}</span>
                <button class="btn-close" id="reporterCloseBtn">X</button>
            </div>
            <div class="body">
                @forelse($topReporters as $key => $reporter)
                    <div class="reporter-item">
                        
                        {{-- Rank badge on top --}}
                        <div class="rank-badge">
                            @if($key == 0)
                                1st
                            @elseif($key == 1)
                                2nd
                            @elseif($key == 2)
                                3rd
                            @endif
                        </div>
        
                        <img src="{{ $reporter->photo ? asset('assets/images/admin/'.$reporter->photo) : asset('assets/images/default-user.png') }}" 
                             alt="{{ $reporter->name }}">
        
                        <div class="reporter-info">
                            <p class="mt-2">{{ $reporter->name }}</p>
                            <small>{{ $reporter->report_type_title }}</small>
                            <br>
                            <small>({{ $reporter->reporter_area_title }})</small>
                        </div>
                    </div>
                @empty
                    <p class="no-reporters">{{ __('No reporters found for last month') }}</p>
                @endforelse
            </div>
        </div>

    </div>


    <div class="row row-cards-one">

        @php
            $nextPayment = auth()->user()->next_payment_date;
        @endphp

        
        @if($nextPayment)
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg5">
                <div class="left">
                    <h6 style="font-size:19px;" class="title">{{ __('Next Payment Due In') }}</h6>
                    <span style="font-size:25px;" class="number" id="payment-countdown">--:--:--</span>
                    <small style="font-size:14px;">
                        {{ __('Due at') }}: {{ \Carbon\Carbon::parse($nextPayment)->format('d M Y, h:i A') }}
                    </small>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg5" style="padding:20px;">
                <div class="left">
                    <h6 class="title">{{ __('Division Admin') }}</h6>
                    
                    <p class="mb-4" style="font-size:12px; font-weight:600; margin:5px 0; color:#fff;">
                            Contact your division admin for any query
                        </p>
        
                    @if(!empty($admins) && count($admins) > 0)
                        @php
                            $admin = $admins[0]; 
                        @endphp
                        <p style="font-size:18px; font-weight:600; margin:5px 0; color:#fff;">
                            {{ $admin->name }}
                        </p>
                        <p style="font-size:16px; font-weight:500; margin:0; color:#fff;">
                            {{ $admin->phone }}
                        </p>
                    @else
                        <p style="font-size:16px; color:#fff;">{{ __('No desk reporter assigned') }}</p>
                    @endif
                </div>
                <div class="right d-flex align-self-center" style="">
                    <div class="icon" style="font-size:30px; margin-top: 60px;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        
        @if(auth()->user()->is_approve != 0)
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="mycard bg1">
                    <div class="left">
                        <h5 class="title">{{ __('News') }} </h5>
                            <span class="number">{{ $articles }}</span>
                        <a href="{{ route('user.post.index') }}?type=article" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="fab fa-blogger-b"></i>
                        </div>
                    </div>
                </div>
            </div>
    
            <!--<div class="col-md-12 col-lg-4 col-xl-3">-->
            <!--    <div class="mycard bg2">-->
            <!--        <div class="left">-->
            <!--            <h5 class="title">{{ __('Audio News') }}</h5>-->
            <!--                <span class="number">{{ $audios }}</span>-->
            <!--            <a href="{{ route('user.post.index') }}?type=audio" class="link">{{ __('View All') }}</a>-->
            <!--        </div>-->
            <!--        <div class="right d-flex align-self-center">-->
            <!--            <div class="icon">-->
            <!--                <i class="fas fa-hourglass"></i>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-md-12 col-lg-4 col-xl-3">-->
            <!--    <div class="mycard bg3">-->
            <!--        <div class="left">-->
            <!--            <h5 class="title">{{ __('Video News') }}</h5>-->
            <!--            <span class="number">{{ $videos }}</span>-->
            <!--            <a href="{{ route('user.post.index') }}?type=video" class="link">{{ __('View All') }}</a>-->
            <!--        </div>-->
            <!--        <div class="right d-flex align-self-center">-->
            <!--            <div class="icon">-->
            <!--                <i class="fas fa-pen-square"></i>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
    
           
    
            <div class="col-md-12 col-lg-4 col-xl-3">
                <div class="mycard bg2">
                    <div class="left">
                        <h6 class="title">{{ __('Pending') }}</h6>
                            <span class="number">{{ $pending_posts }}</span>
                        <a href="{{ route('user.pending.index') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="fas fa-hourglass"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-3">
                <div class="mycard bg3">
                    <div class="left">
                        <h5 class="title">{{ __('Draft') }}</h5>
                        <span class="number">{{ $drafts }}</span>
                        <a href="{{ route('user.draft.index') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon">
                            <i class="fas fa-pen-square"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 mt-3">
                <div class="mycard bg1" style="padding:20px;">
                    <div class="left">
            
                        <h5 class="title" style="font-size:14px;">
                            {{ __('এই অ্যাফিলিয়েট লিঙ্কটি শেয়ার করুন এবং যারা আপনার লিঙ্ক ব্যবহার করে রেজিস্ট্রেশন করবে তাদের সকল সার্ভিস গ্রহনে ৫ম জেনারেশন পর্যন্ত নির্দিষ্ট হারে কমিশন পান') }}
                        </h5>
            
                       
            
                            <input
                                type="text"
                                id="affiliateLink"
                                class="form-control"
                                value="{{ $affiliate_link }}"
                                readonly
                            >
            
                            <button
                                class="btn btn-primary mt-2"
                                onclick="copyAffiliateLink()">
                                Copy Link
                            </button>
            
                            <a
                                class="btn btn-success mt-2"
                                href="https://api.whatsapp.com/send?text={{ urlencode($affiliate_link) }}"
                                target="_blank">
                                WhatsApp
                            </a>
            
                            <a
                                class="btn btn-info mt-2"
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($affiliate_link) }}"
                                target="_blank">
                                Facebook
                            </a>
            
            
                    </div>
                </div>
            </div>
            
                                @php
                        function bn($number)
                        {
                            $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                            $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                            return str_replace($en, $bn, $number);
                        }
                    @endphp

                    {{-- Team Bonus --}}
                    <div class="col-md-6 mb-4">
                        <div class="offer-box">
                           
                            <div class="offer-head head-promo">
                                👥 <strong>আপনার রেফারকৃত সাংবাদিক টিম</strong> <br>
                            </div>
                    
                            <div class="offer-body team-scroll">
                                @php
                                    $firstGenUsers = $genUsers[1] ?? [];
                                    $count = count($firstGenUsers);
                                @endphp
                                
                                <div class="p-3 bg-light rounded border mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="font-weight-bold text-dark">টিম মেম্বার সংখ্যা: {{ bn($count) }} জন</span>
                                        <span class="badge badge-warning font-weight-bold" style="background-color: #f59e0b; color: #fff; font-size: 13px;">কমিশন: ১০%</span>
                                    </div>
                                </div>
                                
                                <div class="p-2 border rounded bg-white" style="max-height: 250px; overflow-y: auto;">
                                    @if ($count > 0)
                                        <ul class="mb-0 pl-0" style="list-style-type: none;">
                                            @foreach ($firstGenUsers as $u)
                                                <li class="mb-2 d-flex align-items-center text-dark font-weight-bold" style="font-size: 14.5px;">
                                                    <img src="{{ $u->photo ? asset('assets/images/admin/' . $u->photo) : asset('assets/images/default_user.png') }}" 
                                                         alt="Photo" 
                                                         class="mr-2" 
                                                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">
                                                    <div>
                                                        <div class="text-dark">{{ $u->name }}</div>
                                                        @if($u->district_name || $u->thana_name)
                                                            <small class="text-muted font-weight-normal" style="font-size: 11.5px; display: block; margin-top: 1px;">
                                                                <i class="fas fa-map-marker-alt text-danger mr-1" style="font-size: 10px;"></i>{{ implode(', ', array_filter([$u->thana_name, $u->district_name])) }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0 text-center py-3">টিমে কোনো সদস্য নেই</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Team Purchases Commission --}}
                    <div class="col-md-6 mb-4">
                        <div class="offer-box">
                           
                            <div class="offer-head head-promo" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                🛒 <strong>টিম মেম্বারের কেনাকাটা ও কমিশন</strong> <br>
                            </div>
                    
                            <div class="offer-body team-scroll" style="height: auto; max-height: 380px; overflow-y: auto;">
                                @if (count($team_purchases) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($team_purchases as $tp)
                                            <div class="list-group-item px-0 py-2 border-bottom">
                                                <div class="d-flex align-items-center mb-1">
                                                    <img src="{{ $tp->user->photo ? asset('assets/images/admin/' . $tp->user->photo) : asset('assets/images/default_user.png') }}" 
                                                         alt="Photo" 
                                                         class="mr-2" 
                                                         style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                                                    <span class="font-weight-bold text-dark" style="font-size: 13.5px;">{{ $tp->user->name ?? 'Deleted member' }}</span>
                                                </div>
                                                <div class="pl-4">
                                                    <small class="d-block text-muted">
                                                        <strong>ক্রয়কৃত পণ্য:</strong> 
                                                        @if($tp->order && $tp->order->items)
                                                            {{ implode(', ', $tp->order->items->map(function($item) {
                                                                return ($item->product->name ?? 'Product') . ' (x' . $item->quantity . ')';
                                                            })->toArray()) }}
                                                        @else
                                                            পণ্য (Order #{{ $tp->order_id }})
                                                        @endif
                                                    </small>
                                                    <small class="d-flex justify-content-between text-success font-weight-bold mt-1" style="font-size: 13px;">
                                                        <span>মূল্য: ৳{{ number_format($tp->order_amount, 2) }}</span>
                                                        <span>কমিশন (১০%): +৳{{ number_format($tp->commission_amount, 2) }}</span>
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0 text-center py-4">টিমের কেনাকাটার কোনো তথ্য নেই</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
            
            <div class="col-md-12 products-section">
                <div class="row p-4">
                    <div class="col-md-12">
                        <h4 class="mb-3">{{ __('সাংবাদিকতা সম্পর্কিত পণ্য কিনুন') }}</h4>
                        
        
                    </div>
                
                    <form id="paySelectedForm" onsubmit="event.preventDefault();" class="w-100">
                        @csrf
                        <div class="row">
                    @forelse($products as $product)
                        <div class="col-6 col-sm-6 col-md-3 col-lg-2 p-2">
                            <div class="card h-100 shadow-sm p-3 product-card"
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ number_format($product->price, 2) }}"
                                 data-stock="{{ $product->stock }}"
                                 data-desc="{{ $product->description }}"
                                 data-slug="{{ $product->slug }}"
                                 data-images='@json($product->images->pluck("image_path"))'
                            >
                                <img
                                    style="height: 120px;"
                                    src="{{ $product->firstImage
                                        ? asset('assets/images/products/'.$product->firstImage->image_path)
                                        : 'https://static.vecteezy.com/system/resources/previews/048/910/778/original/default-image-missing-placeholder-free-vector.jpg' }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid mb-2"
                                />
                                <div class="">
                                    <span class="product-name">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</span>
    
                    
                                    <p class="mb-1">
                                        <strong>{{ __('Price:') }}</strong>
                                        ৳ {{ number_format($product->price, 2) }}
                                    </p>
                                    <div class="product-order-control mt-2" onclick="event.stopPropagation();">
                                        <label class="d-block small mb-1">
                                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                            Select
                                        </label>
                                        <input type="number"
                                               name="quantities[{{ $product->id }}]"
                                               class="form-control form-control-sm"
                                               value="{{ $product->stock > 0 ? 1 : 0 }}"
                                               min="1"
                                               max="{{ $product->stock }}"
                                               {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <small class="text-muted">Stock: {{ $product->stock }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <p class="text-muted text-center">
                                {{ __('No products available at the moment.') }}
                            </p>
                        </div>
                    @endforelse
                        </div>
                        @if($products->count())
                            <div class="row mt-3">
                                <div class="col-md-4 mb-2">
                                    <input type="text"
                                           name="phone_number"
                                           id="paySelectedPhone"
                                           class="form-control"
                                           value="{{ auth()->user()->phone ?? '' }}"
                                           placeholder="Phone number"
                                           required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input type="text"
                                           name="address"
                                           id="paySelectedAddress"
                                           class="form-control"
                                           placeholder="Delivery address"
                                           required>
                                    <small class="text-danger font-weight-bold d-block mt-1">
                                        ⚠️ পণ্যটি পেতে অনুগ্রহ করে বিস্তারিত ঠিকানা প্রদান করুন (গ্রাম, ডাকঘর, থানা, জেলা), অন্যথায় ডেলিভারি বিলম্বিত হতে পারে।
                                    </small>
                                    <small class="text-muted d-block mt-1 font-weight-bold">
                                        🚚 ডেলিভারি চার্জ: ঢাকার ভিতরে ৬০ টাকা, ঢাকার বাইরে ১২০ টাকা।
                                    </small>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <button type="button" class="btn btn-success w-100" id="paySelectedButton">
                                        <i class="fas fa-credit-card"></i>
                                        Pay Selected
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="col-md-12 mt-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3 p-2">My Product Orders</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Order Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}<br><small>{{ $order->transaction_id }}</small></td>
                                            <td>
                                                <ul class="mb-0 pl-3">
                                                    @foreach($order->items as $item)
                                                        <li>
                                                            {{ $item->product->name ?? 'Deleted product' }}
                                                            x {{ $item->quantity }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>BDT {{ number_format($order->total_amount, 2) }}</td>
                                            <td>{{ ucfirst(optional($order->payment)->status ?? 'pending') }}</td>
                                            <td>{{ ucfirst($order->status) }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No orders yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
             
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg1" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Current Balance (Including all)') }}</h5>
                        <span class="number">{{ $balance }}</span>
    
                    </div>
                    <div class="right d-flex align-self-center">
                       <div class="icon" style="font-size:25px;">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
            </div>
    		
    		  <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg4" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Total Views') }}</h5>
                        <span class="number">{{ $total_views }}</span>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon" style="font-size:25px;">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>
            </div>
    		
    		  <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg3" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Earning from views (Lifetime)') }}</h5>
                        <span class="number">{{ $total_commission }}</span>
                    </div>
                    <div class="right d-flex align-self-center">
                         <div class="icon" style="font-size:25px;">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard" style="padding:20px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="left">
                        <h5 class="title" style="font-size:14px; color:#fff;">{{ __('Team Commission (Lifetime)') }}</h5>
                        <span class="number" style="color:#fff;">{{ $product_commission }}</span>
                    </div>
                    <div class="right d-flex align-self-center">
                         <div class="icon" style="font-size:25px; color:#fff; background:rgba(255,255,255,0.2);">
                            <i class="fas fa-percent"></i>
                        </div>
                    </div>
                </div>
            </div>
    		
    		  <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg2" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Total Withdraw') }}</h5>
                        <span class="number">{{ $total_withdraw }}</span>
    
                    </div>
                    <div class="right d-flex align-self-center">
                         <div class="icon" style="font-size:25px;">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
            </div>
    		<div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg1" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Total Earning (Lifetime)') }}</h5>
                        <span class="number">{{ $balance+$total_withdraw }}</span>
    
                    </div>
                    <div class="right d-flex align-self-center">
                       <div class="icon" style="font-size:25px;">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 col-lg-4 col-xl-3">
                <div class="mycard bg4">
                    <div class="left">
                        <h5 class="title" style="font-size:16px;">{{ __('Schedule Post') }}</h5>
                        <span class="number">{{ $schedules }}</span>
                        <a href="{{ route('user.schedule.index') }}" class="link">{{ __('View All') }}</a>
                    </div>
                    <div class="right d-flex align-self-center">
                        <div class="icon" style="font-size:25px;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg5">
                <div class="left">
                    <h5 class="title">{{ __('Rss Feeds') }}</h5>
                    <span class="number">{{ $rss}}</span>
                    <a href="{{ route('user.rss.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-rss"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg6">
                <div class="left">
                    <h5 class="title">{{ __('Polls') }}</h5>
                    <span class="number">{{ $polls}}</span>
                    <a href="{{ route('addPolls.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-poll"></i>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    
    @if(auth()->user()->is_approve != 0)
        <div class="modal fade" id="productsModal" tabindex="-1" aria-labelledby="productsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h6 class="modal-title" id="productsModalLabel">
                  {{ __('সাংবাদিকতা সম্পর্কিত পণ্য কিনুন (ক্রয় করতে ডিভিশন অ্যাডমিনের সাথে যোগাযোগ করুন)') }}
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  @forelse($products as $product)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-3 p-2">
                      <div class="card h-100 shadow-sm p-2 product-card"
                           data-id="{{ $product->id }}"
                           data-name="{{ $product->name }}"
                           data-price="{{ number_format($product->price, 2) }}"
                           data-stock="{{ $product->stock }}"
                           data-desc="{{ $product->description }}"
                           data-images='@json($product->images->pluck("image_path"))'>
                        <img
                          style="height: 120px;"
                          src="{{ $product->firstImage
                              ? asset('assets/images/products/'.$product->firstImage->image_path)
                              : 'https://static.vecteezy.com/system/resources/previews/048/910/778/original/default-image-missing-placeholder-free-vector.jpg' }}"
                          alt="{{ $product->name }}"
                          class="img-fluid mb-2"
                        />
                        <div class="">
                          <h7 class="">
                            {{ \Illuminate\Support\Str::limit($product->name, 40) }}
                          </h7>
        
                          <p class="mb-1">
                            <strong>{{ __('Price:') }}</strong>
                            ৳ {{ number_format($product->price, 2) }}
                          </p>
                        </div>
                      </div>
                    </div>
                  @empty
                    <div class="col-md-12">
                      <p class="text-muted text-center">
                        {{ __('No products available at the moment.') }}
                      </p>
                    </div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
    @endif
    
    @php
        $cacheKey = 'products_modal_shown_' . auth()->id();
        $alreadyShown = cache()->has($cacheKey);
    @endphp
    
    <div class="col-md-12 offer-wrap">
            <div class="mt-5 ">
                <h4 class="mb-3">Books</h4>
                <div class="row">
                    @foreach($books as $book)
                        @php
                            // Check if the user has a purchase record for this book, prioritizing approved, then pending, then rejected
                            $purchase = \App\Models\BookPurchase::where('user_id', auth()->id())
                                         ->where('book_id', $book->id)
                                         ->orderByRaw("CASE WHEN status = 'approved' THEN 1 WHEN status = 'pending' THEN 2 ELSE 3 END")
                                         ->first();
                        @endphp
                    
                        <div class="col-md-3 mb-4 ">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ $book->cover ? asset('assets/images/books/'.$book->cover) : asset('assets/images/default-cover.png') }}" 
                                     class="card-img-top" 
                                     alt="{{ $book->title }}" style="height:200px; object-fit:cover;">
                    
                                <div class="card-body d-flex flex-column pb-3">
                                    <h6 class="card-title mt-3">{{ $book->title }}</h6>
                                    <p class="text-muted mb-2">৳{{ $book->price }}</p>
                    
                                    @if($purchase && $purchase->status === 'approved')
                                        <button class="btn btn-success btn-sm mt-auto w-100" 
                                                onclick="openPDF('{{ asset('assets/pdfs/books/'.$book->pdf_file) }}')">
                                            Open Book
                                        </button>
                                    @elseif($purchase && $purchase->status === 'pending')
                                        <button class="btn btn-secondary btn-sm mt-auto w-100" disabled>
                                            Payment Waiting for approval
                                        </button>
                                    @else
                                       <!-- In your Blade template, for each book -->
                                        <button class="btn btn-warning mt-auto w-100 toggle-pay-section" 
                                                data-target="#paySection{{ $book->id }}">
                                            Locked – Pay Now
                                        </button>
                                        
                                         <div class="pay-section collapse mt-2" id="paySection{{ $book->id }}">
                                            <form action="{{ route('book.pay', $book->id) }}" method="POST" class="p-2 bg-light">
                                                @csrf
                                                <p>Price: <strong>৳{{ $book->price }}</strong></p>
                                                <p>Continue to EPS to complete this payment automatically.</p>

                                                <div class="mb-2">
                                                    <label for="phone_number_{{ $book->id }}">Contact Number</label>
                                                    <input type="text" class="form-control" name="phone_number" id="phone_number_{{ $book->id }}" value="{{ auth()->user()->phone ?? '' }}" required>
                                                </div>
                                        
                                                <input type="hidden" name="operator" value="EPS">
                                        
                                                <button type="submit" class="btn btn-primary w-100">Pay with EPS</button>
                                            </form>
                                        </div>
                                    @endif
                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
            </div>
            

            <div class="modal fade" id="pdfViewerModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body" style="height:90vh;">
                            <iframe id="pdfViewerFrame" width="100%" height="100%"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="course-wrapper mt-5">

    <h4 class="mb-4 course-title fw-bold">
        <span class="px-4 py-2 d-inline-block"
              style="
                background: linear-gradient(135deg,#1e3c72,#2a5298);
                color:#fff;
                border-radius:8px;
                box-shadow:0 4px 12px rgba(0,0,0,0.15);
                letter-spacing:0.5px;
              ">
            🎓 Professional Journalism Training Courses
        </span>
    </h4>


    @forelse($courses as $course)
        
        @php
            $coursePurchase = \App\Models\CoursePurchase::where('user_id', auth()->id())
                            ->where('course_id', $course->id)
                            ->first();
            $purchased = $coursePurchase && $coursePurchase->status === 'approved';
        @endphp
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
        
                <img src="{{ asset('assets/images/courses/'.$course->cover_img) }}"
                     style="height:260px;object-fit:cover;">
        
                <div class="card-body text-center mt-4">
                    <h5 class="mb-3">{{ $course->title }}</h5>
        
                    @if($purchased)
                        <a href="{{ route('user.courses.show', $course->id) }}"
                           class="btn btn-success mb-4">
                            Enter Course
                        </a>
                    @elseif($coursePurchase && $coursePurchase->status === 'pending')
                        <button class="btn btn-secondary mb-4" disabled>
                            Payment Waiting for approval
                        </button>
                    @else
                        <button class="btn btn-warning mb-4"
                                data-bs-toggle="modal"
                                data-bs-target="#purchaseModal{{ $course->id }}">
                            Purchase — {{ $course->price }} BDT
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        
        {{-- Purchase Modal (ONLY ONCE) --}}
        @if(!$purchased && !($coursePurchase && $coursePurchase->status === 'pending'))
        <div class="modal fade" id="purchaseModal{{ $course->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('course.pay', $course->id) }}">
                    @csrf
        
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Purchase {{ $course->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
        
                        <div class="modal-body">
        
                            <input type="text" name="phone_number"
                                   class="form-control mb-3"
                                   value="{{ auth()->user()->phone ?? '' }}"
                                   placeholder="Contact Number" required>
        
                            <select name="operator" class="form-control mb-3 d-none" required>
                                <option value="EPS" selected>EPS</option>
                            </select>
        
                            <div class="alert alert-info">
                                Continue to EPS to pay <strong>{{ number_format($course->price, 2) }} BDT</strong> automatically.
                            </div>
        
                        </div>
        
                        <div class="modal-footer">
                            <button class="btn btn-success">Pay with EPS</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        
        @empty
            <div class="text-center text-muted">No courses available.</div>
        @endforelse
    
    </div>

    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="height: 680px; max-height: 90vh;">
              <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="productDetailModalLabel"></h5>
              </div>
        
              <div class="modal-body">
                <div class="row mb-3">
                  <div class="col-4 col-md-4">
                    <!-- Carousel -->
                    <div id="productImagesCarousel" class="carousel slide" data-ride="carousel">
                      <div class="carousel-inner" id="carouselInner"></div>
                    
                      <button class="carousel-control-prev" type="button" data-target="#productImagesCarousel" data-slide="prev">
                        <svg width="35" height="60" viewBox="0 0 24 24">
                          <path d="M15 6 L9 12 L15 18" stroke="red" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                      </button>
                    
                      <button class="carousel-control-next" type="button" data-target="#productImagesCarousel" data-slide="next">
                        <svg width="35" height="60" viewBox="0 0 24 24">
                          <path d="M9 6 L15 12 L9 18" stroke="red" stroke-width="2" fill="none" stroke-linecap="round"/>
                        </svg>
                      </button>
                    </div>
                  </div>
        
                  <div class="col-8 col-md-8">
                    <h4 id="modalProductName"></h4>
                    <p><strong>{{ __('Price:') }}</strong> ৳ <span id="modalProductPrice"></span></p>
                    
                    <p><strong>{{ __('Stock:') }}</strong> <span id="modalProductStock"></span></p>
                    <p id="modalProductDesc"></p>
                  </div>
                </div>

                {{-- Move Form and Inputs inside the scrollable body --}}
                <form id="productPayDetailsForm" onsubmit="event.preventDefault();">
                    <input type="hidden" name="product_id" id="productPayProductId">
            
                    <div class="card border-0 bg-light">
                        <div class="card-body p-0">
            
                            <h6 class="mb-3 font-weight-bold text-dark">
                                <i class="fas fa-shopping-cart mr-1"></i>
                                Product Purchase Information
                            </h6>

                            {{-- Dynamic Cross-sell list --}}
                            <div id="crossSellContainer" class="mb-3 p-3 bg-white rounded border">
                                <h6 class="font-weight-bold text-primary mb-2" style="font-size: 14px;">
                                    <i class="fas fa-plus-circle mr-1"></i> {{ __('অন্যান্য আইটেম যুক্ত করুন (ঐচ্ছিক) / Add Add-on Items (Optional)') }}
                                </h6>
                                
                                {{-- ID Card Suggestion Banner --}}
                                <div id="idCardWarningBanner" class="alert alert-warning mb-3 d-none p-2" style="border-left: 4px solid #f59e0b; background-color: #fffbeb; font-size: 12px; text-align: left;">
                                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                    <strong>আইডি কার্ডের সাথে প্রেস ফিতা (Press Fita) প্রয়োজন!</strong>
                                </div>

                                <div id="crossSellList" class="cross-sell-list-scrollable" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                    @foreach($products as $prod)
                                        @php
                                            $imgUrl = $prod->firstImage ? asset('assets/images/products/' . $prod->firstImage->image_path) : 'https://static.vecteezy.com/system/resources/previews/048/910/778/original/default-image-missing-placeholder-free-vector.jpg';
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3 cross-sell-item bg-light text-left"
                                             data-product-id="{{ $prod->id }}" 
                                             data-name="{{ $prod->name }}" 
                                             data-price="{{ $prod->price }}"
                                             data-slug="{{ $prod->slug }}"
                                             style="background-color: #f8f9fa; padding: 12px !important; margin-bottom: 12px !important;">
                                            <div class="d-flex align-items-center">
                                                <div class="custom-control custom-checkbox mr-2">
                                                    <input type="checkbox" class="custom-control-input cross-sell-checkbox" 
                                                           id="addCrossProduct{{ $prod->id }}" value="{{ $prod->id }}">
                                                    <label class="custom-control-label" for="addCrossProduct{{ $prod->id }}" style="cursor: pointer;"></label>
                                                </div>
                                                
                                                <img src="{{ $imgUrl }}" alt="{{ $prod->name }}" class="rounded mr-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                
                                                <div>
                                                    <h6 class="mb-0 font-weight-bold" style="font-size: 13px;">
                                                        {{ $prod->name }}
                                                        <span class="badge badge-warning recommendation-badge d-none ml-1" style="font-size: 10px;">{{ __('Recommended') }}</span>
                                                    </h6>
                                                    <span class="text-success font-weight-bold" style="font-size: 12px;">৳ {{ number_format($prod->price, 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center cross-sell-quantity-wrapper" style="opacity: 0.5; pointer-events: none;">
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 btn-minus" style="font-size: 14px; font-weight: bold;">-</button>
                                                <input type="number" class="form-control form-control-sm text-center mx-1 addon-quantity" 
                                                       value="1" min="1" max="{{ $prod->stock }}" readonly style="width: 45px; height: 26px; padding: 2px;">
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 btn-plus" style="font-size: 14px; font-weight: bold;">+</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
            
                            <div class="row text-left">
            
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">
                                        {{ __('Phone Number') }}
                                    </label>
                                    <input
                                        type="text"
                                        name="phone_number"
                                        id="productPayPhone"
                                        class="form-control"
                                        value="{{ auth()->user()->phone ?? '' }}"
                                        placeholder="01XXXXXXXXX"
                                        required
                                    >
                                </div>
            
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">
                                        {{ __('Quantity') }}
                                    </label>
                                    <input
                                        type="number"
                                        name="quantity"
                                        id="productPayQuantity"
                                        class="form-control"
                                        value="1"
                                        min="1"
                                        required
                                    >
                                </div>
            
                                <div class="col-md-12 mb-3">
                                    <label class="font-weight-bold">
                                        {{ __('Delivery Address') }}
                                    </label>
                                    <textarea
                                        name="address"
                                        id="productPayAddress"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Enter your full delivery address"
                                        required
                                    ></textarea>
                                    <small class="text-danger font-weight-bold d-block mt-1">
                                        ⚠️ পণ্যটি পেতে অনুগ্রহ করে বিস্তারিত ঠিকানা প্রদান করুন (গ্রাম, ডাকঘর, থানা, জেলা), অন্যথায় ডেলিভারি বিলম্বিত হতে পারে।
                                    </small>
                                    <small class="text-muted d-block mt-1 font-weight-bold">
                                        🚚 ডেলিভারি চার্জ: ঢাকার ভিতরে ৬০ টাকা, ঢাকার বাইরে ১২০ টাকা।
                                    </small>
                                </div>
            
                            </div>
            
                        </div>
                    </div>
                </form>
              </div>
        
              <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary mr-2"
                    data-dismiss="modal">
                    Close
                </button>
        
                <button
                    type="button"
                    class="btn btn-success font-weight-bold px-4"
                    id="productPayButton">
                    <i class="fas fa-credit-card"></i>
                    Buy Now
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- CONFIRMATION MODAL --}}
        <div class="modal fade" id="productPayConfirmModal" tabindex="-1" aria-labelledby="productPayConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title font-weight-bold" id="productPayConfirmModalLabel">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('Confirm Your Order') }}
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                       

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm text-left" id="confirmOrderTable">
                                <thead class="bg-light text-left">
                                    <tr>
                                        <th>{{ __('Item') }}</th>
                                        <th class="text-center" style="width: 60px;">{{ __('Qty') }}</th>
                                        <th class="text-right" style="width: 100px;">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="font-weight-bold text-dark text-left">
                                        <td colspan="2" class="text-right">{{ __('Total:') }}</td>
                                        <td class="text-right text-success" id="confirmOrderTotal">৳ 0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="bg-light rounded p-3 mb-3 border text-left">
                            <div class="mb-2">
                                <span class="text-muted d-block" style="font-size: 11px;">{{ __('Phone Number') }}</span>
                                <strong id="confirmOrderPhone" style="font-size: 14px;"></strong>
                            </div>
                            <div>
                                <span class="text-muted d-block" style="font-size: 11px;">{{ __('Delivery Address') }}</span>
                                <strong id="confirmOrderAddress" style="font-size: 14px; white-space: pre-wrap;"></strong>
                            </div>
                        </div>

                        <form action="{{ route('product.pay') }}" method="POST" id="finalPaymentForm">
                            @csrf
                            {{-- Hidden inputs populated dynamically via Javascript --}}
                            <div id="finalPaymentHiddenInputs"></div>

                            <div class="d-flex justify-content-between pt-2">
                                <button type="button" class="btn btn-outline-secondary" id="backToDetailModalBtn">
                                    <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
                                </button>
                                <button type="submit" class="btn btn-success font-weight-bold px-4" id="finalPayBtn">
                                    <i class="fas fa-credit-card mr-1"></i> {{ __('Confirm & Pay') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-pprn3073KE6tl6vY5Jw6n4+DZv4pAP9HjT2yyL6w7C1gZ5+u+RW4my06MZ20kGmN" 
        crossorigin="anonymous">
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const widget = document.getElementById("reporterWidget");
    const panel = document.getElementById("reporterPanel");
    const btn = document.getElementById("reporterToggleBtn");
    const closeBtn = document.getElementById("reporterCloseBtn");

    function togglePanel() {
        panel.classList.toggle("show");
        widget.classList.toggle("open");
    }

    btn.addEventListener("click", togglePanel);
    closeBtn.addEventListener("click", togglePanel);
});
</script>




@if($nextPayment)
<script>
    const targetTime = new Date("{{ \Carbon\Carbon::parse($nextPayment)->toIso8601String() }}").getTime();

    const countdownInterval = setInterval(function () {
        const now = new Date().getTime();
        const distance = targetTime - now;

        if (distance <= 0) {
            clearInterval(countdownInterval);
            document.getElementById("payment-countdown").innerHTML = "DUE NOW";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("payment-countdown").innerHTML =
            `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }, 1000);
</script>
@endif

@if(!$alreadyShown)
<script>
document.addEventListener("DOMContentLoaded", function () {
    var myModal = new bootstrap.Modal(document.getElementById('productsModal'));
    myModal.show();

    fetch("{{ route('user.products.modal.cache') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
});
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
      const confirmModal = new bootstrap.Modal(document.getElementById('productPayConfirmModal'));
      const defaultImg = "https://static.vecteezy.com/system/resources/previews/048/910/778/original/default-image-missing-placeholder-free-vector.jpg";

      // Hide specific products in dynamic cross-sell list that matches the current main product ID
      function filterCrossSellList(mainProductId) {
          document.querySelectorAll('.cross-sell-item').forEach(item => {
              const prodId = item.dataset.productId;
              const checkbox = item.querySelector('.cross-sell-checkbox');
              const qtyWrapper = item.querySelector('.cross-sell-quantity-wrapper');
              
              if (prodId === mainProductId) {
                  item.classList.add('d-none');
                  item.classList.remove('d-flex');
              } else {
                  item.classList.remove('d-none');
                  item.classList.add('d-flex');
              }
              
              // Reset checkbox & quantity inputs
              if (checkbox) {
                  checkbox.checked = false;
              }
              if (qtyWrapper) {
                  qtyWrapper.style.opacity = '0.5';
                  qtyWrapper.style.pointerEvents = 'none';
                  const qtyInput = qtyWrapper.querySelector('.addon-quantity');
                  if (qtyInput) {
                      qtyInput.value = 1;
                  }
              }
          });
      }

      // Add cross-sell plus/minus click logic
      document.querySelectorAll('.cross-sell-item').forEach(item => {
          const checkbox = item.querySelector('.cross-sell-checkbox');
          const qtyWrapper = item.querySelector('.cross-sell-quantity-wrapper');
          const qtyInput = item.querySelector('.addon-quantity');
          const btnMinus = item.querySelector('.btn-minus');
          const btnPlus = item.querySelector('.btn-plus');
          const stock = parseInt(qtyInput.max || '1', 10);

          if (btnMinus && btnPlus && qtyInput) {
              btnMinus.addEventListener('click', function(e) {
                  e.stopPropagation();
                  let currentVal = parseInt(qtyInput.value, 10) || 1;
                  if (currentVal > 1) {
                      qtyInput.value = currentVal - 1;
                  }
              });

              btnPlus.addEventListener('click', function(e) {
                  e.stopPropagation();
                  let currentVal = parseInt(qtyInput.value, 10) || 1;
                  if (currentVal < stock) {
                      qtyInput.value = currentVal + 1;
                  }
              });
          }

          if (checkbox) {
              checkbox.addEventListener('change', function() {
                  const itemWrapper = this.closest('.cross-sell-item');
                  const targetSlug = itemWrapper.dataset.slug;
                  const mainSlug = document.getElementById('productPayProductId').dataset.slug || '';
                  
                  if (this.checked) {
                      qtyWrapper.style.opacity = '1';
                      qtyWrapper.style.pointerEvents = 'auto';
                  } else {
                      qtyWrapper.style.opacity = '0.5';
                      qtyWrapper.style.pointerEvents = 'none';
                  }
              });
          }
      });
    
      document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function () {
          const images = JSON.parse(this.dataset.images);
          const slug = this.dataset.slug;
          const mainProductId = this.dataset.id;
    
          let carouselHTML = '';
    
          if (images.length === 0) {
            carouselHTML = `
              <div class="carousel-item active">
                <img src="${defaultImg}" class="d-block w-100" alt="Default Image">
              </div>
            `;
          } else {
            images.forEach((img, index) => {
              carouselHTML += `
                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                  <img 
                    src="/assets/images/products/${img}" 
                    class="d-block w-100" 
                    alt="Product Image"
                    onerror="this.onerror=null; this.src='${defaultImg}';"
                  >
                </div>
              `;
            });
          }
    
          document.getElementById('carouselInner').innerHTML = carouselHTML;
          document.getElementById('productDetailModalLabel').innerText = this.dataset.name;
          document.getElementById('modalProductName').innerText = this.dataset.name;
          document.getElementById('modalProductPrice').innerText = this.dataset.price;
          document.getElementById('modalProductStock').innerText = this.dataset.stock;
          document.getElementById('modalProductDesc').innerText = this.dataset.desc;
          document.getElementById('productPayProductId').value = this.dataset.id;
          document.getElementById('productPayProductId').dataset.slug = this.dataset.slug;

          const stock = parseInt(this.dataset.stock || '0', 10);
          const quantity = document.getElementById('productPayQuantity');
          const payButton = document.getElementById('productPayButton');
          const address = document.getElementById('productPayAddress');

          quantity.value = stock > 0 ? 1 : 0;
          quantity.max = stock;
          quantity.disabled = stock <= 0;
          payButton.disabled = stock <= 0;
          payButton.innerHTML = stock > 0
            ? '<i class="fas fa-credit-card"></i> Buy Now'
            : 'Out of Stock';

          // Filter cross-sell list so you can't buy the same item as addon
          filterCrossSellList(mainProductId);

          // Apply special rules (like showing recommended tag on Press Fita if ID Card is selected)
          const isIdCard = (slug === 'aidi-kard');
          const warningBanner = document.getElementById('idCardWarningBanner');
          if (warningBanner) {
              if (isIdCard) {
                  warningBanner.classList.remove('d-none');
              } else {
                  warningBanner.classList.add('d-none');
              }
          }

          document.querySelectorAll('.cross-sell-item').forEach(item => {
              const itemSlug = item.dataset.slug;
              const badge = item.querySelector('.recommendation-badge');
              if (badge) {
                  if (isIdCard && itemSlug === 'pres-fita') {
                      badge.classList.remove('d-none');
                      
                      // Auto-check Press Fita checkbox and enable quantity wrapper
                      const checkbox = item.querySelector('.cross-sell-checkbox');
                      if (checkbox) {
                          checkbox.checked = true;
                      }
                      const qtyWrapper = item.querySelector('.cross-sell-quantity-wrapper');
                      if (qtyWrapper) {
                          qtyWrapper.style.opacity = '1';
                          qtyWrapper.style.pointerEvents = 'auto';
                      }
                  } else {
                      badge.classList.add('d-none');
                  }
              }
          });
    
          modal.show();
        });
      });

      // Keep track of which modal triggered the confirmation modal ('detail' or 'grid')
      let confirmModalOrigin = 'detail';

      // Handle transition to confirmation modal on "Buy Now" click
      document.getElementById('productPayButton').addEventListener('click', function() {
          const phone = document.getElementById('productPayPhone').value.trim();
          const address = document.getElementById('productPayAddress').value.trim();
          const quantityInput = document.getElementById('productPayQuantity');
          const quantity = parseInt(quantityInput.value, 10);
          
          if (!phone || !address || isNaN(quantity) || quantity < 1) {
              const form = document.getElementById('productPayDetailsForm');
              if (form.reportValidity) {
                  form.reportValidity();
              } else {
                  alert('Please fill out Phone Number, Quantity and Address fields.');
              }
              return;
          }

          // Suggest Press Fita if selected product is ID Card and Press Fita is not checked
          const mainProductSlug = document.getElementById('productPayProductId').dataset.slug || '';
          if (mainProductSlug === 'aidi-kard') {
              const pressFitaItem = Array.from(document.querySelectorAll('.cross-sell-item')).find(item => item.dataset.slug === 'pres-fita');
              if (pressFitaItem) {
                  const checkbox = pressFitaItem.querySelector('.cross-sell-checkbox');
                  if (checkbox && !checkbox.checked) {
                      const addFita = confirm("সম্মানিত গ্রাহক, আইডি কার্ডের সাথে প্রেস ফিতা (Press Fita) প্রয়োজনীয়। আপনি কি এটি আপনার অর্ডারে যুক্ত করতে চান?\n\nDear customer, Press Fita is required with ID Card. Do you want to add it to your order?");
                      if (addFita) {
                          checkbox.checked = true;
                          const qtyWrapper = pressFitaItem.querySelector('.cross-sell-quantity-wrapper');
                          if (qtyWrapper) {
                              qtyWrapper.style.opacity = '1';
                              qtyWrapper.style.pointerEvents = 'auto';
                          }
                      }
                  }
              }
          }

          confirmModalOrigin = 'detail';

          // Build checkout confirmation invoice
          const mainProductId = document.getElementById('productPayProductId').value;
          const mainProductName = document.getElementById('modalProductName').innerText;
          const mainProductPrice = parseFloat(document.getElementById('modalProductPrice').innerText.replace(/,/g, ''));
          const mainSubtotal = mainProductPrice * quantity;

          let totalAmount = mainSubtotal;
          let tableBodyHTML = `
              <tr>
                  <td>${mainProductName}</td>
                  <td class="text-center font-weight-bold">${quantity}</td>
                  <td class="text-right">৳ ${mainSubtotal.toFixed(2)}</td>
              </tr>
          `;

          let hiddenInputsHTML = `
              <input type="hidden" name="product_ids[]" value="${mainProductId}">
              <input type="hidden" name="quantities[${mainProductId}]" value="${quantity}">
              <input type="hidden" name="phone_number" value="${phone}">
              <input type="hidden" name="address" value="${address}">
          `;

          // Check if any addon products are checked
          document.querySelectorAll('.cross-sell-checkbox:checked').forEach(checkbox => {
              const itemWrapper = checkbox.closest('.cross-sell-item');
              const addonId = itemWrapper.dataset.productId;
              const addonName = itemWrapper.dataset.name;
              const addonPrice = parseFloat(itemWrapper.dataset.price);
              const addonQty = parseInt(itemWrapper.querySelector('.addon-quantity').value, 10) || 1;
              const addonSubtotal = addonPrice * addonQty;

              totalAmount += addonSubtotal;
              tableBodyHTML += `
                  <tr>
                      <td>${addonName}</td>
                      <td class="text-center font-weight-bold">${addonQty}</td>
                      <td class="text-right">৳ ${addonSubtotal.toFixed(2)}</td>
                  </tr>
              `;

              hiddenInputsHTML += `
                  <input type="hidden" name="product_ids[]" value="${addonId}">
                  <input type="hidden" name="quantities[${addonId}]" value="${addonQty}">
              `;
          });

          // Update confirm modal text/fields
          document.querySelector('#confirmOrderTable tbody').innerHTML = tableBodyHTML;
          document.getElementById('confirmOrderTotal').innerText = '৳ ' + totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          document.getElementById('confirmOrderPhone').innerText = phone;
          document.getElementById('confirmOrderAddress').innerText = address;
          document.getElementById('finalPaymentHiddenInputs').innerHTML = hiddenInputsHTML;

          // Transition modals
          modal.hide();
          confirmModal.show();
      });

      // Handle checkout for the checkbox product list (সাংবাদিকতা সম্পর্কিত পণ্য কিনুন)
      const paySelectedBtn = document.getElementById('paySelectedButton');
      if (paySelectedBtn) {
          paySelectedBtn.addEventListener('click', function() {
              const phone = document.getElementById('paySelectedPhone').value.trim();
              const address = document.getElementById('paySelectedAddress').value.trim();
              
              // Get all checked checkboxes in products section
              const checkedCheckboxes = document.querySelectorAll('.products-section input[name="product_ids[]"]:checked');
              
              if (checkedCheckboxes.length === 0) {
                  alert('Please select at least one product.');
                  return;
              }
              
              if (!phone || !address) {
                  const form = document.getElementById('paySelectedForm');
                  if (form.reportValidity) {
                      form.reportValidity();
                  } else {
                      alert('Please fill out Phone Number and Address fields.');
                  }
                  return;
              }
              
              let totalAmount = 0;
              let tableBodyHTML = '';
              let hiddenInputsHTML = `
                  <input type="hidden" name="phone_number" value="${phone}">
                  <input type="hidden" name="address" value="${address}">
              `;
              
              let stockError = false;
              let stockErrorMessage = '';
              
              // Verify Fita suggestion rule: if ID Card (aidi-kard) is selected, Press Fita (pres-fita) is required
              let hasIdCard = false;
              let hasPressFita = false;
              let pressFitaCheckbox = null;
              let pressFitaCard = null;
              
              // Scan first to check for ID card and Press Fita selection
              document.querySelectorAll('.products-section .product-card').forEach(card => {
                  const slug = card.dataset.slug;
                  const checkbox = card.querySelector('input[name="product_ids[]"]');
                  
                  if (slug === 'aidi-kard' && checkbox && checkbox.checked) {
                      hasIdCard = true;
                  }
                  if (slug === 'pres-fita') {
                      pressFitaCard = card;
                      if (checkbox) {
                          pressFitaCheckbox = checkbox;
                          if (checkbox.checked) {
                              hasPressFita = true;
                          }
                      }
                  }
              });
              
              if (hasIdCard && !hasPressFita && pressFitaCheckbox && pressFitaCard) {
                  const addFita = confirm("সম্মানিত গ্রাহক, আইডি কার্ডের সাথে প্রেস ফিতা (Press Fita) প্রয়োজনীয়। আপনি কি এটি আপনার অর্ডারে যুক্ত করতে চান?\n\nDear customer, Press Fita is required with ID Card. Do you want to add it to your order?");
                  if (addFita) {
                      pressFitaCheckbox.checked = true;
                      hasPressFita = true;
                      // Refresh checked checkboxes list
                      return paySelectedBtn.click();
                  }
              }
              
              checkedCheckboxes.forEach(checkbox => {
                  const card = checkbox.closest('.product-card');
                  const productId = checkbox.value;
                  const productName = card.dataset.name;
                  const productPrice = parseFloat(card.dataset.price.replace(/,/g, ''));
                  const stock = parseInt(card.dataset.stock, 10) || 0;
                  
                  const qtyInput = card.querySelector(`input[name="quantities[${productId}]"]`);
                  const quantity = parseInt(qtyInput.value, 10) || 1;
                  
                  if (quantity > stock) {
                      stockError = true;
                      stockErrorMessage = `${productName} does not have enough stock. (Available: ${stock})`;
                  }
                  
                  const subtotal = productPrice * quantity;
                  totalAmount += subtotal;
                  
                  tableBodyHTML += `
                      <tr>
                          <td>${productName}</td>
                          <td class="text-center font-weight-bold">${quantity}</td>
                          <td class="text-right">৳ ${subtotal.toFixed(2)}</td>
                      </tr>
                  `;
                  
                  hiddenInputsHTML += `
                      <input type="hidden" name="product_ids[]" value="${productId}">
                      <input type="hidden" name="quantities[${productId}]" value="${quantity}">
                  `;
              });
              
              if (stockError) {
                  alert(stockErrorMessage);
                  return;
              }
              
              confirmModalOrigin = 'grid';
              
              // Update confirm modal text/fields
              document.querySelector('#confirmOrderTable tbody').innerHTML = tableBodyHTML;
              document.getElementById('confirmOrderTotal').innerText = '৳ ' + totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
              document.getElementById('confirmOrderPhone').innerText = phone;
              document.getElementById('confirmOrderAddress').innerText = address;
              document.getElementById('finalPaymentHiddenInputs').innerHTML = hiddenInputsHTML;
              
              // Show confirmation modal
              confirmModal.show();
          });
      }

      // Back button click listener in confirmation modal
      document.getElementById('backToDetailModalBtn').addEventListener('click', function() {
          confirmModal.hide();
          if (confirmModalOrigin === 'detail') {
              modal.show();
          }
      });
    });
</script>

<script>
function openPDF(url){
    // Add #toolbar=0 to hide PDF toolbar and disable download/print
    const secureUrl = url + '#toolbar=0';
    document.getElementById('pdfViewerFrame').src = secureUrl;

    // Open modal
    new bootstrap.Modal(document.getElementById('pdfViewerModal')).show();
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButtons = document.querySelectorAll('.toggle-pay-section');
    
    payButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const targetEl = document.querySelector(targetId);

            // Close all other pay sections
            document.querySelectorAll('.pay-section.collapse.show').forEach(openEl => {
                if (openEl !== targetEl) {
                    bootstrap.Collapse.getInstance(openEl)?.hide();
                }
            });

            // Toggle the clicked one
            new bootstrap.Collapse(targetEl, { toggle: true });
        });
    });
});
</script>

<script>
function copyAffiliateLink() {

    let copyText = document.getElementById("affiliateLink");

    copyText.select();
    copyText.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(copyText.value);

    alert("Affiliate link copied");
}
</script>
@endsection
