<?php 
foreach($newsItems as $index => $post): ?>
	<?php 
		$postCategorySlug = $post->category->slug ?? '';
		if($index%3==0){
			echo "<div style='clear:both;'></div>";
		}
		
		if($default_language->id==1){
			$title_size=65;
		}else{
			$title_size=170;

			
		}
		
	?>
	<div class="col-md-4 col-sm-4">
		<div class="exclisive_news">
			<div class="exclisive_news_image">
				<a href="<?php echo $postCategorySlug ? route('frontend.postBySubcategory.details', [$postCategorySlug, $post->slug]) : '#'; ?>">
				<?php if(!empty($post->image_big)): ?>
					<img width="600" height="337"  data-src="<?php echo asset('assets/images/post/'.$post->image_big); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
				<?php elseif(!empty($post->rss_image)): ?>
					<img data-src="<?php echo asset('assets/images/post/'.$post->rss_image); ?>" class="lazy attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="">
				<?php endif; ?>

				<?php if(!empty($post->post_type) && $post->post_type == 'audio'): ?>
					<span class="vid-aud"><i class="fa fa-volume-up"></i></span>
				<?php endif; ?>
				
				</a>
			</div>
			<div class="content-padding">
				<h4 class="hadding_02"><a href="<?php echo $postCategorySlug ? route('frontend.postBySubcategory.details', [$postCategorySlug, $post->slug]) : '#'; ?>"><?php echo strlen($post->title) > $title_size ? mb_substr($post->title,0,$title_size,'utf-8').'...' : $post->title; ?></a></h4> 
			</div>
		</div>
	</div>
<?php 


endforeach; ?>

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