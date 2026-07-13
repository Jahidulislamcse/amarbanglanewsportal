@extends('layouts.front_custom')

@section('contents')

<style>
section.login-signup {
    padding: 0 15px 40px 15px;
}

.login-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    margin-top: 30px;
    overflow: hidden;
}

.nav-tabs {
    display: flex;
    border-bottom: none;
    margin-bottom: 20px;
}

.nav-tabs > li {
    flex: 1;
}

.nav-tabs > li {
    display: block;
    text-align: center;
    background: #922B21;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 0;
    padding: 12px 0;
    font-size: 15px;
}

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

.form-group.d-flex {
    display: flex;
    gap: 10px;
}

.form-group.d-flex .form-control {
    flex: 1;
}

@media (max-width: 767px) {
    .form-group.d-flex {
        flex-direction: column;
        gap: 0;
    }
}

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

.password-wrapper {
    position: relative;
    width: 100%;
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

#otpModal.modal {
    display: none;
    position: fixed;
    z-index: 2147483646;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    min-height: 100vh;
    padding: 20px;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.5);
    overflow-y: auto;
}

#otpModal.modal.is-open {
    display: flex;
}

#otpModal .modal-content {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 350px;
    max-width: 90%;
    max-height: calc(100vh - 40px);
    margin: 0 auto;
    overflow-y: auto;
    text-align: center;
    position: relative;
}

#otpModal .modal-content input {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.password-wrapper label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    color: #333;
}

.close-modal {
    position: absolute;
    right: 15px;
    top: 10px;
    cursor: pointer;
    font-size: 20px;
}

.alert-box {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    display: none;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}
</style>

<section class="login-signup">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-12">

                <div class="login-box">

                    <ul class="nav nav-tabs">
                        <li class="active">{{ __('Forgot Password') }}</li>
                    </ul>

                    <div class="tab-content" style="padding:20px;">

                        <div class="alert-box alert-success"></div>
                        <div class="alert-box alert-danger"></div>

                        <form id="forgotForm">

                            @csrf

                            <div class="form-group">
                                <input type="text"
                                       class="form-control"
                                       name="login"
                                       placeholder="Email or Phone Number"
                                       required>
                            </div>

                            <button type="button"
                                    id="sendOtpBtn"
                                    class="btn btn-primary btn-block">

                                Send OTP

                            </button>

                        </form>

                        <div class="form-group clearfix" style="margin-top:15px;">
                            <div class="pull-right">
                                <a href="{{ route('front.login.view') }}">
                                    {{ __('Login') }}
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

<!-- OTP Modal -->
<div id="otpModal" class="modal">

    <div class="modal-content">

        <span class="close-modal" id="closeModal">&times;</span>

        <h4 style="margin-bottom:20px;">
            Reset Password
        </h4>

        <form id="resetForm">

            @csrf

            <input type="hidden"
                   name="login"
                   id="reset_login">

            <input type="text"
                   name="otp"
                   id="otpInput"
                   placeholder="Enter OTP"
                   required>

            <div class="password-wrapper">

                <input type="password"
                       id="newPassword"
                       name="password"
                       placeholder="New Password"
                       required>

                <span class="toggle-password"
                      onclick="togglePassword('newPassword', 'newPasswordIcon')">

                    <i class="fa fa-eye" id="newPasswordIcon"></i>

                </span>

            </div>

            <br>

            <div class="password-wrapper">

                <input type="password"
                       id="confirmPassword"
                       name="password_confirmation"
                       placeholder="Confirm Password"
                       required>

                <span class="toggle-password"
                      onclick="togglePassword('confirmPassword', 'confirmPasswordIcon')">

                    <i class="fa fa-eye" id="confirmPasswordIcon"></i>

                </span>

            </div>

            <br>

            <button type="button"
                    class="btn btn-primary btn-block"
                    id="verifyOtpBtn">

                Reset Password

            </button>

        </form>

    </div>

</div>

<script>

function togglePassword(inputId, iconId) {

    let input = document.getElementById(inputId);
    let icon = document.getElementById(iconId);

    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");

    } else {

        input.type = "password";

        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

function showSuccess(message) {

    $('.alert-danger').hide();

    $('.alert-success')
        .html(message)
        .show();
}

function showError(message) {

    $('.alert-success').hide();

    $('.alert-danger')
        .html(message)
        .show();
}

$("#sendOtpBtn").click(function (e) {

    e.preventDefault();

    if ($("#forgotForm")[0].checkValidity()) {

        let btn = $(this);

        btn.text('Sending...');

        $.ajax({

            url: "{{ route('front.forgot.submit') }}",

            type: "POST",

            data: $("#forgotForm").serialize(),

            success: function (res) {

                btn.text('Send OTP');

                if (res.otp_sent) {

                    $('#otpModal').addClass('is-open');

                    $('#reset_login').val($('input[name=login]').val());

                    showSuccess(res.message);
                }
            },

            error: function(xhr) {

                btn.text('Send OTP');

                let msg = 'Something went wrong';

                if(xhr.responseJSON?.errors){

                    if(Array.isArray(xhr.responseJSON.errors)){

                        msg = xhr.responseJSON.errors[0];

                    } else {

                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                }

                showError(msg);
            }

        });

    } else {

        $("#forgotForm")[0].reportValidity();
    }

});


$("#verifyOtpBtn").click(function () {

    let btn = $(this);

    btn.text('Processing...');

    $.ajax({

        url: "{{ route('front.forgot.reset') }}",

        type: "POST",

        data: $("#resetForm").serialize(),

        success: function (res) {

            btn.text('Reset Password');

            $('#otpModal').removeClass('is-open');

            showSuccess(res.message);

            $('#forgotForm')[0].reset();
            $('#resetForm')[0].reset();

            setTimeout(function(){

                window.location.href = "{{ route('front.login.view') }}";

            }, 1500);
        },

        error: function(xhr) {

            btn.text('Reset Password');

            let msg = 'Something went wrong';

            if(xhr.responseJSON?.errors){

                if(Array.isArray(xhr.responseJSON.errors)){

                    msg = xhr.responseJSON.errors[0];

                } else {

                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                }
            }

            showError(msg);
        }

    });

});


$("#closeModal").click(function () {

    $("#otpModal").removeClass('is-open');

});


$(window).click(function(e){

    if($(e.target).is('#otpModal')){

        $("#otpModal").removeClass('is-open');
    }

});

</script>

@endsection
