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
    <title>Transaction Report - {{ $formattedMonth }}</title>
    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ dirname(base_path()) . "/assets/fonts/SolaimanLipi.ttf" }}') format('truetype');
            font-weight: normal;
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
            margin-bottom: 20px;
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
        table.list-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.list-table th, table.list-table td {
            border: 1px solid #e0e0e0;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
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
        .category-header {
            background-color: #f1f3f5;
            font-weight: bold;
            font-size: 12px;
            color: #495057;
        }
        .summary-row {
            background-color: #fafafa;
            font-weight: bold;
            color: #212529;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{!! utf8_to_entities(reshape_bengali($gs->title ?? 'Amar Bangla')) !!}</h1>
        <p>Transaction Report - {{ $formattedMonth }}</p>
    </div>

    <table style="width: 100%; border: none; margin-bottom: 25px; border-collapse: collapse;">
        <tr>
            <td style="width: 32%; border: 1px solid #c3e6cb; border-left: 5px solid #28a745; border-radius: 4px; padding: 12px; background-color: #f4faf6; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #155724; font-weight: bold; margin-bottom: 5px;">Monthly Income</div>
                <div style="font-size: 16px; font-weight: bold; color: #155724;"><span style="font-family: DejaVu Sans;">&#2547;</span> {{ number_format($monthlyIncome, 2) }}</div>
            </td>
            <td style="width: 2%; border: none;"></td>
            <td style="width: 32%; border: 1px solid #f5c6cb; border-left: 5px solid #dc3545; border-radius: 4px; padding: 12px; background-color: #fdf3f4; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #721c24; font-weight: bold; margin-bottom: 5px;">Monthly Expense</div>
                <div style="font-size: 16px; font-weight: bold; color: #721c24;"><span style="font-family: DejaVu Sans;">&#2547;</span> {{ number_format($monthlyExpense, 2) }}</div>
            </td>
            <td style="width: 2%; border: none;"></td>
            <td style="width: 32%; border: 1px solid #bee5eb; border-left: 5px solid #17a2b8; border-radius: 4px; padding: 12px; background-color: #f3fafd; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #0c5460; font-weight: bold; margin-bottom: 5px;">Monthly Balance</div>
                <div style="font-size: 16px; font-weight: bold; color: #0c5460;"><span style="font-family: DejaVu Sans;">&#2547;</span> {{ number_format($monthlyIncome - $monthlyExpense, 2) }}</div>
            </td>
        </tr>
    </table>

    <table class="list-table">
        <thead>
            <tr>
                <th style="width: 6%;">SL</th>
                <th style="width: 14%;">Date</th>
                <th style="width: 20%;">Title</th>
                <th style="width: 26%;">Bearer</th>
                <th style="width: 14%;">Category</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 10%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = 1;
                $currentCategoryName = null;
                $currentCategoryId = null;
            @endphp
            @foreach($transactions as $transaction)
                @if($categoryId === 'all')
                    @php
                        $rowCategoryId = $transaction->category_id;
                        $rowCategoryName = optional($transaction->trcategory)->name ?? 'Uncategorized';
                    @endphp
                    @if($rowCategoryName !== $currentCategoryName)
                        @if($currentCategoryName !== null)
                            <tr class="summary-row">
                                <td colspan="6" class="text-right">Total {!! utf8_to_entities(reshape_bengali($currentCategoryName)) !!}:</td>
                                <td class="text-right"><span style="font-family: DejaVu Sans;">&#2547;</span> {{ number_format($categoryTotals[$currentCategoryId] ?? 0, 2) }}</td>
                            </tr>
                        @endif
                        @php
                            $currentCategoryName = $rowCategoryName;
                            $currentCategoryId = $rowCategoryId;
                            $sl = 1;
                        @endphp
                        <tr class="category-header">
                            <td colspan="7">
                                Folder: {!! utf8_to_entities(reshape_bengali($currentCategoryName)) !!}
                            </td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td>{{ $sl++ }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td>
                        {!! utf8_to_entities(reshape_bengali($transaction->title)) !!}
                        @if($transaction->order_id)
                            <span style="font-size: 8px; color: #555; background-color: #e9ecef; padding: 1px 3px; border-radius: 2px; font-weight: bold; margin-left: 3px;">
                                #{{ $transaction->order_id }}
                            </span>
                        @endif
                    </td>
                    <td>{!! utf8_to_entities(reshape_bengali($transaction->bearer)) !!}</td>
                    <td>{!! utf8_to_entities(reshape_bengali(optional($transaction->trcategory)->name ?? 'Uncategorized')) !!}</td>
                    <td>
                        <span class="badge {{ $transaction->type == 'income' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </td>
                    <td class="text-right"><span style="font-family: DejaVu Sans;">&#2547;</span> {{ number_format($transaction->amount, 2) }}</td>
                </tr>
            @endforeach

            @if($transactions->isNotEmpty())
                @php
                    $lastTransaction = $transactions->last();
                    $lastCategoryId = $lastTransaction->category_id;
                    $lastCategoryName = optional($lastTransaction->trcategory)->name ?? 'Uncategorized';
                @endphp
                <tr class="summary-row">
                    <td colspan="6" class="text-right">Total {!! utf8_to_entities(reshape_bengali($categoryId === 'all' ? $lastCategoryName : (optional($transactions->first()->trcategory)->name ?? 'Uncategorized'))) !!}:</td>
                    <td class="text-right"><span style="font-family: DejaVu Sans;">&#2547;</span> {{ number_format($categoryTotals[$categoryId === 'all' ? $lastCategoryId : $categoryId] ?? 0, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
