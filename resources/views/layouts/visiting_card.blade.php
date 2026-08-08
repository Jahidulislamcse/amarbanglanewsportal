<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visiting Card</title>
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <style>
        body {
            background-color: #e2e8f0;
            font-family: 'Segoe UI', Arial, 'Kalpurush', 'SolaimanLipi', sans-serif;
            margin: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .visiting-card-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
            margin-bottom: 20px;
        }

        @media (min-width: 1400px) {
            .visiting-card-container {
                flex-direction: row;
                gap: 40px;
            }
        }

        .visiting-card {
            width: 700px;
            height: 431px;
            position: relative;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-position: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-radius: 6px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .visiting-card.front {
            background-image: url('{{ asset("assets/Visiting/visiting_card_front.png") }}');
        }

        .visiting-card.back {
            background-image: url('{{ asset("assets/Visiting/visiting_card_back.png") }}');
        }

        /* Overlay Elements for Front Card */
        .v-qr-code {
            position: absolute;
            top: 37%;
            left: 13.5%;
            width: 140px;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 3px;
        }
        .v-qr-code svg, .v-qr-code img {
            width: 100% !important;
            height: 100% !important;
        }

        .v-name-box {
            position: absolute;
            top: 10%;
            right: 6.5%;
            text-align: right;
            max-width: 480px;
        }

        .v-name {
            font-size: 36px;
            font-weight: 700;
            color: #0b7c4a;
            margin: 0;
            line-height: 1.2;
        }

        .v-role {
            font-size: 21px;
            font-weight: 700;
            color: #1a1a1a;
            margin-top: 5px;
            line-height: 1.3;
        }

        /* Contact Items aligned exactly with icon centers */
        .v-contact-phone {
            position: absolute;
            top: 56.66%;
            transform: translateY(-50%);
            right: 12%;
            width: 440px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 20px;
            font-weight: 700;
            color: #111111;
            text-align: right;
            line-height: 1;
        }

        .v-contact-email {
            position: absolute;
            top: 68.14%;
            transform: translateY(-50%);
            right: 12%;
            width: 440px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 19px;
            font-weight: 700;
            color: #111111;
            text-align: right;
            line-height: 1;
        }

        .v-contact-address {
            position: absolute;
            top: 78.80%;
            transform: translateY(-50%);
            right: 12%;
            width: 440px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 17px;
            font-weight: 700;
            color: #111111;
            text-align: right;
            line-height: 1.25;
        }

        .print-btn-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .btn-download-pdf {
            background-color: #0b7c4a;
            color: #ffffff;
            padding: 12px 30px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(11, 124, 74, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-download-pdf:hover {
            background-color: #085a36;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(11, 124, 74, 0.4);
            text-decoration: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: none;
                padding: 0;
            }
            .visiting-card {
                box-shadow: none;
                page-break-after: always;
            }
        }
    </style>

    <script>
    $(document).ready(function () {
        $('#download_visiting_pdf').click(function () {
            const front = document.querySelector('.visiting-card.front');
            const back = document.querySelector('.visiting-card.back');
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating PDF...');

            Promise.all([
                html2canvas(front, { scale: 4, useCORS: true, backgroundColor: '#ffffff' }),
                html2canvas(back, { scale: 4, useCORS: true, backgroundColor: '#ffffff' })
            ]).then(([frontCanvas, backCanvas]) => {
                // Visiting card standard dimensions in mm: 90mm x 54mm (landscape)
                const cardWidthMM = 90;
                const cardHeightMM = 54;
                const gapMM = 6;

                // Points conversion (1 mm = 2.83465 pt)
                const cardWidthPt = cardWidthMM * 2.83465;
                const cardHeightPt = cardHeightMM * 2.83465;
                const gapPt = gapMM * 2.83465;

                // Create landscape PDF with both cards side by side
                const pdf = new jspdf.jsPDF({
                    orientation: 'landscape',
                    unit: 'pt',
                    format: [cardWidthPt * 2 + gapPt * 3, cardHeightPt + gapPt * 2]
                });

                // Background fill
                pdf.setFillColor(240, 243, 246);
                pdf.rect(0, 0, pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight(), 'F');

                // Add Front image
                pdf.addImage(
                    frontCanvas.toDataURL('image/png'),
                    'PNG',
                    gapPt,
                    gapPt,
                    cardWidthPt,
                    cardHeightPt
                );

                // Add Back image
                pdf.addImage(
                    backCanvas.toDataURL('image/png'),
                    'PNG',
                    cardWidthPt + gapPt * 2,
                    gapPt,
                    cardWidthPt,
                    cardHeightPt
                );

                pdf.save('Amar-Bangla-Visiting-Card.pdf');
                btn.prop('disabled', false).html('<i class="fas fa-file-pdf"></i> Download PDF');
            }).catch(function(err) {
                console.error(err);
                alert('Error generating PDF. Please try again.');
                btn.prop('disabled', false).html('<i class="fas fa-file-pdf"></i> Download PDF');
            });
        });
    });
    </script>
</head>
<body>
    @yield('content')
</body>
</html>
