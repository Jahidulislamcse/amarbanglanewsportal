@extends('layouts.front_custom')

<style>
.copy-success {
  display: none;
  color: green;
  font-size: 12px;
  margin-left: 8px;
}
   .copy-btn {
	  display: inline-block;
	  background-color: #DC4E41; /* green background */
	  color: white;
	  padding: 3px;
	  font-size: 14px;
	  border-radius: 4px;
	  text-decoration: none;
	  font-weight: bold;
	  transition: background-color 0.3s;
	}
	
	 .print-btn {
	  display: inline-block;
	  background-color: #489DDE; /* green background */
	  color: white;
	  padding: 3px;
	  font-size: 14px;
	  border-radius: 4px;
	  text-decoration: none;
	  font-weight: bold;
	  transition: background-color 0.3s;
	}
	
	
	.copy-btn:hover {
	  background-color: black;
	  color: white;
	  text-decoration: none;
	}

	.copy-btn .glyphicon {
	  margin-right: 5px; /* space between icon and text */
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
	/* ===== Slider Container ===== */
    .slider-container {
      max-width: 900px;
      margin: 20px auto;
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .slider {
      display: flex;
      transition: transform 0.6s ease-in-out;
    }

    .slider img {
      width: 100%;
      flex-shrink: 0;
      cursor: pointer;
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .slider img[data-loaded="true"] {
      opacity: 1;
    }

    /* ===== Navigation Buttons ===== */
    .slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      padding: 10px 15px;
      font-size: 30px;
      border: none;
      cursor: pointer;
      border-radius: 50%;
      user-select: none;
      z-index: 10;
      transition: background 0.3s ease;
    }

    .slider-btn:hover {
      background: rgba(0, 0, 0, 0.7);
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    /* ===== Lightbox Modal ===== */
    .lightbox {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.85);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .lightbox img {
      max-width: 90%;
      max-height: 80%;
      border-radius: 10px;
      transition: opacity 0.3s ease;
    }

    /* Lightbox Controls */
    .lightbox .close, 
    .lightbox .prev-lightbox, 
    .lightbox .next-lightbox {
      position: absolute;
      color: #fff;
      font-size: 40px;
      cursor: pointer;
      user-select: none;
    }

    .lightbox .close {
      top: 20px;
      right: 30px;
    }

    .lightbox .prev-lightbox,
    .lightbox .next-lightbox {
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0,0,0,0.5);
      padding: 10px;
      border-radius: 50%;
    }

    .lightbox .prev-lightbox { left: 20px; }
    .lightbox .next-lightbox { right: 20px; }

    .lightbox .prev-lightbox:hover,
    .lightbox .next-lightbox:hover,
    .lightbox .close:hover {
      color: #ccc;
    }
	
  .calendar-container { max-width: 900px; margin: 40px auto; }
    .calendar-table { 
      width: 100%; 
      border-collapse: collapse; /* use collapse for solid borders */
      margin-top: 20px;
  }
  .calendar-table th, .calendar-table td { 
      border: 2px solid #337ab7; /* visible border color */
      text-align: center; 
      vertical-align: middle; 
      font-size: 18px; 
      border-radius: 5px; 
      transition: all 0.3s ease; 
  }
  .calendar-table th { 
      background-color: #4962A4; 
      color: #fff; 
      font-size: 15px; 
      padding: 7px; 
  }
  .calendar-table td a { 
      display: block; 
      width: 100%; 
      height: 100%; 
      line-height: 40px; 
      color: #333; 
      text-decoration: none; 
      font-weight: bold; 
  }
  .calendar-table td a:hover { 
      background-color: #f0ad4e; 
      color: #fff; 
  }
  .weekend { 
      background-color: #d9edf7; 
      font-weight: bold; 
  }
  .dropdowns { text-align: center; margin-bottom: 20px; }
  .dropdowns select { display: inline-block; margin: 0 5px; padding: 6px 12px; font-size: 16px; border-radius: 4px; }
  
  .today {
    background-color: #f2dede !important; /* light red */
}
.today a {
    color: #a94442 !important; /* dark red text */
    font-weight: bold;
}

 .calendar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(4, auto);
            gap: 10px;
            max-width: 360px;
            margin: 0 auto;
        }

        .cell {
            background: #fff;
            border: 1px solid #ddd;
            text-align: center;
            padding: 15px 10px;
            border-radius: 8px;
            transition: background 0.3s, transform 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .cell:hover {
            background: #f0f8ff;
            transform: translateY(-3px);
        }

        .cell .icon {
            font-size: 1.8rem;
            display: block;
            margin-bottom: 5px;
        }

        .cell .name {
            font-size: 1.10rem;
            color: #333;
            font-weight: 500;
        }

        /* 📱 Mobile adjustments */
        @media (max-width: 600px) {
            .calendar {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 400px) {
            .calendar {
                grid-template-columns: 1fr;
            }
        }
</style>
@section('contents')

					<?php
						$placementMap=config('placements');
						$side_bar_ads="";
						
						foreach (is_ads(array(16,17,18)) as $ad) {
							if (!isset($placementMap[$ad->add_placement])) {
								continue; // Skip if placement doesn't match
							}
							$varName = str_replace(" ","_",$placementMap[$ad->add_placement]);
							
							if($ad->add_placement==16){
								if ($ad->banner_type === 'image') { 
									$side_bar_ads .= '<a href="' . ($ad->link ?? '#') . '" target="_blank">
										<img class="lazy alignnone size-full wp-image-209" 
										data-src="' . asset('assets/images/addBanner/' . $ad->photo) . '" 
										alt="" width="100%" height="auto">
									</a>';
								} else {
									$side_bar_ads .= $ad->banner_code;
								}
								
							}else{
								if ($ad->banner_type === 'image') {
								   $$varName = '<a href="' . ($ad->link ?? '#') . '" target="_blank">'
										. '<img width="728" height="90" class="lazy image wp-image-471  attachment-full size-full" data-src="'
										. asset('assets/images/addBanner/' . $ad->photo)
										. '" alt="" width="100%" height="auto">'
										. '</a>';
								} else {
									$$varName = $ad->banner_code;
								}
							}
							
						}
						if($default_language->id==1){
							$title_size=80;
						}else{
							$title_size=80;
						}
						
						$zodiac_icons = [
							1  => asset('assets/horoscope/images/aries.png'),
							2  => asset('assets/horoscope/images/taurus.png'),
							3  => asset('assets/horoscope/images/gemini.png'),
							4  => asset('assets/horoscope/images/cancer.png'),
							5  => asset('assets/horoscope/images/leo.png'),
							6  => asset('assets/horoscope/images/virgo.png'),
							7  => asset('assets/horoscope/images/libra.png'),
							8  => asset('assets/horoscope/images/scorpio.png'),
							9  => asset('assets/horoscope/images/Sagittarius-PNG-Image.png'),
							10 => asset('assets/horoscope/images/Capricorn-PNG-Image.png'),
							11 => asset('assets/horoscope/images/Aquarius-Transparent-Image.png'),
							12 => asset('assets/horoscope/images/pisces.png'),
						];
						$image_icons = [
							1  => asset('assets/horoscope/images/1.jpg'),
							2  => asset('assets/horoscope/images/2.jpg'),
							3  => asset('assets/horoscope/images/3.jpg'),
							4  => asset('assets/horoscope/images/4.jpg'),
							5  => asset('assets/horoscope/images/6.jpg'),
							6  => asset('assets/horoscope/images/6.jpg'),
							7  => asset('assets/horoscope/images/7.jpg'),
							8  => asset('assets/horoscope/images/8.jpg'),
							9  => asset('assets/horoscope/images/9.jpg'),
							10 => asset('assets/horoscope/images/10.jpg'),
							11 => asset('assets/horoscope/images/11.jpg'),
							12 => asset('assets/horoscope/images/12.jpg'),
						];
						$no_image=asset('assets/horoscope/images/no.webp')
						?>
 <section class="singlepage-section">
		 
		    
									
					<div class="container">
							
										
            <div class="row">
                <div class="col-md-8 col-sm-8">
				
			
					<?php if(isset($data->id))	{
						
						if($type==53){
							$title="Yearly Horoscope";
							$date=$date[0];
						}else if($type==54){
							$title="Monthly Horoscope";
						}else if($type==55){
							$title="Today Horoscope";
						}else{
							$title="Weekly Horoscope";
						}
						?>	

				
                    <div class="single-cat-info">
                        <div class="single-cat-home">
                            <a href="{{ route('frontend.index')}}"><i class="fa fa-home" aria-hidden="true"></i>{{ __('Home') }}   </a>
                        </div>
      
                    </div>
			
                    <div class="single-title">
                        <h3>   {{ __($title) }}</h3>
                    </div>
					
					<div class="view-section">
					 <div class="row">
							
							
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="reportar-sec">

                                    <div class="sgl-page-views-count">
                                        <ul>
                                            <li> <i class="fa fa-clock-o"></i>  {{ __('Published Time') }} : {{enToBn($data->created_at)}}
											</li>
                                        </ul>
                                    </div>                                 
                                </div>
                            </div>
							
							
                        </div>
                    </div>
					
					  <div class="single-img">
					
						<img width="600" height="337" src="data:image/webp;base64,UklGRtYjAABXRUJQVlA4WAoAAAAwAAAACgMAtQEASUNDUEgMAAAAAAxITGlubwIQAABtbnRyUkdCIFhZWiAHzgACAAkABgAxAABhY3NwTVNGVAAAAABJRUMgc1JHQgAAAAAAAAAAAAAAAAAA9tYAAQAAAADTLUhQICAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABFjcHJ0AAABUAAAADNkZXNjAAABhAAAAGx3dHB0AAAB8AAAABRia3B0AAACBAAAABRyWFlaAAACGAAAABRnWFlaAAACLAAAABRiWFlaAAACQAAAABRkbW5kAAACVAAAAHBkbWRkAAACxAAAAIh2dWVkAAADTAAAAIZ2aWV3AAAD1AAAACRsdW1pAAAD+AAAABRtZWFzAAAEDAAAACR0ZWNoAAAEMAAAAAxyVFJDAAAEPAAACAxnVFJDAAAEPAAACAxiVFJDAAAEPAAACAx0ZXh0AAAAAENvcHlyaWdodCAoYykgMTk5OCBIZXdsZXR0LVBhY2thcmQgQ29tcGFueQAAZGVzYwAAAAAAAAASc1JHQiBJRUM2MTk2Ni0yLjEAAAAAAAAAAAAAABJzUkdCIElFQzYxOTY2LTIuMQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWFlaIAAAAAAAAPNRAAEAAAABFsxYWVogAAAAAAAAAAAAAAAAAAAAAFhZWiAAAAAAAABvogAAOPUAAAOQWFlaIAAAAAAAAGKZAAC3hQAAGNpYWVogAAAAAAAAJKAAAA+EAAC2z2Rlc2MAAAAAAAAAFklFQyBodHRwOi8vd3d3LmllYy5jaAAAAAAAAAAAAAAAFklFQyBodHRwOi8vd3d3LmllYy5jaAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABkZXNjAAAAAAAAAC5JRUMgNjE5NjYtMi4xIERlZmF1bHQgUkdCIGNvbG91ciBzcGFjZSAtIHNSR0IAAAAAAAAAAAAAAC5JRUMgNjE5NjYtMi4xIERlZmF1bHQgUkdCIGNvbG91ciBzcGFjZSAtIHNSR0IAAAAAAAAAAAAAAAAAAAAAAAAAAAAAZGVzYwAAAAAAAAAsUmVmZXJlbmNlIFZpZXdpbmcgQ29uZGl0aW9uIGluIElFQzYxOTY2LTIuMQAAAAAAAAAAAAAALFJlZmVyZW5jZSBWaWV3aW5nIENvbmRpdGlvbiBpbiBJRUM2MTk2Ni0yLjEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHZpZXcAAAAAABOk/gAUXy4AEM8UAAPtzAAEEwsAA1yeAAAAAVhZWiAAAAAAAEwJVgBQAAAAVx/nbWVhcwAAAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAo8AAAACc2lnIAAAAABDUlQgY3VydgAAAAAAAAQAAAAABQAKAA8AFAAZAB4AIwAoAC0AMgA3ADsAQABFAEoATwBUAFkAXgBjAGgAbQByAHcAfACBAIYAiwCQAJUAmgCfAKQAqQCuALIAtwC8AMEAxgDLANAA1QDbAOAA5QDrAPAA9gD7AQEBBwENARMBGQEfASUBKwEyATgBPgFFAUwBUgFZAWABZwFuAXUBfAGDAYsBkgGaAaEBqQGxAbkBwQHJAdEB2QHhAekB8gH6AgMCDAIUAh0CJgIvAjgCQQJLAlQCXQJnAnECegKEAo4CmAKiAqwCtgLBAssC1QLgAusC9QMAAwsDFgMhAy0DOANDA08DWgNmA3IDfgOKA5YDogOuA7oDxwPTA+AD7AP5BAYEEwQgBC0EOwRIBFUEYwRxBH4EjASaBKgEtgTEBNME4QTwBP4FDQUcBSsFOgVJBVgFZwV3BYYFlgWmBbUFxQXVBeUF9gYGBhYGJwY3BkgGWQZqBnsGjAadBq8GwAbRBuMG9QcHBxkHKwc9B08HYQd0B4YHmQesB78H0gflB/gICwgfCDIIRghaCG4IggiWCKoIvgjSCOcI+wkQCSUJOglPCWQJeQmPCaQJugnPCeUJ+woRCicKPQpUCmoKgQqYCq4KxQrcCvMLCwsiCzkLUQtpC4ALmAuwC8gL4Qv5DBIMKgxDDFwMdQyODKcMwAzZDPMNDQ0mDUANWg10DY4NqQ3DDd4N+A4TDi4OSQ5kDn8Omw62DtIO7g8JDyUPQQ9eD3oPlg+zD88P7BAJECYQQxBhEH4QmxC5ENcQ9RETETERTxFtEYwRqhHJEegSBxImEkUSZBKEEqMSwxLjEwMTIxNDE2MTgxOkE8UT5RQGFCcUSRRqFIsUrRTOFPAVEhU0FVYVeBWbFb0V4BYDFiYWSRZsFo8WshbWFvoXHRdBF2UXiReuF9IX9xgbGEAYZRiKGK8Y1Rj6GSAZRRlrGZEZtxndGgQaKhpRGncanhrFGuwbFBs7G2MbihuyG9ocAhwqHFIcexyjHMwc9R0eHUcdcB2ZHcMd7B4WHkAeah6UHr4e6R8THz4faR+UH78f6iAVIEEgbCCYIMQg8CEcIUghdSGhIc4h+yInIlUigiKvIt0jCiM4I2YjlCPCI/AkHyRNJHwkqyTaJQklOCVoJZclxyX3JicmVyaHJrcm6CcYJ0kneierJ9woDSg/KHEooijUKQYpOClrKZ0p0CoCKjUqaCqbKs8rAis2K2krnSvRLAUsOSxuLKIs1y0MLUEtdi2rLeEuFi5MLoIuty7uLyQvWi+RL8cv/jA1MGwwpDDbMRIxSjGCMbox8jIqMmMymzLUMw0zRjN/M7gz8TQrNGU0njTYNRM1TTWHNcI1/TY3NnI2rjbpNyQ3YDecN9c4FDhQOIw4yDkFOUI5fzm8Ofk6Njp0OrI67zstO2s7qjvoPCc8ZTykPOM9Ij1hPaE94D4gPmA+oD7gPyE/YT+iP+JAI0BkQKZA50EpQWpBrEHuQjBCckK1QvdDOkN9Q8BEA0RHRIpEzkUSRVVFmkXeRiJGZ0arRvBHNUd7R8BIBUhLSJFI10kdSWNJqUnwSjdKfUrESwxLU0uaS+JMKkxyTLpNAk1KTZNN3E4lTm5Ot08AT0lPk0/dUCdQcVC7UQZRUFGbUeZSMVJ8UsdTE1NfU6pT9lRCVI9U21UoVXVVwlYPVlxWqVb3V0RXklfgWC9YfVjLWRpZaVm4WgdaVlqmWvVbRVuVW+VcNVyGXNZdJ114XcleGl5sXr1fD19hX7NgBWBXYKpg/GFPYaJh9WJJYpxi8GNDY5dj62RAZJRk6WU9ZZJl52Y9ZpJm6Gc9Z5Nn6Wg/aJZo7GlDaZpp8WpIap9q92tPa6dr/2xXbK9tCG1gbbluEm5rbsRvHm94b9FwK3CGcOBxOnGVcfByS3KmcwFzXXO4dBR0cHTMdSh1hXXhdj52m3b4d1Z3s3gReG54zHkqeYl553pGeqV7BHtje8J8IXyBfOF9QX2hfgF+Yn7CfyN/hH/lgEeAqIEKgWuBzYIwgpKC9INXg7qEHYSAhOOFR4Wrhg6GcobXhzuHn4gEiGmIzokziZmJ/opkisqLMIuWi/yMY4zKjTGNmI3/jmaOzo82j56QBpBukNaRP5GokhGSepLjk02TtpQglIqU9JVflcmWNJaflwqXdZfgmEyYuJkkmZCZ/JpomtWbQpuvnByciZz3nWSd0p5Anq6fHZ+Ln/qgaaDYoUehtqImopajBqN2o+akVqTHpTilqaYapoum/adup+CoUqjEqTepqaocqo+rAqt1q+msXKzQrUStuK4trqGvFq+LsACwdbDqsWCx1rJLssKzOLOutCW0nLUTtYq2AbZ5tvC3aLfguFm40blKucK6O7q1uy67p7whvJu9Fb2Pvgq+hL7/v3q/9cBwwOzBZ8Hjwl/C28NYw9TEUcTOxUvFyMZGxsPHQce/yD3IvMk6ybnKOMq3yzbLtsw1zLXNNc21zjbOts83z7jQOdC60TzRvtI/0sHTRNPG1EnUy9VO1dHWVdbY11zX4Nhk2OjZbNnx2nba+9uA3AXcit0Q3ZbeHN6i3ynfr+A24L3hROHM4lPi2+Nj4+vkc+T85YTmDeaW5x/nqegy6LzpRunQ6lvq5etw6/vshu0R7ZzuKO6070DvzPBY8OXxcvH/8ozzGfOn9DT0wvVQ9d72bfb794r4Gfio+Tj5x/pX+uf7d/wH/Jj9Kf26/kv+3P9t//9WUDhMaBcAAC8KQ20QNYvatm0kWfuPndzbpYqICcgA9PAHR2kf8o9A8pCUoryi0p7KXLQGbduxSc7atm3btm3btm3btm3btm1bcd7nfT/0dv56vTtTi6k1wulgzdiY6vkV27ZtPFUxvti2k67YTjrVE9t27phfjMkvY6aCro6TqZo46U2V7Uytd+84WVwxeitO1piKbdtW1+6v2S9OumrRNVUzcfJrKrYi+j8BbmLbdhX/qn/1wnxyXHYQSX0r7CAAH5j4BvCFklxRPilookJZxIH/J8DStW2nbTl77rJt27Zt27Zt27ZtnlO2bds+tso1v/nNNfceFXzVi23btm3bSde28aZl27bxNna6tm17ZQzbto1WadcviK1WuqcVlWZaOW9ss2Xri23b9qk3ttmyUx5DAm3bplvdGxdJqrC2bdu2bdu2bX7btm3btlWd/uj/BHT5n//5n//5n//5n//5n//5n//5n//5n//5n//5n//5n//5n//5n//5n//5n//5n/9X+cciDi/bt3rPpr0bDqwWXEL5CysU1KPG2nx2pfAsjn++iwYAaF+I70eqD/PeXXlbi1FDyCp7IcWqbkroq9dY8vkvzazTvNtgbgQhJa99/+SfA6N95gS/lTU8orRy15lb+teZ0bPM8fwqcm07QVEpKt3YuIbaWBYYHWTI9NLgqurc8Jwsz2pGZzm2uYI3D6ygyB0toyuMQp8lBKHrunYQOWbbwWpciTOMiOiCjvdn1qeLW1F5e20VtWVW0VbSTO4Hkn6jAVwbyN6mUlS4ppM1IzIk9abahj40ohDJchw0Br1ri7pKilmd3AsAlkGObb7CnlAM5S0sI9GPiOjqFUehkO0/dmtfymTcv5klEDm1ByGEVbdKOxgRobRx8uyQysWsOg/LIJS4THHrxSiOWUaEjC6GJXuIaGBumRUJfWEJjHV+WWpbticZEXU/yuRgWGq+u4CtoGNPpW1wOY2InM1pcnQs3HJoOrfAFcX1dwyW0vZQMFXW4QxRNNKDQQCFbiKlveIuQd3FTlFIesZXABFZd22rtuW3wzXVUuUYRSbUlBXnX4BEPjUXQGp7ZhfQVOKMc0Tt+MT7axejS5Di/mkTftov0ONIed+MwNupk19Q4D8q4JSeRCVOFCCP/a4JoS2/iQJU0gmijUclTwSgA23fBQFCNY0KUY9lF1iAFfXk6VNWumqFclN9Z0Uc+2xF3VU3V930xf/6zYNU2VJV80H+6+HTH+S/fnDfvZorD73FGDgFZh37VPosujihy7cYBysOi0qEpLXsU+ADLk4Icxim4lAKSWvZx8JnwcUJ48Q+jE1QHKKR1rNPF04ow45Ibew6CGvKZ+OEk7dTd8WhZbKmfDZNGEFAFa2XUxwWEz0bmATEeicBsRGT4IK+xdoMbALt1Q6zibXzYGLQHk/gW1BvUh4dm27CsKEKwmrsau7ujbvr2KS7u7t7ZIIM3Y04FUHKqEfcI69Hlbe+tUfd6uoFad15q4M96vVo2qP1N7/ZaKYA1vZWJ3tSfutb31pnJptgird6+sjBhEnlNDdfVFq/ZwEHG3z4CWRVde/QPQZBvjDirAXpIhK2EiVYEjk5smRbIWUPfTS+HwK+Njkd1ELANdWQgtpMlhExkdRKRLYpNGK9BGNtMNtTsSz6AjMK1Z1Gs90Kk9QncayFdWNd3KXJ1FwOf48ZJVWdluJmkvqnsOuwFiJR6i1cBTDKqzn9FhkCSYMSl2VFzAMYU/e2yb9gRrseuWcLcVtec0Fbil9ec/U8IgiqKuYKrkokDUxBnrkno+u5ynR+bxtUmIkX375x9KtVwrLY5wxA+7bW05VVaePXDQBgZIAEvxaxvo3gqCQV5WLJBCGNkaLqxUDdlpT6CR0iTaPcPlVqCIlIKskSuTE0nu5ltHTRdV3XIwNpZXJJM0oy53j0Tw7cpfn4MkYzP5/k20POoF5UFwZJIRGFGG2AjLPLzVbA4xNpkpSayQCm26TrRCSlJAqvoK4AdJzJ+CRViQwXXOQIAYxChrmleyv/7aVMVdpZY23pY2xvDHYVrTEZbXIyr2IEdfPMCPw6S3M7ENdfYETWXTqrFveySCHxenJd7aKhDFMQgZe6zxwykwZJxejPFIhJqtPrWkQrTiR1dyISbUkGoNmFeH5trwebyFQy92Ri74CJfAK0z7rwDeCBCLQqW7fLKPa2GKtYfJFJQfy1GhcLVdFVAWy44tmJNNh2oK8Np8RSM+3KMRaSxhSamd0JAxARGVZbTVMYBln2ZGQrIi3964CIDFprzVbI2gNDqGR0FQCt7qNUmMt/HUkpyZJkXWZo1AL0UUa0pCIpfhv9tB01SR0pdJLG1L92Ko8MQESATzcVG1yIJG0Nw8n4MiAipHOrWb9BeOEpYah7ajIk58MB4AosZYblrTmOf4DWvNyjKRVfZpJS0u9PBO3agEWlSiqSV6uX/mMmqWci4wgunuJTRkQDAsoejkQiaTuRqCk/AUSEZF8Gp5M4iEm8P8H1TJ/GszLKcv35pFJWwSSlpCg7hEHQ64pJhYrTS84k9UuTpZNBOEyGCy94d0ZEAxL81pkF2bSzQUBTJx5kPTgznXPgkeYS4voDKA3LUincQyMpKbjC+4KB4DddEslt66N2EpBSCwVdwnkB13pXTIYKJnoVAxCRr9uvLDlytNTvC5ZE4vVEoa6HIpILXQP2brl5YBQysCnRT+tRKNpyIimJ3aC7blhsXCS6rD6eTlJK2nd6l2ig8pSYNJQH4Yu3QgYqddIARIQMr56YnGkmCRARObogOLPKlhSfJf4+hedVtgZjRab9FexEJRe8GVDIcf4tak1LMQGm7I+TOmkTO0kpKTOzgcy/uxiA6lVWo4d6SEpJYb0fyqp+rsZtiNB5GIZZViAicuyz/RiQQ9mcYsEGTYMoJPEu+q5vGVGwQ59cDg2iMYLM11jucQVsN4BNoPPYN7A6vQhMxa1QKNInKSVVCn1q6eKEJKlMAJcgQYS6NcdSSpqsb4idz0YiOoYK7LZLBujaUSMiP3DX5FAHfhrNBnTkRUSHyejKO0bGOeAEcBboimPj+vJfRNmeBJkBlA+PZsj4ygA6RERFrDNxE2nqRA+vkwreU7IFVtul3nqMkqSkigCYfiyIQ7fzkOJeUL9TJCSQ71NDQy6kt/X8lRHRAA7M+HIpk7frT+nkDlTbCKIYRJTvztbCL1cD+kfJ6JjPHxr1YZz3bppaS/Shm8hnRkSOdaGG2jGJRETr2RIiIrxCnSiHFEoiS8Lf8qufw5GUkpsFgDwEC+rkOL6UUvIzNKABEe1BA/LX39CKGq0N0C8tabovbY+VuEzEnyCis4zKu9xbdnbBsvEK68B/PwtRJpfBYsupPAZE5AS/npuES9WC06gTOWiQUhLvqdi/6yNBKaWkOylKVJB/neZgwYO1YMUCrlWL3ev01p2dDKJrgE73ZvtmHip2JQuw0I1ElNyLDV7u9he8YLbojrXicMLJvCZqNVKLYLvgs4TICX/ZPIm7cAGTDsZSJ46nTUqi+XKdQ6fPBS2oXeFJwevVqRJSUCuafJGUkgKZtSA9vXXuKoeIBvDSmwqPRvREef0gSuhLMBZRIZuCev2xStOk8TdywiWhq/evMx+iaIS0b7MAXUSErfdoTZb1FAni/LcQdaIYXaSUxL7712HlJPisGejnhBExHC5FzxfwQTWddxGSFJ8J2edqA2vaQkQDntmtrQcWqUW8Y6JeLL7WZEotlXuOhvd8ufVseVlE1GiSBSJCoh9PQ9ZTsOlZpE52EoxuUlJsu9ZknkggOcnKnvR/ioj3qRXlLZWUtibUND4v860wjlt+2gB7CAHNJtqbycoX2vMlerAbg4jWMzrnXEREI7O0ROA3STL3/YJ0bisUeMfQRDpJcvmzWUuGIklEJCOG764sL4H8+YM1ob05bGAkNIzCrzTbQgO+N8XIEnZumeE1FiAfvsyRXWV1BmusHQfTekZnl9cfmmGipVuw3hjJPhPNWZxTKfD3kpfJTDpIyWl51JCjiur+2MoKEZEnbQBGWJSREL0i10Pxf/cyyyEaMPEAyjao3UAQIQIAXj8cfT2j8OsuIvGqrApfT9IxBdmfUCoAPLjatFyItElK1FkF0+sw3FqFb5FAhtubtgHS5Oiu0xei92yu0o70bmgDXCmEtO6EFO3JQu6WqT0EC2HSb6b7FVnWUQYCN8m3f5KKQtSCxagWAPo60kCREmmRlOKkKt2FpIXev2uNelToFT/RspiMSFLu/TEPwW0wczEaWAI5+edVNctRJ1ZzAynIa88oLKt2vUV6pyQdiibK/LyCAcC8tqGJNEgiFWRFWv4tndU9iyQ5Na22qIFISnpiv4hoQO3iVnyPJZDBBnXumMA3sGJddRNJBrVgE+S9h+RHoYPg02oGYFegC2lQvSrQbvAkIr5/q4PYSCRp6MpdBZ0nwoo8e0f0ilmBrSwlaBjZfWowgf5rRx5ki885kpSNERmiQ6oawK7PYyE9oPU9uBARBb2hRlDrSawiiSZLKx9P2QdwIikleW9tBCgi9VIpP2bPUVTKt2utLGZVnnvz21H62IcORvKCwKnJ5vBMFgREEFI2gONYSA9AZ1fcof/aeob2PCRGKjrS7TEKRCHEd+f1nKc7MgdEfhbZHZkugqGUFA7UOSnpQ68F7x9fL+TD2YrQWIRC1oLrt4aWX632Frg1MsVWCAGvQL9I5cAInyUrgFxJD+RwNVgVioyrqQxkP0UjiQbVG1iO7J98EgE0map0oJdlkhXo7xikE4XdFqwOUddWq/VbHk7ivQDLGFU74MWVjA9/T41IE3GyP4GVotYi5sT+LsuzLEjnluIBJEDGB5sCvJMa0VZ8Qve+UT1FXn93HhDwQFqqR6NMxge0/gomklIST5a/I/T26Q8Vvh4stH9ZikXFXaoHimYSUNQGoqk0YVHNlLnz1Dd008In9wO9Ci54Din3ts5sOoPrbBFcwp982yuKuSnVAwuqvNRQtWgztGhFUlDuoDPUsYt2Te6nyZ+GZcQ2J0CuplH52GkICrJ9q2Fo6D6Nbq/RpXRXlg6NhdO/Diaoos2/0NJZVGGP8oFcSEr6uVfo/xhS0iI+YWREzSASd+TFyJNU23A5h6h/lXh/MSKncW90UX9zehB4g/ioHx1aiPiDMOCliA84Low6TyKX6ckyvHA8vznBb526k/P7ZuTMLozBIn97LwFnd5IU0IPOHs/SYMid7u1bfmHcb812ojkg2ZFrTTWDy5MfjEIuYFuSb88VhUL+MxKld83WvZsLfrsg8IAqyJLyI/KulevBeH8CM7NpI6T0v/2cgLLe3bUXcEtl7xkX14Dy7M325KDq6oupzQavNoJuNr0YnfkGk6/grQMm4am5HQC0D5M8uHcjtcV8xd/7GNpiST2gmtnbIU77xNUmMZWxNbvMiCez8EXbGdB4T05HwPUAIp+n+rpHqSwfPI4zzB18hLNUCfiRTPPz4N2JV2tkv7tUr8AI6+Swxh2w2LdGD7MvZHpxVJ668sokoXTd+ZH6E5g83wle9pwdZwzj/s3vWwAA8y145VbEZlRkZwC52GcZkWGS7QYCMEgh8o1foayUFL0Afn2YBN4sPM4cFg7Jl5Ht31K/AoigZiuS+8vslNvOiBjPrw5930F/HIM9dQW9GV8GOWS9K1Vlz5YKBCtpCsK7X+BJvLQqOgzQl5Ed0daDYFo3k9VoOADZfFyyrxCRS54icTi6HeQ4ZussPZaaMq0PF+fGd/6RQ6zhrWh+onijierHSY/GN9qP55wCTp+Bh3VWtWEArjX4vjscbzFOBJPOvuCe4ddXegfcytM/+4w3/nNha4lRKbpfQ+hmSzNNmiy/8v2/9jDTVFfDmovbw30HqnoTS3vsRfJEUxHR1TdbEGoDRb/3EdeyvNf94wF7ahCFQEQU/c+r7QHRhr84kIdZ1uYIwMZroAOG9WjTo9Zy+xOIelIFU/bHLcIyTKDBClG/SkW53C9yQvmhC+7p+Bf5UfjsMK/nEuqcv+53XMqJ9zSLj7imuf2QU/2fw/QKu3R3/hrTK+1f+lmmTdvyvJ/N/TX1eHvoVCtm3nRBUc80zib+CQDzD//rI53Hy6Vm+T3zTuwLLvWo0XIYKWXjP3J/QC+HWOfQfEfex/0/y7RiF17k0Bhmmpc+1Gb+F73pnGMOzw7VwHnBLX3wNYfjF9A46DYLfdcXSFx/vgknGZUjCiVq+t4TzgOuFftCl84KyvRPpg9ecDhERP3XsYgbAJxoH8nu9Jx+4S9bANjPPrwHvc4/2wFo2Q8wJAea9j8OUP5h/WUWsQLz+lezZ4hvAIS5ZwA2K3iQPQDzyP/ftxtwVM/o5EH7xN/TIu4F5ieFlVTIk/7e8+brswFMzwGA1wdwsizo/8WLuuZLN7+e+NGfm0a7JcnSxgVc1rDoTYDIr6IhRLd3HrZALny9gkJEH7zg9PTZSYHTKj76t03MDpwX/vIE7A7hDaHfLYi7bRhi8928n/cGAjsnD7Y8JnxsG1rD3DVMAYH72wMB93A//NMNyLp2fGSVdmbcndmLaaDxOYIVM63mD1B19n09DHpCfk7A74a9HuYLTrldki1yLQoNqLXS9PFZAfE8I8gQUWv+sSyABeZwTFnZzza/Mc16BVU+vYwsnAPcQrD/BwMfP+d1+EYqeCq/WNS5bSs+nbjDPU3mR5vzpvkY91PsfwQIVnxynN/7VWve50sfwV42scrXH8MyfuF3Y0tmrqI3vRIY9HDn4Q8ex6G75Hjavc54Q5lSJ1nAOpdDBwvLuE9adw1A5IWT+YcxQcQlzigKo7HbDva//cpZrGvKT23a8rpycaEbeLMkf/8drngmRaVvAvDxK9qtZ5Vq62dfqw0VAd+Ez/GNMDNPsH8+g7Y5LGO+iIe2x9I2AcH0MwFz3Ob6Kz7V32/BdrNMtuDII/MffJ8nO/Yv8LpzAIPO/dEf2zdwUky7lenvObfXTS/RdJgBAkQwI7IvdGsroOHksjwLJvZ+W1F4UU5H/G89yRaPnbIiFPDR7OYxBFds/lQtYf85KR59TsE5r+pbYPaDp65mfIsD/M+Ei43vCDu+mt+wjqjNJ7/7YuOQvXnQ5Qj+vtEzAMAP5gZuwXbA9pb5c57l1usMwNcUfPb5guP4eCIw8nVWQjSHKV8gEVIw9lkLSy53gCxDCaZ3iV1EA35CauoHLzh9EDTlgfBFdtsnHxrKeY+kmG80xeH+yQ9VfDSBcx7oOn6HsXUwP/X6uNghgLOF6xGnWkTbHPw3rJft8fGnCmq1tKrwAtECIcyb9K6Y13m8p9sJlrlKxd5WwG7YHrcGoo9P2S2SoOx3GyDHH41Es6IQpEyOgQuZXQgvpK7caQ0jIDh6dzM/+oTbM8NPmZ5Mii05XXHD+PhuN3FSG8WDN73iMT0Cm3dA3ZY8xjVjgUDvB97vhIdealq1s338RDBHnDIGet3KT45/unniHhVKxbjJHrF288gu63xGfxDe32X9jhh07jVc0w+a+vl8ryfa9J6WJhPU2ACWgTvfhWTDEwpZm/LjFS+OVJX572DSExAQ2X7gbd/841XuxCcAf8+HHy/skJTL5qHjDXwAW2IX+7Rv6hHAZqI1OfDtRXs67UdfDOCJh4CffbDDdbx/8df8Ew3/dIb4I0eFqvk8/Dp//5weytUuwpmi2x+I5Ktq0WwFp3siqbL9olWQcGYArd//3ffqEQB8bRhwff0mfPt31WWBxgDXpQ305ZltAKCup+IXk91L9HoIj/Rg4BZHTNf3LVwfz9OrDTMYVF+/bTWYhrn/uy+0bQEcx5xuXeMgGzSDZgHrBQaTIAV6mbOMAK/bsBFp/egPzjwCgGn3tJ6rucLVJ6xsV9oFrjqLBkj0Y6EbO7EkJXpjJhj2QI/mkK4a8fasDhGNRB+AMzzzj6SIn3e1T/K5bSzZ//zP//zP//zP//zP//zP//zP//zP//zP//zP//zP//zP//zP//zP//zP//zP//w/xXdWCQE=" data-src="{{$image_icons[$id] ?? $no_image}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
					
						
						
						 
                    </div>
					
				
					<div class="single-title">
                        <h3>  {{rashghifalllists($default_language->id,$id)}}  {{$id}}</h3>
                    </div>
					<?php
					
						$index = $id;
						$field = 'description_' . $index;
				
					?>


                    <div class="single-dtls" >
                     
							<span id="copyMsg" style="float:right" class="copy-success">Copied!</span>
							<div style="clear:both"></div>
							
						 
						  <div id="newsText">{!! $data->$field; !!}</div>
						  
                    </div>
				
							
							
			
					
					
					 <button onclick="playBanglaNews('newsText')" class="btn btn-primary" >🔊 {{ __('Listen to the news') }} </button>
					
	

					  <div class="row" style="margin-top:15px;">
						<div class="col-md-4 col-sm-4">
							<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,date('W')])}}">
								<span class="name fnten_bn">{{ __('Weekly Horoscope') }}</span>
							</a>
						</div>
						<div class="col-md-4 col-sm-4">
							<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,54])}}">
								<span class="name fnten_bn">{{ __('Monthly Horoscope') }}</span>
							</a>
						</div>
						<div class="col-md-4 col-sm-4">
							<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,53])}}">
								<span class="name fnten_bn">{{ __('Yearly Horoscope') }}</span>
							</a>
						</div>
					</div>
				
					
					
					<div class="add" style="padding-bottom:10px;">
						 <div class="widget_area">
								<?php echo $Details_Page_Bottom_Size_750x95?? null;?>
						 
						 </div>					
					 </div>
					
				
					
										
                    <div class="sgl-page-social-title" >
                        <h4>{{ __('If you liked this news, please share it.') }}   </h4>
                    </div>
				
                      <div class="sgl-page-social">
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
					
			
					 
			 				   
			
				
					  <?php 
						if($default_language->id==1){
							$title_size=80;
						}else{
							$title_size=80;
						}
					?>	
									
                
					<?php }else{?>
					
					 <div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<p class="text-danger text-center">{{ __('This Category has no news.') }}</p>
							</div>
						</div>
					</div>
					
					  <div class="row" style="margin-top:15px;">
						<div class="col-md-4 col-sm-4">
							<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,date('W')])}}">
								<span class="name fnten_bn">{{ __('Weekly Horoscope') }}</span>
							</a>
						</div>
						<div class="col-md-4 col-sm-4">
							<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,54])}}">
								<span class="name fnten_bn">{{ __('Monthly Horoscope') }}</span>
							</a>
						</div>
						<div class="col-md-4 col-sm-4">
							<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,53])}}">
								<span class="name fnten_bn">{{ __('Yearly Horoscope') }}</span>
							</a>
						</div>
					</div>

					<?php }?>
					
							
					<div class="add"><?php echo $Details_Page_Bottom_Division_Size_750x95 ?? null;?></div>
					
					
					
                </div>
				
				
				
                <div class="col-md-4 col-sm-4">
                    <div class="tab-header">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs nav-justified" role="tablist">
									<li role="presentation" class="active"><a href="#tab21" aria-controls="tab21" role="tab" data-toggle="tab" aria-expanded="false">{{ __('Latest News') }} </a></li>
									<li role="presentation"><a href="#tab22" aria-controls="tab22" role="tab" data-toggle="tab" aria-expanded="true">{{ __('Popular News') }} </a></li>
								</ul>

								<!-- Tab panes -->
								<div class="tab-content ">
									<div role="tabpanel" class="tab-pane in active" id="tab21">

										<div class="news-titletab">
										
																																		
											 @foreach (is_recents($default_language->id) as $is_recent)
                                  
                                                    @if ($is_recent->image_big || $is_recent->rss_image)
                                                        @if ($is_recent->image_big)
                                                            <div class="small-img tab-border">
                                                                <img width="600" height="337" data-src="{{asset('assets/images/post/'.$is_recent->image_big)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$is_recent->category->slug,$is_recent->slug])}}">{{strlen($is_recent->title)>$title_size ? mb_substr($is_recent->title,0,$title_size,"utf-8") : $is_recent->title}} </a></h4>
                                                            </div>
                                                        @endif

                                                        @if ($is_recent->rss_image)
                                                            <div class="small-img tab-border">
                                                                <img width="600" height="337" data-src="{{asset('assets/images/post/'.$is_recent->rss_image)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$is_recent->category->slug,$is_recent->slug])}}">{{strlen($is_recent->title)>$title_size ? mb_substr($is_recent->title,0,$title_size,"utf-8") : $is_recent->title}} </a></h4>
                                                            </div>
                                                        @endif

														@if ($is_recent->post_type == 'audio')
															 <audio controls>
																<source src="{{asset('assets/audios/'.$is_recent->audio)}}" type="audio/mpeg">
																Your browser does not support the audio element.
															  </audio>
														@endif
                                                       
                                                    

                                                    @else
                                                        <div class="small-img tab-border">
                                                            <img width="600" height="337" data-src="{{asset('assets/images/nopic.png')}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" >
                                                        </div>
                                                    @endif
                                      
												@endforeach
											
																						
										</div>
									</div>
									<div role="tabpanel" class="tab-pane fade" id="tab22">                                      
										<div class="news-titletab">
										
										 @foreach ($top_views as $top_view)
											@if ($top_view)
												 @if ($top_view->image_big || $top_view->rss_image)
													@if ($top_view->image_big)
														<div class="small-img tab-border">
															<img width="600" height="337" data-src="{{asset('assets/images/post/'.$top_view->image_big)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$top_view->category->slug,$top_view->slug])}}">{{strlen($top_view->title)>$title_size ? mb_substr($top_view->title,0,$title_size,"utf-8") : $top_view->title}} </a></h4>
														</div>
													@endif

													@if ($top_view->rss_image)
														<div class="small-img tab-border">
															<img width="600" height="337" data-src="{{asset('assets/images/post/'.$top_view->rss_image)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$top_view->category->slug,$top_view->slug])}}">{{strlen($top_view->title)>$title_size ? mb_substr($top_view->title,0,$title_size,"utf-8") : $top_view->title}} </a></h4>
														</div>
													@endif

													@if ($top_view->post_type == 'audio')
														 <audio controls>
															<source src="{{asset('assets/audios/'.$top_view->audio)}}" type="audio/mpeg">
															Your browser does not support the audio element.
														  </audio>
													@endif
												

												@else
													<div class="small-img tab-border">
														<img width="600" height="337" data-src="{{asset('assets/images/nopic.png')}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" >
													</div>
												@endif
											@endif
										@endforeach
																						
										</div>                                          
									</div>
									
								</div>
							</div>
							  <div class="panel panel-default" >
								<div class="panel-heading" style="background:#600001;color:white;">
									<h3 class="panel-title fnten_bn">
										
										{{ __('See Horoscope with just one click') }}
									</h3>
								</div>
							</div>
							<div class="calendar" style="margin-bottom:15px;">
								<?php foreach (rashghifalllists($default_language->id) as $id => $title):
								$titles=str_replace("<br/>","",$title);
								
								?>
									<a class="cell" href="{{ route('frontend.rashifall',[date('Y-m-d'),$id,55,slug_create($titles)])}}">
										 <span class="icon"><?php echo '<img src="' . $zodiac_icons[$id] . '" alt="Zodiac Icon" width="40" height="40">'; ?></span>
										<span class="name"><?php echo $title; ?></span>
									</a>
								<?php endforeach; ?>
							</div>
								
							  <div class="panel panel-default" >
								<div class="panel-heading" style="background:#600001;color:white;">
									<h3 class="panel-title fnten_bn">
										<a href="{{ route('front.newspopularyesterdday') }}">{{ __('See yesterday’s popular news with just one click') }}</a>
									</h3>
								</div>
							</div>
								
							
					 <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0" nonce="XXXXXXXX"></script>
						 
						 <div class="fb-page" 
							 data-href="https://www.facebook.com/profile.php?id=870357109485142"
							 data-tabs="timeline"
							 data-width="360" 
							 data-height="100" 
							 data-small-header="false" 
							 data-adapt-container-width="true"
							 data-hide-cover="false" 
							 data-show-facepile="true">
						  <blockquote cite="https://www.facebook.com/profile.php?id=870357109485142" class="fb-xfbml-parse-ignore">
							<a href="https://www.facebook.com/profile.php?id=870357109485142">Amar Bangla</a>
						  </blockquote>
						</div>
					<div class="add">
						 <div class="widget_text widget_area"><div class="textwidget custom-html-widget">
		
						 
						 <?php echo $side_bar_ads ?? null;?>
						 
						 </div></div>					</div>
						 
						 
						
						
						
						<div class="calendar-container">
						  <div class="dropdowns">
							 <select id="monthSelect" class="form-control" style="width:160px; display:inline-block;"></select>
							<select id="yearSelect" class="form-control" style="width:120px; display:inline-block;"></select>
						  </div>
						  <div id="calendar"></div>
						</div>


							
                </div>
            </div>
			
            </div>
         </section>        



 <script>
 	const baseUrl = "{{ route('front.news_archive') }}";
 <?php if($default_language->id==1){?>
			
	const monthMap = ["জানুয়ারি","ফেব্রুয়ারি","মার্চ","এপ্রিল","মে","জুন","জুলাই","আগস্ট","সেপ্টেম্বর","অক্টোবর","নভেম্বর","ডিসেম্বর"];
	const weekdaysBn = ["রবি","সোম","মঙ্গল","বুধবার","বৃহস্পতি","শুক্রবার","শনিবার"];

	function toBanglaNumber(num){
		const bnNums = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
		return num.toString().split('').map(d => bnNums[d] || d).join('');
	}

	function pad(n){ return n<10 ? '0'+n : n; }

	function generateCalendar(month, year, baseUrl){
		const daysInMonth = new Date(year, month+1, 0).getDate();
		const bnMonth = monthMap[month];
		const today = new Date();
		const isTodayMonthYear = (today.getFullYear() === year && today.getMonth() === month);

		let table = ``;
		table += `<table class="calendar-table"><tr>`;
		weekdaysBn.forEach((day,i) => table += `<th>${day}</th>`);
		table += "</tr><tr>";

		let firstDay = new Date(year, month, 1).getDay();
		for(let i=0;i<firstDay;i++) table += "<td></td>";

		for(let d=1; d<=daysInMonth; d++){
			let weekday = new Date(year, month, d).getDay();
			let url = `${baseUrl}?date=${year}-${pad(month+1)}-${pad(d)}`;
			let tdClass = (weekday==0 || weekday==6) ? 'weekend' : '';

			// Highlight today
			if(isTodayMonthYear && d === today.getDate()) tdClass += ' today';

			table += `<td class="${tdClass}"><a href="${url}" target="_blank">${toBanglaNumber(d)}</a></td>`;
			if(weekday===6 && d!==daysInMonth) table += "</tr><tr>";
		}

		let lastDay = new Date(year, month, daysInMonth).getDay();
		for(let i=lastDay+1;i<7;i++) table += "<td></td>";
		table += "</tr></table>";
		return table;
	}


	const monthSelect = document.getElementById('monthSelect');
	const yearSelect = document.getElementById('yearSelect');


	for(let m=0;m<12;m++){
		let opt = document.createElement('option');
		opt.value = m;
		opt.text = monthMap[m];
		monthSelect.appendChild(opt);
	}

	const currentYear = new Date().getFullYear();
	for(let y=currentYear-10;y<=currentYear+10;y++){
		let opt = document.createElement('option');
		opt.value = y;
		opt.text = toBanglaNumber(y);
		yearSelect.appendChild(opt);
	}

	function updateCalendar(){
		const month = parseInt(monthSelect.value);
		const year = parseInt(yearSelect.value);
		document.getElementById('calendar').innerHTML = generateCalendar(month, year, baseUrl);
	}
	monthSelect.value = new Date().getMonth();
	yearSelect.value = currentYear;
	updateCalendar();

	monthSelect.addEventListener('change', updateCalendar);
	yearSelect.addEventListener('change', updateCalendar);
<?php }else{?>
	const monthMap = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	const weekdaysEn = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

	// Pad month/day
	function pad(n){ return n<10 ? '0'+n : n; }
	// Generate calendar
	function generateCalendar(month, year, baseUrl){
		const daysInMonth = new Date(year, month+1, 0).getDate();
		const engMonth = monthMap[month];
		const today = new Date();
		const isTodayMonthYear = (today.getFullYear() === year && today.getMonth() === month);

		let table = ``;
		table += `<table class="calendar-table"><tr>`;
		weekdaysEn.forEach((day,i) => table += `<th>${day}</th>`);
		table += "</tr><tr>";

		let firstDay = new Date(year, month, 1).getDay();
		for(let i=0;i<firstDay;i++) table += "<td></td>";

		for(let d=1; d<=daysInMonth; d++){
			let weekday = new Date(year, month, d).getDay();
			let url = `${baseUrl}?date=${year}-${pad(month+1)}-${pad(d)}`;
			let tdClass = (weekday==0 || weekday==6) ? 'weekend' : '';

			// Highlight today
			if(isTodayMonthYear && d === today.getDate()) tdClass += ' today';

			table += `<td class="${tdClass}"><a href="${url}" target="_blank">${d}</a></td>`;
			if(weekday===6 && d!==daysInMonth) table += "</tr><tr>";
		}

		let lastDay = new Date(year, month, daysInMonth).getDay();
		for(let i=lastDay+1;i<7;i++) table += "<td></td>";
		table += "</tr></table>";
		return table;
	}


	// Populate dropdowns
	const monthSelect = document.getElementById('monthSelect');
	const yearSelect = document.getElementById('yearSelect');


	for(let m=0;m<12;m++){
		let opt = document.createElement('option');
		opt.value = m;
		opt.text = monthMap[m];
		monthSelect.appendChild(opt);
	}

	const currentYear = new Date().getFullYear();
	for(let y=currentYear-10;y<=currentYear+10;y++){
		let opt = document.createElement('option');
		opt.value = y;
		opt.text = y;
		yearSelect.appendChild(opt);
	}

	// Update calendar
	function updateCalendar(){
		const month = parseInt(monthSelect.value);
		const year = parseInt(yearSelect.value);
		document.getElementById('calendar').innerHTML = generateCalendar(month, year, baseUrl);
	}

	// Default selection
	monthSelect.value = new Date().getMonth();
	yearSelect.value = currentYear;
	updateCalendar();
	
	monthSelect.addEventListener('change', updateCalendar);
	yearSelect.addEventListener('change', updateCalendar);
			 
<?php }?>






 document.addEventListener('DOMContentLoaded', function () {
    var widget = document.querySelector('.poll-widget');
    if (!widget) return;

    var questionId = widget.dataset.questionId;
    var csrfToken = '{{ csrf_token() }}';

    // English to Bangla digit conversion
    function toBanglaNumber(num) {
        const engToBn = {
            '0': '০', '1': '১', '2': '২', '3': '৩', '4': '৪',
            '5': '৫', '6': '৬', '7': '৭', '8': '৮', '9': '৯'
        };
        return num.toString().replace(/[0-9]/g, digit => engToBn[digit]);
    }



  

  
	
	
});


 </script>
 <script src="https://code.responsivevoice.org/responsivevoice.js?key=mlakr0m7"></script>
 <script>
	function playBanglaNews(elementId) {
	  const text = document.getElementById(elementId).innerText;
	  responsiveVoice.speak(text, "UK English Female");
	}

	function copyHiddenValue() {
	  // Get hidden input value
	  var hiddenValue = document.getElementById("hiddenUrl").value;

	  // Create a temporary input to copy the value
	  var tempInput = document.createElement("input");
	  tempInput.type = "text";
	  tempInput.value = hiddenValue;
	  document.body.appendChild(tempInput);

	  // Select and copy the value
	  tempInput.select();
	  tempInput.setSelectionRange(0, 99999); // For mobile devices
	  document.execCommand("copy");

	  // Remove temporary input
	  document.body.removeChild(tempInput);

	  // Show success message
	  var msg = document.getElementById("copyMsg");
	  msg.style.display = "inline";
	  setTimeout(function() {
		msg.style.display = "none";
	  }, 2000);
	}
  </script>
@endsection

