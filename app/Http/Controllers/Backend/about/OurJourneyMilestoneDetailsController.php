<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\OurJourneyMilestoneDetails;


class OurJourneyMilestoneDetailsController extends Controller
{
    public function index()
    {
        $milestones = OurJourneyMilestoneDetails::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('year', 'asc')
            ->get();

        return view('backend.about-us.our-journey.milestone-details.index', compact('milestones'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.our-journey.milestone-details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year'          => 'required|array',
            'year.*'        => 'nullable|string|max:50',
            'sort_order.*'  => 'nullable|integer',
            'description.*' => 'nullable|string',
            'icon_image.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ], [
            'year.required' => 'Please add at least one milestone.',
        ]);

        $years        = $request->input('year', []);
        $sortOrders   = $request->input('sort_order', []);
        $descriptions = $request->input('description', []);

        $added = 0;

        foreach ($years as $i => $year) {
            $year = trim((string) $year);
            if ($year === '') {
                continue; // skip empty rows
            }

            $data = [
                'year'        => $year,
                'description' => trim((string) ($descriptions[$i] ?? '')),
                'sort_order'  => $sortOrders[$i] ?? 0,
                'created_at'  => Carbon::now(),
                'created_by'  => Auth::id(),
            ];

            $newFile = $request->file("icon_image.$i");
            if ($newFile && $newFile->isValid()) {
                $data['icon_image'] = $this->uploadImage($newFile);
            }

            OurJourneyMilestoneDetails::create($data);
            $added++;
        }

        if ($added === 0) {
            return redirect()->back()->withInput()->with('error', 'Please fill at least one milestone (Year is required).');
        }

        return redirect()->route('our-journey-milestone-details.index')->with('message', $added . ' milestone(s) added successfully!');
    }

    public function edit($id)
    {
        $milestone = OurJourneyMilestoneDetails::findOrFail($id);

        return view('backend.about-us.our-journey.milestone-details.edit', compact('milestone'));
    }

    public function update(Request $request, $id)
    {
        $milestone = OurJourneyMilestoneDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = [
            'year'        => $request->year,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ];

        if ($request->hasFile('icon_image')) {
            if ($milestone->icon_image) {
                $this->deleteImage($milestone->icon_image);
            }
            $data['icon_image'] = $this->uploadImage($request->file('icon_image'));
        }

        $milestone->update($data);

        return redirect()->route('our-journey-milestone-details.index')->with('message', 'Milestone has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $milestone = OurJourneyMilestoneDetails::findOrFail($id);
            $milestone->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('our-journey-milestone-details.index')->with('message', 'Milestone deleted successfully!');
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
            'year'        => 'required|string|max:50',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'icon_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ];
    }

    private function messages()
    {
        return [
            'year.required'    => 'The Year is required.',
            'icon_image.image' => 'The icon must be an image (jpg, jpeg, png, webp, svg).',
            'icon_image.max'   => 'The icon image must not be larger than 2MB.',
        ];
    }

    private function uploadImage($file)
    {
        $destination = public_path('about-us/our-journey/milestones');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'oj_milestone_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('about-us/our-journey/milestones/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
