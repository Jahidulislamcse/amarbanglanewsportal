@extends('layouts.admin')

@section('content')
					<input type="hidden" id="headerdata" value="{{ __('User') }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">News Wise {{$user_informations->name}} Income</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
											</li>
											<li>
												<a href="{{ route('admin.staff.index') }}">{{ __('Manage User') }}</a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
						
					<div class="row justify-content-center" style="padding-top:20px;">
						<div class="col-md-6">

						  <table class="table table-bordered text-center">
							<tbody>
							  <tr>
								<th scope="row">Name</th>
								<td>{{$user_informations->name}}</td>
							  </tr>

							  <!-- Email -->
							  <tr>
								<th scope="row">Email</th>
								<td>{{$user_informations->email}}</td>
							  </tr>

							  <!-- Phone -->
							  <tr>
								<th scope="row">Phone</th>
								<td>{{$user_informations->phone}}</td>
							  </tr>
							</tbody>
						  </table>

						</div>
					  </div>
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
    									@include('includes.admin.form-success')
										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
									                        <th>{{ __('Date') }}</th>
									                        <th>{{ __('Title') }}</th>
									                        <th>{{ __('News') }}</th>
															<th>{{ __('Total Views') }}</th>
															<th>{{ __('Total Income') }}</th>
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
					url: '{{ route('admin.staff.userPostdatatables') }}',
					data: function (d) {
						d.user_id = {{$user_informations->id}};

					}
				},
				columns: [
					{ data: 'created_at', name: 'created_at' },
					{ data: 'title', name: 'title' },
					{ data: 'description', name: 'description' },
					{ data: 'view_count', name: 'view_count' },
					{ data: 'total_commission', name: 'total_commission' }
				],
				language: {
					processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">'
				}
			});

			/*$('#start_date, #end_date, #status').on('change', function () {
				table.draw();
			});*/


    </script>

{{-- DATA TABLE --}}

@endsection
