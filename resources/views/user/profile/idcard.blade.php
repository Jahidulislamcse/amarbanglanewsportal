@extends('layouts.card')

@section('content')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

        }
        .crossed-qr {
            position: relative;
            display: inline-block;
            line-height: 0;
        }
        .qr-cross-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 999;
        }
    </style>

    <?php
    
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Milon\Barcode\DNS1D;
    
    $barcode = new DNS1D();
    $barcode->setStorPath('assets/idcard');
    
    $code = $barcode->getBarcodePNGPath('amarbangla24.news', 'C128');
    
    $url = route('frontend.ourteam', [$data->id, $type]);
    
    if ($type && $type == 2) {
        $title = $data->report_type;
    } else {
        if (isset($data->report_type)) {
            $josn_decode = json_decode($data->report_type, true);
            $title = '';
            if (isset($josn_decode[0])) {
                $title = isset($reportcategories[$josn_decode[0]]) ? $reportcategories[$josn_decode[0]] : '';
            }
        } else {
            $title = '';
        }
    }
    /*if($data->dob){
			$age = date_diff(date_create($data->dob), date_create('today'))->y;
		}else{
			$age = "";
		}*/
    if (isset($data->blood)) {
        $blood = $data->blood;
    } else {
        $blood = 90;
    }
    
    $isRestrictedUser = !(auth()->guard('admin')->check() && in_array(auth()->guard('admin')->user()->id, [1, 54]));
    ?>
    <div id="epaper_sss">
        <div id="frontCard" class="id-card front">
            
             <div class="press-bar">
                <img src="{{ asset('assets/idcard/PRESS BAR.PNG') }}" alt="">
            </div>
            <div class="front-content">
                <img src="{{ asset('assets/idcard/amarbangla logo Design -01.png') }}" class="logo" alt="Logo">
                <div>
                    <h2>Identity Card</h2>
                </div>
                <div class="visualization">
                    <div class="profile-card">
                    </div>
                    <div class="profile-photo">
                        <img src="<?php echo asset('assets/images/admin/' . $data->photo); ?>" alt="Profile Photo">
                    </div>
                </div>
                <div class="fixed">
                    <h2 id="fname">{{ $data->name }} </h2>
                    <p class="role">
                        {{ $title }}
                        @if(!empty($areaName))
                            <br>
                            <span style="font-size:12px;">{{ $areaName }}</span>
                        @endif
                    </p>

                </div>
            </div>
            @if(!$isRestrictedUser)
            <div class="sig">
                <div class="s1" >
                   <img src="{{ asset('assets/idcard/MD Sir ID Card-03.png') }}" alt="">
                </div>
            </div>
            @endif
            <div class="signature">
               
                <h5> Mahmuda Khatun <br> Editor & Publisher</h5>
                <div class="{{ $isRestrictedUser ? 'crossed-qr' : '' }}">
                    {!! QrCode::size(40)->generate($url) !!}
                    @if($isRestrictedUser)
                        <svg class="qr-cross-line" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <line x1="0" y1="100" x2="100" y2="0" stroke="red" stroke-width="4" />
                        </svg>
                    @endif
                </div>
            </div>
            <div class="non">
            </div>
        </div>


        <!-- BACK SIDE -->
         <div id="backCard" class="id-card back">
            <div class="back-header">
                <img src="{{ asset('assets/idcard/Back amarbangla.png') }}" alt="Logo">
            </div>
            <p id="found"><strong>If found please return</strong></p>
            <div class="contact">
                <div class="phn">
                    <div class="p1">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <div class="p2">
                        <p>09643-214620</p>
                        <p>01711-774263</p>
                    </div>
                </div>
                <div class="mail">
                    <div class="p3">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div class="p4">
                        <p>amarbangla24.com.bd</p>
                        <p>amarbangla24media@gmail.com</p>
                    </div>
                </div>
                <div class="location">
                    <div class="p5">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="p6">
                        <p>Kabbokosh Bhaban, <br> Level-5, Suite-10, <br> KarwanBazar, Dhaka-1215</p>
                    </div>
                </div>
            </div>
            <div class="info">
                <div class="p7"><i class="fa-solid fa-note-sticky"></i></div>
                <div class="p8">
                    <p>ID No: ABNW-{{ $data->id }} <br>Cell: {{ $data->phone }} <br>Blood: {{ blood_groups(2, $blood) ? blood_groups(2, $blood) : $data->blood }}
                    </p>
                </div>
            </div>

            <div class="barcode">
                <img src="{{ asset($code) }}" alt="Barcode">
            </div>

            <div class="back-bar">
                <img src="{{ asset('assets/idcard/PRESS BAR.PNG') }}" alt="">
            </div>

            <div class="non2">
            </div>
        </div>
    </div>


    @if(!$isRestrictedUser)
    <div class="print-float no-print">
       
        <a href="javascript:void(0)" id="print_news" class="btn green"> <img width="20px"
                src="{{ asset('assets/images/4208397.png') }}">
            {{ __('PDF') }}
        </a>
    </div>
    @endif
@endsection
