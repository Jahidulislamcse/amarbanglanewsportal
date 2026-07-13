@extends('layouts.front_custom')

@push('css')
    <style>
        .team-img-wrapper {
            position: relative;
            overflow: hidden;
            width: 180px;
            height: 180px;
            margin: 20px auto 10px auto;
            border-radius: 8px;
            background: #f0f0f0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid #eee;
            display: block;
        }

        .team-img-wrapper img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
        }

        .expired-card {
            position: relative;
        }

        .expired-image {
            opacity: 0.2;
            filter: grayscale(100%);
        }

        .gallary {
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #eee;
            transition: transform 0.2s ease;
            text-align: center;
        }

        .gallary:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush


@section('contents')
    <div class="container">
        <div class="row">
            <div class="photogallary">

                <?php 
			foreach($newsItems as $index => $post): ?>
                <?php
                
                if ($index % 4 == 0) {
                    echo "<div style='clear:both;'></div>";
                }
                
                if ($post->type == 'admin') {
                    $name = isset($roles[$post->name]) ? $roles[$post->name] : '';
                } else {
                    $josn_decode = json_decode($post->name, true);
                    $name = '';
                    if (isset($josn_decode[0])) {
                        $name = isset($reportcategories[$josn_decode[0]]) ? $reportcategories[$josn_decode[0]] : '';
                    }
                }
                
                $isExpired = false;
                if ($post->type == 'user' && !empty($post->next_payment_date)) {
                    $isExpired = strtotime($post->next_payment_date) < strtotime(date('Y-m-d'));
                }
                
                $areaText = '';
                if ($post->type == 'user') {
                    $lid = session()->has('language') ? session()->get('language') : 1;
                    $column = $lid == 1 ? 'bn_name' : 'name';
                
                    $josn_decode = json_decode($post->name, true);
                    if (is_array($josn_decode) && isset($josn_decode[0])) {
                        $typeId = (int) $josn_decode[0];
                
                        if (in_array($typeId, [29, 31, 37]) && !empty($post->division_id)) {
                            $areaText = DB::table('divisions')->where('id', $post->division_id)->value($column);
                        } elseif (in_array($typeId, [30, 36]) && !empty($post->district_id)) {
                            $areaText = DB::table('districts')->where('id', $post->district_id)->value($column);
                        } elseif (in_array($typeId, [32, 35]) && !empty($post->thana_id)) {
                            $areaText = DB::table('upazilas')->where('id', $post->thana_id)->value($column);
                        } elseif ($typeId == 34 && !empty($post->union_id)) {
                            $areaText = DB::table('unions')->where('id', $post->union_id)->value($column);
                        }
                    }
                }
                ?>
                <div class="col-md-3 col-sm-3">
                    <div class="gallary {{ $isExpired ? 'expired-card' : '' }}">
                        <a href="{{ route('frontend.ourteam', [$post->id, $post->type]) }}" style="display: block; text-align: center;">
                            <div class="team-img-wrapper">
                                <img width="180" height="180" data-src="<?php echo asset('assets/images/admin/' . $post->photo); ?>"
                                    style="width: 180px; height: 180px; object-fit: cover; display: block; margin: 0 auto;"
                                    class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image {{ $isExpired ? 'expired-image' : '' }}"
                                    alt="">
                            </div>
                        </a>
                        @if ($isExpired)
                            <div
                                style="color: #d9534f; font-weight: bold; text-align: center; margin-top: 10px; font-size: 13px;">
                                Temporarily Disabled
                            </div>
                        @endif
                        <h4 class="photo_title" style="margin-top: 15px; margin-bottom: 5px;"><a
                                href="{{ route('frontend.ourteam', [$post->id, $post->type]) }}">{{ $name }}</a>
                        </h4>
                        @if (!empty($areaText))
                            <div class="reporter-area"
                                style="font-size: 12px; color: #777; margin-bottom: 15px; font-weight: normal; padding: 0 10px; text-align: center;">
                                {{ $areaText }}
                            </div>
                        @else
                            <div style="height: 15px; margin-bottom: 15px;"></div>
                        @endif
                    </div>
                </div>

                <?php 


			endforeach; ?>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="post-nav">
                    @if ($newsItems && $newsItems->count() > 0)
                        {{ $newsItems->links('pagination.custom') }}
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
