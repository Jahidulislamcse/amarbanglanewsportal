<?php
if ($default_language->id == 1) {
    $date = convertToBangla();
    $url = '<a style="color:white" href="' . route('frontend.language', 2) . '"> English</a>';
} else {
    $date = date('l, d F, Y');
    $url = '<a style="color:white" href="' . route('frontend.language', 1) . '"> Bangla</a>';
}
?>

<section class="header_sections">


    <div class="container">


        <div class="row">
            <div class="col-md-4 col-sm-4">

                <div class="date">
                    <i class="fa fa-calendar-o "></i> {{ $date }}

                </div>
            </div>

            <div class="col-md-8 col-sm-12">

                <div class="top_hdr_social">

                    <?php if (isMobile()) { ?>

                    <style>
                        .mll {
                            padding-left: 20px;
                        }
                    </style>

                    <div class="mobile-topbar">
                        <ul class="list social-list">
                            @foreach ($social_links as $social_link)
                                <?php
                                $str_icon = str_replace('fab', 'fa', $social_link->icon);
                                $str_icon = str_replace('linkedin-in', 'linkedin', $str_icon);
                                ?>

                                <li>
                                    <a href="{{ $social_link->link }}" class="{{ $social_link->name }}">
                                        @if ($social_link->name == 'twitter')
                                            <img src="{{ asset('assets/images/twitter.svg') }}" width="16"
                                                height="16">
                                        @else
                                            <i class="{{ $str_icon }}"></i>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="list auth-list">
                            <li>
                                <a href="{{ route('install.app') }}" class="app-install-link" aria-label="Install app">
                                    <i class="fa fa-download"></i> ইনস্টল অ্যাপ
                                </a>
                            </li>

                            @if (!auth()->user())
                                <li>
                                    <a href="{{ route('front.login.view') }}">
                                        <i class="fa fa-sign-in"></i> Login
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('front.register.select') }}">
                                        <i class="fa fa-user"></i> Registration
                                    </a>
                                </li>
                            @else
                                @php $data = auth()->user(); @endphp

                                <li class="dropdown user-profile">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <img src="{{ $data->photo ? asset('assets/images/admin/' . $data->photo) : asset('assets/images/noimage.png') }}"
                                            style="width:22px;height:22px;border-radius:50%;margin-right:5px;">

                                        {{ $data->name }}
                                        <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu">

                                        @if (auth()->check() && auth()->user()->is_reader == 1)
                                            <li>
                                                <a href="{{ route('reader.dashboard') }}">
                                                    <i class="fa fa-tachometer"></i> Dashboard
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ route('user.dashboard') }}">
                                                    <i class="fa fa-tachometer"></i> Dashboard
                                                </a>
                                            </li>
                                        @endif

                                        <li class="divider"></li>

                                        <li>
                                            <a href="{{ route('front.logout') }}">
                                                <i class="fa fa-sign-out"></i> Logout
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <?php } else { ?>

                    <ul class=" list">
                        <li>
                            <a href="{{ route('install.app') }}" class="app-install-link" style="color:white"
                                aria-label="Install app">
                                <i class="fa fa-download"></i> ইনস্টল অ্যাপ
                            </a>
                        </li>

                        @if (!auth()->user())
                            <li>
                                <a href="{{ route('front.login.view') }}" style="color:white">
                                    <i class="fa fa-sign-in"></i> {{ __('Login') }}
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('front.register.select') }}" style="color:white">
                                    <i class="fa fa-user"></i> {{ __('Registration') }}
                                </a>
                            </li>
                        @else
                            @php $data = auth()->user(); @endphp

                            <li class="dropdown user-profile">
                                <a href="#" class="dropdown-toggle" style="color:white" data-toggle="dropdown">
                                    <img src="{{ $data->photo ? asset('assets/images/admin/' . $data->photo) : asset('assets/images/noimage.png') }}"
                                        style="width:25px;height:25px;border-radius:50%;margin-right:5px;">

                                    {{ $data->name }}
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a
                                            href="{{ Auth::check() && Auth::user()->is_reader == 1 ? route('reader.dashboard') : route('user.dashboard') }}">
                                            <i class="fa fa-tachometer"></i> Dashboard
                                        </a>
                                    </li>

                                    <li class="divider"></li>

                                    <li>
                                        <a href="{{ route('front.logout') }}">
                                            <i class="fa fa-sign-out"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>

                    <ul class="list">
                        @foreach ($social_links as $social_link)
                            <?php
                            $str_icon = str_replace('fab', 'fa', $social_link->icon);
                            $str_icon = str_replace('linkedin-in', 'linkedin', $str_icon);
                            ?>

                            <li>
                                <a href="{{ $social_link->link }}" class="{{ $social_link->name }}">

                                    @if ($social_link->name == 'twitter')
                                        <img src="{{ asset('assets/images/twitter.svg') }}" width="16"
                                            height="16">
                                    @else
                                        <i class="{{ $str_icon }}"></i>
                                    @endif

                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <?php } ?>

                </div>
            </div>
        </div>
</section>

<section class="header_section">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="header_logo">
                    <a href="{{ route('frontend.index') }}">
                        @php
                            $lid =
                                Session::get('language') ?? DB::table('languages')->where('is_default', 1)->value('id');
                            $header_footer_logo = d_logo($lid)->first();
                        @endphp

                        @if (!empty($header_footer_logo) && !empty($header_footer_logo->header_logo))
                            <img class="wp-image-119"
                                src="{{ asset('assets/images/logo/' . $header_footer_logo->header_logo) }}"
                                alt="Logo">
                        @else
                            <img class="wp-image-119"
                                src="{{ $gs->logo ? asset('assets/images/logo/' . $gs->logo) : asset('assets/front/images/logo.png') }}"
                                alt="Default Logo">
                        @endif
                    </a>
                </div>
            </div>

            <div class="col-md-9 text-right">
                <div class="header_logor header-right-wrap">
                    <div class="widget_text widget_area mr-3">
                        <div class="textwidget custom-html-widget">
                            @php
                                $header_ad = header_ads();
                            @endphp

                            @if ($header_ad)
                                @if ($header_ad->banner_type == 'image')
                                    <a href="{{ $header_ad->link }}" target="_blank" data-addid="{{ $header_ad->id }}"
                                        id="headerAdd">
                                        <img src="{{ asset('assets/images/addBanner/' . $header_ad->photo) }}"
                                            class="alignnone size-full wp-image-119" alt=""
                                            style="max-width:100%;height:auto;">
                                    </a>
                                @else
                                    {!! $header_ad->banner_code !!}
                                @endif
                            @endif

                        </div>
                    </div>

                    <a href="{{ route('worldcup.points') }}" class="worldcup-link">
                        <div class="worldcup-box">

                            <div class="wc-icon">
                                <img src="https://i.pinimg.com/originals/43/a7/fd/43a7fdc82210641cddb095d0804e0f35.png"
                                    alt="World Cup" />
                            </div>

                            <div class="wc-content">
                                <div class="wc-title">
                                    বিশ্বকাপ ২০২৬
                                </div>

                                <div class="wc-countdown">
                                    <span id="wcDays">০</span> দিন
                                    <span id="wcHours">০</span> ঘণ্টা
                                    <span id="wcMinutes">০</span> মিনিট
                                </div>


                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="menu_section" id="myHeader">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-md-11 col-sm-11">
                <div id="menu-area" class="menu_area">
                    <div class="menu_bottom">
                        <nav role="navigation" class="navbar navbar-default mainmenu">
                            <div class="navbar-header">
                                <button type="button" data-target="#navbarCollapse" data-toggle="collapse"
                                    class="navbar-toggle">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <?php
                            ?>
                            <div id="navbarCollapse" class="collapse navbar-collapse">
                                <div class="menu-home-container">
                                    <ul id="menu-home" class="nav navbar-nav">
                                        <li class=" active"><a href="{{ route('frontend.index') }}"><i
                                                    class="fa fa-home mll"></i></a></li>
                                        @foreach ($categories as $category)
                                            @php $children = $category->child->where('show_on_menu', 1); @endphp

                                            @if ($children->count() > 0)
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                        aria-haspopup="true">
                                                        {{ $category->title }}<span class="caret"></span>
                                                    </a>

                                                    <ul class="dropdown-menu">
                                                        @foreach ($children as $child)
                                                            <li><a
                                                                    href="{{ route('frontend.postBySubcategory.details', [$category->slug, $child->slug]) }}">{{ $child->title }}</a>
                                                            </li>
                                                        @endforeach

                                                        <li><a
                                                                href="{{ route('frontend.postBySubcategory.details', ['feature', 'featurenews']) }}">{{ __('Feature News') }}
                                                            </a></li>
                                                    </ul>
                                                </li>
                                            @else
                                                <li><a href="{{ route('frontend.category', $category->slug) }}"
                                                        class="{{ $loop->first ? 'active' : '' }}">{{ $category->title }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true">
                                                {{ __('Division') }}<span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                @foreach (is_division($default_language->id) as $division)
                                                    <li><a
                                                            href="{{ route('frontend.bangladesh', $division->name) }}">{{ $division->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>

                                        <li><a href="{{ route('frontend.ourteam') }}">পরিবার</a>
                                        </li>
                                        <li class=" active"><a href="{{ route('front.news_archive') }}"><i
                                                    class="fa fa-archive mll"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div><!-- /.header_bottom -->

                </div>
            </div>

            <style>
                .input-group-btn {

                    padding-top: 0px !important;

                }

                .input-group-btn {
                    padding-top: 0px !important;
                }

                .navbar-nav>li>a {
                    font-size: 15px !important;
                    padding-left: 8px;
                    padding-right: 8px;
                }
            </style>
            <div class=" col-xs-2 col-md-1 col-sm-1">
                <div class="search-large-divice">
                    <div class="search-icon-holder"> <a href="#" class="search-icon" data-toggle="modal"
                            data-target=".bd-example-modal-lg"><i class="fa fa-search" aria-hidden="true"></i></a>
                        <div class="modal fade bd-example-modal-lg" action="{{ route('frontend.index') }}"
                            tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="margin-top: 200px;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"> <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding-bottom: 100px; ">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form class="form-inline" action="{{ route('front.news_search') }}"
                                                    method="get">
                                                    <div class="input-group input-group-lg" style="width: 100%">
                                                        <input type="text" name="s"
                                                            class="form-control search" required=""
                                                            placeholder="{{ __('Write Here') }}...." value="">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default" type="submit">
                                                                <i class="fa fa-search" aria-hidden="true"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </form>
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
</section>

<section class="scrrol_section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 scrool" style="padding-right:5px;">
                <div class="col-md-2 scrool_1">
                    {{ __('Heading') }} :
                </div>
                <div class="col-md-10 scrool_2" style="height: 34px;">
                    <marquee direction="left" scrollamount="4px" onmouseover="this.stop()"
                        onmouseout="this.start()">
                        @foreach ($trendings as $t)
                            <i class="fa fa-square"></i>
                            <a
                                href="{{ route('frontend.postBySubcategory.details', [$t->category->slug, $t->slug]) }}">
                                {{ $t->title }}
                            </a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .search-icon i {
        color: #000;
        font-size: 20px;
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 5px;
        padding: 7px;
        -webkit-transition: all 0.8s;
        -moz-transition: all 0.8s;
        -o-transition: all 0.8s;
        -ms-transition: all 0.8s;
        transition: all 0.8s;
        background: #ffffff;
    }

    .mobile-topbar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        text-align: center;
    }

    .social-list {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .auth-list {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .social-list a {
        display: flex;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid #fff;
        color: #fff;
    }

    .auth-list a {
        color: #fff;
        font-size: 13px;
        white-space: nowrap;
    }

    .app-install-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 900;
    }

    .user-profile .dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    @media(max-width:480px) {
        .mobile-topbar {
            gap: 10px;
        }

        .auth-list a {
            font-size: 12px;
        }

        .social-list a {
            width: 26px;
            height: 26px;
        }
    }

    .scrool_1 {
        padding: 5px;
        font-size: 17px;
        background: #CD1D23;
        color: #fff;
        text-align: left;
        font-weight: 400;
    }

    .col-md-2 {
        width: 7.666667%;
    }

    .scrool_2 {
        padding: 4px;
        font-size: 17px;
        color: #CD1D23;
        ;
        border: 1px solid #CD1D23;
    }

    .col-md-10 {
        width: 91.333333%;
    }

    @media only screen and (max-width: 914px) {
        .col-md-10 {
            width: 75.333333%;
        }

        .col-md-2 {
            width: 24.666667%;
        }

        .header_logo,
        .header_logor {
            text-align: center;
        }

        .header_logo img {
            max-width: unset;
            height: 100px
        }

    }

    @media only screen and (min-width: 915px) {
        .wp-image-119 {
            max-width: unset;
            height: 82px
        }

        .header_logo {
            text-align: left;
        }

        .header_logor {
            text-align: right;
        }

    }

    .gallery-list {
        overflow: scroll;
        max-height: 400px;
        overflow-x: hidden;
        background-color: #fff;
    }

    .navbar-toggle {
        position: relative;
        float: left;
        padding: 9px 10px;
        margin-top: 8px;
        margin-right: 15px;
        margin-bottom: 8px;
        background-color: transparent;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .search-icon i {
        color: #000;
        font-size: 20px;
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 5px;
        padding: 7px;
        -webkit-transition: all 0.8s;
        -moz-transition: all 0.8s;
        -o-transition: all 0.8s;
        -ms-transition: all 0.8s;
        transition: all 0.8s;
        background: #ffffff;
        margin-right: 19px;
        margin-top: 2px;
    }

    .header_sections {
        background: #CD1D23;
        padding: 0px 0;
    }

    .date {
        text-align: center;
        padding: 8px 0px;
        font-size: 14px;
        font-weight: normal;
        color: #fff;
    }

    .date i {
        color: #ffffff;
        font-size: 17px;
    }

    .header_logo {
        padding: 14px 0;
        max-height: 110px;
    }

    .header_logor {
        padding: 14px 0;
        max-height: 110px;
    }

    .facebook {
        background-color: transparent;
        color: #fff;
        padding: 5px 11px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .twitter {
        background-color: transparent;
        color: #fff;
        padding: 5px 8px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .instagram {
        background-color: transparent;
        color: #fff;
        padding: 5px 9px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .youtube {
        background-color: transparent;
        color: #fff;
        padding: 5px 9px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .mainmenu .collapse ul ul,
    .mainmenu .collapse ul ul.dropdown-menu {
        background: #CD1D23;
    }

    .content-tags ul.padding15 {
        padding: 15px !important;
        margin-bottom: 15px;
    }

    .content-tags ul.padding15 {
        padding: 15px 15px 15px 0px !important;
        margin-bottom: 15px;
    }

    .content-tags ul li {
        display: inline-block;
        margin-right: 5px;
    }

    .content-tags ul li a {
        border: 1px solid #eee;
        display: block;
        background: #fff;
        text-decoration: none;
        color: #000;
        padding: 2px 10px;
        margin: 2px 0;
    }

    .content-tags ul li a:hover {
        background: #ccc;
    }

    .header_logor {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 15px;
    }

    .worldcup-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 15px;
        border-radius: 8px;
        background: linear-gradient(135deg, #7b1fa2, #1565c0);
        color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
        white-space: nowrap;
    }

    .wc-icon {
        width: 62px;
        height: 62px;
        overflow: hidden;

        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .wc-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .wc-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .wc-countdown {
        font-size: 15px;
        font-weight: 700;
        color: #ffeb3b;
    }

    .wc-countdown span {
        color: #fff;
    }

    .worldcup-box small {
        display: block;
        font-size: 11px;
        opacity: .85;
    }

    .header-right-wrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: nowrap;
    }

    /* WORLD CUP CARD INLINE FIX */
    .worldcup-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        background: linear-gradient(135deg, #7b1fa2, #1565c0);
        color: #fff;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .worldcup-link {
        text-decoration: none !important;
        display: block;
    }

    .worldcup-link:hover {
        text-decoration: none;
    }

    .worldcup-link:hover .worldcup-box {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, .25);
    }

    .worldcup-box {
        cursor: pointer;
        transition: .3s ease;
    }

    .wc-icon img {
        width: 42px;
        height: 42px;
        object-fit: contain;
    }

    /* MOBILE OPTIMIZATION */
    @media(max-width:768px) {

        .header-right-wrap {
            justify-content: center;
            flex-wrap: wrap;
        }

        .worldcup-box {
            width: 100%;
            max-width: 320px;
            justify-content: center;
            margin-top: 8px;
        }

        .wc-icon {
            width: 52px;
            height: 52px;
            font-size: 18px;
        }

        .wc-title {
            font-size: 11px;
        }

        .wc-countdown {
            font-size: 12px;
        }

        .widget_area {
            width: 100%;
            text-align: center;
        }
    }

    @media(max-width:768px) {

        .header_logo {
            text-align: center;
            padding-bottom: 10px;
        }

        .header_logo img {
            max-width: 220px;
            width: 100%;
            height: auto !important;
        }

        .header_logor {
            display: block;
            text-align: center;
            max-height: none;
            padding-top: 0;
        }

        .widget_area img {
            max-width: 100%;
            height: auto !important;
        }

        .worldcup-box {
            display: inline-flex;
            margin-top: 10px;
            padding: 6px 12px;
        }

        .wc-title {
            font-size: 11px;
        }

        .wc-countdown {
            font-size: 13px;
        }
    }
</style>

<script>
    (function() {


        const targetDate = new Date('2026-06-11T19:00:00Z').getTime();

        const bnDigits = {
            '0': '০',
            '1': '১',
            '2': '২',
            '3': '৩',
            '4': '৪',
            '5': '৫',
            '6': '৬',
            '7': '৭',
            '8': '৮',
            '9': '৯'
        };

        function toBangla(num) {
            return String(num).replace(/\d/g, d => bnDigits[d]);
        }

        function updateCountdown() {

            const now = Date.now();
            const diff = targetDate - now;

            if (diff <= 0) {
                document.querySelector('.wc-countdown').innerHTML =
                    'ম্যাচ সূচি ও পয়েন্ট টেবিল দেখতে ক্লিক করুন';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            const hours = Math.floor(
                (diff % (1000 * 60 * 60 * 24)) /
                (1000 * 60 * 60)
            );

            const minutes = Math.floor(
                (diff % (1000 * 60 * 60)) /
                (1000 * 60)
            );

            document.getElementById('wcDays').textContent = toBangla(days);
            document.getElementById('wcHours').textContent = toBangla(hours);
            document.getElementById('wcMinutes').textContent = toBangla(minutes);
        }

        updateCountdown();

        // প্রতি মিনিটে আপডেট
        setInterval(updateCountdown, 60000);

    })();
</script>
