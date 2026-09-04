<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ServiceLayout2Details;
use App\Models\ProductCategory;


class ServiceLayout2Controller extends Controller
{
    public function edit($productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceLayout2Details::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        return view('backend.services.layout2.manage', compact('product', 'page'));
    }

    public function update(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);
        $page = ServiceLayout2Details::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $request->validate($this->rules(), $this->messages());

        $data = [
            'product_id'               => $product->id,
            'banner_breadcrumb_parent' => $request->banner_breadcrumb_parent,
            'banner_breadcrumb_child'  => $request->banner_breadcrumb_child,
            'nature_heading'           => $request->nature_heading,
            'nature_description'       => $request->nature_description,
            'process_heading'          => $request->process_heading,
            'process_points'           => $request->process_points,
            'keyfacts_heading'         => $request->keyfacts_heading,
            'keyfacts_points'          => $request->keyfacts_points,
            'keyfacts_note'            => $request->keyfacts_note,
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
            ServiceLayout2Details::create($data);
        }

        return redirect()->route('product-category.index')->with('message', 'Page saved successfully!');
    }

    /* ------------------------------------------------------------------ */

    private function imageFields()
    {
        return [
            'banner_background_image' => 'banner',
            'nature_image'            => 'nature',
            'process_image'           => 'process',
            'keyfacts_image'          => 'keyfacts',
        ];
    }

    private function rules()
    {
        return [
            'banner_background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'nature_image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'process_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'keyfacts_image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ];
    }

    private function messages()
    {
        return [
            'banner_background_image.max' => 'The banner image must not be larger than 8MB.',
        ];
    }

    private function uploadImage($file, $folder)
    {
        $destination = public_path('services/layout2/' . $folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }
        $fileName = 'l2_' . $folder . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName, $folder)
    {
        $path = public_path('services/layout2/' . $folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
