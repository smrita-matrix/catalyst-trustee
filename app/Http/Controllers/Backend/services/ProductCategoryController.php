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
     * Visual guide showing what each layout looks like, previewed with a real page.
     */
    /**
     * What each service layout looks like, previewed with a real page from this
     * website rather than the static design files.
     */
    public const LAYOUT_GUIDE = [
        'debenture' => [
            'for' => 'SEBI style',
            'sections' => [
                'Banner',
                'Intro (image + heading + description + "Our Expertise" points)',
                'Our Services Include (image + points)',
                'Why Catalyst (icon cards)',
                'Services Offered (Advisory / Documentation / Operational tabs)',
                'Recognition & Registration (certificates + note)',
            ],
        ],
        'services2' => [
            'for' => 'SEBI style',
            'sections' => [
                'Banner',
                'Nature Of Work (image + heading + description)',
                'Process & Execution (image + points)',
                'Key Facts (image + points)',
            ],
        ],
        'services3' => [
            'for' => 'Non-SEBI style',
            'sections' => [
                'Banner',
                'Intro (image + heading + description)',
                'Services (side tabs: Fund Registration, Documentation…)',
                'Key Benefits (image + points + note)',
            ],
        ],
        'fif' => [
            'for' => 'GIFT City style',
            'sections' => [
                'Banner',
                'Intro (image + sub-heading + description)',
                'Definition / Concept blocks',
                'Process (side tabs: Corpus, Threshold…)',
                'Tax comparison table',
                'Family Office Solution (image + text)',
                'Capabilities (image + points)',
            ],
        ],
    ];

    public function layoutGuide()
    {
        $guide = [];

        foreach (ProductCategory::LAYOUTS as $key => $label) {
            $products = ProductCategory::whereNull('deleted_at')
                ->where('status', 1)
                ->where('layout', $key)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $example = $products->first(fn ($p) => (string) $p->slug !== '');

            $guide[] = [
                'name'     => $label,
                'for'      => self::LAYOUT_GUIDE[$key]['for'] ?? '',
                'sections' => self::LAYOUT_GUIDE[$key]['sections'] ?? [],
                'sample'   => $example ? route('frontend.product_page', $example->slug) : null,
                'example'  => $example->name ?? null,
                'used_by'  => $products->count(),
            ];
        }

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

    /**
     * Show or hide one service in the website menu, straight from the list.
     *
     * Same effect as the "Show on website" switch inside the edit form — this
     * just saves opening the form when all the admin wants is to take an item
     * off the menu.
     */
    public function toggleStatus($id)
    {
        try {
            $product = ProductCategory::findOrFail($id);

            $product->update([
                'status'      => $product->status ? 0 : 1,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);

            return redirect()->back()->with('message', '"' . $product->name . '" is now '
                . ($product->status ? 'showing in' : 'hidden from') . ' the website menu.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
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
