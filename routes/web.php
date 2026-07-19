<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TransactionCategoryController;
use App\Http\Controllers\Front\FrontendController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Front\ProductShopController;
use App\Http\Controllers\Front\OrderCheckoutController;
use App\Http\Controllers\Front\PollVoteController;
use App\Http\Controllers\Front\PostQuizController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\WorldCupController;
use App\Http\Controllers\MonthlyFeePaymentController;
use App\Http\Controllers\EpsPaymentController;
use App\Http\Controllers\Admin\WorldCupMatchController;
use App\Http\Controllers\Admin\WorldCupStandingController;

Route::get('/fifa-world-cup/points-table', [WorldCupController::class, 'pointsTable'])
    ->name('worldcup.points');
    
Route::get('/install-app', function () {return view('install'); })->name('install.app');
Route::post('/poll-vote', [PollVoteController::class, 'pollvote'])->name('front.poll.vote');
Route::post('/quiz-answer', [PostQuizController::class, 'submit'])->name('quiz.answer');

Route::get('/poll-result/{id}', [PollVoteController::class, 'result'])->name('front.poll.result');

Route::get('/poll/stats/{id}', [PollVoteController::class, 'getStats'])->name('poll.stats');

Route::post('/user/products/modal-cache', function () {
    cache()->put(
        'products_modal_shown_' . auth()->id(),
        true,
        now()->addDay()
    );

    return response()->json(['status' => 'ok']);
})->name('user.products.modal.cache');

Route::get('/admin/sitemaps', 'Admin\SiteMapController@all')->name('admin.sitemap.all');
Route::get('/admin/sitemap.xml', 'Admin\SiteMapController@index')->name('sitemap.index');
Route::get('/admin/sitemap/categories.xml', 'Admin\SiteMapController@categories')->name('sitemap.categories');
Route::get('/admin/sitemap/subcategories.xml', 'Admin\SiteMapController@subcategories')->name('sitemap.subcategories');
Route::get('/admin/sitemap/posts.xml', 'Admin\SiteMapController@posts')->name('sitemap.posts');

Route::post('/user/reporter/cache', function () {
    cache()->put(
        'reporter_widget_seen_' . auth()->id(),
        true,
        now()->addDay()
    );

    return response()->json(['status' => 'ok']);
})->name('user.reporter.cache');

Route::get('/worldcup-test', function () {

    $response = Http::withHeaders([
        'x-apisports-key' => env('API_FOOTBALL_KEY'),
    ])->get('https://v3.football.api-sports.io/standings', [
        'league' => 1,
        'season' => 2022
    ]);

    return response()->json($response->json());
});



// routes/web.php

Route::post('/admin/reporter/generate-top-reporters',[StaffController::class, 'generateTopReporters'])->name('admin.staff.generate_top_reporters');

Route::get('/shop', [ProductShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ProductShopController::class, 'show'])->name('shop.show');

Route::post('/checkout', [OrderCheckoutController::class, 'checkout'])
    ->middleware('auth')
    ->name('checkout.store');

Route::get('/eps/pay', [EpsPaymentController::class, 'pay'])->name('eps.pay');
Route::get('/monthly-fee/pay', [EpsPaymentController::class, 'monthlyPay'])->name('monthly-fee.pay');
Route::get('/eps/success', [EpsPaymentController::class, 'success'])->name('eps.success');
Route::get('/eps/fail', [EpsPaymentController::class, 'fail'])->name('eps.fail');
Route::get('/eps/cancel', [EpsPaymentController::class, 'cancel'])->name('eps.cancel');

Route::post('/package-upgrade/pay',[EpsPaymentController::class, 'packageUpgradePay'])->name('package-upgrade.pay');
Route::post('/product/pay', [EpsPaymentController::class, 'productPay'])
    ->middleware('auth')
    ->name('product.pay');
Route::post('/books/{book}/purchase/pay', [EpsPaymentController::class, 'bookPay'])
    ->middleware('auth')
    ->name('book.pay');
Route::post('/courses/{course}/purchase/pay', [EpsPaymentController::class, 'coursePay'])
    ->middleware('auth')
    ->name('course.pay');


Route::get('/ip-location', function () {
        $ip = $_SERVER['REMOTE_ADDR'];
       
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            $ip = '8.8.8.8'; // Fallback IP
        }
       
   return Cache::remember("user_location_{$ip}", 7200, function () use ($ip) {
       
            $url = "http://ip-api.com/json/" . $ip;
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                $data = json_decode($response, true);
                
                // Handle different API response formats
                if (isset($data['lat']) && isset($data['lon'])) {
                    return [
                        'latitude' => $data['lat'],
                        'longitude' => $data['lon'],
                        'country' => $data['country'] ?? '',
                        'city' => $data['city'] ?? ''
                    ];
                } elseif (isset($data['latitude']) && isset($data['longitude'])) {
                    return [
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'country' => $data['country_name'] ?? $data['country'] ?? '',
                        'city' => $data['city'] ?? ''
                    ];
                }
            }
            return null;
        
   });
        
});

Route::redirect('admin', 'admin/login');
Route::get('/test',function(){

})->name('login');


Route::prefix('admin')->name('admin.')->group(function () {

    Route::get(
        '/worldcup-match',
        [WorldCupMatchController::class,'index']
    )->name('worldcup_match.index');

    Route::post(
        '/worldcup-match/store',
        [WorldCupMatchController::class,'store']
    )->name('worldcup_match.store');

    Route::put(
        '/worldcup-match/update/{id}',
        [WorldCupMatchController::class,'update']
    )->name('worldcup_match.update');

    Route::delete(
        '/worldcup-match/delete/{id}',
        [WorldCupMatchController::class,'destroy']
    )->name('worldcup_match.destroy');
});


Route::prefix('admin/worldcup')->name('admin.worldcup.')->group(function () {

    Route::get('/standings', [WorldCupStandingController::class, 'index'])
        ->name('index');

    Route::post('/store', [WorldCupStandingController::class, 'store'])
        ->name('store');

    Route::put('/update/{id}', [WorldCupStandingController::class, 'update'])
        ->name('update');

    Route::delete('/delete/{id}', [WorldCupStandingController::class, 'destroy'])
        ->name('destroy');
});

Route::prefix('admin')->group(function(){
    Route::get('/login','Admin\LoginController@loginForm')->name('admin.loginForm');
    Route::post('/login','Admin\LoginController@login')->name('admin.login');
    Route::post('/login/send-otp','Admin\LoginController@sendOtp')->name('admin.login.sendOtp');
    Route::post('/login/verify-otp','Admin\LoginController@verifyOtp')->name('admin.login.verifyOtp');
    
    Route::middleware(['admin.status'])->group(function () {

    Route::get('/forgot', 'Admin\LoginController@showForgotForm')->name('admin.forgot');
    Route::post('/forgot', 'Admin\LoginController@forgot')->name('admin.forgot.submit');


    Route::group(['middleware' => 'permissions:menu_builder'], function () {
        //--------------Menu Builder Area---------------
        Route::get('menu-builder','Admin\MenuBuilderController@index')->name('admin.menu.builder');
        Route::post('menu-builder/store','Admin\MenuBuilderController@store')->name('admin.menu.builder.store');
        //--------------Menu Builder Area---------------
    });
    
    Route::group(['middleware' => 'permissions:transaction'], function () {
        Route::resource('transactions', TransactionController::class);
        
    });
    
    Route::resource(
            'transaction-categories',
            TransactionCategoryController::class
        )->except(['create', 'show', 'edit']);
        
    // Route::group(['middleware' => 'permissions:products'], function () {
        
        Route::get('/product-categories', [ProductCategoryController::class, 'index'])
            ->name('admin.productCategories.index');
        Route::post('/product-categories', [ProductCategoryController::class, 'store'])
            ->name('admin.productCategories.store');
        Route::put('/product-categories/update/{id}', [ProductCategoryController::class, 'update'])
            ->name('admin.productCategories.update');
        Route::get('/product-categories/delete/{id}', [ProductCategoryController::class, 'destroy'])
            ->name('admin.productCategories.delete');
    
        // Products
        Route::get('/products', [ProductController::class, 'index'])
            ->name('admin.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('admin.products.create');
        Route::post('/products', [ProductController::class, 'store'])
            ->name('admin.products.store');
        Route::get('/products/edit/{id}', [ProductController::class, 'edit'])
            ->name('admin.products.edit');
        Route::post('/products/update/{id}', [ProductController::class, 'update'])
            ->name('admin.products.update');
        Route::get('/products/delete/{id}', [ProductController::class, 'destroy'])
            ->name('admin.products.delete');
        Route::post('/products/{id}/set-package', [ProductController::class, 'setPackage'])
            ->name('admin.products.setPackage');

        Route::group(['middleware' => 'permissions:product_orders'], function () {
            Route::get('/orders', [OrderController::class, 'index'])
                ->name('admin.orders.index');
            Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])
                ->name('admin.orders.updateStatus');
        });
    // });


    Route::group(['middleware' => 'permissions:categories'], function () {

        //--------------Categories Area Start-----------
        Route::get('/categories/datatables','Admin\CategoryController@categoriesDatatables')->name('categories.datatables');
        Route::get('/categories','Admin\CategoryController@categories')->name('categories.index');
        Route::get('/category/slug','Admin\CategoryController@categorySlug')->name('categories.categorySlug');
        Route::get('/categories/create','Admin\CategoryController@categoriesCreate')->name('categories.categoriesCreate');
        Route::post('/categories','Admin\CategoryController@categoriesStore')->name('categories.categoriesStore');
        Route::get('/categories/edit/{id}','Admin\CategoryController@categoriesEdit')->name('categories.categoriesEdit');
        Route::post('/categories/update/{id}','Admin\CategoryController@categoriesUpdate')->name('categories.categoriesUpdate');
        Route::get('/categories/delete/{id}','Admin\CategoryController@categoriesDelete')->name('categories.categoriesDelete');
        //--------------Categories Area End--------------
		
		 Route::get('/reportercategories/datatables','Admin\ReporterCategoryController@categoriesDatatables')->name('reportcategories.datatables');
        Route::get('/reportercategories','Admin\ReporterCategoryController@reportcategories')->name('reportcategories.index');
        Route::get('/reportercategories/slug','Admin\ReporterCategoryController@categorySlug')->name('reportcategories.categorySlug');
        Route::get('/reportercategories/create','Admin\ReporterCategoryController@categoriesCreate')->name('reportcategories.categoriesCreate');
        Route::post('/reportercategories','Admin\ReporterCategoryController@categoriesStore')->name('reportcategories.categoriesStore');
        Route::get('/reportercategories/edit/{id}','Admin\ReporterCategoryController@categoriesEdit')->name('reportcategories.categoriesEdit');
        Route::post('/reportercategories/update/{id}','Admin\ReporterCategoryController@categoriesUpdate')->name('reportcategories.categoriesUpdate');
        Route::get('/reportercategories/delete/{id}','Admin\ReporterCategoryController@categoriesDelete')->name('reportcategories.categoriesDelete');
		
		 Route::get('/videocategories/datatables','Admin\VideoCategoryController@categoriesDatatables')->name('videocategories.datatables');
        Route::get('/videocategories','Admin\VideoCategoryController@videocategories')->name('videocategories.index');
        Route::get('/videocategories/slug','Admin\VideoCategoryController@categorySlug')->name('videocategories.categorySlug');
        Route::get('/videocategories/create','Admin\VideoCategoryController@categoriesCreate')->name('videocategories.categoriesCreate');
        Route::post('/videocategories','Admin\VideoCategoryController@categoriesStore')->name('videocategories.categoriesStore');
        Route::get('/videocategories/edit/{id}','Admin\VideoCategoryController@categoriesEdit')->name('videocategories.categoriesEdit');
        Route::post('/videocategories/update/{id}','Admin\VideoCategoryController@categoriesUpdate')->name('videocategories.categoriesUpdate');
        Route::get('/videocategories/delete/{id}','Admin\VideoCategoryController@categoriesDelete')->name('videocategories.categoriesDelete');
		
		

        //-------------SubCategories Area Start-----------
        Route::get('/subcategories/datatables','Admin\SubCategoryController@datatables')->name('subcategories.datatables');
        Route::get('/subcategories','Admin\SubCategoryController@index')->name('subcategories.index');
        Route::get('/subcategories/create','Admin\SubCategoryController@create')->name('subcategories.create');
        Route::post('/subcategories','Admin\SubCategoryController@store')->name('subcategories.store');
        Route::get('/subcategories/edit/{id}','Admin\SubCategoryController@edit')->name('subcategories.edit');
        Route::post('/subcategories/update/{id}','Admin\SubCategoryController@update')->name('subcategories.update');
        Route::get('/subcategories/delete/{id}','Admin\SubCategoryController@delete')->name('subcategories.delete');
        Route::get('/subcategories/languageOnUpdate/{x}/{y}','Admin\SubCategoryController@languageOnUpdate')->name('subcategories.languageOnUpdate');

        //-------------SubCategories Area End---------------
    });


    Route::group(['middleware' => 'permissions:add_post'], function () {

        //  New post
        Route::get('/add-post', function(){
            return view('admin.post.format');
        })->name('admin.post.format');


		Route::get('/idcard/{id?}/{type}', 'Admin\DashboardController@idcard')->name('admin.idcard');
		
		Route::get('/applicationform/{id?}/{type}', 'Admin\DashboardController@applicationform')->name('admin.applicationform');
		
		

        //-------------Post Article area--------------------
        Route::get('/add-article','Admin\ArticleController@create')->name('article.create');
        Route::post('/add-article','Admin\ArticleController@store')->name('article.store');
        Route::post('/article/update/{id}','Admin\ArticleController@update')->name('article.update');
        Route::get('/add-article/subcategory/{id}','Admin\ArticleController@subcategory')->name('article.subcategory');
        Route::get('/edit-article/subcategoryUpdate/{x}/{y}','Admin\ArticleController@subcategoryUpdate')->name('article.subcategoryUpdate');
        Route::get('/search-cover-images', 'Admin\ArticleController@searchCoverImages')->name('article.searchCoverImages');
        //-------------slug create-------------------------
        Route::get('/add-article/slugCreate/','Admin\ArticleController@slugCreate')->name('article.slugCreate');
        //-------------slug check--------------------------
        Route::get('/add-article/slugCheck/','Admin\ArticleController@slugCheck')->name('article.slugCheck');
        //-------------category by language----------------
        Route::get('/add-article/languageOnUpdate/{x}/{y}','Admin\ArticleController@languageOnUpdate');
        Route::get('/add-article/language/{id}','Admin\ArticleController@language');
        //-------------Post Article area end----------------
		
		  //-------------albumcategory by language----------------
        Route::get('/add-album/languageOnUpdate/{x}/{y}','Admin\ImageAlbumController@languageOnUpdate');
        Route::get('/add-album/language/{id}','Admin\ImageAlbumController@language');
        //-------------albumcategory area end----------------



        //-------------Post Audio area-----------
        Route::get('/add-audio','Admin\AudioController@create')->name('audio.create');
        Route::post('/add-audio','Admin\AudioController@store')->name('audio.store');
        Route::post('/audio/update/{id}','Admin\AudioController@update')->name('audio.update');
        Route::get('/add-audio/subcategory/{id}','Admin\AudioController@subcategory')->name('audio.subcategory');
        Route::get('/edit-audio/subcategoryUpdate/{x}/{y}','Admin\AudioController@subcategoryUpdate')->name('audio.subcategoryUpdate');
        //------------slug create----------------
        Route::get('/add-audio/slugCreate/','Admin\AudioController@slugCreate')->name('audio.slugCreate');
        //------------slug check----------------
        Route::get('/add-audio/slugCheck/','Admin\AudioController@slugCheck')->name('audio.slugCheck');
        //------------category by language---------
        Route::get('/add-audio/language/{id}','Admin\AudioController@language')->name('audio.language');
        Route::get('/add-audio/languageOnUpdate/{x}/{y}','Admin\AudioController@languageOnUpdate')->name('audio.languageOnUpdate');
        //-------------Post Audio area end-----------




        //-------------Post Video area------------
        Route::get('/add-video','Admin\VideoController@create')->name('video.create');
        Route::post('/add-video','Admin\VideoController@store')->name('video.store');
        Route::post('/video/update/{id}','Admin\VideoController@update')->name('video.update');
        Route::get('/add-video/subcategory/{id}','Admin\VideoController@subcategory')->name('video.subcategory');
        Route::get('/edit-video/subcategoryUpdate/{x}/{y}','Admin\VideoController@subcategoryUpdate')->name('video.subcategoryUpdate');
        //--------------slug create---------------
        Route::get('/add-video/slugCreate/','Admin\VideoController@slugCreate')->name('video.slugCreate');
        //---------------slug check---------------
        Route::get('/add-video/slugCheck/','Admin\VideoController@slugCheck')->name('video.slugCheck');
        //---------------category by language-------
        Route::get('/add-video/language/{id}','Admin\VideoController@language')->name('video.language');
        Route::get('/add-video/languageOnUpdate/{x}/{y}','Admin\VideoController@languageOnUpdate')->name('video.languageOnUpdate');
        //-------------Post Video area------------


        //-------------Post Trivia Quiz Area----------------
        Route::get('/add-tquiz', 'Admin\TQuizController@create')->name('tquiz.create');
        Route::post('/add-tquiz/submit', 'Admin\TQuizController@store')->name('tquiz.store');
        Route::post('/add-tquiz/update/{id}', 'Admin\TQuizController@update')->name('tquiz.update');
        Route::get('/remove-tquizquestion/{id}','Admin\TQuizController@removequestion')->name('tquiz.removequestion');
        Route::get('/remove-tquizanswer/{id}','Admin\TQuizController@removeanswer')->name('tquiz.removeanswer');
        Route::get('/remove-tquizresult/{id}','Admin\TQuizController@removeresult')->name('tquiz.removeresult');
        //-------------Post Trivia Quiz Area----------------


        //-------------Post Personality Quiz Area--------------
        Route::get('/add-pquiz', 'Admin\PQuizController@create')->name('pquiz.create');
        Route::post('/add-pquiz', 'Admin\PQuizController@store')->name('pquiz.store');
        Route::post('/add-pquiz/update/{id}','Admin\PQuizController@update')->name('pquiz.update');
        Route::get('/remove-pquizanswer/{id}','Admin\PQuizController@removeanswer')->name('pquiz.removeanswer');
        Route::get('/remove-pquizresult/{id}','Admin\PQuizController@removepresult')->name('pquiz.removeresult');
        Route::get('/remove-pquizquestion/{id}','Admin\PQuizController@removepquestion')->name('pquiz.removepquestion');
        //-------------Post Personality Quiz Area--------------
        
        Route::get('/post-quizzes', 'Admin\PostQuizResultController@index')
        ->name('post.quiz.index');
        
        Route::get('/post-quiz/{quiz}/participants', 'Admin\PostQuizResultController@show')
            ->name('post.quiz.participants');
        
        Route::post('/post-quiz/{quiz}/draw-winners', 'Admin\PostQuizResultController@drawWinners')
            ->name('post.quiz.draw.winners');
            
        Route::delete('/post-quiz/{quiz}/delete', 'Admin\PostQuizResultController@destroy')
        ->name('post.quiz.destroy');
        
        Route::post('/post-quizzes/day/{date}/draw-winners', 'Admin\PostQuizResultController@drawDayWinnersByDate')
        ->name('post.quiz.day.draw.winners');

        Route::post('/post-quizzes/weekly/draw-winners', 'Admin\PostQuizResultController@drawWeeklyWinners')
            ->name('post.quiz.weekly.draw.winners');
        
        Route::post('/post-quiz-answer/{answer}/choose-winner', 'Admin\PostQuizResultController@chooseManualWinner')
            ->name('post.quiz.answer.choose.winner');

        Route::post('/post-quiz-weekly-participant/{user}/choose-winner', 'Admin\PostQuizResultController@chooseWeeklyManualWinner')
            ->name('post.quiz.weekly.choose.winner');
        
        Route::delete('/post-quiz-winner/{winner}/remove', 'Admin\PostQuizResultController@removeWinner')
            ->name('post.quiz.winner.remove');


        //------------Post Shorted List Area-------------------
        Route::get('/add-shortlist','Admin\ShortListController@create')->name('shortlist.create');
        Route::post('/add-shortlist','Admin\ShortListController@store')->name('shortlist.store');
        Route::post('/add-shortlist/update/{id}','Admin\ShortListController@update')->name('shortlist.update');
        Route::get('/remove-shortlist/{id}','Admin\ShortListController@remove')->name('shortlist.remove');
        //------------Post Shorted List Area-------------------
    });


   //----------------post show area-----------

   Route::group(['middleware' => 'permissions:posts'], function () {

       //-------------Slider Post Area------------
        Route::get('/post/datatables','Admin\PostController@datatables')->name('post.datatables');
		Route::get('/post/postledgerdatatables','Admin\PostController@postledgerdatatables')->name('post.postledgerdatatables');
		
        Route::get('/post','Admin\PostController@index')->name('post.index');
        
        Route::get('/post/approved', 'Admin\PostController@approved')->name('post.approved');
        Route::get('/post/approved/datatables', 'Admin\PostController@approvedDatatables')->name('post.approved.datatables');
         Route::get('/post/rejected', 'Admin\PostController@rejected')->name('post.rejected');
        Route::get('/post/rejected/datatables', 'Admin\PostController@rejectedDatatables')->name('post.rejected.datatables');
 

		Route::get('/postledger','Admin\PostController@postledger')->name('post.postledger');
        Route::get('/post/edit/{id}','Admin\PostController@edit')->name('post.edit');
        Route::get('/post/view/{id}','Admin\PostController@view')->name('post.view');
        Route::get('/post/slider/{id}','Admin\PostController@sliderChange')->name('post.sliderChange');
        Route::get('/post/feature/{id}','Admin\PostController@featureChange')->name('post.feature');
        Route::get('/post/sliderright/{id}','Admin\PostController@sliderright')->name('post.sliderright');
        Route::get('/post/trending/{id}','Admin\PostController@trendingChange')->name('post.trendingChange');
        Route::get('/post/pending/{id}','Admin\PostController@pendingChange')->name('post.pendingChange');
        Route::get('/post/delete/{id}','Admin\PostController@delete')->name('post.delete');
        Route::get('/post/sliderBulk','Admin\PostController@sliderBulk')->name('post.add.sliderBulk');
        Route::get('/post/breakingBulk','Admin\PostController@breakingBulk')->name('post.add.breakingBulk');
        Route::get('/post/featureBulk','Admin\PostController@featureBulk')->name('post.add.feature');
        Route::get('/post/rightBulk','Admin\PostController@rightBulk')->name('post.add.rightBulk');
        Route::get('/post/remove/sliderBulk','Admin\PostController@removesliderBulk')->name('post.remove.sliderBulk');
        Route::get('/post/remove/breakingBulk','Admin\PostController@removebreakingBulk')->name('post.remove.breakingBulk');
        Route::get('/post/remove/featureBulk','Admin\PostController@removefeatureBulk')->name('post.remove.featureBulk');
        Route::get('/post/remove/rightBulk','Admin\PostController@removerightBulk')->name('post.remove.rightBulk');
        Route::get('/post/bulkdelete','Admin\PostController@bulkdelete')->name('post.bulkdelete');


        Route::get('/category-filter/language/{id}','Admin\PostController@categoryFilter');
		Route::get('/category-filter-ledger/language/{id}','Admin\PostController@categoryFilterledger')->name('categoryFilter-ledger.language');
        //-------------Slider Post Area------------

        //--------------Slider Post Area----------
        Route::get('/slider/datatables','Admin\SliderController@datatables')->name('slider.datatables');
        Route::get('/slider','Admin\SliderController@index')->name('slider.index');
        Route::get('/slider/category-filter/language/{id}','Admin\SliderController@categoryFilter');
       //--------------Slider Post Area----------

        //-------------feature Post Area-----------
        Route::get('/feature/datatables','Admin\FeaturedController@datatables')->name('feature.datatables');
        Route::get('/feature','Admin\FeaturedController@index')->name('feature.index');
        Route::get('/feature/category-filter/language/{id}','Admin\FeaturedController@categoryFilter');
        //-------------feature Post Area-----------


        //------------breaking Post Area------------
        Route::get('/breaking/datatables','Admin\BreakingController@datatables')->name('breaking.datatables');
        Route::get('/breaking','Admin\BreakingController@index')->name('breaking.index');
        Route::get('/breaking/category-filter/language/{id}','Admin\BreakingController@categoryFilter')->name('breaking.categoryFilter.language');
        //------------breaking Post Area------------

         //-----------pending Post Area-----------
         Route::get('/pending/datatables','Admin\PendingController@datatables')->name('pending.datatables');
         Route::get('/pending','Admin\PendingController@index')->name('pending.index');
         Route::get('/pending/category-filter/language/{id}','Admin\PendingController@categoryFilter')->name('pending.categoryFilter.language');
         //-----------pending Post Area-----------
   });

    Route::group(['middleware' => 'permissions:schedule_post'], function () {

        //------------Schedule Post Area----------
        Route::get('/schedule/datatables','Admin\ScheduleController@datatables')->name('schedule.datatables');
        Route::get('/schedule','Admin\ScheduleController@index')->name('schedule.index');
        Route::get('/schedule/postApprove','Admin\ScheduleController@postApprove')->name('schedule.postApprove');
        //------------Schedule Post Area----------
    });

    Route::group(['middleware' => 'permissions:drafts'], function () {

        //-----------Draft Post Area------------
        Route::get('/draft/datatables','Admin\DraftController@datatables')->name('draft.datatables');
        Route::get('/draft','Admin\DraftController@index')->name('draft.index');
        Route::get('/draft/article/approve','Admin\DraftController@draftArticle')->name('draft.article');
        Route::get('/draft/audio/approve','Admin\DraftController@draftAudio')->name('draft.audio');
        Route::get('/draft/video/approve','Admin\DraftController@draftVideo')->name('draft.video');
        //-----------Draft Post Area end------------
    });


    //----------------post show area end-----------

    Route::group(['middleware' => 'permissions:rss_feeds'], function () {

        //-------------Rss Feeds Section-----------
        Route::get('/rss/datatables','Admin\RssFeedsController@datatables')->name('rss.datatables');
        Route::get('/rss','Admin\RssFeedsController@index')->name('rss.index');
        Route::get('/rss/create','Admin\RssFeedsController@create')->name('rss.create');
        Route::post('/rss','Admin\RssFeedsController@store')->name('rss.store');
        Route::get('/rss/edit/{id}','Admin\RssFeedsController@edit')->name('rss.edit');
        Route::post('/rss/update/{id}','Admin\RssFeedsController@update')->name('rss.update');
        Route::get('/rss/delete/{id}','Admin\RssFeedsController@delete')->name('rss.delete');
        Route::get('/rss/category/{id}','Admin\RssFeedsController@categoryByLanguage')->name('rss.category');
        Route::get('/rss/categoryUpdate/{x}/{y}','Admin\RssFeedsController@categoryByLanguageUpdate')->name('rss.categoryUpdate');
        Route::get('rss-feed/update/{id}','Admin\RssFeedsController@feedUpdate')->name('rss.feedUpdate');
        //-------------Rss Feeds Section Ends-----------
    });


    Route::group(['middleware' => 'permissions:languages'], function () {

        //------------Language area--------------
        Route::get('/language/datatables','Admin\LanguageController@datatables')->name('language.datatables');
        Route::get('/add-language','Admin\LanguageController@index')->name('admin.language.index');
        Route::get('/add-language/create','Admin\LanguageController@create')->name('admin.language.create');
        Route::post('/add-language','Admin\LanguageController@store')->name('admin.language.store');
        Route::get('/add-language/edit/{id}','Admin\LanguageController@edit')->name('admin.language.edit');
        Route::post('/add-language/update/{id}','Admin\LanguageController@update')->name('admin.language.update');
        Route::get('/add-language/delete/{id}','Admin\LanguageController@delete')->name('admin.language.delete');
        Route::get('/languages/status/{id}', 'Admin\LanguageController@status')->name('admin.language.status');
        //------------Language area end--------------


        //-------------Admin Language Area--------------
        Route::get('/admin-language/datatables','Admin\AdminLanguageController@datatables')->name('admin_language.datatables');
        Route::get('/admin-add-language','Admin\AdminLanguageController@index')->name('admin.admin_language.index');
        Route::get('/admin-add-language/create','Admin\AdminLanguageController@create')->name('admin.admin_language.create');
        Route::post('/admin-add-language','Admin\AdminLanguageController@store')->name('admin.admin_language.store');
        Route::get('/admin-add-language/edit/{id}','Admin\AdminLanguageController@edit')->name('admin.admin_language.edit');
        Route::post('/admin-add-language/update/{id}','Admin\AdminLanguageController@update')->name('admin.admin_language.update');
        Route::get('/admin-add-language/delete/{id}','Admin\AdminLanguageController@delete')->name('admin.admin_language.delete');
        Route::get('/admin-languages/status/{id}', 'Admin\AdminLanguageController@status')->name('admin.admin_language.status');
        //-------------Admin Language Area--------------
    });



    //-------------gallery area--------------
    Route::get('/gallery/show', 'Admin\GalleryController@show')->name('admin.gallery.show');
    Route::post('/gallery/store', 'Admin\GalleryController@store')->name('admin.gallery.store');
    Route::get('/gallery/delete', 'Admin\GalleryController@destroy')->name('admin.gallery.delete');
    //-------------gallery area end--------------

    Route::group(['middleware' => 'permissions:polls'], function () {

        //-------------Polls Area Start----------
        Route::get('/add-polls/datatables','Admin\PollController@datatables')->name('addPolls.datatables');
        Route::get('/add-polls','Admin\PollController@index')->name('addPolls.index');
        Route::get('/add-polls/create','Admin\PollController@create')->name('addPolls.create');
        Route::post('/add-polls','Admin\PollController@store')->name('addPolls.store');
        Route::get('/add-polls/edit/{id}','Admin\PollController@edit')->name('addPolls.edit');
        Route::post('/add-polls/update/{id}','Admin\PollController@update')->name('addPolls.update');
        Route::get('/add-polls/delete/{id}','Admin\PollController@delete')->name('addPolls.delete');
        Route::get('/add-polls/showOnHomePage','Admin\PollController@showOnHomePage')->name('addPolls.showOnHomePage');

        Route::get('/poll-option/create/{id}','Admin\PollController@pollcreate')->name('pollOption.create');
        Route::post('/poll-option/create','Admin\PollController@pollstore')->name('pollOption.pollstore');
        Route::get('/poll-option/edit/{id}','Admin\PollController@polledit')->name('pollOption.polledit');
        Route::get('/poll-option/update/{id}','Admin\PollController@pollupdate')->name('pollOption.pollupdate');
        Route::get('/poll-option/view/{id}','Admin\PollController@pollview')->name('pollOption.pollview');
        Route::get('/poll-option/delete/{id}','Admin\PollController@optiondelete')->name('pollOption.optiondelete');
        //-------------Polls Area Start End----------
    });


    Route::group(['middleware' => 'permissions:widgets'], function () {

        //----------Widget Section Area----------
        Route::get('/widget/datatables','Admin\WidgetController@datatables')->name('widget.datatables');
        Route::get('/widget/index','Admin\WidgetController@index')->name('widget.index');
        Route::get('widget/create','Admin\WidgetController@create')->name('widget.create');
        Route::post('widget/store','Admin\WidgetController@store')->name('widget.store');
        Route::get('widget/edit/{id}','Admin\WidgetController@edit')->name('widget.edit');
        Route::post('widget/update/{id}','Admin\WidgetController@update')->name('widget.update');
        Route::get('widget/delete/{id}','Admin\WidgetController@delete')->name('widget.delete');
        Route::get('widget-settings','Admin\WidgetController@widgetSettings')->name('widget.settings');
        Route::post('widget-settings/update','Admin\WidgetController@widgetSettingsUpdate')->name('widget.settings.update');
        //----------Widget Section Area End-----------
    });


    Route::group(['middleware' => 'permissions:create_ads'], function () {

        //---------Ads Section---------
        Route::get('/ads/datatables','Admin\AddSpaceController@datatables')->name('ads.datatables');
        Route::get('/ads/index','Admin\AddSpaceController@index')->name('ads.index');
        Route::get('/ads/create','Admin\AddSpaceController@create')->name('ads.create');
        Route::post('/ads/store','Admin\AddSpaceController@store')->name('ads.store');
        Route::get('/ads/edit/{id}','Admin\AddSpaceController@edit')->name('ads.edit');
        Route::post('/ads/update/{id}','Admin\AddSpaceController@update')->name('ads.update');
        Route::get('/ads/delete/{id}','Admin\AddSpaceController@delete')->name('ads.delete');
        //---------Ads Section end---------
    });


    Route::group(['middleware' => 'permissions:add_gallery'], function () {

        //--------------Image Album Section-------------
        Route::get('/image-album/datatables','Admin\ImageAlbumController@datatables')->name('image.album.datatables');
        Route::get('/image-album','Admin\ImageAlbumController@index')->name('image.album.index');
        Route::get('/image-album/create','Admin\ImageAlbumController@create')->name('image.album.create');
        Route::post('/image-album','Admin\ImageAlbumController@store')->name('image.album.store');
        Route::get('/image-album/edit/{id}','Admin\ImageAlbumController@edit')->name('image.album.edit');
        Route::post('/image-album/update/{id}','Admin\ImageAlbumController@update')->name('image.album.update');
        Route::get('/image-album/delete/{id}','Admin\ImageAlbumController@delete')->name('image.album.delete');
        //--------------Image Album Section-------------

        //--------------Image Category Section-------------
        Route::get('/image-category/datatables','Admin\ImageCategoryController@datatables')->name('image.category.datatables');
        Route::get('/image-category','Admin\ImageCategoryController@index')->name('image.category.index');
        Route::get('/image-category/create','Admin\ImageCategoryController@create')->name('image.category.create');
        Route::post('/image-category','Admin\ImageCategoryController@store')->name('image.category.store');
        Route::get('/image-category/edit/{id}','Admin\ImageCategoryController@edit')->name('image.category.edit');
        Route::post('/image-category/update/{id}','Admin\ImageCategoryController@update')->name('image.category.update');
        Route::get('/image-category/delete/{id}','Admin\ImageCategoryController@delete')->name('image.category.delete');
        Route::get('/categoryByLanguage/{id}','Admin\ImageCategoryController@categoryByLanguage')->name('image.categoryByLanguage');
        Route::get('/languageOnUpdate/{x}/{y}','Admin\ImageCategoryController@languageOnUpdate')->name('image.languageOnUpdate');
        //--------------Image Category Section-------------

        //-------------Image Gallery Section---------------
        Route::get('/image-gallery/datatables','Admin\ImageGalleryController@datatables')->name('image.gallery.datatables');
        Route::get('/image-gallery','Admin\ImageGalleryController@index')->name('image.gallery.index');
        Route::get('/image-gallery/create','Admin\ImageGalleryController@create')->name('image.gallery.create');
        Route::post('/image-gallery','Admin\ImageGalleryController@store')->name('image.gallery.store');
        Route::get('/image-gallery/edit/{id}','Admin\ImageGalleryController@edit')->name('image.gallery.edit');
        Route::post('/image-gallery/update/{id}','Admin\ImageGalleryController@update')->name('image.gallery.update');
        Route::get('/image-gallery/delete/{id}','Admin\ImageGalleryController@delete')->name('image.gallery.delete');
        Route::get('/image-gallery/galleryShow/{id}','Admin\ImageGalleryController@galleryShow')->name('image.gallery.galleryShow');
        Route::get('/albumByLanguage/{id}','Admin\ImageGalleryController@albumByLanguage')->name('gallery.albumByLanguage');
        Route::get('/categoryByAlbum/{id}','Admin\ImageGalleryController@categoryByAlbum')->name('gallery.categoryByAlbum');
        Route::get('/albumByLanguageUpdate/{x}/{y}','Admin\ImageGalleryController@albumByLanguageUpdate')->name('gallery.albumByLanguageUpdate');
        Route::get('/categoryByAlbumUpdate/{x}/{y}','Admin\ImageGalleryController@categoryByAlbumUpdate')->name('gallery.categoryByAlbumUpdate');
        //-------------Image Gallery Section---------------
    });


    Route::group(['middleware' => 'permissions:general_settings'], function () {

        //------------General Settings-----------
        Route::post('/generalsettings/update','Admin\GeneralSettingsController@update')->name('admin.generalsettings.update');
        Route::get('/generalsettings/logo','Admin\GeneralSettingsController@logo')->name('admin.generalsettings.logo');
        
        Route::get('/generalsettings/fees','Admin\GeneralSettingsController@fees')->name('admin.generalsettings.fees');
        Route::post('/generalsettings/fees/update', 'Admin\GeneralSettingsController@feesUpdate')->name('admin.generalsettings.fees.update');
        
        Route::get('/generalsettings/favicon','Admin\GeneralSettingsController@favicon')->name('admin.generalsettings.favicon');
        Route::get('/generalsettings/loader','Admin\GeneralSettingsController@loader')->name('admin.generalsettings.loader');
        Route::get('/generalsettings/website/content','Admin\GeneralSettingsController@websiteContent')->name('admin.generalsettings.websiteContent');
        Route::get('/generalsettings/footer','Admin\GeneralSettingsController@footer')->name('admin.generalsettings.footer');
        Route::get('/generalsettings/error/page','Admin\GeneralSettingsController@errorPage')->name('admin.generalsettings.errorPage');
        Route::get('/generalsettings/popular/tags','Admin\GeneralSettingsController@popularTags')->name('admin.generalsettings.popularTags');
		
		Route::get('/generalsettings/home-category-section','Admin\GeneralSettingsController@homecategorysection')->name('admin.generalsettings.home-category-section');
		
		Route::get('/generalsettings/home-category-section-en','Admin\GeneralSettingsController@homecategorysectionen')->name('admin.generalsettings.home-category-section-en');

        Route::get('/generalsettings/tawakto/{x}','Admin\GeneralSettingsController@tawkto')->name('admin.generalsettings.tawkto');
        Route::get('/generalsettings/isLoader/{x}','Admin\GeneralSettingsController@isLoader')->name('admin.generalsettings.isLoader');
        Route::get('/generalsettings/isAdminLoader/{x}','Admin\GeneralSettingsController@isAdminLoader')->name('admin.generalsettings.isAdminLoader');
        Route::get('/generalsettings/disqus/{x}','Admin\GeneralSettingsController@disqus')->name('admin.generalsettings.disqus');
        Route::get('/generalsettings/smtp/{x}','Admin\GeneralSettingsController@smtp')->name('admin.generalsettings.smtp');
        Route::Get('/generalsettings/capcha/{x}','Admin\GeneralSettingsController@capcha')->name('admin.generalsettings.capcha');
        Route::Get('/generalsettings/emailverfication/{x}','Admin\GeneralSettingsController@emailVerfication')->name('admin.generalsettings.emailverfication');


        //-------------Language Base Logo Area----------------
        Route::get('/language/logo/datatables','Admin\LogoController@datatables')->name('admin.languagelogo.datatables');
        Route::get('/language/logo','Admin\LogoController@index')->name('admin.languagelogo.index');
        Route::get('/language/logo/create','Admin\LogoController@create')->name('admin.languagelogo.create');
        Route::post('/language/logo','Admin\LogoController@store')->name('admin.languagelogo.store');
        Route::get('/language/logo/edit/{id}','Admin\LogoController@edit')->name('admin.languagelogo.edit');
        Route::post('/language/logo/update/{id}','Admin\LogoController@update')->name('admin.languagelogo.update');
        Route::get('/language/logo/delete/{id}','Admin\LogoController@delete')->name('admin.languagelogo.delete');
        //-------------Language Base Logo Area----------------

        //------------General Settings-----------
    });



    Route::group(['middleware' => 'permissions:seo_tools'], function () {

        //------------Seo Management-------------
        Route::post('seo/update','Admin\SeoController@update')->name('seo.update');
        Route::get('seo/google-analytics','Admin\SeoController@googleAnalytics')->name('seo.google.analytics');
        Route::get('seo/meta-keywords','Admin\SeoController@metaKeywords')->name('seo.meta.keywords');
        //------------Seo Management-------------
    });


    Route::group(['middleware' => 'permissions:social_settings'], function () {

        //-------------SocialSettings Manage-----------
        Route::post('social-settings/update','Admin\SocialSettingsController@update')->name('social.settings.update');
        Route::get('social-settings/google','Admin\SocialSettingsController@google')->name('social.settings.google');
        Route::get('social-settings/facebook','Admin\SocialSettingsController@facebook')->name('social.settings.facebook');
        //-------------SocialSettings Manage-----------

        //-------------SocialLink Manage-------------
        Route::get('social-link/datatables','Admin\SocialLinkController@datatables')->name('social.link.datatables');
        Route::get('social-link','Admin\SocialLinkController@index')->name('social.link.index');
        Route::get('social-link/create','Admin\SocialLinkController@create')->name('social.link.create');
        Route::post('social-link','Admin\SocialLinkController@store')->name('social.link.store');
        Route::get('social-link/edit/{id}','Admin\SocialLinkController@edit')->name('social.link.edit');
        Route::post('social-link/update/{id}','Admin\SocialLinkController@update')->name('social.link.update');
        Route::get('social-link/delete/{id}','Admin\SocialLinkController@delete')->name('social.link.delete');
        //-------------SocialLink Manage-------------
    });


    Route::group(['middleware' => 'permissions:pages'], function () {

        //-------------Page Create Area----------------
        Route::get('page/datatables','Admin\PageController@datatables')->name('admin.page.datatables');
        Route::get('/page','Admin\PageController@index')->name('admin.page.index');
        Route::get('/page/create','Admin\PageController@create')->name('admin.page.create');
        Route::post('/page','Admin\PageController@store')->name('admin.page.store');
        Route::get('/page/edit/{id}','Admin\PageController@edit')->name('admin.page.edit');
        Route::post('/page/update/{id}','Admin\PageController@update')->name('admin.page.update');
        Route::get('/page/delete/{id}','Admin\PageController@delete')->name('admin.page.delete');
        Route::get('/page/slugCreate','Admin\PageController@slugCreate')->name('admin.page.slugCreate');
        //-------------Page Create Area----------------
    });
	
	Route::group(['middleware' => 'permissions:rashifall'], function () {

        //-------------Page Create Area----------------
        Route::get('rashifall/datatables','Admin\RashifallController@datatables')->name('admin.rashifall.datatables');
        Route::get('/rashifall','Admin\RashifallController@index')->name('admin.rashifall.index');
        Route::get('/rashifall/create','Admin\RashifallController@create')->name('admin.rashifall.create');
        Route::post('/rashifall','Admin\RashifallController@store')->name('admin.rashifall.store');
        Route::get('/rashifall/edit/{id}','Admin\RashifallController@edit')->name('admin.rashifall.edit');
        Route::post('/rashifall/update/{id}','Admin\RashifallController@update')->name('admin.rashifall.update');
        Route::get('/rashifall/delete/{id}','Admin\RashifallController@delete')->name('admin.rashifall.delete');
        Route::get('/rashifall/slugCreate','Admin\RashifallController@slugCreate')->name('admin.rashifall.slugCreate');
        //-------------Page Create Area----------------
    });



    Route::group(['middleware' => 'permissions:emails_settings'], function () {

        //---------------Email Manage-------------------
        Route::get('email/config','Admin\EmailController@config')->name('admin.email.config');
        Route::get('email/group','Admin\EmailController@group')->name('admin.email.group');
        Route::post('email/group','Admin\EmailController@groupmailsend')->name('admin.email.groupmailsend');
        //---------------Email Manage-------------------
    });


    Route::group(['middleware' => 'permissions:newsLetter'], function () {

        //---------------Subscriber----------------------
        Route::get('/subscriber/datatables','Admin\SubscriberController@datatables')->name('admin.subscriber.datatables');
        Route::get('/subscriber','Admin\SubscriberController@index')->name('admin.subscriber.index');
        Route::get('/subscriber/download','Admin\SubscriberController@download')->name('admin.subscriber.download');
        Route::get('/send-mail','Admin\SubscriberController@email')->name('admin.subscriber.email');
        Route::post('/send-mail','Admin\SubscriberController@sendemail')->name('admin.subscriber.sendemail');
        //---------------Subscriber----------------------
    });


    Route::group(['middleware' => 'permissions:role_management'], function () {

        //----------------Role Management-----------------
        Route::get('/role/datatables','Admin\RoleController@datatables')->name('admin.role.datatables');
        Route::get('/role','Admin\RoleController@index')->name('admin.role.index');
        Route::get('/role/create','Admin\RoleController@create')->name('admin.role.create');
        Route::post('/role','Admin\RoleController@store')->name('admin.role.store');
        Route::get('/role/edit/{id}','Admin\RoleController@edit')->name('admin.role.edit');
        Route::post('/role/update/{id}','Admin\RoleController@update')->name('admin.role.update');
        Route::get('/role/update/{id}','Admin\RoleController@delete')->name('admin.role.delete');
        //----------------Role Management-----------------
    });


    Route::group(['middleware' => 'permissions:user_management'], function () {
        //----------------Staff Management----------------
        Route::get('/user/datatables','Admin\StaffController@datatables')->name('admin.staff.datatables');
        
        Route::get('/reader/datatables','Admin\StaffController@reader_datatables')->name('admin.readers.datatables');
                
		 Route::get('/user/userPostdatatables','Admin\StaffController@userPostdatatables')->name('admin.staff.userPostdatatables');
		 
        Route::get('/user','Admin\StaffController@index')->name('admin.staff.index');
        
        Route::post('/user/update-next-payment', 'Admin\StaffController@updateNextPaymentDate')->name('admin.staff.update_next_payment');
        Route::get('/user/weekly-best-candidates', 'Admin\StaffController@getWeeklyBestCandidates')->name('admin.staff.weekly_best_candidates');
        Route::post('/user/set-weekly-best', 'Admin\StaffController@setWeeklyBest')->name('admin.staff.set_weekly_best');

        
        Route::get('/user/top-reporters', 'Admin\StaffController@topReportersMonth')->name('admin.staff.top_reporters');

        
        Route::get('/readers','Admin\StaffController@reader_index')->name('admin.reader.index');
        
        Route::get('/user/create','Admin\StaffController@create')->name('admin.staff.create');
        Route::post('/user','Admin\StaffController@store')->name('admin.staff.store');
        Route::get('/user/edit/{id}','Admin\StaffController@edit')->name('admin.staff.edit');
        Route::post('/user/update/{id}','Admin\StaffController@update')->name('admin.staff.update');
        Route::get('/user/delete/{id}','Admin\StaffController@delete')->name('admin.staff.delete');
        Route::get('/user/ban/{id}','Admin\StaffController@ban')->name('admin.staff.ban');
		
		Route::get('/user/user_income_detail/{id}','Admin\StaffController@user_income_detail')->name('admin.staff.user_income_detail');
		
		Route::get('/user/{id}/orders','Admin\StaffController@userOrders')->name('admin.staff.orders');
        //----------------Staff Management----------------
    });

    Route::group(['middleware' => 'permissions:administration_management'], function () {
        //----------------Staff Management----------------
        Route::get('/administator/datatables','Admin\AdministerController@datatables')->name('admin.administator.datatables');
        Route::get('/administator','Admin\AdministerController@index')->name('admin.administator.index');
		

		 Route::get('/paymentdatatables','Admin\AdministerController@paymentdatatables')->name('admin.administator.paymentdatatables');
		
		Route::get('/paymentrequest','Admin\AdministerController@paymentrequest')->name('admin.administator.paymentrequest');
		Route::any('/paymentcerate', 'Admin\AdministerController@paymentcerate')->name('admin.administator.paymentcerate');
		Route::any('/paymentedit/{id}', 'Admin\AdministerController@paymentedit')->name('admin.administator.paymentedit');
		Route::any('/paymentupdate/{id}', 'Admin\AdministerController@paymentupdate')->name('admin.administator.paymentupdate');
		Route::any('/paymentstore', 'Admin\AdministerController@paymentstore')->name('admin.administator.paymentstore');
		Route::get('/paymentdelete/{id}','Admin\AdministerController@paymentdelete')->name('admin.administator.paymentdelete');
		
		Route::get('/receivedatatables','Admin\AdministerController@receivedatatables')->name('admin.administator.receivedatatables');
        Route::get('/paymentreceive','Admin\AdministerController@receiverequest')->name('admin.administator.receiverequest');
        Route::get('/monthlypayments', [App\Http\Controllers\Admin\AdministerController::class, 'monthlypayments'])->name('admin.administator.monthlypayments');
        Route::get('/package-upgrade-payments', [App\Http\Controllers\Admin\AdministerController::class,'packageUpgradePayments'])->name('admin.administator.packageUpgradePayments');
        Route::get('/product-payments', [App\Http\Controllers\Admin\AdministerController::class,'productPayments'])->name('admin.administator.productPayments');
        Route::get('/book-purchases-payments', [App\Http\Controllers\Admin\AdministerController::class,'bookPurchasePayments'])->name('admin.administator.bookPurchasePayments');
		Route::any('/receivecerate', 'Admin\AdministerController@receivecerate')->name('admin.administator.receivecerate');
		Route::any('/receiveedit/{id}', 'Admin\AdministerController@receiveedit')->name('admin.administator.receiveedit');
		Route::any('/receiveupdate/{id}', 'Admin\AdministerController@receiveupdate')->name('admin.administator.receiveupdate');
		Route::any('/receivestore', 'Admin\AdministerController@receivestore')->name('admin.administator.receivestore');
		Route::get('/receivedelete/{id}','Admin\AdministerController@receivedelete')->name('admin.administator.receivedelete');
		
		
		
        Route::get('/administator/create','Admin\AdministerController@create')->name('admin.administator.create');
        Route::post('/administator/store','Admin\AdministerController@store')->name('admin.administator.store');
        Route::get('/administator/edit/{id}','Admin\AdministerController@edit')->name('admin.administator.edit');
        Route::post('/administator/update/{id}','Admin\AdministerController@update')->name('admin.administator.update');
        Route::get('/administator/delete/{id}','Admin\AdministerController@delete')->name('admin.administator.delete');
    });

    //----------------Staff Management----------------
    //----------------Staff Salary Management----------------
    Route::get('/designations', [App\Http\Controllers\Admin\DesignationController::class, 'index'])->name('admin.designations.index');
    Route::post('/designations', [App\Http\Controllers\Admin\DesignationController::class, 'store'])->name('admin.designations.store');
    Route::post('/designations/update/{id}', [App\Http\Controllers\Admin\DesignationController::class, 'update'])->name('admin.designations.update');
    Route::get('/designations/delete/{id}', [App\Http\Controllers\Admin\DesignationController::class, 'destroy'])->name('admin.designations.delete');

    Route::get('/employees', [App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/employees/create', [App\Http\Controllers\Admin\EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/employees', [App\Http\Controllers\Admin\EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/employees/edit/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::post('/employees/update/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::get('/employees/delete/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'destroy'])->name('admin.employees.delete');

    Route::get('/advance-salaries', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'index'])->name('admin.advance-salaries.index');
    Route::get('/advance-salaries/create', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'create'])->name('admin.advance-salaries.create');
    Route::post('/advance-salaries', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'store'])->name('admin.advance-salaries.store');
    Route::get('/advance-salaries/delete/{id}', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'destroy'])->name('admin.advance-salaries.delete');
    Route::get('/advance-salaries/receipt/{id}', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'downloadReceipt'])->name('admin.advance-salaries.receipt');
    Route::post('/advance-salaries/approve/{id}', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'approve'])->name('admin.advance-salaries.approve');
    Route::post('/advance-salaries/reject/{id}', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'reject'])->name('admin.advance-salaries.reject');

    Route::get('/salaries', [App\Http\Controllers\Admin\SalaryController::class, 'index'])->name('admin.salaries.index');
    Route::post('/salaries', [App\Http\Controllers\Admin\SalaryController::class, 'store'])->name('admin.salaries.store');
    Route::get('/salaries/receipt/{id}', [App\Http\Controllers\Admin\SalaryController::class, 'downloadReceipt'])->name('admin.salaries.receipt');

    Route::get('/my-salaries', [App\Http\Controllers\Admin\SalaryController::class, 'mySalariesIndex'])->name('admin.my-salaries.index');
    Route::get('/my-advance-salaries', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'myAdvanceIndex'])->name('admin.my-advance-salaries.index');
    Route::get('/my-advance-salaries/create', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'myAdvanceCreate'])->name('admin.my-advance-salaries.create');
    Route::post('/my-advance-salaries', [App\Http\Controllers\Admin\AdvanceSalaryController::class, 'myAdvanceStore'])->name('admin.my-advance-salaries.store');




    Route::group(['middleware' => 'permissions:site_map'], function () {

    });

    Route::group(['middleware' => 'permissions:font_option'], function () {
        Route::get('/fonts/datatables','Admin\FontController@datatables')->name('admin.fonts.datatables');
        Route::get('/fonts','Admin\FontController@index')->name('fonts.index');
        Route::get('/fonts/status/{id}', 'Admin\FontController@status')->name('admin.fonts.status');
    });

    Route::group(['middleware' => 'permissions:cache_management'], function () {
        //----------------Cache Management-----------------
        Route::get('/cache','Admin\CacheController@clear')->name('admin.cache.clear');
        //----------------Cache Management-----------------
    });

    Route::get('/dashboard','Admin\DashboardController@index')->name('admin.dashboard');
    Route::get('/test-permissions', function() {
        if (!Auth::guard('admin')->check()) {
            return "Not logged in as admin!";
        }
        $user = Auth::guard('admin')->user();
        $sections = $user->role ? json_decode($user->role->section, true) : null;
        return [
            'admin_id' => $user->id,
            'admin_name' => $user->name,
            'role_id' => $user->role_id,
            'role_name' => $user->role ? $user->role->name : 'No Role',
            'role_sections_raw' => $user->role ? $user->role->section : 'No sections',
            'role_sections_decoded' => $sections,
            'sectionCheck_rashifall' => $user->sectionCheck('rashifall') ? 'true' : 'false',
            'sectionCheck_pages' => $user->sectionCheck('pages') ? 'true' : 'false'
        ];
    });
    
    Route::get('/admin/notifications/fetch','Admin\AdministerController@fetch')->name('admin.administator.notifications.fetch');
	
	 Route::get('/media-manager','Admin\MediaController@mediaManager')->name('admin.mediaManager');
	 Route::get('/get-files','Admin\MediaController@getfiles')->name('admin.getfile');
	 Route::post('/folders','Admin\MediaController@createFolder')->name('admin.createFolder');
	 Route::post('/upload','Admin\MediaController@upload')->name('admin.upload');
	 Route::delete('/files','Admin\MediaController@deleteFile')->name('admin.deleteFile');
	 Route::delete('/folders','Admin\MediaController@deleteFolder')->name('admin.deleteFolder');
	 Route::delete('/folders-recursive','Admin\MediaController@deleteFolderRecursive')->name('admin.deleteFolderRecursive');
	 Route::get('/media/file/{path?}','Admin\MediaController@serveFile')->where('path', '.*')->name('admin.media.file');
	


    Route::get('/profile', 'Admin\DashboardController@profile')->name('admin.profile');
    Route::post('/profile', 'Admin\DashboardController@profileUpdate')->name('admin.profile.update');
    Route::get('/password', 'Admin\DashboardController@passwordreset')->name('admin.password');
    Route::post('/password', 'Admin\DashboardController@changepass')->name('admin.password.update');

    Route::get('/check/movescript', 'Admin\DashboardController@movescript')->name('admin-move-script');
    Route::get('/generate/backup', 'Admin\DashboardController@generate_bkup')->name('admin-generate-backup');
    Route::get('/activation', 'Admin\DashboardController@activation')->name('admin-activation-form');
    Route::post('/activation', 'Admin\DashboardController@activation_submit')->name('admin-activate-purchase');
    Route::get('/clear/backup', 'Admin\DashboardController@clear_bkup')->name('admin-clear-backup');
    
    });
    
    Route::get('/logout','Admin\DashboardController@logout')->name('admin.logout');


});

Route::prefix('admin')->group(function() {
    Route::get('/books', 'Admin\BookController@index')->name('admin.books.index');
    Route::get('/books/create', 'Admin\BookController@create')->name('admin.books.create');
    Route::post('/books/store', 'Admin\BookController@store')->name('admin.books.store');
    Route::put('/books/{book}', 'Admin\BookController@update')->name('admin.books.update');
    Route::delete('/books/{book}', 'Admin\BookController@destroy')->name('admin.books.destroy');
    Route::get('/books/pdf/{file}', [BookController::class, 'viewPdf'])
    ->name('admin.books.pdf');
    
    Route::post('/books/purchase/{purchase}/approve', 'Admin\BookController@approvePurchase')->name('admin.books.purchase.approve');
    Route::post('/books/purchase/{purchase}/reject', 'Admin\BookController@rejectPurchase')->name('admin.books.purchase.reject');
    
    Route::get('/upgrade-requests', 'Admin\UpgradeRequestController@index')
        ->name('admin.upgrade.index');
    
    Route::post('/upgrade/{id}/approve', 'Admin\UpgradeRequestController@approve')->name('admin.upgrade.approve');

    Route::post('/upgrade/{id}/reject', 'Admin\UpgradeRequestController@reject')->name('admin.upgrade.reject');
    
    Route::delete('/upgrade-request/{id}', 'Admin\UpgradeRequestController@destroy')->name('admin.upgrade.delete');
    
   Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses.index');
    Route::post('/courses/store', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // MODULES
    Route::post('/modules/store', [CourseController::class, 'storeModule'])->name('admin.modules.store');
    Route::put('/modules/{module}', [CourseController::class, 'updateModule'])->name('admin.modules.update');
    Route::delete('/modules/{module}', [CourseController::class, 'deleteModule'])->name('admin.modules.delete');

    // EXAMS
    Route::post('/exams/store/{module}', [CourseController::class, 'storeExam'])->name('admin.exams.store');
    Route::delete('/exams/{exam}', [CourseController::class, 'deleteExam'])->name('admin.exams.delete');

    // QUESTIONS
    Route::post('/questions/store/{exam}', [CourseController::class, 'storeQuestion'])->name('admin.questions.store');
    Route::post('/questions/{question}', [CourseController::class, 'updateQuestion'])->name('admin.questions.update');
    Route::delete('/questions/{question}', [CourseController::class, 'deleteQuestion'])->name('admin.questions.delete');

});

Route::post('/books/{book}/purchase', [EpsPaymentController::class, 'bookPay'])
    ->middleware('auth')
    ->name('user.books.purchase');

Route::get('/courses/{course}', 'Admin\CourseController@show')
    ->name('user.courses.show');
    
Route::post('/courses/{course}/purchase', [EpsPaymentController::class, 'coursePay'])
    ->middleware('auth')
->name('user.courses.purchase');

Route::post('/courses/purchase/{purchase}/approve', 'Admin\CourseController@approveCoursePurchase')
    ->name('admin.courses.purchase.approve');

Route::post('/courses/purchase/{purchase}/reject', 'Admin\CourseController@rejectCoursePurchase')
    ->name('admin.courses.purchase.reject');

Route::get('reader/dashboard','User\DashboardController@reader_index')->name('reader.dashboard');

Route::post('reader/claim-offer', 'User\DashboardController@claim_offer')
    ->name('reader.claim_offer');
    
Route::post('reader/upgrade-request', 'User\DashboardController@upgradeRequest')
->name('reader.upgrade.request');

Route::any('user/reader-paymentcerate', 'User\DashboardController@reader_paymentcerate')->name('user.profile.reader_paymentcerate');
Route::get('user/reader-paymentrequest','User\DashboardController@reader_paymentrequest')->name('reader.paymentrequest');
Route::any('user/reader_paymentstore', 'User\DashboardController@reader_paymentstore')->name('user.profile.reader_paymentstore');

Route::post('user/reader-upgrade-instantly', 'User\DashboardController@upgradeInstantly')->name('user.profile.upgradeInstantly');

 Route::prefix('user')->group(function() {
        Route::get('/dashboard','User\DashboardController@index')->name('user.dashboard');
        Route::get('/reader-profile', 'User\DashboardController@reader_profile')->name('reader.profile');
        Route::get('/reader-password', 'User\DashboardController@reader_passwordreset')->name('reader.password');
        Route::get('/logout','User\DashboardController@logout')->name('user.logout');
    	Route::post('/profile', 'User\DashboardController@profileUpdate')->name('user.profile.update');
        Route::post('/password', 'User\DashboardController@changepass')->name('user.password.update');
        Route::get('/profile', 'User\DashboardController@profile')->name('user.profile');
        Route::get('/password', 'User\DashboardController@passwordreset')->name('user.password');
 });        
    	
            
Route::middleware(['auth', 'payment.active'])->group(function () {
    Route::prefix('user')->group(function() {
        Route::middleware('approve')->group(function () {
        	Route::get('/reporter','User\DashboardController@reporter')->name('user.reporter');
        	
        	Route::get('/paymentrequest','User\DashboardController@paymentrequest')->name('user.profile.paymentrequest');
	
        	Route::any('/paymentcerate', 'User\DashboardController@paymentcerate')->name('user.profile.paymentcerate');
	
        	Route::any('/paymentedit/{id}', 'User\DashboardController@paymentedit')->name('user.profile.paymentedit');
        	Route::any('/paymentupdate/{id}', 'User\DashboardController@paymentupdate')->name('user.profile.paymentupdate');
        	Route::any('/paymentstore', 'User\DashboardController@paymentstore')->name('user.profile.paymentstore');

        	Route::any('/pincode', 'User\DashboardController@pincode')->name('user.profile.pincode');
        	Route::get('/paymentdelete/{id}','User\DashboardController@paymentdelete')->name('user.profile.paymentdelete');
        	
        	Route::get('/receivedatatables','User\DashboardController@receivedatatables')->name('user.profile.receivedatatables');
        	Route::get('/payment','User\DashboardController@receiverequest')->name('user.profile.receiverequest');
        	Route::any('/receivecerate', 'User\DashboardController@receivecerate')->name('user.profile.receivecerate');
        	Route::any('/receiveedit/{id}', 'User\DashboardController@receiveedit')->name('user.profile.receiveedit');
        	Route::any('/receiveupdate/{id}', 'User\DashboardController@receiveupdate')->name('user.profile.receiveupdate');
        	Route::any('/receivestore', 'User\DashboardController@receivestore')->name('user.profile.receivestore');
        	
     
        	
        	Route::get('/idcard/{id?}', 'User\DashboardController@idcard')->name('user.idcard');
        	Route::get('/applicationform/{id?}', 'User\DashboardController@applicationform')->name('user.applicationform');
        	

            
        	
        	Route::get('/user/datatables','User\DashboardController@datatables')->name('user.user.datatables');
        	
        	Route::get('/user/paymentdatatables','User\DashboardController@paymentdatatables')->name('user.user.paymentdatatables');
        
            Route::get('/add-post', function(){
                return view('user.post.format');
            })->name('user.post.format');
        
        
            //-------------Post Article area--------------------
            Route::get('/add-article','User\ArticleController@create')->name('user.article.create');
            Route::post('/add-article','User\ArticleController@store')->name('user.article.store');
            Route::post('/article/update/{id}','User\ArticleController@update')->name('user.article.update');
            Route::get('/add-article/subcategory/{id}','User\ArticleController@subcategory')->name('user.article.subcategory');
            Route::get('/edit-article/subcategoryUpdate/{x}/{y}','User\ArticleController@subcategoryUpdate')->name('user.article.subcategoryUpdate');
            //-------------slug create-------------------------
            Route::get('/add-article/slugCreate/','User\ArticleController@slugCreate')->name('user.article.slugCreate');
            //-------------slug check--------------------------
            Route::get('/add-article/slugCheck/','User\ArticleController@slugCheck')->name('user.article.slugCheck');
            //-------------category by language----------------
            Route::get('/add-article/languageOnUpdate/{x}/{y}','User\ArticleController@languageOnUpdate')->name('user.article.languageOnUpdate');
            Route::get('/add-article/language/{id}','User\ArticleController@language')->name('user.article.language');
            //-------------Post Article area end----------------
        
        
            Route::get('/gallery/show', 'User\GalleryController@show')->name('user.gallery.show');
            Route::post('/gallery/store', 'User\GalleryController@store')->name('user.gallery.store');
            Route::get('/gallery/delete', 'User\GalleryController@destroy')->name('user.gallery.delete');
        
            //-------------Post Audio area-----------
            Route::get('/add-audio','User\AudioController@create')->name('user.audio.create');
            Route::post('/add-audio','User\AudioController@store')->name('user.audio.store');
            Route::post('/audio/update/{id}','User\AudioController@update')->name('user.audio.update');
            Route::get('/add-audio/subcategory/{id}','User\AudioController@subcategory')->name('user.audio.subcategory');
            Route::get('/edit-audio/subcategoryUpdate/{x}/{y}','User\AudioController@subcategoryUpdate')->name('user.audio.subcategoryUpdate');
            //------------slug create----------------
            Route::get('/add-audio/slugCreate/','User\AudioController@slugCreate')->name('user.audio.slugCreate');
            //------------slug check----------------
            Route::get('/add-audio/slugCheck/','User\AudioController@slugCheck')->name('user.audio.slugCheck');
            //------------category by language---------
            Route::get('/add-audio/language/{id}','User\AudioController@language')->name('user.audio.language');
            Route::get('/add-audio/languageOnUpdate/{x}/{y}','User\AudioController@languageOnUpdate')->name('user.audio.languageOnUpdate');
            //-------------Post Audio area end-----------
        
        
            //-------------Post Video area------------
            Route::get('/add-video','User\VideoController@create')->name('user.video.create');
            Route::post('/add-video','User\VideoController@store')->name('user.video.store');
            Route::post('/video/update/{id}','User\VideoController@update')->name('user.video.update');
            Route::get('/add-video/subcategory/{id}','User\VideoController@subcategory')->name('user.video.subcategory');
            Route::get('/edit-video/subcategoryUpdate/{x}/{y}','User\VideoController@subcategoryUpdate')->name('user.video.subcategoryUpdate');
            //--------------slug create---------------
            Route::get('/add-video/slugCreate/','User\VideoController@slugCreate')->name('user.video.slugCreate');
            //---------------slug check---------------
            Route::get('/add-video/slugCheck/','User\VideoController@slugCheck')->name('user.video.slugCheck');
            //---------------category by language-------
            Route::get('/add-video/language/{id}','User\VideoController@language')->name('user.video.language');
            Route::get('/add-video/languageOnUpdate/{x}/{y}','User\VideoController@languageOnUpdate')->name('user.video.languageOnUpdate');
            //-------------Post Video area------------
        
        
            //-------------Post Trivia Quiz Area----------------
            Route::get('/add-tquiz', 'User\TQuizController@create')->name('user.tquiz.create');
            Route::post('/add-tquiz/submit', 'User\TQuizController@store')->name('user.tquiz.store');
            Route::post('/add-tquiz/update/{id}', 'User\TQuizController@update')->name('user.tquiz.update');
            Route::get('/remove-tquizquestion/{id}','User\TQuizController@removequestion')->name('user.tquiz.removequestion');
            Route::get('/remove-tquizanswer/{id}','User\TQuizController@removeanswer')->name('user.tquiz.removeanswer');
            Route::get('/remove-tquizresult/{id}','User\TQuizController@removeresult')->name('user.tquiz.removeresult');
            //-------------Post Trivia Quiz Area----------------
        
        
            //-------------Post Personality Quiz Area--------------
            Route::get('/add-pquiz', 'User\PQuizController@create')->name('user.pquiz.create');
            Route::post('/add-pquiz', 'User\PQuizController@store')->name('user.pquiz.store');
            Route::post('/add-pquiz/update/{id}','User\PQuizController@update')->name('user.pquiz.update');
            Route::get('/remove-pquizanswer/{id}','User\PQuizController@removeanswer')->name('user.pquiz.removeanswer');
            Route::get('/remove-pquizresult/{id}','User\PQuizController@removepresult')->name('user.pquiz.removeresult');
            Route::get('/remove-pquizquestion/{id}','User\PQuizController@removepquestion')->name('user.pquiz.removepquestion');
            //-------------Post Personality Quiz Area--------------
        
        
            //------------Post Shorted List Area-------------------
            Route::get('/add-shortlist','User\ShortListController@create')->name('user.shortlist.create');
            Route::post('/add-shortlist','User\ShortListController@store')->name('user.shortlist.store');
            Route::post('/add-shortlist/update/{id}','User\ShortListController@update')->name('user.shortlist.update');
            Route::get('/remove-shortlist/{id}','User\ShortListController@remove')->name('user.shortlist.remove');
            //------------Post Shorted List Area-------------------
        
            //-------------Slider Post Area------------
            Route::get('/post/datatables','User\PostController@datatables')->name('user.post.datatables');
            Route::get('/post','User\PostController@index')->name('user.post.index');
            Route::get('/post/edit/{id}','User\PostController@edit')->name('user.post.edit');
            Route::get('/post/view/{id}','User\PostController@view')->name('user.post.view');
            Route::get('/post/delete/{id}','User\PostController@delete')->name('user.post.delete');
        	
        	
        	Route::get('/breaking/datatables','User\BreakingController@datatables')->name('user.breaking.datatables');
            Route::get('/breaking','User\BreakingController@index')->name('user.breaking.index');
        
        	Route::get('/feature/datatables','User\FeaturedController@datatables')->name('user.feature.datatables');
        	Route::get('/feature','User\FeaturedController@index')->name('user.feature.index');
        
        	
        	Route::get('/pending/datatables','User\PendingController@datatables')->name('user.pending.datatables');
        	Route::get('/pending','User\PendingController@index')->name('user.pending.index');
        	
        	
        	Route::get('/category-filter/language/{id}','User\PostController@categoryFilter')->name('user.post.categoryFilter.language');
        	
        
            //-------------Slider Post Area------------
        
        
            //------------Schedule Post Area----------
            Route::get('/schedule/datatables','User\ScheduleController@datatables')->name('user.schedule.datatables');
            Route::get('/schedule','User\ScheduleController@index')->name('user.schedule.index');
            //------------Schedule Post Area----------
        
        
            //-----------Draft Post Area------------
            Route::get('/draft/datatables','User\DraftController@datatables')->name('user.draft.datatables');
            Route::get('/draft','User\DraftController@index')->name('user.draft.index');
            Route::get('/draft/article/approve','User\DraftController@draftArticle')->name('user.draft.article');
            Route::get('/draft/audio/approve','User\DraftController@draftAudio')->name('user.draft.audio');
            Route::get('/draft/video/approve','User\DraftController@draftVideo')->name('user.draft.video');
            //-----------Draft Post Area end------------
        
            //----------------Cache Management-----------------
            Route::get('/cache','User\CacheController@clear')->name('user.cache.clear');
        
        });
        //----------------Cache Management-----------------
    });
});


//Route::get('/{id?}','Front\FrontendController@index')->name('frontend.index');
Route::get('/','Front\FrontendController@index')->name('frontend.index');
Route::get('/change/language/{id?}','Front\FrontendController@language')->name('frontend.language');
Route::get('/prayer/auto','Front\FrontendController@fetchSalat')->name('frontend.fetchSalat');


Route::get('/ttsstream','Front\FrontendController@ttsstream')->name('frontend.ttsstream');

Route::get('/rashifall/{date}/{id}/{type}/{slug?}','Front\FrontendController@rashifall')->name('frontend.rashifall');

Route::get('/rcron','Front\FrontendController@rcron')->name('frontend.rcron');

Route::get('/photo/category/{album?}','Front\FrontendController@photoalbumdetails')->name('frontend.photoalbumdetails');

Route::get('/photo/{category?}','Front\FrontendController@photoalbum')->name('frontend.photoalbum');

Route::get('/video/{category?}','Front\FrontendController@video')->name('frontend.video');


Route::get('/ourteam/{id?}/{type?}','Front\FrontendController@ourteam')->name('frontend.ourteam');

Route::get('/video/details/{category?}','Front\FrontendController@videodetails')->name('frontend.videodetails');

Route::any('/news/bangladesh/{division?}/{district?}/{upazila?}','Front\FrontendController@allbangladesh')->name('frontend.bangladesh');
Route::any('/news/archive','Front\FrontendController@newsArchive')->name('front.news_archive');

Route::any('/news-sections/fetch','Front\FrontendController@fetchNews')->name('news.sections.fetch');
Route::any('/news-division/fetch','Front\FrontendController@fetchDivisionNews')->name('news.division.fetch');

Route::get('/tag/{search}','Front\FrontendController@searchByTag')->name('tag.search');
Route::get('/{category}/{slug}/{print?}','Front\FrontendController@details')->name('frontend.postBySubcategory.details');

Route::post('the/genius/ocean/2441139', 'Front\FrontendController@subscription');
Route::get('finalize', 'Front\FrontendController@finalize');

Route::post('/news/like', 'Front\FrontendController@like')->name('news.like');
Route::post('/subcribe','Front\FrontendController@subcribe')->name('front.subcribe');




Route::post('/subscribers','Front\SubscriberController@store')->name('front.subscribers.store');
Route::get('/load-more','Front\FrontendController@loadMore')->name('frontend.loadMore');
Route::get('/gallery-view/{id}','Front\GalleryController@view')->name('gallery.view');
Route::get('/all-poll','Front\FrontendController@allPoll')->name('front.allPoll');
Route::get('/all-poll-result','Front\FrontendController@allPollResult')->name('front.allPollResult');
Route::get('/news-search','Front\FrontendController@newsSearch')->name('front.news_search');


Route::get('/newspopularyesterdday','Front\FrontendController@newspopularyesterdday')->name('front.newspopularyesterdday');

Route::get('/profile/{admin}','Front\FrontendController@authorProfile')->name('front.authorProfile');
Route::get('/follower','Front\FrontendController@follower')->name('front.follower');


Route::get('/follower/create/{id}','Front\FollowController@followerCreate')->name('front.followerCreate');
Route::get('/follower/{admin}','Front\FollowController@following')->name('front.following');

Route::get('/contact/refresh_code','Front\FrontendController@refresh_code');
Route::get('/log-reg','Front\RegisterController@LogReg')->name('front.LogReg');

Route::get('/login','Front\RegisterController@login')->name('front.login.view');



Route::get('/register', 'Front\RegisterController@selectRegistrationType')->name('front.register.select');

Route::get('/registration','Front\RegisterController@registration')->name('front.registration');

Route::post('/register/send-otp', 'Front\RegisterController@sendOtp')
    ->name('register.sendOtp');

Route::post('/register/verify-otp',  'Front\RegisterController@verifyOtp')
    ->name('register.verifyOtp');

Route::get('/register/reader', 'Front\RegisterController@registerReader')->name('front.register_reader');


Route::post('/get-divisions','Front\RegisterController@getDivisions')->name('front.getDivisions');
Route::post('/get-districts','Front\RegisterController@getDistricts')->name('front.getDistricts');
Route::post('/get-thanas','Front\RegisterController@getThanas')->name('front.getThanas');;
Route::post('/get-unions','Front\RegisterController@getUnions')->name('front.getUnions');;


Route::post('/register','Front\RegisterController@register')->name('front.register');


Route::get('/register-reader', 'User\ReaderController@registerReader')->name('register_reader');

Route::post('/verify-otp', 'User\ReaderController@verifyOtp')->name('reader.verifyOtp');

Route::post('/register/reader','User\ReaderController@register')->name('reader.register');
Route::post('/send-otp', 'Front\RegisterController@sendOtp')->name('reporter.send.otp');
Route::post('/reporter/verify-otp', 'Front\RegisterController@verifyOtp')
    ->name('reporter.verifyOtp');

Route::get('/register/verify/{token}', 'Front\RegisterController@token')->name('user.register.token');
Route::post('/login','Front\LoginController@login')->name('front.login');
Route::get('/logout','Front\LoginController@logout')->name('front.logout');

Route::get('/forgot', 'Front\ForgotController@showforgotform')->name('front.forgot');
Route::post('/forgot', 'Front\ForgotController@forgot')->name('front.forgot.submit');

Route::post('/forgot/reset', 'Front\ForgotController@resetPassword')
    ->name('front.forgot.reset');


Route::get('auth/{provider}', 'Front\SocialRegisterController@redirectToProvider')->name('social.provider');
Route::get('auth/{provider}/callback', 'Front\SocialRegisterController@handleProviderCallback');

Route::get('/{category}','Front\FrontendController@category')->name('frontend.category');

Route::get('/click/count/{id}','Front\FrontendController@clickCount')->name('frontend.click.count');
Route::get('related/informations/ab/{slug}', [FrontendController::class, 'dynamicPage'])
    ->name('dynamic.page');
Route::get('/change/language/{id}','Front\FrontendController@language')->name('front.language');
Route::get('/news/date/post-by-date','Front\FrontendController@postByDate')->name('frontend.postByDate');
Route::get('rss-feed/cronJob/Update','Front\FrontendController@cronJobUpdate')->name('rss.cronJobUpdate');



