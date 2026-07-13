<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>আমার বাংলা ২৪ - অ্যাপ ইনস্টল করুন</title>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --red: #c0001d;
      --dark: #0d0d0d;
      --card: #161616;
      --border: rgba(255,255,255,0.08);
      --gold: #e8b14f;
      --text: #e8e3d8;
      --muted: #888;
    }

    body {
      background: var(--dark);
      color: var(--text);
      font-family: 'Hind Siliguri', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
      overflow-x: hidden;
    }

    /* background texture */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 50% at 50% -10%, rgba(192,0,29,0.18) 0%, transparent 70%),
        radial-gradient(ellipse 50% 30% at 80% 100%, rgba(192,0,29,0.10) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    .card {
      position: relative;
      z-index: 1;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      max-width: 420px;
      width: 100%;
      padding: 36px 28px 32px;
      box-shadow: 0 8px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.04);
      animation: fadeUp 0.6s cubic-bezier(.22,1,.36,1) both;
    }
    
    .back-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      border-radius: 12px;
      background: var(--red);
      color: var(--text);
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 10px;
      text-decoration: none;
      border: 1px solid var(--border);
      transition: all 0.2s ease;
    }
    
    
    .back-btn:hover {
      background: rgba(255,255,255,0.05);
      border-color: rgba(255,255,255,0.2);
    }
    

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .logo-row {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 28px;
    }

    .logo-icon {
      width: 56px;
      height: 56px;
      border-radius: 14px;
      background: var(--red);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Tiro Bangla', serif;
      font-size: 28px;
      color: #fff;
      flex-shrink: 0;
      box-shadow: 0 4px 20px rgba(192,0,29,0.4);
    }

    .logo-text h1 {
      font-size: 20px;
      font-weight: 700;
      line-height: 1.2;
      letter-spacing: -0.3px;
    }

    .logo-text p {
      font-size: 13px;
      color: var(--muted);
      margin-top: 2px;
    }

    .badge {
      display: inline-block;
      background: rgba(192,0,29,0.15);
      border: 1px solid rgba(192,0,29,0.3);
      color: #ff4d66;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      padding: 3px 10px;
      border-radius: 100px;
      margin-bottom: 14px;
    }

    .headline {
      font-size: 26px;
      font-weight: 700;
      line-height: 1.3;
      margin-bottom: 10px;
    }

    .headline span {
      color: var(--red);
    }

    .sub {
      font-size: 14px;
      color: var(--muted);
      line-height: 1.6;
      margin-bottom: 28px;
    }

    .divider {
      height: 1px;
      background: var(--border);
      margin: 24px 0;
    }

    /* Steps */
    .steps-title {
      font-size: 13px;
      font-weight: 600;
      color: var(--gold);
      letter-spacing: 0.6px;
      text-transform: uppercase;
      margin-bottom: 16px;
    }

    .steps { display: flex; flex-direction: column; gap: 14px; }

    .step {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 14px 16px;
      background: rgba(255,255,255,0.03);
      border: 1px solid var(--border);
      border-radius: 12px;
      transition: background 0.2s;
    }

    .step:hover { background: rgba(255,255,255,0.055); }

    .step-num {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--red);
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .step-body strong {
      display: block;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 3px;
    }

    .step-body p {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.5;
    }

    .step-body .tag {
      display: inline-block;
      background: rgba(255,255,255,0.08);
      border-radius: 6px;
      padding: 1px 7px;
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
      margin: 0 2px;
    }

    /* OS tabs */
    .os-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
    }

    .os-btn {
      flex: 1;
      padding: 9px 0;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: transparent;
      color: var(--muted);
      font-family: 'Hind Siliguri', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .os-btn.active {
      background: var(--red);
      border-color: var(--red);
      color: #fff;
      box-shadow: 0 4px 14px rgba(192,0,29,0.35);
    }

    .os-btn:not(.active):hover {
      border-color: rgba(255,255,255,0.2);
      color: var(--text);
    }

    .steps-panel { display: none; }
    .steps-panel.active { display: flex; flex-direction: column; gap: 14px; }

    /* CTA */
    .cta-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      padding: 15px;
      border-radius: 12px;
      background: var(--red);
      color: #fff;
      font-family: 'Hind Siliguri', sans-serif;
      font-size: 16px;
      font-weight: 700;
      text-decoration: none;
      border: none;
      cursor: pointer;
      margin-top: 24px;
      box-shadow: 0 6px 24px rgba(192,0,29,0.4);
      transition: transform 0.15s, box-shadow 0.15s;
    }

    .cta-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 32px rgba(192,0,29,0.5);
    }

    .cta-btn:active { transform: translateY(0); }

    .cta-btn svg { flex-shrink: 0; }

    .footer-note {
      text-align: center;
      font-size: 12px;
      color: var(--muted);
      margin-top: 20px;
    }

    .footer-note a {
      color: var(--gold);
      text-decoration: none;
    }

    /* QR section */
    .qr-section {
      text-align: center;
      margin-top: 24px;
      padding: 20px;
      background: rgba(255,255,255,0.03);
      border: 1px solid var(--border);
      border-radius: 12px;
    }

    .qr-section p {
      font-size: 13px;
      color: var(--muted);
      margin-bottom: 12px;
    }

    .qr-img {
      width: 140px;
      height: 140px;
      border-radius: 10px;
      background: #fff;
      padding: 8px;
      margin: 0 auto;
      display: block;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo-row">
      <div class="logo-icon">আ</div>
      <div class="logo-text">
        <h1>আমার বাংলা ২৪</h1>
        <p>amarbangla24.com.bd</p>
      </div>
    </div>
    
    <a href="https://apk.e-droid.net/apk/app4092352-rov610.apk?v=1" class="back-btn">
      ⬇️ APK ডাউনলোড করুন
    </a>

    <div class="badge">📲 Android APK · সরাসরি ইনস্টল করুন</div>
    <div class="headline">আপনার ফোনে <span>ইনস্টল</span> করুন</div>
    <p class="sub">নিচের বোতামে ট্যাপ করে সরাসরি APK ডাউনলোড ও ইনস্টল করুন।</p>

    <!-- OS selector -->
    <div class="os-tabs">
      <button class="os-btn active" onclick="switchOS('android')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.523 15.341 19.9 11.2a.5.5 0 0 0-.867-.5l-2.4 4.163a7.92 7.92 0 0 1-9.268 0L4.967 10.7a.5.5 0 0 0-.867.5l2.377 4.141A8.5 8.5 0 0 0 12 17.5a8.5 8.5 0 0 0 5.523-2.159ZM8 9.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/></svg>
        Android
      </button>
      <button class="os-btn" onclick="switchOS('ios')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
        iPhone
      </button>
    </div>

    
<!-- Android steps -->
<div id="steps-android" class="steps-panel active">
  <div class="steps-title">Android ইনস্টল ধাপ</div>
  <div class="step"><div class="step-num">১</div><div class="step-body"><strong>APK ডাউনলোড করুন</strong><p>নিচের ডাউনলোড বোতামে ট্যাপ করুন।</p></div></div>
  <div class="step"><div class="step-num">২</div><div class="step-body"><strong>ফাইল খুলুন</strong><p>ডাউনলোড শেষ হলে APK ফাইল ওপেন করুন।</p></div></div>
  <div class="step"><div class="step-num">৩</div><div class="step-body"><strong>Install চাপুন</strong><p>প্রয়োজনে 'Allow from this source' চালু করুন।</p></div></div>
  <div class="step"><div class="step-num">৪</div><div class="step-body"><strong>অ্যাপ চালু করুন</strong><p>ইনস্টল সম্পন্ন হলে Open চাপুন।</p></div></div>
</div>

<!-- iOS steps -->

    <div id="steps-ios" class="steps-panel">
      <div class="steps-title">ধাপে ধাপে ইনস্টল করুন</div>
      <div class="step">
        <div class="step-num">১</div>
        <div class="step-body">
          <strong>Safari-তে খুলুন</strong>
          <p>iPhone-এ Safari ব্রাউজারে <span class="tag">amarbangla24.com.bd</span> লিখুন। (Chrome-এ কাজ করবে না।)</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">২</div>
        <div class="step-body">
          <strong>শেয়ার বোতামে ট্যাপ করুন</strong>
          <p>নিচের টুলবারে <span class="tag">⬆ Share</span> আইকনে ট্যাপ করুন।</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">৩</div>
        <div class="step-body">
          <strong>"Add to Home Screen" বেছে নিন</strong>
          <p>মেনু থেকে স্ক্রল করে <span class="tag">Add to Home Screen</span> খুঁজে ট্যাপ করুন।</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">৪</div>
        <div class="step-body">
          <strong>Add চাপুন</strong>
          <p>উপরের ডান কোণে <span class="tag">Add</span> বোতাম চাপলেই অ্যাপ ইনস্টল হয়ে যাবে!</p>
        </div>
      </div>
    </div>

    <a class="cta-btn" href="https://apk.e-droid.net/apk/app4092352-rov610.apk?v=1" target="_blank" download>
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      এখনই APK ডাউনলোড করুন
    </a>

    <!-- QR Code using Google Charts API -->
    <div class="qr-section">
      <p>📷 QR কোড স্ক্যান করুন — সরাসরি ফোনে খুলবে</p>
      <img
        class="qr-img"
        src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=https://apk.e-droid.net/apk/app4092352-rov610.apk?v=1"
        alt="QR Code for amarbangla24.com.bd"
      />
    </div>

    <p class="footer-note">
      সম্পূর্ণ বিনামূল্যে · কোনো App Store দরকার নেই<br/>
      <a href="https://apk.e-droid.net/apk/app4092352-rov610.apk?v=1" target="_blank" download>amarbangla24.com.bd</a>
    </p>
  </div>

  <script>
    function switchOS(os) {
      document.querySelectorAll('.os-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.steps-panel').forEach(p => p.classList.remove('active'));
      event.currentTarget.classList.add('active');
      document.getElementById('steps-' + os).classList.add('active');
    }
  </script>
</body>
</html>