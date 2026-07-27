<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ServiceLayout3Details;
use App\Models\ProductCategory;


class ServiceLayout3Controller extends Controller
{
    public function edit($productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceLayout3Details::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        return view('backend.services.layout3.manage', compact('product', 'page'));
    }

    public function update(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceLayout3Details::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $request->validate($this->rules(), $this->messages());

        $data = [
            'product_id'               => $product->id,
            'banner_breadcrumb_parent' => $request->banner_breadcrumb_parent,
            'banner_breadcrumb_child'  => $request->banner_breadcrumb_child,
            'intro_heading'            => $request->intro_heading,
            'intro_description'        => $request->intro_description,
            'services_divider_label'   => $request->services_divider_label,
            'services_tabs'            => $this->buildTabs($request, $page->services_tabs ?? []),
            'benefits_heading'         => $request->benefits_heading,
            'benefits_points'          => $request->benefits_points,
            'benefits_note'            => $request->benefits_note,
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
            ServiceLayout3Details::create($data);
        }

        return redirect()->route('product-category.index')->with('message', 'Page saved successfully!');
    }

    /* ------------------------------------------------------------------ */

    private function imageFields()
    {
        return [
            'banner_background_image' => 'banner',
            'intro_image'             => 'intro',
            'benefits_image'          => 'benefits',
        ];
    }

    private function rules()
    {
        return [
            'banner_background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'intro_image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'benefits_image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tab_icon.*'              => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ];
    }

    private function messages()
    {
        return [
            'banner_background_image.max' => 'The banner image must not be larger than 2MB.',
            'intro_image.max'             => 'The intro image must not be larger than 2MB.',
            'benefits_image.max'          => 'The image must not be larger than 2MB.',
        ];
    }

    /**
     * Build the services tabs JSON [{ icon, title, description, points }].
     */
    private function buildTabs(Request $request, array $existing)
    {
        $titles       = $request->input('tab_title', []);
        $descriptions = $request->input('tab_description', []);
        $points       = $request->input('tab_points', []);
        $existingIcons = $request->input('tab_existing_icon', []);
        $tabs = [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $desc  = trim((string) ($descriptions[$i] ?? ''));
            $pts   = trim((string) ($points[$i] ?? ''));
            $icon  = $existingIcons[$i] ?? null;

            $newFile = $request->file("tab_icon.$i");
            if ($newFile && $newFile->isValid()) {
                $icon = $this->uploadImage($newFile, 'services');
                if (!empty($existingIcons[$i])) {
                    $this->deleteImage($existingIcons[$i], 'services');
                }
            }

            if ($title === '' && $desc === '' && $pts === '' && !$icon) {
                continue;
            }

            $tabs[] = ['icon' => $icon, 'title' => $title, 'description' => $desc, 'points' => $pts];
        }

        $usedIcons = array_filter(array_column($tabs, 'icon'));
        foreach ($existing as $old) {
            $oldIcon = $old['icon'] ?? null;
            if ($oldIcon && !in_array($oldIcon, $usedIcons, true)) {
                $this->deleteImage($oldIcon, 'services');
            }
        }

        return $tabs;
    }

    private function uploadImage($file, $folder)
    {
        $destination = public_path('services/layout3/' . $folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }
        $fileName = 'l3_' . $folder . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName, $folder)
    {
        $path = public_path('services/layout3/' . $folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
