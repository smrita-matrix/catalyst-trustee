<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\LeadershipBannerDetails;


class LeadershipBannerDetailsController extends Controller
{
    public function index()
    {
        $banner = LeadershipBannerDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.about-us.leadership.banner-details.index', compact('banner'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.leadership.banner-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = [
            'title'             => $request->title,
            'breadcrumb_parent' => $request->breadcrumb_parent,
            'created_at'        => Carbon::now(),
            'created_by'        => Auth::id(),
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $this->uploadImage($request->file('background_image'));
        }

        LeadershipBannerDetails::create($data);

        return redirect()->route('leadership-banner-details.index')->with('message', 'Banner added successfully!');
    }

    public function edit($id)
    {
        $banner = LeadershipBannerDetails::findOrFail($id);

        return view('backend.about-us.leadership.banner-details.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = LeadershipBannerDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = [
            'title'             => $request->title,
            'breadcrumb_parent' => $request->breadcrumb_parent,
            'modified_at'       => Carbon::now(),
            'modified_by'       => Auth::id(),
        ];

        if ($request->hasFile('background_image')) {
            if ($banner->background_image) {
                $this->deleteImage($banner->background_image);
            }
            $data['background_image'] = $this->uploadImage($request->file('background_image'));
        }

        $banner->update($data);

        return redirect()->route('leadership-banner-details.index')->with('message', 'Banner has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $banner = LeadershipBannerDetails::findOrFail($id);
            $banner->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('leadership-banner-details.index')->with('message', 'Banner deleted successfully!');
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
            'title'             => 'required|string|max:255',
            'breadcrumb_parent' => 'nullable|string|max:255',
            'background_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    private function messages()
    {
        return [
            'title.required'         => 'The Title is required.',
            'background_image.image' => 'The background must be an image (jpg, jpeg, png, webp).',
            'background_image.max'   => 'The background image must not be larger than 2MB.',
        ];
    }

    private function uploadImage($file)
    {
        $destination = public_path('about-us/leadership/banner');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'lead_banner_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('about-us/leadership/banner/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
