@extends('layouts.front_custom')

@section('contents')
<section class="regpage-section">
    <div class="regpage-wrapper">
        <div class="regpage-row">
            <div class="regpage-column">
                <div class="regpage-box">
                    <h2 class="regpage-title">রেজিস্ট্রেশন করুন</h2>
                    <div class="regpage-btn-group">
                        <a href="{{ route('register_reader') }}" class="regpage-btn-reader">পাঠক</a>
                        <a href="{{ route('front.registration') }}" class="regpage-btn-reporter">রিপোর্টার/প্রতিবেদক</a>
                    </div>
                    <p class="regpage-info-text">
                        আপনি যে ধরনের রেজিস্ট্রেশন করতে চান তা নির্বাচন করুন।
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
body {
    border-top: 5px solid #4caf50;
    border-bottom: 5px solid #4caf50;
}
.regpage-section {
    padding: 40px 15px;
}
.regpage-wrapper {
    max-width: 500px;
    margin: 0 auto;
}
.regpage-row {
    display: flex;
    justify-content: center;
}
.regpage-column {
    width: 100%;
}
.regpage-box {
    background: #fff;
    padding: 30px 20px;
    border-radius: 12px;
    border: 2px solid #4caf50;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
}
.regpage-title {
    font-weight: 700;
    margin-bottom: 20px;
    color: #2e7d32;
    font-size: 22px;
}
.regpage-btn-group {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 15px;
}
.regpage-btn-group a {
    flex: 1 1 45%;
    text-decoration: none;
    padding: 10px 0;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid #4caf50;
    font-size: 16px;
}
.regpage-btn-reader {
    background-color: #f1f8f1;
    color: #2e7d32;
}
.regpage-btn-reader:hover {
    background-color: #e0f2e0;
    color: #145a16;
}
.regpage-btn-reporter {
    background-color: #f1f8f1;
    color: #2e7d32;
}
.regpage-btn-reporter:hover {
    background-color: #e0f2e0;
    color: #145a16;
}
.regpage-info-text {
    margin-top: 15px;
    font-size: 14px;
    color: #4caf50;
}
@media (max-width: 576px) {
    .regpage-btn-group {
        flex-direction: column;
        gap: 10px;
    }
    .regpage-btn-group a {
        flex: 1 1 100%;
        padding: 8px 0;
        font-size: 14px;
    }
    .regpage-box {
        padding: 20px 15px;
    }
    .regpage-title {
        font-size: 20px;
    }
}
</style>
@endsection
