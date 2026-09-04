<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\CompanyOverviewIntroductionDetails;


class CompanyOverviewIntroductionDetailsController extends Controller
{
    public function index()
    {
        $introduction = CompanyOverviewIntroductionDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.about-us.company-overview.introduction-details.index', compact('introduction'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.company-overview.introduction-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['created_at'] = Carbon::now();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        CompanyOverviewIntroductionDetails::create($data);

        return redirect()->route('company-overview-introduction-details.index')->with('message', 'Introduction added successfully!');
    }

    public function edit($id)
    {
        $introduction = CompanyOverviewIntroductionDetails::findOrFail($id);

        return view('backend.about-us.company-overview.introduction-details.edit', compact('introduction'));
    }

    public function update(Request $request, $id)
    {
        $introduction = CompanyOverviewIntroductionDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($request->hasFile('image')) {
            if ($introduction->image) {
                $this->deleteImage($introduction->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $introduction->update($data);

        return redirect()->route('company-overview-introduction-details.index')->with('message', 'Introduction has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $introduction = CompanyOverviewIntroductionDetails::findOrFail($id);
            $introduction->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('company-overview-introduction-details.index')->with('message', 'Introduction deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function payload(Request $request)
    {
        return [
            'experience_number' => $request->experience_number,
            'experience_label'  => $request->experience_label,
            'established_label' => $request->established_label,
            'established_year'  => $request->established_year,
            'sub_heading'       => $request->sub_heading,
            'heading'           => $request->heading,
            'tagline'           => $request->tagline,
            'description'       => $request->description,
            'more_content'      => $request->more_content,
            'button_text'       => $request->button_text,
            'button_link'       => $request->button_link,
        ];
    }

    private function rules()
    {
        return [
            'heading'      => 'required|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ];
    }

    private function messages()
    {
        return [
            'heading.required' => 'The Heading is required.',
            'image.image'      => 'The image must be a valid image (jpg, jpeg, png, webp).',
            'image.max'        => 'The image must not be larger than 8MB.',
        ];
    }

    private function uploadImage($file)
    {
        $destination = public_path('about-us/company-overview/introduction');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'co_intro_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('about-us/company-overview/introduction/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
