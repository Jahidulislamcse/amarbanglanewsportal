@extends('layouts.front_custom')

@section('contents')

<style>
.page-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    padding: 25px;
    margin-top: 30px;
    margin-bottom: 30px;
}

.page-box h3 {
    margin-bottom: 20px;
    font-weight: 600;
}
</style>

<section class="dynamic">
    <div class="container">
        <div class="row">

            @if ($page->wbsite_right_column == 1)

                <div class="col-lg-12">
                    <div class="page-box">
                        
                        {!! $page->description !!}
                    </div>
                </div>

            @else

                <div class="col-lg-8">
                    <div class="page-box">
                        
                        {!! $page->description !!}
                    </div>
                </div>

                <div class="col-lg-4 aside">
                    <div class="page-box">

                        <h4 class="title mb-3">{{ __('CATEGORIES') }}</h4>

                        <ul class="categori-list">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('frontend.category', $category->slug) }}">
                                        <span>{{ $category->title }}</span>
                                        <span>
                                            ({{ $category->posts()
                                                ->where('schedule_post', 0)
                                                ->where('status', true)
                                                ->count() }})
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="aside-newsletter-widget mt-4">
                            <h4 class="title">{{ __('Newsletter') }}</h4>
                            <p class="text">{{ __('Subscribe to our newsletter to stay.') }}</p>

                            <form action="{{ route('front.subscribers.store') }}" method="POST">
                                @csrf
                                <input type="text"
                                       name="email"
                                       class="form-control mb-2"
                                       placeholder="{{ __('Enter Your Email Address') }}">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Subscribe') }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            @endif

        </div>
    </div>
</section>

@endsection
