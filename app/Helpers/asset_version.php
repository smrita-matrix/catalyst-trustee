<?php

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
