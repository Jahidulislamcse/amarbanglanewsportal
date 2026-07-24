@extends('layouts.admin')

@section('content')
    @php
        $quiz = $data->quiz;
    @endphp

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit News') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}">{{ __('News') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @include('includes.admin.form-both')
        @php
            $categorySlug = !empty($data->category->slug) ? $data->category->slug : 'uncategorized';
            $detailsUrl = route('frontend.postBySubcategory.details', [$categorySlug, $data->slug]);
        @endphp
        <div id="approved-action-buttons" class="mt-2 mb-3" style="display: {{ $data->is_pending == 0 ? 'block' : 'none' }};">
            <a href="javascript:void(0)" class="download-postcard-btn btn btn-info" style="background:#145a32; border-color:#145a32;" data-title="{{ e($data->title) }}" data-image="{{ $data->image_big ? asset('assets/images/post/'.$data->image_big) : asset('assets/images/nopic.png') }}" data-date="{{ enToBn(date('d M Y', strtotime($data->schedule_post_date ?? $data->created_at->toDateTimeString()))) }}"> <i class="fas fa-image"></i> Download Photocard</a>
            <a href="javascript:void(0)" class="copy-post-link-btn btn btn-success ml-2" data-url="{{ $detailsUrl }}"> <i class="fas fa-copy"></i> Copy Link</a>
        </div>
        <div class="gocover"
            style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
        </div>
        <form id="geniusformdata2" action="{{ route('article.update', $data->id) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="is_pending" value="{{ $data->is_pending }}">
            <input type="hidden" name="post_type" value="{{ $data->post_type }}">
            <input type="hidden" id="article_post_id" value="{{ $data->id }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Reporter') }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="input-field" value="{{ $reporterName }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Reporter Phone') }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="input-field" value="{{ $reporterPhone }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">Reporter Area</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="input-field" value="{{ $reporterArea }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">Reporter Type</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="input-field" value="{{ $reportTypeTitle }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">Total Posts</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="input-field" value="{{ $reporterPostCount }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">Reporter Views</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" class="input-field" value="{{ $reporterViews }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Reporter Post History') }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <table class="table table-bordered table-striped text-center align-middle"
                                                    style="font-size: 11px; background: #fff; width: 100%;">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="p-2">Approved</th>
                                                            <th class="p-2">Pending</th>
                                                            <th class="p-2">Rejected</th>
                                                            <th class="p-2">Today</th>
                                                            <th class="p-2">3 Days</th>
                                                            <th class="p-2">7 Days</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-success fw-bold p-2">{{ $approvedCount }}</td>
                                                            <td class="text-warning fw-bold p-2">{{ $pendingCount }}</td>
                                                            <td class="text-danger fw-bold p-2">{{ $rejectedCount }}</td>
                                                            <td class="fw-bold p-2">{{ $todayCount }}</td>
                                                            <td class="fw-bold p-2">{{ $threeDaysCount }}</td>
                                                            <td class="fw-bold p-2">{{ $sevenDaysCount }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Language') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <select name="language_id" id="article_language_id">
                                                    <option value="1" selected>বাংলা</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Title') }} *</h4>
                                                    <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field" name="title"
                                                    placeholder="{{ __('Title') }}" id="title" autocomplete="off"
                                                    value="{{ $data->title }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Slug') }} *</h4>
                                                    <p class="sub-heading">{{ __('In English') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field" id="slug" name="slug"
                                                    placeholder="{{ __('Slug') }}" value="{{ $data->slug }}">
                                                <p id="slugCheck"></p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Keyword meta tag') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea class="input-field textarea" name="meta_tag" placeholder="{{ __('Keyword meta tag') }}">{{ $data->meta_tag }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Tags') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="{{ __('Tags') }}"
                                                    class="tags input-field" id="tags" name="tags"
                                                    value="{{ $data->tags }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Description') }} *
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 description">
                                                <textarea class="nic-edit" name="description" placeholder="{{ __('Description') }}" id="description">{{ $data->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <select name="division_id" id="division_id" class="form-control"
                                                    data-selected="{{ old('division_id', $data->division_id) }}">

                                                    <option value="">{{ __('বিভাগ') }}</option>
                                                    @foreach (is_division($data->language_id) as $division)
                                                        <option value="{{ $division->id }}">{{ $division->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-4">
                                                <select name="district_id" id="district_id" class="form-control"
                                                    data-selected="{{ old('district_id', $data->district_id) }}">
                                                    <option value="">{{ __('জেলা') }}</option>
                                                </select>

                                            </div>
                                            <div class="col-lg-4">
                                                <select name="thana_id" id="thana_id" class="form-control"
                                                    data-selected="{{ old('thana_id', $data->thana_id) }}">
                                                    <option value="">{{ __('উপজেলা') }}</option>
                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="add-product-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Current Featured Image') }} *</h4>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div id="image-preview"
                                                            class="image-preview img-upload custom-image-upload text-center">
                                                            <img id="preview-img"
                                                                src="{{ asset('assets/images/post/' . $data->image_big) }}"
                                                                class="img-thumbnail mb-2"
                                                                style="max-width:100%; max-height:250px;">
                                                            <div>
                                                                <label for="image-upload" class="btn btn-primary btn-sm">
                                                                    <i class="icofont-upload-alt"></i>
                                                                    {{ __('Upload Image') }}
                                                                </label>
                                                                <input type="file" name="image_big" id="image-upload"
                                                                    style="display:none;">
                                                            </div>
                                                            <p class="text mt-2">
                                                                {{ __('Prefered Size: (600x600) or Square Sized Image') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="cover_image_id" id="cover_image_id">
                                                <div class="row mt-3">
                                                    <div class="col-lg-12">
                                                        <div class="left-area">
                                                            <h4 class="heading" style="color: green;">Search from Existing Images</h4>
                                                        </div>

                                                        <input type="text"
                                                               id="search-cover"
                                                               class="input-field"
                                                               placeholder="Type image title...">

                                                        <div id="cover-results"
                                                             class="row mt-3 cover-scroll-container"
                                                             style="max-height:300px;overflow-y:auto;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="imageBigModal" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body text-center p-0">
                                                                <img id="modal-preview-img" src=""
                                                                    class="img-fluid" alt="Featured Image">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Image Note / Caption --}}
                                                <div class="row mt-3">
                                                    <div class="col-lg-12">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Image Note / Caption') }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <textarea class="input-field textarea" name="image_note"
                                                            placeholder="{{ __('Image note or caption...') }}"
                                                            style="height:70px;">{{ $data->image_note }}</textarea>
                                                    </div>
                                                </div>


                                                <!--<div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                            <h4 class="heading">
                                                                {{ __('Gallery Images') }} *
                                                            </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <a href="#" class="set-gallery" data-toggle="modal" data-target="#setgallery">
                                                            <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                                    </a>
                                                </div>
                                            </div>-->


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3 categoryDiv" style="display:none;">
                        <div class="col-lg-12">
                            <div class="add-product-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Category') }} *</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select name="category_id" id="article_parent_id">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Sub Category') }} *</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <select name="subcategories_id" id="subcategory_id">
                                                            <option value="">
                                                                {{ __('Please a Sub Category(if any)') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="add-product-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Add to Feature') }} *</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="custom-control custom-radio d-inline-block mr-5">
                                                            <input type="radio" class="custom-control-input"
                                                                name="is_feature" value="1" id="is_feature1"
                                                                @if ($data->is_feature == 1) checked @endif>
                                                            <label for="is_feature1"
                                                                class="custom-control-label">{{ __('Yes') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio d-inline-block mr-5">
                                                            <input type="radio" class="custom-control-input"
                                                                name="is_feature" value="0" id="is_feature2"
                                                                @if ($data->is_feature == 0) checked @endif>
                                                            <label for="is_feature2"
                                                                class="custom-control-label">{{ __('No') }}</label>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Add to Breaking News') }} *</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="custom-control custom-radio d-inline-block mr-5">
                                                            <input type="hidden" name="is_slider" value="0">
                                                            <input type="hidden" name="slider_right" value="0">
                                                            <input type="radio" class="custom-control-input"
                                                                name="is_trending" value="1" id="is_trending1"
                                                                @if ($data->is_trending == 1) checked @endif>
                                                            <label for="is_trending1"
                                                                class="custom-control-label">{{ __('Yes') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio d-inline-block mr-5">
                                                            <input type="radio" class="custom-control-input"
                                                                name="is_trending" value="0" id="is_trending2"
                                                                @if ($data->is_trending == 0) checked @endif>
                                                            <label for="is_trending2"
                                                                class="custom-control-label">{{ __('No') }}</label>
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

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="add-product-content">
                                <div class="product-description">
                                    <div class="body-area">

                                        <h4 class="heading">Post Quiz (Optional)</h4>

                                        <input type="text" name="quiz_question" class="input-field"
                                            placeholder="Question" value="{{ $quiz->question ?? '' }}">

                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <input type="text" name="option_1" class="input-field"
                                                    value="{{ $quiz->option_1 ?? '' }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" name="option_2" class="input-field"
                                                    value="{{ $quiz->option_2 ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <input type="text" name="option_3" class="input-field"
                                                    value="{{ $quiz->option_3 ?? '' }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" name="option_4" class="input-field"
                                                    value="{{ $quiz->option_4 ?? '' }}">
                                            </div>
                                        </div>

                                        <select name="correct_answer" class="input-field mt-2">
                                            <option value="">Correct Answer</option>
                                            <option value="1"
                                                {{ ($quiz->correct_answer ?? '') == 1 ? 'selected' : '' }}>Option 1
                                            </option>
                                            <option value="2"
                                                {{ ($quiz->correct_answer ?? '') == 2 ? 'selected' : '' }}>Option 2
                                            </option>
                                            <option value="3"
                                                {{ ($quiz->correct_answer ?? '') == 3 ? 'selected' : '' }}>Option 3
                                            </option>
                                            <option value="4"
                                                {{ ($quiz->correct_answer ?? '') == 4 ? 'selected' : '' }}>Option 4
                                            </option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="add-product-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                                @if (Auth::guard('admin')->user()->IsSuper() ||
                                                        (Auth::guard('admin')->user()->role &&
                                                            (strtolower(Auth::guard('admin')->user()->role->name) === 'admin' ||
                                                                Auth::guard('admin')->user()->sectionCheck('schedule_post'))))
                                                    <div class="row"
                                                        @if ($data->schedule_post == 0) ? style="display:none;" : style="display:block;" @endif>
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Schedule News') }} *</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <input type="checkbox" class="schedule" name="schedule_post"
                                                                value="{{ $data->schedule_post }}"
                                                                {{ $data->schedule_post == 1 ? 'checked' : '' }} disabled>
                                                        </div>
                                                    </div>

                                                    <div class="row datepick" style="display:none;">
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading"></h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <input id="from" class="input-field" type="text"
                                                                    name="schedule_post_date" autocomplete="off"
                                                                    placeholder="{{ __('Start Date') }}" required=""
                                                                    value="{{ $data->schedule_post_date }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="row">
                                                    <div class="row">

                                                        @php
                                                            $adminUser = Auth::guard('admin')->user();
                                                        @endphp

                                                        @if (($adminUser && $adminUser->sectionCheck('news_approve')) || (Auth::check() && Auth::user()->id == 1))
                                                            <div class="col-lg-12">
                                                                <div class="left-area">
                                                                    <h4 class="heading">{{ __('Approve News') }} *</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">

                                                                <div
                                                                    class="custom-control custom-radio d-inline-block mr-5">
                                                                    <input type="radio" class="custom-control-input"
                                                                        name="is_pending" value="1" id="is_pending1"
                                                                        @if ($data->is_pending == 0) checked @endif>
                                                                    <label for="is_pending1"
                                                                        class="custom-control-label">{{ __('Yes') }}</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-radio d-inline-block mr-5">
                                                                    <input type="radio" class="custom-control-input"
                                                                        name="is_pending" value="0" id="is_pending2"
                                                                        @if ($data->is_pending == 1) checked @endif>
                                                                    <label for="is_pending2"
                                                                        class="custom-control-label">{{ __('No') }}</label>
                                                                </div>
                                                                <div
                                                                    class="custom-control custom-radio d-inline-block mr-5">
                                                                    <input type="radio" class="custom-control-input"
                                                                        name="is_pending" value="2" id="is_pending3"
                                                                        @if ($data->is_pending == 2) checked @endif>
                                                                    <label for="is_pending3"
                                                                        class="custom-control-label">{{ __('Rejected') }}</label>
                                                                </div>

                                                                <div id="reject_reason_container"
                                                                    style="margin-top: 15px; display: {{ $data->is_pending == 2 ? 'block' : 'none' }};">
                                                                    <label for="reject_reason" class="heading"
                                                                        style="font-weight: bold; margin-bottom: 5px;">{{ __('Rejection Reason / Comment') }}</label>
                                                                    <select id="reject_reason_presets"
                                                                        class="form-control mb-2"
                                                                        style="font-size: 14px; background: #fff; border: 1px solid #ced4da; height: 38px; color: #495057;">
                                                                        <option value="">--
                                                                            {{ __('Select a Pre-written Rejection Reason') }}
                                                                            --</option>
                                                                        <option value="promo"
                                                                            data-text="সম্মানিত মহোদয়/মহোদয়া,

আপনার প্রেরিত সংবাদটি আমাদের নীতিমালা অনুযায়ী *বিজ্ঞাপন/প্রচারমূলক (Promotional)* ক্যাটাগরির অন্তর্ভুক্ত। এ ধরনের সংবাদ প্রকাশের ক্ষেত্রে প্রতিষ্ঠানের নির্ধারিত নীতিমালা অনুযায়ী ন্যূনতম *৫০০ (পাঁচশত) টাকা* বিজ্ঞাপন ফি প্রযোজ্য।

বিজ্ঞাপন ফি পরিশোধের পর আপনার সংবাদটি প্রয়োজনীয় পর্যালোচনা শেষে প্রকাশের জন্য প্রক্রিয়াকরণ করা হবে।

ধন্যবাদ।">
                                                                            বিজ্ঞাপন/প্রচারমূলক </option>
                                                                        <option value="area"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদটি আপনার নির্ধারিত কর্মএলাকার বাইরে হওয়ায় আমাদের নীতিমালা অনুযায়ী এটি প্রকাশ করা সম্ভব হচ্ছে না।

অনুগ্রহ করে ভবিষ্যতে শুধুমাত্র আপনার দায়িত্বপ্রাপ্ত এলাকা থেকে সংবাদ প্রেরণ করুন। নির্ধারিত কর্মএলাকার বাইরের সংবাদ সংশ্লিষ্ট এলাকার প্রতিনিধির মাধ্যমে প্রকাশ করা হয়।

আপনার সহযোগিতার জন্য ধন্যবাদ।">
                                                                            কর্মএলাকার বাইরে </option>
                                                                        <option value="published"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদটি আমাদের পোর্টালে ইতোমধ্যে প্রকাশিত হয়েছে। একই সংবাদ একাধিকবার প্রকাশ করা আমাদের নীতিমালার পরিপন্থী হওয়ায় এটি অনুমোদন করা সম্ভব হচ্ছে না।

অনুগ্রহ করে নতুন ও স্বতন্ত্র সংবাদ সংগ্রহ করে জমা দিন। আপনার অন্যান্য সংবাদ আমরা যথাযথভাবে পর্যালোচনা করে প্রকাশের জন্য বিবেচনা করব।

ধন্যবাদ।">
                                                                            ইতোমধ্যে প্রকাশিত সংবাদ (Already Published)
                                                                        </option>
                                                                        <option value="missing_info"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদে ঘটনাস্থল, সময় ও তারিখ স্পষ্টভাবে উল্লেখ করা হয়নি। ফলে সংবাদের তথ্যপূর্ণতা ও নির্ভুলতা নিশ্চিত করা সম্ভব হচ্ছে না। এ কারণে সংবাদটি এই মুহূর্তে প্রকাশ করা যাচ্ছে না।

অনুগ্রহ করে সংবাদে ঘটনার স্থান, সময়, তারিখ এবং প্রয়োজনীয় তথ্য সম্পূর্ণভাবে উল্লেখ করে পুনরায় জমা দিন। তথ্য যাচাই সাপেক্ষে সংবাদটি প্রকাশের জন্য বিবেচনা করা হবে।

ধন্যবাদ।">
                                                                            ঘটনাস্থল, সময় ও তারিখের অনুপস্থিতি (Missing
                                                                            Location/Time/Date)</option>
                                                                        <option value="insufficient"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদে প্রয়োজনীয় তথ্য ও বিস্তারিত বর্ণনা পর্যাপ্ত নয়। ফলে সংবাদের সত্যতা, প্রেক্ষাপট এবং পূর্ণাঙ্গতা যাচাই করা সম্ভব হচ্ছে না। এ কারণে সংবাদটি এই মুহূর্তে অনুমোদন করা যাচ্ছে না।

অনুগ্রহ করে ঘটনার পূর্ণাঙ্গ বিবরণ, স্থান, সময়, তারিখ, সংশ্লিষ্ট ব্যক্তি বা প্রতিষ্ঠানের তথ্য এবং প্রয়োজনীয় প্রাসঙ্গিক তথ্য সংযুক্ত করে পুনরায় সংবাদটি জমা দিন। তথ্য সম্পূর্ণ থাকলে সংবাদটি দ্রুত পর্যালোচনা করে প্রকাশের জন্য বিবেচনা করা হবে।

ধন্যবাদ।">
                                                                            অপর্যাপ্ত তথ্য ও বিবরণ (Insufficient
                                                                            Information)</option>
                                                                        <option value="verification"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদটিতে এমন কিছু তথ্য রয়েছে, যা যাচাই-বাছাই ছাড়া প্রকাশ করা সমীচীন নয়। সঠিক ও নির্ভুল সংবাদ প্রকাশের স্বার্থে আমরা বিষয়টি যাচাই করছি।

যাচাই-বাছাই সম্পন্ন না হওয়া পর্যন্ত সংবাদটি অনুমোদন ও প্রকাশ করা সম্ভব হচ্ছে না। তথ্যের সত্যতা নিশ্চিত হওয়ার পর আমাদের সম্পাদকীয় নীতিমালা অনুযায়ী প্রয়োজনীয় ব্যবস্থা গ্রহণ করা হবে।

আপনার ধৈর্য ও সহযোগিতার জন্য ধন্যবাদ।">
                                                                            তথ্য যাচাই-বাছাইধীন (Under Verification)
                                                                        </option>
                                                                        <option value="invalid_img"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদটির সঙ্গে সংযুক্ত ছবিটি পর্যাপ্ত পরিষ্কার (Clear) নয়। এছাড়া ছবির ওপর কোনো ধরনের লেখা, লোগো, ওয়াটারমার্ক বা গ্রাফিক্স ব্যবহার করা আমাদের সম্পাদকীয় নীতিমালার পরিপন্থী।

অনুগ্রহ করে উচ্চমানের, পরিষ্কার এবং সম্পাদনাবিহীন (Original) ছবি সংযুক্ত করে পুনরায় সংবাদটি জমা দিন। ছবি স্পষ্ট ও নীতিমালা অনুযায়ী হলে সংবাদটি পর্যালোচনা করে প্রকাশের জন্য বিবেচনা করা হবে।

আপনার সহযোগিতার জন্য ধন্যবাদ।">
                                                                            অস্পষ্ট/নীতিমালা পরিপন্থী ছবি (Blurry/Policy
                                                                            Violating Image)</option>
                                                                        <option value="outdated"
                                                                            data-text="সম্মানিত প্রতিনিধি,

আপনার প্রেরিত সংবাদটি পর্যালোচনা করে দেখা গেছে, এটি দীর্ঘদিন আগের বা পূর্বে ঘটে যাওয়া ঘটনার ওপর ভিত্তি করে তৈরি। আমাদের সম্পাদকীয় নীতিমালা অনুযায়ী পুরোনো বা অপ্রাসঙ্গিক সংবাদ বর্তমানে প্রকাশ করা হয় না।

এ কারণে সংবাদটি এই মুহূর্তে অনুমোদন করা সম্ভব হচ্ছে না।

অনুগ্রহ করে সাম্প্রতিক, তথ্যবহুল এবং নতুন ঘটনা সম্পর্কে সংবাদ সংগ্রহ করে পাঠান। নতুন ও সময়োপযোগী সংবাদ অগ্রাধিকার ভিত্তিতে পর্যালোচনা করে প্রকাশের জন্য বিবেচনা করা হবে।

আপনার সহযোগিতার জন্য ধন্যবাদ।">
                                                                            পুরোনো বা অপ্রাসঙ্গিক সংবাদ (Old/Outdated News)
                                                                        </option>
                                                                        <option value="low_views"
                                                                            data-text="প্রিয় প্রতিনিধি,

আপনার সর্বশেষ প্রকাশিত সংবাদের পাঠকসংখ্যা (ভিউ) আমাদের নির্ধারিত মানদণ্ডের তুলনায় কম হওয়ায় এই মুহূর্তে নতুন সংবাদ অনুমোদন করা সম্ভব হচ্ছে না।

অনুগ্রহ করে প্রথমে আপনার পূর্বে প্রকাশিত সংবাদগুলো বিভিন্ন সামাজিক যোগাযোগমাধ্যমে শেয়ার করে পাঠকসংখ্যা বৃদ্ধি করুন। আপনার সংবাদগুলোর ভিউ ও পাঠকের সম্পৃক্ততা সন্তোষজনক পর্যায়ে পৌঁছালে পরবর্তী সংবাদ দ্রুত পর্যালোচনা করে অনুমোদনের জন্য বিবেচনা করা হবে।

আপনার সহযোগিতা ও সক্রিয় অংশগ্রহণের জন্য ধন্যবাদ।">
                                                                            কম ভিউ/পাঠক সংখ্যা (Low Views/Engagement)
                                                                        </option>
                                                                        <option value="custom">-- হাতে লিখুন / Custom Text
                                                                            (Type below) --</option>
                                                                    </select>

                                                                    <textarea name="reject_reason" id="reject_reason" class="form-control" rows="5"
                                                                        placeholder="Enter the reason why this post was rejected...">{{ $data->reject_reason }}</textarea>
                                                                </div>

                                                                <script>
                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                        const isPendingRadios = document.querySelectorAll('input[name="is_pending"]');
                                                                        const rejectReasonContainer = document.getElementById('reject_reason_container');
                                                                        const rejectReasonSelect = document.getElementById('reject_reason_presets');
                                                                        const rejectReasonTextarea = document.getElementById('reject_reason');

                                                                        // Handle radio changes
                                                                        isPendingRadios.forEach(radio => {
                                                                            radio.addEventListener('change', function() {
                                                                                if (this.value == '2') {
                                                                                    rejectReasonContainer.style.display = 'block';
                                                                                } else {
                                                                                    rejectReasonContainer.style.display = 'none';
                                                                                }
                                                                            });
                                                                        });

                                                                        // Handle preset select changes
                                                                        rejectReasonSelect.addEventListener('change', function() {
                                                                            const selectedOption = this.options[this.selectedIndex];
                                                                            const text = selectedOption.getAttribute('data-text');

                                                                            if (text) {
                                                                                rejectReasonTextarea.value = text;
                                                                            } else if (this.value === 'custom') {
                                                                                rejectReasonTextarea.value = '';
                                                                                rejectReasonTextarea.focus();
                                                                            }
                                                                        });

                                                                        // Prevent submission without a rejection reason
                                                                        $(document).on('click', '.submit-btn1', function(e) {
                                                                            const isPendingRejected = document.getElementById('is_pending3').checked;
                                                                            const reasonVal = rejectReasonTextarea.value.trim();

                                                                            if (isPendingRejected && !reasonVal) {
                                                                                e.preventDefault();
                                                                                e.stopImmediatePropagation();

                                                                                // Hide loading screen and enable submit button
                                                                                $('.gocover').hide();
                                                                                $('button.addProductSubmit-btn').prop('disabled', false);

                                                                                // Display error alert
                                                                                $('.alert-success').hide();
                                                                                $('.alert-danger').show();
                                                                                $('.alert-danger ul').html(
                                                                                    '<li>অনুগ্রহ করে সংবাদটি প্রত্যাখ্যান করার সঠিক কারণটি নির্বাচন বা টাইপ করুন (Please enter a rejection reason).</li>'
                                                                                );

                                                                                // Scroll back to top
                                                                                $('body, html').animate({
                                                                                    scrollTop: $('html').offset().top
                                                                                }, 'slow');

                                                                                return false;
                                                                            }
                                                                        });
                                                                    });
                                                                </script>

                                                            </div>
                                                        @endif

                                                    </div>
                                                    <div class="col-lg-12">
                                                        <input type="submit" data-draft="0"
                                                            class="btn btn-success submit-btn1" value="Update News">
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
        </form>
    </div>

    <div class="modal fade-scale" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="top-area">
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <div class="upload-img-btn">
                                    <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="post_id" name="post_id"
                                            value="{{ $data->id }}">
                                        <input type="file" name="gallery[]" class="hidden"
                                            id="article_upload_gallery_edit" accept="image/*" multiple>
                                        <label id="article_gallery_edit"><i
                                                class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images.') }}</small> )
                            </div>
                        </div>
                    </div>
                    <div class="gallery-images">
                        <div class="selected-image">
                            <div class="row">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}

    <div class="modal fade-scale" id="post-approve" tabindex="-1" role="dialog" aria-labelledby="modal1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Post Approve') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">
                        {{ __('You are about to approve this Post') }}.
                    </p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger btn-ok">{{ __('Approve') }}</a>
                </div>

            </div>
        </div>
    </div>

    {{-- DELETE MODAL ENDS --}}

@php
    $postcardLogoSrc = asset('assets/amarbangla.png');
@endphp

<!-- Postcard Wrapper Template -->
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
    <script src="{{ asset('assets/admin/js/login.js') }}"></script>
    <script src="{{ asset('assets/admin/js/articleEdit.js') }}"></script>
    <script src="{{ asset('assets/admin/js/image_gallary.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tagify.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        $("#title").on('keyup', function() {
            var title = $(this).val();
            var url = mainurl + 'admin/add-article/slugCreate';
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    title: title
                },
                success: function(data) {
                    $("#slug").prop('value', data);
                }
            })
        })


        $('.tags').tagify();
    </script>
    <script>
        $('.image-preview').on('click', function(e) {
            if ($(e.target).is('label') || $(e.target).is('input')) return;

            const src = $(this).find('#preview-img').attr('src');
            $('#modal-preview-img').attr('src', src);
            $('#imageBigModal').modal('show');
        });

        // --- Live preview for uploaded image ---
        const fileInput = document.getElementById('image-upload');
        const previewImg = document.getElementById('preview-img');

        fileInput.addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                $('#cover_image_id').val('');
            }
        });

        $('#search-cover').on('keyup', function () {
            let keyword = $(this).val();

            if(keyword.length < 2){
                $('#cover-results').html('');
                return;
            }

            $.ajax({
                url: "{{ route('article.searchCoverImages') }}",
                type: "GET",
                data: {
                    search: keyword
                },
                success: function(response){
                    let html = '';
                    response.forEach(function(item){
                        html += `
                            <div class="col-4 mb-2">
                                <img src="/assets/images/post/${item.cover_image}"
                                     class="img-thumbnail w-100 search-cover-image"
                                     data-id="${item.id}"
                                     data-image="/assets/images/post/${item.cover_image}"
                                     style="cursor:pointer;">
                            </div>
                        `;
                    });
                    $('#cover-results').html(html);
                }
            });
        });

        $(document).on('click', '.search-cover-image', function(){
            $('.search-cover-image').removeClass('active');
            $(this).addClass('active');
            $('#cover_image_id').val($(this).data('id'));
            $('#preview-img').attr('src', $(this).data('image'));
            $('#image-upload').val('');
        });

        function colorSecondVisualLine(titleEl) {
            const text = titleEl.textContent.trim();
            const originalHTML = titleEl.innerHTML;
            const originalDisplay = titleEl.style.display;
            const originalLineClamp = titleEl.style.webkitLineClamp;
            const originalOverflow = titleEl.style.overflow;

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

        function triggerPostcardDownload(title, image, date) {
            $('#newsImage').attr('src', image);
            $('#newsTitle').text(title);
            $('#newsDate').text(date);

            setTimeout(() => {
                const postcard = document.querySelector('#news-postcard-wrapper .postcard');
                if (!postcard) return;

                let restoreTitle = null;
                const titleEl = document.getElementById('newsTitle');
                if (titleEl) {
                    restoreTitle = colorSecondVisualLine(titleEl);
                }

                html2canvas(postcard, {
                    useCORS: true,
                    scale: 2,
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
                }).finally(() => {
                    if (restoreTitle) {
                        restoreTitle();
                    }
                });
            }, 400);
        }

        function triggerCopyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                if (typeof $.notify === 'function') {
                    $.notify("সংবাদের লিংকটি কপি করা হয়েছে! (Link Copied)", "success");
                }
            }).catch(err => {
                console.error('Copy failed:', err);
            });
        }

        $(document).on('click', '.download-postcard-btn', function() {
            const btn = $(this);
            const title = $('#title').val() || btn.data('title');
            const image = $('#preview-img').attr('src') || btn.data('image');
            const date = btn.data('date');

            const originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            btn.css('pointer-events', 'none');

            triggerPostcardDownload(title, image, date);

            setTimeout(() => {
                btn.html(originalText);
                btn.css('pointer-events', 'auto');
            }, 1500);
        });

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

        // Show/hide action buttons at top below success message upon successful post update
        $(document).ajaxSuccess(function(event, xhr, settings) {
            const actionUrl = $("#geniusformdata2").attr('action');
            if (actionUrl && settings.url && (settings.url === actionUrl || settings.url.indexOf('/article/update/') !== -1) && settings.type === "POST") {
                const response = xhr.responseJSON;
                if (response && !response.errors) {
                    const isApproved = $('#is_pending1').is(':checked');
                    if (isApproved) {
                        $('.download-postcard-btn').data('title', $('#title').val());
                        $('.download-postcard-btn').data('image', $('#preview-img').attr('src'));
                        $('#approved-action-buttons').css('display', 'block');
                    } else {
                        $('#approved-action-buttons').css('display', 'none');
                    }
                }
            }
        });
    </script>
@endsection
