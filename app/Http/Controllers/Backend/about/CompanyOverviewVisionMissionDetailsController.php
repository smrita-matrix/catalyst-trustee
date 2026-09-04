<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\CompanyOverviewVisionMissionDetails;


class CompanyOverviewVisionMissionDetailsController extends Controller
{
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $vision_mission = CompanyOverviewVisionMissionDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.about-us.company-overview.vision-mission-details.index', compact('vision_mission'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.company-overview.vision-mission-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        CompanyOverviewVisionMissionDetails::create([
            'heading'    => $request->heading,
            'items'      => $this->buildItems($request, []),
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('company-overview-vision-mission-details.index')->with('message', 'Vision & Mission added successfully!');
    }

    public function edit($id)
    {
        $vision_mission = CompanyOverviewVisionMissionDetails::findOrFail($id);

        return view('backend.about-us.company-overview.vision-mission-details.edit', compact('vision_mission'));
    }

    public function update(Request $request, $id)
    {
        $vision_mission = CompanyOverviewVisionMissionDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingItems = $vision_mission->items ?? [];
        $items = $this->buildItems($request, $existingItems);

        $this->cleanupUnusedIcons($existingItems, $items);

        $vision_mission->update([
            'heading'     => $request->heading,
            'items'       => $items,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('company-overview-vision-mission-details.index')->with('message', 'Vision & Mission has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $vision_mission = CompanyOverviewVisionMissionDetails::findOrFail($id);
            $vision_mission->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('company-overview-vision-mission-details.index')->with('message', 'Vision & Mission deleted successfully!');
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
            'item_title'         => 'nullable|array',
            'item_title.*'       => 'nullable|string|max:255',
            'item_tag.*'         => 'nullable|string|max:100',
            'item_description.*' => 'nullable|string',
            'item_icon.*'        => ['nullable', 'file', 'max:8192', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'item_icon.*.max' => 'Each icon must not be larger than 8MB.',
        ];
    }

    /**
     * Build the items JSON array [{ tag, icon, title, description }].
     */
    private function buildItems(Request $request, array $existingItems)
    {
        $tags         = $request->input('item_tag', []);
        $titles       = $request->input('item_title', []);
        $descriptions = $request->input('item_description', []);
        $existingIcons = $request->input('item_existing_icon', []);

        $items = [];

        foreach ($titles as $i => $title) {
            $tag         = trim((string) ($tags[$i] ?? ''));
            $title       = trim((string) $title);
            $description = trim((string) ($descriptions[$i] ?? ''));
            $existingIcon = $existingIcons[$i] ?? null;

            $icon = $existingIcon;
            $newFile = $request->file("item_icon.$i");
            if ($newFile && $newFile->isValid()) {
                $icon = $this->uploadIcon($newFile);
                if ($existingIcon) {
                    $this->deleteIcon($existingIcon);
                }
            }

            if ($tag === '' && $title === '' && $description === '' && !$icon) {
                continue;
            }

            $items[] = [
                'tag'         => $tag,
                'icon'        => $icon,
                'title'       => $title,
                'description' => $description,
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
        $destination = public_path('about-us/company-overview/vision-mission');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'vm_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteIcon($fileName)
    {
        $path = public_path('about-us/company-overview/vision-mission/' . $fileName);
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
                $fail('The icon must be a file of type: ' . implode(', ', self::IMAGE_EXTENSIONS) . '.');
            }
        };
    }
}
