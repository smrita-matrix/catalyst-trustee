<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\DebentureTrusteeListedDetails;
use App\Models\ServiceCategory;
use App\Models\ProductCategory;


class ServicesSebiDetailsController extends Controller
{
    /**
     * Show the Layout 1 editor for a specific product (per-product page).
     */
    public function edit($productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = DebentureTrusteeListedDetails::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        return view('backend.services.debenture-trustee-listed-details.manage', compact('product', 'page'));
    }

    /**
     * Save (create or update) the Layout 1 page content for a specific product.
     */
    public function update(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = DebentureTrusteeListedDetails::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $request->validate($this->rules(), $this->messages());

        $existing = $page;

        $data = $this->baseData($request);
        $data['product_id']            = $product->id;
        $data['category_id']           = $product->service_category_id;
        $data['banner_title']          = $product->name;
        $data['why_cards']             = $this->buildCards($request, $existing->why_cards ?? []);
        $data['services_offered_tabs'] = $this->buildTabs($request, $existing->services_offered_tabs ?? []);
        $data['certificates']          = $this->buildCertificates($request, $existing->certificates ?? []);

        foreach ($this->imageFields() as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($existing && $existing->$field) {
                    $this->deleteImage($existing->$field, $folder);
                }
                $data[$field] = $this->uploadImage($request->file($field), $folder);
            }
        }

        if ($page) {
            $data['modified_at'] = Carbon::now();
            $data['modified_by'] = Auth::id();
            $page->update($data);
        } else {
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::id();
            DebentureTrusteeListedDetails::create($data);
        }

        return redirect()->route('product-services.index')->with('message', 'Page saved successfully!');
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function imageFields()
    {
        return [
            'banner_background_image' => 'banner',
            'intro_image'             => 'intro',
            'services_include_image'  => 'services-include',
        ];
    }

    private function baseData(Request $request)
    {
        return [
            'banner_breadcrumb_parent' => $request->banner_breadcrumb_parent,
            'banner_breadcrumb_child'  => $request->banner_breadcrumb_child,

            'intro_heading'            => $request->intro_heading,
            'intro_description'        => $request->intro_description,
            'intro_expertise_heading'  => $request->intro_expertise_heading,
            'intro_expertise_points'   => $request->intro_expertise_points,

            'services_include_heading' => $request->services_include_heading,
            'services_include_points'  => $request->services_include_points,

            'why_heading'              => $request->why_heading,

            'services_offered_heading' => $request->services_offered_heading,

            'recognition_heading'      => $request->recognition_heading,
            'recognition_note'         => $request->recognition_note,
        ];
    }

    private function rules()
    {
        return [
            'banner_title'            => 'nullable|string|max:255',
            'banner_background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'intro_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'services_include_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'why_card_icon.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'tab_image.*'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'certificate_image.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    private function messages()
    {
        return [
            'banner_background_image.max' => 'The banner image must not be larger than 2MB.',
            'intro_image.max'             => 'The intro image must not be larger than 2MB.',
            'services_include_image.max'  => 'The image must not be larger than 2MB.',
        ];
    }

    /**
     * Build "Why Catalyst" cards JSON [{ icon, title }].
     */
    private function buildCards(Request $request, array $existing)
    {
        $titles       = $request->input('why_card_title', []);
        $existingIcons = $request->input('why_card_existing_icon', []);
        $cards = [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $icon  = $existingIcons[$i] ?? null;

            $newFile = $request->file("why_card_icon.$i");
            if ($newFile && $newFile->isValid()) {
                $icon = $this->uploadImage($newFile, 'why');
                if (!empty($existingIcons[$i])) {
                    $this->deleteImage($existingIcons[$i], 'why');
                }
            }

            if ($title === '' && !$icon) {
                continue;
            }

            $cards[] = ['icon' => $icon, 'title' => $title];
        }

        // remove icons dropped entirely
        $usedIcons = array_filter(array_column($cards, 'icon'));
        foreach ($existing as $old) {
            $oldIcon = $old['icon'] ?? null;
            if ($oldIcon && !in_array($oldIcon, $usedIcons, true)) {
                $this->deleteImage($oldIcon, 'why');
            }
        }

        return $cards;
    }

    /**
     * Build "Services Offered" tabs JSON [{ title, image, points }].
     */
    private function buildTabs(Request $request, array $existing)
    {
        $titles        = $request->input('tab_title', []);
        $points        = $request->input('tab_points', []);
        $existingImgs  = $request->input('tab_existing_image', []);
        $tabs = [];

        foreach ($titles as $i => $title) {
            $title      = trim((string) $title);
            $tabPoints  = trim((string) ($points[$i] ?? ''));
            $image      = $existingImgs[$i] ?? null;

            $newFile = $request->file("tab_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile, 'services-offered');
                if (!empty($existingImgs[$i])) {
                    $this->deleteImage($existingImgs[$i], 'services-offered');
                }
            }

            if ($title === '' && $tabPoints === '' && !$image) {
                continue;
            }

            $tabs[] = ['title' => $title, 'image' => $image, 'points' => $tabPoints];
        }

        $usedImgs = array_filter(array_column($tabs, 'image'));
        foreach ($existing as $old) {
            $oldImg = $old['image'] ?? null;
            if ($oldImg && !in_array($oldImg, $usedImgs, true)) {
                $this->deleteImage($oldImg, 'services-offered');
            }
        }

        return $tabs;
    }

    /**
     * Build "Recognition" certificates JSON [{ image, alt }].
     */
    private function buildCertificates(Request $request, array $existing)
    {
        $alts         = $request->input('certificate_alt', []);
        $existingImgs = $request->input('certificate_existing_image', []);
        $certs = [];

        foreach ($alts as $i => $alt) {
            $alt   = trim((string) $alt);
            $image = $existingImgs[$i] ?? null;

            $newFile = $request->file("certificate_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile, 'certificates');
                if (!empty($existingImgs[$i])) {
                    $this->deleteImage($existingImgs[$i], 'certificates');
                }
            }

            if ($alt === '' && !$image) {
                continue;
            }

            $certs[] = ['image' => $image, 'alt' => $alt];
        }

        $usedImgs = array_filter(array_column($certs, 'image'));
        foreach ($existing as $old) {
            $oldImg = $old['image'] ?? null;
            if ($oldImg && !in_array($oldImg, $usedImgs, true)) {
                $this->deleteImage($oldImg, 'certificates');
            }
        }

        return $certs;
    }

    private function uploadImage($file, $folder)
    {
        $destination = public_path('services/debenture-trustee-listed/' . $folder);

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'dtl_' . $folder . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName, $folder)
    {
        $path = public_path('services/debenture-trustee-listed/' . $folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
