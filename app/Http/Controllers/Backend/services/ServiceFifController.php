<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ServiceFifDetails;
use App\Models\ProductCategory;


class ServiceFifController extends Controller
{
    public function edit($productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceFifDetails::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        return view('backend.services.fif.manage', compact('product', 'page'));
    }

    public function update(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceFifDetails::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $request->validate($this->rules(), $this->messages());

        $data = [
            'product_id'               => $product->id,
            'banner_breadcrumb_parent' => $request->banner_breadcrumb_parent,
            'banner_breadcrumb_child'  => $request->banner_breadcrumb_child,
            'intro_subheading'         => $request->intro_subheading,
            'intro_description'        => $request->intro_description,
            'definition_description'   => $request->definition_description,
            'process_heading'          => $request->process_heading,
            'process_tabs'             => $this->buildTabs($request, $page->process_tabs ?? []),
            'tax_intro'                => $request->tax_intro,
            'tax_table_html'           => $request->tax_table_html,
            'family_heading'           => $request->family_heading,
            'family_description'       => $request->family_description,
            'capabilities_heading'     => $request->capabilities_heading,
            'capabilities_points'      => $request->capabilities_points,
            'capabilities_disclaimer'  => $request->capabilities_disclaimer,
        ];

        foreach ($this->imageFields() as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($page && $page->$field) {
                    $this->deleteImage($page->$field, $folder);
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
            ServiceFifDetails::create($data);
        }

        return redirect()->route('product-category.index')->with('message', 'Page saved successfully!');
    }

    /* ------------------------------------------------------------------ */

    private function imageFields()
    {
        return [
            'banner_background_image' => 'banner',
            'intro_image'             => 'intro',
            'definition_image'        => 'definition',
            'family_image'            => 'family',
            'capabilities_image'      => 'capabilities',
        ];
    }

    private function rules()
    {
        return [
            'banner_background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'intro_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'definition_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'family_image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'capabilities_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'tab_image.*'             => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:8192',
        ];
    }

    private function messages()
    {
        return [
            'banner_background_image.max' => 'The banner image must not be larger than 8MB.',
        ];
    }

    /** Definition cards JSON [{ heading, content }]. */
    private function buildCards(Request $request)
    {
        $headings = $request->input('def_heading', []);
        $contents = $request->input('def_content', []);
        $cards = [];

        foreach ($headings as $i => $h) {
            $h = trim((string) $h);
            $c = trim((string) ($contents[$i] ?? ''));
            if ($h === '' && $c === '') {
                continue;
            }
            $cards[] = ['heading' => $h, 'content' => $c];
        }

        return $cards;
    }

    /** Process tabs JSON [{ title, image, points }]. */
    private function buildTabs(Request $request, array $existing = [])
    {
        $titles        = $request->input('tab_title', []);
        $points        = $request->input('tab_points', []);
        $existingImages = $request->input('tab_existing_image', []);
        $tabs = [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $pts   = trim((string) ($points[$i] ?? ''));
            $image = $existingImages[$i] ?? null;

            $newFile = $request->file("tab_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile, 'process');
                if (!empty($existingImages[$i])) {
                    $this->deleteImage($existingImages[$i], 'process');
                }
            }

            if ($title === '' && $pts === '' && !$image) {
                continue;
            }
            $tabs[] = ['title' => $title, 'image' => $image, 'points' => $pts];
        }

        // remove image files of tabs that were deleted
        $usedImages = array_filter(array_column($tabs, 'image'));
        foreach ($existing as $old) {
            $oldImg = $old['image'] ?? null;
            if ($oldImg && !in_array($oldImg, $usedImages, true)) {
                $this->deleteImage($oldImg, 'process');
            }
        }

        return $tabs;
    }

    private function uploadImage($file, $folder)
    {
        $destination = public_path('services/fif/' . $folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }
        $fileName = 'fif_' . $folder . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName, $folder)
    {
        $path = public_path('services/fif/' . $folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
