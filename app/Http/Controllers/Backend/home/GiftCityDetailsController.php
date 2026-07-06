<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\GiftCityDetails;


class GiftCityDetailsController extends Controller
{
    /**
     * Allowed image extensions (SVG plus common raster formats).
     */
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $gift_city = GiftCityDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.gift-city-details.index', compact('gift_city'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.gift-city-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $items = $this->buildItems($request, []);

        GiftCityDetails::create([
            'heading'     => $request->heading,
            'footer_text' => $request->footer_text,
            'items'       => $items,
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('gift-city-details.index')->with('message', 'GIFT City section added successfully!');
    }

    public function edit($id)
    {
        $gift_city = GiftCityDetails::findOrFail($id);

        return view('backend.home-page.gift-city-details.edit', compact('gift_city'));
    }

    public function update(Request $request, $id)
    {
        $gift_city = GiftCityDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingItems = $gift_city->items ?? [];
        $items = $this->buildItems($request, $existingItems);

        $this->cleanupUnusedImages($existingItems, $items);

        $gift_city->update([
            'heading'     => $request->heading,
            'footer_text' => $request->footer_text,
            'items'       => $items,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('gift-city-details.index')->with('message', 'GIFT City section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $gift_city = GiftCityDetails::findOrFail($id);
            $gift_city->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('gift-city-details.index')->with('message', 'GIFT City section deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function rules()
    {
        return [
            'heading'            => 'required|string|max:255',
            'footer_text'        => 'nullable|string',
            'item_title'         => 'nullable|array',
            'item_title.*'       => 'nullable|string|max:255',
            'item_title_link.*'  => 'nullable|string|max:255',
            'item_description.*'  => 'nullable|string',
            'item_image.*'       => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'heading.required'  => 'The Heading is required.',
            'item_image.*.max'  => 'Each image must not be larger than 2MB.',
        ];
    }

    /**
     * Build the items JSON array from the repeater rows. A newly uploaded image
     * replaces the row's existing one; otherwise the existing filename (hidden
     * field) is kept. Rows that are completely empty are skipped.
     */
    private function buildItems(Request $request, array $existingItems)
    {
        $titles       = $request->input('item_title', []);
        $titleLinks   = $request->input('item_title_link', []);
        $descriptions = $request->input('item_description', []);
        $existingImages = $request->input('item_existing_image', []);

        $items = [];

        foreach ($titles as $i => $title) {
            $title       = trim((string) $title);
            $titleLink   = trim((string) ($titleLinks[$i] ?? ''));
            $description = trim((string) ($descriptions[$i] ?? ''));
            $existingImage = $existingImages[$i] ?? null;

            $image = $existingImage;
            $newFile = $request->file("item_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile);

                if ($existingImage) {
                    $this->deleteImage($existingImage);
                }
            }

            // Skip fully empty rows
            if ($title === '' && $description === '' && !$image && $titleLink === '') {
                continue;
            }

            $items[] = [
                'image'       => $image,
                'title'       => $title,
                'title_link'  => $titleLink,
                'description' => $description,
            ];
        }

        return $items;
    }

    private function cleanupUnusedImages(array $oldItems, array $newItems)
    {
        $newImages = array_filter(array_column($newItems, 'image'));

        foreach ($oldItems as $old) {
            $oldImage = $old['image'] ?? null;
            if ($oldImage && !in_array($oldImage, $newImages, true)) {
                $this->deleteImage($oldImage);
            }
        }
    }

    private function uploadImage($file)
    {
        $destination = public_path('home/gift-city');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'gift_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/gift-city/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function imageExtensionRule()
    {
        return function ($attribute, $value, $fail) {
            if (!$value) {
                return;
            }

            $ext = strtolower($value->getClientOriginalExtension());
            if (!in_array($ext, self::IMAGE_EXTENSIONS, true)) {
                $fail('The image must be a file of type: ' . implode(', ', self::IMAGE_EXTENSIONS) . '.');
            }
        };
    }
}
