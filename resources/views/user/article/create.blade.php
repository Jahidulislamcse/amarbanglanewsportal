@extends('layouts.user')

@section('content')

<style>
.select-cover-img {
    border: 3px solid transparent;
}
.select-cover-img.active {
    border-color: #28a745;
}
.cover-scroll-container {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 5px;
}
</style>

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Add News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}">{{ __('News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('includes.admin.form-both')
    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <form id="geniusformdata2" action="{{ route('user.article.store')}}" method="POST" enctype="multipart/form-data">
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
                                                    placeholder="{{ __('Title') }}" id="title" autocomplete="off">
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
                                                    placeholder="{{ __('Slug') }}" value="">
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
                                                <textarea class="input-field textarea" name="meta_tag" placeholder="{{__('Keyword meta tag')}}"></textarea>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Tags') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="{{__('Tags')}}" class="tags input-field" id="tags" name="tags" value="">
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
                                        <br>
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
                                                    <h4 class="heading">{{__('Current Featured Image')}} </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="img-upload custom-image-upload">
                                                    <div class="img-upload custom-image-upload text-center">

                                                        <img id="preview-img"
                                                             src="{{ asset('assets/admin/images/upload.png') }}"
                                                             class="img-thumbnail mb-2"
                                                             style="max-width:100%; max-height:250px; object-fit:contain;">
                                                    
                                                        <div>
                                                            <label for="image-upload" class="btn btn-primary btn-sm">
                                                                <i class="icofont-upload-alt"></i> {{ __('Upload Image') }}
                                                            </label>
                                                            <input type="file" name="image_big" id="image-upload" style="display:none;">
                                                        </div>
                                                    
                                                    </div>
                                                        <p class="text">{{__('Prefered Size: (600x600) or Square Sized Image')}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="row mt-2">
                                            <div class="col-lg-12">
                                                <p class="text">{{ __('Image Note: Like.. "Collected" etc.') }}</p>
                                                <input type="text" class="input-field" name="image_note" placeholder="{{ __('Add a note about this image') }}">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 categoryDiv" style="display:none;">
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
                                                    <option value="">{{__('Please add a subcategory(if any)')}}</option>
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
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Add to Feature') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                            <div class="custom-control custom-radio d-inline-block mr-5">
                                                <input type="radio" class="custom-control-input" id="is_feature1"  name="is_feature" value="1">
                                                <label class="custom-control-label" for="is_feature1">{{__('Yes')}}</label>
                                            </div>   
                                            <div class="custom-control custom-radio d-inline-block">
                                                <input type="radio" class="custom-control-input" id="is_feature2"  name="is_feature" value="0" checked>
                                                <label class="custom-control-label" for="is_feature2">{{__('No')}}</label>
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
											    <input type="hidden"  name="is_slider" value="0">
												<input type="hidden"  name="slider_right" value="0">
                                                <div class="custom-control custom-radio d-inline-block mr-5">
                                                    <input type="radio" class="custom-control-input" id="is_trending" name="is_trending" value="1">
                                                    <label class="custom-control-label" for="is_trending">{{__('Yes')}}</label>
                                                </div>  

                                                <div class="custom-control custom-radio d-inline-block mr-5">
                                                    <input type="radio" class="custom-control-input" id="is_trending2" name="is_trending" value="0" checked>
                                                    <label class="custom-control-label" for="is_trending2">{{__('No')}}</label>
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">
                                        <div class="body-area">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Schedule News') }} *</h4>
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
                                                    <input type="submit" data-draft="0" class="btn btn-success submit-btn1 addPost" value="Save News">
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

<div class="modal fade-scale" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
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
                            <label id="article_gallery"><i class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> {{ __('Done') }}</a>
                    </div>
                    <div class="col-sm-12 text-center">( <small>{{ __('You can upload multiple Images.') }}</small> )</div>
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
@endsection

@section('scripts')
<script src="{{ asset('assets/admin/js/user_create.js') }}"></script>
<script src="{{ asset('assets/admin/js/tagify.js') }}"></script>

<script>
    $("#article_language_id").trigger('change');
    $('.tags').tagify();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const fileInput = document.getElementById('image-upload');
        const previewImg = document.getElementById('preview-img');

        if (fileInput && previewImg) {

            fileInput.addEventListener('change', function () {

                if (this.files && this.files[0]) {

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                    };

                    reader.readAsDataURL(this.files[0]);

                } else {

                    previewImg.src = "{{ asset('assets/admin/images/upload.png') }}";

                }

            });

        }

    });
</script>

@endsection