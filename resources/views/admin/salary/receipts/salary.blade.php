@php
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
<html lang="bn">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Salary Slip - {!! utf8_to_entities($salary->employee->name) !!}</title>
    
    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('https://raw.githubusercontent.com/shiftenterdev/bangla-font/master/SolaimanLipi.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'SolaimanLipi', sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            border: 2px solid #ddd;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            background: #fff;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #337ab7;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .company-name {
            font-size: 26px;
            font-weight: bold;
            color: #337ab7;
            text-transform: uppercase;
            margin: 0;
        }

        .company-sub {
            font-size: 12px;
            color: #666;
            margin: 2px 0 0 0;
        }

        .receipt-title {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            width: 30%;
        }

        .info-value {
            color: #333;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .details-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #555;
        }

        .details-table td {
            border: 1px solid #ddd;
            padding: 12px 10px;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .footer-table {
            width: 100%;
            margin-top: 60px;
        }

        .footer-table td {
            text-align: center;
            width: 50%;
            vertical-align: bottom;
        }

        .signature-line {
            width: 80%;
            border-top: 1px solid #999;
            margin: auto;
            padding-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .payment-method {
            margin-top: 15px;
            font-size: 12px;
            color: #555;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            border: 1px dashed #ddd;
        }
    </style>
</head>
@php
    $logoPath = dirname(base_path()) . '/assets/amarbangla.png';
    $logoData = '';
    if (file_exists($logoPath)) {
        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
@endphp

<div class="container">
    <table class="header-table">
        <tr>
            @if ($logoData)
                <td style="width: 15%; text-align: left; padding-right: 15px;">
                    <img src="{{ $logoData }}" alt="Logo" style="max-height: 55px; display: block;">
                    <p class="company-sub">amarbangla24.com.bd </p>

                </td>
            @endif

            <td class="receipt-title">
                Salary Payslip
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-label">Employee Name:</td>
            <td class="info-value">{!! utf8_to_entities($salary->employee->name) !!}</td>
            <td class="info-label">Payslip ID:</td>
            <td class="info-value">#SLY-{{ str_pad($salary->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="info-label">Designation:</td>
            <td class="info-value">{!! utf8_to_entities($salary->employee->designation->name ?? '-') !!}</td>
            <td class="info-label">Payroll Period:</td>
            <td class="info-value">{{ date('F', mktime(0, 0, 0, $salary->month, 10)) }}, {{ $salary->year }}</td>
        </tr>
        <tr>
            <td class="info-label">Payment Date:</td>
            <td class="info-value">{{ \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') }}</td>
            <td class="info-label">Status:</td>
            <td class="info-value"><strong
                    style="color: #4cae4c; text-transform: uppercase;">{{ $salary->status }}</strong></td>
        </tr>
    </table>

    <table class="details-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right" style="width: 30%;">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td class="text-right">BDT {{ number_format($salary->basic_salary, 2) }}</td>
            </tr>
            @if ($salary->advance_paid > 0)
                <tr>
                    <td style="color: #d9534f;">Advance Payment Deduction</td>
                    <td class="text-right" style="color: #d9534f;">-BDT {{ number_format($salary->advance_paid, 2) }}
                    </td>
                </tr>
            @endif
            <tr class="total-row">
                <td>Net Paid Salary</td>
                <td class="text-right">BDT {{ number_format($salary->salary_paid, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if ($salary->employee->account_details)
        <div class="payment-method">
            <strong>Disbursed To Account:</strong><br>
            <span style="white-space: pre-line;">{!! utf8_to_entities($salary->employee->account_details) !!}</span>
        </div>
    @endif

    <table class="footer-table">
        <tr>
            <td>
                <br><br><br>
                <div class="signature-line">Employee Signature</div>
            </td>
            <td>
                <br><br><br>
                <div class="signature-line">Authorized Signature</div>
            </td>
        </tr>
    </table>
</div>

</body>

</html>
