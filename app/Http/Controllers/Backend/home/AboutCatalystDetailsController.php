<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\AboutCatalystDetails;


class AboutCatalystDetailsController extends Controller
{
    /**
     * Allowed feature-icon extensions (SVG plus common raster formats).
     */
    private const ICON_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $about_catalyst = AboutCatalystDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.about-catalyst-details.index', compact('about_catalyst'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.about-catalyst-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $features = $this->buildFeatures($request, []);

        AboutCatalystDetails::create([
            'sub_heading' => $request->sub_heading,
            'heading'     => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'features'    => $features,
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('about-catalyst-details.index')->with('message', 'About Catalyst added successfully!');
    }

    public function edit($id)
    {
        $about_catalyst = AboutCatalystDetails::findOrFail($id);

        return view('backend.home-page.about-catalyst-details.edit', compact('about_catalyst'));
    }

    public function update(Request $request, $id)
    {
        $about_catalyst = AboutCatalystDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingFeatures = $about_catalyst->features ?? [];
        $features = $this->buildFeatures($request, $existingFeatures);

        // Remove icon files that are no longer referenced
        $this->cleanupUnusedIcons($existingFeatures, $features);

        $about_catalyst->update([
            'sub_heading' => $request->sub_heading,
            'heading'     => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'features'    => $features,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('about-catalyst-details.index')->with('message', 'About Catalyst has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $about_catalyst = AboutCatalystDetails::findOrFail($id);
            $about_catalyst->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('about-catalyst-details.index')->with('message', 'About Catalyst deleted successfully!');
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
            'sub_heading'             => 'required|string|max:255',
            'heading'                 => 'required|string',
            'description'             => 'required|string',
            'button_text'             => 'required|string|max:100',
            'button_link'             => 'required|string|max:255',
            'feature_title'           => 'nullable|array',
            'feature_title.*'         => 'nullable|string|max:255',
            'feature_description.*'   => 'nullable|string',
            'feature_icon_svg.*'      => 'nullable|string',
            'feature_icon.*'          => ['nullable', 'file', 'max:2048', $this->iconExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'sub_heading.required' => 'The Sub Heading is required.',
            'heading.required'     => 'The Heading is required.',
            'description.required' => 'The Description is required.',
            'button_text.required' => 'The Button Text is required.',
            'button_link.required' => 'The Button Link is required.',
            'feature_icon.*.max'   => 'Each feature icon must not be larger than 2MB.',
        ];
    }

    /**
     * Build the features JSON array from the repeater rows.
     * A newly uploaded icon replaces the row's existing icon; otherwise the
     * existing icon (submitted as a hidden field) is preserved. Rows that are
     * completely empty are skipped.
     */
    private function buildFeatures(Request $request, array $existingFeatures)
    {
        $titles       = $request->input('feature_title', []);
        $descriptions = $request->input('feature_description', []);
        $iconSvgs     = $request->input('feature_icon_svg', []);
        $existingIcons = $request->input('feature_existing_icon', []);

        $features = [];

        foreach ($titles as $i => $title) {
            $title       = trim((string) $title);
            $description = trim((string) ($descriptions[$i] ?? ''));
            $iconSvg     = trim((string) ($iconSvgs[$i] ?? ''));
            $existingIcon = $existingIcons[$i] ?? null;

            $icon = $existingIcon;
            $newFile = $request->file("feature_icon.$i");
            if ($newFile && $newFile->isValid()) {
                $icon = $this->uploadIcon($newFile);

                // Delete the icon this row previously used, if any
                if ($existingIcon) {
                    $this->deleteIcon($existingIcon);
                }
            }

            // Skip fully empty rows
            if ($title === '' && $description === '' && !$icon && $iconSvg === '') {
                continue;
            }

            $features[] = [
                'icon'        => $icon,
                'icon_svg'    => $iconSvg !== '' ? $iconSvg : null,
                'title'       => $title,
                'description' => $description,
            ];
        }

        return $features;
    }

    /**
     * Delete icon files present in the old set but absent from the new set.
     */
    private function cleanupUnusedIcons(array $oldFeatures, array $newFeatures)
    {
        $newIcons = array_filter(array_column($newFeatures, 'icon'));

        foreach ($oldFeatures as $old) {
            $oldIcon = $old['icon'] ?? null;
            if ($oldIcon && !in_array($oldIcon, $newIcons, true)) {
                $this->deleteIcon($oldIcon);
            }
        }
    }

    private function uploadIcon($file)
    {
        $destination = public_path('home/about-catalyst');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'feature_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteIcon($fileName)
    {
        $path = public_path('home/about-catalyst/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function iconExtensionRule()
    {
        return function ($attribute, $value, $fail) {
            if (!$value) {
                return;
            }

            $ext = strtolower($value->getClientOriginalExtension());
            if (!in_array($ext, self::ICON_EXTENSIONS, true)) {
                $fail('The icon must be a file of type: ' . implode(', ', self::ICON_EXTENSIONS) . '.');
            }
        };
    }
}
