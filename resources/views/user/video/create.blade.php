@extends('layouts.user')

@section('styles')
<link href="{{asset('assets/admin/css/tabbar.css')}}" rel="stylesheet" />
@endsection

@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Add Video News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('user.post.index') }}">{{ __('Video News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('includes.admin.form-both')
    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <form id="geniusformdata2" action="{{ route('user.video.store')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}

    <input type="hidden" name="is_pending">
    <input type="hidden" name="post_type">
    <div class="row">
        <div class="col-lg-8">
            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description">
                            <div class="body-area">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Language') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <select name="language_id" id="video_language_id">
                                            <option value="">{{__('Please Select a Language')}}</option>
                                            @foreach ($languages as $language)
                                                <option value="{{$language->id}}" {{ $default_language->id == $language->id ? 'selected' : ''}}>{{$language->language}}</option>
                                            @endforeach
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
                                                placeholder="{{ __('Title') }}" autocomplete="off" id="title">
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
                                            <input type="text" class="input-field" name="slug"
                                                placeholder="{{ __('Slug') }}" autocomplete="off" id="slug">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Keyword meta tag') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <textarea class="form-control textarea" name="meta_tag" placeholder="{{__('Keyword meta tag')}}"></textarea>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Tags') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="text" placeholder="{{__('Enter Tags')}}" class="form-control tags" id="tags" name="tags" value="">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">
                                                    {{__('Description')}} *
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 description">
                                            <textarea class="nic-edit" name="description" placeholder="{{__('Description')}}" id="description"></textarea>
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
                                        
										<div class="row" style="display: none;">
                                            <select id="videoAdd">
                                                <option value="embed_video">Embed Video</option>
                                            </select>
                                        </div>
                                        <div class="row localVideo" style="display: none;">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Embed Video') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input class="upload-video-file" type='file' name="video"/>
                                                <div style="display: none;" class='video-prev mt-4' class="pull-right">
                                                    <video height="200" width="100%" class="video-preview" controls="controls"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row embedVideo" style="display:block">
                                            <div class="col-lg-12">
                                                <textarea type="text" class="input-field textarea" name="embed_video" placeholder="{{ __('Embed Video') }}" autocomplete="off" id="embed_video"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{__('Current Preview Image')}} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="img-upload custom-image-upload">
                                                    <div id="image-preview" class="img-preview"
                                                        style="background: url({{ asset('assets/admin/images/upload.png') }});">
                                                        <label for="image-upload" class="img-label" id="image-label"><i
                                                                class="icofont-upload-alt"></i>{{__('Upload')}}</label>
                                                        <input type="file" name="image_big" class="img-upload"
                                                            id="image-upload">
                                                    </div>
                                                    <p class="text">{{__('Prefered Size: (600x600) or Square Sized Image')}}</p>
                                                </div>

                                            </div>
											
											<input type="hidden"  name="is_trending" value="0">
												<input type="hidden"  name="slider_right" value="0">
												<input type="hidden"  name="is_feature" value="0">
												<input type="hidden"  name="is_feature" value="0">
											
												<input type="hidden"  name="subcategories_id" value="0">
												<input type="hidden"  name="is_videoGallery" value="1">
												<input type="hidden"  name="is_slider" value="2">
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
											<select name="category_id" id="video_parent_id">
											   <option value="">Select category</option>
												
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

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Schedule Video News') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="checkbox" class="schedule" name="schedule_post" value="1">
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
                                                    <input id="from" class="input-field" type="text" name="schedule_post_date" autocomplete="off" placeholder="{{__('Start Date')}}" required="" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">

                                            </div>
                                            <div class="col-lg-12">
                                                <input type="submit" data-draft="1" class="btn btn-warning submit-btn1 saveAsDraft" value="Save as Drafts">
                                               
												
												<?php if(auth()->user()->is_approve==1){?>
                                                     <input type="submit" data-draft="0" class="btn btn-success submit-btn1 addPost" value="Add Video News">
												<?php }?>
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

</div>

@endsection


@section('scripts')
<script src="{{asset('assets/admin/js/user_create.js')}}"></script>
<script src="{{asset('assets/admin/js/tagify.js')}}"></script>
<script>
    $('.tags').tagify();
</script>
@endsection
