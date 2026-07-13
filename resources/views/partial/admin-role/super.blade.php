<li>
    <a href="{{ route('admin.menu.builder') }}" >
        <i class="fas fa-bars"></i>{{ __('Menu Builder') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.worldcup.index') }}" >
        <i class="fas fa-futbol"></i>{{ __('FIFA WorldCup 2026') }}
    </a>
</li>

<li>
    <a href="#products" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-box"></i>{{ __('E-Commerce') }}
    </a>
    <ul class="collapse list-unstyled" id="products" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.productCategories.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Product Category') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Products') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#books" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-box"></i>{{ __('E Books') }}
    </a>
    <ul class="collapse list-unstyled" id="books" data-parent="#accordion">
      
        <li>
            <a href="{{ route('admin.books.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Books') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="#postQuiz" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-question-circle"></i>{{ __('Post Quiz') }}
    </a>
    <ul class="collapse list-unstyled" id="postQuiz" data-parent="#accordion">
        <li>
            <a href="{{ route('post.quiz.index') }}">
                <span><i class="fas fa-angle-double-right"></i>{{ __('Quiz Results') }}</span>
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="#couses" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-box"></i>{{ __('Couses') }}
    </a>
    <ul class="collapse list-unstyled" id="couses" data-parent="#accordion">
      
        <li>
            <a href="{{ route('admin.courses.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Courses') }}</span></a>
        </li>
    </ul>
</li>

<!--<li>-->
<!--    <a href="#packages" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">-->
<!--        <i class="fas fa-box"></i>{{ __('Packages') }}-->
<!--    </a>-->
<!--    <ul class="collapse list-unstyled" id="packages" data-parent="#accordion">-->
      
<!--        <li>-->
<!--            <a href="{{ route('admin.upgrade.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('User Requests') }}</span></a>-->
<!--        </li>-->
<!--    </ul>-->
<!--</li>-->

<li>
    <a href="#page" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-window-restore"></i>{{ __('Pages') }}
    </a>
    <ul class="collapse list-unstyled" id="page" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.page.create') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Add Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.page.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Pages') }}</span></a>
        </li>
    </ul>
</li>


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


<li>
    <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-folder-open"></i>{{ __('Categories') }}
    </a>
    <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
        <li>
            <a href="{{ route('categories.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Categories') }}</span></a>
        </li>
        <li>
            <a href="{{ route('subcategories.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('SubCategories') }}</span></a>
        </li>
		
		 <li>
            <a href="{{ route('reportcategories.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Reporter Categories') }}</span></a>
        </li>
		
		 <li>
            <a href="{{ route('videocategories.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Video Categories') }}</span></a>
        </li>
    </ul>
</li>


<li>
    <a href="{{ route('article.create') }}" >
        <i class="fa fa-file"></i>{{ __('Add News') }}
    </a>
</li>
 
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


<li>
    <a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-bars"></i>{{ __('News') }}
    </a>
    <ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">
        <li>
            <a href="{{ route('post.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('All News') }}</span></a>
        </li>
        
        <li>
            <a href="{{ route('feature.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Featured News') }}</span></a>
        </li>
        <li>
            <a href="{{ route('breaking.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Breaking News') }}</span></a>
        </li>
        <li>
            <a href="{{ route('pending.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Pending News') }}</span></a>
        </li>
        <li>
            <a href="{{ route('post.rejected') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Rejected News') }}</span></a>
        </li>
		<li>
            <a href="{{ route('post.postledger') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('News Ledger') }}</span></a>
        </li>
		

    </ul>
</li>

<li>
    <a href="{{ route('schedule.index') }}"><span><i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Schedule News') }}</span></a>
</li>



<li>
    <a href="{{ route('draft.index') }}"><span><i class="fab fa-firstdraft"></i>{{ __('Drafts') }}</span></a>
</li>


<li>
    <a href="#rss" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-rss"></i>{{ __('Rss Feeds') }}
    </a>
    <ul class="collapse list-unstyled" id="rss" data-parent="#accordion">
        <li>
            <a href="{{ route('rss.create') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Import Rss Feeds') }}</span></a>
        </li>
        <li>
            <a href="{{ route('rss.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Rss Feeds') }}</span></a>
        </li>
    </ul>
</li>

  
<li>
    <a href="#poll" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-list"></i>{{ __('Polls') }}
    </a>
    <ul class="collapse list-unstyled" id="poll" data-parent="#accordion">
        <li>
            <a href="{{ route('addPolls.create') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Add Polls') }}</span></a>
        </li>
        <li>
            <a href="{{ route('addPolls.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Polls') }}</span></a>
        </li>
    </ul>
</li>




 
<li>
    <a href="#menu8" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-dollar-sign"></i>{{ __('Adverisment Spaces') }}
    </a>
    <ul class="collapse list-unstyled" id="menu8" data-parent="#accordion">
        <li>
            <a href="{{ route('ads.create') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Create Ads') }}</span></a>
        </li>

        <li>
            <a href="{{ route('ads.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('All Ads') }}</span></a>
        </li>
    </ul>
</li>

 
<li>
    <a href="#menu9" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-envelope"></i>{{ __('NewsLetter') }}
    </a>
    <ul class="collapse list-unstyled" id="menu9" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.subscriber.email') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Send Email') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin.subscriber.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('All Subscribers') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('transactions.index') }}" class="wave-effect">
        <i class="fa fa-database"></i>{{ __('Income & Expenses') }}
    </a>
</li> 
  
<li>
    <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cogs"></i>{{__('General Settings')}}
    </a>
    <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
        <li>
            <a href="{{route('admin.generalsettings.fees')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Fees')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.logo')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Logo')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.languagelogo.index')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Language Base Logo')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.favicon')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Favicon')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.loader')}}"><span><i class="fas fa-angle-double-right"></i>{{__('loader')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.websiteContent')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Website Contents')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.popularTags')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Popular Tags')}}</span></a>
        </li>
        <li>
            <a href="{{route('admin.generalsettings.footer')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Footer')}}</span></a>
        </li>
        
        <li>
            <a href="{{route('admin.generalsettings.errorPage')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Error Page')}}</span></a>
        </li>
		
		
        <li>
            <a href="{{route('admin.generalsettings.home-category-section')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Home Category Section BN')}}</span></a>
        </li>
		
		 <!--<li>-->
   <!--         <a href="{{route('admin.generalsettings.home-category-section-en')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Home Category Section EN')}}</span></a>-->
   <!--     </li>-->

    </ul>
</li>

   
<li>
    <a href="#socials" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-paper-plane"></i>{{ __('Social Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="socials" data-parent="#accordion">
            <li><a href="{{route('social.link.index')}}"><span><i class="fas fa-angle-double-right"></i>{{ __('Social Links') }}</span></a></li>
            <li><a href="{{route('social.settings.google')}}"><span><i class="fas fa-angle-double-right"></i>{{ __('Google Login') }}</span></a></li>
            <li><a href="{{route('social.settings.facebook')}}"><span><i class="fas fa-angle-double-right"></i>{{ __('Facebook Login') }}</span></a></li>
    </ul>
</li>

  
<li>
    <a href="#emails" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-at"></i>{{__('Email Settings')}}
    </a>
    <ul class="collapse list-unstyled" id="emails" data-parent="#accordion">
        <li><a href="{{route('admin.email.config')}}"><span><i class="fas fa-angle-double-right"></i>{{__('Email Configurations')}}</span></a></li>  
    </ul>
</li>


<li>
    <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-wrench"></i>{{__('SEO Tools')}}
    </a>
    <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">
        <li>
            <a href="{{ route('seo.google.analytics') }}"><span><i class="fas fa-angle-double-right"></i>{{__('Google Analytics')}}</span></a>
        </li
        >
        <li>
            <a href="{{ route('seo.meta.keywords') }}"><span><i class="fas fa-angle-double-right"></i>{{__('Website Meta Keywords')}}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('admin.sitemap.all') }}" class=" wave-effect"><i class="fas fa-sitemap"></i>{{ __('Site Map') }}</a>
</li>
   


<li>
    <a href="{{ route('admin.role.index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Role Management') }}</a>
</li>  

<li>
    <a href="{{ route('admin.administator.index') }}"><span><i class="fas fa-angle-double-right"></i>{{__('Staff Management')}}</span></a>
</li>

<li>
    <a href="{{ route('admin.staff.index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Reporter') }}</a>
</li> 

<li>
    <a href="{{ route('admin.reader.index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Readers') }}</a>
</li> 

<li>
    <a href="{{ route('admin.administator.paymentrequest') }}" class="wave-effect">
        <i class="fa fa-credit-card"></i>
        {{ __('Payment Request') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.administator.receiverequest') }}" class="wave-effect">
        <i class="fa fa-inbox"></i>
        {{ __('Payment Receive Requests') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.administator.monthlypayments') }}" class="wave-effect">
        <i class="fa fa-calendar"></i>
        {{ __('Reporter Monthly Fee') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.administator.packageUpgradePayments') }}" class="wave-effect">
        <i class="fa fa-dollar"></i>
        {{ __('Package Upgrade Fee') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.administator.productPayments') }}" class="wave-effect">
        <i class="fa fa-shopping-cart"></i>
        {{ __('Product Payments') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.administator.bookPurchasePayments') }}" class="wave-effect">
        <i class="fa fa-book"></i>
        {{ __('Book Purchase Payments') }}
    </a>
</li>

<li>
    <a href="{{ route('admin.orders.index') }}" class="wave-effect">
        <i class="fa fa-box"></i>
        {{ __('Product Orders') }}
    </a>
</li>



<li>
    <a href="#staffSalary" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fa fa-credit-card"></i>{{ __('Staff Salary') }}
    </a>
    <ul class="collapse list-unstyled" id="staffSalary" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.designations.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Designations') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.employees.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Employees') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.salaries.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Pay Salary') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin.advance-salaries.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Advance Payments') }}</span></a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('admin.cache.clear') }}" class=" wave-effect"><i class="fa fa-database"></i>{{ __('Clear Cache') }}</a>
</li>    


<!--<li>
    <a href="#sactive" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cog"></i>{{ __('System Activation') }}
    </a>
    <ul class="collapse list-unstyled" id="sactive" data-parent="#accordion">

        <li><a href="{{route('admin-activation-form')}}"> {{ __('Activation') }}</a></li>
        <li><a href="{{route('admin-generate-backup')}}"> {{ __('Generate Backup') }}</a></li>
    </ul>
</li>

<li class="nav-item mt-5">
    <h6 class="text-primary text-center"> @lang('Version') : 2.3 </h6>
</li>-->
