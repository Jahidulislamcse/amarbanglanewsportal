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
}
.nav-tabs > li {
    display: block;
    text-align: center;
    background: #922B21;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 0;
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

/* ======= Password Toggle ======= */
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

.modal {
    display: none;
    position: fixed;
    z-index: 99999; 
    margin-top:250px;
    width: 100%; height: 100%;
}
.modal-content {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 350px;
    max-width: 90%;
    margin: 10% auto;
    text-align: center;
    position: relative;
    z-index: 100000;
}
.modal-content input {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.password-wrapper label{
    display:block;
    margin-bottom:6px;
    font-size:14px;
    color:#333;
}
</style>

<section class="login-signup">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12">
					
                <div class="login-box">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active">{{ __('Registration') }}</li>
                    </ul>

                    <div class="tab-content" style="padding:20px;">
                        <div class="tab-pane fade in active" id="nav-reg">
                            <form id="registerForm" class="registerform" action="{{ route('reader.register') }}" method="POST" enctype="multipart/form-data">
                                @include('includes.validation.form_validation')
                                @csrf
							
                                <!-- Full Name -->
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="{{ __('Full Name') }}" required>
                                </div>

                                <!-- Phone -->
                               <div class="form-group d-flex">
                                    <input type="email" class="form-control" name="email" placeholder="আপনার ইমেইল লিখুন" required>
                                    <input type="text" class="form-control" name="phone" placeholder="আপনার ফোন নম্বর লিখুন" required>
                                </div>

                                <!-- Password -->
                                <div class="form-group d-flex">

                                    <div class="password-wrapper">
                                        <label>যে কোনো পাসওয়ার্ড দিন</label>
                                
                                        <input type="password" id="password" class="form-control"
                                               name="password" placeholder="{{ __('Password') }}" required>
                                
                                        <span class="toggle-password" onclick="togglePassword('password', 'passwordIcon')">
                                            <i class="fa fa-eye" id="passwordIcon" style="margin-top: 30px;"></i>
                                        </span>
                                    </div>
                                
                                    <div class="password-wrapper">
                                        <label>পাসওয়ার্ড টি পুনরায় লিখুন </label>
                                
                                        <input type="password" id="password_confirmation" class="form-control"
                                               name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                                
                                        <span class="toggle-password" onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')">
                                            <i class="fa fa-eye" id="confirmPasswordIcon" style="margin-top: 30px;"></i>
                                        </span>
                                    </div>
                                
                                </div>
							
                                <input type="hidden" name="referrer_code" value="{{ $ref }}">

                                <input class="mregdata" type="hidden" value="Registering...">
                                <button type="button" id="sendOtpBtn" class="btn btn-primary btn-block">{{ __('Submit') }}</button>
                            </form>
                        </div>

                        <!-- OTP Method Modal -->
                        <div id="otpChoiceModal" class="modal">
                            <div class="modal-content">
                                <h4>OTP কোথায় পাঠাতে চান?</h4>
                                <button class="btn btn-primary btn-block chooseOtp" data-type="phone">Phone</button>
                                <button class="btn btn-primary btn-block chooseOtp" data-type="email" style="margin-top:10px;">Email</button>
                            </div>
                        </div>
                        
                        <!-- OTP Modal -->
                        <div id="otpModal" class="modal">
                            <div class="modal-content">
                                <h4>Please Enter OTP</h4>
                                <input type="text" id="otpInput" placeholder="Enter 4 Digit OTP">
                                <button class="btn btn-primary" id="verifyOtpBtn">Verify OTP</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
function togglePassword(inputId, iconId) {
    let input = document.getElementById(inputId);
    let icon = document.getElementById(iconId);
    if(input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
    $("#sendOtpBtn").click(function (e) {
        e.preventDefault();
    
        if ($("#registerForm")[0].checkValidity()) {
            $('#otpChoiceModal').show();
        } else {
            $("#registerForm")[0].reportValidity();
        }
    });
    
    $(".chooseOtp").click(function () {
    
        let otpVia = $(this).data("type");
        let formData = $("#registerForm").serialize() + '&otp_via=' + otpVia;
    
        $('#otpChoiceModal').hide();
    
        $.ajax({
            url: "{{ route('reader.register') }}",
            type: "POST",
            data: formData,
            success: function (res) {
                if (res.otp_sent) {
                    $('#otpModal').show();
                    sessionStorage.setItem("contact", res.contact);
                } else if(res.errors) {
                    alert(Object.values(res.errors).join("\n"));
                } else if(res.error) {
                    alert(res.error);
                } else {
                    alert("Registration failed: Unknown error occurred.");
                }
            },
            error: function(xhr) {
                let errorMsg = "Something went wrong";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ": " + xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg += ": " + xhr.responseJSON.error;
                } else if (xhr.responseText) {
                    let cleanText = xhr.responseText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (cleanText.length > 150) {
                        cleanText = cleanText.substring(0, 150) + "...";
                    }
                    if (cleanText) {
                        errorMsg += ": " + cleanText;
                    }
                } else if (xhr.statusText === "error" || !xhr.statusText) {
                    errorMsg += ": Please check your internet connection or try again. The server might be temporarily busy.";
                } else if (xhr.statusText) {
                    errorMsg += " (" + xhr.statusText + ")";
                }
                alert(errorMsg);
            }
        });
    });

// VERIFY OTP
$("#verifyOtpBtn").click(function () {
    let otp = $("#otpInput").val();
    let contact = sessionStorage.getItem("contact");
    
    $.ajax({
        url: "/verify-otp",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            otp: otp,
            contact: contact
        },
        success: function (res) {
            if (res.success) {
                window.location.href = res.url;
            } else {
                alert(res.error || "OTP verification failed. Please try again.");
            }
        },
        error: function(xhr) {
            let errorMsg = "OTP verification failed";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg += ": " + xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg += ": " + xhr.responseJSON.error;
            } else if (xhr.responseText) {
                let cleanText = xhr.responseText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                if (cleanText.length > 150) {
                    cleanText = cleanText.substring(0, 150) + "...";
                }
                if (cleanText) {
                    errorMsg += ": " + cleanText;
                }
            } else if (xhr.statusText === "error" || !xhr.statusText) {
                errorMsg += ": Please check your connection or try again. The server might be temporarily busy.";
            } else if (xhr.statusText) {
                errorMsg += " (" + xhr.statusText + ")";
            }
            alert(errorMsg);
        }
    });
});
</script>

@endsection
