@extends('layouts.visiting_card')

@section('content')
    <?php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    
    $url = route('frontend.ourteam', [$data->id, $type]);
    
    if ($type && $type == 2) {
        $title = $data->report_type;
    } else {
        if (isset($data->report_type)) {
            $json_decode = json_decode($data->report_type, true);
            $title = '';
            if (isset($json_decode[0])) {
                $title = isset($reportcategories[$json_decode[0]]) ? $reportcategories[$json_decode[0]] : '';
            }
        } else {
            $title = '';
        }
    }

    $isRestrictedUser = !(auth()->guard('admin')->check() && in_array(auth()->guard('admin')->user()->id, [1, 55]));
    ?>

    <div class="visiting-card-container">
        <!-- FRONT SIDE -->
        <div id="visitingFrontCard" class="visiting-card front">
            <!-- QR CODE -->
            <div class="v-qr-code {{ $isRestrictedUser ? 'crossed-qr' : '' }}">
                {!! QrCode::size(140)->generate($url) !!}
                @if($isRestrictedUser)
                    <svg class="qr-cross-line" viewBox="0 0 100 100" preserveAspectRatio="none" style="position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:99;">
                        <line x1="0" y1="100" x2="100" y2="0" stroke="red" stroke-width="4" />
                    </svg>
                @endif
            </div>

            <!-- NAME & TITLE -->
            <div class="v-name-box">
                <h1 class="v-name">{{ $data->name }}</h1>
                <div class="v-role">
                    {{ $title }}
                    @if(!empty($areaName))
                        <span>({{ $areaName }})</span>
                    @endif
                </div>
            </div>

            <!-- CONTACT DETAILS -->
            <div class="v-contact-phone">
                {{ $data->phone ? $data->phone : '-' }}
            </div>
            <div class="v-contact-email">
                {{ $data->email ? $data->email : '-' }}
            </div>
            <div class="v-contact-address">
                {{ !empty($fullAddress) ? $fullAddress : '-' }}
            </div>
        </div>

        <!-- BACK SIDE -->
        <div id="visitingBackCard" class="visiting-card back">
        </div>
    </div>

    <!-- DOWNLOAD BUTTON -->
    <div class="print-btn-wrapper no-print">
        <button id="download_visiting_pdf" class="btn-download-pdf">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </div>
@endsection
