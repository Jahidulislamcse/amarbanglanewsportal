@extends('layouts.front_custom')

<style>
    .disabled-day span {
        color: #ccc;
        pointer-events: none;
        cursor: not-allowed;
    }

    .calendar-table td.disabled-day a {
        pointer-events: none;
    }

    .calendar-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .calendar-filters select {
        flex: 1;
        padding: 8px 10px;
        font-size: 16px;
        border-radius: 6px;
        border: 2px solid #337ab7;
        width: 100%;
    }

    .disabled-date {
        background: #f5f5f5 !important;
        color: #aaa !important;
        cursor: not-allowed;
    }

    .disabled-date span {
        pointer-events: none;
    }

    .copy-success {
        display: none;
        color: green;
        font-size: 12px;
        margin-left: 8px;
    }

    .copy-btn {
        display: inline-block;
        background-color: #DC4E41;
        /* green background */
        color: white;
        padding: 3px;
        font-size: 14px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .print-btn {
        display: inline-block;
        background-color: #489DDE;
        /* green background */
        color: white;
        padding: 3px;
        font-size: 14px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }


    .copy-btn:hover {
        background-color: black;
        color: white;
        text-decoration: none;
    }

    .copy-btn .glyphicon {
        margin-right: 5px;
        /* space between icon and text */
    }

    .linkedin1 {
        background-color: #337ab7 !important;
        padding: 7px 12px !important;
        border-radius: 0% !important;
        color: #fff !important;
        /* Make icon/text white */

    }

    .linkedin1:hover {
        text-decoration: none !important;
        color: #337ab7 !important;
        border-radius: 0% !important;
        background-color: white !important;
        border-radius: 5px;
        border: 1px solid #337ab7;
    }

    .whatsapp {
        background-color: #25D366 !important;
        /* Official WhatsApp green */
        padding: 7px 12px !important;
        border-radius: 4px !important;
        /* Use small rounding, not 0% */
        color: #fff !important;
        /* Make icon/text white */
        display: inline-flex;
        align-items: center;
        gap: 6px;
        /* space between icon and text */
        text-decoration: none !important;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .whatsapp:hover {
        color: #1EBE5D !important;
        /* Slightly darker green on hover */
        text-decoration: none !important;
        background-color: white !important;
        border-radius: 5px;
        border: 1px solid #25D366;
    }

    /* ===== Slider Container ===== */
    .slider-container {
        max-width: 900px;
        margin: 20px auto;
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .slider {
        display: flex;
        transition: transform 0.6s ease-in-out;
    }

    .slider img {
        width: 100%;
        flex-shrink: 0;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .slider img[data-loaded="true"] {
        opacity: 1;
    }

    /* ===== Navigation Buttons ===== */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        padding: 10px 15px;
        font-size: 30px;
        border: none;
        cursor: pointer;
        border-radius: 50%;
        user-select: none;
        z-index: 10;
        transition: background 0.3s ease;
    }

    .slider-btn:hover {
        background: rgba(0, 0, 0, 0.7);
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }

    /* ===== Lightbox Modal ===== */
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .lightbox img {
        max-width: 90%;
        max-height: 80%;
        border-radius: 10px;
        transition: opacity 0.3s ease;
    }

    /* Lightbox Controls */
    .lightbox .close,
    .lightbox .prev-lightbox,
    .lightbox .next-lightbox {
        position: absolute;
        color: #fff;
        font-size: 40px;
        cursor: pointer;
        user-select: none;
    }

    .lightbox .close {
        top: 20px;
        right: 30px;
    }

    .lightbox .prev-lightbox,
    .lightbox .next-lightbox {
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
        border-radius: 50%;
    }

    .lightbox .prev-lightbox {
        left: 20px;
    }

    .lightbox .next-lightbox {
        right: 20px;
    }

    .lightbox .prev-lightbox:hover,
    .lightbox .next-lightbox:hover,
    .lightbox .close:hover {
        color: #ccc;
    }

    .calendar-container {
        max-width: 900px;
        margin: 40px auto;
    }

    .calendar-table {
        width: 100%;
        border-collapse: collapse;
        /* use collapse for solid borders */
        margin-top: 20px;
    }

    .calendar-table th,
    .calendar-table td {
        border: 2px solid #337ab7;
        /* visible border color */
        text-align: center;
        vertical-align: middle;
        font-size: 18px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .calendar-table th {
        background-color: #4962A4;
        color: #fff;
        font-size: 15px;
        padding: 7px;
    }

    .calendar-table td a {
        display: block;
        width: 100%;
        height: 100%;
        line-height: 40px;
        color: #333;
        text-decoration: none;
        font-weight: bold;
    }

    .calendar-table td a:hover {
        background-color: #f0ad4e;
        color: #fff;
    }

    .weekend {
        background-color: #d9edf7;
        font-weight: bold;
    }

    .dropdowns {
        text-align: center;
        margin-bottom: 20px;
    }

    .dropdowns select {
        display: inline-block;
        margin: 0 5px;
        padding: 6px 12px;
        font-size: 16px;
        border-radius: 4px;
    }

    .today {
        background-color: #f2dede !important;
        /* light red */
    }

    .today a {
        color: #a94442 !important;
        /* dark red text */
        font-weight: bold;
    }


    .calendar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(4, auto);
        gap: 10px;
        max-width: 360px;
        margin: 0 auto;
    }

    .cell1 {
        background: #fff;
        border: 1px solid #ddd;
        text-align: center;
        padding: 15px 10px;
        border-radius: 8px;
        transition: background 0.3s, transform 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .cell1:hover {
        background: #f0f8ff;
        transform: translateY(-3px);
    }

    .cell1 .icon {
        font-size: 1.8rem;
        display: block;
        margin-bottom: 5px;
    }

    .cell1 .name {
        font-size: 1.10rem;
        color: #333;
        font-weight: 500;
    }

    /* ЁЯУ▒ Mobile adjustments */
    @media (max-width: 600px) {
        .calendar {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 400px) {
        .calendar {
            grid-template-columns: 1fr;
        }
    }

    .instagrams {
        background-color: #CB2028 !important;
        padding: 7px 12px !important;
        border-radius: 4px !important;
        color: #fff !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .instagrams:hover {
        color: #000 !important;
        background: white !important;
        border: 1px solid #CB2028;
    }

    .disabled-date {
        pointer-events: none;
        opacity: 0.4;
        color: #999;
    }

    .disabled-date span {
        cursor: not-allowed;
    }

    .quiz-popup-modal {
        display: none;
        position: fixed;
        z-index: 999999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }

    .quiz-popup-content {
        background: #fff;
        width: 90%;
        max-width: 400px;
        margin: 10% auto;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        position: relative;
        animation: popupFade 0.4s ease;
    }

    .quiz-popup-content h3 {
        margin-bottom: 15px;
        color: #600001;
        font-weight: bold;
    }

    .quiz-popup-content p {
        font-size: 16px;
        margin-bottom: 20px;
    }

    .quiz-popup-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        cursor: pointer;
    }

    .quiz-popup-content {
        position: relative;
    }

    .register-popup-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #666;
        line-height: 1;
    }

    .register-popup-close:hover {
        color: #000;
    }

    @keyframes popupFade {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
@section('contents')



    <section class="singlepage-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="add">
                    </div>
                    <div class="single-cat-info">
                        <div class="single-cat-home">
                            <a href="{{ route('frontend.index') }}"><i class="fa fa-home"
                                    aria-hidden="true"></i>{{ __('Home') }} </a>
                        </div>
                        <div class="single-cat-cate">
                            @if ($data->category_id == 22 && $data->subcategory)
                                <i class="fa fa-bars" aria-hidden="true"></i>
                                <a href="{{ route('frontend.category', $data->category->slug) }}">
                                    {{ $data->subcategory->title }}
                                </a>
                            @else
                                <i class="fa fa-bars" aria-hidden="true"></i>
                                <a href="{{ route('frontend.category', $data->category->slug) }}">
                                    {{ $data->category->title }}
                                </a>
                            @endif

                        </div>
                    </div>

                    <div class="single-title">
                        <h3> {{ $data->title }}</h3>
                    </div>

                    <div class="view-section">
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-2">
                                <div class="reportar-img">
                                    @if (!empty($user_info->photo))
                                        <img src="{{ asset('assets/images/admin/' . $user_info->photo) }}" width="100%">
                                    @else
                                        <img src="{{ asset('assets/images/noimagee.gif') }}" width="100%">
                                    @endif



                                </div>
                            </div>


                            <div class="col-md-11 col-sm-11 col-xs-10">
                                <div class="reportar-sec">
                                    <div class="reportar-title">
                                        <strong>{{ $user_info->name ?? 'No Name' }}</strong>
                                        @if (!empty($reporter_title) || !empty($area))
                                            <div class="reporter-area" style="font-size:13px;color:#777;margin-top:4px;">
                                                <i class="fa fa-map-marker"></i>

                                                @if (!empty($reporter_title))
                                                    {{ $reporter_title }}
                                                @endif

                                                @if (!empty($reporter_title) && !empty($area))
                                                    ({{ $area }})
                                                @elseif(empty($reporter_title) && !empty($area))
                                                    {{ $area }}
                                                @endif
                                            </div>
                                        @endif

                                    </div>


                                    <div class="sgl-page-views-count">
                                        <ul>
                                            <?php if($default_language->id==1){?>
                                            <li> <i class="fa fa-clock-o"></i> {{ __('Published Time') }} :
                                                {{ enToBn($data->schedule_post_date) }}
                                            </li>

                                            <?php }else{?>

                                            <li> <i class="fa fa-clock-o"></i> {{ __('Published Time') }} :
                                                {{ $data->schedule_post_date }}
                                            </li>


                                            <?php }?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-img" style="position: relative; display: inline-block;">

                        <img width="600" height="337" src=""
                            data-src="{{ asset('assets/images/post/' . $data->image_big) }}"
                            class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">

                        @if ($data->post_type == 'audio')
                            <audio controls style="margin-top: 10px; display: block;">
                                <source src="{{ asset('assets/audios/' . $data->audio) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @endif

                        @if (!empty($data->image_note))
                            <div class="collected-tag"
                                style="
                                    position: absolute;
                                    bottom: 10px;
                                    left: 10px;
                                    background-color: rgba(224, 224, 224, 0.8);
                                    color: #000000;
                                    font-weight: bold;
                                    padding: 4px 10px;
                                    border-radius: 4px;
                                    font-size: 14px;
                                ">
                                {{ $data->image_note }}
                            </div>
                        @endif

                    </div>



                    <div class="single-dtls">
                        <div style="margin-bottom:10px;">
                            <a href="{{ route('frontend.postBySubcategory.details', [$data->category->slug, $data->slug]) }}/print"
                                class="print-btn">
                                <span class="glyphicon glyphicon-print"></span> {{ __('Print News') }}</a>

                            <a href="javascript:void(0)" style="float:right; margin-left: 10px;" class="copy-btn"
                                onclick="copyHiddenValue()">
                                <span class="glyphicon glyphicon-copy"></span>কপি লিংক</a>

                            <a href="javascript:void(0)" style="float:right; margin-left: 10px;" class="copy-btn"
                                id="sharePostcardBtn">
                                <span class="glyphicon glyphicon-share-alt"></span> ফটোকার্ড</a>

                            <input type="hidden" id="hiddenUrl" value="{{ request()->fullUrl() }}">
                        </div>
                        <span id="copyMsg" style="float:right" class="copy-success">Copied!</span>
                        <div style="clear:both"></div>
                        <div id="newsText">{!! $data->description !!}</div>

                        @php
                            $postcardLogoSrc = asset('assets/amarbangla.png');
                        @endphp

                        <div id="news-postcard-wrapper"
                            style="position:absolute; left:-9999px; top:0; opacity:1; width:720px; overflow:hidden; z-index:9999; pointer-events:none;">
                            <div class="postcard-wrap">
                                <article class="postcard" aria-label="News postcard template">
                                    <section class="photo-panel">
                                        <img class="news-image" id="newsImage"
                                            src="{{ asset('assets/images/post/' . $data->image_big) }}"
                                            alt="{{ $data->title }}">
                                    </section>

                                    <div class="orange-rule" aria-hidden="true"></div>

                                    <div class="logo-panel" aria-label="Amarbangla24">
                                        <img class="site-logo" src="{{ $postcardLogoSrc }}"
                                            alt="{{ $gs->title ?? 'Amar Bangla 24' }}">
                                    </div>

                                    <section class="headline-panel">
                                        <h1 class="headline" id="newsTitle">{{ $data->title }}</h1>
                                    </section>

                                    <section class="promo-strip" aria-label="Sponsor banner">
                                        <div class="player-chip" aria-hidden="true"></div>
                                        <div class="promo-copy">
                                            <span>পাঠক রেজিস্ট্রেশন করে বুঝে নিন প্রতি নিউজ ভিউতে ইনকাম, কুইজ মানি সহ আরও
                                                অনেক কিছু <span class="highlight">শুধুমাত্র আমার বাংলায়</span></span>
                                        </div>
                                        {{-- <div class="sponsor-mark">নগদ</div> --}}
                                    </section>

                                    <footer class="meta-strip ">
                                        <time id="newsDate"
                                            style="margin-bottom: 30px;">{{ $default_language->id == 1 ? enToBn($data->schedule_post_date) : $data->schedule_post_date }}</time>
                                        <span class="center" style="margin-bottom: 30px;">বিস্তারিত কমেন্টে</span>
                                        <span class="site" style="margin-bottom: 30px;">amarbangla24.com.bd</span>
                                    </footer>
                                </article>
                            </div>
                        </div>

                        <style>
                            #news-postcard-wrapper .postcard-wrap {
                                width: min(100%, 720px);
                            }

                            #news-postcard-wrapper .postcard {
                                position: relative;
                                width: 100%;
                                aspect-ratio: 1080 / 1248;
                                overflow: hidden;
                                background: #082f1f;
                                border: 10px solid #145a32;
                                box-shadow: 0 24px 70px rgba(0, 0, 0, 0.38);
                                font-family: "Noto Serif Bengali", "Noto Sans Bengali", "SolaimanLipi", "Siyam Rupali", Georgia, serif;
                            }

                            #news-postcard-wrapper .photo-panel {
                                position: relative;
                                height: 56.8%;
                                overflow: hidden;
                                background: #0a3322;
                            }

                            #news-postcard-wrapper .news-image {
                                width: 100%;
                                height: 100%;
                                display: block;
                                object-fit: cover;
                                object-position: center top;
                                filter: brightness(0.78) contrast(1.05) saturate(0.95);
                            }

                            #news-postcard-wrapper .photo-panel::after {
                                display: none;
                            }

                            #news-postcard-wrapper .orange-rule {
                                position: absolute;
                                top: 56.3%;
                                left: 0;
                                right: 0;
                                height: 7px;
                                background: linear-gradient(90deg, #0d4b2b, #6fcf97 48%, #d7270d);
                                z-index: 5;
                            }

                            #news-postcard-wrapper .logo-panel {
                                position: absolute;
                                top: calc(56.3% - 50px);
                                left: 50%;
                                z-index: 8;
                                width: clamp(280px, 45vw, 220px);
                                transform: translateX(-50%);
                                display: grid;
                                place-items: center;
                                padding: 0;
                                background: transparent;
                                border-radius: 12px;
                                box-shadow: none;
                            }

                            #news-postcard-wrapper .site-logo {
                                width: 100%;
                                max-width: 260px;
                                height: auto;
                                object-fit: contain;
                                display: block;
                            }

                            #news-postcard-wrapper .logo-small {
                                position: relative;
                                z-index: 1;
                                display: block;
                                font-family: Arial, Helvetica, sans-serif;
                                font-size: clamp(10px, 1.4vw, 13px);
                                font-weight: 800;
                                line-height: 1;
                                text-transform: uppercase;
                                text-align: center;
                            }

                            #news-postcard-wrapper .logo-large {
                                position: relative;
                                z-index: 1;
                                display: block;
                                margin-top: -2px;
                                font-family: Arial, Helvetica, sans-serif;
                                font-size: clamp(34px, 6.7vw, 54px);
                                font-weight: 900;
                                line-height: 0.8;
                                text-align: center;
                                letter-spacing: -3px;
                            }

                            #news-postcard-wrapper .headline-panel {
                                position: relative;
                                height: 32.2%;
                                display: grid;
                                place-items: center;
                                padding: 42px 18px 18px;
                                background: radial-gradient(circle at 16% 78%, rgba(255, 255, 255, 0.08) 0 1px, transparent 2px),
                                    radial-gradient(circle at 84% 22%, rgba(255, 255, 255, 0.07) 0 1px, transparent 2px),
                                    linear-gradient(115deg, rgba(32, 0, 10, 0.74) 0%, transparent 34%),
                                    linear-gradient(145deg, #310004 0%, #760009 52%, #ca0508 100%);
                                background-size: auto, 34px 34px, auto, auto;
                            }

                            #news-postcard-wrapper .headline-panel::before,
                            #news-postcard-wrapper .headline-panel::after {
                                content: "";
                                position: absolute;
                                border: 2px solid rgba(255, 255, 255, 0.06);
                                border-radius: 50%;
                                pointer-events: none;
                            }

                            #news-postcard-wrapper .headline-panel::before {
                                width: 270px;
                                height: 270px;
                                left: -78px;
                                bottom: -126px;
                                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0.025), 0 0 0 32px rgba(255, 255, 255, 0.018);
                            }

                            #news-postcard-wrapper .headline-panel::after {
                                width: 230px;
                                height: 230px;
                                right: -84px;
                                top: 38px;
                                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0.02);
                            }

                            #news-postcard-wrapper .headline {
                                margin: 0;
                                width: 96%;
                                max-width: 96%;

                                font-size: 36px;
                                font-weight: 900;
                                line-height: 1.5;
                                letter-spacing: -0.5px;
                                text-align: center;

                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                overflow: hidden;

                                color: #fff;

                                text-shadow:
                                    0 2px 0 rgba(65, 0, 0, .9),
                                    0 4px 10px rgba(0, 0, 0, .45);
                            }

                            #news-postcard-wrapper .promo-strip {
                                height: 6.6%;
                                position: relative;
                                overflow: hidden;
                                background: #f7faf5;
                                border-top: 2px solid rgba(13, 75, 43, 0.95);
                            }

                            #news-postcard-wrapper .promo-copy {
                                position: absolute;
                                width: 100%;
                                height: 100%;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                gap: 8px;
                                font-size: 15px;
                                color: #310004;
                                font-weight: 700;
                            }

                            #news-postcard-wrapper .promo-line {
                                width: clamp(10px, 9vw, 20px);
                                height: 5px;
                                background: #8a0006;
                                border-radius: 999px;
                                box-shadow: 0 10px 0 -2px #8a0006;
                            }

                            #news-postcard-wrapper .highlight {
                                color: #117a47;
                                -webkit-text-stroke: 1px #0c4f38;
                                text-shadow: none;
                            }

                            #news-postcard-wrapper .sponsor-mark {
                                position: absolute;
                                right: 16px;
                                top: 50%;
                                transform: translateY(-50%);
                                font-size: 13px;
                                font-weight: 700;
                                color: #ffffff;
                                background: #1e6d49;
                                padding: 4px 10px;
                                border-radius: 10px;
                            }

                            #news-postcard-wrapper .meta-strip {
                                padding: 0 12px;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                color: #fff;
                                font-size: 14px;
                                letter-spacing: 0.02em;
                                height: 8%;
                            }

                            #news-postcard-wrapper .meta-strip time,
                            #news-postcard-wrapper .meta-strip .center,
                            #news-postcard-wrapper .meta-strip .site {
                                display: inline-block;
                            }

                            #news-postcard-wrapper .center {
                                text-align: center;
                                flex: 1;
                            }
                        </style>

                    </div>
                    <div style="text-align:right; font-size:13px; color:#555; margin-bottom: 30px;">
                        <b>{{ __('Approved by') }}:</b><br>
                        {{ $data->approvedBy->name ?? 'No Name' }} ({{ $data->approvedBy->role->name ?? '' }})<br>
                        <span class="text-muted">

                        </span>
                    </div>


                    @if ($data->quiz && $isTodayPost && (!auth('web')->check() || auth('web')->user()->is_reader == 1))
                        <div class="panel panel-default" style="margin-bottom:2px;">
                            <div class="panel-heading" style="background:#600001;color:white;">
                                <h3 class="panel-title fnten_bn">
                                    কুইজে অংশ নিন, নিয়মিত পুরস্কার জিতুন
                                </h3>
                            </div>
                        </div>

                        <div id="quizWidget" class="panel panel-default poll-widget"
                            data-quiz-id="{{ $data->quiz->id }}" data-auth="{{ auth('web')->check() ? 1 : 0 }}">

                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    {{ $data->quiz->question }}
                                </h3>
                            </div>

                            <div class="panel-body">

                                <div class="alert alert-info" style="font-size:15px; line-height:1.8;">
                                    📘 এই কুইজের সঠিক উত্তর জানতে সম্পূর্ণ সংবাদটি মনোযোগ সহকারে পড়ুন।
                                    সংবাদে থাকা তথ্য থেকেই কুইজের উত্তর নির্বাচন করুন।
                                </div>

                                @if ($quizAnswered)
                                    <div class="alert alert-success">
                                        আপনি ইতোমধ্যে এই কুইজে অংশগ্রহণ করেছেন।
                                    </div>
                                @else
                                    <div class="quiz-options">

                                        <button type="button" class="btn btn-default btn-block quiz-btn"
                                            data-answer="1">

                                            <div class="row">
                                                <div class="col-xs-12 text-left option-text">
                                                    {{ $data->quiz->option_1 }}
                                                </div>
                                            </div>

                                        </button>

                                        <button type="button" class="btn btn-default btn-block quiz-btn"
                                            data-answer="2">

                                            <div class="row">
                                                <div class="col-xs-12 text-left option-text">
                                                    {{ $data->quiz->option_2 }}
                                                </div>
                                            </div>

                                        </button>

                                        <button type="button" class="btn btn-default btn-block quiz-btn"
                                            data-answer="3">

                                            <div class="row">
                                                <div class="col-xs-12 text-left option-text">
                                                    {{ $data->quiz->option_3 }}
                                                </div>
                                            </div>

                                        </button>

                                        <button type="button" class="btn btn-default btn-block quiz-btn"
                                            data-answer="4">

                                            <div class="row">
                                                <div class="col-xs-12 text-left option-text">
                                                    {{ $data->quiz->option_4 }}
                                                </div>
                                            </div>

                                        </button>

                                    </div>

                                    <div id="quizMessage" class="mt-3"></div>
                                @endif

                            </div>

                        </div>
                    @endif



                    @if (!empty($encouraging_ads))
                        @foreach ($encouraging_ads as $ad)
                            @if ($ad->banner_type === 'image')
                                <a href="{{ $ad->link }}" target="_blank">
                                    <img src="{{ asset('assets/images/addBanner/' . $ad->photo) }}"
                                        style="width:100%; margin:10px 0;">
                                </a>
                            @else
                                {!! $ad->banner_code !!}
                            @endif
                        @endforeach
                    @endif

                    <div class="sgl-page-social-title" style="margin-top: 50px;">
                        <h4>{{ __('If you liked this news, please share it.') }} </h4>
                    </div>

                    <div class="sgl-page-social">
                        <ul>
                            <li><a href="http://www.facebook.com/sharer.php?u={{ request()->fullUrl() }}"
                                    class="ffacebook" target="_blank"> <i class="fa fa-facebook"></i> Facebook</a></li>


                            <li><a href="https://wa.me/?text={{ request()->fullUrl() }}" class="whatsapp"
                                    target="_blank"> <i class="fa fa-whatsapp"></i> Whatsapp </a></li>

                        </ul>
                    </div>

                    <!-- *(view-tab show or hide open)*-->

                    <div id="comments" class="comments-area">

                    </div><!-- #comments -->
                    <!-- *(view-tab show or hide close)*-->

                    <?php
                    if ($default_language->id == 1) {
                        $title_size = 80;
                    } else {
                        $title_size = 80;
                    }
                    ?>

                    <div class="sgl-cat-tittle">
                        {{ __('More news in this section') }}
                    </div>
                    @foreach ($division_news->chunk(3) as $division_news_chunk)
                        <div class="row">
                            @foreach ($division_news_chunk as $division_new)
                                <div class="col-sm-4 col-md-4">
                                    <div class="Name-again box-shadow">
                                        <div class="image-again">
                                            <a
                                                href="{{ route('frontend.postBySubcategory.details', [$division_new->category->slug, $division_new->slug]) }}">
                                                <img width="600" height="337"
                                                    data-src="{{ asset('assets/images/post/' . $division_new->image_big) }}"
                                                    class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                    alt="" decoding="async">
                                            </a>
                                            <h4 class="sgl-hadding">
                                                <a
                                                    href="{{ route('frontend.postBySubcategory.details', [$division_new->category->slug, $division_new->slug]) }}">
                                                    {{ strlen($division_new->title) > $title_size ? mb_substr($division_new->title, 0, $title_size, 'utf-8') : $division_new->title }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="add"><?php echo $Details_Page_Bottom_Division_Size_750x95 ?? null; ?></div>

                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="tab-header">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            <li role="presentation" class="active"><a href="#tab21" aria-controls="tab21"
                                    role="tab" data-toggle="tab" aria-expanded="false">{{ __('Latest News') }} </a>
                            </li>
                            <li role="presentation"><a href="#tab22" aria-controls="tab22" role="tab"
                                    data-toggle="tab" aria-expanded="true">{{ __('Popular News') }} </a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content ">
                            <div role="tabpanel" class="tab-pane in active" id="tab21">

                                <div class="news-titletab">

                                    @foreach (is_recents($default_language->id) as $is_recent)
                                        @if ($is_recent->image_big || $is_recent->rss_image)
                                            @if ($is_recent->image_big)
                                                <div class="small-img tab-border">
                                                    <img width="600"
                                                        src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs="
                                                        onerror="this.onerror=null;this.src='{{ asset('assets/images/noimagee.gif') }}}';"
                                                        height="337"
                                                        data-src="{{ asset('assets/images/post/' . $is_recent->image_big) }}"
                                                        class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                        alt="" decoding="async">
                                                    <h4 class="hadding_03"><a
                                                            href="{{ route('frontend.postBySubcategory.details', [$is_recent->category->slug, $is_recent->slug]) }}">{{ strlen($is_recent->title) > $title_size ? mb_substr($is_recent->title, 0, $title_size, 'utf-8') : $is_recent->title }}
                                                        </a></h4>
                                                </div>
                                            @endif

                                            @if ($is_recent->rss_image)
                                                <div class="small-img tab-border">
                                                    <img width="600" height="337"
                                                        data-src="{{ asset('assets/images/post/' . $is_recent->rss_image) }}"
                                                        class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                        alt="" decoding="async">
                                                    <h4 class="hadding_03"><a
                                                            href="{{ route('frontend.postBySubcategory.details', [$is_recent->category->slug, $is_recent->slug]) }}">{{ strlen($is_recent->title) > $title_size ? mb_substr($is_recent->title, 0, $title_size, 'utf-8') : $is_recent->title }}
                                                        </a></h4>
                                                </div>
                                            @endif

                                            @if ($is_recent->post_type == 'audio')
                                                <audio controls>
                                                    <source src="{{ asset('assets/audios/' . $is_recent->audio) }}"
                                                        type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @endif
                                        @else
                                            <div class="small-img tab-border">
                                                <img width="600" height="337"
                                                    data-src="{{ asset('assets/images/nopic.png') }}"
                                                    class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                    alt="" decoding="async">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab22">

                                <div class="news-titletab">
                                    @foreach ($top_views as $post)
                                        @if ($post)
                                            @if ($post->image_big || $post->rss_image)
                                                @if ($post->image_big)
                                                    <div class="small-img tab-border">
                                                        <img width="600" height="337"
                                                            data-src="{{ asset('assets/images/post/' . $post->image_big) }}"
                                                            class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                            alt="" decoding="async">
                                                        <h4 class="hadding_03"><a
                                                                href="{{ route('frontend.postBySubcategory.details', [$post->category->slug, $post->slug]) }}">{{ strlen($post->title) > $title_size ? mb_substr($post->title, 0, $title_size, 'utf-8') : $post->title }}
                                                            </a></h4>
                                                    </div>
                                                @endif

                                                @if ($post->rss_image)
                                                    <div class="small-img tab-border">
                                                        <img width="600" height="337"
                                                            data-src="{{ asset('assets/images/post/' . $post->rss_image) }}"
                                                            class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                            alt="" decoding="async">
                                                        <h4 class="hadding_03"><a
                                                                href="{{ route('frontend.postBySubcategory.details', [$post->category->slug, $post->slug]) }}">{{ strlen($post->title) > $title_size ? mb_substr($post->title, 0, $title_size, 'utf-8') : $post->title }}
                                                            </a></h4>
                                                    </div>
                                                @endif

                                                @if ($post->post_type == 'audio')
                                                    <audio controls>
                                                        <source src="{{ asset('assets/audios/' . $post->audio) }}"
                                                            type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                @endif
                                            @else
                                                <div class="small-img tab-border">
                                                    <img width="600" height="337"
                                                        data-src="{{ asset('assets/images/nopic.png') }}"
                                                        class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                        alt="" decoding="async">
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>


                    <div style="margin-bottom:15px;">
                        @if ($default_language->id == 1)
                            @include('partial.front2.salat')
                        @else
                            @include('partial.front2.salaten')
                        @endif
                    </div>

                    <div class="panel panel-default" style="margin-bottom:0px;">
                        <div class="panel-heading" style="background:#600001;color:white;">
                            <h3 class="panel-title fnten_bn">
                                <a href="{{ route('front.newspopularyesterdday') }}">
                                    {{ __('See yesterday’s popular news with just one click') }}</a>
                            </h3>
                        </div>
                    </div>

                    <div class="widget_text widget_area">
                        <div class="textwidget custom-html-widget" style="margin-bottom:10px;">
                            <a href="{{ route('front.newspopularyesterdday') }}"><img width="100%"
                                    data-src="{{ asset('assets/' . $default_language->id . '.gif') }}"
                                    class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                    alt="" decoding="async">
                            </a>
                        </div>
                    </div>

                    <div id="pollWidget" class="panel panel-default poll-widget"
                        data-question-id="{{ $poll->id ?? '' }}">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                @if ($poll)
                                    {{ $poll->question }}
                                @else
                                    {{ __('No active polls available.') }}
                                @endif
                            </h3>
                        </div>

                        @if ($poll)
                            <div class="panel-body">
                                <!-- Poll Options -->
                                <div class="poll-options">
                                    @foreach ($poll->answers as $answer)
                                        <button type="button" class="btn btn-default btn-block poll-btn"
                                            data-answer-id="{{ $answer->id }}">
                                            <div class="row">
                                                <div class="col-xs-8 text-left option-text">
                                                    {{ $answer->poll_option }}
                                                </div>
                                                <div class="col-xs-4 text-right">
                                                    <span class="percent">0%</span>
                                                    (<span class="count">0</span>)
                                                </div>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>

                                <hr>
                                <div class="text-right">
                                    <strong>{{ __('Total Vote') }}:</strong> <span data-total>0</span>
                                </div>
                            </div>
                        @endif
                    </div>


                    @php
                        $cacheKey = 'zodiac_block_v2_' . $default_language->id;

                        $items = cache()->rememberForever($cacheKey, function () use ($default_language) {
                            $today = date('Y-m-d');

                            $icons = [
                                1 => '/assets/horoscope/images/aries.png',
                                2 => '/assets/horoscope/images/taurus.png',
                                3 => '/assets/horoscope/images/gemini.png',
                                4 => '/assets/horoscope/images/cancer.png',
                                5 => '/assets/horoscope/images/leo.png',
                                6 => '/assets/horoscope/images/virgo.png',
                                7 => '/assets/horoscope/images/libra.png',
                                8 => '/assets/horoscope/images/scorpio.png',
                                9 => '/assets/horoscope/images/Sagittarius-PNG-Image.png',
                                10 => '/assets/horoscope/images/Capricorn-PNG-Image.png',
                                11 => '/assets/horoscope/images/Aquarius-Transparent-Image.png',
                                12 => '/assets/horoscope/images/pisces.png',
                            ];

                            $list = rashghifalllists($default_language->id);

                            $output = [];

                            foreach ($list as $id => $title) {
                                $cleanTitle = str_replace('<br/>', '', $title);

                                $output[] = [
                                    'title' => $title,
                                    'icon' => $icons[$id],
                                    'url' => route('frontend.rashifall', [$today, $id, 55, slug_create($cleanTitle)]),
                                ];
                            }

                            return $output;
                        });
                    @endphp


                    <div class="panel panel-default" style="margin-bottom:0px;">
                        <div class="panel-heading" style="background:#600001;color:white;">
                            <h3 class="panel-title fnten_bn">
                                {{ __('See Horoscope with just one click') }}
                            </h3>
                        </div>
                    </div>

                    <div class="calendar" style="margin-bottom:15px;">

                        @foreach ($items as $item)
                            <a class="cell1" href="{{ $item['url'] }}">

                                <span class="icon">
                                    <img src="{{ $item['icon'] }}" alt="Zodiac Icon" width="40" height="40"
                                        loading="lazy">
                                </span>

                                <span class="name">
                                    {!! $item['title'] !!}
                                </span>

                            </a>
                        @endforeach

                    </div>

                    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0"
                        nonce="XXXXXXXX"></script>

                    <div class="fb-page" data-href="https://www.facebook.com/profile.php?id=61581509769626"
                        data-tabs="" data-width="360" data-height="130" data-small-header="true"
                        data-hide-cover="false" data-show-facepile="false">
                    </div>


                    <div class="add">
                        <div class="widget_text widget_area">
                            <div class="textwidget custom-html-widget">

                                @if (!empty($side_bar_ads))
                                    @foreach ($side_bar_ads as $ad)
                                        @if ($ad->banner_type === 'image')
                                            <a href="{{ $ad->link }}" target="_blank">
                                                <img src="{{ asset('assets/images/addBanner/' . $ad->photo) }}"
                                                    style="width:100%; margin-bottom:10px;">
                                            </a>
                                        @else
                                            {!! $ad->banner_code !!}
                                        @endif
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="calendar-container">
                        <h4 style="text-align:center;font-weight:bold;margin-bottom:10px;">তারিখ অনুযায়ী সংবাদ</h4>

                        <div class="calendar-filters">
                            <select id="monthSelect"></select>
                            <select id="yearSelect"></select>
                        </div>

                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th>রবি</th>
                                    <th>সোম</th>
                                    <th>মঙ্গল</th>
                                    <th>বুধ</th>
                                    <th>বৃহস্পতি</th>
                                    <th>শুক্র</th>
                                    <th>শনি</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody"></tbody>
                        </table>
                    </div>


                </div>

            </div>

            <div id="quizPopupModal" class="quiz-popup-modal">
                <div class="quiz-popup-content">

                    <span class="quiz-popup-close">&times;</span>

                    <h3>🎁 কুইজে অংশ নিন</h3>

                    <div
                        style="
                        background:#f8f9fa;
                        border:1px solid #ddd;
                        border-radius:8px;
                        padding:10px;
                        margin-bottom:10px;
                        text-align:left;
                    ">

                        <div
                            style="padding:15px;background:#f8f9fa;border-radius:8px;text-align:center;">
                            🎯 <strong>প্রতিটি সঠিক উত্তরের জন্য</strong><br>
                            <span style="font-size:20px;color:#28a745;font-weight:bold;">
                                ১ টাকা এড ব্যালেন্স
                            </span>
                        </div>

                        @php
                            $fees = \App\Models\Fee::select(
                                'quiz_1st_prize',
                                'quiz_2nd_prize',
                                'quiz_3rd_prize',
                            )->first();
                        @endphp

                        <h3 style="text-align:center;color:#333;margin:10px 0 15px;">
                            🏆 সাপ্তাহিক পুরস্কার
                        </h3>

                        <p style="text-align:center;margin-bottom:15px;">
                            প্রতি সপ্তাহে সকল কুইজের সঠিক উত্তরদাতাদের মধ্য থেকে ৩ জন বিজয়ী নির্বাচন করা
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

                        @if ($weeklyWinners->count())
                            <hr>

                            <h4 class="text-center mb-3">🏆 গত সপ্তাহের বিজয়ীরা</h4>

                            <div
                                style="display:flex;justify-content:space-between;align-items:flex-start;text-align:center;">

                                @foreach ($weeklyWinners as $winner)
                                    @php
                                        $user = $winner->answer->user;

                                        $photo =
                                            $user && !empty($user->photo)
                                                ? asset('assets/images/admin/' . $user->photo)
                                                : 'https://static.vecteezy.com/system/resources/thumbnails/024/983/914/small_2x/simple-user-default-icon-free-png.png';

                                        $phone =
                                            '01****' . substr(preg_replace('/\D/', '', $winner->answer->phone), -3);
                                    @endphp

                                    <div style="flex:1;padding:0 5px;">

                                        <img src="{{ $photo }}"
                                            style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #ffc107;">

                                        <div style="font-size:12px;font-weight:normal;margin-top:6px;">
                                            {{ \Illuminate\Support\Str::limit($winner->answer->name, 10) }}
                                        </div>

                                        <div style="font-size:12px;color:#666;">
                                            {{ $phone }}
                                        </div>

                                        <div style="font-size:20px;">
                                            @if ($winner->position == 1)
                                                🥇
                                            @elseif($winner->position == 2)
                                                🥈
                                            @else
                                                🥉
                                            @endif
                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        @endif

                    </div>



                    <button id="goToQuizBtn" class="btn btn-danger btn-block">
                        এখনই অংশ নিন
                    </button>

                </div>
            </div>
            <div id="quizRegisterModal" class="quiz-popup-modal">
                <div class="quiz-popup-content">

                    <span class="register-popup-close">&times;</span>

                    <h3>🔒পাঠক অ্যাকাউন্ট করুন</h3>

                    <p>
                        কুইজে অংশ নিতে প্রথমে একটি পাঠক অ্যাকাউন্ট করতে হবে।
                    </p>

                    <p style="font-size:15px; line-height:1.8; margin-top:15px;">
                        পাঠক অ্যাকাউন্টের মাধ্যমে কুইজে অংশগ্রহণ, প্রতি রেফারেল বোনাস, সংবাদ দেখার ভিত্তিতে আয়সহ বিভিন্ন
                        সুবিধা উপভোগ করতে রেজিস্ট্রেশন করুন।
                    </p>

                    <a href="{{ route('register_reader') }}" class="btn btn-danger btn-block">
                        রেজিস্টার করুন
                    </a>

                </div>

            </div>

            @if (!auth('web')->check())
                <div id="readerRegisterModal" class="quiz-popup-modal">
                    <div class="quiz-popup-content">

                        <span class="register-popup-close">&times;</span>

                        <h3>🔒 পাঠক অ্যাকাউন্ট করুন</h3>

                        <p>
                            আরও সুবিধা পেতে একটি পাঠক অ্যাকাউন্ট তৈরি করুন।
                        </p>

                        <p style="font-size:15px; line-height:1.8; margin-top:15px;">
                            পাঠক অ্যাকাউন্টের মাধ্যমে কুইজে অংশগ্রহণ, প্রতি রেফারেলে বোনাস,
                            সংবাদ দেখার ভিত্তিতে আয় এবং অন্যান্য বিশেষ সুবিধা উপভোগ করতে পারবেন।
                        </p>

                        <a href="{{ route('register_reader') }}" class="btn btn-danger btn-block">
                            রেজিস্টার করুন
                        </a>

                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const quizWidget = document.getElementById('quizWidget');

        if (!quizWidget) {
            return;
        }

        const modal = document.getElementById('quizPopupModal');
        const closeBtn = document.querySelector('.quiz-popup-close');
        const goBtn = document.getElementById('goToQuizBtn');

        setTimeout(function() {
            modal.style.display = 'block';
        }, 3000);

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        goBtn.addEventListener('click', function() {

            modal.style.display = 'none';

            quizWidget.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            quizWidget.style.boxShadow = '0 0 20px red';

            setTimeout(function() {
                quizWidget.style.boxShadow = '';
            }, 3000);
        });

    });
</script>
<script>
    function copyHiddenValue() {
        const url = document.getElementById('hiddenUrl').value;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => {
                alert('URL copied!');
            });
        } else {
            // Fallback for older browsers
            const temp = document.createElement('textarea');
            temp.value = url;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            alert('URL copied!');
        }
    }

    function downloadPostcardImage() {
        const postcardWrapper = document.getElementById('news-postcard-wrapper');
        if (!postcardWrapper) {
            alert('Unable to find postcard template.');
            return;
        }

        const postcard = postcardWrapper.querySelector('.postcard');
        const button = document.getElementById('sharePostcardBtn');

        if (button) {
            button.disabled = true;
            button.textContent = 'Preparing...';
        }

        let restoreTitle = null;

        const titleEl = document.getElementById('newsTitle');
        if (titleEl) {
            restoreTitle = colorSecondVisualLine(titleEl);
        }

        setTimeout(() => {
            html2canvas(postcard, {
                    scale: 1,
                    useCORS: false,
                    allowTaint: true,
                    backgroundColor: null,
                    imageTimeout: 5000,
                })
                .then((canvas) => {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = 'amarbangla-postcard.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch((error) => {
                    console.error('Postcard generation failed:', error);
                    alert('Unable to generate postcard image. Please try again.');
                })
                .finally(() => {
                    if (restoreTitle) {
                        restoreTitle();
                    }

                    if (button) {
                        button.disabled = false;
                        button.textContent = 'Share Postcard';
                    }
                });
        }, 100);
    }

    function colorSecondVisualLine(titleEl) {
        const text = titleEl.textContent.trim();

        // Save original
        const originalHTML = titleEl.innerHTML;
        const originalDisplay = titleEl.style.display;
        const originalLineClamp = titleEl.style.webkitLineClamp;
        const originalOverflow = titleEl.style.overflow;

        // Remove clamp and flex display temporarily to prevent children from stacking vertically
        titleEl.style.display = 'block';
        titleEl.style.webkitLineClamp = 'unset';
        titleEl.style.overflow = 'visible';

        const words = text.split(' ');
        titleEl.innerHTML = '';

        const spans = [];

        words.forEach((word, i) => {
            const span = document.createElement('span');
            span.textContent = word + (i < words.length - 1 ? ' ' : '');
            titleEl.appendChild(span);
            spans.push(span);
        });

        // Find line positions
        const lines = {};
        spans.forEach(span => {
            const top = Math.round(span.offsetTop);
            if (!lines[top]) lines[top] = [];
            lines[top].push(span);
        });

        const lineTops = Object.keys(lines)
            .map(Number)
            .sort((a, b) => a - b);

        // Color second line
        if (lineTops.length > 1) {
            lines[lineTops[1]].forEach(span => {
                span.style.color = '#ffd700';
            });
        }

        return () => {
            titleEl.innerHTML = originalHTML;
            titleEl.style.display = originalDisplay;
            titleEl.style.webkitLineClamp = originalLineClamp;
            titleEl.style.overflow = originalOverflow;
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        const shareButton = document.getElementById('sharePostcardBtn');
        if (shareButton) {
            shareButton.addEventListener('click', downloadPostcardImage);
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        console.log("Poll script loaded");

        const pollWidget = document.getElementById('pollWidget');
        if (!pollWidget) {
            console.log("Poll widget not found");
            return;
        }

        const questionId = pollWidget.dataset.questionId;

        // SAFE CSRF HANDLING (prevents crash)
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

        if (!csrf) {
            console.warn("CSRF token not found!");
        }

        function updateUI(data) {
            console.log("STATS DATA RECEIVED:", data);

            if (!data) return;

            const totalEl = document.querySelector('[data-total]');
            if (totalEl) {
                totalEl.innerText = data.total;
            }

            if (data.stats && Array.isArray(data.stats)) {
                data.stats.forEach(stat => {
                    const btn = document.querySelector(`.poll-btn[data-answer-id="${stat.answer_id}"]`);

                    if (btn) {
                        const percentEl = btn.querySelector('.percent');
                        const countEl = btn.querySelector('.count');

                        if (percentEl) percentEl.innerText = stat.percent + '%';
                        if (countEl) countEl.innerText = stat.count;
                    }
                });
            }
        }

        // LOAD STATS ON PAGE LOAD
        fetch(`{{ url('/poll/stats') }}/${questionId}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error("Failed to load stats");
                }
                return res.json();
            })
            .then(data => {
                console.log("Initial stats loaded");
                updateUI(data);
            })
            .catch(err => {
                console.error("Stats fetch error:", err);
            });

        // VOTE CLICK HANDLER
        document.querySelectorAll('.poll-btn').forEach(btn => {
            btn.addEventListener('click', function() {

                const answerId = this.dataset.answerId;

                fetch("{{ route('front.poll.vote') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            poll_question_id: questionId,
                            poll_answer_id: answerId
                        })
                    })
                    .then(res => {

                        if (res.status === 403) {
                            alert('You already voted!');
                            return null;
                        }

                        if (!res.ok) {
                            throw new Error("Vote request failed");
                        }

                        return res.json();
                    })
                    .then(data => {
                        if (!data) return;

                        console.log("Vote response:", data);

                        alert('Vote submitted successfully!');

                        updateUI(data);

                        // disable buttons after vote
                        document.querySelectorAll('.poll-btn').forEach(b => {
                            b.disabled = true;
                        });
                    })
                    .catch(err => {
                        console.error("Vote error:", err);
                    });

            });
        });

    });
</script>
<script>
    const monthNamesBn = [
        "জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন",
        "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"
    ];

    function toBn(num) {
        return num.toString().replace(/\d/g, d => "০১২৩৪৫৬৭৮৯" [d]);
    }

    function renderCalendar(month, year) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const firstDay = new Date(year, month).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        const tbody = document.getElementById('calendarBody');
        tbody.innerHTML = "";

        let date = 1;

        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');

            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');

                if (i === 0 && j < firstDay) {
                    cell.innerHTML = "";
                } else if (date > daysInMonth) {
                    break;
                } else {

                    const thisDate = new Date(year, month, date);
                    thisDate.setHours(0, 0, 0, 0);

                    const formattedDate =
                        year + "-" +
                        String(month + 1).padStart(2, '0') + "-" +
                        String(date).padStart(2, '0');

                    if (thisDate > today) {
                        // FUTURE DATE → DISABLED
                        cell.classList.add('disabled-date');
                        cell.innerHTML = "<span>" + toBn(date) + "</span>";
                    } else {
                        const link = document.createElement('a');
                        link.href = "/news/archive?date=" + formattedDate;
                        link.innerText = toBn(date);

                        if (
                            date === today.getDate() &&
                            month === today.getMonth() &&
                            year === today.getFullYear()
                        ) {
                            cell.classList.add('today');
                        }

                        cell.appendChild(link);
                    }

                    date++;
                }

                row.appendChild(cell);
            }

            tbody.appendChild(row);
        }
    }


    function populateSelectors() {
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');

        const currentYear = new Date().getFullYear();

        for (let m = 0; m < 12; m++) {
            const opt = document.createElement('option');
            opt.value = m;
            opt.text = monthNamesBn[m];
            monthSelect.appendChild(opt);
        }

        for (let y = currentYear - 5; y <= currentYear + 1; y++) {
            const opt = document.createElement('option');
            opt.value = y;
            opt.text = toBn(y);
            yearSelect.appendChild(opt);
        }

        monthSelect.value = new Date().getMonth();
        yearSelect.value = currentYear;

        monthSelect.addEventListener('change', () =>
            renderCalendar(+monthSelect.value, +yearSelect.value)
        );
        yearSelect.addEventListener('change', () =>
            renderCalendar(+monthSelect.value, +yearSelect.value)
        );

        renderCalendar(+monthSelect.value, +yearSelect.value);
    }

    document.addEventListener('DOMContentLoaded', populateSelectors);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.quiz-btn').forEach(function(button) {

            button.addEventListener('click', function() {

                const quizWidget = document.getElementById('quizWidget');
                const quizMessage = document.getElementById('quizMessage');

                if (!quizWidget) {
                    alert('Quiz widget not found');
                    return;
                }

                const isAuth = quizWidget.dataset.auth;

                // Guest user → show register popup
                if (isAuth === "0") {

                    const registerModal =
                        document.getElementById('quizRegisterModal');

                    if (registerModal) {
                        registerModal.style.display = 'block';
                    } else {
                        alert('Register modal not found');
                    }

                    return;
                }

                const quizId = quizWidget.dataset.quizId;
                const selectedAnswer = this.dataset.answer;
                const csrf = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

                if (!quizId) {
                    alert('Error: Quiz ID not found');
                    return;
                }

                if (!csrf) {
                    alert('Error: CSRF token not found');
                    return;
                }

                document.querySelectorAll('.quiz-btn').forEach(function(btn) {
                    btn.disabled = true;
                });

                fetch("{{ route('quiz.answer') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": csrf,
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            quiz_id: quizId,
                            selected_answer: selectedAnswer
                        })
                    })
                    .then(async function(response) {

                        const data = await response.json().catch(function() {
                            return {};
                        });

                        if (!response.ok) {
                            throw {
                                status: response.status,
                                message: data.message || 'Request failed'
                            };
                        }

                        return data;
                    })
                    .then(function(data) {

                        if (quizMessage) {

                            let alertClass = data.correct ? 'alert-success' :
                                'alert-danger';

                            quizMessage.innerHTML =
                                '<div class="alert ' + alertClass + '">' +
                                (data.message || '') +
                                '</div>';
                        }

                        document.querySelector('.quiz-options')?.remove();
                    })
                    .catch(function(error) {

                        let message = error.message || 'Something went wrong';

                        if (error.status === 419) {
                            message = 'CSRF token mismatch. Please refresh the page.';
                        } else if (error.status === 401) {
                            message = 'Please login first.';
                        } else if (error.status === 422) {
                            message = 'Invalid quiz data.';
                        } else if (error.status === 404) {
                            message = 'Quiz submit route not found.';
                        } else if (error.status === 500) {
                            message = 'Server error. Check Laravel log.';
                        }

                        if (quizMessage) {
                            quizMessage.innerHTML =
                                '<div class="alert alert-danger">' +
                                message +
                                '</div>';
                        }

                        document.querySelectorAll('.quiz-btn').forEach(function(btn) {
                            btn.disabled = false;
                        });
                    });

            });

        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const registerModal =
            document.getElementById('quizRegisterModal');

        const closeBtn =
            document.querySelector('.register-popup-close');

        if (!registerModal || !closeBtn) return;

        closeBtn.addEventListener('click', function() {
            registerModal.style.display = 'none';
        });

        window.addEventListener('click', function(e) {

            if (e.target === registerModal) {
                registerModal.style.display = 'none';
            }

        });

    });
</script>

@if (!auth('web')->check() && (!$data->quiz || !$isTodayPost))
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById('readerRegisterModal');
            const closeBtn = document.querySelector('#readerRegisterModal .register-popup-close');

            if (!modal) return;

            setTimeout(function() {
                modal.style.display = 'block';
            }, 3000);

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }

            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });

        });
    </script>
@endif

@if($data->is_pending == 0)
<!-- Stay Timer Border Visual Progress & AJAX View Increment -->
<div class="stay-timer-border stay-timer-border-top"></div>
<div class="stay-timer-border stay-timer-border-right"></div>
<div class="stay-timer-border stay-timer-border-bottom"></div>
<div class="stay-timer-border stay-timer-border-left"></div>

<!-- Premium Toast Notification -->
<div id="stay-timer-toast" style="display: none; position: fixed; bottom: 30px; right: 30px; background: rgba(30, 30, 30, 0.95); color: #fff; padding: 16px 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35); z-index: 1000000; font-family: 'Hind Siliguri', 'Inter', sans-serif; display: flex; align-items: center; gap: 12px; border-left: 4px solid #28a745; transform: translateY(100px); opacity: 0; transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.5s ease;">
    <div style="background: rgba(40, 167, 69, 0.2); border-radius: 50%; padding: 6px; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>
    <div>
        <h4 id="toast-title" style="margin: 0; font-size: 15px; font-weight: 700; color: #fff;">পড়া সম্পন্ন হয়েছে!</h4>
        <p id="toast-message" style="margin: 3px 0 0 0; font-size: 13px; color: #ccc;">আপনার ব্যালেন্স ও ভিউ যুক্ত করা হয়েছে।</p>
    </div>
</div>

<style>
    .stay-timer-border {
        position: fixed;
        z-index: 999999;
        pointer-events: none;
        transition: opacity 0.5s ease-out;
    }
    .stay-timer-border-top {
        top: 0;
        left: 0;
        height: 4px;
        width: 0;
        background: linear-gradient(to right, #28a745, #00d084);
        box-shadow: 0 1px 5px rgba(0, 208, 132, 0.4);
        animation: grow-top 30s linear forwards;
    }
    .stay-timer-border-right {
        top: 0;
        right: 0;
        width: 4px;
        height: 0;
        background: linear-gradient(to bottom, #00d084, #28a745);
        box-shadow: -1px 0 5px rgba(0, 208, 132, 0.4);
        animation: grow-right 30s linear forwards;
    }
    .stay-timer-border-bottom {
        bottom: 0;
        right: 0;
        height: 4px;
        width: 0;
        background: linear-gradient(to left, #28a745, #00d084);
        box-shadow: 0 -1px 5px rgba(0, 208, 132, 0.4);
        animation: grow-bottom 30s linear forwards;
    }
    .stay-timer-border-left {
        bottom: 0;
        left: 0;
        width: 4px;
        height: 0;
        background: linear-gradient(to top, #00d084, #28a745);
        box-shadow: 1px 0 5px rgba(0, 208, 132, 0.4);
        animation: grow-left 30s linear forwards;
    }

    @keyframes grow-top {
        0% { width: 0; }
        25% { width: 100%; }
        100% { width: 100%; }
    }
    @keyframes grow-right {
        0%, 25% { height: 0; }
        50% { height: 100%; }
        100% { height: 100%; }
    }
    @keyframes grow-bottom {
        0%, 50% { width: 0; }
        75% { width: 100%; }
        100% { width: 100%; }
    }
    @keyframes grow-left {
        0%, 75% { height: 0; }
        100% { height: 100%; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var duration = 30000; // 30 seconds
        
        setTimeout(function() {
            // Trigger AJAX request after 30 seconds
            $.ajax({
                url: "{{ route('frontend.post.incrementView') }}",
                type: "POST",
                data: {
                    id: "{{ $data->id }}",
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.incremented) {
                            document.getElementById('toast-title').innerText = "{{ $lid }}" === "1" ? "পড়া সম্পন্ন হয়েছে!" : "Reading completed!";
                            document.getElementById('toast-message').innerText = "{{ $lid }}" === "1" ? "আপনার ব্যালেন্স ও ভিউ যুক্ত করা হয়েছে।" : "Your views and balance have been updated.";
                        } else {
                            document.getElementById('toast-title').innerText = "{{ $lid }}" === "1" ? "ধন্যবাদ!" : "Thank you!";
                            document.getElementById('toast-message').innerText = "{{ $lid }}" === "1" ? "লেখাটি ইতিমধ্যে পড়া হয়েছে।" : "You have already read this article today.";
                        }
                        
                        showToastNotification();
                        
                        // Slowly fade out the borders
                        setTimeout(function() {
                            document.querySelectorAll('.stay-timer-border').forEach(function(el) {
                                el.style.opacity = '0';
                            });
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    console.error("Failed to register page stay timer.", xhr);
                }
            });
        }, duration);

        function showToastNotification() {
            var toast = document.getElementById('stay-timer-toast');
            toast.style.display = 'flex';
            toast.offsetHeight; // trigger reflow
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            
            setTimeout(hideToastNotification, 5000);
        }

        function hideToastNotification() {
            var toast = document.getElementById('stay-timer-toast');
            toast.style.transform = 'translateY(100px)';
            toast.style.opacity = '0';
            setTimeout(function() {
                toast.style.display = 'none';
            }, 500);
        }
    });
</script>
@endif
