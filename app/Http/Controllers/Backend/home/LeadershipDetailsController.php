<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\LeadershipDetails;


class LeadershipDetailsController extends Controller
{
    /**
     * Allowed image extensions (SVG plus common raster formats).
     */
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $leadership = LeadershipDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.leadership-details.index', compact('leadership'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.leadership-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $leaders = $this->buildLeaders($request, []);
        $numbers = $this->buildNumbers($request, []);

        LeadershipDetails::create([
            'leadership_heading' => $request->leadership_heading,
            'numbers_heading'    => $request->numbers_heading,
            'leaders'            => $leaders,
            'numbers'            => $numbers,
            'created_at'         => Carbon::now(),
            'created_by'         => Auth::id(),
        ]);

        return redirect()->route('leadership-details.index')->with('message', 'Leadership section added successfully!');
    }

    public function edit($id)
    {
        $leadership = LeadershipDetails::findOrFail($id);

        return view('backend.home-page.leadership-details.edit', compact('leadership'));
    }

    public function update(Request $request, $id)
    {
        $leadership = LeadershipDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingLeaders = $leadership->leaders ?? [];
        $existingNumbers = $leadership->numbers ?? [];

        $leaders = $this->buildLeaders($request, $existingLeaders);
        $numbers = $this->buildNumbers($request, $existingNumbers);

        $this->cleanupUnusedImages($existingLeaders, $leaders, 'image');
        $this->cleanupUnusedImages($existingNumbers, $numbers, 'icon');

        $leadership->update([
            'leadership_heading' => $request->leadership_heading,
            'numbers_heading'    => $request->numbers_heading,
            'leaders'            => $leaders,
            'numbers'            => $numbers,
            'modified_at'        => Carbon::now(),
            'modified_by'        => Auth::id(),
        ]);

        return redirect()->route('leadership-details.index')->with('message', 'Leadership section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $leadership = LeadershipDetails::findOrFail($id);
            $leadership->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('leadership-details.index')->with('message', 'Leadership section deleted successfully!');
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
            'leadership_heading'  => 'nullable|string|max:255',
            'numbers_heading'     => 'nullable|string|max:255',

            // Leaders
            'leader_name'         => 'nullable|array',
            'leader_name.*'       => 'nullable|string|max:255',
            'leader_designation.*'=> 'nullable|string|max:255',
            'leader_description.*'=> 'nullable|string',
            'leader_image.*'      => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],

            // Numbers
            'number_text'         => 'nullable|array',
            'number_text.*'       => 'nullable|string|max:255',
            'number_value.*'      => 'nullable|string|max:50',
            'number_suffix.*'     => 'nullable|string|max:20',
            'number_icon.*'       => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'leader_image.*.max' => 'Each leadership image must not be larger than 2MB.',
            'number_icon.*.max'  => 'Each counter icon must not be larger than 2MB.',
        ];
    }

    /**
     * Build the leaders JSON array from the repeater rows.
     */
    private function buildLeaders(Request $request, array $existing)
    {
        $names        = $request->input('leader_name', []);
        $designations = $request->input('leader_designation', []);
        $descriptions = $request->input('leader_description', []);
        $existingImgs = $request->input('leader_existing_image', []);

        $leaders = [];

        foreach ($names as $i => $name) {
            $name        = trim((string) $name);
            $designation = trim((string) ($designations[$i] ?? ''));
            $description = trim((string) ($descriptions[$i] ?? ''));

            $image = $this->resolveFile($request, "leader_image.$i", $existingImgs[$i] ?? null);

            if ($name === '' && $designation === '' && $description === '' && !$image) {
                continue;
            }

            $leaders[] = [
                'image'       => $image,
                'name'        => $name,
                'designation' => $designation,
                'description' => $description,
            ];
        }

        return $leaders;
    }

    /**
     * Build the numbers/counters JSON array from the repeater rows.
     */
    private function buildNumbers(Request $request, array $existing)
    {
        $texts         = $request->input('number_text', []);
        $values        = $request->input('number_value', []);
        $suffixes      = $request->input('number_suffix', []);
        $existingIcons = $request->input('number_existing_icon', []);

        $numbers = [];

        foreach ($texts as $i => $text) {
            $text   = trim((string) $text);
            $value  = trim((string) ($values[$i] ?? ''));
            $suffix = trim((string) ($suffixes[$i] ?? ''));

            $icon = $this->resolveFile($request, "number_icon.$i", $existingIcons[$i] ?? null);

            if ($text === '' && $value === '' && $suffix === '' && !$icon) {
                continue;
            }

            $numbers[] = [
                'icon'       => $icon,
                'count_text' => $text,
                'number'     => $value,
                'suffix'     => $suffix,
            ];
        }

        return $numbers;
    }

    /**
     * Upload a new file (deleting the old one) or fall back to the existing filename.
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

    private function cleanupUnusedImages(array $oldItems, array $newItems, $key)
    {
        $newFiles = array_filter(array_column($newItems, $key));

        foreach ($oldItems as $old) {
            $file = $old[$key] ?? null;
            if ($file && !in_array($file, $newFiles, true)) {
                $this->deleteImage($file);
            }
        }
    }

    private function uploadImage($file)
    {
        $destination = public_path('home/leadership');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'lead_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/leadership/' . $fileName);
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
