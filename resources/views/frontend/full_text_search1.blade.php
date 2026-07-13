@extends('layouts.front_custom')

@push('css')
@endpush

@section('contents')

    <?php
    $placementMap = config('placements');
    $side_bar_ads = '';
    
    foreach (is_ads([19, 20]) as $ad) {
        if (!isset($placementMap[$ad->add_placement])) {
            continue; // Skip if placement doesn't match
        }
        $varName = str_replace(' ', '_', $placementMap[$ad->add_placement]);
    
        if ($ad->add_placement == 19) {
            if ($ad->banner_type === 'image') {
                $side_bar_ads .=
                    '<a href="' .
                    ($ad->link ?? '#') .
                    '" target="_blank">
                					<img class="lazy alignnone size-full wp-image-209" 
                					data-src="' .
                    asset('assets/images/addBanner/' . $ad->photo) .
                    '" 
                					alt="" width="100%" height="auto">
                				</a>';
            } else {
                $side_bar_ads .= $ad->banner_code;
            }
        } else {
            if ($ad->banner_type === 'image') {
                $$varName = '<a href="' . ($ad->link ?? '#') . '" target="_blank">' . '<img width="728" height="90" class="lazy image wp-image-471  attachment-full size-full" data-src="' . asset('assets/images/addBanner/' . $ad->photo) . '" alt="" width="100%" height="auto">' . '</a>';
            } else {
                $$varName = $ad->banner_code;
            }
        }
    }
    
    if ($default_language->id == 1) {
        $title_size = 40;
        $description_first_size = 90;
        $description_sec_size = 400;
    } else {
        $title_size = 40;
        $description_first_size = 90;
        $description_sec_size = 400;
    }
    ?>

    <section class="archive-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="category_info">
                        @if ($results->count() > 0)
                            @foreach ($results as $key => $result)
                                @php
                                    // Get post safely (only if needed)
                                    if ($type) {
                                        $post = \App\Models\Post::with('category')
                                            ->where('id', $result->id ?? $result->post_id)
                                            ->where('language_id', $default_language->id)
                                            ->first();
                                    } else {
                                        $post = $result;
                                    }
                                @endphp

                                @if ($post)
                                    <div class="archive_page archive_back">
                                        <div class="row">

                                            {{-- IMAGE SECTION --}}
                                            <div class="col-md-4 col-sm-4">
                                                @if ($post->image_big)
                                                    <div class="small-img tab-border">
                                                        <img width="600" height="337"
                                                            data-src="{{ asset('assets/images/post/' . $post->image_big) }}"
                                                            class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                            alt="" decoding="async">
                                                    </div>
                                                @elseif ($post->rss_image)
                                                    <div class="small-img tab-border">
                                                        <img width="600" height="337"
                                                            data-src="{{ asset('assets/images/post/' . $post->rss_image) }}"
                                                            class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                                            alt="" decoding="async">
                                                    </div>
                                                @endif

                                                {{-- AUDIO --}}
                                                @if ($post->post_type == 'audio')
                                                    <audio controls>
                                                        <source src="{{ asset('assets/audios/' . $post->audio) }}"
                                                            type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                @endif
                                            </div>

                                            {{-- CONTENT SECTION --}}
                                            @if ($post->category)
                                                <div class="col-md-8 col-sm-8">

                                                    <h3 class="archive_title01">
                                                        <a
                                                            href="{{ route('frontend.postBySubcategory.details', [$post->category->slug, $post->slug]) }}">
                                                            {{ strlen($post->title) > $title_size ? mb_substr($post->title, 0, $title_size, 'utf-8') : $post->title }}
                                                        </a>
                                                    </h3>

                                                    {{ strlen(strip_tags($post->description)) > $description_sec_size
                                                        ? convertUtf8(substr(strip_tags($post->description), 0, $description_sec_size)) . '...'
                                                        : convertUtf8(strip_tags($post->description)) }}

                                                    <h4 class="archvie_more">
                                                        <a
                                                            href="{{ route('frontend.postBySubcategory.details', [$post->category->slug, $post->slug]) }}">
                                                            {{ __('Read More') }}
                                                        </a>
                                                    </h4>

                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            {{-- No News Found --}}
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="text-danger text-center">{{ __('This Category has no news.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <?php if($type && $type==1){?>

                        <?php }else{?>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="post-nav">


                                    {{ $results->links('pagination.custom') }}
                                </div>
                            </div>
                        </div>
                        <?php }?>

                        <div class="add"><?php echo $Category_Page_Bottom ?? null; ?></div>
                    </div>
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
                            <li role="presentation" class="active"><a href="#tab21" aria-controls="tab21" role="tab"
                                    data-toggle="tab" aria-expanded="false">{{ __('Latest News') }} </a></li>
                            <li role="presentation"><a href="#tab22" aria-controls="tab22" role="tab" data-toggle="tab"
                                    aria-expanded="true">{{ __('Popular News') }} </a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content ">
                            <div role="tabpanel" class="tab-pane in active" id="tab21">

                                <div class="news-titletab">


                                    @foreach (is_recents($default_language->id) as $is_recent)
                                        @if ($is_recent->image_big || $is_recent->rss_image)
                                            @if ($is_recent->image_big)
                                                <div class="small-img tab-border">
                                                    <img width="600" height="337"
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

                    <div class="add">
                        <div class="widget_text widget_area">
                            <div class="textwidget custom-html-widget">
                                <?php echo $side_bar_ads ?? null; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
@endpush
