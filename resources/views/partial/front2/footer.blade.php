<section class="footer_section">
    <div class="container">
        <div class="footer-border">
            <div class="row">
                <div class="col-xs-4 col-sm-2 col-md-2">
                    <div class="footer-menu"></div>
                </div>
                <div class="col-xs-4 col-sm-2 col-md-2">
                    <div class="footer-menu"></div>
                </div>
                <div class="col-xs-4 col-sm-2 col-md-2">
                    <div class="footer-menu"></div>
                </div>
                <div class="col-xs-4 col-sm-2 col-md-2">
                    <div class="footer-menu"></div>
                </div>
                <div class="col-xs-4 col-sm-2 col-md-2">
                    <div class="footer-menu"></div>
                </div>
                <div class="col-xs-4 col-sm-2 col-md-2">
                    <div class="footer-menu"></div>
                </div>
            </div>
        </div>

        @php
            use App\Models\Contact;
            use App\Models\Management;
            $contact = Contact::first();
            $management = Management::all();
        @endphp

        <div class="row footer-text">
            @if($default_language->id == 1)
                {{-- Bangla --}}
                <div class="col-md-3 col-sm-3 menu-border">
                    <div class="editorial-text">
                        @foreach($management as $m)
                            <p><strong>{{ $m->designation_bn }}</strong> {{ $m->name_bn }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="address-text">
                        <p><strong>বাণিজ্যিক কার্যালয়:</strong> {{ $contact->address_bn }}</p>
                        <p><strong>ফোনঃ</strong> {{ $contact->phone }}</p>
                        <p><strong>ই-মেইলঃ</strong> {{ $contact->email }}</p>
                        
                        @php
                            $links = explode('-', $contact->website);
                        @endphp
                        
                        <p><strong>ওয়েবসাইটঃ</strong>
                            @foreach($links as $link)
                                @php $clean = trim($link); @endphp
                                <a href="https://{{ $clean }}" target="_blank" style="color:#1E90FF; font-weight:bold; text-decoration:none; transition:0.3s;">{{ $clean }}</a>
                                @if(!$loop->last) | @endif
                            @endforeach
                        </p>
                    </div>
                </div>
            @else
                {{-- English --}}
                <div class="col-md-3 col-sm-3 menu-border">
                    <div class="editorial-text">
                        @foreach($management as $m)
                            <p><strong>{{ $m->designation }}:</strong> {{ $m->name }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="address-text">
                        <p><strong>Commercial Office:</strong> {{ $contact->address }}</p>
                        <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                        <p><strong>Email:</strong> {{ $contact->email }}</p>
                        @php
                            $links = explode('-', $contact->website);
                        @endphp
                        
                        <p><strong>Website:</strong>
                            @foreach($links as $link)
                                @php $clean = trim($link); @endphp
                                <a href="https://{{ $clean }}" target="_blank"style="color:#1E90FF; font-weight:bold; text-decoration:none; transition:0.3s;">{{ $clean }}</a>
                                @if(!$loop->last) | @endif
                            @endforeach
                        </p>

                    </div>
                </div>
            @endif

            <div class="col-md-3 col-sm-3">
                <form id="subscribeForm">
                    <div class="input-group">
                        <input type="email" class="form-control" name="email"
                            placeholder="{{ $default_language->id == 1 ? 'আপনার ইমেইল লিখুন' : 'Enter your email' }}" required>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary" style="background:#CD1D23">
                                {{ $default_language->id == 1 ? 'সদস্য হোন' : 'Subscribe' }}
                            </button>
                        </span>
                    </div>
                </form>
            
                <div id="responseMessage" class="alert mt-2" style="display:none;"></div>
            
                <div class="footer-links">
                    <ul class="footer-links-list">
                        @php
                            $footer_menus = \App\Models\Page::where('placement','footer')
                                            ->where('status',1)
                                            ->get();
                        @endphp
                
                        @foreach ($footer_menus as $menu)
                            <li class="footer-links-item">
                                <a href="{{ route('dynamic.page', $menu->slug) }}" class="footer-links-anchor">
                                    {{ $menu->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    .root {
        padding: 10px 10px;
        background-color: #000000;
        margin-bottom: 0px;
    }

    .scrollToTop {
        width: 40px;
        height: 40px;
        padding: 10px;
        background: #CD1D23;
        position: fixed;
        right: 25px;
        bottom: 70px;
        border-radius: 10%;
        z-index: 999;

    }

    .scrollToTop i.fa {
        font-size: 35px;
        color: #ffffff;
        font-weight: 400;
        top: -2px;
        display: block;
        position: absolute;
        right: 7px;
    }

    .fixed_cat_title {
        padding: 7px;
        border-bottom: 1px solid #CD1D23;
        margin-bottom: 7px;

    }

    .cat_title {
        display: block;
        margin-bottom: 7px;
        background-color: transparent;

    }

    .cat_title a {
        color: #000;
        font-weight: 400;
        font-size: 16px;
        text-decoration: none;
        position: relative;
        display: inline-block;
        margin: 0px 0 0 0 !important;
        padding: 5px 11px;
        border: 1px solid #01284f;
        background: transparent !important;
    }

    .cat_title a:after {
        left: 100%;
        height: 0;
        width: 7px;
        position: absolute;
        top: 0;
        pointer-events: none;
        margin-left: 0;
        margin-top: -1px;
        border-bottom: 35px solid #01284F;
        border-right: 28px solid transparent;
    }

    .fixed_cat_title span {

        border-left: 7px solid #CD1D23;
        background: transparent !important;
    }

    .fixed_cat_title a {
        color: #000;
    }

    .section_five {
        background-color: #ffffff;
        padding: 0px 5px;
    }

    .gallery-title {
        background-color: #CD1D23;
        border-left: 5px solid#873600;
        padding-left: 10px;
        margin-bottom: 15px;
        margin-top: 17px;
        color: #ffffff;
        font-weight: 400;
        font-size: 20px;
    }

    .gallery-title a {
        color: #ffffff;
        font-weight: 400;
        font-size: 20px;
        text-decoration: none;
    }

    .slider-padding {
        padding: 15px 10px;
        background-color: #ffffff;
        margin-bottom: 7px;
    }

    .photo-title a {
        color: #bb6d0f;
        font-weight: 400;
        font-size: 20px;
        text-decoration: none;
    }

    .photo-content {
        color: #000;
        text-align: justify;
        line-height: auto;
    }

    .small-pto-title {
        color: #000;
        font-size: 16px;
        font-weight: 400;
        line-height: auto;
        padding: 0px 5px 5px 5px;
    }

    .slide-small-img {
        margin-bottom: 7px;
        background: #f5f5f5;
    }

    .video-bg {
        background-color: #f8f8f8;
        padding: 5px 10px 40px 10px;
    }

    .defalt_hadding a {
        color: #000;
        font-size: 18px;
        text-decoration: none;
        font-weight: 400;
        line-height: 1.3;
    }

    .video-more-news {
        text-align: center;
        background-color: #CD1D23;
        padding: 7px;
    }

    .widget_section {
        background: transparent;
        padding: 0px 0px;
    }

    .footer-border {
        border-bottom: 0px solid #D3DBCE;
        padding-top: 10px;
        margin-bottom: 0px;
    }

    .top_hdr_social ul {
        list-style: none;
        padding: 0;
        float: right;
        margin-top: -10px;

    }

    .root_01 {
        font-size: 16px;
        color: #B8B8B8;

    }

    @media only screen and (max-width: 600px) {
        .root_01 {
            text-align: center !important;
            padding: 0px 0px 19px 0px !important;
        }
    }
        .footer-links {
        margin-top: 15px;
    }
    
    .footer-links-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .footer-links-item {
        margin-bottom: 6px;
    }
    
    .footer-links-anchor {
        font-size: 14px;
        color: #ffffff;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .footer-links-anchor:hover {
        color: #CD1D23;
        text-decoration: underline;
    }

</style>

<section class="root">
    <div class="container">
        <div class="row">
            <div class="root_01">
                <?php if($default_language->id==1){?>
                <center>{{ $gs->copyright_text }}</center>
                <?php }else{?>
                <center>Copyright © <?php date('Y'); ?> All Rights Reserved | Amar Bangla Multimedia & Publication Ltd.
                </center>
                <?php }?>
            </div>
        </div>
        <div style="display:none"></div>
        <a href="" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
    </div>
</section>

<style>
    .top_hdr_social ul {
        list-style: none;
        padding: 0;
        float: right;
        margin-top: -10px;
    }

    .root_01 {
        font-size: 16px;
        color: white;

    }

    @media only screen and (max-width: 600px) {
        .root_01 {
            text-align: center !important;
            padding: 0px 0px 19px 0px !important;
        }
    }
</style>
<script src="{{ asset('assets/front2/js/lazy.js') }}" defer=""></script>
<link rel="stylesheet" id="wc-blocks-style-css" href="{{ asset('assets/front2/css/wc-blocks.css') }}" type="text/css"
    media="all">
