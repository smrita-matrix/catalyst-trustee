<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CompanyOverviewBannerDetails;
use App\Models\CompanyOverviewIntroductionDetails;
use App\Models\CompanyOverviewVisionMissionDetails;
use App\Models\FooterDetails;
use App\Models\LeadershipBannerDetails;
use App\Models\LeadershipContentDetails;
use App\Models\GroupCompaniesBannerDetails;
use App\Models\GroupCompaniesDifcDetails;
use App\Models\GroupCompaniesOverviewDetails;
use App\Models\OurJourneyBannerDetails;
use App\Models\OurJourneyMilestoneDetails;


class AboutUsController extends Controller
{
    public function company_overview()
    {
        $banner        = CompanyOverviewBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $introduction  = CompanyOverviewIntroductionDetails::whereNull('deleted_at')->latest('id')->first();
        $visionMission = CompanyOverviewVisionMissionDetails::whereNull('deleted_at')->latest('id')->first();
        $footer        = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.Aboutus.company-overview', compact(
            'banner',
            'introduction',
            'visionMission',
            'footer'
        ));
    }

    public function leadership()
    {
        $banner  = LeadershipBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $content = LeadershipContentDetails::whereNull('deleted_at')->latest('id')->first();
        $footer  = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.Aboutus.leadership', compact(
            'banner',
            'content',
            'footer'
        ));
    }


    public function group_companies()
    {
        $banner   = GroupCompaniesBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $overview = GroupCompaniesOverviewDetails::whereNull('deleted_at')->latest('id')->first();
        $difc     = GroupCompaniesDifcDetails::whereNull('deleted_at')->latest('id')->first();
        $footer   = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.Aboutus.group_companies', compact(
            'banner',
            'overview',
            'difc',
            'footer'
        ));
    }

    public function our_journey()
    {
        $banner     = OurJourneyBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $milestones = OurJourneyMilestoneDetails::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('year', 'asc')
            ->get();
        $footer     = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.Aboutus.our_journey', compact(
            'banner',
            'milestones',
            'footer'
        ));
    }

}