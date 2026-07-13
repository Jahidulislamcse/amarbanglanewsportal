@extends('layouts.print')

@section('title', $data->title)
@section('contents')
<style>
.print-float {
    right: 0% !important;
}
.linkedin1 {
	background-color: #337ab7!important;
	padding: 7px 12px !important;
	border-radius: 0% !important;
	color: #fff !important; /* Make icon/text white */

}
.linkedin1:hover {
		text-decoration: none !important;
		color: #337ab7!important;
		border-radius: 0% !important;
		background-color: white !important;
		border-radius: 5px;
		border: 1px solid #337ab7;
	}
.whatsapp {
		background-color: #25D366 !important; /* Official WhatsApp green */
		padding: 7px 12px !important;
		border-radius: 4px !important; /* Use small rounding, not 0% */
		color: #fff !important; /* Make icon/text white */
		display: inline-flex;
		align-items: center;
		gap: 6px; /* space between icon and text */
		text-decoration: none !important;
		font-weight: 600;
		transition: background-color 0.3s ease;
	}
	.whatsapp:hover {
		color: #1EBE5D !important; /* Slightly darker green on hover */
		text-decoration: none !important;
		background-color: white !important;
		border-radius: 5px;
		border: 1px solid #25D366;
	}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
 <section class="singlepage-section">
		 
 <div id="epaper_ss" style="background: white!important;">
    <div class="content-center">
        <img src="{{ asset('assets/idcard/amarbangla logo Design -01.png') }}" style="height:200px" alt="Logo">
    <div class="time text-center p-date" style="font-size:17px">
	
	<?php if($default_language->id==1){?>
				প্রিন্ট এর তারিখঃ {{enToBn(date('Y-m-d h:s A'))}} || প্রকাশের তারিখঃ {{enToBn($data->schedule_post_date)}}
		
		<?php }else{?>
				Print Date: {{date('Y-m-d h:s A')}} || Published Date: {{$data->schedule_post_date}}
					 
		<?php }?>
		
		
     </div>
<h2 class="p-title">{{$data->title}} </h2>
  
<div id="my-element" class="news-details-print print-container">
 

    <p>
		@if ($data->image_big)
		<img  src="{{asset('assets/images/post/'.$data->image_big)}}"   alt="">
		@else 
			<img  src="{{asset('assets/images/nopic.png')}}" alt="" decoding="async" >
		@endif 
	</p>
	<?php if($default_language->id==1){?>
					<p style="font-size:16px"><strong>{{$user_info->name ?? 'No Name';}} | আমার বাংলা | {{enToBn(date('Y-m-d'))}} </strong></p>
		
		<?php }else{?>
				 <p style="font-size:16px"><strong>{{$user_info->name ?? 'No Name';}}| Kalbindu| {{date('Y-m-d')}} </strong></p>
					 
		<?php }?>



    <div style="text-align: justify;">
        {!! $data->description !!}
    </div>


</div>

<div id="" class="print-copyright" style="font-size:18px;text-align: center;">
    <hr>
		<?php if($default_language->id==1){?>
				         <center>{{$gs->copyright_text}}</center>
		<?php }else{?>
					 <center>Copyright © <?php date('Y');?> All Rights Reserved | Kalbindu Media & Publication Ltd.</center>
					 
		<?php }?>
                  
	
</div>
</div>
 </div>
 
  <div class="sgl-page-social-title" >
  <br/>
	<h4>{{ __('If you liked this news, please share it.') }} </h4>
</div>

  <div class="sgl-page-social no-print">
	<ul>
		<li><a href="http://www.facebook.com/sharer.php?u={{request()->fullUrl()}}" class="ffacebook" target="_blank"> <i class="fa fa-facebook"></i> Facebook</a></li>

		<li><a href="https://twitter.com/intent/tweet?url={{request()->fullUrl()}}&text={{$data->title}}" class="ttwitter" target="_blank"> <i class="fa fa-twitter"></i> Twitter</a></li>

		<li><a href="http://digg.com/submit?url={{request()->fullUrl()}}&title={{$data->title}}" class="digg" target="_blank"> <i class="fa fa-digg"></i> Digg </a></li>
		
		<li><a href="http://www.linkedin.com/shareArticle?mini=true&title={{$data->title}}&url={{request()->fullUrl()}}" class="linkedin1" target="_blank"> <i class="fa fa-linkedin"></i> Linkedin </a></li>

		<li><a href="http://www.reddit.com/submit?url={{request()->fullUrl()}}&title={{$data->title}}" class="reddit" target="_blank"> <i class="fa fa-reddit"></i> Reddit </a></li>
		
		<li><a href="https://plus.google.com/share?url={{request()->fullUrl()}}" class="google-plus" target="_blank"> <i class="fa fa-google-plus"></i> Google Plus</a></li>

		<li><a href="http://www.pinterest.com/pin/create/button/?url={{request()->fullUrl()}}" class="pinterest" target="_blank"> <i class="fa fa-pinterest"></i> Pinterest </a></li>
		
		<li><a href="https://wa.me/?text={{request()->fullUrl()}}" class="whatsapp" target="_blank"> <i class="fa fa-whatsapp"></i> Whatsapp </a></li>

	</ul>
	
</div>
 
 <div class="print-float no-print">
<a href="#" class="btn red" onclick="window.print()"> <img src="{{asset('assets/images/3022251.png')}}">
{{ __('Print News') }}
</a> <a href="javascript:void(0)" id="print_news"  class="btn green"> <img src="{{asset('assets/images/4208397.png')}}">
{{ __('Save') }}
</a>
</div>
  <script>
  
 
 $(document).ready(function() {
    $('#print_news').click(function() {
        const element = document.getElementById('epaper_ss');

        html2canvas(element, { scale: 2 }).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'pt', 'a4');

            const imgWidth = 595.28; // A4 width in pt
            const pageHeight = window.innerHeight;
            const imgHeight = canvas.height * imgWidth / canvas.width;

            let heightLeft = imgHeight;
            let position = 0;

            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            pdf.save('{{$data->title}}.pdf');
        });
		
		
    });
});
    </script>


@endsection
  
@push('js')




@endpush
