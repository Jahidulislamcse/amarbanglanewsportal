@extends('layouts.reader')

<style>
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

    .head-free {
        background: #17a2b8
    }

    .head-executive {
        background: #28a745
    }

    .head-vip {
        background: #ffc107;
        color: #222
    }

    .head-upgrade {
        background: #6f42c1
    }

    .head-team {
        background: #e83e8c
    }

    .head-promo {
        background: #fd7e14
    }

    .head-course {
        background: #20c997
    }

    .head-agency {
        background: #007bff
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

    .upgrade-btn {
        background: #000;
        color: #fff;
        border: none;
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 13px;
        margin-top: 10px;
    }

    .team-scroll {
        max-height: 320px;
        overflow-y: auto;
    }

    .vip-scroll {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .vip-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .vip-scroll::-webkit-scrollbar-thumb {
        background: #bbb;
        border-radius: 10px;
    }

    .row-cards-one .mycard .left .number_f {
        font-size: 32px;
        line-height: 42px;
        font-weight: 600;
        display: block;
        color: #fff;
    }
</style>

@section('content')
    <div class="content-area">
        <div class="row row-cards-one">

            <!--<div class="col-md-12 col-lg-6 col-xl-3">-->
            <!--    <div class="mycard bg4" style="padding:20px;">-->
            <!--        <div class="left">-->
            <!--            <h5 class="title" style="font-size:14px;">{{ __('Total Views') }}</h5>-->
            <!--            <span class="number_f">{{ $total_views }}</span>-->
            <!--        </div>-->
            <!--        <div class="right d-flex align-self-center">-->
            <!--            <div class="icon" style="font-size:25px;">-->
            <!--                <i class="fas fa-search"></i>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->

            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg4" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">
                            {{ __('Earning from Views') }}
                        </h5>

                        <span class="number_f">{{ $view_income }}</span>
                        <small style="color:#ddd; font-size:11px;">
                            শুধুমাত্র সাম্প্রতিক সংবাদ পড়ার জন্য আয় যোগ করা হয়
                        </small>
                    </div>


                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg3" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Refferal Commission') }}</h5>
                        <span class="number_f">{{ $refferal_commission }}</span>
                    </div>

                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg5" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">
                            {{ __('Team Income') }}
                        </h5>

                        <span class="number_f">
                            {{ $team_income_total }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg5" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">
                            {{ __('Quiz Added Balance') }}
                        </h5>

                        <span class="number_f">
                            {{ $quiz_prize_total }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg3" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Current Balance') }}</h5>
                        <span class="number_f">{{ $total_commission }}</span>
                    </div>

                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg2" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Total Withdraw') }}</h5>
                        <span class="number_f">{{ $total_withdraw }}</span>

                    </div>

                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="mycard bg1" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">{{ __('Total Earning') }}</h5>
                        <span class="number_f">{{ $total_balance }}</span>

                    </div>

                </div>
            </div>
            {{-- <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg5">
                <div class="left">
                    <h5 class="title">{{ __('Rss Feeds') }}</h5>
                    <span class="number_f">{{ $rss}}</span>
                    <a href="{{ route('user.rss.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg6">
                <div class="left">
                    <h5 class="title">{{ __('Polls') }}</h5>
                    <span class="number_f">{{ $polls}}</span>
                    <a href="{{ route('addPolls.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                
            </div>
        </div> --}}
            <div class="col-md-12 col-lg-12 col-xl-12 mt-3">
                <div class="mycard bg1" style="padding:20px;">
                    <div class="left">
                        <h5 class="title" style="font-size:14px;">
                            {{ __('এই অ্যাফিলিয়েট লিঙ্কটি শেয়ার করুন এবং যারা আপনার লিঙ্ক ব্যবহার করে রেজিস্ট্রেশন করবে তাদের ভিউ থেকে কমিশন পান') }}
                        </h5>

                        @if ($total_views >= 10)
                            <input type="text" id="affiliateLink" class="form-control" value="{{ $affiliate_link }}"
                                readonly>

                            <button class="btn btn-primary mt-2" onclick="copyAffiliateLink()">
                                <i class="fas fa-copy"></i> Copy Link
                            </button>

                            <a class="btn btn-success mt-2"
                                href="https://api.whatsapp.com/send?text={{ urlencode($affiliate_link) }}" target="_blank">
                                <i class="fab fa-whatsapp"></i> Share on WhatsApp
                            </a>

                            <a class="btn btn-info mt-2"
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($affiliate_link) }}"
                                target="_blank">
                                <i class="fab fa-facebook"></i> Share on Facebook
                            </a>
                        @else
                            <div class="alert alert-warning mt-3 mb-0">
                                🔒 অ্যাফিলিয়েট লিঙ্ক দেখতে ও শেয়ার করতে কমপক্ষে
                                <b>১০ টি নিউজ </b> ভিজিট করতে হবে। <br>
                                আপনার বর্তমান ভিউ: <b>{{ $total_views }}</b>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-md-12 offer-wrap">
                <h4 class="mb-4 font-weight-bold ">এক নজরে সকল প্যাকেজ</h4>

                @php
                    use App\Models\Fee;

                    $user = auth()->user();
                    $fees = Fee::first();

                    $userType = $user->reader_type ?? 'free';

                    $upgradeMap = [
                        'free' => 'executive',
                        'executive' => 'vip',
                    ];

                    $upgrade = $upgradeMap[$userType] ?? null;

                    $packagePrices = [
                        'executive' => $fees->executive_package_price ?? 0,
                        'vip' => $fees->vip_package_price ?? 0,
                    ];

                    $quizPrizeAmounts = [
                        1 => $fees->quiz_1st_prize ?? 0,
                        2 => $fees->quiz_2nd_prize ?? 0,
                        3 => $fees->quiz_3rd_prize ?? 0,
                    ];

                    $viewRates = [
                        'free' => 5,
                        'executive' => 10,
                        'vip' => 15,
                    ];

                    // VIP commission calculation
                    $vipPercent = $fees->referral_commission ?? 0;
                    $vipAmount = $packagePrices['vip'] ?? 0;
                    $vipValue = ($vipAmount * $vipPercent) / 100;

                    $packageReferral = [
                        'free' => '৫ টাকা',
                        'executive' => '১০ টাকা',
                        'vip' => $vipPercent . '% (সর্বোচ্চ ৳ ' . number_format($vipValue, 2) . ' পর্যন্ত)',
                    ];

                    // Pending request package
                    $pendingPackage = $upgradeRequest->package ?? null;
                @endphp

                <div class="row">

                    {{-- Current Package --}}
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="offer-box">
                            <div class="offer-head head-{{ $userType }}">
                                🎖 আপনার বর্তমান প্যাকেজ ({{ ucfirst($userType) }})
                            </div>

                            <div class="offer-body">
                                <ul>
                                    <li>
                                        প্রতি ভিউ
                                        <span class="offer-highlight">
                                            {{ $viewRates[$userType] }} পয়সা
                                        </span>
                                    </li>

                                    <li>
                                        প্রতি রেফারেল (অ্যাকাউন্ট করলে)
                                        <span class="offer-highlight">৫ টাকা</span>
                                    </li>

                                    <li>
                                        রেফারকৃত ব্যাক্তি প্যাকেজ নিলে আরও
                                        <span class="offer-highlight">
                                            {{ $packageReferral[$userType] }}
                                        </span>
                                        স্বয়ংক্রিয়ভাবে আপনার ব্যালেন্সে যুক্ত হবে
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Upgrade Package --}}
                    @if ($upgrade)
                        <div class="col-md-6 col-xl-4 mb-4">
                            <div class="offer-box">
                                <div class="offer-head head-upgrade">
                                    🚀 আরও বেশি ইনকাম করতে আপগ্রেড করুন ({{ ucfirst($upgrade) }})
                                </div>

                                <div class="offer-body">
                                    <div class="vip-scroll">
                                        <ul>
                                            <li>
                                                প্রতি ভিউ ইনকাম হবে
                                                <b>{{ $viewRates[$upgrade] }} পয়সা</b>
                                            </li>

                                            <li>
                                                প্রতি রেফারেল (অ্যাকাউন্ট করলে)
                                                <span class="offer-highlight">৫ টাকা</span>
                                            </li>

                                            <li>
                                                রেফারকৃত ব্যাক্তি প্যাকেজ নিলে আরও
                                                <span class="offer-highlight">
                                                    {{ $packageReferral[$upgrade] }}
                                                </span>
                                                স্বয়ংক্রিয়ভাবে আপনার ব্যালেন্সে যুক্ত হবে
                                            </li>

                                            @if ($userType == 'executive')
                                                <!--<li class="mt-2">-->
                                                <!--    👥 এ ছাড়াও রয়েছে টিম বোনাস ইনকাম এর সুযোগ-->
                                                <!--    <ul class="mt-2">-->
                                                <!--        <li>১ম জেনারেশন <b>৫%</b></li>-->
                                                <!--        <li>২য় <b>৪%</b></li>-->
                                                <!--        <li>৩য় <b>৩%</b></li>-->
                                                <!--        <li>৪র্থ <b>২%</b></li>-->
                                                <!--        <li>৫ম <b>১%</b></li>-->
                                                <!--    </ul>-->
                                                <!--</li>-->
                                            @endif
                                        </ul>
                                    </div>

                                    {{-- Executive/VIP pending check --}}
                                    @if ($pendingPackage == $upgrade)
                                        <button class="btn btn-warning btn-sm" disabled>
                                            ⏳ {{ ucfirst($upgrade) }} Pending
                                        </button>
                                    @elseif ($pendingPackage == 'vip')
                                        <button class="upgrade-btn" disabled style="opacity:.6; cursor:not-allowed;">
                                            Upgrade to {{ ucfirst($upgrade) }}
                                        </button>
                                    @else
                                        <button type="button" class="upgrade-btn" data-toggle="modal"
                                            data-target="#upgradeModal{{ $upgrade }}">
                                            Upgrade to {{ ucfirst($upgrade) }}
                                        </button>
                                    @endif
                                    <!--@if ($pendingPackage == 'executive')
    -->
                                    <!--    <button class="btn btn-warning btn-sm" disabled>-->
                                    <!--        ⏳ Pending -->
                                    <!--    </button>-->

                                    <!--
@elseif ($pendingPackage == 'vip')
    -->
                                    <!--    <button class="upgrade-btn" disabled-->
                                    <!--        style="opacity:.6; cursor:not-allowed;">-->
                                    <!--        Upgrade to {{ ucfirst($upgrade) }}-->
                                    <!--    </button>-->

                                <!--@else-->
                                    <!--    <button type="button"-->
                                    <!--        class="upgrade-btn"-->
                                    <!--        data-toggle="modal"-->
                                    <!--        data-target="#upgradeModalvip">-->
                                    <!--        Upgrade to {{ ucfirst($upgrade) }}-->
                                    <!--    </button>-->
                                    <!--
    @endif-->
                                </div>
                            </div>
                        </div>

                        {{-- EXTRA VIP OFFER ONLY FOR FREE USERS --}}
                        @if ($userType == 'free')
                            <div class="col-md-6 col-xl-4 mb-4">
                                <div class="offer-box">
                                    <div class="offer-head head-vip">
                                        💎 VIP প্রিমিয়াম অফার (সর্বোচ্চ ইনকাম)
                                    </div>

                                    <div class="offer-body">
                                        <div class="vip-scroll">
                                            <ul>
                                                <li>
                                                    প্রতি ভিউ ইনকাম হবে
                                                    <b>{{ $viewRates['vip'] }} পয়সা</b>
                                                </li>

                                                <li>
                                                    প্রতি রেফারেল (অ্যাকাউন্ট করলে)
                                                    <span class="offer-highlight">৫ টাকা</span>
                                                </li>

                                                <li>
                                                    রেফারকৃত ব্যাক্তি প্যাকেজ নিলে আরও
                                                    <span class="offer-highlight">
                                                        {{ $packageReferral['vip'] }}
                                                    </span>
                                                    স্বয়ংক্রিয়ভাবে আপনার ব্যালেন্সে যুক্ত হবে
                                                </li>

                                                <!--<li class="mt-2">-->
                                                <!--    👥 এ ছাড়াও রয়েছে টিম বোনাস ইনকাম এর সুযোগ-->
                                                <!--    <ul class="mt-2">-->
                                                <!--        <li>১ম জেনারেশন <b>৫%</b></li>-->
                                                <!--        <li>২য় <b>৪%</b></li>-->
                                                <!--        <li>৩য় <b>৩%</b></li>-->
                                                <!--        <li>৪র্থ <b>২%</b></li>-->
                                                <!--        <li>৫ম <b>১%</b></li>-->
                                                <!--    </ul>-->
                                                <!--</li>-->

                                                <li>
                                                    সর্বোচ্চ কমিশন সুবিধা
                                                    <span class="offer-highlight">
                                                        Available only for VIP
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- Always Visible Button --}}
                                        <div class="mt-3">
                                            @if ($pendingPackage == 'vip')
                                                <button class="btn btn-warning btn-sm" disabled>
                                                    ⏳ Pending
                                                </button>
                                            @elseif ($pendingPackage == 'executive')
                                                <button class="upgrade-btn" disabled
                                                    style="opacity:.6; cursor:not-allowed;">
                                                    Go VIP
                                                </button>
                                            @else
                                                <button type="button" class="upgrade-btn" data-toggle="modal"
                                                    data-target="#upgradeModalvip">
                                                    Go VIP
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @php
                        function bn($number)
                        {
                            $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                            $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                            return str_replace($en, $bn, $number);
                        }
                    @endphp

                    {{-- Team Bonus --}}
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="offer-box">

                            <div class="offer-head head-promo">
                                👥 <strong>আপনার রেফারকৃত সদস্যগন (টিম)</strong> <br>
                                <small>( কমিশন শুধুমাত্র <strong>VIP প্যাকেজের</strong> ক্ষেত্রে প্রযোজ্য )</small>
                            </div>

                            <!-- Apply scroll here -->
                            <div class="offer-body team-scroll">

                                @php
                                    $commissionMap = [
                                        1 => '৫%',
                                        2 => '৪%',
                                        3 => '৩%',
                                        4 => '২%',
                                        5 => '১%',
                                    ];
                                @endphp

                                @for ($g = 1; $g <= 5; $g++)
                                    @php $count = count($genUsers[$g]); @endphp

                                    <div class="mb-3 border rounded">

                                        <button
                                            class="btn btn-light w-100 text-left d-flex justify-content-between align-items-center"
                                            type="button" data-toggle="collapse" data-target="#gen{{ $g }}"
                                            aria-expanded="false" aria-controls="gen{{ $g }}">

                                            <div>
                                                <div class="font-weight-bold">
                                                    {{ bn($g) }}{{ ['', 'ম', 'য়', 'য়', 'র্থ', 'ম'][$g] ?? '' }}
                                                    স্তরের টিম মেম্বারগন ({{ bn($count) }})
                                                </div>

                                                <small class="text-muted">
                                                    কমিশন: {{ $commissionMap[$g] }}
                                                </small>
                                            </div>

                                            <span class="badge badge-primary">দেখুন</span>
                                        </button>

                                        <div id="gen{{ $g }}" class="collapse">
                                            <!-- ❌ remove inner scroll -->
                                            <div class="p-2 border-top">

                                                @if ($count > 0)
                                                    <ul class="mb-0 pl-3">
                                                        @foreach ($genUsers[$g] as $u)
                                                            <li class="mb-1">
                                                                {{ $u->name }}
                                                                — <span class="badge badge-info">
                                                                    {{ ucfirst($u->reader_type) }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted mb-0">কোনো ইউজার নেই</p>
                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                @endfor

                            </div>
                        </div>
                    </div>

                    <!--<div class="col-md-6 col-xl-4 mb-4">-->
                    <!--    <div class="offer-box">-->
                    <!--         <div class="offer-head head-promo">-->
                    <!--            👥 <strong>আপনার রেফারকৃত সদস্যগন (টিম)</strong> <br>-->

                    <!--        </div>-->
                    <!--        <div class="ml-2 mt-2">-->
                    <!--            <span class="badge badge-success p-2">-->
                    <!--                👑 আপনি-->
                    <!--            </span>-->
                    <!--        </div>-->
                    <!--        <div class="offer-body team-scroll">-->
                    <!--        @foreach ($referralRows as $u)
    -->

                    <!--            <div class="tree-member level-{{ $u->gen }}">-->

                    <!--                <div class="member-card">-->

                    <!--                    <span class="tree-line">-->
                    <!--                        @for ($i = 1; $i < $u->gen; $i++)
    -->
                    <!--                            <span class="tree-indent">│&nbsp;&nbsp;&nbsp;</span>-->
                    <!--
    @endfor-->
                    <!--                        ├──-->
                    <!--                    </span>-->

                    <!--                    <small>{{ $u->name }}</small>-->

                    <!--                    <span class="badge badge-info ml-1">-->
                    <!--                        {{ ucfirst($u->reader_type) }}-->
                    <!--                    </span>-->

                    <!--                    <span class="badge badge-light ml-1">-->
                    <!--                        Gen {{ $u->gen }}-->
                    <!--                    </span>-->

                    <!--                </div>-->

                    <!--            </div>-->

                    <!--
    @endforeach-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="offer-box">

                            <div class="offer-head head-team">
                                👥 টিম ইনকাম হিস্টোরি
                            </div>

                            @if (auth()->user()->reader_type !== 'vip' && $team_income_history->count() > 0)
                                <div class="alert alert-warning mb-2">
                                    🔒 এই টিম ইনকাম শুধুমাত্র VIP ইউজারদের ব্যালেন্সে যোগ হবে।
                                    আপগ্রেড করলে সব কমিশন আপনার ব্যালেন্সে যুক্ত হবে।
                                </div>
                            @endif

                            <div class="offer-body team-scroll">

                                @forelse($team_income_history as $row)
                                    <div class="mb-2 pb-2 border-bottom">

                                        <strong>
                                            ৳ {{ $row->commission }}
                                        </strong>

                                        <br>

                                        <small>
                                            From: {{ $row->source->name ?? 'Unknown' }}
                                            ({{ ucfirst($row->source->reader_type ?? '') }})
                                        </small>

                                        <br>

                                        <small class="text-muted">
                                            Generation: {{ $row->generation }} |
                                            Package: {{ ucfirst($row->package) }}
                                        </small>

                                    </div>

                                @empty
                                    <p class="text-muted mb-0">
                                        কোনো team income পাওয়া যায়নি।
                                    </p>
                                @endforelse

                            </div>

                        </div>
                    </div>

                    <div class="col-md-4 col-xl-4 mb-4">
                        <div class="offer-box">

                            <div class="offer-head head-promo">
                                🎁 <strong>প্রমোশনাল ইনসেন্টিভ</strong> <br>
                                <small>(শুধুমাত্র <strong>VIP প্যাকেজধারীদের</strong> ক্ষেত্রে প্রযোজ্য)</small>
                            </div>

                            <div class="offer-body">
                                <ul>
                                    <li>১ম স্তরের টিম থেকে <strong>১০০ VIP একাউন্ট</strong> হলে <strong>৫,০০০ টাকা
                                            বোনাস</strong></li>
                                    <li>২য় স্তরের টিম থেকে <strong>৩০০ VIP একাউন্ট</strong> হলে <strong>৬,০০০ টাকা
                                            বোনাস</strong></li>
                                    <li>৩য় স্তরের টিম থেকে <strong>৫০০ VIP একাউন্ট</strong> হলে <strong>৭,০০০ টাকা
                                            বোনাস</strong></li>
                                    <li>৪র্থ স্তরের টিম থেকে <strong>১,০০০ VIP একাউন্ট</strong> হলে <strong>১০,০০০ টাকা
                                            বোনাস</strong></li>
                                    <li>৫ম স্তরের টিম থেকে <strong>২,০০০ VIP একাউন্ট</strong> হলে <strong>১২,000 টাকা
                                            বোনাস</strong></li>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>




            @php
                function bnNumber($number)
                {
                    return str_replace(
                        ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
                        ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
                        $number,
                    );
                }

                function bnPosition($pos)
                {
                    $number = bnNumber($pos);

                    $suffix = match ($pos) {
                        1 => 'ম',
                        2 => 'য়',
                        3 => 'য়',
                        default => 'তম',
                    };

                    return $number . $suffix;
                }
            @endphp

            <div class="col-md-12 offer-wrap">

                <h4 class="mb-4 font-weight-bold">কুইজ বিজয়ী </h4>

                <div class="row">

                    {{-- MY HISTORY --}}
                    <div class="col-md-6 mb-4">

                        <div class="offer-box">

                            <div class="offer-head head-team">
                                আমার বিজয়ী ইতিহাস
                            </div>

                            <div class="offer-body team-scroll">

                                @forelse($myQuizWinningHistory as $winner)
                                    @php
                                        $amount = $quizPrizeAmounts[$winner->position] ?? 0;
                                    @endphp

                                    <div class="mb-2 pb-2 border-bottom">

                                        <strong>
                                            {{ bnPosition($winner->position) }} — {{ bnNumber($amount) }} টাকা
                                        </strong>

                                        <br>

                                        <small>
                                            {{ bnNumber(\Carbon\Carbon::parse($winner->created_at)->format('d-m-Y')) }}
                                        </small>

                                    </div>

                                @empty

                                    <p class="text-muted mb-0">
                                        এখনো কোনো বিজয়ী ইতিহাস নেই।
                                    </p>
                                @endforelse

                            </div>

                        </div>

                    </div>

                    {{-- RECENT WINNERS --}}
                    <div class="col-md-6 mb-4">

                        <div class="offer-box">

                            <div class="offer-head head-promo">
                                সাম্প্রতিক কুইজ বিজয়ীগণ
                            </div>

                            <div class="offer-body team-scroll">

                                @forelse($recentQuizWinners->groupBy(function($winner) {
                                    return \Carbon\Carbon::parse($winner->created_at)->format('d M Y');
                                }) as $date => $winners)
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-primary border-bottom pb-1">
                                            {{ $date }}
                                        </h6>

                                        @foreach ($winners as $winner)
                                            <div class="mb-2 pb-2 border-bottom">

                                                <strong>
                                                    {{ bnPosition($winner->position) }}
                                                </strong>

                                                <br>

                                                {{ $winner->answer->name ?? 'N/A' }}

                                                @if (!empty($winner->answer->phone))
                                                    <br>

                                                    <small>
                                                        {{ substr($winner->answer->phone, 0, -3) . '***' }}
                                                    </small>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>

                                @empty

                                    <p class="text-muted mb-0">
                                        এখনো কোনো বিজয়ী নেই।
                                    </p>
                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal fade" id="upgradeModalexecutive" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">🚀 Upgrade to Executive</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <p class="mb-2">
                                Package Price:
                                <strong>৳{{ $packagePrices['executive'] }}</strong>
                            </p>

                            <hr>

                            <p class="text-muted">
                                You will be redirected to the payment gateway to complete your Executive package upgrade.
                            </p>

                            <form action="{{ route('package-upgrade.pay') }}" method="POST">
                                @csrf

                                <input type="hidden" name="package" value="executive">

                                <button class="btn btn-success w-100 mt-2">
                                    Pay & Upgrade to Executive
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="upgradeModalvip" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">💎 Upgrade to VIP</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <p class="mb-2">
                                Package Price:
                                <strong>৳{{ $packagePrices['vip'] }}</strong>
                            </p>

                            <hr>

                            <p class="text-muted">
                                You will be redirected to the payment gateway to complete your VIP package upgrade.
                            </p>

                            <form action="{{ route('package-upgrade.pay') }}" method="POST">
                                @csrf

                                <input type="hidden" name="package" value="vip">

                                <button class="btn btn-warning w-100 mt-2">
                                    Pay & Upgrade to VIP
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($todayQuizPosts) && $todayQuizPosts->count() > 0)
        <div class="modal fade" id="dailyQuizOfferModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius:14px; overflow:hidden;">

                    <div class="modal-header" style="background:#600001;">
                        <h5 class="modal-title" style="color:#fff !important;">
                            🎁 আজকের কুইজে অংশ নিন
                        </h5>

                        <button type="button" class="close" data-dismiss="modal"
                            style="color:#fff !important; opacity:1; text-shadow:none;">
                            <span style="color:#fff !important;">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center" style="padding:25px 20px;">

                        <div
                            style="
                        background:#f8f9fa;
                        border:1px solid #eee;
                        border-radius:10px;
                        padding:15px;
                        
                        text-align:left;
                    ">

                            <div
                                style="padding:15px;background:#f8f9fa;border-radius:8px;margin-bottom:15px;text-align:center;">
                                🎯 <strong>প্রতিটি সঠিক উত্তরের জন্য</strong><br>
                                <span style="font-size:20px;color:#28a745;font-weight:bold;">
                                    +১ টাকা এড ব্যালেন্স
                                </span>
                            </div>

                            @php
                                $fees = \App\Models\Fee::first();
                            @endphp

                            <h5 style="text-align:center;color:#333;margin:20px 0 15px;">
                                🏆 সাপ্তাহিক বিজয়ী পুরস্কার
                            </h5>

                            <p style="text-align:center;margin-bottom:15px;">
                                প্রতি সপ্তাহে সকল কুইজের সঠিক উত্তরদাতাদের মধ্য থেকে লটারির মাধ্যমে ৩ জন বিজয়ী নির্বাচন করা
                                হবে।
                            </p>

                            <div style="margin-bottom:10px;">
                                🥇 <strong>১ম বিজয়ী:</strong>
                                <span style="color:#d9534f;font-weight:bold;">
                                    {{ number_format($fees->quiz_1st_prize ?? 0) }} টাকা এড ব্যালেন্স
                                </span>
                            </div>

                            <div style="margin-bottom:10px;">
                                🥈 <strong>২য় বিজয়ী:</strong>
                                <span style="color:#f0ad4e;font-weight:bold;">
                                    {{ number_format($fees->quiz_2nd_prize ?? 0) }} টাকা এড ব্যালেন্স
                                </span>
                            </div>

                            <div>
                                🥉 <strong>৩য় বিজয়ী:</strong>
                                <span style="color:#5bc0de;font-weight:bold;">
                                    {{ number_format($fees->quiz_3rd_prize ?? 0) }} টাকা এড ব্যালেন্স
                                </span>
                            </div>

                        </div>

                        <div class="mt-4" style="max-height:200px; overflow-y:auto; padding-right:5px;">

                            <!--@foreach ($todayQuizPosts as $quizPost)
    -->

                            <!--    <a href="{{ route('frontend.postBySubcategory.details', [$quizPost->category->slug, $quizPost->slug]) }}"-->
                            <!--       class="btn btn-success btn-block mb-2"-->
                            <!--       style="border-radius:30px;font-weight:500; font-size:12px;">-->

                            <!--        {{ \Illuminate\Support\Str::limit($quizPost->title, 50) }}-->

                            <!--    </a>-->

                            <!--
    @endforeach-->

                            @php
                                $quizCount = $todayQuizPosts->count() + 3;

                                $banglaDigits = [
                                    '0' => '০',
                                    '1' => '১',
                                    '2' => '২',
                                    '3' => '৩',
                                    '4' => '৪',
                                    '5' => '৫',
                                    '6' => '৬',
                                    '7' => '৭',
                                    '8' => '৮',
                                    '9' => '৯',
                                ];

                                $quizCountBn = strtr($quizCount, $banglaDigits);
                            @endphp

                            <p class="text-center mb-2" style="font-size:14px; font-weight:600; color:red;">
                                আজকের {{ $quizCountBn }}টি কুইজে অংশ নিতে চোখ রাখুন সর্বশেষ সংবাদে। আপনাদের সংবাদ পাঠে
                                আগ্রহ বাড়াতে আমাদের এই উদ্যোগ।
                            </p>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function copyAffiliateLink() {
            var copyText = document.getElementById("affiliateLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Copied: " + copyText.value);
        }
    </script>
    <script>
        function openPDF(url) {
            document.getElementById('pdfViewerFrame').src = url;
            new bootstrap.Modal(document.getElementById('pdfViewerModal')).show();
        }
    </script>

    @if (!empty($todayQuizPosts) && $todayQuizPosts->count() > 0)
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('#dailyQuizOfferModal').modal('show');
                }, 1000);
            });
        </script>
    @endif
@endsection
