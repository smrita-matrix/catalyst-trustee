<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ServiceSecuritisationDetails;
use App\Models\ProductCategory;

/**
 * The editor behind the two Securitisation pages.
 *
 * The page is a run of bands, each of which disappears when it is left
 * empty, so one form covers both the Listed and the Unlisted page and a
 * third could be set up without any new code.
 */
class ServiceSecuritisationController extends Controller
{
    public function edit($productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceSecuritisationDetails::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        return view('backend.services.securitisation.manage', compact('product', 'page'));
    }

    public function update(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceSecuritisationDetails::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $request->validate($this->rules(), $this->messages());

        $data = [
            'product_id'               => $product->id,
            'banner_title'             => $request->banner_title,
            'banner_breadcrumb_parent' => $request->banner_breadcrumb_parent,
            'banner_breadcrumb_child'  => $request->banner_breadcrumb_child,

            'panel_image_side'         => $this->side($request->panel_image_side),
            'panel_blocks'             => $this->buildPanelBlocks($request),
            'panel_points'             => $request->panel_points,

            'glance_heading'           => $request->glance_heading,
            'glance_cards'             => $this->buildGlance($request, $page),

            'capabilities_heading'     => $request->capabilities_heading,
            'capability_tabs'          => $this->buildTabs($request, $page),

            'lifecycle_heading'        => $request->lifecycle_heading,
            'lifecycle_steps'          => $this->buildLifecycle($request),
        ];

        foreach (ServiceSecuritisationDetails::BANDS as $band) {
            $heading     = $request->input($band . '_heading');
            $subheading  = $request->input($band . '_subheading');
            $description = $request->input($band . '_description');

            $data[$band . '_heading']     = $heading;
            $data[$band . '_subheading']  = $subheading;
            $data[$band . '_description'] = $description;

            // A band nobody has filled in keeps nothing at all, so saving the
            // form does not quietly write settings for a band that is not there.
            $used = trim(strip_tags((string) $heading . $subheading . $description)) !== ''
                    || $request->hasFile($band . '_image')
                    || ($page && $page->{$band . '_image'});

            $data[$band . '_image_side'] = $used ? $this->side($request->input($band . '_image_side')) : null;
            $data[$band . '_background'] = $used
                ? ($request->input($band . '_background') === 'white' ? 'white' : 'tint')
                : null;
        }

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
            ServiceSecuritisationDetails::create($data);
        }

        return redirect()->route('product-category.index')->with('message', 'Page saved successfully!');
    }

    /* ------------------------------------------------------------------ */

    /** Which side a picture sits on; anything unrecognised means the left. */
    private function side(?string $value): string
    {
        return $value === 'right' ? 'right' : 'left';
    }

    private function imageFields(): array
    {
        return [
            'banner_background_image' => 'banner',
            'intro_image'             => 'intro',
            'business_image'          => 'business',
            'closing_image'           => 'closing',
            'panel_image'             => 'panel',
        ];
    }

    /** The headed paragraphs on the panel: [{ heading, description }]. */
    private function buildPanelBlocks(Request $request): array
    {
        $headings     = $request->input('panel_block_heading', []);
        $descriptions = $request->input('panel_block_description', []);

        $blocks = [];
        foreach ($headings as $i => $heading) {
            $heading     = trim((string) $heading);
            $description = trim((string) ($descriptions[$i] ?? ''));

            if ($heading === '' && strip_tags($description) === '') { continue; }

            $blocks[] = ['heading' => $heading, 'description' => $description];
        }

        return $blocks;
    }

    /** The figures at a glance: [{ icon, value, label }]. */
    private function buildGlance(Request $request, $page): array
    {
        $values   = $request->input('glance_value', []);
        $labels   = $request->input('glance_label', []);
        $existing = $request->input('glance_existing_icon', []);

        $cards = [];
        foreach ($values as $i => $value) {
            $value = trim((string) $value);
            $label = trim((string) ($labels[$i] ?? ''));
            $icon  = $existing[$i] ?? null;

            $file = $request->file("glance_icon.$i");
            if ($file && $file->isValid()) {
                $icon = $this->uploadImage($file, 'glance');
                if (!empty($existing[$i])) { $this->deleteImage($existing[$i], 'glance'); }
            }

            if ($value === '' && $label === '' && !$icon) { continue; }

            $cards[] = ['icon' => $icon, 'value' => $value, 'label' => $label];
        }

        $this->sweep($page, 'glance_cards', 'icon', collect($cards)->pluck('icon'), 'glance');

        return $cards;
    }

    /** The tabbed capabilities: [{ icon, title, description, points, flow }]. */
    private function buildTabs(Request $request, $page): array
    {
        $titles       = $request->input('tab_title', []);
        $descriptions = $request->input('tab_description', []);
        $points       = $request->input('tab_points', []);
        $flows        = $request->input('tab_flow', []);
        $notes        = $request->input('tab_note', []);
        $existing     = $request->input('tab_existing_icon', []);

        $tabs = [];
        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $icon  = $existing[$i] ?? null;

            $file = $request->file("tab_icon.$i");
            if ($file && $file->isValid()) {
                $icon = $this->uploadImage($file, 'capabilities');
                if (!empty($existing[$i])) { $this->deleteImage($existing[$i], 'capabilities'); }
            }

            if ($title === '') { continue; }

            $tabs[] = [
                'icon'        => $icon,
                'title'       => $title,
                'description' => trim((string) ($descriptions[$i] ?? '')),
                'points'      => trim((string) ($points[$i] ?? '')),
                'flow'        => trim((string) ($flows[$i] ?? '')),
                'note'        => trim((string) ($notes[$i] ?? '')),
            ];
        }

        $this->sweep($page, 'capability_tabs', 'icon', collect($tabs)->pluck('icon'), 'capabilities');

        return $tabs;
    }

    /** The lifecycle steps: [{ icon, title }]. The icon is a Font Awesome name. */
    private function buildLifecycle(Request $request): array
    {
        $titles = $request->input('lifecycle_title', []);
        $icons  = $request->input('lifecycle_icon', []);

        $steps = [];
        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            if ($title === '') { continue; }

            $steps[] = ['icon' => trim((string) ($icons[$i] ?? '')), 'title' => $title];
        }

        return $steps;
    }

    /**
     * Delete the pictures of rows that have just been removed.
     *
     * Without this a picture would sit in the uploads folder for good once
     * its row was taken out of the form.
     */
    private function sweep($page, string $field, string $key, $keeping, string $folder): void
    {
        if (!$page) { return; }

        foreach ((array) ($page->$field ?? []) as $row) {
            $name = $row[$key] ?? null;
            if ($name && !$keeping->contains($name)) {
                $this->deleteImage($name, $folder);
            }
        }
    }

    private function rules(): array
    {
        $rules = [];

        foreach (array_keys($this->imageFields()) as $field) {
            $rules[$field] = 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:8192';
        }

        $rules['glance_icon.*'] = 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:8192';
        $rules['tab_icon.*']    = 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:8192';

        return $rules;
    }

    private function messages(): array
    {
        return [
            'banner_background_image.max' => 'The banner image must not be larger than 8MB.',
        ];
    }

    private function uploadImage($file, $folder): string
    {
        $destination = public_path('service-uploads/securitisation/' . $folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'sec_' . $folder . '_' . time() . '_' . Str::random(8) . '.'
                    . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName, $folder): void
    {
        $path = public_path('service-uploads/securitisation/' . $folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
