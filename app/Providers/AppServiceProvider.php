<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ServiceCategory;
use App\Models\ProductCategory;
use App\Models\NoticeCategory;
use App\Models\GrievancePageDetails;
use App\Models\FooterDetails;
use App\Models\PolicyPage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Small helper used by the frontend layout for cache-busting.
        require_once app_path('Helpers/asset_version.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Feed the dynamic "Services" menu to the frontend header.
        View::composer('components.frontend.header', function ($view) {
            $serviceMenu = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('service_categories')) {
                $serviceMenu = ServiceCategory::whereNull('deleted_at')
                    ->where('status', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get()
                    ->map(function ($cat) {
                        $items = ProductCategory::where('service_category_id', $cat->id)
                            ->whereNull('deleted_at')
                            ->where('status', 1)
                            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')
                            ->get()
                            ->map(function ($p) {
                                if (in_array($p->layout, ['debenture', 'services2', 'services3', 'fif'], true) && $p->slug) {
                                    $link = route('frontend.product_page', $p->slug);
                                } else {
                                    $link = '#';
                                }
                                return ['title' => $p->name, 'link' => $link];
                            })
                            ->all();

                        return [
                            'name'  => $cat->name,
                            'icon'  => $cat->icon,
                            'items' => $items,
                        ];
                    })
                    ->values();
            }

            $view->with('serviceMenu', $serviceMenu);
        });

        // Feed the dynamic "Public Notice" mega-menu to the frontend header.
        View::composer('components.frontend.header', function ($view) {
            $noticeMenu = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('notice_categories')) {
                $noticeMenu = NoticeCategory::live()
                    ->whereNull('parent_id')
                    ->ordered()
                    ->with(['children' => fn ($q) => $q->where('status', 1)->with(['children' => fn ($q2) => $q2->where('status', 1)])])
                    ->get();
            }

            $view->with('noticeMenu', $noticeMenu);
        });

        // "Contact for Support" in the Grievance menu is just a PDF the admin uploads.
        View::composer('components.frontend.header', function ($view) {
            $supportPdf = null;

            if (\Illuminate\Support\Facades\Schema::hasTable('grievance_page_details')) {
                $supportPdf = optional(
                    GrievancePageDetails::whereNull('deleted_at')->latest('id')->first()
                )->support_pdf_url;
            }

            $view->with('supportPdf', $supportPdf);
        });

        // Footer links: the admin's Quick Links plus the legal pages.
        View::composer('components.frontend.footer', function ($view) {
            $policies = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('policy_pages')) {
                $policies = PolicyPage::live()->ordered()->get();
            }

            $byTitle = $policies->keyBy(fn ($p) => \Illuminate\Support\Str::lower(trim($p->title)));
            $claimed = collect();

            $host = request()->getHost();

            // A Quick Link with no address of its own is filled in from the
            // policy page of the same name — so the "Privacy Policy" entry that
            // has always been in the footer now actually opens the page. Rows
            // still left without an address are dropped rather than rendered as
            // a link that goes nowhere.
            $quickLinks = collect($view->getData()['footer']?->quick_links ?? [])
                ->map(function ($link) use ($byTitle, $claimed, $host) {
                    $label = trim($link['label'] ?? '');
                    $url   = trim($link['url'] ?? '');

                    if ($url === '' && $label !== '') {
                        $match = $byTitle->get(\Illuminate\Support\Str::lower($label));

                        if ($match) {
                            $url = $match->url;
                            $claimed->push($match->id);
                        }
                    }

                    return [
                        'label'    => $label,
                        'url'      => $url,
                        'external' => \Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])
                            && parse_url($url, PHP_URL_HOST) !== $host,
                    ];
                })
                ->filter(fn ($l) => $l['label'] !== '' && $l['url'] !== '')
                ->values();

            // Anything ticked "show in footer" that the Quick Links did not
            // already cover goes in the bar under the copyright line.
            $policyLinks = $policies
                ->where('show_in_footer', true)
                ->reject(fn ($p) => $claimed->contains($p->id))
                ->values();

            $view->with([
                'quickLinks'  => $quickLinks,
                'policyLinks' => $policyLinks,
            ]);
        });

        // Site-wide button links (the header "Get Started" button).
        View::composer('components.frontend.header', function ($view) {
            $siteLinks = null;

            if (\Illuminate\Support\Facades\Schema::hasTable('footer_details')) {
                $siteLinks = FooterDetails::whereNull('deleted_at')->latest('id')->first();
            }

            $view->with('siteLinks', $siteLinks);
        });
    }
}
