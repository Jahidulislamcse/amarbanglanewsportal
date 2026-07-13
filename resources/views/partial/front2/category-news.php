<style>
    .ftx{
       font-weight:bold; 
    }
.video-bg-n {
    background-color: #f8f8f8;
    padding: 5px 10px 10px 10px;
}


</style>

<?php if(!empty($newsItems) && in_array($section, [2, 5, 6])){ ?>

    <?php 
		if($default_language->id==1){
			$title_size=65;
			$description_first_size=350;
			$description_sec_size=220;
			$Read_More="আরো পড়ুন...";
			$Read_more_news="আরো সংবাদ...";
		}else{
			$title_size=170;
			$description_first_size=350;
			$description_sec_size=220;
			$Read_More="Read More...";
			$Read_more_news="Read More News...";
			
		}
	
        $firstPost = $newsItems[0] ?? null;
        if($firstPost && is_object($firstPost)):
            $categorySlug = $firstPost->category->slug ?? '';
    ?>
        <div class="border_image">
            <a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>">
                <?php if(!empty($firstPost->image_big)): ?>
                    <img data-src="<?php echo asset('assets/images/post/'.$firstPost->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
                <?php elseif(!empty($firstPost->rss_image)): ?>
					  <img data-src="<?php echo asset('assets/images/post/'.$firstPost->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
                <?php endif; ?>

                <?php if(!empty($firstPost->post_type) && $firstPost->post_type == 'audio'): ?>
					  <audio controls>
						<source src="{{asset('assets/audios/'.$firstPost->audio)}}" type="audio/mpeg">
						Your browser does not support the audio element.
					  </audio>
                <?php endif; ?>
            </a>
        </div>
        <div class="content-padding">
        <h4>
            <?php echo strlen($firstPost->title) > $title_size ? mb_substr($firstPost->title,0,$title_size,'utf-8').'...' : $firstPost->title; ?>
        </h4>

        <div class="content-dtls">
            <?php echo strlen(strip_tags($firstPost->description)) > $description_first_size 
                ? convertUtf8(substr(strip_tags($firstPost->description),0,$description_first_size)).'...' 
                : convertUtf8(strip_tags($firstPost->description)); ?>
            <?php if($categorySlug): ?>
                <span style="text-align:right">
                    <a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>"><?php echo $Read_More;?></a>
                </span>
            <?php endif; ?>
        </div>
        </div>
    <?php endif; ?>

    <?php 
	$postCategorySlug="";
	
	foreach($newsItems as $index => $post): ?>
        <?php 
            if($index == 0) continue; 
            if(!$post || !is_object($post)) continue; // skip null or bool
            $postCategorySlug = $post->category->slug ?? '';
        ?>
        <div class="little_img border">
            <?php if(!empty($post->image_big)): ?>
                <img  data-src="<?php echo asset('assets/images/post/'.$post->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
            <?php elseif(!empty($post->rss_image)): ?>
                <img data-src="<?php echo asset('assets/images/post/'.$post->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
            <?php endif; ?>

            <?php if(!empty($post->post_type) && $post->post_type == 'audio'): ?>
                 <audio controls>
						<source src="{{asset('assets/audios/'.$post->audio)}}" type="audio/mpeg">
						Your browser does not support the audio element.
					  </audio>
            <?php endif; ?>

            <div class="ftx">
                <a href="<?php echo $postCategorySlug ? route('frontend.postBySubcategory.details', [$postCategorySlug, $post->slug]) : '#'; ?>">
                    <?php echo strlen(strip_tags($post->title)) > $description_sec_size 
                        ? convertUtf8(substr(strip_tags($post->title),0,$description_sec_size)).'...' 
                        : convertUtf8(strip_tags($post->title)); ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
	<?php if(!empty($postCategorySlug)){ ?>
	 <div class="row">
		<div class="col-sm-12 col-md-12">
			<h4 class="more_news"><a href="<?php echo  route('frontend.category', $title);?>"><?php echo $Read_more_news;?><i class="fa fa-angle-double-right" aria-hidden="true"></i>  </a></h4>
		</div>
	</div>
	<?php } ?>

<?php }else if(!empty($newsItemsL) && $section==3){ 

$key=0;

if($default_language->id==1){
	$title_size=65;
	$description_first_size=700;	$sec_title=35;
	
if (isMobile()) {
     $sec_title=50;
 }else{
    $sec_title=35;
 }
	$Read_More="আরো পড়ুন...";
}else{
	$title_size=170;
	$description_first_size=700;
	$sec_title=35;
	$Read_More="Read More...";
	
}

 



foreach ($cat_section_list as $title => $cat){
	$key=$key+1;

	$newsItems=isset($newsItemsL[$cat])?$newsItemsL[$cat]:'';
	
	
	if($key==1){
		
		
		if($newsItems){
			
			
?>

						<div class="col-md-8 col-sm-7">					
							<div class="gallery-title">
								 <a href="<?php echo  route('frontend.category', $title);?>" style="text-transform: capitalize;">
									<?php echo $title;?>
								</a>
							</div>
								

		                    <div class="slider-padding">
		                        <div class="slide-img">
								
								  <?php 
									$firstPost = $newsItems[0] ?? null;
									if($firstPost && is_object($firstPost)):
										$categorySlug = $firstPost->category->slug ?? '';
								?>
									
									
									<div class="mySlides" style="width: 100%; display: block;">
										<div class="row">
											<div class="col-md-5">
												<div class="photo-title">
													<a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>"><?php echo strlen($firstPost->title) > $title_size ? mb_substr($firstPost->title,0,$title_size,'utf-8').'...' : $firstPost->title; ?></a>
												</div>
												<div class="photo-content">
													 <?php echo strlen(strip_tags($firstPost->description)) > $description_first_size 
													? convertUtf8(substr(strip_tags($firstPost->description),0,$description_first_size)).'...' 
													: convertUtf8(strip_tags($firstPost->description)); ?>
													  <?php if($categorySlug): ?>
													  
													  <span style="text-align:right"><a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>"><?php echo $Read_More;?> </a></span>
													  	<?php endif; ?>
												</div>
											</div>
											   
											<div class="col-md-7">
												<div class="slide-img">
												
												<?php if(!empty($firstPost->image_big)): ?>
													<img data-src="<?php echo asset('assets/images/post/'.$firstPost->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
												<?php elseif(!empty($firstPost->rss_image)): ?>
													  <img data-src="<?php echo asset('assets/images/post/'.$firstPost->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
												<?php endif; ?>

												<?php if(!empty($firstPost->post_type) && $firstPost->post_type == 'audio'): ?>
													 <audio controls>
														<source src="{{asset('assets/audios/'.$firstPost->audio)}}" type="audio/mpeg">
														Your browser does not support the audio element.
													  </audio>
												<?php endif; ?>
											</div>               
											</div>               
										</div>
									</div>
										
								<?php endif; ?>
							
										
																			
		                        </div>
		                                    
		                    </div>
							
		<style>
.slider-wrapper {
  position: relative;
  overflow: hidden;
  width: 100%;
  cursor: grab;
}

.slider-content-pic {
  display: flex;
  transition: transform 0.3s ease;
}

.slide-small-img {
  flex: 0 0 25%; /* 4 items per view */
  box-sizing: border-box;
  padding: 5px;
  cursor: grab;
}

.slide-small-img img {
  width: 100%;
  display: block;
  border-radius: 5px;
}
@media (max-width: 768px) {
  .slide-small-img {
    flex: 0 0 50%; 
   padding: 5px; 
  }
}

.small-pto-title {
  text-align: center;
  margin-top: 5px;
  font-size: 14px;
  font-weight: bold;
}
@media (max-width: 768px) {
  .small-pto-title {
    font-size: 6px; 
    text-align: left;
    margin-top: 2px;
    font-weight: normal;
  }
}
</style>

<div class="row">
  <div class="col-md-12">
    <div class="slider-wrapper" id="sliderWrapper">
      <div class="slider-content-pic" id="myCarousel">
        <?php foreach($newsItems as $index => $post): 
          if($index == 0 || !$post || !is_object($post)) continue; 
		  
		  $postCategorySlug = $post->category->slug ?? '';
		  ?>
          <div class="slide-small-img">
            <?php if(!empty($post->image_big)): ?>
              <img data-src="<?php echo asset('assets/images/post/'.$post->image_big); ?>" class="lazy" alt="">
            <?php elseif(!empty($post->rss_image)): ?>
              <img data-src="<?php echo asset('assets/images/post/'.$post->rss_image); ?>" class="lazy" alt="">
            <?php endif; ?>
			
			<?php if(!empty($post->post_type) && $post->post_type == 'audio'): ?>
				 <audio controls>
					<source src="{{asset('assets/audios/'.$post->audio)}}" type="audio/mpeg">
					Your browser does not support the audio element.
				  </audio>
			<?php endif; ?>
            <div class="small-pto-title">
              <a href="<?php echo $postCategorySlug ? route('frontend.postBySubcategory.details', [$postCategorySlug, $post->slug]) : '#'; ?>"><?php echo strlen($post->title) > $sec_title ? mb_substr($post->title,0,$sec_title,'utf-8').'...' : $post->title; ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<script>
// Lazy load images
document.querySelectorAll('.lazy').forEach(img => {
  const src = img.getAttribute('data-src');
  if(src) img.src = src;
});

const slider = document.getElementById('sliderWrapper');
const carousel = document.getElementById('myCarousel');
const slides = carousel.querySelectorAll('.slide-small-img');
const totalSlides = slides.length;

let visibleSlides = window.innerWidth <= 768 ? 2 : 4; // 2 for mobile, 4 for desktop
let currentIndex = 0;
let isDragging = false;
let startX = 0;
let currentTranslate = 0;

// Update visibleSlides on resize
window.addEventListener('resize', () => {
  visibleSlides = window.innerWidth <= 768 ? 2 : 4;
  showSlide(currentIndex); // adjust current position
});

// Drag events
slider.addEventListener('mousedown', (e) => {
  isDragging = true;
  startX = e.pageX;
  carousel.style.transition = 'none';
});

slider.addEventListener('mouseup', () => {
  isDragging = false;
  carousel.style.transition = 'transform 0.3s ease';
  currentIndex = Math.round(Math.abs(currentTranslate) / (slider.offsetWidth / visibleSlides));
  showSlide(currentIndex);
});

slider.addEventListener('mouseleave', () => {
  if (isDragging) {
    isDragging = false;
    carousel.style.transition = 'transform 0.3s ease';
    currentIndex = Math.round(Math.abs(currentTranslate) / (slider.offsetWidth / visibleSlides));
    showSlide(currentIndex);
  }
});

slider.addEventListener('mousemove', (e) => {
  if(!isDragging) return;
  const diff = e.pageX - startX;
  carousel.style.transform = `translateX(${currentTranslate + diff}px)`;
});

// Function to move to specific slide
function showSlide(index) {
  if(index < 0) index = 0;
  if(index > totalSlides - visibleSlides) index = totalSlides - visibleSlides;
  currentIndex = index;
  currentTranslate = -currentIndex * (slider.offsetWidth / visibleSlides);
  carousel.style.transform = `translateX(${currentTranslate}px)`;
}

// Auto-play
setInterval(() => {
  if(currentIndex >= totalSlides - visibleSlides) currentIndex = -1;
  showSlide(currentIndex + 1);
}, 3000);

</script>




							

		                </div>
	<?php }?>	
						
	<?php }else if($key==2){ 

if($default_language->id==1){
	$title_size=65;
	$description_size=200;
	$Read_more_news="আরো সংবাদ...";

}else{
	$title_size=170;
	$description_size=200;
	$Read_more_news="Read More News...";
	
}



	?>
						
						
		                <div class="col-md-4 col-sm-5">
		                    <div class="video-bg-n">
		                        
															
							<div class="gallery-title">
								 <a href="<?php echo  route('frontend.category', $title);?>" style="text-transform: capitalize;">
									<?php echo $title;?>
								</a>
							</div>

		                        <div class="normal-img">
									 <?php 
										$firstPost = $newsItems[0] ?? null;
										if($firstPost && is_object($firstPost)):
											$categorySlug = $firstPost->category->slug ?? '';
									?>
																												
		                        
									
									 <a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>">
										<?php if(!empty($firstPost->image_big)): ?>
											<img data-src="<?php echo asset('assets/images/post/'.$firstPost->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
										<?php elseif(!empty($firstPost->rss_image)): ?>
											  <img data-src="<?php echo asset('assets/images/post/'.$firstPost->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
										<?php endif; ?>

										<?php if(!empty($firstPost->post_type) && $firstPost->post_type == 'audio'): ?>
											 <audio controls>
												<source src="{{asset('assets/audios/'.$firstPost->audio)}}" type="audio/mpeg">
												Your browser does not support the audio element.
											  </audio>
										<?php endif; ?>
									</a>
									
		                            <h4 class="defalt_hadding"><a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>"><?php echo strlen($firstPost->title) > $title_size ? mb_substr($firstPost->title,0,$title_size,'utf-8').'...' : $firstPost->title; ?></a></h4>
									
				
									
									 <?php endif; ?>
									
																		
								  <?php 
									$postCategorySlug="";
									
									foreach($newsItems as $index => $post): ?>
										<?php 
											if($index == 0) continue; 
											if(!$post || !is_object($post)) continue; // skip null or bool
											$postCategorySlug = $post->category->slug ?? '';
										?>
		                            <div class="little-img border">
		                          
										
										
										<?php if(!empty($post->image_big)): ?>
											<img  data-src="<?php echo asset('assets/images/post/'.$post->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
										<?php elseif(!empty($post->rss_image)): ?>
											<img data-src="<?php echo asset('assets/images/post/'.$post->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
										<?php endif; ?>

										<?php if(!empty($post->post_type) && $post->post_type == 'audio'): ?>
											 <audio controls>
												<source src="{{asset('assets/audios/'.$post->audio)}}" type="audio/mpeg">
												Your browser does not support the audio element.
											  </audio>
										<?php endif; ?>

									<div class="ftx">
											<a href="<?php echo $postCategorySlug ? route('frontend.postBySubcategory.details', [$postCategorySlug, $post->slug]) : '#'; ?>">
												<?php echo strlen(strip_tags($post->description)) > $description_size 
													? convertUtf8(substr(strip_tags($post->description),0,$description_size)).'...' 
													: convertUtf8(strip_tags($post->description)); ?>
											</a>
										</div>
		                            </div>
		                            
								  <?php endforeach; ?>
									
		                            
								<?php if(!empty($postCategorySlug)){ ?>					
		                        <div class="video-more-news">
									<a href="<?php echo  route('frontend.category', $title);?>"> <?php echo $Read_more_news;?> </a>
		                        </div>
								<?php } ?>
								
		                    </div>

		                </div>
		            </div>
				<?php }?>
					
			<?php }?>
<?php }else if(!empty($newsItemsL) && $section==4){

foreach ($cat_section_list as $title => $cat){
	$newsItems=isset($newsItemsL[$cat])?$newsItemsL[$cat]:'';
	
	//print_r($newsItems);
	
if($default_language->id==1){
	$title_size=65;
	$description_size=400;
	$description_sec_size=90;
	$Read_More="আরো পড়ুন...";
	$Read_more_news="আরো সংবাদ...";

}else{
	$title_size=170;
	$description_size=400;
	$description_sec_size=90;
	$Read_More="Read More...";
	$Read_more_news="Read More News...";
	
}

 
	?>

				<div class="col-md-6 col-sm-6">
	                        <div class="section_six_bg">
							
															
	                            <div class="fixed_cat_title">
	                                <span><a href="<?php echo  route('frontend.category', $title);?>" style="text-transform: capitalize;">
									<?php echo $title;?>
								</a></span>
	                            
									
									
								
	                            </div>

	                            <div class="row">
	                                <div class="col-md-6">
									  <?php 
									$firstPost = $newsItems[0] ?? null;
									if($firstPost && is_object($firstPost)):
										$categorySlug = $firstPost->category->slug ?? '';
								?>								
	                                    <div class="leadnews">
	                                    	<div class="leadnews_image">
	                                        	
												<?php if(!empty($firstPost->image_big)): ?>
													<img data-src="<?php echo asset('assets/images/post/'.$firstPost->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
												<?php elseif(!empty($firstPost->rss_image)): ?>
													  <img data-src="<?php echo asset('assets/images/post/'.$firstPost->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
												<?php endif; ?>

												<?php if(!empty($firstPost->post_type) && $firstPost->post_type == 'audio'): ?>
													 <audio controls>
													<source src="{{asset('assets/audios/'.$firstPost->audio)}}" type="audio/mpeg">
													Your browser does not support the audio element.
												  </audio>
												<?php endif; ?>

												</div>
	                                        <div class="content-padding">
	                                            <h4 class="hadding_02"><a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>"><?php echo strlen($firstPost->title) > $title_size ? mb_substr($firstPost->title,0,$title_size,'utf-8').'...' : $firstPost->title; ?></a></h4> 
												
	                                            <div class="content-dtls">
												 <?php echo strlen(strip_tags($firstPost->description)) > $description_size 
													? convertUtf8(substr(strip_tags($firstPost->description),0,$description_size)).'...' 
													: convertUtf8(strip_tags($firstPost->description)); ?>
													  <?php if($categorySlug): ?>
													  
													  <span style="text-align:right"><a href="<?php echo $categorySlug ? route('frontend.postBySubcategory.details', [$categorySlug, $firstPost->slug]) : '#'; ?>"><?php echo $Read_More;?></a></span>
													  	<?php endif; ?>
												
													
	                                            </div>
	                                        </div>
	                                    </div>
										<?php endif; ?>
										
																				
	                                </div>
	                                <div class="col-md-6">
									
																			
	                                  
	                                     <?php 
										$postCategorySlug="";
										
										foreach($newsItems as $index => $post): ?>
											<?php 
												if($index == 0) continue; 
												if(!$post || !is_object($post)) continue; // skip null or bool
												$postCategorySlug = $post->category->slug ?? '';
											?>
																			
	                                    <div class="small-img tab-border">
	                                           <?php if(!empty($post->image_big)): ?>
												<img  data-src="<?php echo asset('assets/images/post/'.$post->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
											<?php elseif(!empty($post->rss_image)): ?>
												<img data-src="<?php echo asset('assets/images/post/'.$post->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
											<?php endif; ?>

											<?php if(!empty($post->post_type) && $post->post_type == 'audio'): ?>
												 <audio controls>
												<source src="{{asset('assets/audios/'.$post->audio)}}" type="audio/mpeg">
												Your browser does not support the audio element.
											  </audio>
											<?php endif; ?>

											<h4 class="hadding_03"> <a href="<?php echo $postCategorySlug ? route('frontend.postBySubcategory.details', [$postCategorySlug, $post->slug]) : '#'; ?>">
												<?php echo strlen(strip_tags($post->description)) > $description_sec_size 
													? convertUtf8(substr(strip_tags($post->description),0,$description_sec_size)).'...' 
													: convertUtf8(strip_tags($post->description)); ?>
											</a></h4>
										 </div>
	                                   
	                                      <?php endforeach; ?>
									
	                                    <?php if(!empty($postCategorySlug)){ ?>
										 <div class="row">
											<div class="col-sm-12 col-md-12">
												<h4 class="more_news"><a href="<?php echo  route('frontend.category', $title);?>"> <?php echo $Read_more_news;?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>  </a></h4>
											</div>
										</div>
										<?php } ?>

	                                </div>
	                            </div>
								
								
	                        </div>
	                    </div>

	                
		<?php }?>
<?php }else{?>

<?php }?>
<script>
(function($){
  $.fn.fastLazyLoad = function(userOptions){
    const defaults = {
      root: null,
      rootMargin: '100px 0px', // preload distance
      threshold: 0.01,
      dataSrc: 'data-src',
      dataSrcset: 'data-srcset',
      dataBg: 'data-bg',
      placeholderClass: 'll-placeholder',
      loadedClass: 'll-loaded',
      fadeDuration: 300,
      isBackground: false,     // if true, treat element as background image
      observe: true,           // allow IntersectionObserver
      onLoad: null,            // function(element) when loaded
      onError: null            // function(element, error)
    };
    const opts = $.extend({}, defaults, userOptions || {});
    const $elements = this;

    // Helper: load an element (img or background)
    function loadElement(el) {
      const $el = $(el);
      if (opts.isBackground || $el.hasClass('lazy-bg')) {
        const bg = $el.attr(opts.dataBg) || $el.data('bg');
        if (!bg) return markDone($el);
        const img = new Image();
        img.onload = function(){
          $el.css('background-image','url("'+bg+'")');
          markDone($el);
        };
        img.onerror = function(e){
          markDone($el);
          if (typeof opts.onError === 'function') opts.onError($el, e);
        };
        img.src = bg;
      } else {
        // img element
        const src = $el.attr(opts.dataSrc) || $el.data('src');
        const srcset = $el.attr(opts.dataSrcset) || $el.data('srcset');
        if (!src && !srcset) return markDone($el);

        // Try using loading="lazy" attribute for modern browsers (good hint)
        if ($el.is('img')) $el.attr('loading','lazy');

        if (srcset) $el.attr('srcset', srcset);
        if (src) $el.attr('src', src);

        // handle load and error
        $el.off('.fastLazyLoad');
        $el.on('load.fastLazyLoad', function(){
          markDone($el);
        }).on('error.fastLazyLoad', function(e){
          markDone($el);
          if (typeof opts.onError === 'function') opts.onError($el, e);
        });
        // in case the image is cached and 'load' won't fire
        if ($el[0].complete && ($el.attr('src') || $el.attr('srcset'))) {
          // small timeout to ensure browser sets naturalWidth
          setTimeout(()=>{ markDone($el); if (typeof opts.onLoad==='function') opts.onLoad($el); }, 0);
        }
      }
    }

    function markDone($el) {
      $el.addClass(opts.loadedClass);
      $el.removeClass(opts.placeholderClass);
      $el.css({
        'transition': `opacity ${opts.fadeDuration}ms ease`,
        'opacity': 1
      });
      if (typeof opts.onLoad === 'function') opts.onLoad($el);
    }

    // Prepare elements: add placeholder styling
    $elements.each(function(){
      const $el = $(this);
      $el.addClass(opts.placeholderClass).css({ 'opacity': 0 });
      if (opts.isBackground || $el.hasClass('lazy-bg')) {
        // nothing to add for now
      } else if ($el.is('img')) {
        // optional tiny transparent placeholder to reserve layout
        if (!$el.attr('src') && !$el.attr('srcset')) {
          $el.attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
        }
      }
    });

    // Use IntersectionObserver when available and allowed
    if ('IntersectionObserver' in window && opts.observe) {
      const io = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting || entry.intersectionRatio > 0) {
            const el = entry.target;
            observer.unobserve(el);
            loadElement(el);
          }
        });
      }, {
        root: opts.root,
        rootMargin: opts.rootMargin,
        threshold: opts.threshold
      });

      $elements.each(function(){
        io.observe(this);
      });

    } else {
      // Fallback: very light scroll handler with debounce
      const throttle = (fn, wait) => {
        let last = 0;
        return function(...args){
          const now = Date.now();
          if (now - last >= wait) {
            last = now;
            fn.apply(this, args);
          }
        };
      };

      function inViewport(el) {
        const rect = el.getBoundingClientRect();
        const winH = window.innerHeight || document.documentElement.clientHeight;
        const winW = window.innerWidth || document.documentElement.clientWidth;
        const margin = 200; // should match rootMargin roughly
        return rect.bottom >= -margin && rect.top <= winH + margin && rect.right >= 0 && rect.left <= winW;
      }

      const pending = new Set($elements.toArray());
      const check = throttle(function(){
        pending.forEach(el => {
          if (inViewport(el)) {
            pending.delete(el);
            loadElement(el);
          }
        });
        if (pending.size === 0) {
          window.removeEventListener('scroll', check);
          window.removeEventListener('resize', check);
        }
      }, 120);

      // initial check and attach events
      check();
      window.addEventListener('scroll', check, { passive: true });
      window.addEventListener('resize', check);
    }

    // allow chaining
    return this;
  };
  
  
  $('.lazy').fastLazyLoad({ rootMargin: '200px 0px', threshold: 0.01 });
  $('.lazy-bg').fastLazyLoad({ isBackground: true });
})(jQuery);
</script>
