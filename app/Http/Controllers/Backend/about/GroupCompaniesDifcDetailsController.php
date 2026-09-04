<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\GroupCompaniesDifcDetails;


class GroupCompaniesDifcDetailsController extends Controller
{
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $difc = GroupCompaniesDifcDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.about-us.group-companies.difc-details.index', compact('difc'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.group-companies.difc-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = [
            'heading'            => $request->heading,
            'top_description'    => $request->top_description,
            'bottom_description' => $request->bottom_description,
            'services'           => $this->buildServices($request, []),
            'button_text'        => $request->button_text,
            'button_link'        => $request->button_link,
            'created_at'         => Carbon::now(),
            'created_by'         => Auth::id(),
        ];

        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $this->uploadImage($request->file('logo_image'));
        }

        GroupCompaniesDifcDetails::create($data);

        return redirect()->route('group-companies-difc-details.index')->with('message', 'DIFC section added successfully!');
    }

    public function edit($id)
    {
        $difc = GroupCompaniesDifcDetails::findOrFail($id);

        return view('backend.about-us.group-companies.difc-details.edit', compact('difc'));
    }

    public function update(Request $request, $id)
    {
        $difc = GroupCompaniesDifcDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingServices = $difc->services ?? [];
        $services = $this->buildServices($request, $existingServices);
        $this->cleanupUnusedIcons($existingServices, $services);

        $data = [
            'heading'            => $request->heading,
            'top_description'    => $request->top_description,
            'bottom_description' => $request->bottom_description,
            'services'           => $services,
            'button_text'        => $request->button_text,
            'button_link'        => $request->button_link,
            'modified_at'        => Carbon::now(),
            'modified_by'        => Auth::id(),
        ];

        if ($request->hasFile('logo_image')) {
            if ($difc->logo_image) {
                $this->deleteImage($difc->logo_image);
            }
            $data['logo_image'] = $this->uploadImage($request->file('logo_image'));
        }

        $difc->update($data);

        return redirect()->route('group-companies-difc-details.index')->with('message', 'DIFC section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $difc = GroupCompaniesDifcDetails::findOrFail($id);
            $difc->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('group-companies-difc-details.index')->with('message', 'DIFC section deleted successfully!');
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
            'heading'            => 'required|string',
            'top_description'    => 'nullable|string',
            'bottom_description' => 'nullable|string',
            'button_text'        => 'nullable|string|max:100',
            'button_link'        => 'nullable|string|max:255',
            'logo_image'         => ['nullable', 'file', 'max:8192', $this->imageExtensionRule()],
            'service_title.*'    => 'nullable|string|max:255',
            'service_icon.*'     => ['nullable', 'file', 'max:8192', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'heading.required'   => 'The Heading is required.',
            'logo_image.max'     => 'The logo must not be larger than 8MB.',
            'service_icon.*.max' => 'Each service icon must not be larger than 8MB.',
        ];
    }

    /**
     * Build the services JSON array [{ icon, title }].
     */
    private function buildServices(Request $request, array $existingItems)
    {
        $titles        = $request->input('service_title', []);
        $existingIcons = $request->input('service_existing_icon', []);

        $services = [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $existingIcon = $existingIcons[$i] ?? null;

            $icon = $existingIcon;
            $newFile = $request->file("service_icon.$i");
            if ($newFile && $newFile->isValid()) {
                $icon = $this->uploadImage($newFile);
                if ($existingIcon) {
                    $this->deleteImage($existingIcon);
                }
            }

            if ($title === '' && !$icon) {
                continue;
            }

            $services[] = [
                'icon'  => $icon,
                'title' => $title,
            ];
        }

        return $services;
    }

    private function cleanupUnusedIcons(array $oldItems, array $newItems)
    {
        $newIcons = array_filter(array_column($newItems, 'icon'));

        foreach ($oldItems as $old) {
            $oldIcon = $old['icon'] ?? null;
            if ($oldIcon && !in_array($oldIcon, $newIcons, true)) {
                $this->deleteImage($oldIcon);
            }
        }
    }

    private function uploadImage($file)
    {
        $destination = public_path('about-us/group-companies/difc');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'gc_difc_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('about-us/group-companies/difc/' . $fileName);
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
