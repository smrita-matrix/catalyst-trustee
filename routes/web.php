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


//Frontend controller
use App\Http\Controllers\Frontend\HomeController;


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


//Frontend Pages
Route::get('/', [HomeController::class, 'home'])->name('frontend.index');

