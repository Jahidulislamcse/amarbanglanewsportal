@extends('layouts.admin')

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
    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <form id="geniusformdata2" action="{{ route('article.store')}}" method="POST" enctype="multipart/form-data">
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
									
									
                                    <div class="row">
                                        <div class="col-lg-4">
                                           <select name="division_id" id="division_id" class="form-control" data-selected="{{ old('division_id') }}">
										   
											<option value="">{{ __('বিভাগ') }}</option>
											
											</select>
                                        </div>
										
										<div class="col-lg-4">
											<select name="district_id" id="district_id" class="form-control" data-selected="{{ old('district_id') }}" >
												<option value="">{{ __('জেলা') }}</option>
											</select>
                                           
                                        </div>
										<div class="col-lg-4">
                                             <select name="thana_id" id="thana_id" class="form-control" data-selected="{{ old('thana_id') }}">
												<option value="">{{ __('উপজেলা') }}</option>
											</select>
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
                                                <div class="img-upload custom-image-upload text-center">
                                            
                                                    <!-- Image Preview -->
                                                    <img id="preview-img"
                                                         src="{{ asset('assets/admin/images/upload.png') }}"
                                                         class="img-thumbnail mb-2"
                                                         style="max-width:100%; max-height:250px;">
                                            
                                                    <!-- Upload Button -->
                                                    <div>
                                                        <label for="image-upload" class="btn btn-primary btn-sm">
                                                            <i class="icofont-upload-alt"></i> {{ __('Upload Image') }}
                                                        </label>
                                                        <input type="file" name="image_big" id="image-upload" style="display:none;">
                                                    </div>
                                            
                                                    <p class="text mt-2">{{ __('Prefered Size: (600x600) or Square Sized Image') }}</p>
                                            
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-2" id="upload-title-wrap" style="display:none;">
                                            <div class="col-lg-12">
                                                <p class="text" style="color:red;">
                                                    {{ __('Max 2 Words title for uploaded image*') }}
                                                </p>
                                        
                                                <input type="text"
                                                   id="image_title"
                                                   class="input-field"
                                                   name="image_title"
                                                   placeholder="Max 2 words">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-12">
                                                <p class="text">{{ __('Image Note: Like.. "Collected" etc.') }}</p>
                                                <input type="text" class="input-field" name="image_note" placeholder="{{ __('Add a note about this image') }}">
                                            </div>
                                        </div>
                                                                                <div class="row mt-3">
                                            <div class="col-lg-12">
                                                
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
                                                
                                                <!--<div class="left-area">-->
                                                <!--    <h4 class="heading">Select From Existing Images</h4>-->
                                                <!--</div>-->
                                        
                                                <!--<div class="row cover-scroll-container" style="max-height: 300px; overflow-y: auto;">-->
                                                <!--    @foreach($covers as $cover)-->
                                                <!--        <div class="col-4 cover-item" data-title="{{ strtolower($cover->title) }}">-->
                                                <!--            <label style="cursor:pointer;">-->
                                                <!--                <input type="radio" name="cover_image_id" value="{{ $cover->id }}" class="d-none choose-cover">-->
                                                <!--                <img src="{{ asset('assets/images/post/'.$cover->cover_image) }}"-->
                                                <!--                     class="img-thumbnail w-100 select-cover-img"-->
                                                <!--                     data-id="{{ $cover->id }}">-->
                                                <!--                <p style="font-size: 12px;">{{ \Illuminate\Support\Str::limit($cover->title, 10) }}</p>-->
                                                <!--            </label>-->
                                                <!--        </div>-->
                                                <!--    @endforeach-->
                                                <!--</div>-->
                                                
                                                <!--<div class="mt-3">-->
                                                <!--    {{ $covers->links() }}-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="cover_image_id" id="cover_image_id">
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
                                        <div class="row" id="death-count-row" style="display:none;">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading">মৃত্যুর সংখ্যা</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="number"
                                                       min="0"
                                                       class="input-field"
                                                       name="death_count"
                                                       id="death_count"
                                                       placeholder="মৃত্যুর সংখ্যা">
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
                        <div class="product-description">
                            <div class="body-area">
            
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">Post Quiz (Optional)</h4>
                                            <p class="sub-heading">You can add a quiz for this post</p>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <input type="text"
                                               class="input-field"
                                               name="quiz_question"
                                               placeholder="Enter Quiz Question">
                                    </div>
                                </div>
            
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <input type="text"
                                               class="input-field"
                                               name="option_1"
                                               placeholder="Option 1">
                                    </div>
            
                                    <div class="col-lg-6">
                                        <input type="text"
                                               class="input-field"
                                               name="option_2"
                                               placeholder="Option 2">
                                    </div>
                                </div>
            
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <input type="text"
                                               class="input-field"
                                               name="option_3"
                                               placeholder="Option 3">
                                    </div>
            
                                    <div class="col-lg-6">
                                        <input type="text"
                                               class="input-field"
                                               name="option_4"
                                               placeholder="Option 4">
                                    </div>
                                </div>
            
                                <div class="row">
                                    <div class="col-lg-12">
                                        <select name="correct_answer" class="input-field">
                                            <option value="">Select Correct Answer</option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                            <option value="4">Option 4</option>
                                        </select>
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
                                        @if(Auth::guard('admin')->user()->IsSuper() || (Auth::guard('admin')->user()->role && (strtolower(Auth::guard('admin')->user()->role->name) === 'admin' || Auth::guard('admin')->user()->sectionCheck('schedule_post'))))
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
                                        @endif

                                        <div class="row">
                                            <div class="col-lg-12">
                                                
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="submit" data-draft="1" class="btn btn-warning submit-btn1 saveAsDraft" value="Save as Drafts">
                                                <input type="submit" data-draft="0" class="btn btn-success submit-btn1 addPost" value="Save News">
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
<script src="{{asset('assets/admin/js/login.js')}}"></script>
<script src="{{asset('assets/admin/js/articleCreate.js')}}"></script>
<script src="{{asset('assets/admin/js/tagify.js')}}"></script>
<script>

 $("#title").on('keyup',function(){
        var title = $(this).val();
        var url = mainurl+'admin/add-article/slugCreate';
        $.ajax({
            type        : 'GET',
            url         : url,
            data        : {title:title},
            success     : function(data){
                            $("#slug").prop('value',data);
            }
        })
    })
	
$("#article_language_id").trigger('change');
    $('.tags').tagify();
</script>

<script>
    const fileInput = document.getElementById('image-upload');
    const previewImg = document.getElementById('preview-img');
    const uploadTitleWrap = document.getElementById('upload-title-wrap');
    const uploadTitleInput = document.querySelector("input[name='image_title']");

    // Upload new image
    fileInput.addEventListener('change', function () {
    
        if (this.files.length > 0) {
    
            uploadTitleWrap.style.display = 'block';
            uploadTitleInput.setAttribute('required','required');
    
            $("input[name='cover_image_id']").prop('checked', false);
            $('.select-cover-img').removeClass('active');
    
            const file = this.files[0];
            previewImg.src = URL.createObjectURL(file);
    
        } else {
    
            uploadTitleWrap.style.display = 'none';
            uploadTitleInput.removeAttribute('required');
            previewImg.src = "{{ asset('assets/admin/images/upload.png') }}";
    
        }
    });

    $('.choose-cover').on('change', function () {
        uploadTitleWrap.style.display = 'none';
        uploadTitleInput.removeAttribute('required'); 
        fileInput.value = null;

        const img = $(this).siblings('img').attr('src');
        imagePreview.style.backgroundImage = `url(${img})`;
    });

    $('.select-cover-img').click(function(){
    
        $('.select-cover-img').removeClass('active');
        $(this).addClass('active');
    
        $("input[name='cover_image_id']").prop('checked', false);
        $(this).siblings("input.choose-cover").prop('checked', true);
    
        uploadTitleWrap.style.display = 'none';
        uploadTitleInput.removeAttribute('required');
        fileInput.value = null;
    
        const img = $(this).attr('src');
        previewImg.src = img;
    
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
    
    function toggleDeathCount() {
    
        let categoryText = $('#article_parent_id option:selected').text().trim();
    
        if (categoryText === 'অপরাধ') {
    
            $('#death-count-row').show();
            $('#death_count').attr('required', true);
    
        } else {
    
            $('#death-count-row').hide();
            $('#death_count').removeAttr('required');
            $('#death_count').val('');
        }
    }
    
    $(document).on('change', '#article_parent_id', function () {
        toggleDeathCount();
    });
    
    // for edit/preloaded category
    $(document).ready(function () {
        toggleDeathCount();
    });
</script>


<script>
    const fileInput = document.getElementById('image-upload');
    const uploadTitleWrap = document.getElementById('upload-title-wrap');
    const uploadTitleInput = document.querySelector("input[name='image_title']");

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            uploadTitleWrap.style.display = 'block';
            uploadTitleInput.setAttribute('required', 'required'); // Make required

            $("input[name='cover_image_id']").prop('checked', false);
            $('.select-cover-img').removeClass('active');
        } else {
            uploadTitleWrap.style.display = 'none';
            uploadTitleInput.removeAttribute('required'); 
        }
    });

    $('.choose-cover').on('change', function () {
        uploadTitleWrap.style.display = 'none';
        uploadTitleInput.removeAttribute('required'); 
        fileInput.value = null;
    });

    $('.select-cover-img').click(function () {
        uploadTitleWrap.style.display = 'none';
        uploadTitleInput.removeAttribute('required'); 
        fileInput.value = null;
    });
    $('#image_title').on('input', function () {
        let words = $(this).val().trim().split(/\s+/);
    
        if (words.length > 2) {
            $(this).val(words.slice(0, 2).join(' '));
        }
    });
</script>

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
