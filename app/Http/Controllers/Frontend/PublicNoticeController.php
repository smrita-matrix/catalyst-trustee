<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeBannerDetails;
use App\Models\FooterDetails;

class PublicNoticeController extends Controller
{
    public function notices()
    {
        $all = Notice::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Breach of Minimum Security Cover — grouped by period (date pill)
        $bomsc = $all->where('section', 'bomsc')->groupBy(function ($n) {
            return trim((string) $n->period);
        });

        // Breach of Covenants — grouped by period (collapsible month)
        $boc = $all->where('section', 'boc')->groupBy(function ($n) {
            return trim((string) $n->period);
        });

        // Auction Notices — flat list
        $auc = $all->where('section', 'auc')->values();

        $banner = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.public-notice.notices', compact('banner', 'bomsc', 'boc', 'auc', 'footer'));
    }
}
