@extends('layouts.admin')

<style>
    .quad-box {
        border: 2px solid #ffffff;
    }
    
    .quad-item {
        border-right: 2px solid #ffffff;
        border-bottom: 2px solid #ffffff;
    }

    .quad-item:nth-child(2),
    .quad-item:nth-child(4) {
        border-right: none;
    }

    .quad-item:nth-child(3),
    .quad-item:nth-child(4) {
        border-bottom: none;
    }

</style>

@section('content')
<div class="content-area">
    @if (Auth::guard('admin')->user()->role_id == 4)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4" style="border-left: 5px solid #dc3545; border-radius: 8px; background: #fff;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <h4 class="text-danger mb-2">
                                    <span class="spinner-grow spinner-grow-sm text-danger" role="status" aria-hidden="true" style="width: 14px; height: 14px; margin-right: 5px;"></span>
                                    Active Duty Shift
                                </h4>
                                <p class="text-muted mb-0" style="font-size: 15px;">
                                    এই শিফটে সকল কল রিসিভ করা এবং মেসেজের উত্তর দেওয়া আপনার দায়িত্ব।
                                </p>
                            </div>
                            <div class="text-center mt-3 mt-md-0" style="min-width: 220px;">
                                <div id="duty-timer" class="font-weight-bold text-dark" style="font-size: 2.2rem; font-family: monospace; letter-spacing: 2px;" data-seconds="{{ $remainingSeconds }}">08:00:00</div>
                                <small class="text-secondary font-weight-bold">অবশিষ্ট সময় (Time Remaining)</small>
                            </div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 10px; background-color: #e9ecef; border-radius: 5px; overflow: hidden;">
                            <div id="duty-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%; transition: width 1s linear; background-color: #28a745;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row row-cards-one">
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg1">
                <div class="left">
                    <h5 class="title">{{ __('News') }} </h5>
                    @php
                        $user = Auth::guard('admin')->user()->role;
                    @endphp
                    @if ($user->code == 'admin' && $user->code != 'NA')
                        <span class="number">{{ $author_post }}</span>
                    @else 
                        <span class="number">{{ $total_post }}</span>
                    @endif
                    <a href="{{ route('post.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fab fa-blogger-b"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        @if($hasDivision)
            <div class="col-md-6 col-lg-3 col-xl-4">
            <div class="mycard bg5">
                <div class="left w-100 text-white">
                    <h7 class="title text-white">{{ __('Approved News') }}</h7>
        
                    <div class="row text-center mt-2 quad-box">
        
                        <div class="col-6 quad-item">
                            <h5 class="mb-0 text-white">{{ $approved_total }}</h5>
                            <small class="text-white">Total</small>
                        </div>
        
                        <div class="col-6 quad-item">
                            <h5 class="mb-0 text-white">{{ $approved_today }}</h5>
                            <small class="text-white">Today</small>
                        </div>
        
                        <div class="col-6 quad-item">
                            <h5 class="mb-0 text-white">{{ $approved_last_week }}</h5>
                            <small class="text-white">Last Week</small>
                        </div>
        
                        <div class="col-6 quad-item">
                            <h5 class="mb-0 text-white">{{ $approved_last_month }}</h5>
                            <small class="text-white">Last Month</small>
                        </div>
        
                    </div>
        
                    <a href="{{ route('post.approved') }}" class="link mt-2 d-block">
                        {{ __('View All') }}
                    </a>
                </div>
            </div>
        </div>
        @endif



        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg2">
                <div class="left">
                    <h5 class="title">{{ __('Pending News') }}</h5>
                    @if ($user->code == 'admin' && $user->code != 'NA')
                        <span class="number">{{ $author_pending }}</span>
                    @else 
                        <span class="number">{{ $pending_posts }}</span>
                    @endif
                    <a href="{{ route('pending.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-hourglass"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg3">
                <div class="left">
                    <h5 class="title">{{ __('Draft') }}</h5>
                    <span class="number">{{ $drafts }}</span>
                    <a href="{{ route('draft.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-pen-square"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 col-xl-4">
            <div class="mycard bg4">
                <div class="left">
                    <h5 class="title">{{ __('Schedule News') }}</h5>
                    <span class="number">{{ $schedules }}</span>
                    <a href="{{ route('schedule.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
   
        <div class="col-md-6 col-lg-3 col-xl-4">
            <div class="mycard bg6">
                <div class="left">
                    <h5 class="title">{{ __('Polls') }}</h5>
                    <span class="number">{{ $polls}}</span>
                    <a href="{{ route('addPolls.index') }}" class="link">{{ __('View All') }}</a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="fas fa-poll"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->guard('admin')->user()->whereId(1)->exists())
        @if ($user->code != 'admin' && $user->code == 'NA')
            <div class="row row-cards-one">
                <div class="col-md-6 col-xl-4">
                    <div class="card c-info-box-area">
                        <div class="c-info-box box1">
                            <p>{{ App\Models\User::where('is_approve','=',0)->where('is_reader', 0)->count()  }}</p>
                        </div>
                        <div class="c-info-box-content">
                            <h6 class="title">{{ __('Total Pending Reporter') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card c-info-box-area">
                        <div class="c-info-box box2">
                            <p>{{ App\Models\User::where('is_approve','=',1)->count() }}</p>
                        </div>
                        <div class="c-info-box-content">
                            <h6 class="title">{{ __('Total Active Reporter') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card c-info-box-area">
                        <div class="c-info-box box3">
                            <p>{{ App\Models\Subscriber::get()->count()  }}</p>
                        </div>
                        <div class="c-info-box-content">
                            <h6 class="title">{{ __('Total Subscribers') }}</h6>
                            <p class="text">{{ __('All Time') }}</p>
                        </div>
                    </div>
                </div>
            
            </div>
        @endif

    <div class="row row-cards-one">
         @if ($user->code != 'admin' && $user->code == 'NA')
        <div class="col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <h5 class="card-header">{{ __('Recent Reporter(s)') }}</h5>
                <div class="card-body">
                    <div class="my-table-responsiv">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Joined') }}</th>
                                </tr>
                                @foreach($userRole as $role)
                                    @foreach ($role->users()->orderBy('id','desc')->take(10)->get() as $data)
                                        <tr>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <h5 class="card-header">{{ __('Subscribers') }}</h5>
                <div class="card-body">
                    <div class="my-table-responsiv">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __("Sl") }}</th>
                                    <th>{{ __("Email") }}</th>
                                </tr>
                                @foreach($subscribers as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->email }}</td>
                                    </tr>
                                @endforeach
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection

@section('scripts')
@if (Auth::guard('admin')->user()->role_id == 4)
<script>
$(document).ready(function() {
    let remainingSeconds = parseInt($('#duty-timer').data('seconds')) || 0;
    const totalShiftSeconds = 28800; // 8 hours
    
    function formatTime(seconds) {
        let h = Math.floor(seconds / 3600);
        let m = Math.floor((seconds % 3600) / 60);
        let s = seconds % 60;
        
        return [
            h.toString().padStart(2, '0'),
            m.toString().padStart(2, '0'),
            s.toString().padStart(2, '0')
        ].join(':');
    }
    
    function updateProgress(seconds) {
        let pct = (seconds / totalShiftSeconds) * 100;
        pct = Math.max(0, Math.min(100, pct));
        
        const progressBar = $('#duty-progress-bar');
        progressBar.css('width', pct + '%');
        
        // Dynamic colors
        if (seconds <= 3600) { // < 1 hour: Red
            progressBar.css('background-color', '#dc3545');
        } else if (seconds <= 10800) { // < 3 hours: Yellow
            progressBar.css('background-color', '#ffc107');
        } else { // Green
            progressBar.css('background-color', '#28a745');
        }
    }
    
    // Initial update
    $('#duty-timer').text(formatTime(remainingSeconds));
    updateProgress(remainingSeconds);
    
    let shiftInterval = setInterval(function() {
        if (remainingSeconds <= 0) {
            clearInterval(shiftInterval);
            $('#duty-timer').text('00:00:00');
            alert('আপনার দায়িত্ব শিফটের সময় শেষ হয়েছে। আপনি স্বয়ংক্রিয়ভাবে লগআউট হয়ে যাচ্ছেন।');
            window.location.href = "{{ route('admin.logout') }}";
        } else {
            remainingSeconds--;
            $('#duty-timer').text(formatTime(remainingSeconds));
            updateProgress(remainingSeconds);
        }
    }, 1000);
});
</script>
@endif
@endsection