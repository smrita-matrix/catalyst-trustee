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
     * Render a product's page using the template for its chosen layout.
     */
    public function show($slug)
    {
        $product = ProductCategory::where('slug', $slug)->whereNull('deleted_at')->firstOrFail();
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
