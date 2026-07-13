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
    <div class="row mb-3">
        <div class="col-lg-12 text-right">
            <a href="{{ route('user.post.index') }}" class="btn btn-primary btn-sm">{{__('All News')}}</a>
        </div>
    </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.article.create') }}" class="add-post-area">
                   <div class="icon">
                    <i class="far fa-file-alt"></i>
                   </div>
                   <h6>
                       {{__('News')}}
                   </h6>
                   <p>{{__('An news with images')}}</p>
                </a>
            </div>

            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.audio.create') }}" class="add-post-area">
                    <div class="icon">
                        <i class="fas fa-volume-up"></i>
                    </div>
                    <h6>
                        {{__('Audio News')}}
                    </h6>
                    <p>{{__('Upload audios ')}}</p>
                </a>
            </div>

            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.video.create') }}" class="add-post-area">
                    <div class="icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <h6>
                        {{__('Video News')}}
                    </h6>
                    <p>{{__('Upload or embed videos')}}</p>
                </a>
            </div>

        </div>
</div>



@endsection
