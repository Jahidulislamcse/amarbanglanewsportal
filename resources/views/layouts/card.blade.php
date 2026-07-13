<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card</title>
	<link rel="stylesheet" href="{{asset('assets/idcard/style.css')}}">
	<link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" />

  <script src="https://kit.fontawesome.com/7306bb18cf.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  
  <script>
$(document).ready(function () {
  $('#print_news').click(function () {
    const front = document.querySelector('.id-card.front');
    const back = document.querySelector('.id-card.back');

    // Capture both sides as high-resolution canvases
    Promise.all([
      html2canvas(front, { scale: 6, useCORS: true, backgroundColor: '#ffffff' }),
      html2canvas(back, { scale: 6, useCORS: true, backgroundColor: '#ffffff' })
    ]).then(([frontCanvas, backCanvas]) => {

      // Card size in mm (standard ID card 85.6mm × 54mm)
      const cardWidthMM = 90;
      const cardHeightMM = 137;
      const cardWidth = cardWidthMM * 2.83465;
      const cardHeight = cardHeightMM * 2.83465;

      const gap = 10; // space between front and back
      const gapColor = '#e0e3ef'; // light gray background

      // Create new PDF sized for both sides horizontally
      const pdf = new jspdf.jsPDF({
        orientation: 'landscape',
        unit: 'pt',
        format: [cardWidth * 2 + gap, cardHeight]
      });

      // Background fill for the PDF
      pdf.setFillColor(gapColor);
      pdf.rect(0, 0, pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight(), 'F');

      // Add front side image (left)
      pdf.addImage(
        frontCanvas.toDataURL('image/png'),
        'PNG',
        0,
        0,
        cardWidth,
        cardHeight
      );

      // Add back side image (right)
      pdf.addImage(
        backCanvas.toDataURL('image/png'),
        'PNG',
        cardWidth + gap,
        0,
        cardWidth,
        cardHeight
      );

      // Save final PDF
      pdf.save('Amar Bangla-ID-Card.pdf');
    });
  });
});
</script>
</head>
<body  style="background:#ccc">
 @yield('content')
</body>
</html>
