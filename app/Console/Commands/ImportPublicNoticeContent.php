<?php

namespace App\Console\Commands;

use App\Models\Notice;
use App\Models\NoticeCategory;
use Carbon\Carbon;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

/**
 * Pulls the documents off the approved static design pages and stores them
 * against the matching Public Notice page.
 *
 * Re-running is safe: a notice is matched on page + title, so existing rows are
 * updated rather than duplicated, and anything the admin has added by hand stays.
 *
 *   php artisan public-notice:import                 (all pages)
 *   php artisan public-notice:import --slug=policies (one page)
 *   php artisan public-notice:import --dry-run       (report only, writes nothing)
 */
class ImportPublicNoticeContent extends Command
{
    protected $signature = 'public-notice:import
                            {--slug= : Import a single page by its slug}
                            {--only-empty : Skip any page that already has notices}
                            {--purge : Delete previously imported rows first (never touches admin-uploaded PDFs)}
                            {--dry-run : Show what would be imported without saving}';

    protected $description = 'Import Public Notice documents from the approved design pages';

    private const BASE = 'https://mbihosting.in/catalyst-trustee/html/';

    /** Our page slug => the design file it came from (most are identical). */
    private const SOURCES = [
        'breach-of-minimum-security-cover'   => 'breach-of-minimum-security-cover',
        'breach-of-covenants'                => 'breach-of-covenants',
        'auction-notices'                    => 'auction-notices',
        'revision-in-credit-ratings'         => 'revision-in-credit-ratings',
        'status-of-payment-of-interest-and-principal' => 'status-of-payment-of-interest-and-principal',
        'monitoring-of-utilization-certificate'       => 'monitoring-of-utilization-certificate',
        'security-cover-certificate'         => 'security-cover-certificate',
        'periodical-status-performance-reports-quarterly-compliance-reports-financial-statement'
                                             => 'periodical-status-performance-reports-quarterly-compliance-reports-financial-statement',
        'details-of-debenture-issues-handled-by-debenture-trustee-and-their-status'
                                             => 'details-of-debenture-issues-handled-by-debenture-trustee-and-their-status',
        'status-of-information-regarding-breach-of-covenants-terms-of-the-issue'
                                             => 'status-of-information-regarding-breach-of-covenants-terms-of-the-issue',
        'complaints-received-including-default-cases' => 'complaints-received-including-default-cases-half-yearly',
        'debenture-redemption-reserve'       => 'debenture-redemption-reserve',
        'debenture-redemption-fund'          => 'debenture-redemption-fund',
        'recovery-expenses-fund'             => 'recovery-expenses-fund',
        'monitoring-of-accounts-fund-municipal-debt-securities'
                                             => 'monitoring-of-accounts-fund-municipal-debt-securities-annual',
        'information-on-defaulted-cases'     => 'information-on-defaulted-cases',
        'investor-charter-debenture-trustee' => 'investor-charter-debenture-trustee',
        'periodical-statements-submitted-to-sebi' => 'periodical-statements-submitted-to-sebi',
        'policies'                           => 'policies',
        'dskdl-developers-ltd'               => 'dskdl-developers-ltd',
        'sefl-sifl-ibc'                      => 'sefl-sifl-ibc',
    ];

    /** Card wrappers, in every design. Each holds one document. */
    private const CARD_CLASSES = [
        'bomsc-card', 'boc-card', 'auc-card',
        'secu-cover-certi-card', 'regul-disclo-stpmt-box-col',
    ];

    /** Group headings — the date pill / collapsible month above a set of cards. */
    private const GROUP_CLASSES = [
        'bomsc-date', 'boc-month-title', 'secu-cover-certi-month-title',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $only   = $this->option('slug');

        $sources = $only
            ? array_intersect_key(self::SOURCES, [$only => true])
            : self::SOURCES;

        if (!$sources) {
            $this->error("No design page is mapped to slug \"{$only}\".");
            return self::FAILURE;
        }

        $totalAdded = $totalUpdated = $totalSkipped = 0;

        foreach ($sources as $slug => $file) {
            $category = NoticeCategory::where('slug', $slug)->first();

            if (!$category) {
                $this->warn("skip  {$slug} — no such page in notice_categories");
                continue;
            }

            if ($this->option('only-empty') && $category->notices()->exists()) {
                $this->line(sprintf('  %-58s skipped — already has content', $slug));
                continue;
            }

            if ($this->option('purge') && !$dryRun) {
                // Only rows this command created; an uploaded PDF means it was curated.
                $removed = Notice::where('notice_category_id', $category->id)
                    ->where(fn ($q) => $q->whereNull('document_file')->orWhere('document_file', ''))
                    ->delete();

                if ($removed) {
                    $this->line(sprintf('  %-58s purged %d imported row(s)', $slug, $removed));
                }
            }

            $html = @file_get_contents(self::BASE . $file . '.html');

            if ($html === false) {
                $this->error("fail  {$slug} — could not fetch {$file}.html");
                continue;
            }

            [$added, $updated, $skipped] = $this->importPage($category, $html, $dryRun);

            $totalAdded   += $added;
            $totalUpdated += $updated;
            $totalSkipped += $skipped;

            $this->line(sprintf('  %-58s %2d new, %2d updated, %2d kept', $slug, $added, $updated, $skipped));
        }

        $this->newLine();
        $this->info(($dryRun ? '[dry run] ' : '') . "Done — {$totalAdded} added, {$totalUpdated} updated, {$totalSkipped} left untouched (admin-uploaded PDFs).");

        return self::SUCCESS;
    }

    /** Parse one design page and store its documents against $category. */
    private function importPage(NoticeCategory $category, string $html, bool $dryRun): array
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        $this->importPageChrome($category, $xpath, $dryRun);

        $added = $updated = $skipped = 0;
        $group = '';
        $order = 0;

        // Walk the document in order so each card keeps the heading above it.
        foreach ($xpath->query('//*[@class]') as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            $classes = preg_split('/\s+/', trim($node->getAttribute('class')));

            if (array_intersect($classes, self::GROUP_CLASSES)) {
                $group = $this->text($node);
                continue;
            }

            if (!array_intersect($classes, self::CARD_CLASSES)) {
                continue;
            }

            $card = $this->parseCard($node, $xpath);

            if ($card['title'] === '') {
                continue;
            }

            $order += 10;

            if ($dryRun) {
                $added++;
                continue;
            }

            // Title alone is not unique — the same company appears under several
            // months — so the group is part of the key.
            $existing = Notice::where('notice_category_id', $category->id)
                ->where('title', $card['title'])
                ->where('period', $group)
                ->whereNull('deleted_at')
                ->first();

            $data = [
                'notice_category_id' => $category->id,
                'period'             => $group,
                'title'              => $card['title'],
                'description'        => $card['description'],
                'notice_date'        => $card['date'],
                'document_link'      => $card['link'],
                'sort_order'         => $order,
                'status'             => 1,
            ];

            if ($existing) {
                // A row with an uploaded PDF was curated in the admin — leave it alone.
                if ($existing->document_file) {
                    $skipped++;
                    continue;
                }
                $existing->update($data + ['modified_at' => Carbon::now(), 'modified_by' => Auth::id()]);
                $updated++;
            } else {
                Notice::create($data + ['created_at' => Carbon::now(), 'created_by' => Auth::id()]);
                $added++;
            }
        }

        return [$added, $updated, $skipped];
    }

    /** Page heading and the "Attention Investors!" box, straight off the design. */
    private function importPageChrome(NoticeCategory $category, DOMXPath $xpath, bool $dryRun): void
    {
        if ($dryRun) {
            return;
        }

        $changes = [];

        $heading = $xpath->query('//div[contains(@class,"heading")]/h2')->item(0);
        if ($heading && !$category->page_title) {
            $changes['page_title'] = $this->text($heading);
        }

        $alertHeading = $xpath->query('//*[contains(@class,"alert-heading")]')->item(0);
        if ($alertHeading && !$category->alert_heading) {
            $changes['alert_heading'] = $this->text($alertHeading);
        }

        $alertText = $xpath->query('//*[contains(@class,"alert-text")]')->item(0);
        if ($alertText && !$category->alert_text) {
            $changes['alert_text'] = $this->innerHtml($alertText);
        }

        if ($changes) {
            $category->update($changes + ['modified_at' => Carbon::now()]);
        }
    }

    /** Pull title, description, date and document link out of one card. */
    private function parseCard(DOMElement $card, DOMXPath $xpath): array
    {
        $title = '';
        foreach ($xpath->query('.//*[contains(@class,"-title")]', $card) as $node) {
            $title = $this->text($node);
            break;
        }

        $date = '';
        foreach ($xpath->query('.//*[contains(@class,"card-date")]', $card) as $node) {
            $date = $this->text($node);
            break;
        }

        // First paragraph that is not the title or the call-to-action.
        $description = '';
        foreach ($xpath->query('.//p', $card) as $node) {
            $value = $this->text($node);
            if ($value !== '' && $value !== $title && !str_contains(strtolower($value), 'view document')) {
                $description = $value;
                break;
            }
        }

        // The card is usually the <a> itself; otherwise take the first link inside.
        $href = $card->getAttribute('href');
        if ($href === '') {
            foreach ($xpath->query('.//a[@href]', $card) as $node) {
                $href = $node->getAttribute('href');
                break;
            }
        }

        return [
            'title'       => $title,
            'date'        => $date,
            'description' => $description,
            'link'        => $this->absoluteUrl($href),
        ];
    }

    private function absoluteUrl(string $href): ?string
    {
        $href = trim($href);

        if ($href === '' || $href === '#' || str_starts_with($href, 'javascript:')) {
            return null;
        }

        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            return $href;
        }

        return self::BASE . ltrim($href, '/');
    }

    private function text(\DOMNode $node): string
    {
        return trim(preg_replace('/\s+/u', ' ', $node->textContent));
    }

    private function innerHtml(\DOMNode $node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument->saveHTML($child);
        }

        return trim(preg_replace('/\s+/u', ' ', $html));
    }
}
