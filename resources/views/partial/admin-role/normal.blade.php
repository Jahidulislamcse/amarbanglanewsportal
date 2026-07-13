@if(Auth::guard('admin')->user()->sectionCheck('menu_builder'))
<li>
    <a href="{{ route('admin.menu.builder') }}" >
        <i class="fas fa-bars"></i>{{ __('Menu Builder') }}
    </a>
</li>
@endif


@if(Auth::guard('admin')->user()->sectionCheck('pages'))   
<li>
    <a href="#page" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-window-restore"></i>{{ __('Pages') }}
    </a>
    <ul class="collapse list-unstyled" id="page" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.page.create') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('Add Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.page.index') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('Pages') }}</span></a>
        </li>
    </ul>
</li>
@endif

@if(Auth::guard('admin')->user()->sectionCheck('rashifall'))
<li>
    <a href="#rashifall" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-window-restore"></i>{{ __('Rashifall') }}
    </a>
    <ul class="collapse list-unstyled" id="rashifall" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.rashifall.create') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Add Rashifall') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.rashifall.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Rashifall') }}</span></a>
        </li>
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('categories'))   
<li>
    <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-folder-open"></i>{{ __('Categories') }}
    </a>
    <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
        <li>
            <a href="{{ route('categories.index') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('Categories') }}</span></a>
        </li>
        <li>
            <a href="{{ route('subcategories.index') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('SubCategories') }}</span></a>
        </li>
    </ul>
</li>
@endif


<li>
    <a href="{{ route('article.create') }}" >
        <i class="fa fa-file"></i>{{ __('Add Post') }}
    </a>
</li>


@if (Auth::guard('admin')->user()->sectionCheck('add_gallery'))   
<li>
    <a href="#gallery" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-image"></i>{{ __('Add Gallery') }}
    </a>
    <ul class="collapse list-unstyled" id="gallery" data-parent="#accordion">
      
		
		<li>
            <a href="{{ route('image.category.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Make Categories') }}</span></a>
        </li>
		<li>
            <a href="{{ route('image.album.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Make Album') }}</span></a>
        </li>
		 
        <li>
            <a href="{{ route('image.gallery.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Show Image Gallery') }}</span></a>
        </li>
		
        <li>
            <a href="{{ route('image.gallery.create') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Make Image Gallery') }}</span></a>
        </li>
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('posts'))   
<li>
    <a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-bars"></i>{{ __('Posts') }}
    </a>
    <ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">
        <li>
            <a href="{{ route('post.index') }}"><span>{{ __('Posts') }}</span></a>
        </li>
        <li>
            <a href="{{ route('feature.index') }}"><span>{{ __('Featured Posts') }}</span></a>
        </li>
        <li>
            <a href="{{ route('breaking.index') }}"><span>{{ __('Breaking News') }}</span></a>
        </li>
        @php
            $adminId = Auth::guard('admin')->id();
            $hasDivision = \App\Models\AdminDivision::where('admin_id', $adminId)->exists();
        @endphp
        
        @if($hasDivision)
            <li>
                <a href="{{ route('pending.index') }}"><span>{{ __('Pending Posts') }}</span></a>
            </li>
        
            <li>
                <a href="{{ route('post.approved') }}">
                    <span>{{ __('Approved Posts') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('post.rejected') }}">
                    <span>{{ __('Rejected Posts') }}</span>
                </a>
            </li>
        @endif



    </ul>
</li>
@endif

<li>
    <a href="{{ route('admin.staff.index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Reporter') }}</a>
</li> 

@if (Auth::guard('admin')->user()->sectionCheck('schedule_post'))    
<li>
    <a href="{{ route('schedule.index') }}"><span><i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Schedule Post') }}</span></a>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('drafts'))    
<li>
    <a href="{{ route('draft.index') }}"><span><i class="fab fa-firstdraft"></i>{{ __('Drafts') }}</span></a>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('rss_feeds'))   
<li>
    <a href="#rss" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-rss"></i>{{ __('Rss Feeds') }}
    </a>
    <ul class="collapse list-unstyled" id="rss" data-parent="#accordion">
        <li>
            <a href="{{ route('rss.create') }}"><span>{{ __('Import Rss Feeds') }}</span></a>
        </li>
        <li>
            <a href="{{ route('rss.index') }}"><span>{{ __('Rss Feeds') }}</span></a>
        </li>
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('polls'))    
<li>
    <a href="#poll" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-list"></i>{{ __('Polls') }}
    </a>
    <ul class="collapse list-unstyled" id="poll" data-parent="#accordion">
        <li>
            <a href="{{ route('addPolls.create') }}"><span>{{ __('Add Polls') }}</span></a>
        </li>
        <li>
            <a href="{{ route('addPolls.index') }}"><span>{{ __('Polls') }}</span></a>
        </li>
    </ul>
</li>
@endif




@if (Auth::guard('admin')->user()->sectionCheck('create_ads'))    
<li>
    <a href="#menu8" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-dollar-sign"></i>{{ __('Adverisment Spaces') }}
    </a>
    <ul class="collapse list-unstyled" id="menu8" data-parent="#accordion">
        <li>
            <a href="{{ route('ads.create') }}"><span>{{ __('Create Ads') }}</span></a>
        </li>

        <li>
            <a href="{{ route('ads.index') }}"><span>{{ __('All Ads') }}</span></a>
        </li>
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('newsLetter'))   
<li>
    <a href="#menu9" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-envelope"></i>{{ __('NewsLetter') }}
    </a>
    <ul class="collapse list-unstyled" id="menu9" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.subscriber.email') }}"><span>{{ __('Send Email') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin.subscriber.index') }}"><span>{{ __('All Subscribers') }}</span></a>
        </li>
    </ul>
</li>
@endif



@if (Auth::guard('admin')->user()->sectionCheck('general_settings'))    
<li>
    <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cogs"></i>General Settings
    </a>
    <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
        <li>
            <a href="{{route('admin.generalsettings.logo')}}"><span>Logo</span></a>
        </li>
        <li>
            <a href="{{route('admin.languagelogo.index')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Language Base Logo')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.favicon')}}"><span>Favicon</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.loader')}}"><span>loader</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.websiteContent')}}"><span>Website Contents</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.popularTags')}}"><span>Popular Tags</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.footer')}}"><span>Footer</span></a>
        </li>
        
        <li>
            <a href="{{route('admin.generalsettings.errorPage')}}"><span>Error Page</span></a>
        </li>

    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('social_settings'))    
<li>
    <a href="#socials" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-paper-plane"></i>{{ __('Social Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="socials" data-parent="#accordion">
            <li><a href="{{route('social.link.index')}}"><span>{{ __('Social Links') }}</span></a></li>
            <li><a href="{{route('social.settings.google')}}"><span>{{ __('Google Login') }}</span></a></li>
            <li><a href="{{route('social.settings.facebook')}}"><span>{{ __('Facebook Login') }}</span></a></li>
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('emails_settings'))    
<li>
    <a href="#emails" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-at"></i>Email Settings
    </a>
    <ul class="collapse list-unstyled" id="emails" data-parent="#accordion">
        <li><a href="{{route('admin.email.config')}}"><span>Email Configurations</span></a></li>    
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('seo_tools'))   
<li>
    <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-wrench"></i>SEO Tools
    </a>
    <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">
        <li>
            <a href="{{ route('seo.google.analytics') }}"><span>Google Analytics</span></a>
        </li
        >
        <li>
            <a href="{{ route('seo.meta.keywords') }}"><span>Website Meta Keywords</span></a>
        </li>
    </ul>
</li>
@endif


@if (Auth::guard('admin')->user()->sectionCheck('site_map'))  
<li>
    <a href="{{ route('admin.sitemap.all') }}" class=" wave-effect"><i class="fas fa-sitemap"></i>{{ __('Site Map') }}</a>
</li>
@endif

@if (Auth::guard('admin')->user()->sectionCheck('transaction'))  
<li>
    <a href="{{ route('transactions.index') }}" class="wave-effect">
        <i class="fa fa-database"></i>{{ __('Income & Expenses') }}
    </a>
</li> 
@endif

@if (Auth::guard('admin')->user()->sectionCheck('product_orders'))
<li>
    <a href="{{ route('admin.orders.index') }}" class="wave-effect">
        <i class="fa fa-box"></i>
        {{ __('Product Orders') }}
    </a>
</li>
@endif

@if (Auth::guard('admin')->user()->sectionCheck('role_management'))
<li>
    <a href="{{ route('admin.role.index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Role Management') }}</a>
</li>  
@endif


<!--@if (Auth::guard('admin')->user()->sectionCheck('user_management'))-->
<!--<li>-->
<!--    <a href="{{ route('admin.staff.index') }}" class=" wave-effect"><i class="fas fa-user-secret"></i>{{ __('User Management') }}</a>-->
<!--</li>   -->
<!--@endif-->


<li>
    <a href="#mySalaryMenu" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-money-bill-alt"></i>{{ __('My Salary') }}
    </a>
    <ul class="collapse list-unstyled" id="mySalaryMenu" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.my-salaries.index') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('Salary History') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.my-advance-salaries.index') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('Advance History') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.my-advance-salaries.create') }}"><i class="fas fa-angle-double-right"></i><span>{{ __('Request Advance') }}</span></a>
        </li>
    </ul>
</li>

@if (Auth::guard('admin')->user()->sectionCheck('cache_management'))
<li>
    <a href="{{ route('admin.cache.clear') }}" class=" wave-effect"><i class="fa fa-database"></i>{{ __('Clear Cache') }}</a>
</li>    
@endif