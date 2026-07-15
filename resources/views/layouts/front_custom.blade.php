<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="google-adsense-account" content="ca-pub-3911918675338770">
	
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3911918675338770"
     crossorigin="anonymous"></script>

	<link rel="icon" href="{{ asset('assets/images/'.$gs->favicon) }}" type="image/png">
	@include('includes.pwa-head')
	@if (Route::is('frontend.postBySubcategory.details'))
			<meta property="og:title" content="{{ $data->title ?? ''}}" />
			<meta property="og:description" content="{{$data->description ?? ''}}" />
			<meta property="og:image" content="{{asset('assets/images/post/'. ($data->image_big ?? ''))}}" />
			<meta name="keywords" content="{{ $seo->meta_keys }}">
			<meta name="author" content="আমার বাংলা 24">
			<title>আমার বাংলা 24 | {{$data->title ?? ''}}</title>
		
	@else 
		<meta property="og:title" content="{{$gs->title}}" />
		<meta property="og:description" content="{{ strip_tags($gs->footer) }}" />
		<meta property="og:image" content="{{asset('assets/images/logo/'.$gs->logo)}}" />
		<meta name="keywords" content="{{ $seo->meta_keys }}">
		<meta name="author" content="Kalbindu">
		<title>আমার বাংলা 24</title>
	@endif
	 <!--@if (Route::is('frontend.index'))-->
  <!--      <meta http-equiv="refresh" content="300">-->
  <!--  @endif-->

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WQBRKX73');
    </script>
    <!-- End Google Tag Manager -->


	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	
	<script type="text/javascript">
    /* <![CDATA[ */
    window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/16.0.1\/svg\/","svgExt":".svg","source":{"concatemoji":"https:\/\/kalbindu.com\/wp-includes\/js\/wp-emoji-release.min.js?ver=6.8.2"}};
    /*! This file is auto-generated */
    !function(s,n){var o,i,e;function c(e){try{var t={supportTests:e,timestamp:(new Date).valueOf()};sessionStorage.setItem(o,JSON.stringify(t))}catch(e){}}function p(e,t,n){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);var t=new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data),a=(e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(n,0,0),new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data));return t.every(function(e,t){return e===a[t]})}function u(e,t){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);for(var n=e.getImageData(16,16,1,1),a=0;a<n.data.length;a++)if(0!==n.data[a])return!1;return!0}function f(e,t,n,a){switch(t){case"flag":return n(e,"\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f","\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f")?!1:!n(e,"\ud83c\udde8\ud83c\uddf6","\ud83c\udde8\u200b\ud83c\uddf6")&&!n(e,"\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f","\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f");case"emoji":return!a(e,"\ud83e\udedf")}return!1}function g(e,t,n,a){var r="undefined"!=typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?new OffscreenCanvas(300,150):s.createElement("canvas"),o=r.getContext("2d",{willReadFrequently:!0}),i=(o.textBaseline="top",o.font="600 32px Arial",{});return e.forEach(function(e){i[e]=t(o,e,n,a)}),i}function t(e){var t=s.createElement("script");t.src=e,t.defer=!0,s.head.appendChild(t)}"undefined"!=typeof Promise&&(o="wpEmojiSettingsSupports",i=["flag","emoji"],n.supports={everything:!0,everythingExceptFlag:!0},e=new Promise(function(e){s.addEventListener("DOMContentLoaded",e,{once:!0})}),new Promise(function(t){var n=function(){try{var e=JSON.parse(sessionStorage.getItem(o));if("object"==typeof e&&"number"==typeof e.timestamp&&(new Date).valueOf()<e.timestamp+604800&&"object"==typeof e.supportTests)return e.supportTests}catch(e){}return null}();if(!n){if("undefined"!=typeof Worker&&"undefined"!=typeof OffscreenCanvas&&"undefined"!=typeof URL&&URL.createObjectURL&&"undefined"!=typeof Blob)try{var e="postMessage("+g.toString()+"("+[JSON.stringify(i),f.toString(),p.toString(),u.toString()].join(",")+"));",a=new Blob([e],{type:"text/javascript"}),r=new Worker(URL.createObjectURL(a),{name:"wpTestEmojiSupports"});return void(r.onmessage=function(e){c(n=e.data),r.terminate(),t(n)})}catch(e){}c(n=g(i,f,p,u))}t(n)}).then(function(e){for(var t in e)n.supports[t]=e[t],n.supports.everything=n.supports.everything&&n.supports[t],"flag"!==t&&(n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&n.supports[t]);n.supports.everythingExceptFlag=n.supports.everythingExceptFlag&&!n.supports.flag,n.DOMReady=!1,n.readyCallback=function(){n.DOMReady=!0}}).then(function(){return e}).then(function(){var e;n.supports.everything||(n.readyCallback(),(e=n.source||{}).concatemoji?t(e.concatemoji):e.wpemoji&&e.twemoji&&(t(e.twemoji),t(e.wpemoji)))}))}((window,document),window._wpemojiSettings);
    /* ]]> */
    </script>
    <style id="wp-emoji-styles-inline-css" type="text/css">
    
    	img.wp-smiley, img.emoji {
    		display: inline !important;
    		border: none !important;
    		box-shadow: none !important;
    		height: 1em !important;
    		width: 1em !important;
    		margin: 0 0.07em !important;
    		vertical-align: -0.1em !important;
    		background: none !important;
    		padding: 0 !important;
    	}
    </style>
    <link rel="stylesheet" id="wp-block-library-css" href="{{asset('assets/front2/css/style.min.css')}}" type="text/css" media="all">
    
    <style id="classic-theme-styles-inline-css" type="text/css">
    /*! This file is auto-generated */
    .wp-block-button__link{color:#fff;background-color:#32373c;border-radius:9999px;box-shadow:none;text-decoration:none;padding:calc(.667em + 2px) calc(1.333em + 2px);font-size:1.125em}.wp-block-file__button{background:#32373c;color:#fff;text-decoration:none}
    </style>
    <style id="global-styles-inline-css" type="text/css">
    :root{--wp--preset--aspect-ratio--square: 1;--wp--preset--aspect-ratio--4-3: 4/3;--wp--preset--aspect-ratio--3-4: 3/4;--wp--preset--aspect-ratio--3-2: 3/2;--wp--preset--aspect-ratio--2-3: 2/3;--wp--preset--aspect-ratio--16-9: 16/9;--wp--preset--aspect-ratio--9-16: 9/16;--wp--preset--color--black: #000000;--wp--preset--color--cyan-bluish-gray: #abb8c3;--wp--preset--color--white: #ffffff;--wp--preset--color--pale-pink: #f78da7;--wp--preset--color--vivid-red: #cf2e2e;--wp--preset--color--luminous-vivid-orange: #ff6900;--wp--preset--color--luminous-vivid-amber: #fcb900;--wp--preset--color--light-green-cyan: #7bdcb5;--wp--preset--color--vivid-green-cyan: #00d084;--wp--preset--color--pale-cyan-blue: #8ed1fc;--wp--preset--color--vivid-cyan-blue: #0693e3;--wp--preset--color--vivid-purple: #9b51e0;--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%);--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%);--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%);--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%);--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%);--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%);--wp--preset--gradient--blush-light-purple: linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%);--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%);--wp--preset--gradient--luminous-dusk: linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%);--wp--preset--gradient--pale-ocean: linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%);--wp--preset--gradient--electric-grass: linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%);--wp--preset--gradient--midnight: linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%);--wp--preset--font-size--small: 13px;--wp--preset--font-size--medium: 20px;--wp--preset--font-size--large: 36px;--wp--preset--font-size--x-large: 42px;--wp--preset--spacing--20: 0.44rem;--wp--preset--spacing--30: 0.67rem;--wp--preset--spacing--40: 1rem;--wp--preset--spacing--50: 1.5rem;--wp--preset--spacing--60: 2.25rem;--wp--preset--spacing--70: 3.38rem;--wp--preset--spacing--80: 5.06rem;--wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);--wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);--wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);--wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);--wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1);}:where(.is-layout-flex){gap: 0.5em;}:where(.is-layout-grid){gap: 0.5em;}body .is-layout-flex{display: flex;}.is-layout-flex{flex-wrap: wrap;align-items: center;}.is-layout-flex > :is(*, div){margin: 0;}body .is-layout-grid{display: grid;}.is-layout-grid > :is(*, div){margin: 0;}:where(.wp-block-columns.is-layout-flex){gap: 2em;}:where(.wp-block-columns.is-layout-grid){gap: 2em;}:where(.wp-block-post-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-post-template.is-layout-grid){gap: 1.25em;}.has-black-color{color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-color{color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-color{color: var(--wp--preset--color--white) !important;}.has-pale-pink-color{color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-color{color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-color{color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-color{color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-color{color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-color{color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-color{color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-color{color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-color{color: var(--wp--preset--color--vivid-purple) !important;}.has-black-background-color{background-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-background-color{background-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-background-color{background-color: var(--wp--preset--color--white) !important;}.has-pale-pink-background-color{background-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-background-color{background-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-background-color{background-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-background-color{background-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-background-color{background-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-background-color{background-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-background-color{background-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-background-color{background-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-background-color{background-color: var(--wp--preset--color--vivid-purple) !important;}.has-black-border-color{border-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-border-color{border-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-border-color{border-color: var(--wp--preset--color--white) !important;}.has-pale-pink-border-color{border-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-border-color{border-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-border-color{border-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-border-color{border-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-border-color{border-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-border-color{border-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-border-color{border-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-border-color{border-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-border-color{border-color: var(--wp--preset--color--vivid-purple) !important;}.has-vivid-cyan-blue-to-vivid-purple-gradient-background{background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;}.has-light-green-cyan-to-vivid-green-cyan-gradient-background{background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;}.has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;}.has-luminous-vivid-orange-to-vivid-red-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;}.has-very-light-gray-to-cyan-bluish-gray-gradient-background{background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;}.has-cool-to-warm-spectrum-gradient-background{background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;}.has-blush-light-purple-gradient-background{background: var(--wp--preset--gradient--blush-light-purple) !important;}.has-blush-bordeaux-gradient-background{background: var(--wp--preset--gradient--blush-bordeaux) !important;}.has-luminous-dusk-gradient-background{background: var(--wp--preset--gradient--luminous-dusk) !important;}.has-pale-ocean-gradient-background{background: var(--wp--preset--gradient--pale-ocean) !important;}.has-electric-grass-gradient-background{background: var(--wp--preset--gradient--electric-grass) !important;}.has-midnight-gradient-background{background: var(--wp--preset--gradient--midnight) !important;}.has-small-font-size{font-size: var(--wp--preset--font-size--small) !important;}.has-medium-font-size{font-size: var(--wp--preset--font-size--medium) !important;}.has-large-font-size{font-size: var(--wp--preset--font-size--large) !important;}.has-x-large-font-size{font-size: var(--wp--preset--font-size--x-large) !important;}
    :where(.wp-block-post-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-post-template.is-layout-grid){gap: 1.25em;}
    :where(.wp-block-columns.is-layout-flex){gap: 2em;}:where(.wp-block-columns.is-layout-grid){gap: 2em;}
    :root :where(.wp-block-pullquote){font-size: 1.5em;line-height: 1.6;}
    </style>
    
    
    <link rel="stylesheet" id="print-css-css" href="{{asset('assets/front2/css/print.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="woocommerce-layout-css" href="{{asset('assets/front2/css/woocommerce-layout.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="woocommerce-smallscreen-css" href="{{asset('assets/front2/css/woocommerce-smallscreen.css')}}" type="text/css" media="only screen and (max-width: 768px)">
    
    
    <link rel="stylesheet" id="woocommerce-general-css" href="{{asset('assets/front2/css/woocommerce.css')}}" type="text/css" media="all">
    
    <style id="woocommerce-inline-inline-css" type="text/css">
    .woocommerce form .form-row .required { visibility: visible; }
    </style>
    
    <link rel="stylesheet" id="brands-styles-css" href="{{asset('assets/front2/css/brands.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="bootstrap-css" href="{{asset('assets/front2/css/bootstrap.min.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="font-awesome-css" href="{{asset('assets/front2/css/font-awesome.min.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="responsive-css" href="{{asset('assets/front2/css/responsive.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="menu-css" href="{{asset('assets/front2/css/menu.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="owl_carousel_min-css" href="{{asset('assets/front2/css/owl.carousel.min.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="archive-style-css" href="{{asset('assets/front2/css/archive-style.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="style-css" href="{{asset('assets/front2/css/style.css')}}" type="text/css" media="all">
    
    <link rel="stylesheet" id="mo_customer_validation_form_main_css-css" href="{{asset('assets/front2/css/mo_forms_css.min.css')}}" type="text/css" media="all">
    
    
    <script type="text/javascript" src="{{asset('assets/front2/js/jquery.min.js')}}" id="jquery-core-js"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/jquery-migrate.min.js')}}" id="jquery-migrate-js"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/jquery.blockUI.min.js')}}" id="jquery-blockui-js" defer="defer" data-wp-strategy="defer"></script>
    <script type="text/javascript" id="wc-add-to-cart-js-extra">
    /* <![CDATA[ */
    var wc_add_to_cart_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_view_cart":"View cart","cart_url":"https:\/\/kalbindu.com\/cart\/","is_cart":"","cart_redirect_after_add":"no"};
    /* ]]> */
    </script>
    <script type="text/javascript" src="{{asset('assets/front2/js/add-to-cart.min.js')}}" id="wc-add-to-cart-js" defer="defer" data-wp-strategy="defer"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/js.cookie.min.js')}}" id="js-cookie-js" defer="defer" data-wp-strategy="defer"></script>
    <script type="text/javascript" id="woocommerce-js-extra">
    /* <![CDATA[ */
    var woocommerce_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_password_show":"Show password","i18n_password_hide":"Hide password"};
    /* ]]> */
    </script>
    <script type="text/javascript" src="{{asset('assets/front2/js/woocommerce.min.js')}}" id="woocommerce-js" defer="defer" data-wp-strategy="defer"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/bootstrap.min.js')}}" id="js_min-js"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/jquery.min_1.js')}}" id="jquery-min-js"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/owl.carousel.min.js')}}" id="owl_carousel_min-js"></script>
    <script type="text/javascript" src="{{asset('assets/front2/js/main.js')}}" id="main-js"></script>
    <link rel="https://api.w.org/" href="https://kalbindu.com/wp-json/"><link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://kalbindu.com/xmlrpc.php?rsd">
    <meta name="generator" content="WordPress 6.8.2">
    <meta name="generator" content="WooCommerce 10.1.2">
	<noscript><style>.woocommerce-product-gallery{ opacity: 1 !important; }</style></noscript>
	<meta name="generator" content="Elementor 3.31.3; features: e_font_icon_svg, additional_custom_breakpoints, e_element_cache; settings: css_print_method-external, google_font-enabled, font_display-swap">
			<style>
				.e-con.e-parent:nth-of-type(n+4):not(.e-lazyloaded):not(.e-no-lazyload),
				.e-con.e-parent:nth-of-type(n+4):not(.e-lazyloaded):not(.e-no-lazyload) * {
					background-image: none !important;
				}
				@media screen and (max-height: 1024px) {
					.e-con.e-parent:nth-of-type(n+3):not(.e-lazyloaded):not(.e-no-lazyload),
					.e-con.e-parent:nth-of-type(n+3):not(.e-lazyloaded):not(.e-no-lazyload) * {
						background-image: none !important;
					}
				}
				@media screen and (max-height: 640px) {
					.e-con.e-parent:nth-of-type(n+2):not(.e-lazyloaded):not(.e-no-lazyload),
					.e-con.e-parent:nth-of-type(n+2):not(.e-lazyloaded):not(.e-no-lazyload) * {
						background-image: none !important;
					}
				}
			</style>
			<link rel="icon" href="images/Kalbindu-Round-icon-150x150.png" sizes="32x32">
			<link rel="icon" href="images/Kalbindu-Round-icon.png" sizes="192x192">
			<link rel="apple-touch-icon" href="images/Kalbindu-Round-icon.png">
			<meta name="msapplication-TileImage" content="{{asset('assets/img/Kalbindu-Round-icon.png')}}">
                                <style type="text/css" id="wp-custom-css">
						.tab-header .nav-tabs > li.active > a, .tab-header .nav-tabs > li.active > a:focus, .tab-header .nav-tabs > li.active > a:hover {
				border: none;
				border-radius: 0;
				background: #000000ff;
				color: #fff;
				border-top: 3px solid #7719AA;
				font-size: 17px;
			}
					</style>
					
				
			<style>

			body {
				font-size: 16px;
				width:100%;
				font-family: SolaimanLipiNormal;
			}
			
			.slider-padding {
				padding: 15px 15px 15px 0px !important;
			}
			.date {
				text-align: center;
				padding: 20px 0px;
				font-size: 18px;
				font-weight: normal;
				color:#000;
			}
			.scrool_1{
				padding:5px;
				font-size:17px;
				background: #CD1D23;
				color:#fff;
				text-align:left;
				font-weight: 400;
			}
			.footer-scrool {
				position: fixed;
				background: aliceblue;
				color:#fff;
				z-index: 99;
				overflow: hidden;
				bottom: 0;
				left: 0;
				right: 0;
			}
			.footer-scrool-1 {
				float: left;
				width: 18%;
				background: #CD1D23;
				padding: 6px;
				font-size: 18px;
			}
			.cat_title{
				display: block;
				margin-bottom:7px;
				background-color: #b0d2f4;
			} 
			.cat_title a{ 
				color:#fff;
				font-weight:400;
				font-size: 16px;
				text-decoration: none; 
				position:relative;
				display: inline-block; 
				margin: 0px 0 0 0 !important;
				background: #000000;
				padding:6px 10px;
			}
			.cat_title a:after{
				left: 100%;
				height: 0;
				width: 7px;
				position: absolute;
				top: 0;
				content: "";
				pointer-events: none;
				margin-left: 0;
				margin-top: 0;
				border-bottom: 35px solid #000000;
				border-right: 28px solid transparent;
			}
			.cat_title p{ 
				color:#fff;
				font-weight:400;
				font-size: 16px;
				text-decoration: none; 
				position:relative;
				display: inline-block; 
				margin: 0px 0 0 0 !important;
				background: #000000;
				padding:6px 10px;
			}
			.cat_title p:after{
				left: 100%;
				height: 0;
				width: 7px;
				position: absolute;
				top: 0;
				content: "";
				pointer-events: none;
				margin-left: 0;
				margin-top: 0;
				border-bottom: 35px solid #000000;
				border-right: 28px solid transparent;
			}

			.cat_title_two{
				background:#F0F0F0;
			}
			#pointer a{
				color:#fff;
				font-weight:;
				font-size: 18px;
				text-decoration: none;
			}

			 #pointer {
				width: 180px;
				height: 40px;
				position: relative;
				background: #000000;
				padding-top:8px;
				padding-left:10px;
				margin:0;
				color:#fff;
				font-weight:;
				font-size: 18px;
				margin-bottom:5px;
				margin-top:10px;
			  }
			 #pointer:after {     
				content: "";
				position: absolute;
				left: 0;
				bottom: 0;
				width: 0;
				height: 0 white;
			  }
			#pointer:before {
				content: "";
				position: absolute;
				right: -20px;
				bottom: 0;
				width: 0;
				height: 0;
				border-left: 20px solid #000000;
				border-top: 20px solid transparent;
				border-bottom: 20px solid transparent;
			  }
			.cat_title_three{
				color:#fff;
				font-weight:400;
				font-size: 17px;
				background:#515151;
				border-left:4px solid#B30F0F;
				padding:5px;
				margin-bottom:7px;
			}
			.cat_title_three a{
				color:#fff;
				font-weight:400;
				font-size: 17px;
			}
			.cat_title_four{
				background-color:  #006699;
				padding: 7px;
				border-radius: 5px 5px 0px 0px;
				margin-top: 7px;
				margin-bottom: 5px;
				color:#fff;
				font-weight:400;
				font-size: 20px;
			}
			.cat_title_four span a{
				color:#fff;
				font-weight:400;
				font-size: 20px;
				text-decoration: none;
				background: transparent;
				border-radius: 0px 50px 0px 0px;
				padding: 7px 20px 7px 10px;
			}
			.cat_title_four span {
				color:#fff;
				font-weight:400;
				font-size: 20px;
				text-decoration: none;
				background: transparent;
				border-radius: 0px 50px 0px 0px;
				padding: 7px 20px 7px 10px;
			}
			.fixed_cat_title{
				padding:7px;
				border-bottom:1px solid #CD1D23;
				margin-bottom: 7px;
			}
			.fixed_cat_title span{
				background: #CD1D23;
				padding:8px 20px;
				margin-left:-7px;
				font-size:18px;
			}
			.fixed_cat_title span2{
				padding:8px 8px 0 0;
				margin-right:-10px;
				margin-top: -7px;
				float:right;
				font-size:18px;
			}
			.fixed_cat_title a{
				color:#fff;
			}
			.fixed_cat_title span2 a{ 
				color:#489DDE; 
				padding-left: 20px;
				border-left: 3px solid#FE0101;  
			}



			.overly_hadding_1 {
			  position: absolute; 
			  bottom: 0; 
			  background: rgb(0, 0, 0);
			  background: rgba(0, 0, 0, 0.2); /* Black see-through */
			  color: #f1f1f1; 
			  width: 100%;
			  transition: .5s ease;
			  opacity:0;
			  padding:10px;
			  margin:0;
			}
			.overly_hadding_1 a {
				text-decoration:none;
				font-size:22px;
				line-height:autopx;
				font-weight:400;
				color:#fff;
			}
			.overly_hadding_1 a:hover {
				color: #F9FF06
			}

			.Name .overly_hadding_1 {
			  opacity: 1;
			}

			.overly_hadding_2 {
			  position: absolute; 
			  bottom: 0; 
			  background: rgb(0, 0, 0);
			  background: rgba(0, 0, 0, 0.2); /* Black see-through */
			  color: #f1f1f1; 
			  width: 100%;
			  transition: .5s ease;
			  opacity:0;
			  padding: 10px;
			  margin:0;
			}
			.overly_hadding_2 a {
				text-decoration:none;
				font-size:18px;
				line-height:autopx;
				font-weight:400;
				color:#fff;
			}
			.overly_hadding_2 a:hover {
				color: #F9FF06
			}

			.Name .overly_hadding_2 {
			  opacity: 1;
			}
			.hadding_01{  
				padding: 3px 0px 5px 5px;
				margin: 0;
			}
			.hadding_01 a{
				font-size:21px;
				line-height:autopx;
				font-weight:400;
				color:#000;
				text-decoration:none;
			}
			.hadding_01 a:hover{
				color: #000000ff;
			}
			.hadding_02{  
				padding-top:3px;
				padding-bottom:5px;
				margin: 0;
			}
			.hadding_02 a{
				font-size:19px;
				line-height:autopx;
				font-weight:400;
				color:#000;
				text-decoration:none;
			}
			.hadding_02 a:hover{
				color: #020257;
			}

			.hadding_03{  
				padding-right: 3px;
				padding-left: 6px;
				padding-bottom:4px;
				margin: 0;
			}
			.hadding_03 a{
				font-size:16px;
				line-height:autopx;
				font-weight:400;
				color:#000;
				text-decoration:none;
			}
			.hadding_03 a:hover{
				color: #000000ff;
			}

			.more_news {
				float:right;
				margin-bottom: 3px;
			}
			.more_news a{
				display: inline-block;
				font-size:15px;
				font-weight:;
				color: #CD1D23;
				padding: 6px 20px;
				border-radius: 50px;
				transition: .3s;
				margin-top: 2px;
				text-decoration: none;
			  }
			.more_news a:hover{
				color: #049D0F;
				transition: .7s;
			  }

			.facebook_title{
				font-size:17px;
				font-weight:;
				color:#fff;
				background:#000000;
				border-left:4px solid#B30F0F;
				padding:5px;
				margin-bottom:7px;
			}
			.archive_calender_sec {
				margin: 8px 0px;
				overflow: hidden;
			}
			.archive_title{
				font-size:17px;
				font-weight:;
				color:#fff;
				background:#000000;
				border-left:4px solid#B30F0F;
				padding:5px;
				margin-bottom:7px;
			}

			.widget_area h3{
				font-size:17px;
				font-weight:;
				color:#fff;
				background:#000000;
				border-left:4px solid#B30F0F;
				padding:5px;
				margin-bottom:7px;
			}

			.footer_section{
				background: #595959;
				padding: 20px 0;
			}
			.footer-menu ul li a{
				color: #ffffff;
				text-decoration: none;
			}
			.footer-border{
				border-bottom :1px solid #ffffff;
				padding-top: 10px;
				margin-bottom: 10px;
			}
			.menu-border{
				border-right: 1px solid #ffffff;
			}

			.editorial-text{
				font-size: 17px;
				color: #ffffff;
				text-align: right; 
			}
			.address-text{
				text-align: left;
				font-size: 17px;
				color: #ffffff; 
			}
			.root{
				padding: 10px 10px;
				background-color: #000000;
				margin-bottom: 35px;
			}
			.root_01{
				font-size: 16px;
				color: #ffffff;
			}
			.root_02 {
				font-size: 16px;
				color: #ffffff;
				text-align: right;
			}

			.scrollToTop{
				width:40px; 
				height:40px;
				padding:10px;  
				background: transparent;
				position:fixed;
				right:25px;
				bottom:70px;
				border-radius: 50%;
				z-index: 999;
				border: 2px solid #CD1D23;
			}
			.scrollToTop i.fa {
				font-size: 35px;
				color: #CD1D23;
				font-weight: 400;
				top: -2px;
				display: block;
				position: absolute;
				right: 7px;
			}

			.menu_section{
				position: sticky;
			    top: 0;
				background: #CD1D23;
				box-shadow: 0 0 10px #dddbdb;
				z-index: 999999;
				
			}
			
		
			.menu_bottom { 
				background: #CD1D23;
			 }
			.menu_area .menu_bottom .mainmenu a , .navbar-default .navbar-nav > li > a {
				font-size: 16px;
				color: #fff;
				text-transform: capitalize;
				padding: 12px 14px;
				border-right:1px solid#ffffff;
			}

			.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, 
			.navbar-default .navbar-nav > .active > a:focus {
				color: #fff !important;
				margin: 0px;
				background-color: #000000ff;
			}
			.search-icon i {
				color: #fff;
				font-size: 20px;
				-webkit-border-radius: 50px;
				-moz-border-radius: 50px;
				border-radius: 50px;
				padding: 11px;
				-webkit-transition: all 0.8s;
				-moz-transition: all 0.8s;
				-o-transition: all 0.8s;
				-ms-transition: all 0.8s;
				transition: all 0.8s;
				background: #ffffff;
			}
			.pager .next>a, .pager .next>span {
				float: none !important;
			}

		.menu_area .menu_bottom .mainmenu a, .navbar-default .navbar-nav > li > a {

			padding: 12px  10px 14px 10px !important;

		}
		.fnten_bn{
			font-size:17px !important;
		}
		
        @media only screen and (max-width: 767px) {
            .tab_list {
                margin-left: 1% !important;
                margin-right: 1% !important;
                padding: 8px 2px !important;
        
                overflow-x: auto;      
                white-space: nowrap;    
                display: block;         
                -webkit-overflow-scrolling: touch; 
            }
        
            .tab_list::-webkit-scrollbar {
                display: none;
            }
        }
		</style>
		
		<script src="{{asset('assets/front2/js/wp-emoji-release.min.js')}}" defer="">
		</script>


     <?php if($default_language->id==2){?>
    		<style>
    		.date {
    			font-size: 16px !important;
    		}
    		.menu_area .menu_bottom .mainmenu a, .navbar-default .navbar-nav > li > a {
    			padding: 12px 4px 12px 5px !important;
    		}
    		
    		.menu_area .menu_bottom .mainmenu a, .navbar-default .navbar-nav > li > a {
    			font-size: 15px !important;
    		}
    		.fnten_bn{
    			font-size:14px !important;
    		}
    
    		</style>
     <?php }?>
 
 </head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WQBRKX73"
        height="0" width="0" style="display:none;visibility:hidden">
    </iframe>
</noscript>

<!--<div id="installPopup">-->
<!--  <div class="popup-box">-->

<!--    <h3>📲 অ্যাপ ইনস্টল করুন</h3>-->
<!--    <p>ভালো অভিজ্ঞতার জন্য আমাদের অ্যাপ ব্যবহার করুন</p>-->

<!--    <button onclick="goInstall()" style="background:#c0001d; color:#fff; padding:10px; width:100%; border:none; border-radius:6px;">-->
<!--      চালিয়ে যান-->
<!--    </button>-->

<!--    <button onclick="markInstalled()" style="margin-top:8px; background:#1a7f37; color:#fff; padding:10px; width:100%; border:none; border-radius:6px;">-->
<!--      ইতোমধ্যে ইনস্টল করা আছে-->
<!--    </button>-->

<!--    <button onclick="closePopup()" style="margin-top:8px; background:#eee; padding:8px; width:100%; border:none; border-radius:6px;">-->
<!--      বন্ধ করুন-->
<!--    </button>-->

<!--  </div>-->
<!--</div>-->
<!-- End Google Tag Manager (noscript) -->

    <!-- Header Part-->
    @include('partial.front2.header')
    <!-- Header Part End-->

    <!--Content of each page-->
    @yield('contents')
	<!--Content of each page end-->

	<!-- Footer Area Start -->
	@include('partial.front2.footer')
	<!-- Footer Area End -->
<script src="{{asset('assets/front/js/login.js')}}"></script>

<script>

$(document).on('change','#languageChange',function(){
	var language_id = $(this).val();
	var url ="{{ url('/change/language/')}}/"+language_id;
	window.location = url;
})
$(document).ready(function () {
    function loadCategory(container) {
        if (container.data('loading') !== 0) return;
        let categoryId = container.data('category');
        let section = container.data('section');
        let title = container.data('title');
        container.data('loading', 1); 
        $.ajax({
            url: "{{ route('news.sections.fetch') }}",
            type: 'POST',
            data: {
                category_id: categoryId,
                section: section,
                title: title,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                container.find('.loading-text').remove();
               
                if (response.html && response.html.trim() !== '') {
                    container.html(response.html);
                } else {
                    container.html("<div class='loading-text'>No data found for " + title + "</div>");
                }

                container.data('loading', 2); // Mark as fully loaded
            },
            error: function () {
                container.find('.loading-text').text('Failed to load news.');
                container.data('loading', 0); // Reset to allow retry
            }
        });
    }
    let throttleTimer = null;
    $(window).on('scroll', function () {
        if (throttleTimer) return;
        throttleTimer = setTimeout(() => {
            $('.category-content').each(function () {
                let container = $(this);

                if (container.data('loading') !== 0) return; 

                let scrollTop = $(window).scrollTop();
                let windowHeight = $(window).height();
                let elementTop = container.offset().top;

                if (scrollTop + windowHeight > elementTop - 150) {
                    loadCategory(container);
                }
            });

            throttleTimer = null;
        }, 200);
    });

    // Trigger on page load in case some elements are already visible
    $(window).trigger('scroll');
    
    
	  $('#subscribeForm').on('submit', function(e) {
        e.preventDefault();
        
        var email = $('input[name="email"]').val();
        var token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route('front.subcribe') }}",
            type: "POST",
            data: { email: email, _token: token },
            success: function(response) {
                $('#responseMessage').removeClass('alert-danger').addClass('alert-success')
                    .text(response.success).show();
                $('#subscribeForm')[0].reset();
            },
            error: function(xhr) {
                var msg = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                    msg = xhr.responseJSON.errors.email[0];
                }
                $('#responseMessage').removeClass('alert-success').addClass('alert-danger')
                    .text(msg).show();
            }
        });
    });
});
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-NL46M7KDNM"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NL46M7KDNM');
</script>

@include('includes.pwa-install-prompt')
@include('includes.pwa-script')


</body>

</html>
