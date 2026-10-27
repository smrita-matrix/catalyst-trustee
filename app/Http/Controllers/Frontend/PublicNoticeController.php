<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeCategory;
use App\Models\NoticeBannerDetails;
use App\Models\FooterDetails;

class PublicNoticeController extends Controller
{
    /**
     * Any Public Notice page, rendered from its category's chosen layout.
     * Adding a page is an admin action now — no route or view change needed.
     */
    public function show($slug)
    {
        $category = NoticeCategory::live()
            ->where('slug', $slug)
            ->where('link_type', 'page')
            ->firstOrFail();

        $notices = $category->notices()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Layouts that show collapsible / pill groups read this instead of $notices.
        $grouped = $notices->groupBy(fn ($n) => trim((string) $n->period));

        $banner = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.public-notice.page', compact('category', 'notices', 'grouped', 'banner', 'footer'));
    }

    /**
     * A notice's own page: its banner, then its documents under their headings.
     * Only reachable while the notice and the page it sits on are both live.
     */
    public function detail($slug, $detail)
    {
        $category = NoticeCategory::live()
            ->where('slug', $slug)
            ->where('link_type', 'page')
            ->firstOrFail();

        $notice = $category->notices()
            ->where('status', 1)
            ->where('has_detail_page', 1)
            ->where('detail_slug', $detail)
            ->firstOrFail();

        return $this->detailView($category, $notice);
    }

    /** A page inside a notice's page, e.g. DHFL > DHFL IBC. */
    public function subPage($slug, $detail, $sub)
    {
        $category = NoticeCategory::live()
            ->where('slug', $slug)
            ->where('link_type', 'page')
            ->firstOrFail();

        $parent = $category->notices()
            ->where('status', 1)
            ->where('has_detail_page', 1)
            ->where('detail_slug', $detail)
            ->firstOrFail();

        $notice = $parent->subPages()
            ->where('status', 1)
            ->where('has_detail_page', 1)
            ->where('detail_slug', $sub)
            ->firstOrFail();

        return $this->detailView($category, $notice, $parent);
    }

    private function detailView(NoticeCategory $category, Notice $notice, ?Notice $parent = null)
    {
        $items = $notice->detailItems()->where('status', 1)->get();

        // Consecutive documents under the same heading form one block, so a
        // stand-alone document between two groups keeps its place.
        $blocks = [];
        foreach ($items as $item) {
            $heading = $item->is_highlight ? null : (trim((string) $item->group_title) ?: null);
            $last = end($blocks);

            if ($last && !$item->is_highlight && !$last['highlight'] && $last['heading'] === $heading) {
                $blocks[key($blocks)]['items'][] = $item;
            } else {
                $blocks[] = ['heading' => $heading, 'highlight' => (bool) $item->is_highlight, 'items' => [$item]];
            }
        }

        $banner = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();
        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.public-notice.detail', compact('category', 'notice', 'parent', 'blocks', 'banner', 'footer'));
    }

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
