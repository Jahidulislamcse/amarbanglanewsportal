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
					<input type="hidden" id="headerdata" value="{{ __('PAYMENT REQUEST') }}">
					<div class="content-area">

@if($blockUser)
{{-- ============================================================
     PACKAGE 1 GATE CUSTOM HIGH-FIDELITY OVERLAY
     ============================================================ --}}
<div id="packageGateOverlay" style="
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 999999;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
    font-family: 'Outfit', 'Inter', 'SolaimanLipi', sans-serif;
">
    {{-- Back to Dashboard Button --}}
    <a href="{{ route('user.dashboard') }}" style="
        position: fixed;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        z-index: 1000000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    " onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
        <i class="fas fa-arrow-left"></i> ড্যাশবোর্ডে ফিরে যান
    </a>

    <div style="
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 800px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        margin: auto;
        border: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        flex-direction: column;
        animation: pgModalFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    ">
        {{-- Header --}}
        <div style="
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            padding: 28px 32px;
            color: #ffffff;
        ">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
                <div style="
                    background: rgba(255, 255, 255, 0.2);
                    width: 44px; height: 44px;
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 20px;
                ">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <h4 style="margin: 0; font-weight: 700; font-size: 22px; color: #ffffff;">
                    পেমেন্ট রিকোয়েস্ট করতে প্যাকেজ সংগ্রহ করুন
                </h4>
            </div>
            <p style="margin: 0; font-size: 14px; opacity: 0.95; line-height: 1.6; color: #ffe4e6; font-weight: 500;">
                সম্মানিত প্রতিনিধি, ইতিমধ্যে আপনার {{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $postCount) }}টি সংবাদ আমার বাংলা 24 এ প্রকাশিত হয়েছে । আমাদের সাথে থাকার জন্য আপনাকে আন্তরিক ধন্যবাদ! আমার বাংলার 24 এর সাংবাদিকতা পরিচয়কে আরও পেশাদার করতে অনুগ্রহ করে নিচের প্যাকেজটি সংগ্রহ করুন।
            </p>
        </div>

        {{-- Body --}}
        <div style="padding: 32px; background: #f8fafc; overflow-y: auto; max-height: 70vh; text-align: left;">
            @if($package1Products->count())
                {{-- Scroll Down Indicator --}}
                <div style="
                    text-align: center;
                    margin: 0 0 20px 0;
                    padding: 14px 20px;
                    background: #fffbeb;
                    border: 1px solid #fde68a;
                    border-radius: 12px;
                    color: #b45309;
                    font-weight: 700;
                    font-size: 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 12px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
                ">
                    <span>পেমেন্ট ও অর্ডার ফরমটি পূরণ করতে নিচে যান</span>
                    <i class="fas fa-chevron-circle-down pg-bouncing-arrow" style="font-size: 24px; color: #d97706; display: inline-block; vertical-align: middle;"></i>
                </div>

                <h6 style="font-weight: 700; color: #1e293b; margin: 0 0 16px 0; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-box-open text-rose-600"></i> প্যাকেজে অন্তর্ভুক্ত সামগ্রীসমূহ
                </h6>

                {{-- Products List --}}
                <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 12px 16px; font-weight: 600; color: #475569; font-size: 13px; width: 80px;">ছবি</th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #475569; font-size: 13px;">পণ্যের নাম</th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #475569; font-size: 13px; text-align: right; width: 150px;">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $packageTotal = 0;
                                $hasExcluded = false;
                            @endphp
                            @foreach($package1Products as $prod)
                                @php
                                    $isPurchased = in_array($prod->id, $purchasedProductIds);
                                    if (!$isPurchased) {
                                        $packageTotal += $prod->price;
                                    } else {
                                        $hasExcluded = true;
                                    }
                                    $imgSrc = $prod->primaryImage
                                        ? asset('assets/images/products/' . $prod->primaryImage->image_path)
                                        : asset('assets/images/noimage.png');
                                @endphp
                                <tr style="border-bottom: 1px solid #f1f5f9; {{ $isPurchased ? 'background-color: #f8fafc;' : '' }}">
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                        <img src="{{ $imgSrc }}" width="50" height="50"
                                             style="object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; {{ $isPurchased ? 'opacity: 0.5;' : '' }}">
                                    </td>
                                    <td style="padding: 12px 16px; vertical-align: middle; font-weight: 600; color: {{ $isPurchased ? '#94a3b8' : '#1e293b' }}; font-size: 14px;">
                                        {{ $prod->name }}
                                        @if($isPurchased)
                                            <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 9999px; margin-left: 8px; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-check-circle" style="font-size: 10px;"></i> ইতোমধ্যে ক্রয়কৃত (বাদ দেওয়া হয়েছে)
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px 16px; vertical-align: middle; text-align: right; font-weight: 700; font-size: 14px;">
                                        @if($isPurchased)
                                            <del style="color: #94a3b8; margin-right: 8px;">৳{{ number_format($prod->price, 0) }}</del>
                                            <span style="color: #ef4444;">৳০</span>
                                        @else
                                            <span style="color: #10b981;">৳{{ number_format($prod->price, 0) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #fff1f2; border-top: 1px solid #ffe4e6;">
                                <td colspan="2" style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b; font-size: 14px;">
                                    মোট পণ্যের মূল্য:
                                </td>
                                <td style="padding: 16px; text-align: right; font-weight: 800; color: #e11d48; font-size: 16px;">
                                    ৳{{ number_format($packageTotal, 0) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                {{-- Checkout Form --}}
                <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <h6 style="font-weight: 700; color: #1e293b; margin: 0 0 20px 0; font-size: 15px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <i class="fas fa-shopping-basket text-rose-600"></i> ডেলিভারি ও পেমেন্ট তথ্য প্রদান করুন
                    </h6>

                    <form action="{{ route('product.pay') }}" method="POST" id="packageGatePayForm">
                        @csrf

                        @foreach($package1Products as $prod)
                            @if(!in_array($prod->id, $purchasedProductIds))
                                <input type="hidden" name="product_ids[]" value="{{ $prod->id }}">
                                <input type="hidden" name="quantities[]" value="1">
                            @endif
                        @endforeach

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 6px;">ফোন নম্বর *</label>
                                <input type="text" name="phone_number" class="form-control"
                                       value="{{ auth()->user()->phone ?? '' }}"
                                       placeholder="01XXXXXXXXX" required
                                       style="height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; width: 100%;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 6px;">ডেলিভারি জোন *</label>
                                <select name="delivery_zone" id="pgDeliveryZone" class="form-control" required
                                        style="height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; width: 100%;">
                                    <option value="inside" data-charge="80">ঢাকার ভিতরে (৳ ৮০)</option>
                                    <option value="outside" data-charge="120" selected>ঢাকার বাইরে (৳ ১২০)</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #475569; font-size: 13px; margin-bottom: 6px;">ডেলিভারি ঠিকানা *</label>
                            <textarea name="address" class="form-control" rows="3"
                                      placeholder="আপনার সম্পূর্ণ ডেলিভারি ঠিকানা লিখুন (গ্রাম, ডাকঘর, থানা, জেলা)" required
                                      style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; width: 100%; padding: 10px;"></textarea>
                            <span style="display: block; font-size: 12px; color: #64748b; margin-top: 6px; font-weight: 500;">
                                🚚 ডেলিভারি চার্জ: ঢাকার ভিতরে ৮০ টাকা, ঢাকার বাইরে ১২০ টাকা।
                            </span>
                        </div>

                        <div style="background: #f1f5f9; border-radius: 12px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border: 1px dashed #cbd5e1;">
                            <span style="font-weight: 700; color: #334155; font-size: 14px;">সর্বমোট পরিশোধযোগ্য মূল্য (পণ্য + ডেলিভারি চার্জ):</span>
                            <span style="font-weight: 800; color: #e11d48; font-size: 20px;" id="pgGrandTotal">
                                ৳{{ number_format($packageTotal + 120, 0) }}
                            </span>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" style="
                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                color: #ffffff;
                                font-weight: 700;
                                font-size: 16px;
                                border: none;
                                padding: 14px 40px;
                                border-radius: 30px;
                                cursor: pointer;
                                box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
                                display: inline-flex;
                                align-items: center;
                                gap: 10px;
                                transition: all 0.2s ease;
                            ">
                                <i class="fas fa-shopping-cart"></i> অর্ডার সম্পন্ন করুন ও পেমেন্ট করুন
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; text-align: center; color: #b45309; font-weight: 600;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i> এই মুহূর্তে প্যাকেজে কোনো পণ্য নেই। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@keyframes pgModalFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes pgArrowBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(6px); }
}
.pg-bouncing-arrow {
    animation: pgArrowBounce 1s infinite;
}
</style>

<script>
document.getElementById('pgDeliveryZone') && document.getElementById('pgDeliveryZone').addEventListener('change', function() {
    const charge = parseInt(this.options[this.selectedIndex].dataset.charge || '120', 10);
    const products = {{ $packageTotal ?? 0 }};
    document.getElementById('pgGrandTotal').textContent = '৳' + (products + charge).toLocaleString('en-BD');
});
</script>
@endif

					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">Payment Request</h4>
										<ul class="links">
											<li>
												<a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
											</li>

										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
					
						
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
    									@include('includes.admin.form-success')
										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
														
									                        <th>{{ __('Reporter Name') }}</th>
									                        <th>{{ __('Reporter Email') }}</th>
									                        <th>{{ __('Reporter Phone') }}</th>
															<th>{{ __('Request Date') }}</th>
															<th>{{ __('Request Amount') }}</th>
															<th>{{ __('Account Details') }}</th>
															<th>{{ __('Approve Amount') }}</th>
															<th>{{ __('Approve Date') }}</th>
															<th>{{ __('Status') }}</th>
															<th>{{ __('Approve By') }}</th>
															<th>{{ __('Action') }}</th>
									                     
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
					url: '{{ route('user.user.paymentdatatables') }}',
					data: function (data) {
						//var reporter_area=$('select[name=reporter_area]').val();
						//data.reporter_area= reporter_area;

					}
				},
               columns: [
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'phone', name: 'phone' },
						{ data: 'request_date', name: 'request_date' },
						{ data: 'request_amount', name: 'request_amount' },
						{data: 'account_details', name: 'account_details' },
						{ data: 'approve_amount', name: 'approve_amount' },
						{ data: 'verify_date', name: 'verify_date' },
						{ data: 'status', name: 'status' },
						{ data: 'admin_id', name: 'admin_id' },
						{ data: 'action', name: 'action' },

                     ],
               language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                }
            });
			
			 $('#reporter_area').change(function(){
					table.draw();
			});
			  	$(function() {
        $(".btn-area").append('<div class="col-sm-4 text-right">'+
        	'<a class="add-btn" data-href="{{route('user.profile.paymentcerate')}}" id="add-data" data-toggle="modal" data-target="#modal1">'+
          '<i class="fas fa-plus"></i> {{ __('Add Payment Request') }}'+
          '</a>'+
          '</div>');
      });
    

    </script>
	
@endsection

