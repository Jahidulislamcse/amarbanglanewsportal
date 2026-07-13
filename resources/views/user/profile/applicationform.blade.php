@extends('layouts.form')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        .form-container {
            background-color: #fff;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        p,
        li {
            line-height: 1.6;
        }

        .signature {
            margin-top: 40px;
        }

        ol li {
            margin-bottom: 10px;
        }

        .signature-section {
            margin-top: 40px;
        }

        .signature-line {
            display: inline-block;
            width: 250px;
            border-bottom: 1px solid #000;
            text-align: center;
            height: 50px;
        }

        .date-line {
            display: inline-block;
            width: 150px;
            border-bottom: 1px solid #000;
            text-align: center;
        }

        .signature-img {
            width: 150px;
            border-top: 1px solid #000;
        }

        a.btn.red {
            background: red;
            color: white;
            padding: 5px;
            text-decoration: none;
            font-size: 22px;
            margin-right: 20px;

        }

        a.btn img {
            max-height: 18px;
            filter: brightness(0) invert(1);
        }

        a.btn.green {
            background: green;
            color: white;
            padding: 5px;
            text-decoration: none;
            font-size: 22px;

        }

        @media print {

            .no-print {
                display: none !important;
            }

        }

        .header {
            text-align: center;
            font-size: 25px;
        }

        #loader.loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Spinner */
        #loader.loader::before {
            content: "";
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
        /* Bangla digit counter style */
        @counter-style bangla {
            system: numeric;
            symbols: "০" "১" "২" "৩" "৪" "৫" "৬" "৭" "৮" "৯";
            suffix: " ";
        }
    
        ol.bangla-counter {
            counter-reset: section;
            list-style: none;
            padding-left: 0;
        }
        ol.bangla-counter > li {
            counter-increment: section;
            margin: 10px 0;
           
        }
        ol.bangla-counter > li::before {
            content: counter(section, bangla) ". ";
            font-weight: bold;
        }
    
        ol.bangla-counter ol {
            counter-reset: subsection;
            list-style: none;
            padding-left: 25px;
            margin-top: 6px;
        }
        ol.bangla-counter ol > li {
            counter-increment: subsection;
            margin: 5px 0;
        }
        ol.bangla-counter ol > li::before {
            content: counter(section, bangla) "." counter(subsection, bangla) " ";
            font-weight: bold;
        }
    </style>

    <?php
    
    if ($type && $type == 2) {
        $title = $data->report_type;
    } else {
        $josn_decode = json_decode($data->report_type, true);
        $title = '';
        if (isset($josn_decode[0])) {
            $title = isset($reportcategories[$josn_decode[0]]) ? $reportcategories[$josn_decode[0]] : '';
        }
    }
    
    ?>

    <div class="print-float no-print" align="center">
        <a href="#" class="btn red" onclick="window.print()"> <img src="{{ asset('assets/images/3022251.png') }}">
            Print
        </a> <a href="javascript:void(0)" id="print_news" class="btn green"> <img
                src="{{ asset('assets/images/4208397.png') }}">
            Pdf
        </a>
    </div>
    <div id="loader" class="loader" style="display:none;"></div>
    <div class="form-container" id="epaper_ss">
        <p align="center" class="header">সাংবাদিক প্রতিনিধি  আবেদনপত্র</p>

        <p>বরাবর, <br>
            সম্পাদক,<br>
            আমার বাংলা মাল্টিমিডিয়া & পাবলিকেশন<br>
            আমীন কোর্ট, সেনা কল্যাণ বিসনেস সেন্টার, মতিঝিল সি/এ, ঢাকা-১০০০</p>


       <p>বিষয়: অনলাইন প্রতিনিধি (Commission Based} হিসেবে নিয়োগ–সংক্রান্ত আবেদন। </p>

        </br>
        <p>মাননীয় সম্পাদক/প্রকাশক মহোদয়,</p>
        
        <p>
            আমি নিম্নস্বাক্ষরকারী, নাম - {{ $data->name }}
            পিতা- {{ $data->father_name }}
            মাতা- {{ $data->mother_name }}
            স্থায়ী ঠিকানা- {{ $data->address }}
            জাতীয় পরিচয়পত্র/জন্ম নিবন্ধন নং- {{ $data->nid_no }}
            মোবাইল নাম্বার- {{ $data->phone }}
        </p>
        
        <p>
           এই মর্মে আবেদন করছি যে, আমি আপনার প্রতিষ্ঠানে অনলাইন প্রতিনিধি (Commission Based) হিসেবে কাজ করতে আগ্রহী। আমি সম্পূর্ণ কমিশন–ভিত্তিক পদ্ধতিতে, নিউজের ভিউ/রিচ অনুযায়ী পারিশ্রমিক গ্রহণে সম্মত।
        </p>
        
         <p>
          প্রতিষ্ঠানের উন্নয়ন, তথ্যপ্রবাহ বৃদ্ধিকরণ এবং সঠিক সংবাদ পরিবেশনে দায়িত্বশীল ভূমিকা রাখতে চাই। আপনার প্রতিষ্ঠানের নীতি, মানদণ্ড এবং সম্পাদকীয় নির্দেশনা অনুসরণ করে নিয়মিত নিউজ প্রেরণ ও জনস্বার্থে প্রতিবেদন তৈরি করার অঙ্গীকার করছি।
        </p>
        </br>
        
         <h5>অঙ্গীকারনামা (Commission Based Reporter Agreement)</h5>
         
        <p>আমি ঘোষণা করছি যে—</p>
       
        <ol class="bangla-counter">
            <li>আইন, নীতিমালা ও নৈতিকতা
                <ol>
                    <li>আমি বাংলাদেশের প্রচলিত আইন, সাংবাদিকতার নৈতিকতা ও প্রতিষ্ঠানের সম্পাদকীয় নীতিমালা মেনে দায়িত্ব পালন করব।</li>
                    <li>আমি কোনো ভুয়া, বিভ্রান্তিকর, গুজবভিত্তিক বা প্রমাণহীন তথ্য প্রকাশ করব না।</li>
                    <li>আমি কোনোরূপ অপপ্রচার, ব্যক্তিগত আক্রমণ, মানহানিকর বা বেআইনি কনটেন্ট তৈরি করব না।</li>
                </ol>
            </li>
        
            <li>কমিশন–ভিত্তিক কাজের শর্ত
                <ol>
                    <li>আমি বেতনভুক্ত নই; নিউজের ভিউ/রিচ/ইনগেজমেন্ট অনুযায়ী কমিশন পদ্ধতিতে কাজ করব।</li>
                    <li>প্রতিষ্ঠানের নির্ধারিত কমিশন হার, নিয়মনীতি ও পেমেন্ট প্রক্রিয়া আমি মান্য করব।</li>
                    <li>আমার প্রেরিত নিউজের ভিউ–ডাটা প্রতিষ্ঠান যাচাই করে কমিশন নির্ধারণ করবে—এ বিষয়ে আমি সম্মত।</li>
                </ol>
            </li>
        
            <li>দায়িত্ববোধ ও পেশাগত আচরণ
                <ol >
                    <li>সংবাদ সংগ্রহ ও প্রকাশের ক্ষেত্রে আমি সততা, নিরপেক্ষতা, জনস্বার্থ ও পেশাদারিত্ব বজায় রাখব।</li>
                    <li>প্রতিষ্ঠানের নাম, পরিচয় বা আইডি ব্যবহার করে কোনো ব্যক্তিগত সুবিধা, আর্থিক লেনদেন বা বেআইনি প্রভাব খাটাব না।</li>
                    <li>অফিসিয়াল তথ্য, সোর্স বা গোপন নথি অনুমতি ছাড়া প্রকাশ করব না।</li>
                </ol>
            </li>
        
            <li>আর্থিক ও প্রশাসনিক নীতিমালা
                <ol >
                    <li>প্রতিষ্ঠানের মাসিক সদস্যপদ চাঁদা, মার্কেটিং ফি বা প্রশাসনিক চার্জ (যদি থাকে) নিয়মিত পরিশোধ করতে আমি সম্মত।</li>
                    <li>কমিশনের বাইরে অতিরিক্ত কোনো আর্থিক দাবি বা সুবিধা চাইব না।</li>
                </ol>
            </li>
        
            <li>শাস্তিমূলক ও চুক্তিভঙ্গ সংক্রান্ত শর্ত
                <ol >
                    <li>আমি যদি প্রতিষ্ঠানের নীতিমালা ভঙ্গ করি, মিথ্যা তথ্য প্রদান করি বা বেআইনি কর্মকাণ্ডে জড়াই—তাহলে আমার সদস্যপদ বা প্রতিনিধি আইডি বাতিল করা যাবে এবং এ বিষয়ে আমি আপত্তি করব না।</li>
                    <li>প্রয়োজন হলে আমার বিরুদ্ধে আইনগত ব্যবস্থা নেওয়া যেতে পারে—এ বিষয়ে আমি নীতিগতভাবে সম্মত।</li>
                </ol>
            </li>
        </ol>
        
        <p>ঘোষণা </br>
            উপরোক্ত সকল শর্ত আমি পূর্ণাঙ্গভাবে বুঝেছি, স্বেচ্ছায় গ্রহণ করেছি এবং নির্ধারিত নীতি অনুযায়ী কমিশন ভিত্তিক সংবাদদাতা/প্রতিনিধি হিসেবে কাজ করতে অঙ্গীকার করছি।

        </br> </br> ধন্যবাদান্তে,        </p>


        <div class="signature-section">
            <img src="{{ $data->id ? asset('assets/images/admin/' . $data->id . '.png') : asset('assets/images/noimage.png') }}"
                width="100" style="padding-left:1%" alt="স্বাক্ষর">
            <p class="signature-img">আবেদনকারীর স্বাক্ষর</p>
            <span>তারিখ: {{ enToBn(date('d/ m/ Y', strtotime($data->created_at)), 1) }}</span>
        </div>



    </div>

    <script>
        $(document).ready(function() {
            $('#print_news').click(function() {

                $('#epaper_ss').css({
                    'font-size': '16pt',
                    'line-height': '1.5',
                    'font-family': 'Arial, sans-serif'
                });
                $('.header').css({
                    'font-size': '30px'
                });

                $('.signature-img').css({
                    'width': '210px'
                });
                $('#loader').show();
                const element = document.getElementById('epaper_ss');

                html2canvas(element, {
                    scale: 2
                }).then((canvas) => {
                    const imgData = canvas.toDataURL('image/png');
                    const {
                        jsPDF
                    } = window.jspdf;
                    const pdf = new jsPDF('p', 'pt', 'a4');

                    const marginLeft = 20; // left margin in pt
                    const marginTop = 20; // top margin in pt
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    const imgWidth = pageWidth - 2 * marginLeft; // adjust width for margins
                    const imgHeight = canvas.height * imgWidth / canvas.width;

                    let heightLeft = imgHeight;
                    let position = marginTop;

                    pdf.addImage(imgData, 'PNG', marginLeft, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight - 2 * marginTop;

                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight + marginTop;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', marginLeft, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight - 2 * marginTop;
                    }
                    $('#loader').hide();
                    pdf.save('applicationform-{{ $data->id }}.pdf');

                    // Reload page after download
                    setTimeout(function() {
                        location.reload();
                    }, 100); // small delay to ensure save completes
                });
            });
        });
    </script>
@endsection
