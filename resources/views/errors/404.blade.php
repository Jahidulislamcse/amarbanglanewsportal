@extends('layouts.front_custom')

@push('css')

@endpush

@section('contents')



<section class="fourzerofour" style="padding-top:20px;padding-bottom:20px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="content">
		  <a class="mybtn1 pt-3" href="{{ route('frontend.index') }}">{{ __("Back To Home Page") }}</a>
		  <br/>
            <img src="{{ asset('assets/images/'.$gs->error_photo)}}" alt="">
		
            
          </div>
        </div>
      </div>
    </div>
  </section>


@endsection