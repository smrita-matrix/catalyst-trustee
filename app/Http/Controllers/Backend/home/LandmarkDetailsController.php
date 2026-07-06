<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\LandmarkDetails;


class LandmarkDetailsController extends Controller
{
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $landmark = LandmarkDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.landmark-details.index', compact('landmark'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.landmark-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $items = $this->buildItems($request, []);

        LandmarkDetails::create([
            'heading'    => $request->heading,
            'items'      => $items,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('landmark-details.index')->with('message', 'Landmark Transactions section added successfully!');
    }

    public function edit($id)
    {
        $landmark = LandmarkDetails::findOrFail($id);

        return view('backend.home-page.landmark-details.edit', compact('landmark'));
    }

    public function update(Request $request, $id)
    {
        $landmark = LandmarkDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingItems = $landmark->items ?? [];
        $items = $this->buildItems($request, $existingItems);

        $this->cleanupUnusedImages($existingItems, $items);

        $landmark->update([
            'heading'     => $request->heading,
            'items'       => $items,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('landmark-details.index')->with('message', 'Landmark Transactions section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $landmark = LandmarkDetails::findOrFail($id);
            $landmark->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('landmark-details.index')->with('message', 'Landmark Transactions section deleted successfully!');
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
            'item_title'         => 'nullable|array',
            'item_title.*'       => 'nullable|string|max:255',
            'item_description.*' => 'nullable|string',
            'item_link.*'        => 'nullable|string|max:255',
            'item_image.*'       => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'heading.required' => 'The Heading is required.',
            'item_image.*.max' => 'Each image must not be larger than 2MB.',
        ];
    }

    private function buildItems(Request $request, array $existingItems)
    {
        $titles        = $request->input('item_title', []);
        $descriptions  = $request->input('item_description', []);
        $links         = $request->input('item_link', []);
        $existingImages = $request->input('item_existing_image', []);

        $items = [];

        foreach ($titles as $i => $title) {
            $title       = trim((string) $title);
            $description = trim((string) ($descriptions[$i] ?? ''));
            $link        = trim((string) ($links[$i] ?? ''));
            $existingImage = $existingImages[$i] ?? null;

            $image = $existingImage;
            $newFile = $request->file("item_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile);
                if ($existingImage) {
                    $this->deleteImage($existingImage);
                }
            }

            if ($title === '' && $description === '' && !$image && $link === '') {
                continue;
            }

            $items[] = [
                'image'       => $image,
                'title'       => $title,
                'description' => $description,
                'link'        => $link,
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
        $destination = public_path('home/landmark');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'landmark_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/landmark/' . $fileName);
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
