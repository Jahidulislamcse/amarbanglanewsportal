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


 <section class="singlepage-section">
		 
		    
									
					<div class="container">
							
										
            <div class="row">
                <div class="col-md-8 col-sm-8">
				
					<div class="add">
						 					</div>
					
				
                    										
                    <div class="single-cat-info">
                        <div class="single-cat-home">
                            <a href="{{ route('frontend.index')}}"><i class="fa fa-home" aria-hidden="true"></i> মূল পাতা </a>
                        </div>
                       
                    </div>
			
                    <div class="single-title">
                        <h3>  {{$data->title}}</h3>
                    </div>

					<div class="view-section">
					 <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-2">
                                <div class="reportar-img">
									@if(!empty($user_info->photo))
										<img src="{{ asset('assets/images/admin/'.$user_info->photo) }}" width="100%">
									@else
										<img src="{{ asset('assets/images/noimagee.gif') }}" width="100%">
									@endif
                                </div>
                            </div>
							
							<?php
							//pr(public_path('assets/images/admin/'.$user_info->photo));
							?>

                             <div class="col-md-11 col-sm-11 col-xs-10">
                                <div class="reportar-sec">
                                    <div class="reportar-title">											
										{{$user_info->name ?? 'No Name';}}
                                    </div>
                                    <div class="sgl-page-views-count">
                                        <ul>
                                            <li> <i class="fa fa-clock-o"></i>  প্রকাশের সময়  : {{enToBn($data->createdAt())}}
											</li>
											<li class="active"> 
												<i class="fa fa-eye"></i> {{enToBn($totalViews,1)}}	 বার পড়া হয়েছে   			
											</li>
                                        </ul>
                                    </div>                                 
                                </div>
                            </div>
                        </div>
                    </div>
					

                    <div class="single-img">
						
						 <?php echo $data->embed_video ?? null;?>
                    </div>

                    <div class="single-dtls">
						  {!! $data->description !!}
                    </div>
					
				
					
				
					
				
					
										
                    <div class="sgl-page-social-title">
                        <h4>সংবাদ টি ভালো লাগলে শেয়ার করুন</h4>
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

							<li><a href="https://twitter.com/intent/tweet?url={{request()->fullUrl()}}&text={{$data->title}}" class="ttwitter" target="_blank"> <i class="fa fa-twitter"></i> Twitter</a></li>

							<li><a href="http://digg.com/submit?url={{request()->fullUrl()}}&title={{$data->title}}" class="digg" target="_blank"> <i class="fa fa-digg"></i> Digg </a></li>
							
							<li><a href="http://www.linkedin.com/shareArticle?mini=true&title={{$data->title}}&url={{request()->fullUrl()}}" class="linkedin1" target="_blank"> <i class="fa fa-linkedin"></i> Linkedin </a></li>

							<li><a href="http://www.reddit.com/submit?url={{request()->fullUrl()}}&title={{$data->title}}" class="reddit" target="_blank"> <i class="fa fa-reddit"></i> Reddit </a></li>
							
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
					
					 
			 				   
				   <!-- *(view-tab show or hide open)*-->	
				
					    
<div id="comments" class="comments-area">
 
     
     
</div><!-- #comments -->					 
			 				<!-- *(view-tab show or hide close)*-->
				
					
									
                    <div class="sgl-cat-tittle">
                       এ বিভাগের আরো ভিডিও                      
					</div>
						@foreach ($division_news->chunk(3) as $division_news_chunk)
								<div class="row">
								@foreach ($division_news_chunk as $division_new)
									<div class="col-sm-4 col-md-4">
										<div class="Name-again box-shadow">
											<div class="image-again">
												<a href="{{ route('frontend.videodetails',  $division_new->slug) }}">
													<img width="600" height="337" data-src="{{ asset('assets/images/post/'.$division_new->image_big) }}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async">
												</a>
												<h4 class="sgl-hadding">
													<a href="{{ route('frontend.videodetails', $division_new->slug) }}">
														{{ strlen($division_new->title) > 40 ? mb_substr($division_new->title, 0, 40, "utf-8") : $division_new->title }}
													</a>
												</h4>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						@endforeach
					
					
					
                </div>
				
				
				
                <div class="col-md-4 col-sm-4">
                    <div class="tab-header">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs nav-justified" role="tablist">
									<li role="presentation" class="active"><a href="#tab21" aria-controls="tab21" role="tab" data-toggle="tab" aria-expanded="false">সর্বশেষ সংবাদ  </a></li>
									<li role="presentation"><a href="#tab22" aria-controls="tab22" role="tab" data-toggle="tab" aria-expanded="true">জনপ্রিয় সংবাদ </a></li>
								</ul>

								<!-- Tab panes -->
								<div class="tab-content ">
									<div role="tabpanel" class="tab-pane in active" id="tab21">

										<div class="news-titletab">
										
																																		
											 @foreach (is_recents($default_language->id) as $is_recent)
                                  
                                                    @if ($is_recent->image_big || $is_recent->rss_image)
                                                        @if ($is_recent->image_big)
                                                            <div class="small-img tab-border">
                                                                <img width="600" height="337" data-src="{{asset('assets/images/post/'.$is_recent->image_big)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$is_recent->category->slug,$is_recent->slug])}}">{{strlen($is_recent->title)>80 ? mb_substr($is_recent->title,0,80,"utf-8") : $is_recent->title}} </a></h4>
                                                            </div>
                                                        @endif

                                                        @if ($is_recent->rss_image)
                                                            <div class="small-img tab-border">
                                                                <img width="600" height="337" data-src="{{asset('assets/images/post/'.$is_recent->rss_image)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$is_recent->category->slug,$is_recent->slug])}}">{{strlen($is_recent->title)>80 ? mb_substr($is_recent->title,0,80,"utf-8") : $is_recent->title}} </a></h4>
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
															<img width="600" height="337" data-src="{{asset('assets/images/post/'.$post->image_big)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$post->category->slug,$post->slug])}}">{{strlen($post->title)>80 ? mb_substr($post->title,0,80,"utf-8") : $post->title}} </a></h4>
														</div>
													@endif

													@if ($post->rss_image)
														<div class="small-img tab-border">
															<img width="600" height="337" data-src="{{asset('assets/images/post/'.$post->rss_image)}}" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" ><h4 class="hadding_03"><a href="{{route('frontend.postBySubcategory.details',[$post->category->slug,$post->slug])}}">{{strlen($post->title)>80 ? mb_substr($post->title,0,80,"utf-8") : $post->title}} </a></h4>
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
					
					
							
                </div>
            </div>
			
            </div>
         </section>        



<?php
?>
<!-- News Details Page End -->
@endsection

@push('js')

    <script>
     
    </script>


@endpush
