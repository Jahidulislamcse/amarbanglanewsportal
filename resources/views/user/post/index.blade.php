@extends('layouts.user')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('CATEGORY') }}">
<input type="hidden" id="attribute_data" value="{{ __('ADD NEW ATTRIBUTE') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('All News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('user.post.index') }}">{{ __('News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-sm-2 m-3">
                <label ><b>{{__('Language')}}</b></label>
                <select  id="filter_lang">
                    @foreach ($languages as $language)
                        <option data-href="{{ route('user.post.datatables') }}?lang={{ $language->id }}" value="{{ $language->id }}" {{ $language->is_default==1 ? 'selected':''}}>{{ $language->language }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 m-3">
                <label ><b>{{__('Category')}}</b></label>
                <select id="category_id" >

                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    @include('includes.admin.flash-message')
                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Postcard') }}</th>
                                    <th>{{ __('Copy Link') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Post Type') }}</th>
                                    <th>{{ __('Post Status') }}</th>
									<th>{{ __('Total View') }}</th>
									<th>{{ __('Unique View') }}</th>
									<th>{{ __('Current View') }}</th>
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


<div class="modal fade-scale" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>




{{-- DELETE MODAL --}}

<div class="modal fade-scale" id="confirm-delete-option" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <p class="text-center">
                    {{ __('You are trying to delete post.') }}
                </p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger bulk-delete">{{ __('Delete') }}</a>
            </div>

        </div>
    </div>
</div>

{{-- DELETE MODAL ENDS --}}

{{-- DELETE MODAL --}}

<div class="modal fade-scale" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <p class="text-center">
                    {{ __('You are trying to delete post.') }}
                </p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>

        </div>
    </div>
</div>

{{-- DELETE MODAL ENDS --}}

{{-- REJECTION REASON DETAIL MODAL --}}
<div class="modal fade-scale" id="reject-reason-modal" tabindex="-1" role="dialog" aria-labelledby="reject-reason-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold; color: #721c24;">{{ __('সংবাদ প্রত্যাখ্যানের কারণ / Rejection Reason') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-justify" style="font-size: 15px; line-height: 1.6; color: #495057; text-align: justify !important; padding: 20px; white-space: pre-wrap;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
{{-- REJECTION REASON DETAIL MODAL ENDS --}}

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

<script type="text/javascript">
    "use strict";

    var table;

     table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
		ajax: {
				url: '{{ route('user.post.datatables') }}',
				data: function (data) {

					data.type= '{{$type}}';

				}
			},
        columns: [
            {data: 'image_big',name: 'image_big'},
            {data: 'title',name: 'title'},
            {data: 'postcard',name: 'postcard',searchable: false,orderable: false},
            {data: 'copylink',name: 'copylink',searchable: false,orderable: false},
            {data: 'category_id',name: 'category_id'},
            {data: 'language_id',name: 'language_id'},
            {data: 'post_type',name: 'post_type'},
            {
                data: 'is_approve',
                name: 'is_approve',
                render: function(data, type, row) {
                    if (row.is_pending == 0) {
                        return '<span class="badge badge-success">approve</span>';
                    } else if (row.is_pending == 1) {
                        return '<span class="badge badge-warning">pending</span>';
                    } else if (row.is_pending == 2) {
                        let html = '<span class="badge badge-danger">rejected</span>';
                        if (row.reject_reason) {
                            let reason = row.reject_reason;
                            let title = 'Rejected';
                            
                            if (reason.includes('বিজ্ঞাপন/প্রচারমূলক') || reason.includes('বিজ্ঞাপন ফি')) {
                                title = 'বিজ্ঞাপন/প্রচারমূলক (Promotional)';
                            } else if (reason.includes('কর্মএলাকার বাইরে')) {
                                title = 'কর্মএলাকার বাইরে (Outside Area)';
                            } else if (reason.includes('ইতোমধ্যে প্রকাশিত')) {
                                title = 'ইতোমধ্যে প্রকাশিত (Already Published)';
                            } else if (reason.includes('ঘটনাস্থল, সময় ও তারিখ')) {
                                title = 'ঘটনাস্থল, সময় ও তারিখের অনুপস্থিতি';
                            } else if (reason.includes('অপর্যাপ্ত তথ্য ও বিবরণ')) {
                                title = 'অপর্যাপ্ত তথ্য ও বিবরণ';
                            } else if (reason.includes('যাচাই-বাছাই ছাড়া') || reason.includes('যাচাই করছি')) {
                                title = 'তথ্য যাচাই-বাছাইধীন (Under Verification)';
                            } else if (reason.includes('ছবিটি পর্যাপ্ত পরিষ্কার') || reason.includes('ছবির ওপর কোনো ধরনের')) {
                                title = 'অস্পষ্ট/নীতিমালা পরিপন্থী ছবি';
                            } else if (reason.includes('দীর্ঘদিন আগের') || reason.includes('পূর্বে ঘটে যাওয়া')) {
                                title = 'পুরোনো বা অপ্রাসঙ্গিক সংবাদ';
                            } else {
                                let lines = reason.split('\n');
                                let firstLine = lines[0].trim();
                                if (firstLine.length > 35) {
                                    title = firstLine.substring(0, 35) + '...';
                                } else {
                                    title = firstLine || 'Rejected';
                                }
                            }
                            
                            let encodedReason = encodeURIComponent(reason);
                            let escapedTitle = $('<div>').text(title).html();
                            
                            html += `
                            <div class="reject-title" style="margin-top: 5px; font-weight: bold; font-size: 11px; white-space: normal; line-height: 1.3;">
                                কারণ: <span style="font-weight: normal; color: #495057;">${escapedTitle}</span>
                                <a href="javascript:void(0);" class="show-details-btn" data-reason="${encodedReason}" style="display:block; color:#0f766e; text-decoration:underline; font-weight: bold; margin-top:2px;">বিস্তারিত দেখুন</a>
                            </div>`;
                        }
                        return html;
                    }
                    return data;
                }
            },
			{data: 'view_count',name: 'view_count'},
			{data: 'unique_count',name: 'unique_count'},
			{data: 'current_count',name: 'current_count'},
            {data: 'created_at',name: 'created_at'},
            {data: 'action',searchable: false,orderable: false}
        ],
        language: {
            processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
        },
        drawCallback: function (settings) {
            $('.select').niceSelect();
        }
    });

</script>

<script src="{{asset('assets/admin/js/userpost.js')}}"></script>
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

// Show rejection reason modal details
$(document).on('click', '.show-details-btn', function() {
    const encoded = $(this).data('reason');
    if (encoded) {
        const reasonText = decodeURIComponent(encoded);
        $('#reject-reason-modal .modal-body').text(reasonText);
        $('#reject-reason-modal').modal('show');
    }
});

// Explicitly close rejection reason modal on clicking close/dismiss buttons
$(document).on('click', '#reject-reason-modal [data-dismiss="modal"], #reject-reason-modal [data-bs-dismiss="modal"]', function() {
    $('#reject-reason-modal').modal('hide');
});
</script>

@endsection
