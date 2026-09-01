<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DebentureTrusteeListedDetails;
use App\Models\ServiceLayout2Details;
use App\Models\ServiceLayout3Details;
use App\Models\ServiceFifDetails;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\FooterDetails;


class ServicesSebiController extends Controller
{
    public function debenture_trustee_listed()
    {
        $page   = DebentureTrusteeListedDetails::whereNull('deleted_at')->latest('id')->first();
        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.services.sebi_services.debenture_trustee_listed', compact('page', 'footer'));
    }

    /**
     * Old address, before the group was part of it: /services/{slug}.
     *
     * Anything already linked or bookmarked still works — it is sent on to the
     * new address rather than hitting a dead page. Where a slug is used by more
     * than one group the first match wins, which is the best that can be done
     * for a link that was ambiguous to begin with.
     */
    public function showLegacy($slug)
    {
        $product = ProductCategory::with('serviceCategory')
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        abort_unless($product->url, 404);

        return redirect($product->url, 301);
    }

    /**
     * Render a product's page using the template for its chosen layout.
     *
     * The group slug is part of the address because a service name is only
     * unique within its group — "Facility Agent" sits under both Non SEBI and
     * GIFT City, and looking up by name alone would always return the same one.
     */
    public function show($categorySlug, $slug)
    {
        $category = ServiceCategory::where('slug', $categorySlug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $product = ProductCategory::where('service_category_id', $category->id)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $footer  = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        switch ($product->layout) {
            case 'debenture':
                $page = DebentureTrusteeListedDetails::where('product_id', $product->id)->whereNull('deleted_at')->first();
                return view('frontend.services.sebi_services.debenture_trustee_listed', compact('product', 'page', 'footer'));

            case 'services2':
                $page = ServiceLayout2Details::where('product_id', $product->id)->whereNull('deleted_at')->first();
                return view('frontend.services.layouts.services2', compact('product', 'page', 'footer'));

            case 'services3':
                $page = ServiceLayout3Details::where('product_id', $product->id)->whereNull('deleted_at')->first();
                return view('frontend.services.layouts.services3', compact('product', 'page', 'footer'));

            case 'fif':
                $page = ServiceFifDetails::where('product_id', $product->id)->whereNull('deleted_at')->first();
                return view('frontend.services.layouts.fif', compact('product', 'page', 'footer'));

            default:
                abort(404);
        }
    }
}
