@extends('layouts.reader')

@section('content')
@php
    $user = auth()->user();
    $balance = $user->balance;

    $vatPercent = 15;
    $minWithdraw = 500;

    $maxWithdraw = max(0, $balance - 1); 

    $canWithdraw = $maxWithdraw >= $minWithdraw;
@endphp

<div class="content-area">

    <div class="mr-breadcrumb d-flex justify-content-between align-items-center">
        <h4 class="heading">Payment Request</h4>

        <button class="btn btn-primary btn-sm"
                id="openModal"
                @if(!$canWithdraw || auth()->user()->reader_type !== 'vip') disabled @endif>
            + Add Payment Request
        </button>
    </div>

    @if(auth()->user()->reader_type !== 'vip')
        <div class="card mt-3 border-warning" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #ff9900; background-color: #fffdf5;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="mr-2" style="font-size: 24px;">💎</span>
                    <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 18px;">
                        VIP প্রিমিয়াম অফার (সর্বোচ্চ ইনকাম)
                    </h5>
                </div>
                
                <div class="alert alert-warning py-2 px-3 mb-3 border-0" style="background-color: #fffbeb; color: #b45309; border-radius: 6px; font-size: 13.5px; font-weight: 600;">
                    <i class="fas fa-info-circle mr-1"></i> দুঃখিত, পেমেন্ট রিকোয়েস্ট করার সুবিধা শুধুমাত্র ভিআইপি (VIP) প্যাকেজ হোল্ডার ব্যবহারকারীদের জন্য উপলব্ধ।
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <ul class="list-unstyled mb-0" style="font-size: 14.5px; line-height: 1.8; color: #4f4f4f;">
                            <li><i class="fas fa-check-circle text-warning mr-2"></i> প্রতি ভিউ ইনকাম হবে <span class="font-weight-bold text-dark">15 পয়সা</span></li>
                            <li><i class="fas fa-check-circle text-warning mr-2"></i> প্রতি রেফারেল (অ্যাকাউন্ট করলে) <span class="font-weight-bold text-dark">৫ টাকা</span></li>
                            <li><i class="fas fa-check-circle text-warning mr-2"></i> রেফারকৃত ব্যাক্তি প্যাকেজ নিলে আরও <span class="font-weight-bold text-dark">10% (সর্বোচ্চ ৳ 49.90 পর্যন্ত)</span> স্বয়ংক্রিয়ভাবে আপনার ব্যালেন্সে যুক্ত হবে</li>
                            <li><i class="fas fa-check-circle text-warning mr-2"></i> সর্বোচ্চ কমিশন সুবিধা <span class="font-weight-bold text-dark">Available only for VIP</span></li>
                        </ul>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
                        <a href="{{ route('reader.dashboard') }}" class="btn btn-warning px-4 py-2 font-weight-bold text-white shadow-sm" style="background-color: #ff9900; border: none; border-radius: 6px; font-size: 14.5px;">
                            <i class="fas fa-gem mr-1"></i> Go VIP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!$canWithdraw)
        <div class="alert alert-danger mt-3">
             Minimum 500 balance required to apply for withdrawal.
        </div>
    @endif

    @include('includes.admin.form-success')

    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Approve By</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $req)
                    <tr>
                        <td>{{ $req->request_date }}</td>
                        <td>৳{{ $req->request_amount }}</td>
                        <td>{{ $req->account_details }}</td>
                        <td>
                            @if($req->status == 0)
                                <span class="badge badge-warning">Pending</span>
                            @elseif($req->status == 1)
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        @if($req->status == 1)
                            <td>{{ $req->verifier->name ?? '-' }}</td>
                        @elseif($req->status == 0)
                            <td>Request can take up-to 24 hours to be updated <br></td>
                        @else
                             <td>{{ $req->verifier->name ?? '-' }}</td>
                        @endif
                        <td>-</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No Data</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

{{-- ================= MODAL ================= --}}
<div id="customModal" class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">
            <h5>Create Payment Request</h5>
            <button type="button" id="closeModal">×</button>
        </div>

        <form method="POST" action="{{ route('user.profile.reader_paymentstore') }}">
            @csrf

            <div class="modal-body">



                <div class="form-group">
                    <label>Amount</label>
                    <input type="number"
                       id="amountInput"
                       name="user_amount"
                       class="form-control"
                       value="{{ $canWithdraw ? $maxWithdraw : '' }}"
                       min="500"
                       max="{{ $maxWithdraw }}"
                       onkeyup="validateAmount()"
                       onchange="validateAmount()"
                       @if(!$canWithdraw) disabled @endif
                       required>
                </div>
                

                <div class="form-group">
                    <label>Amount After 15% VAT (Actual Withdraw Amount)</label>
                    <input type="number"
                           id="afterVatInput"
                           class="form-control"
                           value="{{ $canWithdraw ? number_format($maxWithdraw * 0.85, 0, '.', '') : '' }}"
                           disabled>
                    <input type="hidden"
                           id="requestAmountHidden"
                           name="request_amount"
                           value="{{ $canWithdraw ? number_format($maxWithdraw * 0.85, 0, '.', '') : '' }}">
                    <small class="text-success" id="netAmountText"></small>
                </div>
                
                <div class="form-group">
                    <label>Payment Type</label>
                    <select name="payment_type" class="form-control" required
                            @if(!$canWithdraw) disabled @endif>
                        <option value="">Select</option>
                        @foreach(payment_type() as $key => $val)
                            @if(in_array($key, [3, 4, 5]))
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Number</label>
                    <input type="text"
                           name="account_details"
                           class="form-control"
                           placeholder="017XXXXXXXX"
                           @if(!$canWithdraw) disabled @endif
                           required>
                </div>

                <small class="text-danger" id="amountMsg" style="display:none;">
                    Enter amount between 500 and {{ $maxWithdraw }}
                </small>
                
                <small class="text-primary">
                    Your Balance: {{ $balance }} <br>
                    <!--You must keep minimum 1 in balance <br>-->
                    VAT: 15% will be deducted from requested amount <br>
                    Request can take up-to 24 hours to update <br>
                    Minimum Withdraw: 500 <br>
                    Max Withdraw Allowed: {{ $maxWithdraw }}
                </small>
            </div>

            <div class="modal-footer">
                <button type="submit"
                        class="btn btn-success"
                        @if(!$canWithdraw) disabled @endif>
                    Submit
                </button>

                <button type="button" class="btn btn-secondary" id="closeModal2">
                    Close
                </button>
            </div>

        </form>

    </div>
</div>

@endsection


@section('scripts')

<style>
.modal-overlay{
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.modal-box{
    background:#fff;
    width:400px;
    border-radius:10px;
    overflow:hidden;
}

.modal-header{
    padding:10px 15px;
    background:#f5f5f5;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.modal-header button{
    border:none;
    background:none;
    font-size:20px;
    cursor:pointer;
}

.modal-body{
    padding:15px;
}

.modal-footer{
    padding:10px 15px;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}
</style>

<script>
    const canWithdraw = @json($canWithdraw);
    
    document.getElementById('openModal').addEventListener('click', function () {
        if (!canWithdraw) {
            alert('Minimum 500 balance required');
            return;
        }
        document.getElementById('customModal').style.display = 'flex';
    });
    
    document.getElementById('closeModal').onclick = () =>
        document.getElementById('customModal').style.display = 'none';
    
    document.getElementById('closeModal2').onclick = () =>
        document.getElementById('customModal').style.display = 'none';
    
    window.onclick = function(e){
        let modal = document.getElementById('customModal');
        if(e.target == modal){
            modal.style.display = 'none';
        }
    }

    function validateAmount() {
        let amount = parseInt(document.getElementById('amountInput').value);
        let min = 500;
        let max = {{ $maxWithdraw }};
        let vat = 15;

        if (isNaN(amount) || amount < min || amount > max) {
            document.querySelector('button[type="submit"]').disabled = true;
            document.getElementById('amountMsg').style.display = 'block';
        } else {
            document.querySelector('button[type="submit"]').disabled = false;
            document.getElementById('amountMsg').style.display = 'none';
        }

        if (!isNaN(amount) && amount > 0) {
            let vatAmount = (amount * vat) / 100;
            let netAmount = amount - vatAmount;
            let netStr = netAmount.toFixed(0);
            document.getElementById('afterVatInput').value = netStr;
            document.getElementById('requestAmountHidden').value = netStr;
            document.getElementById('netAmountText').innerText =
                "After 15% VAT deduction you will receive: ৳" + netStr;
        } else {
            document.getElementById('afterVatInput').value = '';
            document.getElementById('requestAmountHidden').value = '';
            document.getElementById('netAmountText').innerText = '';
        }
    }

    validateAmount();
</script>
@endsection