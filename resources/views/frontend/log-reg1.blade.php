@extends('layouts.front_custom')

@section('contents')

<style>
/* ======= General ======= */


/* Section */
section.login-signup {
    padding: 0 60px 60px 60px;
}

/* ======= Card Style ======= */
.login-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    margin-top: 30px;
    overflow: hidden; /* keep tabs inside */
}

.nav-tabs {
    width: 100%;
    display: flex;
    margin-bottom: 0;
    border-bottom: none;
}

.nav-tabs > li {
    flex: 1;
    margin: 0;
}

.nav-tabs > li > a {
    display: block;
    text-align: center;
    background: #922B21; /* Always colored */
    color: #fff;
    font-weight: 600;
    border-radius: 0;
    border: none;
    transition: all 0.3s ease;
}

/* Hover effect */
.nav-tabs > li > a:hover {
    background: #922B21 !important; /* Darker on hover */
    color: #fff !important; 
}

/* Remove active tab distinction */
.nav-tabs > li.active > a {
    background: #922B21; /* Same as normal */
    color: #fff;
}

/* ======= Form Fields ======= */
.form-group input {
    border-radius: 30px;
    padding: 12px 20px;
    height: auto;
    border: 1px solid #ddd;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    border-color: #922B21;
    box-shadow: 0 0 5px rgba(146, 43, 33, 0.4);
}

/* ======= Buttons ======= */
.btn-primary {
    background: #922B21;
    border-color: #922B21;
    border-radius: 30px;
    padding: 12px;
    font-weight: 600;
}

.btn-primary:hover {
    background: #7b241c;
    border-color: #7b241c;
}

.btn-success {
    background: #27ae60;
    border-color: #27ae60;
    border-radius: 30px;
    padding: 12px;
    font-weight: 600;
}

.btn-success:hover {
    background: #1e8449;
    border-color: #1e8449;
}

/* ======= Breadcrumb ======= */
.breadcrumb-area {
    background: #922B21;
    padding: 20px 0;
    margin-bottom: 40px;
    text-align: center;
    border-bottom: 2px solid #7b241c;
}

.breadcrumb li a { color: #fff; }
.breadcrumb li.active { color: #ffddcc; }

/* ======= Captcha ======= */
.g-recaptcha {
    margin: 15px 0;
    display: block;
}
.has-error .help-block {
    color: #e74c3c;
    font-size: 13px;
    margin-top: 5px;
}
.g-recaptcha {
    margin: 15px auto; /* Center horizontally with auto */
    display: block;
}
</style>

<!-- Login & Register Area -->
<section class="login-signup">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <div class="login-box">
                    <!-- Split Top Tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#nav-log" data-toggle="tab">{{ __('Login') }}</a></li>
                   
                    </ul>

                    <div class="tab-content" style="padding:30px;">
                        <!-- Login Form -->
                        <div class="tab-pane fade in active signin-form" id="nav-log">
                           <form class="mloginform" action="{{ route('front.login') }}" method="POST">
								@include('includes.validation.form_validation')
                                @csrf
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required>
                                </div>

                                @if($gs->is_capcha == 1)
                                    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! app('captcha')->display() !!}
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="form-group clearfix">
                                    <div class="checkbox pull-left">
                                        <label><input type="checkbox" name="remember"> {{ __('Remember Me') }}</label>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('front.forgot') }}" class="forgot-link">{{ __('Forgot Password?') }}</a>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
                            </form>
                        </div>

                        <!-- Register Form -->
                        <div class="tab-pane fade signin-form" id="nav-reg">
                           <form class="registerform" action="{{ route('front.register') }}" method="POST">
								@include('includes.validation.form_validation')
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="{{ __('Full Name') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone" placeholder="{{ __('Phone Number') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                                </div>

                                @if($gs->is_capcha == 1)
                                    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! app('captcha')->display() !!}
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
                            </form>
                        </div>
                    </div>
                </div><!-- /.login-box -->

            </div>
        </div>
    </div>
</section>


@endsection

