<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\NewsletterBannerDetails;
use App\Models\FooterDetails;
use App\Models\NewsMedia;
use App\Models\NewsMediaBannerDetails;

class NewsletterController extends Controller
{
    public function articles()
    {
        $banner = NewsletterBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $all = Article::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Group by year: numeric years descending, then non-numeric (e.g. Archive) last
        $groups  = $all->groupBy(fn ($a) => trim((string) $a->year) !== '' ? $a->year : 'Archive');
        $numeric = $groups->keys()->filter(fn ($y) => is_numeric($y))->sortDesc()->values();
        $others  = $groups->keys()->reject(fn ($y) => is_numeric($y))->values();

        $blocks = [];
        foreach ($numeric->merge($others) as $y) {
            $blocks[$y] = $groups[$y];
        }

        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.newsletter.articles', compact('banner', 'blocks', 'footer'));
    }

    /** Newsletter > News & Media listing. */
    public function newsMedia()
    {
        $banner = NewsMediaBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $items = NewsMedia::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.newsletter.news-media', compact('banner', 'items', 'footer'));
    }
}
