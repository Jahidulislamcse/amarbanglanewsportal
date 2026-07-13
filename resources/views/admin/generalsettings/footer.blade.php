@extends('layouts.admin')

@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
          <h4 class="heading">{{__('Website Footer')}}</h4>
        <ul class="links">
          <li>
            <a href="{{ route('admin.dashboard') }}">{{__('Dashboard')}} </a>
          </li>
          <li>
            <a href="javascript:;">{{__('General Settings')}}</a>
          </li>
          <li>
            <a href="{{ route('admin.generalsettings.footer')}}">{{__('Footer')}}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
    <div class="add-product-content">
    @include('includes.admin.form-both')
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="product-description">
          <div class="body-area">
            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
          <form class="uplogo-form" id="geniusform" action="{{route('admin.generalsettings.update')}}" method="POST" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row justify-content-center">
                  <div class="col-lg-12">
                    <div class="left-area">
                        <h4 class="heading">
                            {{__('Copyright Text')}} *
                            <p class="sub-heading">{{(__('In Any Language'))}}</p>
                        </h4>
                      
                    </div>
                  </div>
                  <div class="col-lg-12">
                      <div class="tawk-area">
                      <textarea name="copyright_text" required="">{{$data->copyright_text}}</textarea>
                      </div>
                  </div>
                </div>
              <div class="row justify-content-center">
                  <div class="col-lg-12">
                    <div class="left-area">
                        <h4 class="heading">
                            {{__('Footer Text')}} *
                            <p class="sub-heading">{{(__('In Any Language'))}}</p>
                        </h4>
                    </div>
                  </div>
                  <div class="col-lg-12">
                      <div class="tawk-area">
                        <textarea class="nic-edit"  name="footer_text">{{$data->footer_text}}</textarea>
                      </div>
                  </div>
                </div>
                
                {{-- Contact Section --}}
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-12 mb-3">
                        <div class="left-area">
                            <h4 class="heading">{{ __('Contact Information') }}</h4>
                            <p class="sub-heading">{{ __('Add contact details used in footer') }}</p>
                        </div>
                    </div>
                
                   <div class="col-lg-12 mb-3">
                        <input type="text" class="input-field" name="address" placeholder="Address" value="{{ $contact->address ?? '' }}">
                    </div>
                    
                    <div class="col-lg-12 mb-3">
                        <input type="text" class="input-field" name="address_bn" placeholder="Address (Bangla)" value="{{ $contact->address_bn ?? '' }}">
                    </div>
                    
                    <div class="col-lg-12 mb-3">
                        <input type="text" class="input-field" name="phone" placeholder="Phone Number" value="{{ $contact->phone ?? '' }}">
                    </div>
                    
                    <div class="col-lg-12 mb-3">
                        <input type="email" class="input-field" name="email" placeholder="Email" value="{{ $contact->email ?? '' }}">
                    </div>
                    
                    <div class="col-lg-12 mb-4">
                        <input type="text" class="input-field" name="website" placeholder="Website URL" value="{{ $contact->website ?? '' }}">
                    </div>

                </div>
                
                {{-- Management Section --}}
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-12 mb-3">
                        <div class="left-area">
                            <h4 class="heading">{{ __('Management Details') }}</h4>
                            <p class="sub-heading">{{ __('Add multiple management members dynamically') }}</p>
                        </div>
                    </div>
                
                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" id="management-table">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 15%">Designation</th>
                                    <th style="width: 15%">Designation (BN)</th>
                                    <th style="width: 30%">Name</th>
                                    <th style="width: 30%">Name (BN)</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($management) && count($management) > 0)
                                    @foreach($management as $m)
                                        <tr>
                                            <input type="hidden" name="management_id[]" value="{{ $m->id }}">
                                            <td><input type="text" name="designation[]" class="input-field w-100" value="{{ $m->designation }}"></td>
                                            <td><input type="text" name="designation_bn[]" class="input-field w-100" value="{{ $m->designation_bn }}"></td>
                                            <td><input type="text" name="name[]" class="input-field w-100" value="{{ $m->name }}"></td>
                                            <td><input type="text" name="name_bn[]" class="input-field w-100" value="{{ $m->name_bn }}"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger removeRow">X</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                
                        <button type="button" id="addRow" class="btn btn-success btn-sm mt-2">+ Add More</button>
                    </div>
                </div>

            <div class="row justify-content-center">
              <div class="col-lg-12">
                <button class="addProductSubmit-btn" type="submit">{{__('Save')}}</button>
              </div>
            </div>
         </form>
          </div>
        </div>
      </div>
    </div>
  </div>
    </div>

<script>
    document.getElementById('addRow').addEventListener('click', function () {
        let table = document.querySelector('#management-table tbody');
        let newRow = `
            <tr>
                <input type="hidden" name="management_id[]" value="">
                <td><input type="text" name="designation[]" class="input-field w-100"></td>
                <td><input type="text" name="designation_bn[]" class="input-field w-100"></td>
                <td><input type="text" name="name[]" class="input-field w-100"></td>
                <td><input type="text" name="name_bn[]" class="input-field w-100"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">X</button></td>
            </tr>
        `;
        table.insertAdjacentHTML('beforeend', newRow);
    });
    
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection