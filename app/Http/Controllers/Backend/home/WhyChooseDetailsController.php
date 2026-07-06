<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\WhyChooseDetails;


class WhyChooseDetailsController extends Controller
{
    /**
     * Allowed icon extensions (SVG plus common raster formats).
     */
    private const ICON_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $why_choose = WhyChooseDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.why-choose-details.index', compact('why_choose'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.why-choose-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $items = $this->buildItems($request, []);

        WhyChooseDetails::create([
            'heading'    => $request->heading,
            'items'      => $items,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('why-choose-details.index')->with('message', 'Why Choose section added successfully!');
    }

    public function edit($id)
    {
        $why_choose = WhyChooseDetails::findOrFail($id);

        return view('backend.home-page.why-choose-details.edit', compact('why_choose'));
    }

    public function update(Request $request, $id)
    {
        $why_choose = WhyChooseDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingItems = $why_choose->items ?? [];
        $items = $this->buildItems($request, $existingItems);

        $this->cleanupUnusedIcons($existingItems, $items);

        $why_choose->update([
            'heading'     => $request->heading,
            'items'       => $items,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('why-choose-details.index')->with('message', 'Why Choose section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $why_choose = WhyChooseDetails::findOrFail($id);
            $why_choose->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('why-choose-details.index')->with('message', 'Why Choose section deleted successfully!');
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
            'heading'          => 'required|string|max:255',
            'item_text'        => 'nullable|array',
            'item_text.*'      => 'nullable|string',
            'item_icon_svg.*'  => 'nullable|string',
            'item_icon.*'      => ['nullable', 'file', 'max:2048', $this->iconExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'heading.required'  => 'The Heading is required.',
            'item_icon.*.max'   => 'Each icon must not be larger than 2MB.',
        ];
    }

    /**
     * Build the items JSON array from the repeater rows.
     * A newly uploaded icon replaces the row's existing icon; otherwise the
     * existing icon (submitted as a hidden field) is preserved. Rows that are
     * completely empty are skipped.
     */
    private function buildItems(Request $request, array $existingItems)
    {
        $texts         = $request->input('item_text', []);
        $iconSvgs      = $request->input('item_icon_svg', []);
        $existingIcons = $request->input('item_existing_icon', []);

        $items = [];

        foreach ($texts as $i => $text) {
            $text    = trim((string) $text);
            $iconSvg = trim((string) ($iconSvgs[$i] ?? ''));
            $existingIcon = $existingIcons[$i] ?? null;

            $icon = $existingIcon;
            $newFile = $request->file("item_icon.$i");
            if ($newFile && $newFile->isValid()) {
                $icon = $this->uploadIcon($newFile);

                if ($existingIcon) {
                    $this->deleteIcon($existingIcon);
                }
            }

            // Skip fully empty rows
            if ($text === '' && !$icon && $iconSvg === '') {
                continue;
            }

            $items[] = [
                'icon'     => $icon,
                'icon_svg' => $iconSvg !== '' ? $iconSvg : null,
                'text'     => $text,
            ];
        }

        return $items;
    }

    private function cleanupUnusedIcons(array $oldItems, array $newItems)
    {
        $newIcons = array_filter(array_column($newItems, 'icon'));

        foreach ($oldItems as $old) {
            $oldIcon = $old['icon'] ?? null;
            if ($oldIcon && !in_array($oldIcon, $newIcons, true)) {
                $this->deleteIcon($oldIcon);
            }
        }
    }

    private function uploadIcon($file)
    {
        $destination = public_path('home/why-choose');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'why_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteIcon($fileName)
    {
        $path = public_path('home/why-choose/' . $fileName);
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
