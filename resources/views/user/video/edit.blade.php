@extends('layouts.user')

@section('styles')
<link href="{{asset('assets/admin/css/tabbar.css')}}" rel="stylesheet" />
@endsection

@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Edit Video News') }}</h4>
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
    <form id="geniusformdata2" action="{{ route('user.video.update',$data->id)}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}

        <input type="hidden" id="article_post_id" value="{{ $data->id}}">
        <input type="hidden" name="is_pending" value="{{ $data->is_pending}}">
        <input type="hidden" name="post_type" value="{{ $data->post_type}}">
        <input type="hidden" id="id" value="{{ $data->id}}">

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
                                        <select name="language_id" >
                                            @foreach ($languages as $language)
                                                <option value="{{$language->id}}" {{ $language->id == $data->language_id ? 'selected' : ''}}>{{$language->language}}</option>
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
                                        placeholder="{{ __('Title') }}" autocomplete="off" id="title" value="{{ $data->title }}">
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
                                                placeholder="{{ __('Slug') }}" autocomplete="off" id="slug" value="{{ $data->slug }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Keyword meta tag') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <textarea class="form-control text-area" name="meta_tag" placeholder="{{ __('Keyword meta tag') }}">{{ $data->meta_tag }}</textarea>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Tags') }} *</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="text" placeholder="{{__('Enter Tags')}}" class="tags" id="tags" name="tags" value="{{$data->tags}}">
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
                                            <textarea class="nic-edit" name="description" placeholder="{{__('Description')}}" id="description">{{ $data->description }}</textarea>
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
                                            <select id="videoEdit">
                                                <option value="local_video" {{$data->video != NULL ? 'selected':'' }}>Local Video</option>
                                                <option value="embed_video" {{$data->embed_video != NULL ? 'selected':''}}>Embed Video</option>
                                            </select>
                                        </div>

                                        <div class="row localVideo" style="display: none;">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Video') }} *</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <textarea type="text" class="input-field textarea" name="embed_video" placeholder="{{ __('Embed Video') }}" autocomplete="off" id="embed_video">{{$data->embed_video}}</textarea>
                                            </div>
                                        </div>

                                        {{-- @if ($data->embed_video !=NULL)
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <textarea type="text" class="input-field textarea" name="embed_video" placeholder="{{ __('Embed Video') }}" autocomplete="off" id="embed_video">{{$data->embed_video}}</textarea>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($data->video !=NULL)

                                        @endif --}}
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12">
                                              <div class="left-area">
                                                  <h4 class="heading">{{__('Featured Image')}} *</h4>
                                              </div>
                                            </div>
                                            <div class="col-lg-12">
                                              <div class="img-upload custom-image-upload">
                                                  <div id="image-preview" class="img-preview" style="background: url({{ $data->image_big ? asset('assets/images/post/'.$data->image_big) : asset('assets/admin/images/upload.png') }});">
                                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{__('Upload')}}</label>
                                                      <input type="file" name="image_big" class="img-upload" id="image-upload">
                                                  </div>
                                                    <p class="text">{{__('Prefered Size: (600x600) or Square Sized Image')}}</p>
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
											<select name="category_id">
											   <option value="">Select category</option>
												@foreach (video_category($data->language_id) as $cat_id => $video_cat)
													<option value="{{ $cat_id }}" {{$cat_id == $data->category_id ? 'selected':''}}>{{ $video_cat }}</option>
												@endforeach
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
                                        <div class="row" @if($data->schedule_post == 0) ? style="display:none;" : style="display:block;" @endif>
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Schedule Video News') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                            <input type="checkbox" class="schedule" name="schedule_post" value="{{ $data->schedule_post}}" {{$data->schedule_post ==1 ? 'checked':''}} disabled>
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
                                                    <input id="from" class="input-field" type="text" name="schedule_post_date" autocomplete="off" placeholder="{{__('Start Date')}}" required="" value="{{ $data->schedule_post_date}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">

                                            </div>
                                            <div class="col-lg-12">
                                                <input type="submit" data-draft="0" class="btn btn-success submit-btn1" value="Update Video News">
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
<script src="{{asset('assets/admin/js/user_edit.js')}}"></script>
<script src="{{asset('assets/admin/js/tagify.js')}}"></script>
<script>
    $('.tags').tagify();
</script>
@endsection
