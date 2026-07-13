@extends('layouts.front_custom')

@section('contents')

<style>
/* ======= General Section ======= */
section.login-signup {
    padding: 0 15px 40px 15px;
}

/* ======= Card Container ======= */
.login-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    margin-top: 30px;
    overflow: hidden;
}

/* ======= Tabs ======= */
.nav-tabs {
    display: flex;
    border-bottom: none;
    margin-bottom: 20px;
}
.nav-tabs > li {
    flex: 1;
    display: block;
    text-align: center;
    background: #922B21;
    color: #fff;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    padding: 12px 0;
    font-size: 15px;
}

/* ======= Form Inputs ======= */
.form-control {
    border-radius: 8px;
    padding: 12px 15px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: none;
    height: 44px;
    background-color: #fff;
}

.form-control:focus {
    border-color: #922B21;
    box-shadow: 0 0 6px rgba(146, 43, 33, 0.4);
    outline: none;
}

/* Password Toggle */
.password-wrapper {
    position: relative;
}

.password-wrapper .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #777;
    font-size: 18px;
}

.password-wrapper .toggle-password:hover {
    color: #922B21;
}

/* ======= Two Inputs or Selects Side-by-Side ======= */
.form-group.d-flex {
    display: flex;
    gap: 10px;
}
.form-group.d-flex .form-control {
    flex: 1;
}

/* Stack vertically on mobile */
@media (max-width: 767px) {
    .form-group.d-flex {
        flex-direction: column;
        gap: 0;
    }
}

/* ======= Buttons ======= */
.btn-primary {
    background: #922B21;
    border-color: #922B21;
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    background: #7b241c;
    border-color: #7b241c;
}

/* ======= Captcha ======= */
.g-recaptcha {
    margin: 15px auto;
    display: block;
}
</style>

<section class="login-signup">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-12">
					
                <div class="login-box">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active">{{ __('Login') }}</li>
                    </ul>

                    <div class="tab-content" style="padding:20px;">
                        <div class="tab-pane fade in active signin-form" id="nav-log">
                           <form class="mloginform" action="{{ route('front.login') }}" method="POST">
								@include('includes.validation.form_validation')
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" placeholder="{{ __('Email or Phone') }}" required>

                                </div>
                                
                                <!-- Password Field with Show/Hide -->
                                <div class="form-group password-wrapper">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required>
                                    <span class="toggle-password" onclick="togglePassword()">
                                        <i class="fa fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div>

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
                    </div>
                </div><!-- /.login-box -->

            </div>
        </div>
    </div>
</section>



<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}
</script>

@endsection
