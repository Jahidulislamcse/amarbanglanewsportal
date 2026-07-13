@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('APPROVED NEWS') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Approved News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('post.approved') }}">{{ __('Approved News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="product-area">

        {{-- Bulk Actions --}}
        <div class="row m-2 p-2 selectPost" style="display:none;">
            <div class="col-lg-12">
                <button class="btn btn-sm btn-danger delete m-1" data-toggle="modal" data-target="#confirm-delete-option">
                    {{__('Delete')}}
                </button>

                <button id="add-to-breaking" class="btn btn-sm btn-secondary m-1">
                    <i class="fa fa-plus"></i> {{__('Add to Breaking')}}
                </button>

                <button id="add-to-feature" class="btn btn-sm btn-secondary m-1">
                    <i class="fa fa-plus"></i> {{__('Add to Feature')}}
                </button>

                <button id="remove-to-breaking" class="btn btn-sm btn-secondary m-1">
                    <i class="fa fa-minus"></i> {{__('Remove Breaking')}}
                </button>

                <button id="remove-to-feature" class="btn btn-sm btn-secondary m-1">
                    <i class="fa fa-minus"></i> {{__('Remove Feature')}}
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="row">
            <div class="col-sm-2 m-3">
                <label><b>{{__('Language')}}</b></label>
                <select id="filter_lang">
                    @foreach ($languages as $language)
                        <option data-href="{{ route('post.approved.datatables') }}?lang={{ $language->id }}"
                                value="{{ $language->id }}"
                                {{ $language->is_default==1 ? 'selected':''}}>
                            {{ $language->language }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-2 m-3">
                <label><b>{{__('Category')}}</b></label>
                <select id="category_id"></select>
            </div>
        </div>

        {{-- Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    @include('includes.admin.flash-message')

                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="form-check-input m-0 p-0" id="headercheck"></th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Postcard') }}</th>
                                    <th>{{ __('Copy Link') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Post Type') }}</th>
                                    <th>{{ __('Author') }}</th>
                                    <th>{{ __('Post Status') }}</th>
                                    <th>{{ __('Approved Time') }}</th>
                                    <th>{{ __('Total View') }}</th>
                                  
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@php
    $postcardLogoSrc = asset('assets/amarbangla.png');
@endphp

<div id="news-postcard-wrapper"
    style="position:absolute; left:-9999px; top:0; opacity:1; width:720px; overflow:hidden; z-index:9999; pointer-events:none;">
    <div class="postcard-wrap">
        <article class="postcard" aria-label="News postcard template">
            <section class="photo-panel">
                <img class="news-image" id="newsImage"
                    src=""
                    alt="">
            </section>

            <div class="orange-rule" aria-hidden="true"></div>

            <div class="logo-panel" aria-label="Amarbangla24">
                <img class="site-logo"
                    src="{{ $postcardLogoSrc }}"
                    alt="{{ $gs->title ?? 'Amar Bangla 24' }}">
            </div>

            <section class="headline-panel">
                <h1 class="headline" id="newsTitle"></h1>
            </section>

            <section class="promo-strip" aria-label="Sponsor banner">
                <div class="player-chip" aria-hidden="true"></div>
                <div class="promo-copy">
                    <span>পাঠক রেজিস্ট্রেশন করে বুঝে নিন প্রতি নিউজ ভিউতে ইনকাম, কুইজ মানি সহ আরও অনেক কিছু <span class="highlight">শুধুমাত্র আমার বাংলায়</span></span>
                </div>
            </section>

            <footer class="meta-strip " >
                <time id="newsDate" style="margin-bottom: 30px;"></time>
                <span class="center" style="margin-bottom: 30px;">বিস্তারিত কমেন্টে</span>
                <span class="site" style="margin-bottom: 30px;">amarbangla24.com.bd</span>
            </footer>
        </article>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;700;900&family=Noto+Sans+Bengali:wght@400;700;900&display=swap');

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

    #news-postcard-wrapper .headline-panel {
        position: relative;
        height: 32.2%;
        display: grid;
        place-items: center;
        padding:42px 18px 18px;
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

    #news-postcard-wrapper .headline{
        font-family: 'SolaimanLipi', Arial, sans-serif !important;
        margin:0;
        width:96%;
        max-width:96%;
    
        font-size:36px;
        font-weight:900;
        line-height:1.5;
        letter-spacing:-0.5px;
        text-align:center;
    
        display:-webkit-box;
        -webkit-line-clamp:2;
        -webkit-box-orient:vertical;
        overflow:hidden;
    
        color:#fff;
    
        text-shadow:
            0 2px 0 rgba(65,0,0,.9),
            0 4px 10px rgba(0,0,0,.45);
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
        font-size: 13px;
        color: #310004;
        font-weight: 700;
    }

    #news-postcard-wrapper .highlight {
        color: #117a47;
        -webkit-text-stroke: 1px #0c4f38;
        text-shadow: none;
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

@endsection


@section('scripts')

<script>
"use strict";

var table = $('#geniustable').DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('post.approved.datatables') }}', 
    columns: [
        {data: 'checkbox',name: 'checkbox'},
        {data: 'image_big',name: 'image_big'},
        {data: 'title',name: 'title'},
        {data: 'postcard',name: 'postcard',searchable: false,orderable: false},
        {data: 'copylink',name: 'copylink',searchable: false,orderable: false},
        {data: 'category_id',name: 'category_id'},
        {data: 'language_id',name: 'language_id'},
        {data: 'post_type',name: 'post_type'},
        {data: 'admin_id',name: 'admin_id'},
        {data: 'is_approve',name: 'is_approve'},
        {data: 'approved_at',name: 'approved_at'},
        {data: 'view_count',name: 'view_count'},
        {data: 'created_at',name: 'created_at'},
        {data: 'action',searchable: false,orderable: false}
    ],
    language: {
        processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
    },
    drawCallback: function () {
        $('.select').niceSelect();
    }
});

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.godropdown').length) {
            $('.godropdown').removeClass('active'); 
        }
    });
    
    $(document).on('click', '.go-dropdown-toggle', function(e){
        e.stopPropagation(); 
        var parent = $(this).closest('.godropdown');
        parent.toggleClass('active');
      
        $('.godropdown').not(parent).removeClass('active');
    });

</script>

<script src="{{asset('assets/admin/js/post.js')}}"></script>
<script src="{{asset('assets/admin/js/bulk.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
$(document).on('click', '.download-postcard-btn', function() {
    const btn = $(this);
    const title = btn.data('title');
    const image = btn.data('image');
    const date = btn.data('date');

    // Update postcard template with target post's info
    $('#newsImage').attr('src', image);
    $('#newsTitle').text(title);
    $('#newsDate').text(date);

    // Wait slightly for image to load in DOM
    setTimeout(() => {
        const postcard = document.querySelector('#news-postcard-wrapper .postcard');
        const originalText = btn.html();
        
        let restoreTitle = null;
        const titleEl = document.getElementById('newsTitle');
        if (titleEl) {
            restoreTitle = colorSecondVisualLine(titleEl);
        }

        // Show loading state
        btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        btn.css('pointer-events', 'none');

        html2canvas(postcard, {
            useCORS: true,
            scale: 2, // upscale for crisp print quality
            logging: false,
            allowTaint: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = 'amarbangla-postcard-' + Date.now() + '.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }).catch(err => {
            console.error('Postcard generation error:', err);
            alert('Failed to generate postcard. Please try again.');
        }).finally(() => {
            if (restoreTitle) {
                restoreTitle();
            }
            btn.html(originalText);
            btn.css('pointer-events', 'auto');
        });
    }, 400);
});

function colorSecondVisualLine(titleEl) {
    const text = titleEl.textContent.trim();
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

    const lines = {};
    spans.forEach(span => {
        const top = Math.round(span.offsetTop);
        if (!lines[top]) lines[top] = [];
        lines[top].push(span);
    });

    const lineTops = Object.keys(lines)
        .map(Number)
        .sort((a, b) => a - b);

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

$(document).on('click', '.copy-post-link-btn', function() {
    const btn = $(this);
    const url = btn.data('url');
    
    navigator.clipboard.writeText(url).then(() => {
        const originalText = btn.html();
        btn.html('<i class="fas fa-check"></i> Copied!');
        btn.css('background-color', '#6c757d');
        btn.css('border-color', '#6c757d');
        setTimeout(() => {
            btn.html(originalText);
            btn.css('background-color', '');
            btn.css('border-color', '');
        }, 2000);
    }).catch(err => {
        console.error('Copy failed:', err);
        alert('Failed to copy link.');
    });
});
</script>

@endsection
