<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;


class ProductCategoryController extends Controller
{
    public function index()
    {
        $products = ProductCategory::with('serviceCategory')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.services.product-categories.index', compact('products'));
    }

    /**
     * "Product Services" — all products grouped into category tabs, for editing each page.
     */
    public function services()
    {
        $products = ProductCategory::with('serviceCategory')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $categories = ServiceCategory::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.services.product-services.index', compact('products', 'categories'));
    }

    public function create()
    {
        $serviceCategories = $this->activeServiceCategories();

        return view('backend.services.product-categories.create', compact('serviceCategories'));
    }

    /**
     * Visual guide showing what each layout looks like (sections + sample design).
     */
    public function layoutGuide()
    {
        $base = 'https://mbihosting.in/catalyst-trustee/html/';
        $guide = [
            [
                'name' => 'Layout 1', 'for' => 'SEBI style',
                'sample' => $base . 'debenture-trustee-listed.html',
                'sections' => ['Banner', 'Intro (image + heading + description + "Our Expertise" points)', 'Our Services Include (image + points)', 'Why Catalyst (icon cards)', 'Services Offered (Advisory / Documentation / Operational tabs)', 'Recognition & Registration (certificates + note)'],
            ],
            [
                'name' => 'Layout 2', 'for' => 'SEBI style',
                'sample' => $base . 'services-2.html',
                'sections' => ['Banner', 'Nature Of Work (image + heading + description)', 'Process & Execution (image + points)', 'Key Facts (image + points)'],
            ],
            [
                'name' => 'Layout 3', 'for' => 'Non-SEBI style',
                'sample' => $base . 'services-3.html',
                'sections' => ['Banner', 'Intro (image + heading + description)', 'Services (side tabs: Fund Registration, Documentation…)', 'Key Benefits (image + points + note)'],
            ],
            [
                'name' => 'Layout 4', 'for' => 'GIFT City style',
                'sample' => $base . 'family-investment-funds.html',
                'sections' => ['Banner', 'Intro (image + sub-heading + description)', 'Definition / Concept blocks', 'Process (side tabs: Corpus, Threshold…)', 'Tax comparison table', 'Family Office Solution (image + text)', 'Capabilities (image + points)'],
            ],
        ];

        return view('backend.services.layout-guide', compact('guide'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        ProductCategory::create([
            'service_category_id' => $request->service_category_id,
            'name'                => $request->name,
            'slug'                => Str::slug($request->name),
            'layout'              => $request->layout,
            'sort_order'          => $request->sort_order ?? 0,
            'status'              => $request->has('status') ? 1 : 0,
            'created_at'          => Carbon::now(),
            'created_by'          => Auth::id(),
        ]);

        return redirect()->route('product-category.index')->with('message', 'Product added successfully!');
    }

    public function edit($id)
    {
        $product = ProductCategory::findOrFail($id);
        $serviceCategories = $this->activeServiceCategories();

        return view('backend.services.product-categories.edit', compact('product', 'serviceCategories'));
    }

    public function update(Request $request, $id)
    {
        $product = ProductCategory::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $product->update([
            'service_category_id' => $request->service_category_id,
            'name'                => $request->name,
            'slug'                => Str::slug($request->name),
            'layout'              => $request->layout,
            'sort_order'          => $request->sort_order ?? 0,
            'status'              => $request->has('status') ? 1 : 0,
            'modified_at'         => Carbon::now(),
            'modified_by'         => Auth::id(),
        ]);

        return redirect()->route('product-category.index')->with('message', 'Product has been successfully updated!');
    }

    /**
     * Open the correct layout editor for a product (based on its chosen layout).
     */
    public function editPage($id)
    {
        $product = ProductCategory::findOrFail($id);

        switch ($product->layout) {
            case 'debenture': return redirect()->route('service-layout1.edit', $product->id);
            case 'services2': return redirect()->route('service-layout2.edit', $product->id);
            case 'services3': return redirect()->route('service-layout3.edit', $product->id);
            case 'fif':       return redirect()->route('service-fif.edit', $product->id);
            default:
                return redirect()->route('product-category.edit', $product->id)
                    ->with('error', 'Please choose a Page Layout for this product first.');
        }
    }

    public function destroy($id)
    {
        try {
            $product = ProductCategory::findOrFail($id);
            $product->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('product-category.index')->with('message', 'Product deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function activeServiceCategories()
    {
        return ServiceCategory::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    private function rules()
    {
        return [
            'service_category_id' => 'required|integer|exists:service_categories,id',
            'name'                => 'required|string|max:255',
            'layout'              => 'nullable|string|in:' . implode(',', array_keys(ProductCategory::LAYOUTS)),
            'sort_order'          => 'nullable|integer',
        ];
    }

    private function messages()
    {
        return [
            'service_category_id.required' => 'Please select a Service Category.',
            'name.required'                => 'The Product Name is required.',
        ];
    }
}
