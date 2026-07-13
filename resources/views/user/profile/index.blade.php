@extends('layouts.user')
@section('content')

<style>
   label {
      font-size: 14px;
      color: #555;
      margin-right: 8px;
    }

    input[type="month"] {
      padding: 6px 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
    }

    input[type="month"]:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 4px rgba(59, 130, 246, 0.5);
    }
</style>
					<input type="hidden" id="headerdata" value="{{ __('Reporter') }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">Reporter</h4>
										<ul class="links">
											<li>
												<a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
											</li>

										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
						@if(in_array(Auth::user()->reporter_area, [1,2,3]))

						 <div class="row">
							<div class="col-sm-2 m-3">
								<label ><b>Reporter Area</b></label>
								 <select name="reporter_area" id="reporter_area" class="form-control"  required>
									<option value="">Reporter Area</option>
									@foreach (reporter_area(Auth::user()->reporter_area) as $key => $status)
										<option value="{{ $key }}" {{$key==old('reporter_area') ? 'selected' : '' }}>
											{{ $status }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
						@endif
						
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
    									@include('includes.admin.form-success')
										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
									                        <th>{{ __('Name') }}</th>
															<th>{{ __('Desination') }}</th>
									                        <th>{{ __('Email') }}</th>
									                        <th>{{ __('Phone') }}</th>
															<th>{{ __('Total Views') }}</th>
															<th>{{ __('Total Income') }}</th>
															<th>{{ __('Current Views') }}</th>
															<th>{{ __('Current Income') }}</th>
									                     
														</tr>
													</thead>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

{{-- ADD / EDIT MODAL --}}

				<div class="modal fade-scale" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="submit-loader">
									<img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
							</div>
							<div class="modal-header">
								<h5 class="modal-title"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
							</div>
						</div>
					</div>

				</div>

{{-- ADD / EDIT MODAL ENDS --}}


{{-- DELETE MODAL --}}

<div class="modal fade-scale" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

	<div class="modal-header d-block text-center">
		<h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
	</div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center">{{ __('You are about to delete this User.') }}</p>
            <p class="text-center">{{ __('Do you want to proceed?') }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
            <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
      </div>

    </div>
  </div>
</div>

{{-- DELETE MODAL ENDS --}}

@endsection



@section('scripts')

{{-- DATA TABLE --}}


    <script type="text/javascript">

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
				ajax: {
					url: '{{ route('user.user.datatables') }}',
					data: function (data) {
						var reporter_area=$('select[name=reporter_area]').val();
						data.reporter_area= reporter_area;

					}
				},
               columns: [
                        { data: 'name', name: 'name' },
						{ data: 'report_type', name: 'report_type' },
                        { data: 'email', name: 'email' },
                        { data: 'phone', name: 'phone' },
						{ data: 'total_views', name: 'total_views' },
						{ data: 'total_commission', name: 'total_commission' },
						{ data: 'current_views', name: 'current_views' },
						{ data: 'current_commission', name: 'current_commission' },

                     ],
               language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                }
            });
			
			 $('#reporter_area').change(function(){
					table.draw();
			});

    

    </script>
	
@endsection

