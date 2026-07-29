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

                    <div class="header-cards-group">
                        <a href="https://amarbangla24.tv/" target="_blank" class="live-player-link">
                            <div class="live-player-box">
                                <div class="lp-icon">
                                    <svg class="header-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                        <polygon points="10 8 16 11 10 14 10 8" fill="currentColor"></polygon>
                                    </svg>
                                    <span class="live-dot"></span>
                                </div>
                                <div class="lp-content" style="text-align: left;">
                                    <div class="lp-title">Amar Bangla 24</div>
                                    <div class="lp-subtitle">লাইভ টিভি</div>
                                </div>
                            </div>
                        </a>

                        <div class="radio-coming-box">
                            <div class="rc-icon">
                                <svg class="header-svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="8" width="20" height="12" rx="2" ry="2"></rect>
                                    <line x1="6" y1="8" x2="6" y2="3"></line>
                                    <circle cx="6" cy="3" r="1" fill="currentColor"></circle>
                                    <circle cx="8" cy="14" r="3"></circle>
                                    <rect x="15" y="11" width="4" height="2" rx="1"></rect>
                                    <circle cx="17" cy="16" r="1" fill="currentColor"></circle>
                                </svg>
                            </div>
                            <div class="rc-content" style="text-align: left;">
                                <div class="rc-title">Amar Bangla 24 Radio</div>
                                <div class="rc-subtitle">Coming Soon</div>
                            </div>
                        </div>
                    </div>
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

    .live-player-link {
        text-decoration: none !important;
        display: block;
    }

    .header-cards-group {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: nowrap;
    }

    .live-player-box, .radio-coming-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        color: #fff;
        white-space: nowrap;
        flex-shrink: 0;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }

    .live-player-box {
        background: linear-gradient(135deg, #ff007f, #ff4500);
        box-shadow: 0 2px 10px rgba(255, 0, 127, 0.2);
    }

    .live-player-link:hover .live-player-box {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 0, 127, 0.4);
    }

    .radio-coming-box {
        background: linear-gradient(135deg, #4776e6, #8e54e9);
        box-shadow: 0 2px 10px rgba(71, 118, 230, 0.2);
        cursor: default;
    }

    .radio-coming-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(71, 118, 230, 0.4);
    }

    .lp-icon, .rc-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        position: relative;
        flex-shrink: 0;
    }

    .header-svg-icon {
        width: 20px;
        height: 20px;
        color: #fff;
    }

    .live-dot {
        position: absolute;
        top: 0;
        right: 0;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: #00e676;
        border: 1.5px solid #ff4500;
        animation: pulse-live 1.5s infinite;
    }

    @keyframes pulse-live {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(0, 230, 118, 0.7);
        }
        70% {
            transform: scale(1.1);
            box-shadow: 0 0 0 5px rgba(0, 230, 118, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(0, 230, 118, 0);
        }
    }

    .lp-title, .rc-title {
        font-size: 11px;
        font-weight: 500;
        opacity: 0.9;
        line-height: 1.2;
    }

    .lp-subtitle, .rc-subtitle {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
    }

    .header-right-wrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: nowrap;
    }

    /* MOBILE OPTIMIZATION */
    @media(max-width:768px) {
        .header-right-wrap {
            justify-content: center;
            flex-wrap: wrap;
        }

        .header-cards-group {
            width: 100%;
            justify-content: center;
            gap: 8px;
        }

        .live-player-box, .radio-coming-box {
            padding: 6px 10px;
            font-size: 12px;
        }

        .lp-icon, .rc-icon {
            width: 30px;
            height: 30px;
        }

        .header-svg-icon {
            width: 16px;
            height: 16px;
        }

        .lp-title, .rc-title {
            font-size: 10px;
        }

        .lp-subtitle, .rc-subtitle {
            font-size: 11px;
        }

        .widget_area {
            width: 100%;
            text-align: center;
        }

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
    }
</style>

