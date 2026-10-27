<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use App\Models\GlobalPresenceDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Home page > Global Presence.
 *
 * The section is a single strip, so there is one screen to edit it rather
 * than a list of records - saving creates the row the first time.
 */
class GlobalPresenceDetailsController extends Controller
{
    private const FOLDER = 'home/global-presence';
    private const ICON_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function edit()
    {
        $presence = GlobalPresenceDetails::whereNull('deleted_at')->latest('id')->first()
            ?? new GlobalPresenceDetails(['status' => 1, 'items' => []]);

        return view('backend.home-page.global-presence-details.edit', compact('presence'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'eyebrow'          => 'nullable|string|max:255',
            'heading'          => 'required|string|max:255',
            'description'      => 'nullable|string|max:1000',
            'button_text'      => 'nullable|string|max:255',
            'button_link'      => 'nullable|string|max:500',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'item_name'        => 'nullable|array',
            'item_name.*'      => 'nullable|string|max:255',
            'item_link.*'      => 'nullable|string|max:500',
            'item_icon.*'      => ['nullable', 'file', 'max:4096', $this->iconRule()],
        ], [
            'heading.required' => 'The Heading is required.',
        ]);

        $presence = GlobalPresenceDetails::whereNull('deleted_at')->latest('id')->first();

        $oldItems = $presence->items ?? [];
        $items    = $this->buildItems($request);

        $data = [
            'eyebrow'     => $request->eyebrow,
            'heading'     => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'items'       => $items,
            'status'      => $request->has('status') ? 1 : 0,
        ];

        if ($request->hasFile('background_image')) {
            if ($presence && $presence->background_image) {
                $this->deleteFile($presence->background_image);
            }
            $data['background_image'] = $this->upload($request->file('background_image'), 'gp_bg');
        } elseif ($request->boolean('remove_background') && $presence && $presence->background_image) {
            // Back to the dotted world map.
            $this->deleteFile($presence->background_image);
            $data['background_image'] = null;
        }

        if ($presence) {
            $presence->update($data + ['modified_at' => Carbon::now(), 'modified_by' => Auth::id()]);
        } else {
            GlobalPresenceDetails::create($data + ['created_at' => Carbon::now(), 'created_by' => Auth::id()]);
        }

        // Icons no row uses any more.
        $kept = array_filter(array_column($items, 'icon'));
        foreach ($oldItems as $old) {
            if (!empty($old['icon']) && !in_array($old['icon'], $kept, true)) {
                $this->deleteFile($old['icon']);
            }
        }

        return redirect()->route('global-presence-details.edit')->with('message', 'Global Presence section saved successfully!');
    }

    /** Rows from the locations table; a new upload replaces the row's icon. */
    private function buildItems(Request $request)
    {
        $names    = $request->input('item_name', []);
        $links    = $request->input('item_link', []);
        $existing = $request->input('item_existing_icon', []);

        $items = [];
        foreach ($names as $i => $name) {
            $name = trim((string) $name);
            $icon = $existing[$i] ?? null;

            $file = $request->file("item_icon.$i");
            if ($file && $file->isValid()) {
                $icon = $this->upload($file, 'gp_icon');
            }

            if ($name === '' && !$icon) {
                continue; // an empty row
            }

            $items[] = [
                'icon' => $icon ?: null,
                'name' => $name,
                'link' => trim((string) ($links[$i] ?? '')) ?: null,
            ];
        }

        return $items;
    }

    private function upload($file, $prefix)
    {
        $destination = public_path(self::FOLDER);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $name = $prefix . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $name);

        return $name;
    }

    private function deleteFile($name)
    {
        $path = public_path(self::FOLDER . '/' . $name);
        if ($name && is_file($path)) {
            @unlink($path);
        }
    }

    private function iconRule()
    {
        return function ($attribute, $value, $fail) {
            if ($value && !in_array(strtolower($value->getClientOriginalExtension()), self::ICON_EXTENSIONS, true)) {
                $fail('Each icon must be a file of type: ' . implode(', ', self::ICON_EXTENSIONS) . '.');
            }
        };
    }
}
