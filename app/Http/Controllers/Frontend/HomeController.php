<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BannerDetails;
use App\Models\AboutCatalystDetails;
use App\Models\BusinessPerformanceDetails;
use App\Models\CtaDetails;
use App\Models\FooterDetails;
use App\Models\GiftCityDetails;
use App\Models\LandmarkDetails;
use App\Models\LeadershipDetails;
use App\Models\MarqueeInnerDetails;
use App\Models\NonSebiServiceDetails;
use App\Models\ProofsDetails;
use App\Models\SebiServiceDetails;
use App\Models\WhyChooseDetails;



class HomeController extends Controller
{

  public function home()
{
    // Banner slider (multiple)
    $banners = BannerDetails::whereNull('deleted_at')->orderBy('id')->get();

    // Marquee ticker items (multiple)
    $marquee = MarqueeInnerDetails::whereNull('deleted_at')->orderBy('id')->get();

    // Single-record sections (latest active row)
    $about               = AboutCatalystDetails::whereNull('deleted_at')->latest('id')->first();
    $whyChoose           = WhyChooseDetails::whereNull('deleted_at')->latest('id')->first();
    $sebi                = SebiServiceDetails::whereNull('deleted_at')->latest('id')->first();
    $nonSebi             = NonSebiServiceDetails::whereNull('deleted_at')->latest('id')->first();
    $giftCity            = GiftCityDetails::whereNull('deleted_at')->latest('id')->first();
    $leadership          = LeadershipDetails::whereNull('deleted_at')->latest('id')->first();
    $businessPerformance = BusinessPerformanceDetails::whereNull('deleted_at')->latest('id')->first();
    $landmark            = LandmarkDetails::whereNull('deleted_at')->latest('id')->first();
    $proofs              = ProofsDetails::whereNull('deleted_at')->latest('id')->first();
    $cta                 = CtaDetails::whereNull('deleted_at')->latest('id')->first();
    $footer              = FooterDetails::whereNull('deleted_at')->latest('id')->first();

    return view('frontend.welcome', compact(
        'banners',
        'marquee',
        'about',
        'whyChoose',
        'sebi',
        'nonSebi',
        'giftCity',
        'leadership',
        'businessPerformance',
        'landmark',
        'proofs',
        'cta',
        'footer'
    ));
}


}