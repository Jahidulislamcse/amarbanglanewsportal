<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Account Suspended</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#f8f9fa;
            font-family: Arial, sans-serif;
        }

        .box {
            background:#fff;
            padding:35px;
            border-radius:12px;
            box-shadow:0 5px 20px rgba(0,0,0,.12);
            text-align:center;
            max-width:420px;
            width:100%;
        }

        h2 {
            color:#dc3545;
            margin-bottom:10px;
        }

        .btn {
            display:inline-block;
            margin-top:15px;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
        }

        .btn-pay {
            background:#28a745;
            color:#fff;
        }

        .btn-pay:hover {
            background:#218838;
        }

        .btn-contact {
            background:#007bff;
            color:#fff;
        }

        .btn-contact:hover {
            background:#0069d9;
        }

        .admin {
            margin-top:15px;
            padding:10px;
            border:1px dashed #ddd;
            border-radius:6px;
            text-align:left;
        }

        .phone {
            font-size:15px;
            font-weight:bold;
            color:#198754;
        }

        .note {
            font-size:13px;
            color:#666;
            margin-top:10px;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>অ্যাকাউন্ট সাময়িকভাবে বন্ধ</h2>

    <p>
        আপনার মাসিক পেমেন্ট সম্পন্ন না হওয়ায় আপনার অ্যাকাউন্ট সাময়িকভাবে বন্ধ করা হয়েছে।<br>
        অনুগ্রহ করে অনলাইনে পেমেন্ট করুন। যদি কোনো সমস্যার সম্মুখীন হন, তাহলে অ্যাডমিনের সাথে যোগাযোগ করুন।
    </p>

    {{-- PAY BUTTON --}}
    <a href="{{ route('monthly-fee.pay') }}" class="btn btn-pay">
        💳 Pay Monthly Fee
    </a>

    <br>

    <div class="note">
        পেমেন্ট সফল হলে আপনার অ্যাকাউন্ট স্বয়ংক্রিয়ভাবে চালু হবে।
    </div>

    <hr>

    <h4>Contact Admin</h4>

    @forelse($admins as $admin)
        <div class="admin">
            <div><strong>{{ $admin->name }}</strong></div>
            <div class="phone">📞 {{ $admin->phone }}</div>
        </div>
    @empty
        <p>No admin available</p>
    @endforelse

    <br>

    <a href="tel:{{ $admins->first()->phone ?? '' }}" class="btn btn-contact">
        📞 Call Admin
    </a>

</div>

</body>
</html>