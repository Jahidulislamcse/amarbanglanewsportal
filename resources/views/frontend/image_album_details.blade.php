@extends('layouts.front_custom')

@push('css')
<style>
    video {
        width: 100%;
        height: auto;
    }

</style>
@endpush

@section('contents')
	<?php


	$placementMap=config('placements');
	$side_bar_ads="";
	
	foreach (is_ads(array(19,20)) as $ad) {
		if (!isset($placementMap[$ad->add_placement])) {
			continue; // Skip if placement doesn't match
		}
		$varName = str_replace(" ","_",$placementMap[$ad->add_placement]);
		
		if($ad->add_placement==19){
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
	?>

 <section class="singlepage-section">
		 
		    
									
					<div class="container">
							
										
            <div class="row">
                <div class="col-md-8 col-sm-8">
				
					<div class="add">
						 					</div>
					
													
                   
			
                    <div class="single-title">
                        <h3> {{$image_album->album_name}}</h3>
                    </div>

					<div class="view-section">
					 <div class="row">
                           
                            <div class="col-md-11 col-sm-11 col-xs-10">
                                <div class="reportar-sec">
                                    <div class="reportar-titles">											
										{{$user_info->name ?? 'No Name';}}
										
										
                                    </div>
                                    <div class="sgl-page-views-count">
                                        <ul>
                                            <li> <i class="fa fa-clock-o"></i>  প্রকাশের সময়  : {{enToBn($image_album->created_at)}}
											</li>

                                        </ul>
                                    </div>                                 
                                </div>
                            </div>
                        </div>
                    </div>
			
                    	

				  <div class="single-img">
					
					<img  width="600" height="337" data-src="<?php echo asset('assets/images/image-album/'.$image_album->photo); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
					
		
                    </div>
					

					
					<div class="single-dtls">
                   
						  {!! $image_album->album_description !!}
                    </div>
                

                    
					
			
					
				
					
				
					
										
                    <div class="sgl-page-social-title">
                        <h4>{{ __('If you liked this news, please share it.') }}</h4>
                    </div>
					<style>
					.linkedin1 {
						background-color: #337ab7!important;
						padding: 7px 12px !important;
					    border-radius: 0% !important;
						color: #fff !important; /* Make icon/text white */

					}
					.linkedin1:hover {
							text-decoration: none !important;
							color: #337ab7!important;
							
							background-color: white !important;
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
						}
					</style>
                      <div class="sgl-page-social">
                        <ul>
							<li><a href="http://www.facebook.com/sharer.php?u={{request()->fullUrl()}}" class="ffacebook" target="_blank"> <i class="fa fa-facebook"></i> Facebook</a></li>

							<li><a href="https://twitter.com/intent/tweet?url={{request()->fullUrl()}}&text={{$image_album->album_name}}" class="ttwitter" target="_blank"> <i class="fa fa-twitter"></i> Twitter</a></li>

							<li><a href="http://digg.com/submit?url={{request()->fullUrl()}}&title={{$image_album->album_name}}" class="digg" target="_blank"> <i class="fa fa-digg"></i> Digg </a></li>
							
							<li><a href="http://www.linkedin.com/shareArticle?mini=true&title={{$image_album->album_name}}&url={{request()->fullUrl()}}" class="linkedin1" target="_blank"> <i class="fa fa-linkedin"></i> Linkedin </a></li>

							<li><a href="http://www.reddit.com/submit?url={{request()->fullUrl()}}&title={{$image_album->album_name}}" class="reddit" target="_blank"> <i class="fa fa-reddit"></i> Reddit </a></li>
							
							<li><a href="https://plus.google.com/share?url={{request()->fullUrl()}}" class="google-plus" target="_blank"> <i class="fa fa-google-plus"></i> Google Plus</a></li>

							<li><a href="http://www.pinterest.com/pin/create/button/?url={{request()->fullUrl()}}" class="pinterest" target="_blank"> <i class="fa fa-pinterest"></i> Pinterest </a></li>
							
							<li><a href="https://wa.me/?text={{request()->fullUrl()}}" class="whatsapp" target="_blank"> <i class="fa fa-whatsapp"></i> Whatsapp </a></li>

							<li><a onclick="myFunction()" class="print" target="_blank"> <i class="fa fa-print"></i> Print </a></li>
						</ul>
						
                    </div>
					
					<script>
					function myFunction() {
						window.print();
					}
					</script>
					
	
					
									
                    @foreach ($image_albums as $image_album)
                                  
								 <div class="single-img">
	
									<img  width="600" height="337" data-src="<?php echo asset('assets/images/image-gallery/'.$image_album->gallery); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
									 
									</div>
								<div class="single-dtls">
                   
									  {!! $image_album->title !!} ({{$image_album->user_name ?? 'No Name';}})
								</div>
 
					@endforeach
				
			

					
					
                </div>
				
				
				
                <?php 
						if($default_language->id==1){
							$title_size=80;
						}else{
							$title_size=80;
						}
					?>	
                      
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
										@php
											$post = \App\Models\Post::where('id',$top_view->post_id)->where('language_id',$default_language->id)->first();
										@endphp
											@if ($post)
												 @if ($post->image_big || $post->rss_image)
													@if ($post->image_big)
														<div class="small-img tab-border">
															<img width="600" height="337" data-src="{{asset('assets/images/post/'.$post->image_big)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$post->category->slug,$post->slug])}}">{{strlen($post->title)>$title_size ? mb_substr($post->title,0,$title_size,"utf-8") : $post->title}} </a></h4>
														</div>
													@endif

													@if ($post->rss_image)
														<div class="small-img tab-border">
															<img width="600" height="337" data-src="{{asset('assets/images/post/'.$post->rss_image)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$post->category->slug,$post->slug])}}">{{strlen($post->title)>$title_size ? mb_substr($post->title,0,$title_size,"utf-8") : $post->title}} </a></h4>
														</div>
													@endif

													@if ($post->post_type == 'audio')
														 <audio controls>
															<source src="{{asset('assets/audios/'.$post->audio)}}" type="audio/mpeg">
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
					
					<div class="add">
						 <div class="widget_text widget_area"><div class="textwidget custom-html-widget">
						 
						<?php echo $side_bar_ads ?? null;?>
						 
						 
						 </div></div>					</div>
							
                </div>
				
				
            </div>
			
            </div>
         </section>        



<?php
?>
<!-- News Details Page End -->
@endsection


