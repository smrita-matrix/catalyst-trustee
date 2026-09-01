<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CareerOpening;
use App\Models\NewsMedia;
use App\Models\Notice;
use App\Models\NoticeCategory;
use App\Models\PolicyPage;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Site-wide search.
 *
 * Looks across the fixed pages and every content type the admin manages —
 * services, public notice pages and their documents, job openings, articles
 * and news. Results are shown in the header modal; there is no separate
 * search page.
 */
class SearchController extends Controller
{
    /** Pages that are not database-driven, matched on their own titles. */
    private function staticPages(): array
    {
        return [
            ['title' => 'Company Overview',   'route' => 'frontend.company_overview', 'group' => 'About Us'],
            ['title' => 'Our Leadership',     'route' => 'frontend.leadership',       'group' => 'About Us'],
            ['title' => 'Group Companies',    'route' => 'frontend.group_companies',  'group' => 'About Us'],
            ['title' => 'Our Journey',        'route' => 'frontend.our_journey',      'group' => 'About Us'],
            ['title' => 'Careers',            'route' => 'frontend.careers',          'group' => 'Careers'],
            ['title' => 'Investor Grievance', 'route' => 'frontend.investor_grievance', 'group' => 'Grievance'],
            ['title' => 'Contact Us',         'route' => 'frontend.contact',          'group' => 'Contact'],
            ['title' => 'Articles',           'route' => 'frontend.articles',         'group' => 'Newsletter'],
            ['title' => 'News & Media',       'route' => 'frontend.news_media',       'group' => 'Newsletter'],
        ];
    }

    /**
     * Live results for the header search modal.
     *
     * Returns a flat, ranked list rather than the grouped page view, so the
     * dropdown can show the closest matches first as the visitor types.
     */
    public function suggest(Request $request)
    {
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['term' => $term, 'results' => []]);
        }

        $rows = [];

        foreach ($this->collect(mb_substr($term, 0, 120)) as $group => $items) {
            foreach ($items as $item) {
                $rows[] = [
                    'title' => $item['title'],
                    'group' => $group,
                    'url'   => $item['url'],
                ];
            }
        }

        // A title that starts with what was typed is the most likely target.
        $needle = mb_strtolower($term);
        usort($rows, function ($a, $b) use ($needle) {
            $rank = function ($row) use ($needle) {
                $title = mb_strtolower($row['title']);
                if ($title === $needle) { return 0; }
                if (str_starts_with($title, $needle)) { return 1; }
                if (str_contains($title, $needle)) { return 2; }
                return 3;
            };

            return [$rank($a), mb_strlen($a['title'])] <=> [$rank($b), mb_strlen($b['title'])];
        });

        return response()->json([
            'term'    => $term,
            'total'   => count($rows),
            'results' => array_slice($rows, 0, 30),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Sources                                                            */
    /* ------------------------------------------------------------------ */

    private function collect(string $term): array
    {
        $groups = [];

        foreach ($this->matchStaticPages($term) as $row) {
            $groups[$row['group']][] = $row;
        }

        foreach ($this->matchServices($term) as $row) {
            // Labelled with its own group, so two services of the same name in
            // different groups can be told apart in the results.
            $groups[$row['group']][] = $row;
        }

        foreach ($this->matchNoticePages($term) as $row) {
            $groups['Public Notice'][] = $row;
        }

        foreach ($this->matchNoticeDocuments($term) as $row) {
            $groups['Public Notice Documents'][] = $row;
        }

        foreach ($this->matchOpenings($term) as $row) {
            $groups['Careers'][] = $row;
        }

        foreach ($this->matchArticles($term) as $row) {
            $groups['Newsletter'][] = $row;
        }

        foreach ($this->matchNews($term) as $row) {
            $groups['News & Media'][] = $row;
        }

        foreach ($this->matchPolicies($term) as $row) {
            $groups['Policies'][] = $row;
        }

        return $groups;
    }

    private function matchStaticPages(string $term): array
    {
        $rows = [];

        foreach ($this->staticPages() as $page) {
            if (!Str::contains(Str::lower($page['title']), Str::lower($term))) {
                continue;
            }

            $rows[] = [
                'group'   => $page['group'],
                'title'   => $page['title'],
                'snippet' => 'Website page',
                'url'     => route($page['route']),
            ];
        }

        return $rows;
    }

    private function matchServices(string $term): array
    {
        return ProductCategory::with('serviceCategory')
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->where('name', 'like', "%{$term}%")
            ->orderBy('sort_order')
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'title'   => $p->name,
                'group'   => optional($p->serviceCategory)->name ?: 'Services',
                'snippet' => 'Service page',
                'url'     => $p->url,
            ])
            ->filter(fn ($r) => $r['url'])
            ->values()
            ->all();
    }

    private function matchNoticePages(string $term): array
    {
        return NoticeCategory::live()
            ->where('link_type', 'page')
            ->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                                 ->orWhere('page_title', 'like', "%{$term}%"))
            ->ordered()
            ->limit(20)
            ->get()
            ->map(fn ($c) => [
                'title'   => $c->page_title ?: $c->name,
                'snippet' => 'Public Notice page',
                'url'     => $c->slug ? route('frontend.notice_page', $c->slug) : null,
            ])
            ->filter(fn ($r) => $r['url'])
            ->values()
            ->all();
    }

    private function matchNoticeDocuments(string $term): array
    {
        return Notice::whereNull('deleted_at')
            ->where('status', 1)
            ->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                                 ->orWhere('description', 'like', "%{$term}%")
                                 ->orWhere('period', 'like', "%{$term}%"))
            ->with('category')
            ->limit(30)
            ->get()
            ->map(function ($n) {
                $page = $n->category;

                return [
                    'title'   => $n->title,
                    'snippet' => trim(($n->period ? $n->period . ' — ' : '')
                        . ($page->name ?? 'Public Notice')),
                    // Prefer the document itself; fall back to the page it sits on.
                    'url'     => $n->document_url
                        ?: (($page && $page->slug) ? route('frontend.notice_page', $page->slug) : null),
                ];
            })
            ->filter(fn ($r) => $r['url'])
            ->values()
            ->all();
    }

    private function matchOpenings(string $term): array
    {
        return CareerOpening::whereNull('deleted_at')
            ->where('status', 1)
            ->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                                 ->orWhere('description', 'like', "%{$term}%")
                                 ->orWhere('location', 'like', "%{$term}%")
                                 ->orWhere('qualification', 'like', "%{$term}%"))
            ->orderBy('sort_order')
            ->limit(20)
            ->get()
            ->map(fn ($o) => [
                'title'   => $o->title,
                'snippet' => trim(collect([$o->experience, $o->location])->filter()->implode(' — ')) ?: 'Job opening',
                'url'     => route('frontend.careers') . '#current-openings',
            ])
            ->all();
    }

    private function matchArticles(string $term): array
    {
        return Article::whereNull('deleted_at')
            ->where('status', 1)
            ->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                                 ->orWhere('year', 'like', "%{$term}%"))
            ->orderBy('sort_order')
            ->limit(20)
            ->get()
            ->map(fn ($a) => [
                'title'   => $a->title,
                'snippet' => trim(($a->year ? $a->year . ' — ' : '') . 'Article'),
                'url'     => $a->pdf_url ?: route('frontend.articles'),
            ])
            ->all();
    }

    /** Privacy Policy and the other legal pages, matched on title or body text. */
    private function matchPolicies(string $term): array
    {
        return PolicyPage::live()
            ->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                                 ->orWhere('sections', 'like', "%{$term}%")
                                 ->orWhere('intro_text', 'like', "%{$term}%"))
            ->ordered()
            ->limit(10)
            ->get()
            ->map(fn ($p) => [
                'title'   => $p->title,
                'snippet' => 'Website page',
                'url'     => $p->url,
            ])
            ->all();
    }

    private function matchNews(string $term): array
    {
        return NewsMedia::whereNull('deleted_at')
            ->where('status', 1)
            ->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
                                 ->orWhere('category', 'like', "%{$term}%"))
            ->orderBy('sort_order')
            ->limit(20)
            ->get()
            ->map(fn ($n) => [
                'title'   => $n->title,
                'snippet' => $n->category ?: 'News & Media',
                'url'     => $n->read_more_url ?: route('frontend.news_media'),
            ])
            ->all();
    }
}
