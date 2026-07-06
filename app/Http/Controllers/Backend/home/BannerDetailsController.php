<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Permission;
use App\Models\UsersPermission;
use App\Models\BannerDetails;


class BannerDetailsController extends Controller
{

    public function index()
    {
        $banner_details = BannerDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.banner-details.index', compact('banner_details'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.banner-details.create');
    }



    public function store(Request $request)
    {
        $request->validate([
            'banner_heading'     => 'required|string',
            'banner_description' => 'required|string',
            'button_text'        => 'required|string|max:100',
            'button_link'        => 'required|string|max:255',
            'banner_image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'banner_heading.required'     => 'The Banner Heading is required.',
            'banner_description.required' => 'The Banner Description is required.',
            'button_text.required'        => 'The Button Text is required.',
            'button_link.required'        => 'The Button Link is required.',
            'banner_image.required'       => 'Please upload a Banner Image.',
            'banner_image.max'            => 'The Banner Image must not be larger than 2MB.',
        ]);

        $data = [
            'banner_heading'     => $request->banner_heading,
            'banner_description' => $request->banner_description,
            'button_text'        => $request->button_text,
            'button_link'        => $request->button_link,
            'created_at'         => Carbon::now(),
            'created_by'         => Auth::id(),
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_images'] = $this->uploadBannerImage($request->file('banner_image'));
        }

        BannerDetails::create($data);

        return redirect()->route('banner-details.index')->with('message', 'Banner added successfully!');
    }


    public function edit($id)
    {
        $banner_details = BannerDetails::findOrFail($id);

        return view('backend.home-page.banner-details.edit', compact('banner_details'));
    }


    public function update(Request $request, $id)
    {
        $banner_details = BannerDetails::findOrFail($id);

        $request->validate([
            'banner_heading'     => 'required|string',
            'banner_description' => 'required|string',
            'button_text'        => 'required|string|max:100',
            'button_link'        => 'required|string|max:255',
            'banner_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'banner_heading.required'     => 'The Banner Heading is required.',
            'banner_description.required' => 'The Banner Description is required.',
            'button_text.required'        => 'The Button Text is required.',
            'button_link.required'        => 'The Button Link is required.',
            'banner_image.max'            => 'The Banner Image must not be larger than 2MB.',
        ]);

        $data = [
            'banner_heading'     => $request->banner_heading,
            'banner_description' => $request->banner_description,
            'button_text'        => $request->button_text,
            'button_link'        => $request->button_link,
            'modified_at'        => Carbon::now(),
            'modified_by'        => Auth::id(),
        ];

        if ($request->hasFile('banner_image')) {
            // Remove the old file if present
            if ($banner_details->banner_images) {
                $oldPath = public_path('home/banner/' . $banner_details->banner_images);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $data['banner_images'] = $this->uploadBannerImage($request->file('banner_image'));
        }

        $banner_details->update($data);

        return redirect()->route('banner-details.index')->with('message', 'Banner has been successfully updated!');
    }

    /**
     * Store the uploaded banner image in public/home/banner and return its filename.
     */
    private function uploadBannerImage($file)
    {
        $destination = public_path('home/banner');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'banner_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $fileName);

        return $fileName;
    }

    public function destroy(string $id)
    {
        $data['deleted_by'] =  Auth::user()->id;
        $data['deleted_at'] =  Carbon::now();
        try {
            $industries = BannerDetails::findOrFail($id);
            $industries->update($data);

            return redirect()->route('banner-details.index')->with('message', 'Banner Details deleted successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    

}