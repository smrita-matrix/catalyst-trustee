<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Http;


// Backend controller
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\home\BannerDetailsController;
use App\Http\Controllers\Backend\home\MarqueeInnerDetailsController;
use App\Http\Controllers\Backend\home\AboutCatalystDetailsController;
use App\Http\Controllers\Backend\home\WhyChooseDetailsController;
use App\Http\Controllers\Backend\home\SebiServiceDetailsController;
use App\Http\Controllers\Backend\home\NonSebiServiceDetailsController;
use App\Http\Controllers\Backend\home\GiftCityDetailsController;
use App\Http\Controllers\Backend\home\LeadershipDetailsController;
use App\Http\Controllers\Backend\home\BusinessPerformanceDetailsController;
use App\Http\Controllers\Backend\home\LandmarkDetailsController;
use App\Http\Controllers\Backend\home\ProofsDetailsController;
use App\Http\Controllers\Backend\home\CtaDetailsController;
use App\Http\Controllers\Backend\home\FooterDetailsController;
// About Us controllers
use App\Http\Controllers\Backend\about\CompanyOverviewBannerDetailsController;
use App\Http\Controllers\Backend\about\CompanyOverviewIntroductionDetailsController;
use App\Http\Controllers\Backend\about\CompanyOverviewVisionMissionDetailsController;
use App\Http\Controllers\Backend\about\LeadershipBannerDetailsController;
use App\Http\Controllers\Backend\about\LeadershipContentDetailsController;
use App\Http\Controllers\Backend\about\GroupCompaniesBannerDetailsController;
use App\Http\Controllers\Backend\about\GroupCompaniesOverviewDetailsController;
use App\Http\Controllers\Backend\about\GroupCompaniesDifcDetailsController;
use App\Http\Controllers\Backend\about\OurJourneyBannerDetailsController;
use App\Http\Controllers\Backend\about\OurJourneyMilestoneDetailsController;


// Services Sebi controllers
use App\Http\Controllers\Backend\services\ServicesSebiDetailsController;
use App\Http\Controllers\Backend\services\ServiceCategoryController;
use App\Http\Controllers\Backend\services\ProductCategoryController;
use App\Http\Controllers\Backend\services\ServiceLayout3Controller;
use App\Http\Controllers\Backend\services\ServiceLayout2Controller;
use App\Http\Controllers\Backend\services\ServiceFifController;

//Frontend controller
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AboutUsController;
use App\Http\Controllers\Frontend\ServicesSebiController;


// Backend
Route::get('/admin-login', [LoginController::class, 'login'])->name('admin.login');
Route::post('/admin-login', [LoginController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin-logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/change-password', [LoginController::class, 'change_password'])->name('admin.changepassword');
Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('admin.updatepassword');
Route::get('/admin-register', [LoginController::class, 'register'])->name('admin.register');
Route::post('/register', [LoginController::class, 'authenticate_register'])->name('admin.register.authenticate');


// Routes with Middleware
Route::group(['middleware' => ['auth:web', \App\Http\Middleware\PreventBackHistoryMiddleware::class]], function () {
    Route::get('/dashboard', function () {
            return view('backend.dashboard'); 
        })->name('admin.dashboard');
});



//home pages 
Route::resource('banner-details', BannerDetailsController::class);
Route::resource('marquee-inner', MarqueeInnerDetailsController::class);
Route::resource('about-catalyst-details', AboutCatalystDetailsController::class);
Route::resource('why-choose-details', WhyChooseDetailsController::class);
Route::resource('sebi-service-details', SebiServiceDetailsController::class);
Route::resource('non-sebi-service-details', NonSebiServiceDetailsController::class);
Route::resource('gift-city-details', GiftCityDetailsController::class);
Route::resource('leadership-details', LeadershipDetailsController::class);
Route::resource('business-performance-details', BusinessPerformanceDetailsController::class);
Route::resource('landmark-details', LandmarkDetailsController::class);
Route::resource('proofs-details', ProofsDetailsController::class);
Route::resource('cta-details', CtaDetailsController::class);
Route::resource('footer-details', FooterDetailsController::class);


//Aboutus pages 
Route::resource('company-overview-banner-details', CompanyOverviewBannerDetailsController::class);
Route::resource('company-overview-introduction-details', CompanyOverviewIntroductionDetailsController::class)
    ->parameters(['company-overview-introduction-details' => 'introduction']);
Route::resource('company-overview-vision-mission-details', CompanyOverviewVisionMissionDetailsController::class)
    ->parameters(['company-overview-vision-mission-details' => 'visionMission']);
Route::resource('leadership-banner-details', LeadershipBannerDetailsController::class);
Route::resource('leadership-content-details', LeadershipContentDetailsController::class)
    ->parameters(['leadership-content-details' => 'content']);
Route::resource('group-companies-banner-details', GroupCompaniesBannerDetailsController::class)
    ->parameters(['group-companies-banner-details' => 'banner']);
Route::resource('group-companies-overview-details', GroupCompaniesOverviewDetailsController::class)
    ->parameters(['group-companies-overview-details' => 'overview']);
Route::resource('group-companies-difc-details', GroupCompaniesDifcDetailsController::class)
    ->parameters(['group-companies-difc-details' => 'difc']);
Route::resource('our-journey-banner-details', OurJourneyBannerDetailsController::class);
Route::resource('our-journey-milestone-details', OurJourneyMilestoneDetailsController::class)
    ->parameters(['our-journey-milestone-details' => 'milestone']);


//Services
Route::resource('service-category', ServiceCategoryController::class)
    ->parameters(['service-category' => 'category']);
Route::resource('product-category', ProductCategoryController::class)
    ->parameters(['product-category' => 'product']);
Route::get('product-page/{product}/open', [ProductCategoryController::class, 'editPage'])->name('product-page.open');
Route::get('product-services', [ProductCategoryController::class, 'services'])->name('product-services.index');
Route::get('layout-guide', [ProductCategoryController::class, 'layoutGuide'])->name('layout-guide');
Route::get('service-layout1/{product}/edit', [ServicesSebiDetailsController::class, 'edit'])->name('service-layout1.edit');
Route::match(['put', 'post'], 'service-layout1/{product}', [ServicesSebiDetailsController::class, 'update'])->name('service-layout1.update');
// Per-product page editors (one per layout)
Route::get('service-layout3/{product}/edit', [ServiceLayout3Controller::class, 'edit'])->name('service-layout3.edit');
Route::match(['put', 'post'], 'service-layout3/{product}', [ServiceLayout3Controller::class, 'update'])->name('service-layout3.update');
Route::get('service-layout2/{product}/edit', [ServiceLayout2Controller::class, 'edit'])->name('service-layout2.edit');
Route::match(['put', 'post'], 'service-layout2/{product}', [ServiceLayout2Controller::class, 'update'])->name('service-layout2.update');
Route::get('service-fif/{product}/edit', [ServiceFifController::class, 'edit'])->name('service-fif.edit');
Route::match(['put', 'post'], 'service-fif/{product}', [ServiceFifController::class, 'update'])->name('service-fif.update');


//Frontend Pages
Route::get('/', [HomeController::class, 'home'])->name('frontend.index');
Route::get('/company-overview', [AboutUsController::class, 'company_overview'])->name('frontend.company_overview');
Route::get('/leadership', [AboutUsController::class, 'leadership'])->name('frontend.leadership');
Route::get('/group-companies', [AboutUsController::class, 'group_companies'])->name('frontend.group_companies');
Route::get('/our-journey', [AboutUsController::class, 'our_journey'])->name('frontend.our_journey');
Route::get('/debenture-trustee-listed', [ServicesSebiController::class, 'debenture_trustee_listed'])->name('frontend.debenture_trustee_listed');
Route::get('/services/{slug}', [ServicesSebiController::class, 'show'])->name('frontend.product_page');

