@php
    if (!function_exists('reshape_bengali')) {
        function reshape_bengali($str) {
            if (empty($str)) return '';
            $str = str_replace(
                ["\xe0\xa7\x8b", "\xe0\xa7\x8c"],
                ["\xe0\xa7\x87\xe0\xa6\xbe", "\xe0\xa7\x87\xe0\xa7\x97"],
                $str
            );
            $consonant = '[\x{0995}-\x{09b9}\x{09dc}-\x{09df}\x{09f0}\x{09f1}]';
            $conjunct = '(?:' . $consonant . '\x{09cd})*' . $consonant;
            $left_vowel = '([\x{09bf}\x{09c7}\x{09c8}])';
            $pattern = '/(' . $conjunct . ')' . $left_vowel . '/u';
            return preg_replace($pattern, '$2$1', $str);
        }
    }

    if (!function_exists('utf8_to_entities')) {
        function utf8_to_entities($str) {
            if (empty($str)) return '';
            $entities = '';
            $len = strlen($str);
            for ($i = 0; $i < $len; $i++) {
                $c = ord($str[$i]);
                if ($c < 128) {
                    $entities .= $str[$i];
                } elseif ($c < 224) {
                    $char = (($c - 192) << 6) + (ord($str[++$i]) - 128);
                    $entities .= '&#' . $char . ';';
                } elseif ($c < 240) {
                    $char = (($c - 224) << 12) + ((ord($str[++$i]) - 128) << 6) + (ord($str[++$i]) - 128);
                    $entities .= '&#' . $char . ';';
                } else {
                    $char = (($c - 240) << 18) + ((ord($str[++$i]) - 128) << 12) + ((ord($str[++$i]) - 128) << 6) + (ord($str[++$i]) - 128);
                    $entities .= '&#' . $char . ';';
                }
            }
            return $entities;
        }
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Summary - {{ $formattedMonth }}</title>
    <style>
        @page {
            margin: 15mm 15mm 20mm 15mm;
        }
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ dirname(base_path()) . "/assets/fonts/SolaimanLipi.ttf" }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ dirname(base_path()) . "/assets/fonts/SolaimanLipi.ttf" }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: 'SolaimanLipi', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #1a1a1a;
            font-weight: 700;
        }
        .header p {
            margin: 4px 0 0 0;
            color: #666;
            font-size: 13px;
        }
        footer {
            position: fixed;
            bottom: -10mm;
            left: 0px;
            right: 0px;
            height: 10mm;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eaeaea;
            padding-top: 5px;
        }
        .page-number:after {
            content: counter(page);
        }
        table.list-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.list-table th, table.list-table td {
            border: 1px solid #e0e0e0;
            padding: 10px 14px;
            text-align: left;
            font-size: 12px;
        }
        table.list-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>

    <footer>
        Page <span class="page-number"></span>
    </footer>

    <div class="header">
        <h1>{!! utf8_to_entities(reshape_bengali($gs->title ?? 'Amar Bangla')) !!}</h1>
        <p>Monthly Transaction Summary - {{ $formattedMonth }}</p>
    </div>

    <table class="list-table">
        <thead>
            <tr>
                <th style="width: 10%;">SL</th>
                <th style="width: 50%;">Sector</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 20%;" class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp
            @foreach($categoryTotals as $total)
                <tr>
                    <td>{{ $sl++ }}</td>
                    <td>
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">
                            {!! utf8_to_entities(reshape_bengali($total->category_name)) !!}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $total->type == 'income' ? 'badge-success' : ($total->type == 'expense' ? 'badge-danger' : ($total->type == 'assets' ? 'badge-warning' : 'badge-info')) }}">
                            {{ ucfirst($total->type) }}
                        </span>
                    </td>
                    <td class="text-right" style="font-weight: normal;">
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">&#2547;</span> <strong>{{ number_format($total->total_amount, 2) }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; margin-top: 25px; page-break-inside: avoid;">
        <table style="width: 300px; margin-left: auto; border-collapse: collapse; border: 1px solid #ddd; background-color: #fafafa; font-family: 'SolaimanLipi', Arial, sans-serif;">
            @if(empty($type) || $type === 'income')
                <tr>
                    <td style="padding: 8px 12px; font-weight: bold; border: 1px solid #ddd; color: #495057;">Total Income:</td>
                    <td style="padding: 8px 12px; text-align: right; border: 1px solid #ddd; color: #212529; font-weight: normal;">
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">&#2547;</span> <strong style="font-weight: bold;">{{ number_format($monthlyIncome, 2) }}</strong>
                    </td>
                </tr>
            @endif
            @if(empty($type) || $type === 'expense')
                <tr>
                    <td style="padding: 8px 12px; font-weight: bold; border: 1px solid #ddd; color: #495057;">Total Expense:</td>
                    <td style="padding: 8px 12px; text-align: right; border: 1px solid #ddd; color: #212529; font-weight: normal;">
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">&#2547;</span> <strong style="font-weight: bold;">{{ number_format($monthlyExpense, 2) }}</strong>
                    </td>
                </tr>
            @endif
            @if(empty($type) || $type === 'assets')
                <tr>
                    <td style="padding: 8px 12px; font-weight: bold; border: 1px solid #ddd; color: #495057;">Total Assets:</td>
                    <td style="padding: 8px 12px; text-align: right; border: 1px solid #ddd; color: #212529; font-weight: normal;">
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">&#2547;</span> <strong style="font-weight: bold;">{{ number_format($monthlyAssets, 2) }}</strong>
                    </td>
                </tr>
            @endif
            @if(empty($type) || $type === 'investment')
                <tr>
                    <td style="padding: 8px 12px; font-weight: bold; border: 1px solid #ddd; color: #495057;">Total Investment (Lifetime):</td>
                    <td style="padding: 8px 12px; text-align: right; border: 1px solid #ddd; color: #212529; font-weight: normal;">
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">&#2547;</span> <strong style="font-weight: bold;">{{ number_format($monthlyInvestment, 2) }}</strong>
                    </td>
                </tr>
            @endif
            @if(empty($type))
                <tr style="background-color: #f1f3f5;">
                    <td style="padding: 8px 12px; font-weight: bold; border: 1px solid #ddd; color: #495057;">Net Balance (Income − Expense):</td>
                    <td style="padding: 8px 12px; text-align: right; border: 1px solid #ddd; color: #212529; font-weight: normal;">
                        <span style="font-weight: normal; font-family: 'SolaimanLipi';">&#2547;</span> <strong style="font-weight: bold;">{{ number_format($monthlyIncome - $monthlyExpense, 2) }}</strong>
                    </td>
                </tr>
            @endif
        </table>
    </div>

</body>
</html>
