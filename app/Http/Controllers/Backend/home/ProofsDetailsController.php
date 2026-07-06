<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ProofsDetails;


class ProofsDetailsController extends Controller
{
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $proofs = ProofsDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.proofs-details.index', compact('proofs'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.proofs-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $items = $this->buildItems($request, []);

        ProofsDetails::create([
            'heading'    => $request->heading,
            'items'      => $items,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('proofs-details.index')->with('message', 'Proofs section added successfully!');
    }

    public function edit($id)
    {
        $proofs = ProofsDetails::findOrFail($id);

        return view('backend.home-page.proofs-details.edit', compact('proofs'));
    }

    public function update(Request $request, $id)
    {
        $proofs = ProofsDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingItems = $proofs->items ?? [];
        $items = $this->buildItems($request, $existingItems);

        $this->cleanupUnusedImages($existingItems, $items);

        $proofs->update([
            'heading'     => $request->heading,
            'items'       => $items,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('proofs-details.index')->with('message', 'Proofs section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $proofs = ProofsDetails::findOrFail($id);
            $proofs->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('proofs-details.index')->with('message', 'Proofs section deleted successfully!');
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
            'heading'            => 'nullable|string|max:255',
            'item_text'          => 'nullable|array',
            'item_text.*'        => 'nullable|string',
            'item_icon_svg.*'    => 'nullable|string',
            'item_image.*'       => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
            'item_icon.*'        => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'item_image.*.max' => 'Each background image must not be larger than 2MB.',
            'item_icon.*.max'  => 'Each icon must not be larger than 2MB.',
        ];
    }

    /**
     * Build the items JSON array [{ image, icon, icon_svg, text }].
     */
    private function buildItems(Request $request, array $existingItems)
    {
        $texts         = $request->input('item_text', []);
        $iconSvgs      = $request->input('item_icon_svg', []);
        $existingImages = $request->input('item_existing_image', []);
        $existingIcons  = $request->input('item_existing_icon', []);

        $items = [];

        foreach ($texts as $i => $text) {
            $text    = trim((string) $text);
            $iconSvg = trim((string) ($iconSvgs[$i] ?? ''));

            $image = $this->resolveFile($request, "item_image.$i", $existingImages[$i] ?? null);
            $icon  = $this->resolveFile($request, "item_icon.$i", $existingIcons[$i] ?? null);

            // Skip fully empty rows
            if ($text === '' && !$image && !$icon && $iconSvg === '') {
                continue;
            }

            $items[] = [
                'image'    => $image,
                'icon'     => $icon,
                'icon_svg' => $iconSvg !== '' ? $iconSvg : null,
                'text'     => $text,
            ];
        }

        return $items;
    }

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
            if (!empty($item['image'])) $newFiles[] = $item['image'];
            if (!empty($item['icon']))  $newFiles[] = $item['icon'];
        }

        foreach ($oldItems as $old) {
            foreach (['image', 'icon'] as $key) {
                $file = $old[$key] ?? null;
                if ($file && !in_array($file, $newFiles, true)) {
                    $this->deleteImage($file);
                }
            }
        }
    }

    private function uploadImage($file)
    {
        $destination = public_path('home/proofs');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'proof_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/proofs/' . $fileName);
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
                $fail('The file must be of type: ' . implode(', ', self::IMAGE_EXTENSIONS) . '.');
            }
        };
    }
}
