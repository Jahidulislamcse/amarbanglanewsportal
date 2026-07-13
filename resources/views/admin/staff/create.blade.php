@extends('layouts.load')
@section('content')
<style>
    .experience-box {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #F5F1EB;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .user-image-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }
    .user-image-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px;
        background: #fff;
        text-align: center;
    }
    .user-image-card h4 {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 8px;
        color: #333;
    }
    .user-image-card img {
        width: 100%;
        height: 170px;
        object-fit: contain;
        border: 1px solid #eee;
        border-radius: 6px;
        background: #f8f9fa;
        display: block;
        margin-bottom: 10px;
    }
    .user-image-card input[type="file"] {
        display: none;
    }
    .user-image-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }
    .user-image-actions .btn {
        margin: 0;
        padding: 6px 10px;
        font-size: 13px;
        line-height: 1.2;
        cursor: pointer;
    }
    @media (max-width: 991px) {
        .user-image-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 575px) {
        .user-image-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

    <div class="add-product-content p-0 shadow-none">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area shadow-none">
                        <div class="gocover"
                            style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form id="geniusformdata" action="{{ route('admin.staff.store') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="lang_id" value="2">

                            <div class="user-image-grid">
                                <div class="user-image-card">
                                    <h4>{{ __('Photo') }} *</h4>
                                    <img id="photo-preview" src="{{ asset('assets/images/noimage.png') }}" alt="Photo">
                                    <input type="file" name="photo" id="photo-upload" accept="image/*" required>
                                    <div class="user-image-actions">
                                        <label for="photo-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                    </div>
                                </div>

                                <div class="user-image-card">
                                    <h4>{{ __('NID Front') }} *</h4>
                                    <img id="nid-front-preview" src="{{ asset('assets/images/noimage.png') }}" alt="NID Front">
                                    <input type="file" name="nid" id="nid-front-upload" accept="image/*" required>
                                    <div class="user-image-actions">
                                        <label for="nid-front-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                    </div>
                                </div>

                                <div class="user-image-card">
                                    <h4>{{ __('NID Back') }} *</h4>
                                    <img id="nid-back-preview" src="{{ asset('assets/images/noimage.png') }}" alt="NID Back">
                                    <input type="file" name="nid_back" id="nid-back-upload" accept="image/*" required>
                                    <div class="user-image-actions">
                                        <label for="nid-back-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                    </div>
                                </div>

                                <div class="user-image-card">
                                    <h4>{{ __('Signature') }} *</h4>
                                    <img id="signature-preview" src="{{ asset('assets/images/noimage.png') }}" alt="Signature">
                                    <input type="file" name="signature" id="signature-upload" accept="image/*" required>
                                    <div class="user-image-actions">
                                        <label for="signature-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="name"
                                        placeholder="{{ __('User Name') }}" required="" value="">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Email') }} *</h4>
                                    </div>
                                    <input type="email" class="input-field" name="email"
                                        placeholder="{{ __('Email Address') }}" required="" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Phone') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="phone"
                                        placeholder="{{ __('Phone Number') }}" required="" value="">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('NID No') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="nid_no"
                                        placeholder="{{ __('NID No') }}" required="" value="">
                                </div>
                            </div>

                            <div class="experience-box p-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="left-area">
                                            <h4 class="heading">Has Experience *</h4>
                                        </div>
                                        <select name="has_experience" id="has_experience" class="form-control" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="left-area">
                                            <h4 class="heading">Experience (Years / Details)</h4>
                                        </div>
                                        <input type="text" class="input-field" name="experience" value="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="left-area">
                                            <h4 class="heading">Organization</h4>
                                        </div>
                                        <input type="text" class="input-field" name="experience_organization" value="">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="left-area">
                                            <h4 class="heading">Designation</h4>
                                        </div>
                                        <input type="text" class="input-field" name="experience_designation" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Father Name') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="father_name"
                                        placeholder="{{ __('Father Name') }}" required="" value="">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Mother Name') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="mother_name"
                                        placeholder="{{ __('Mother Name') }}" required="" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Date of Birth *</h4>
                                    </div>
                                    <input type="date" class="input-field" name="dob" required="" value="">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Blood Group *</h4>
                                    </div>
                                    <select name="blood" id="blood" class="form-control" required>
                                        <option value="">{{ __('Blood Group') }}</option>
                                        @foreach (blood_groups(2) as $klb => $blood_group)
                                            <option value="{{ $klb }}">{{ $blood_group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Education Qualification *</h4>
                                    </div>
                                    <select name="eduaction" id="eduaction" class="form-control" required>
                                        <option value="">Education Qualification</option>
                                        @foreach (educationlists(2) as $ecn => $educationlist)
                                            <option value="{{ $ecn }}">{{ $educationlist }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Education Year *</h4>
                                    </div>
                                    <select name="education_year" id="education_year" class="form-control" required>
                                        <option value="">Education Year</option>
                                        <?php
                                        $startYear = 1971;
                                        $currentYear = date('Y');
                                        for ($year = $startYear; $year <= $currentYear; $year++) {
                                            echo "<option value='$year'>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">Address *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="address" placeholder="Address"
                                        required="" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Division') }} *</h4>
                                    </div>
                                    <select name="division_id" id="division_id" class="form-control" required>
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('District') }} *</h4>
                                    </div>
                                    <select name="district_id" id="district_id" class="form-control" required>
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Upazila') }} *</h4>
                                    </div>
                                    <select name="thana_id" id="thana_id" class="form-control" required>
                                        <option value="">Select Upazila</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Union') }} *</h4>
                                    </div>
                                    <select name="union_id" id="union_id" class="form-control" required>
                                        <option value="">Select Union</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">Reporter Area *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="reporter_area" id="reporter_area" class="form-control" required>
                                        <option value="">Select Reporter Area</option>
                                        @foreach (reporter_area('', 2) as $kl => $reporter_ar)
                                            <option value="{{ $kl }}">{{ $reporter_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Password') }} *</h4>
                                    </div>
                                    <input type="password" class="input-field" name="password" placeholder="Password"
                                        required="" value="">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Is Staff Reporter') }}</h4>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_staff" value="1" id="is_staff">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Applied Type') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    @foreach (report_type(2) as $key => $report_cat)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input single-select-checkbox" type="checkbox"
                                                name="report_type[]" value="{{ $key }}"
                                                id="report_type_{{ $key }}">
                                            <label class="form-check-label" for="report_type_{{ $key }}">
                                                {{ $report_cat }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Create User') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/front/js/login.js') }}"></script>
    <script>
        function bindAdminImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (!input || !preview) return;

            input.addEventListener('change', function() {
                const file = this.files[0];

                if (!file || !file.type.match(/^image\//)) return;

                preview.src = URL.createObjectURL(file);
            });
        }

        bindAdminImagePreview('nid-front-upload', 'nid-front-preview');
        bindAdminImagePreview('nid-back-upload', 'nid-back-preview');
        bindAdminImagePreview('photo-upload', 'photo-preview');
        bindAdminImagePreview('signature-upload', 'signature-preview');

        const checkboxes = document.querySelectorAll('.single-select-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    checkboxes.forEach(function(other) {
                        if (other !== checkbox) {
                            other.checked = false;
                        }
                    });
                }
            });
        });
    </script>
@endsection
