@extends('layouts.front_custom')

<style>
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

    .single-title,
    .single-dtls1 {
        text-align: center !important;
    }
</style>
@section('contents')

    @php
        $isExpired = false;
        if ($category != 'admin' && !empty($data->next_payment_date)) {
            $isExpired = strtotime($data->next_payment_date) < strtotime(date('Y-m-d'));
        }

        $areaText = '';
        if ($category != 'admin') {
            $lid = isset($default_language) ? $default_language->id : 1;
            $column = $lid == 1 ? 'bn_name' : 'name';

            $josn_decode = json_decode($data->report_type, true);
            if (is_array($josn_decode) && isset($josn_decode[0])) {
                $typeId = (int) $josn_decode[0];

                if (in_array($typeId, [29, 31, 37]) && !empty($data->division_id)) {
                    $areaText = DB::table('divisions')->where('id', $data->division_id)->value($column);
                } elseif (in_array($typeId, [30, 36]) && !empty($data->district_id)) {
                    $areaText = DB::table('districts')->where('id', $data->district_id)->value($column);
                } elseif (in_array($typeId, [32, 35]) && !empty($data->thana_id)) {
                    $areaText = DB::table('upazilas')->where('id', $data->thana_id)->value($column);
                } elseif ($typeId == 34 && !empty($data->union_id)) {
                    $areaText = DB::table('unions')->where('id', $data->union_id)->value($column);
                }
            }
        }
    @endphp

    @if ($isExpired)
        <section class="singlepage-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center" style="margin: 100px auto;">

                        <div class="alert alert-danger"
                            style="font-size: 22px; font-weight: bold; padding: 40px; border-radius: 8px; border: 2px solid #ebccd1; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background-color: #f2dede; color: #a94442;">
                            Reporter is suspended temporarily
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="singlepage-section">



            <div class="container">


                <div class="row">
                    <div class="col-md-8 col-sm-8">




                        @if ($category == 'admin')
                            <div class="single-title">
                                <h3> {{ $data->role_name }}</h3>
                            </div>
                            <div class="single-img">
                                <img data-src="<?php echo asset('assets/images/admin/' . $data->photo); ?>"
                                    class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
                            </div>

                            <div class="single-dtls">
                                <div id="newsText" style="text-align:center;font-weight:bold">{!! $data->name !!}</div>
                            </div>

                            <div class="single-dtls ">
                                <div id="newsText">{!! $data->details !!}</div>
                            </div>
                        @else
                            <?php
                            
                            $josn_decode = json_decode($data->report_type, true);
                            $title = '';
                            if (isset($josn_decode[0])) {
                                $title = isset($reportcategories[$josn_decode[0]]) ? $reportcategories[$josn_decode[0]] : '';
                            }
                            
                            ?>
                            <div class="single-title">
                                <h3> {{ $title }}</h3>
                                @if (!empty($areaText))
                                    <div style="font-size: 14px; color: #666; margin-top: 5px; font-weight: normal;">
                                        {{ $areaText }}
                                    </div>
                                @endif
                            </div>

                            <div class="single-img">
                                <img width="600" height="337" data-src="<?php echo asset('assets/images/admin/' . $data->photo); ?>"
                                    class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
                            </div>

                            <div class="single-dtls">
                                <div id="newsText" style="text-align:center;font-weight:bold">{!! $data->name !!}</div>
                            </div>
                            <div class="single-dtls ">
                                <div id="newsText">{!! $data->details !!}</div>
                            </div>
                        @endif



                    </div>

                    <?php
                    if ($default_language->id == 1) {
                        $title_size = 80;
                    } else {
                        $title_size = 80;
                    }
                    ?>


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

                        <div id="pollWidget" class="panel panel-default poll-widget " style="margin-top: 30px;"
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

                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0"
                            nonce="XXXXXXXX"></script>

                        <div class="fb-page" style=""
                            data-href="https://www.facebook.com/profile.php?id=61581509769626" data-tabs=""
                            data-width="360" data-height="130" data-small-header="true" data-hide-cover="false"
                            data-show-facepile="false">
                        </div>






                    </div>
                </div>

            </div>
        </section>

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
            document.addEventListener('DOMContentLoaded', function() {
                var widget = document.querySelector('.poll-widget');
                if (!widget) return;

                var questionId = widget.dataset.questionId;
                var csrfToken = '{{ csrf_token() }}';

                // English to Bangla digit conversion
                function toBanglaNumber(num) {
                    const engToBn = {
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
                    return num.toString().replace(/[0-9]/g, digit => engToBn[digit]);
                }


                // Bind vote button click events
                widget.querySelectorAll('.poll-btn').forEach(function(button) {
                    button.addEventListener('click', function() {
                        vote(this.dataset.answerId);
                    });
                });



                const slider = document.getElementById("slider");
                const slides = slider.querySelectorAll("img");
                const prevBtn = document.getElementById("prev");
                const nextBtn = document.getElementById("next");

                let currentIndex = 0;
                let autoSlideInterval;

                /* ===== Lazy Loading Function ===== */
                const lazyLoad = (img) => {
                    const src = img.getAttribute('data-src');
                    if (!src) return;
                    const image = new Image();
                    image.src = src;
                    image.onload = () => {
                        img.src = src;
                        img.setAttribute('data-loaded', 'true');
                    };
                };

                // Lazy load first image immediately
                lazyLoad(slides[0]);

                // Lazy load next image when slider moves
                const preloadNext = (index) => {
                    if (slides[index] && !slides[index].getAttribute('data-loaded')) {
                        lazyLoad(slides[index]);
                    }
                };

                /* ===== Slider Functions ===== */
                function updateSlider() {
                    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
                    lazyLoad(slides[currentIndex]);
                    preloadNext(currentIndex + 1);
                }

                function showNextSlide() {
                    currentIndex = (currentIndex + 1) % slides.length;
                    updateSlider();
                }

                function showPrevSlide() {
                    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                    updateSlider();
                }

                /* ===== Auto Slide ===== */
                function startAutoSlide() {
                    autoSlideInterval = setInterval(showNextSlide, 3000);
                }

                function stopAutoSlide() {
                    clearInterval(autoSlideInterval);
                }

                nextBtn.addEventListener("click", () => {
                    showNextSlide();
                    stopAutoSlide();
                    startAutoSlide();
                });

                prevBtn.addEventListener("click", () => {
                    showPrevSlide();
                    stopAutoSlide();
                    startAutoSlide();
                });

                // Start Auto Slide
                startAutoSlide();

                /* ===== Lightbox ===== */
                const lightbox = document.getElementById("lightbox");
                const lightboxImage = document.getElementById("lightboxImage");
                const closeLightbox = document.getElementById("close");
                const lightboxPrev = document.getElementById("lightboxPrev");
                const lightboxNext = document.getElementById("lightboxNext");

                // Open Lightbox
                slides.forEach((slide, index) => {
                    slide.addEventListener("click", () => {
                        currentIndex = index;
                        openLightbox();
                    });
                });

                function openLightbox() {
                    lightbox.style.display = "flex";
                    lightboxImage.src = slides[currentIndex].getAttribute('src') || slides[currentIndex].getAttribute(
                        'data-src');
                }

                function closeLightboxModal() {
                    lightbox.style.display = "none";
                }

                function lightboxShowNext() {
                    currentIndex = (currentIndex + 1) % slides.length;
                    openLightbox();
                }

                function lightboxShowPrev() {
                    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                    openLightbox();
                }

                closeLightbox.addEventListener("click", closeLightboxModal);
                lightboxNext.addEventListener("click", lightboxShowNext);
                lightboxPrev.addEventListener("click", lightboxShowPrev);

                // Close Lightbox by clicking outside image
                lightbox.addEventListener("click", (e) => {
                    if (e.target === lightbox) {
                        closeLightboxModal();
                    }
                });

                // Keyboard Controls
                document.addEventListener("keydown", (e) => {
                    if (lightbox.style.display === "flex") {
                        if (e.key === "ArrowRight") lightboxShowNext();
                        if (e.key === "ArrowLeft") lightboxShowPrev();
                        if (e.key === "Escape") closeLightboxModal();
                    }
                });


            });
        </script>
        <script src="https://code.responsivevoice.org/responsivevoice.js?key=mlakr0m7"></script>
        <script>
            function playBanglaNews(elementId) {
                const text = document.getElementById(elementId).innerText;
                responsiveVoice.speak(text, "UK English Female");
            }
            $(document).ready(function() {

                $('.like-btn').click(function() {
                    var btn = $(this);
                    var newsId = '{{ $data->id }}';

                    $.post('{{ route('news.like') }}', {
                        id: newsId,
                        _token: '{{ csrf_token() }}'
                    }, function(data) {
                        btn.find('.like-count').text(data.count);
                        if (data.liked) {
                            btn.addClass('btn-success').removeClass('btn-primary');
                        } else {
                            btn.addClass('btn-primary').removeClass('btn-success');
                        }
                    });
                });
            });

            function copyHiddenValue() {
                // Get hidden input value
                var hiddenValue = document.getElementById("hiddenUrl").value;

                // Create a temporary input to copy the value
                var tempInput = document.createElement("input");
                tempInput.type = "text";
                tempInput.value = hiddenValue;
                document.body.appendChild(tempInput);

                // Select and copy the value
                tempInput.select();
                tempInput.setSelectionRange(0, 99999); // For mobile devices
                document.execCommand("copy");

                // Remove temporary input
                document.body.removeChild(tempInput);

                // Show success message
                var msg = document.getElementById("copyMsg");
                msg.style.display = "inline";
                setTimeout(function() {
                    msg.style.display = "none";
                }, 2000);
            }
        </script>
    @endif
@endsection
