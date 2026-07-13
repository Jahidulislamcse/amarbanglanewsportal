
<!DOCTYPE html><html><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print | @yield('title', 'Default Title')</title>
         <style type="text/css">
        .news-details-print{
  column-count: 2;
}

body.print-body {
  width: 210mm;

  }
a{
	text-decoration: none;
}

</style>

<link rel="stylesheet" id="print-css-css" href="{{asset('assets/front2/css/print.css')}}" type="text/css" media="all">
<link rel="stylesheet" id="print-css-css" href="{{asset('assets/front2/css/style.css')}}" type="text/css" media="all">
<link rel="stylesheet" id="print-css-css" href="{{asset('assets/front2/css/font-awesome.min.css')}}" type="text/css" media="all">
</head>
<body class="print-body">

@yield('contents')

</body>

</html>