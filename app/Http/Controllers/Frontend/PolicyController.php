<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FooterDetails;
use App\Models\PolicyPage;

/**
 * Renders the admin-managed legal pages (Privacy Policy, and any others the
 * team adds later) from their slug.
 */
class PolicyController extends Controller
{
    public function show(string $slug)
    {
        $page   = PolicyPage::live()->where('slug', $slug)->firstOrFail();
        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.policy.show', compact('page', 'footer'));
    }
}
