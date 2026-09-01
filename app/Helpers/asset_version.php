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
