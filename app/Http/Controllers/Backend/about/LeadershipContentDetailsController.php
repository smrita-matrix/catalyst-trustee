<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\LeadershipContentDetails;


class LeadershipContentDetailsController extends Controller
{
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $content = LeadershipContentDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.about-us.leadership.content-details.index', compact('content'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.leadership.content-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        LeadershipContentDetails::create([
            'intro_sub_heading' => $request->intro_sub_heading,
            'intro_heading'     => $request->intro_heading,
            'intro_description' => $request->intro_description,
            'board_heading'     => $request->board_heading,
            'board_members'     => $this->buildMembers($request, 'board', []),
            'team_heading'      => $request->team_heading,
            'team_members'      => $this->buildMembers($request, 'team', []),
            'created_at'        => Carbon::now(),
            'created_by'        => Auth::id(),
        ]);

        return redirect()->route('leadership-content-details.index')->with('message', 'Leadership content added successfully!');
    }

    public function edit($id)
    {
        $content = LeadershipContentDetails::findOrFail($id);

        return view('backend.about-us.leadership.content-details.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = LeadershipContentDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingBoard = $content->board_members ?? [];
        $existingTeam  = $content->team_members ?? [];

        $board = $this->buildMembers($request, 'board', $existingBoard);
        $team  = $this->buildMembers($request, 'team', $existingTeam);

        $this->cleanupUnusedImages($existingBoard, $board);
        $this->cleanupUnusedImages($existingTeam, $team);

        $content->update([
            'intro_sub_heading' => $request->intro_sub_heading,
            'intro_heading'     => $request->intro_heading,
            'intro_description' => $request->intro_description,
            'board_heading'     => $request->board_heading,
            'board_members'     => $board,
            'team_heading'      => $request->team_heading,
            'team_members'      => $team,
            'modified_at'       => Carbon::now(),
            'modified_by'       => Auth::id(),
        ]);

        return redirect()->route('leadership-content-details.index')->with('message', 'Leadership content has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $content = LeadershipContentDetails::findOrFail($id);
            $content->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('leadership-content-details.index')->with('message', 'Leadership content deleted successfully!');
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
            'intro_heading'         => 'nullable|string',
            'intro_description'     => 'nullable|string',
            'board_name.*'          => 'nullable|string|max:255',
            'board_designation.*'   => 'nullable|string|max:255',
            'board_description.*'    => 'nullable|string',
            'board_image.*'         => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
            'team_name.*'           => 'nullable|string|max:255',
            'team_designation.*'    => 'nullable|string|max:255',
            'team_description.*'    => 'nullable|string',
            'team_image.*'          => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
        ];
    }

    private function messages()
    {
        return [
            'board_image.*.max' => 'Each Board member image must not be larger than 2MB.',
            'team_image.*.max'  => 'Each Team member image must not be larger than 2MB.',
        ];
    }

    /**
     * Build a members JSON array [{ image, name, designation, description }]
     * for the given field prefix (board / team).
     */
    private function buildMembers(Request $request, $prefix, array $existing)
    {
        $names        = $request->input($prefix . '_name', []);
        $designations = $request->input($prefix . '_designation', []);
        $descriptions = $request->input($prefix . '_description', []);
        $existingImgs = $request->input($prefix . '_existing_image', []);

        $members = [];

        foreach ($names as $i => $name) {
            $name        = trim((string) $name);
            $designation = trim((string) ($designations[$i] ?? ''));
            $description = trim((string) ($descriptions[$i] ?? ''));
            $existingImg = $existingImgs[$i] ?? null;

            $image = $existingImg;
            $newFile = $request->file("{$prefix}_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile);
                if ($existingImg) {
                    $this->deleteImage($existingImg);
                }
            }

            if ($name === '' && $designation === '' && $description === '' && !$image) {
                continue;
            }

            $members[] = [
                'image'       => $image,
                'name'        => $name,
                'designation' => $designation,
                'description' => $description,
            ];
        }

        return $members;
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
        $destination = public_path('about-us/leadership/content');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'lead_member_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('about-us/leadership/content/' . $fileName);
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
