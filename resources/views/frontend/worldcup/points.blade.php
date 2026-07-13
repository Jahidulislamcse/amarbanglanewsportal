@extends('layouts.front_custom')

@section('contents')
    <style>
        .worldcup-header {
            text-align: center;
            margin: 20px 0 30px;
        }

        .worldcup-header h2 {
            font-weight: 700;
            color: #CD1D23;
            margin-bottom: 8px;
        }

        .worldcup-header p {
            color: #777;
            margin: 0;
        }

        .group-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .08);
            margin-bottom: 25px;
            transition: .3s;
        }

        .group-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
        }

        .group-title {
            background: linear-gradient(135deg, #CD1D23, #ff4d4d);
            color: #fff;
            padding: 12px 15px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }

        .wc-table {
            margin-bottom: 0;
        }

        .wc-table th {
            background: #f8f9fa;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            border: none !important;
        }

        .wc-table td {
            vertical-align: middle !important;
            text-align: center;
            font-size: 13px;
        }

        .wc-team {
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
        }

        .wc-team img {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 1px solid #ddd;
        }

        .rank-badge {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
        }

        .rank-1 {
            background: #28a745;
        }

        .rank-2 {
            background: #17a2b8;
        }

        .rank-other {
            background: #adb5bd;
        }

        .qualify {
            background: #f3fff4;
        }

        .points {
            font-size: 15px;
            font-weight: 700;
            color: #CD1D23;
        }

        .group-footer {
            background: #fafafa;
            padding: 10px;
            font-size: 12px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
        }

        @media(max-width:768px) {

            .group-title {
                font-size: 16px;
            }

            .wc-table th,
            .wc-table td {
                font-size: 11px;
                padding: 5px;
            }

            .wc-team img {
                width: 22px;
                height: 22px;
            }

            .wc-team span {
                font-size: 11px;
            }
        }
    </style>

    <style>
        .match-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
            background: #fff;
        }

        .match-header {
            padding: 14px 20px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
        }

        .today-header {
            background: linear-gradient(135deg, #28a745, #42d96d);
        }

        .tomorrow-header {
            background: linear-gradient(135deg, #007bff, #4da3ff);
        }

        .fixture-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }

        .fixture-item:last-child {
            border-bottom: none;
        }

        .fixture-time {
            width: 90px;
            text-align: center;
            font-weight: 700;
            color: #CD1D23;
            line-height: 1.4;
        }

        .fixture-match {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
        }

        .fixture-team {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .fixture-team img {
            width: 20px;
            height: 15px;
            object-fit: cover;
            border-radius: 3px;
            border: 1px solid #ddd;
        }

        .fixture-vs {
            color: #CD1D23;
            font-weight: 700;
        }

        .group-badge {
            background: #CD1D23;
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .schedule-btn {
            border: none;
            width: 100%;
            background: linear-gradient(135deg, #CD1D23, #ff4d4d);
            color: #fff;
            font-weight: 700;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px;
        }

        .schedule-group {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .08);
        }

        .schedule-group-header {
            background: linear-gradient(135deg, #28a745, #42d96d);
            color: #fff;
            padding: 12px 15px;
            font-weight: 700;
            font-size: 17px;
        }

        .schedule-table {
            margin-bottom: 0;
        }

        .schedule-table th {
            background: #f8f9fa;
            text-align: center;
            font-size: 13px;
        }

        .schedule-table td {
            text-align: center;
            vertical-align: middle;
        }

        .schedule-team {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 600;
        }

        .schedule-team img {
            width: 20px;
            height: 15px;
            border: 1px solid #ddd;
        }

        .match-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-finished {
            background: #28a745;
            color: #fff;
        }

        .status-scheduled {
            background: #ffc107;
            color: #000;
        }

        @media(max-width:768px) {

            .fixture-item {
                flex-direction: column;
                gap: 10px;
            }

            .fixture-match {
                flex-wrap: wrap;
            }

            .fixture-time {
                width: auto;
            }

            .schedule-table {
                font-size: 12px;
            }

            .schedule-team {
                flex-wrap: wrap;
            }
        }

        .match-list-scroll {
            max-height: 180px;
            overflow-y: auto;
        }

        .match-list-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .match-list-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .match-list-scroll::-webkit-scrollbar-track {
            background: #f5f5f5;
        }

        .full_match-list-scroll {
            max-height: 350px;
            overflow-y: auto;
        }

        .full_match-list-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .full_match-list-scroll::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .full_match-list-scroll::-webkit-scrollbar-track {
            background: #f5f5f5;
        }

        .wc-scrollable-body {
            max-height: 380px;
            overflow-y: auto;
        }
        .wc-scrollable-body::-webkit-scrollbar {
            width: 6px;
        }
        .wc-scrollable-body::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        .wc-scrollable-body::-webkit-scrollbar-track {
            background: #f5f5f5;
        }

        @media (max-width: 768px) {
            .row.mb-4>div {
                margin-bottom: 15px;
            }

            .row.mb-4>div:last-child {
                margin-bottom: 0;
            }
        }
    </style>

    @php

        $teamsBn = [
            'Argentina' => 'আর্জেন্টিনা',
            'Brazil' => 'ব্রাজিল',
            'England' => 'ইংল্যান্ড',
            'France' => 'ফ্রান্স',
            'Germany' => 'জার্মানি',
            'Spain' => 'স্পেন',
            'Portugal' => 'পর্তুগাল',
            'Netherlands' => 'নেদারল্যান্ডস',
            'Belgium' => 'বেলজিয়াম',
            'Croatia' => 'ক্রোয়েশিয়া',
            'Uruguay' => 'উরুগুয়ে',
            'Mexico' => 'মেক্সিকো',
            'USA' => 'যুক্তরাষ্ট্র',
            'Canada' => 'কানাডা',
            'Japan' => 'জাপান',
            'South Korea' => 'দক্ষিণ কোরিয়া',
            'Saudi Arabia' => 'সৌদি আরব',
            'Australia' => 'অস্ট্রেলিয়া',
            'Morocco' => 'মরক্কো',
            'Senegal' => 'সেনেগাল',
            'Tunisia' => 'তিউনিসিয়া',
            'Switzerland' => 'সুইজারল্যান্ড',
            'Cameroon' => 'ক্যামেরুন',
            'Ghana' => 'ঘানা',
            'Iran' => 'ইরান',
            'Qatar' => 'কাতার',
            'Ecuador' => 'ইকুয়েডর',
            'Poland' => 'পোল্যান্ড',
            'Denmark' => 'ডেনমার্ক',
            'Wales' => 'ওয়েলস',
            'Costa Rica' => 'কোস্টারিকা',
            'Serbia' => 'সার্বিয়া',

            // Missing teams
            'Czech Republic' => 'চেক প্রজাতন্ত্র',
            'South Africa' => 'দক্ষিণ আফ্রিকা',
            'Bosnia' => 'বসনিয়া',
            'Haiti' => 'হাইতি',
            'Scotland' => 'স্কটল্যান্ড',
            'Paraguay' => 'প্যারাগুয়ে',
            'Turkey' => 'তুরস্ক',
            'Ivory Coast' => 'আইভরি কোস্ট',
            'Curacao' => 'কুরাসাও',
            'Sweden' => 'সুইডেন',
            'Egypt' => 'মিশর',
            'New Zealand' => 'নিউজিল্যান্ড',
            'Cape Verde' => 'কেপ ভার্দে',
            'Iraq' => 'ইরাক',
            'Norway' => 'নরওয়ে',
            'Algeria' => 'আলজেরিয়া',
            'Austria' => 'অস্ট্রিয়া',
            'Jordan' => 'জর্ডান',
            'Colombia' => 'কলম্বিয়া',
            'DR Congo' => 'ডিআর কঙ্গো',
            'Uzbekistan' => 'উজবেকিস্তান',
            'Panama' => 'পানামা',
        ];
        $flags = [
            // Group A
            'Mexico' => 'mx',
            'South Korea' => 'kr',
            'Czech Republic' => 'cz',
            'South Africa' => 'za',

            // Group B
            'Bosnia' => 'ba',
            'Canada' => 'ca',
            'Qatar' => 'qa',
            'Switzerland' => 'ch',

            // Group C
            'Brazil' => 'br',
            'Haiti' => 'ht',
            'Morocco' => 'ma',
            'Scotland' => 'gb-sct',

            // Group D
            'Australia' => 'au',
            'Paraguay' => 'py',
            'Turkey' => 'tr',
            'USA' => 'us',

            // Group E
            'Ivory Coast' => 'ci',
            'Curacao' => 'cw',
            'Ecuador' => 'ec',
            'Germany' => 'de',

            // Group F
            'Japan' => 'jp',
            'Netherlands' => 'nl',
            'Sweden' => 'se',
            'Tunisia' => 'tn',

            // Group G
            'Belgium' => 'be',
            'Egypt' => 'eg',
            'Iran' => 'ir',
            'New Zealand' => 'nz',

            // Group H
            'Cape Verde' => 'cv',
            'Saudi Arabia' => 'sa',
            'Spain' => 'es',
            'Uruguay' => 'uy',

            // Group I
            'France' => 'fr',
            'Iraq' => 'iq',
            'Norway' => 'no',
            'Senegal' => 'sn',

            // Group J
            'Algeria' => 'dz',
            'Argentina' => 'ar',
            'Austria' => 'at',
            'Jordan' => 'jo',

            // Group K
            'Colombia' => 'co',
            'DR Congo' => 'cd',
            'Portugal' => 'pt',
            'Uzbekistan' => 'uz',

            // Group L
            'Croatia' => 'hr',
            'England' => 'gb-eng',
            'Ghana' => 'gh',
            'Panama' => 'pa',
        ];

        function bnNumber($number)
        {
            return str_replace(
                ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
                ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
                $number,
            );
        }

    @endphp

    @php
        $banglaMonths = [
            'January' => 'জানুয়ারি',
            'February' => 'ফেব্রুয়ারি',
            'March' => 'মার্চ',
            'April' => 'এপ্রিল',
            'May' => 'মে',
            'June' => 'জুন',
            'July' => 'জুলাই',
            'August' => 'আগস্ট',
            'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর',
            'November' => 'নভেম্বর',
            'December' => 'ডিসেম্বর',
        ];

        $tomorrow = \Carbon\Carbon::tomorrow()->timezone('Asia/Dhaka');

        $monthEn = $tomorrow->format('F');

        $tomorrowDateBn =
            bnNumber($tomorrow->format('d')) .
            ' ' .
            ($banglaMonths[$monthEn] ?? $monthEn) .
            ' ' .
            bnNumber($tomorrow->format('Y'));
    @endphp

    <div class="container">

        @php

            $todayMatches = collect($matches)->filter(function ($match) {
                return \Carbon\Carbon::parse($match->match_date)->isToday();
            });

            $tomorrowMatches = collect($matches)->filter(function ($match) {
                return \Carbon\Carbon::parse($match->match_date)->isTomorrow();
            });

            $groupedMatches = collect($matches)->sortBy('match_date')->groupBy('group_name')->forget('Round of 32');

            $groupBn = [
                'Group A' => 'গ্রুপ এ',
                'Group B' => 'গ্রুপ বি',
                'Group C' => 'গ্রুপ সি',
                'Group D' => 'গ্রুপ ডি',
                'Group E' => 'গ্রুপ ই',
                'Group F' => 'গ্রুপ এফ',
                'Group G' => 'গ্রুপ জি',
                'Group H' => 'গ্রুপ এইচ',
                'Group I' => 'গ্রুপ আই',
                'Group J' => 'গ্রুপ জে',
                'Group K' => 'গ্রুপ কে',
                'Group L' => 'গ্রুপ এল',
            ];

        @endphp

        <div class="worldcup-header">
            <h2>⚽ ফিফা বিশ্বকাপ ২০২৬ </h2>
        </div>

        <!-- ==================== Round of 32 Section ==================== -->
        @php
            $r32MatchesList = collect($matches)->where('group_name', 'Round of 32')->sortBy('match_date');
            $r32StandingsList = isset($groups['Round of 32']) ? $groups['Round of 32'] : collect([]);
        @endphp

        <div class="row mb-5" style="margin-bottom: 60px !important;">

            <div class="col-md-6 mb-3">

                <div class="match-card">

                    <div class="match-header today-header">
                        🔥 আজকের ম্যাচ (বাংলাদেশ সময়)
                    </div>

                    <div class="match-list-scroll">
                        @forelse($todayMatches as $match)
                            <div class="fixture-item">

                                <div class="fixture-time">

                                    {{ bnNumber(\Carbon\Carbon::parse($match->match_date)->format('h:i')) }}

                                    {{ \Carbon\Carbon::parse($match->match_date)->format('A') }}

                                </div>

                                <div class="fixture-match">

                                    <div class="fixture-team">
                                        <img src="https://flagcdn.com/48x36/{{ $flags[$match->home_team] ?? 'un' }}.png">
                                        {{ $teamsBn[$match->home_team] ?? $match->home_team }}
                                    </div>

                                    <div class="fixture-vs">
                                        VS
                                    </div>

                                    <div class="fixture-team">
                                        <img src="https://flagcdn.com/48x36/{{ $flags[$match->away_team] ?? 'un' }}.png">
                                        {{ $teamsBn[$match->away_team] ?? $match->away_team }}
                                    </div>

                                </div>

                                <span class="group-badge">
                                    {{ $groupBn[$match->group_name] ?? $match->group_name }}
                                </span>

                            </div>

                        @empty

                            <div class="d-flex align-items-center justify-content-center text-muted fw-bold w-100 text-center" style="height: 180px;">
                                আজ কোনো ম্যাচ নেই।
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="col-md-6 mb-3">

                <div class="match-card">


                    <div class="match-header tomorrow-header">
                        আগামীকালের ম্যাচ ({{ $tomorrowDateBn }} বাংলাদেশ সময়)
                    </div>

                    <div class="match-list-scroll">
                        @forelse($tomorrowMatches as $match)
                            <div class="fixture-item">

                                <div class="fixture-time">

                                    {{ bnNumber(\Carbon\Carbon::parse($match->match_date)->format('h:i')) }}

                                    {{ \Carbon\Carbon::parse($match->match_date)->format('A') }}

                                </div>

                                <div class="fixture-match">

                                    <div class="fixture-team">
                                        <img src="https://flagcdn.com/48x36/{{ $flags[$match->home_team] ?? 'un' }}.png">
                                        {{ $teamsBn[$match->home_team] ?? $match->home_team }}
                                    </div>

                                    <div class="fixture-vs">
                                        VS
                                    </div>

                                    <div class="fixture-team">
                                        <img src="https://flagcdn.com/48x36/{{ $flags[$match->away_team] ?? 'un' }}.png">
                                        {{ $teamsBn[$match->away_team] ?? $match->away_team }}
                                    </div>

                                </div>

                                <span class="group-badge">
                                    {{ $groupBn[$match->group_name] ?? $match->group_name }}
                                </span>

                            </div>

                        @empty

                            <div class="d-flex align-items-center justify-content-center text-muted fw-bold w-100 text-center" style="height: 180px;">
                                আগামীকাল কোনো ম্যাচ নেই।
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>

        @if($r32MatchesList->isNotEmpty() || $r32StandingsList->isNotEmpty())
            <div class="row mb-5 mt-4">
                <!-- Round of 32 Standings -->
                @if($r32StandingsList->isNotEmpty())
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm" style="border:none; border-radius:16px; overflow:hidden; box-shadow: 0 5px 20px rgba(0,0,0,.08);">
                            <div class="card-header text-white fw-bold fs-5 text-center" style="background: linear-gradient(135deg, #17a2b8, #007bff); border: none; padding: 18px 24px !important;">
                                📊 রাউন্ড অফ ৩২ পয়েন্ট টেবিল (Round of 32 Standings)
                            </div>
                            <div class="card-body p-0 wc-scrollable-body">
                                <div class="table-responsive">
                                    <table class="table wc-table align-middle text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>র‍্যাংক</th>
                                                <th>দল</th>
                                                <th>খেলেছে</th>
                                                <th>জয়</th>
                                                <th>ড্র</th>
                                                <th>হার</th>
                                                <th>জিডি</th>
                                                <th>পয়েন্ট</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($r32StandingsList as $team)
                                                <tr class="qualify">
                                                    <td>
                                                        <span class="rank-badge rank-1">
                                                            {{ bnNumber($team->rank) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="wc-team d-inline-flex align-items-center gap-2">
                                                            <img src="https://flagcdn.com/48x36/{{ $flags[$team->team_name] ?? 'un' }}.png" style="width:20px; height:15px;">
                                                            <span class="fw-bold" style="font-size:12px;">{{ $teamsBn[$team->team_name] ?? $team->team_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ bnNumber($team->played) }}</td>
                                                    <td>{{ bnNumber($team->win) }}</td>
                                                    <td>{{ bnNumber($team->draw) }}</td>
                                                    <td>{{ bnNumber($team->lose) }}</td>
                                                    <td>{{ bnNumber($team->goal_diff) }}</td>
                                                    <td class="points">{{ bnNumber($team->points) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Round of 32 Matches -->
                @if($r32MatchesList->isNotEmpty())
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm" style="border:none; border-radius:16px; overflow:hidden; box-shadow: 0 5px 20px rgba(0,0,0,.08);">
                            <div class="card-header text-white fw-bold fs-5 text-center" style="background: linear-gradient(135deg, #CD1D23, #ff4d4d); border: none; padding: 18px 24px !important;">
                                🏆 রাউন্ড অফ ৩২ ম্যাচ সূচি (Round of 32 Matches)
                            </div>
                            <div class="card-body p-0 wc-scrollable-body">
                                <div class="table-responsive">
                                    <table class="table schedule-table align-middle text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>তারিখ ও সময়</th>
                                                <th>ম্যাচ</th>
                                                <th>স্ট্যাটাস</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($r32MatchesList as $match)
                                                <tr>
                                                    <td style="font-size:11px; white-space:nowrap; padding: 12px 8px;">
                                                        {{ bnNumber(\Carbon\Carbon::parse($match->match_date)->timezone('Asia/Dhaka')->format('d M')) }}<br>
                                                        <span class="text-muted">{{ bnNumber(\Carbon\Carbon::parse($match->match_date)->format('h:i')) }} {{ \Carbon\Carbon::parse($match->match_date)->format('A') }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                                            <span class="fw-bold" style="font-size:12px;">{{ $teamsBn[$match->home_team] ?? $match->home_team }}</span>
                                                            @if($match->status === 'finished')
                                                                <span class="badge bg-dark px-2 py-1">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                                            @else
                                                                <span class="text-danger fw-bold">VS</span>
                                                            @endif
                                                            <span class="fw-bold" style="font-size:12px;">{{ $teamsBn[$match->away_team] ?? $match->away_team }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($match->status === 'finished')
                                                            <span class="badge bg-success" style="font-size:10px;">শেষ</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark" style="font-size:10px;">নির্ধারিত</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- <button id="scheduleToggle" class="schedule-btn mb-3" style="margin-bottom: 20px; margin-top: 20px;">

            <span>
                ⚽ ফিফা বিশ্বকাপ ২০২৬ (গ্রুপ পর্ব) পূর্ণ ম্যাচ সূচি
            </span>

            <span id="scheduleArrow">▼</span>

        </button> --}}

        <div class="full_match-list-scroll" id="worldCupSchedule" style="display:none; margin-bottom: 20px; margin-top: 20px;">

            @foreach ($groupedMatches as $group => $groupMatches)
                <div class="schedule-group">

                    <div class="schedule-group-header">
                        {{ $groupBn[$group] ?? $group }}
                    </div>

                    <div class="table-responsive">

                        <table class="table schedule-table">

                            <thead>

                                <tr>
                                    <th>তারিখ</th>
                                    <th>সময়</th>
                                    <th>ম্যাচ</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($groupMatches as $match)
                                    <tr>

                                        <td>

                                            {{ bnNumber(\Carbon\Carbon::parse($match->match_date)->timezone('Asia/Dhaka')->format('d')) }}

                                            {{ bnNumber(\Carbon\Carbon::parse($match->match_date)->timezone('Asia/Dhaka')->translatedFormat('M Y')) }}

                                        </td>

                                        <td>

                                            {{ bnNumber(\Carbon\Carbon::parse($match->match_date)->format('h:i')) }}

                                            {{ \Carbon\Carbon::parse($match->match_date)->format('A') }}

                                        </td>

                                        <td>

                                            <div class="schedule-team">

                                                <img
                                                    src="https://flagcdn.com/48x36/{{ $flags[$match->home_team] ?? 'un' }}.png">

                                                {{ $teamsBn[$match->home_team] ?? $match->home_team }}

                                                <span class="text-danger fw-bold">
                                                    VS
                                                </span>

                                                {{ $teamsBn[$match->away_team] ?? $match->away_team }}

                                                <img
                                                    src="https://flagcdn.com/48x36/{{ $flags[$match->away_team] ?? 'un' }}.png">

                                            </div>

                                        </td>

                                        <td>

                                            @if ($match->status == 'finished')
                                                <span class="match-status status-finished">
                                                    শেষ
                                                </span>
                                            @else
                                                <span class="match-status status-scheduled">
                                                    নির্ধারিত
                                                </span>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>
            @endforeach

        </div>

        <div class="worldcup-header" style="margin-top: 50px;">
            <h2>পয়েন্ট টেবিল (গ্রুপ পর্ব)</h2>
            <p>গ্রুপ পর্বের সর্বশেষ অবস্থান</p>
        </div>
        <div class="row">

            @foreach ($groups as $group => $teams)
                @if($group === 'Round of 32') @continue @endif
                <div class="col-md-6">

                    <div class="group-card">

                        <div class="group-title">
                            @php
                                $groupBn = [
                                    'Group A' => 'গ্রুপ এ',
                                    'Group B' => 'গ্রুপ বি',
                                    'Group C' => 'গ্রুপ সি',
                                    'Group D' => 'গ্রুপ ডি',
                                    'Group E' => 'গ্রুপ ই',
                                    'Group F' => 'গ্রুপ এফ',
                                    'Group G' => 'গ্রুপ জি',
                                    'Group H' => 'গ্রুপ এইচ',
                                    'Group I' => 'গ্রুপ আই',
                                    'Group J' => 'গ্রুপ জে',
                                    'Group K' => 'গ্রুপ কে',
                                    'Group L' => 'গ্রুপ এল',
                                ];
                            @endphp

                            {{ $groupBn[$group] ?? $group }}
                        </div>

                        <div class="table-responsive">

                            <table class="table wc-table">

                                <thead>
                                    <tr>
                                        <th>র‍্যাংক</th>
                                        <th>দল</th>
                                        <th>খেলেছে</th>
                                        <th>জয়</th>
                                        <th>ড্র</th>
                                        <th>হার</th>
                                        <th>গো.ব্যা</th>
                                        <th>পয়েন্ট</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($teams as $team)
                                        <tr class="{{ $team->rank <= 2 ? 'qualify' : '' }}">

                                            <td>
                                                <span
                                                    class="rank-badge {{ $team->rank == 1 ? 'rank-1' : ($team->rank == 2 ? 'rank-2' : 'rank-other') }}">
                                                    {{ bnNumber($team->rank) }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="wc-team">
                                                    <img
                                                        src="https://flagcdn.com/48x36/{{ $flags[$team->team_name] ?? 'un' }}.png">
                                                    <span>{{ $teamsBn[$team->team_name] ?? $team->team_name }}</span>
                                                </div>
                                            </td>

                                            <td>{{ bnNumber($team->played) }}</td>
                                            <td>{{ bnNumber($team->win) }}</td>
                                            <td>{{ bnNumber($team->draw) }}</td>
                                            <td>{{ bnNumber($team->lose) }}</td>
                                            <td>{{ bnNumber($team->goal_diff) }}</td>

                                            <td class="points">
                                                {{ bnNumber($team->points) }}
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <div class="group-footer">
                            ✅ শীর্ষ ২ দল পরবর্তী পর্বে উত্তীর্ণ হবে
                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const btn = document.getElementById('scheduleToggle');
            const schedule = document.getElementById('worldCupSchedule');
            const arrow = document.getElementById('scheduleArrow');

            btn.addEventListener('click', function() {

                if (schedule.style.display === 'none') {

                    schedule.style.display = 'block';
                    arrow.innerHTML = '▲';

                } else {

                    schedule.style.display = 'none';
                    arrow.innerHTML = '▼';

                }

            });

        });
    </script>
@endsection
