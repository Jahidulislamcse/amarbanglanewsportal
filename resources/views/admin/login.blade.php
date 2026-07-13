<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="GeniusOcean">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $gs->title ?? 'Admin Login' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/'.$gs->favicon) }}" type="image/x-icon">

    <!-- Core CSS -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/icofont.min.css') }}">
    
    <!-- Custom Login Styles -->
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet"/>
    
    <style>
        .password-wrapper { position: relative; }
        .password-wrapper .toggle-password {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 18px;
        }
        .password-wrapper .toggle-password:hover { color: #922B21; }
    </style>
</head>
<body>
    <section class="login-signup">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="login-area">
                        <div class="header-area">
                            <h4 class="title">{{ __('Login Now') }}</h4>
                            <p class="text">{{ __('Welcome back, please sign in below') }}</p>
                        </div>
                        <div class="login-form">
                            @include('includes.admin.form-login')    
                            
                            <div id="credentials-section">
                               <form id="loginform" action="{{ secure_url('admin/login') }}" method="POST">
                                    {{ csrf_field() }}

                                    <div class="form-input">
                                        <input type="email" name="email" class="User Name" placeholder="{{ __('Type Email Address') }}" autofocus autocomplete="email">
                                        <i class="icofont-user-alt-5"></i>
                                    </div>

                                    <div class="form-input password-wrapper">
                                        <input type="password" id="adminPassword" name="password" class="Password" placeholder="{{ __('Type Password') }}" required>
                                        <i class="icofont-ui-password"></i>
                                        <span class="toggle-password" onclick="toggleAdminPassword()">
                                            <i class="fa fa-eye" id="adminToggleIcon"></i>
                                        </span>
                                    </div>

                                    <div class="form-forgot-pass">
                                        <div class="left">
                                            <input type="checkbox" name="remember" id="rp" {{ old('remember') ? 'checked' : '' }}>
                                            <label for="rp">{{ __('Remember Password') }}</label>
                                        </div>
                                        <div class="right">
                                            <a href="{{ route('admin.forgot') }}">{{ __('Forgot Password?') }}</a>
                                        </div>
                                    </div>

                                    <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                                    <button class="submit-btn">{{ __('Send OTP') }}</button>
                                </form>
                            </div>

                            <div id="otp-selection-section" style="display:none; padding: 10px 0;">
                               <form id="otpselectionform" action="{{ route('admin.login.sendOtp') }}" method="POST">
                                    {{ csrf_field() }}

                                    <p style="font-weight: 500; margin-bottom: 15px; color: #4a5568; font-size: 14px;">
                                        Select where you would like to receive your verification OTP code:
                                    </p>

                                    <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px; position: relative; padding-left: 25px;">
                                        <input type="radio" name="channel" id="channel_email" value="email" checked style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; margin: 0; cursor: pointer;">
                                        <label for="channel_email" style="font-size: 14px; font-weight: 500; cursor: pointer; color: #2d3748; margin: 0; line-height: 18px;">
                                            Send to Email: <span id="masked-email" style="font-weight: 600; color: #0f766e;"></span>
                                        </label>
                                    </div>

                                    <div style="margin-bottom: 25px; display: flex; align-items: center; gap: 10px; position: relative; padding-left: 25px;">
                                        <input type="radio" name="channel" id="channel_phone" value="phone" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; margin: 0; cursor: pointer;">
                                        <label for="channel_phone" style="font-size: 14px; font-weight: 500; cursor: pointer; color: #2d3748; margin: 0; line-height: 18px;">
                                            Send to SMS Phone: <span id="masked-phone" style="font-weight: 600; color: #0f766e;"></span>
                                        </label>
                                    </div>

                                    <button class="submit-btn">{{ __('Send Verification OTP') }}</button>
                                    
                                    <div class="text-center mt-3">
                                        <a href="javascript:;" class="back-to-login-btn" style="color: #922B21; font-weight: 500; text-decoration: underline;">{{ __('Back to Login') }}</a>
                                    </div>
                                </form>
                            </div>

                            <div id="otp-section" style="display:none;">
                               <form id="otpform" action="{{ route('admin.login.verifyOtp') }}" method="POST">
                                    {{ csrf_field() }}

                                    <div class="form-input">
                                        <input type="text" name="otp" class="User Name" placeholder="{{ __('Enter 6-Digit OTP') }}" autocomplete="off" required>
                                        <i class="icofont-key"></i>
                                    </div>

                                    <button class="submit-btn">{{ __('Verify OTP & Login') }}</button>
                                    
                                    <div class="text-center mt-3">
                                        <a href="javascript:;" id="back-to-login" style="color: #922B21; font-weight: 500; text-decoration: underline;">{{ __('Change OTP Channel') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core JS -->
    <script src="{{ asset('assets/admin/js/vendors/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendors/bootstrap.min.js') }}"></script>

    <!-- Custom Login JS -->
    <script>
        function toggleAdminPassword() {
            const passwordField = document.getElementById('adminPassword');
            const toggleIcon = document.getElementById('adminToggleIcon');

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
    <script>
    $(document).ready(function(){
        $('#loginform').on('submit', function(e) {
            e.preventDefault(); // prevent normal form submission
            var form = $(this);
            $('.alert-danger').hide();
            $('.alert-success').hide();
     
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response){
                    if(response.errors){
                        let html = '';
                        $.each(response.errors, function(key, value){
                            html += '<p>'+value+'</p>';
                        });
                        $('.alert-danger').html(html).show();
                    } else if (response.otp_selection_required) {
                        $('#credentials-section').hide();
                        $('#masked-email').text(response.email);
                        $('#masked-phone').text(response.phone);
                        $('#otp-selection-section').show();
                    } else {
                        window.location.href = response; // redirect
                    }
                },
                error: function(xhr){
                    alert("Something went wrong: " + xhr.status);
                }
            });
        });

        $('#otpselectionform').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $('.alert-danger').hide();
            $('.alert-success').hide();

            var selectedChannel = $('input[name="channel"]:checked').val();
     
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response){
                    if(response.errors){
                        let html = '';
                        $.each(response.errors, function(key, value){
                            html += '<p>'+value+'</p>';
                        });
                        $('.alert-danger').html(html).show();
                    } else if (response.otp_sent) {
                        $('#otp-selection-section').hide();
                        $('#otp-section').show();
                        var channelName = selectedChannel === 'email' ? 'email address' : 'phone number';
                        $('.alert-success').show().find('p').html('OTP sent successfully to your registered ' + channelName + '.');
                    }
                },
                error: function(xhr){
                    alert("Something went wrong: " + xhr.status);
                }
            });
        });

        $('#otpform').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $('.alert-danger').hide();
            $('.alert-success').hide();
     
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response){
                    if(response.errors){
                        let html = '';
                        $.each(response.errors, function(key, value){
                            html += '<p>'+value+'</p>';
                        });
                        $('.alert-danger').html(html).show();
                    } else {
                        window.location.href = response;
                    }
                },
                error: function(xhr){
                    alert("Something went wrong: " + xhr.status);
                }
            });
        });

        $('.back-to-login-btn').on('click', function() {
            $('#otp-section').hide();
            $('#otp-selection-section').hide();
            $('#credentials-section').show();
            $('.alert-success').hide();
            $('.alert-danger').hide();
        });
        
        $('#back-to-login').on('click', function() {
            $('#otp-section').hide();
            $('#otp-selection-section').show();
            $('.alert-success').hide();
            $('.alert-danger').hide();
        });
    });
    </script>
</body>
</html>