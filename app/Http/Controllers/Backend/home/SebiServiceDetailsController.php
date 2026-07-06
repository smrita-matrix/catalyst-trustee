<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\SebiServiceDetails;


class SebiServiceDetailsController extends Controller
{
    /**
     * Allowed image extensions (SVG plus common raster formats).
     */
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $sebi_services = SebiServiceDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.sebi-service-details.index', compact('sebi_services'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.sebi-service-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $items = $this->buildItems($request, []);

        SebiServiceDetails::create([
            'heading'    => $request->heading,
            'items'      => $items,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('sebi-service-details.index')->with('message', 'SEBI Services section added successfully!');
    }

    public function edit($id)
    {
        $sebi_service = SebiServiceDetails::findOrFail($id);

        return view('backend.home-page.sebi-service-details.edit', compact('sebi_service'));
    }

    public function update(Request $request, $id)
    {
        $sebi_service = SebiServiceDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingItems = $sebi_service->items ?? [];
        $items = $this->buildItems($request, $existingItems);

        $this->cleanupUnusedImages($existingItems, $items);

        $sebi_service->update([
            'heading'     => $request->heading,
            'items'       => $items,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('sebi-service-details.index')->with('message', 'SEBI Services section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $sebi_service = SebiServiceDetails::findOrFail($id);
            $sebi_service->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('sebi-service-details.index')->with('message', 'SEBI Services section deleted successfully!');
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
            'heading'               => 'required|string|max:255',
            'item_title'            => 'nullable|array',
            'item_title.*'          => 'nullable|string|max:255',
            'item_title_link.*'     => 'nullable|string|max:255',
            'item_description.*'    => 'nullable|string',
            'item_read_more_link.*' => 'nullable|string|max:255',
            'item_service_img.*'    => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
            'item_icon.*'           => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'heading.required'       => 'The Heading is required.',
            'item_service_img.*.max' => 'Each service image must not be larger than 2MB.',
            'item_icon.*.max'        => 'Each icon must not be larger than 2MB.',
        ];
    }

    /**
     * Build the items JSON array from the repeater blocks. Each block has two
     * images (service image + icon); a newly uploaded file replaces the row's
     * existing one, otherwise the existing filename (hidden field) is kept.
     * Rows that are completely empty are skipped.
     */
    private function buildItems(Request $request, array $existingItems)
    {
        $titles        = $request->input('item_title', []);
        $titleLinks    = $request->input('item_title_link', []);
        $descriptions  = $request->input('item_description', []);
        $readMoreLinks = $request->input('item_read_more_link', []);
        $existingServiceImgs = $request->input('item_existing_service_img', []);
        $existingIcons       = $request->input('item_existing_icon', []);

        $items = [];

        foreach ($titles as $i => $title) {
            $title        = trim((string) $title);
            $titleLink    = trim((string) ($titleLinks[$i] ?? ''));
            $description  = trim((string) ($descriptions[$i] ?? ''));
            $readMoreLink = trim((string) ($readMoreLinks[$i] ?? ''));

            $serviceImg = $this->resolveFile($request, "item_service_img.$i", $existingServiceImgs[$i] ?? null);
            $icon       = $this->resolveFile($request, "item_icon.$i", $existingIcons[$i] ?? null);

            // Skip fully empty rows
            if ($title === '' && $description === '' && !$serviceImg && !$icon && $titleLink === '' && $readMoreLink === '') {
                continue;
            }

            $items[] = [
                'service_img'    => $serviceImg,
                'icon'           => $icon,
                'title'          => $title,
                'title_link'     => $titleLink,
                'description'    => $description,
                'read_more_link' => $readMoreLink,
            ];
        }

        return $items;
    }

    /**
     * Return the stored filename for a file field: uploads a new file (deleting
     * the old one) or falls back to the existing filename.
     */
    private function resolveFile(Request $request, $inputKey, $existing)
    {
        $file = $request->file($inputKey);
        if ($file && $file->isValid()) {
            if ($existing) {
                $this->deleteImage($existing);
            }
            return $this->uploadImage($file);
        }
        return $existing;
    }

    private function cleanupUnusedImages(array $oldItems, array $newItems)
    {
        $newFiles = [];
        foreach ($newItems as $item) {
            if (!empty($item['service_img'])) $newFiles[] = $item['service_img'];
            if (!empty($item['icon']))        $newFiles[] = $item['icon'];
        }

        foreach ($oldItems as $old) {
            foreach (['service_img', 'icon'] as $key) {
                $file = $old[$key] ?? null;
                if ($file && !in_array($file, $newFiles, true)) {
                    $this->deleteImage($file);
                }
            }
        }
    }

    private function uploadImage($file)
    {
        $destination = public_path('home/sebi-services');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'sebi_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/sebi-services/' . $fileName);
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
