<?php

if (!function_exists('site_link')) {
    /**
     * Turn a link stored by an admin into one that works on this installation.
     *
     * Links typed into the dashboard are stored as they are. A full web address
     * is left alone, but a path such as "services/gift-city-services/facility-agent"
     * is completed with the site's own address. That matters because the live
     * site sits in a sub-folder — a path starting with "/" would point at the
     * wrong place there, and a full address typed on one server would send
     * visitors to the other one.
     *
     * Anything empty comes back as "#", so a link is never broken.
     */
    function site_link(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '#') {
            return '#';
        }

        // Already complete, or something the browser handles itself.
        if (preg_match('~^(https?:)?//~i', $value)
            || preg_match('~^(mailto:|tel:|#)~i', $value)) {
            return $value;
        }

        return url($value);
    }
}

if (!function_exists('versioned_asset')) {
    /**
     * asset() with a cache-busting stamp taken from the file's last-modified time.
     *
     * Browsers hold on to CSS and JS aggressively, so an edited stylesheet or
     * script can keep serving the old copy long after a change is deployed.
     * Appending the file's timestamp gives every change a fresh URL.
     * Falls back to a plain asset() when the file cannot be found.
     */
    function versioned_asset(string $path): string
    {
        $full = public_path($path);

        return is_file($full)
            ? asset($path) . '?v=' . filemtime($full)
            : asset($path);
    }
}

if (!function_exists('site_page_links')) {
    /**
     * Every page on the website, as a list an admin can pick from.
     *
     * Link boxes in the dashboard take a path typed by hand, which means
     * knowing what the paths are. This gathers them - the fixed pages, plus
     * the services, notice pages and blog posts as they are added - so the
     * boxes can suggest them instead.
     *
     * Returns [path => what the page is called].
     */
    function site_page_links(): array
    {
        $links = [
            ''                                          => 'Home',
            'company-overview'                          => 'About — Company Overview',
            'leadership'                                => 'About — Our Leadership',
            'group-companies'                           => 'About — Group Companies',
            'our-journey'                               => 'About — Our Journey',
            'services'                                  => 'Services — all services',
            'careers/life-at-catalyst'                  => 'Careers — Life at Catalyst',
            'careers/current-openings'                  => 'Careers — Current Openings',
            'newsletter/articles'                       => 'Articles — Newsletter',
            'blog'                                      => 'Articles — Blog',
            'newsletter/news-and-media'                 => 'Articles — News & Media',
            'grievance-redressal-for-services-regulated-by-sebi' => 'Grievance — SEBI regulated',
            'for-services-not-regulated-by-sebi'        => 'Grievance — not SEBI regulated',
            'notices-and-announcements'                 => 'Public Notice — all notices',
            'contact-us'                                => 'Contact Us',
        ];

        // The pages that come and go with the content.
        try {
            if (class_exists(\App\Models\ProductCategory::class)) {
                foreach (\App\Models\ProductCategory::with('serviceCategory')
                    ->whereNull('deleted_at')->where('status', 1)
                    ->orderBy('sort_order')->get() as $service) {

                    $url = $service->url;
                    if (!$url) { continue; }

                    $links[ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/')] =
                        'Service — ' . $service->name;
                }
            }

            if (class_exists(\App\Models\NoticeCategory::class)) {
                foreach (\App\Models\NoticeCategory::live()->where('link_type', 'page')
                    ->ordered()->get() as $page) {
                    if (!$page->slug) { continue; }
                    $links['public-notice/' . $page->slug] = 'Notice page — ' . $page->name;
                }
            }

            if (class_exists(\App\Models\Blog::class)) {
                foreach (\App\Models\Blog::live()->newestFirst()->get() as $post) {
                    $links['blog/' . $post->slug] = 'Blog — ' . $post->title;
                }
            }
        } catch (\Throwable $e) {
            // Before the tables exist, the fixed list on its own is still useful.
        }

        return $links;
    }
}
