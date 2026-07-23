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
    .user-image-actions .btn,
    .user-image-actions a {
        margin: 0;
        padding: 6px 10px;
        font-size: 13px;
        line-height: 1.2;
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

    <div class="add-product-content  p-0 shadow-none">
        @include('includes.admin.form-error')
        @include('includes.admin.form-success')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area shadow-none">
                        <div class="gocover"
                            style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form id="geniusformdata" action="{{ route('admin.staff.update', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="lang_id" value="2">
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="user-image-grid">
                                <div class="user-image-card">
                                    <h4>{{ __('NID Front') }}</h4>
                                    <img id="nid-front-preview" src="{{ $data->nid ? asset('assets/images/admin/' . $data->nid) : asset('assets/images/noimage.png') }}" alt="NID Front">
                                    <input type="file" name="nid" id="nid-front-upload" accept="image/*">
                                    <div class="user-image-actions">
                                        <label for="nid-front-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                        <a class="btn btn-info btn-sm" href="{{ $data->nid ? asset('assets/images/admin/' . $data->nid) : asset('assets/images/noimage.png') }}" target="_blank">{{ __('View') }}</a>
                                    </div>
                                </div>

                                <div class="user-image-card">
                                    <h4>{{ __('NID Back') }}</h4>
                                    <img id="nid-back-preview" src="{{ $data->nid_back ? asset('assets/images/admin/' . $data->nid_back) : asset('assets/images/noimage.png') }}" alt="NID Back">
                                    <input type="file" name="nid_back" id="nid-back-upload" accept="image/*">
                                    <div class="user-image-actions">
                                        <label for="nid-back-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                        <a class="btn btn-info btn-sm" href="{{ $data->nid_back ? asset('assets/images/admin/' . $data->nid_back) : asset('assets/images/noimage.png') }}" target="_blank">{{ __('View') }}</a>
                                    </div>
                                </div>

                                <div class="user-image-card">
                                    <h4>{{ __('Photo') }}</h4>
                                    <img id="photo-preview" src="{{ $data->photo ? asset('assets/images/admin/' . $data->photo) : asset('assets/images/noimage.png') }}" alt="Photo">
                                    <input type="file" name="photo" id="photo-upload" accept="image/*">
                                    <div class="user-image-actions">
                                        <label for="photo-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                        <a class="btn btn-info btn-sm" href="{{ $data->photo ? asset('assets/images/admin/' . $data->photo) : asset('assets/images/noimage.png') }}" target="_blank">{{ __('View') }}</a>
                                    </div>
                                </div>

                                <div class="user-image-card">
                                    <h4>{{ __('Signature') }}</h4>
                                    <img id="signature-preview" src="{{ $data->id ? asset('assets/images/admin/' . $data->id . '.png') : asset('assets/images/noimage.png') }}" alt="Signature">
                                    <input type="file" name="signature" id="signature-upload" accept="image/*">
                                    <div class="user-image-actions">
                                        <label for="signature-upload" class="btn btn-primary btn-sm">{{ __('Upload') }}</label>
                                        <a class="btn btn-info btn-sm" href="{{ $data->id ? asset('assets/images/admin/' . $data->id . '.png') : asset('assets/images/noimage.png') }}" target="_blank">{{ __('View') }}</a>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-4">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Post History') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <table class="table table-bordered table-striped text-center align-middle" style="font-size: 11px; background: #fff; width: 100%;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="p-2">Approved</th>
                                                <th class="p-2">Pending</th>
                                                <th class="p-2">Rejected</th>
                                                <th class="p-2">Today</th>
                                                <th class="p-2">3 Days</th>
                                                <th class="p-2">7 Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-success fw-bold p-2">{{ $approvedCount }}</td>
                                                <td class="text-warning fw-bold p-2">{{ $pendingCount }}</td>
                                                <td class="text-danger fw-bold p-2">{{ $rejectedCount }}</td>
                                                <td class="fw-bold p-2">{{ $todayCount }}</td>
                                                <td class="fw-bold p-2">{{ $threeDaysCount }}</td>
                                                <td class="fw-bold p-2">{{ $sevenDaysCount }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Total Posts') }}</h4>
                                    </div>
                                    <input type="text" class="input-field" value="{{ $reporterPostCount }}" disabled>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Total Views') }}</h4>
                                    </div>
                                    <input type="text" class="input-field" value="{{ $reporterViews }}" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="name"
                                        placeholder="{{ __('User Name') }}" required="" value="{{ $data->name }}">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Email') }} *</h4>
                                    </div>
                                    <input type="email" class="input-field" name="email"
                                        placeholder="{{ __('Email Address') }}" required=""
                                        value="{{ $data->email }}">
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Phone') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="phone"
                                        placeholder="{{ __('Phone Number') }}" required=""
                                        value="{{ $data->phone }}">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('NID No') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="nid_no"
                                        placeholder="{{ __('NID No') }}" required="" value="{{ $data->nid_no }}">

                                </div>
                            </div>
                            
                          <div class="experience-box p-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Has Experience *</h4>
                                    </div>
                                    <select name="has_experience" id="has_experience" class="form-control">
                                        <option value="0" {{ $data->has_experience == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ $data->has_experience == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                        
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Experience (Years / Details)</h4>
                                    </div>
                                    <input type="text"
                                           class="input-field"
                                           name="experience"
                                           value="{{ $data->experience }}">
                                </div>
                            </div>
                        
                            <div class="row ">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Organization</h4>
                                    </div>
                                    <input type="text"
                                           class="input-field"
                                           name="experience_organization"
                                           value="{{ $data->experience_organization }}">
                                </div>
                        
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Designation</h4>
                                    </div>
                                    <input type="text"
                                           class="input-field"
                                           name="experience_designation"
                                           value="{{ $data->experience_designation }}">
                                </div>
                            </div>
                        </div>

                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Father Name') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="father_name"
                                        placeholder="{{ __('Father Name') }}" required=""
                                        value="{{ $data->father_name }}">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Mother Name') }} *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="mother_name"
                                        placeholder="{{ __('Mother Name') }}" required=""
                                        value="{{ $data->mother_name }}">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Date of Birth *</h4>
                                    </div>
                                    <input type="date" class="input-field" name="dob" required=""
                                        value="{{ $data->dob }}">
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">Blood Group *</h4>
                                    </div>
                                    <select name="blood" id="blood" class="form-control" required>
                                        <option value="">{{ __('Blood Group') }}</option>
                                        @foreach (blood_groups(2) as $klb => $blood_group)
                                            <option value="{{ $klb }}"
                                                {{ $klb == $data->blood ? 'selected' : '' }}>{{ $blood_group }}</option>
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
                                            <option value="{{ $ecn }}"
                                                {{ $ecn == $data->eduaction ? 'selected' : '' }}>{{ $educationlist }}
                                            </option>
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
                                            $sel = $year == $data->education_year ? 'selected' : '';
                                        
                                            echo "<option value='$year' $sel>$year</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">Addres *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="address" placeholder="Address"
                                        required="" value="{{ $data->address }}">

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <hr>
                                    <h5 class="sub-heading" style="color: #922B21; font-weight: bold; margin-bottom: 15px;">{{ __('Reporting Area') }}</h5>
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
                                            <option value="{{ $division->id }}" {{ $data->division_id == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('District') }} *</h4>
                                    </div>
                                   <select name="district_id" id="district_id" class="form-control" required>
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ $data->district_id == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
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
                                        @foreach($thanas as $thana)
                                            <option value="{{ $thana->id }}" {{ $data->thana_id == $thana->id ? 'selected' : '' }}>
                                                {{ $thana->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Union') }}</h4>
                                    </div>
                                   <select name="union_id" id="union_id" class="form-control">
                                        <option value="">Select Union</option>
                                        @foreach($unions as $union)
                                            <option value="{{ $union->id }}" {{ $data->union_id == $union->id ? 'selected' : '' }}>
                                                {{ $union->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <hr>
                                    <h5 class="sub-heading" style="color: #922B21; font-weight: bold; margin-bottom: 15px;">{{ __('Permanent Address') }}</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Permanent Division') }} *</h4>
                                    </div>
                                    <select name="permanent_division_id" id="permanent_division_id" class="form-control" required>
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ $data->permanent_division_id == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Permanent District') }} *</h4>
                                    </div>
                                   <select name="permanent_district_id" id="permanent_district_id" class="form-control" required>
                                        <option value="">Select District</option>
                                        @foreach($permanentDistricts as $district)
                                            <option value="{{ $district->id }}" {{ $data->permanent_district_id == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Permanent Upazila') }} *</h4>
                                    </div>
                                    <select name="permanent_thana_id" id="permanent_thana_id" class="form-control" required>
                                        <option value="">Select Upazila</option>
                                        @foreach($permanentThanas as $thana)
                                            <option value="{{ $thana->id }}" {{ $data->permanent_thana_id == $thana->id ? 'selected' : '' }}>
                                                {{ $thana->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Permanent Union') }}</h4>
                                    </div>
                                   <select name="permanent_union_id" id="permanent_union_id" class="form-control">
                                        <option value="">Select Union</option>
                                        @foreach($permanentUnions as $union)
                                            <option value="{{ $union->id }}" {{ $data->permanent_union_id == $union->id ? 'selected' : '' }}>
                                                {{ $union->name }}
                                            </option>
                                        @endforeach
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
                                    <select name="reporter_area" id="reporter_area" class="form-control"
                                        data-selected="{{ old('reporter_area', $data->reporter_area) }}" required>
                                        <option value="">Select Reporter Area</option>
                                        @foreach (reporter_area('', 2) as $kl => $reporter_ar)
                                            <option value="{{ $kl }}"
                                                {{ $kl == $data->reporter_area ? 'selected' : '' }}>{{ $reporter_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Is Staff Reporter') }}</h4>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_staff" value="1"
                                            id="is_staff" {{ $data->is_staff == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <div class="left-area">
                                        <h4 class="heading">Pin Code *</h4>
                                    </div>
                                    <input type="text" class="input-field" name="affilate_code" required=""
                                        value="{{ $data->affilate_code }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Applied Type') }} *</h4>
                                    </div>
                                </div>
                                <?php
                                $selectedReportTypes = json_decode($data->report_type, true) ?? [];
                                ?>
                                <div class="col-lg-7">
                                    @foreach (report_type(2) as $key => $report_cat)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input single-select-checkbox" type="checkbox"
                                                name="report_type[]" value="{{ $key }}"
                                                id="report_type_{{ $key }}"
                                                {{ in_array($key, old('report_type', $selectedReportTypes)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="report_type_{{ $key }}">
                                                {{ $report_cat }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if (auth()->user()->role_id == 12)
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Status') }} *</h4>
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <select name="is_approve" id="is_approve" class="form-control">
                                            @foreach (is_status() as $key => $status)
                                                <option value="{{ $key }}"
                                                    {{ $key == $data->is_approve ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                            @else
                                @if (Auth::guard('admin')->user()->sectionCheck('reporter_approve') && auth()->user()->role_id != 12)
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Status') }} *</h4>
                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <select name="is_approve" id="is_approve" class="form-control">
                                                @foreach (is_status() as $key => $status)
                                                    <option value="{{ $key }}"
                                                        {{ $key == $data->is_approve ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>
                                @endif
                            @endif


                            @if(auth('admin')->check() && auth('admin')->id() == 1)
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Package 1 Purchased') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7" style="display: flex; align-items: center; gap: 20px; padding-top: 10px;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input pg-purchased-radio" type="radio" name="package1_purchased" id="pg_purchased_no" value="0" {{ $data->package1_purchased == 0 ? 'checked' : '' }} data-checked="{{ $data->package1_purchased == 0 ? 'true' : 'false' }}">
                                        <label class="form-check-label" for="pg_purchased_no" style="font-weight: 600; cursor: pointer; margin-left: 5px;">No</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input pg-purchased-radio" type="radio" name="package1_purchased" id="pg_purchased_yes" value="1" {{ $data->package1_purchased == 1 ? 'checked' : '' }} data-checked="{{ $data->package1_purchased == 1 ? 'true' : 'false' }}">
                                        <label class="form-check-label" for="pg_purchased_yes" style="font-weight: 600; cursor: pointer; margin-left: 5px;">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="bypass-duration-row" style="{{ $data->package1_purchased == 1 ? 'display: none;' : '' }}">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Bypass Purchase Block') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7" style="padding-top: 10px;">
                                    <select class="form-control" name="bypass_duration" id="bypass_duration">
                                        <option value="">{{ __('None / Expired') }}</option>
                                        <option value="24" {{ $data->package_bypass_until && \Carbon\Carbon::parse($data->package_bypass_until)->isFuture() && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) <= 24 ? 'selected' : '' }}>{{ __('24 Hours') }}</option>
                                        <option value="48" {{ $data->package_bypass_until && \Carbon\Carbon::parse($data->package_bypass_until)->isFuture() && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) > 24 && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) <= 48 ? 'selected' : '' }}>{{ __('48 Hours') }}</option>
                                        <option value="72" {{ $data->package_bypass_until && \Carbon\Carbon::parse($data->package_bypass_until)->isFuture() && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) > 48 && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) <= 72 ? 'selected' : '' }}>{{ __('72 Hours') }}</option>
                                        <option value="96" {{ $data->package_bypass_until && \Carbon\Carbon::parse($data->package_bypass_until)->isFuture() && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) > 72 && abs(\Carbon\Carbon::parse($data->package_bypass_until)->diffInHours(now())) <= 96 ? 'selected' : '' }}>{{ __('96 Hours') }}</option>
                                    </select>
                                    @if($data->package_bypass_until && \Carbon\Carbon::parse($data->package_bypass_until)->isFuture())
                                        <small class="text-success font-weight-bold d-block mt-1">
                                            Bypass active until: {{ \Carbon\Carbon::parse($data->package_bypass_until)->format('d M Y H:i') }} ({{ \Carbon\Carbon::parse($data->package_bypass_until)->diffForHumans() }})
                                        </small>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Update') }}</button>
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
        const checkboxess = document.querySelectorAll('.single-select-checkbox1');
        checkboxess.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    checkboxess.forEach(function(other) {
                        if (other !== checkbox) {
                            other.checked = false;
                        }
                    });
                }
            });
        });
    </script>
    
    <script>
    const allDistricts = @json($allDistricts);
    
    $(document).off('change', '#division_id');
    
    $(document).on('change', '#division_id', function () {
    
        let divisionId = $(this).val();
    
        let districts = allDistricts.filter(function(item){
            return item.division_id == divisionId;
        });
    
        let html = '<option value="">Select District</option>';
    
        districts.forEach(function(item){
            html += `<option value="${item.id}">${item.name}</option>`;
        });
    
        $('#district_id').html(html);
    
        console.log('districts loaded', districts);
    });

    $(document).off('change', '#permanent_division_id');
    
    $(document).on('change', '#permanent_division_id', function () {
    
        let divisionId = $(this).val();
    
        let districts = allDistricts.filter(function(item){
            return item.division_id == divisionId;
        });
    
        let html = '<option value="">Select District</option>';
    
        districts.forEach(function(item){
            html += `<option value="${item.id}">${item.name}</option>`;
        });
    
        $('#permanent_district_id').html(html);
        $('#permanent_thana_id').html('<option value="">Select Upazila</option>');
        $('#permanent_union_id').html('<option value="">Select Union</option>');
    
        console.log('permanent districts loaded', districts);
    });

    // Package 1 Purchased confirmation check using event delegation (works with AJAX modals)
    $(document).off('click', 'input[name="package1_purchased"]');
    $(document).on('click', 'input[name="package1_purchased"]', function(e) {
        let $el = $(this);
        if ($el.attr('data-checked') === 'true') {
            return;
        }
        
        let confirmMsg = $el.val() == 1 
            ? "Are you sure you want to mark Package 1 as purchased for this user?" 
            : "Are you sure you want to mark Package 1 as NOT purchased for this user?";
            
        let confirmed = confirm(confirmMsg);
        if (confirmed) {
            $('input[name="package1_purchased"]').attr('data-checked', 'false');
            $el.attr('data-checked', 'true');
            if ($el.val() == 1) {
                $('#bypass-duration-row').hide();
                $('#bypass_duration').val('');
            } else {
                $('#bypass-duration-row').show();
            }
        } else {
            e.preventDefault();
            return false;
        }
    });
    </script>
@endsection
