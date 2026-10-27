<?php

namespace App\Console\Commands;

use App\Models\Notice;
use App\Models\NoticeBannerDetails;
use App\Models\NoticeCategory;
use App\Models\NoticeDetailItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Gives each company under Default Cases > Listed Issuance Details a page on
 * this site, built from what its page on the old catalysttrustee.com held.
 *
 * The documents were downloaded into public/public-notice/detail-documents and
 * listed in database/data/default-cases.json; this command only writes rows.
 * A company is matched on the old link its card points to, then on its title.
 *
 *   php artisan public-notice:import-default-cases
 *   php artisan public-notice:import-default-cases --dry-run
 *   php artisan public-notice:import-default-cases --force   (rebuild pages that already have documents)
 */
class ImportDefaultCasePages extends Command
{
    protected $signature = 'public-notice:import-default-cases
                            {--slug=listed-issuance-details : The page the companies are listed on}
                            {--data=default-cases : The file in database/data to read, without .json}
                            {--force : Replace the documents of a company that already has some}
                            {--dry-run : Report only, write nothing}';

    protected $description = 'Build the Default Cases company pages from the documents taken off the old website';

    public function handle()
    {
        $page = NoticeCategory::whereNull('deleted_at')->where('slug', $this->option('slug'))->first();
        if (!$page) {
            $this->error('No Public Notice page with the slug "' . $this->option('slug') . '".');
            return self::FAILURE;
        }

        $data = json_decode(file_get_contents(database_path('data/' . $this->option('data') . '.json')), true);
        $dry  = $this->option('dry-run');

        $banner = $dry ? null : $this->bannerFor($page);
        $notices = $page->notices()->get();
        $done = 0;

        foreach ($data as $position => $company) {
            $notice = $notices->first(fn ($n) => $this->sameLink($n->document_link, $company['source']))
                ?: $notices->first(fn ($n) => Str::lower(trim($n->title)) === Str::lower(trim($company['notice_title'])));

            if (!$notice && !empty($company['create']) && !$dry) {
                $notice = Notice::create([
                    'notice_category_id' => $page->id,
                    'title'              => $company['notice_title'],
                    'status'             => 1,
                    'created_at'         => Carbon::now(),
                ]);
                $this->info('Added: ' . $notice->title);
            }

            if (!$notice) {
                $this->warn('Not on the page, skipped: ' . $company['notice_title']);
                continue;
            }

            // The card itself: the heading it sits under, its place, and a copy
            // of its document on this site instead of a link to the old one.
            if (!$dry && (isset($company['group']) || !empty($company['card_file']))) {
                $card = ['sort_order' => ($position + 1) * 10, 'modified_at' => Carbon::now()];
                if (isset($company['group'])) {
                    $card['period'] = $company['group'];
                }
                if (!empty($company['card_file']) && !$notice->document_file
                    && is_file(public_path('public-notice/documents/' . $company['card_file']))) {
                    $card['document_file'] = $company['card_file'];
                }
                $notice->update($card);
            }

            if (empty($company['documents'])) {
                continue;   // a card that opens its document, with no page of its own
            }

            if ($notice->detailItems()->exists() && !$this->option('force')) {
                $this->line('Already has a page, left alone: ' . $notice->title . ' (use --force to rebuild)');
                continue;
            }

            $missing = collect($company['documents'])
                ->filter(fn ($d) => $d['file'] && !is_file(public_path('public-notice/detail-documents/' . $d['file'])))
                ->count();
            if ($missing) {
                $this->warn($notice->title . ': ' . $missing . ' document file(s) missing from public/public-notice/detail-documents.');
            }

            $this->info(sprintf('%s: %d documents', $notice->title, count($company['documents'])));
            if ($dry) {
                continue;
            }

            $notice->update([
                'has_detail_page'     => 1,
                'detail_slug'         => $notice->detail_slug ?: $this->uniqueSlug($notice, $page),
                'detail_banner_title' => $notice->detail_banner_title ?: $company['banner_heading'],
                'detail_banner_image' => $notice->detail_banner_image ?: $banner,
                'modified_at'         => Carbon::now(),
            ]);

            // Pages inside this one (DHFL > DHFL IBC) come first, so rows can link to them.
            $subPaths = [];
            foreach ($company['sub_pages'] ?? [] as $j => $subData) {
                $sub = Notice::whereNull('deleted_at')
                    ->where('parent_notice_id', $notice->id)
                    ->where('detail_slug', $subData['slug'])
                    ->first();

                if (!$sub) {
                    $sub = Notice::create([
                        'notice_category_id' => $page->id,
                        'parent_notice_id'   => $notice->id,
                        'title'              => $subData['title'],
                        'detail_slug'        => $subData['slug'],
                        'status'             => 1,
                        'created_at'         => Carbon::now(),
                    ]);
                }

                $sub->update([
                    'has_detail_page'     => 1,
                    'detail_banner_title' => $sub->detail_banner_title ?: $subData['title'],
                    'detail_banner_image' => $sub->detail_banner_image ?: $notice->detail_banner_image,
                    'sort_order'          => ($j + 1) * 10,
                    'modified_at'         => Carbon::now(),
                ]);

                $this->writeItems($sub, $subData['documents']);
                $subPaths['sub:' . $subData['slug']] = '/public-notice/' . $page->slug . '/' . $notice->detail_slug . '/' . $subData['slug'];
                $this->line('   inside: ' . $subData['title'] . ' (' . count($subData['documents']) . ' documents)');
            }

            $this->writeItems($notice, $company['documents'], $subPaths);
            $done++;
        }

        $this->info($dry ? 'Dry run - nothing saved.' : "Built {$done} company pages.");

        return self::SUCCESS;
    }

    /** Replace a page's documents with the imported ones. */
    private function writeItems(Notice $notice, array $documents, array $subPaths = [])
    {
        NoticeDetailItem::where('notice_id', $notice->id)->whereNull('deleted_at')
            ->update(['deleted_at' => Carbon::now()]);

        foreach ($documents as $i => $doc) {
            $link = $doc['file'] ? null : $doc['link'];

            NoticeDetailItem::create([
                'notice_id'     => $notice->id,
                'group_title'   => $doc['highlight'] ? null : $doc['heading'],
                'title'         => $doc['title'],
                'document_file' => $doc['file'],
                'document_link' => $subPaths[$link] ?? $link,
                'is_highlight'  => $doc['highlight'] ? 1 : 0,
                'sort_order'    => ($i + 1) * 10,
                'status'        => 1,
                'created_at'    => Carbon::now(),
            ]);
        }
    }

    /**
     * The pages need a banner image; start them on the one the list page shows,
     * copied once into the detail banner folder and shared until an admin
     * gives a company its own.
     */
    private function bannerFor(NoticeCategory $page)
    {
        $source = $page->banner_image
            ? 'public-notice/banner/' . $page->banner_image
            : (($shared = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first()) && $shared->background_image
                ? 'public-notice/banner/' . $shared->background_image
                : null);

        if (!$source || !is_file(public_path($source))) {
            $this->warn('No banner image found to start the pages with - add one to each page in the admin.');
            return null;
        }

        $name = 'detail_banner_' . $page->slug . '.' . pathinfo($source, PATHINFO_EXTENSION);
        $dir  = public_path('public-notice/detail-banner');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        if (!is_file($dir . '/' . $name)) {
            copy(public_path($source), $dir . '/' . $name);
        }

        return $name;
    }

    /** Old links were typed with and without www and a trailing slash. */
    private function sameLink($a, $b)
    {
        $norm = fn ($u) => rtrim(preg_replace('#^https?://(www\.)?#i', '', trim((string) $u)), '/');

        return $a && $b && Str::lower($norm($a)) === Str::lower($norm($b));
    }

    private function uniqueSlug(Notice $notice, NoticeCategory $page)
    {
        $base = Str::slug($notice->title) ?: 'details';
        $slug = $base;
        $n    = 2;

        while (Notice::where('notice_category_id', $page->id)->where('id', '!=', $notice->id)
            ->where('detail_slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
