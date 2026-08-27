<?php

namespace App\Http\Controllers\Backend\Career;

use App\Http\Controllers\Controller;
use App\Models\CareerPageDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/** Careers > Page Content — the banner, intro text and where applications are emailed. */
class CareerPageController extends Controller
{
    public function index()
    {
        $content = CareerPageDetails::whereNull('deleted_at')->latest('id')->first();

        return view('backend.career.page-details.index', compact('content'));
    }

    public function create()
    {
        $content = new CareerPageDetails();

        return view('backend.career.page-details.create', compact('content'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['created_at'] = Carbon::now();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->upload($request->file('banner_image'));
        }

        CareerPageDetails::create($data);

        return redirect()->route('career-page.index')->with('message', 'Careers page content saved successfully!');
    }

    public function edit($id)
    {
        $content = CareerPageDetails::findOrFail($id);

        return view('backend.career.page-details.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = CareerPageDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($request->hasFile('banner_image')) {
            $this->deleteFile($content->banner_image);
            $data['banner_image'] = $this->upload($request->file('banner_image'));
        }

        $content->update($data);

        return redirect()->route('career-page.index')->with('message', 'Careers page content has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            CareerPageDetails::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('career-page.index')->with('message', 'Careers page content deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */

    private function payload(Request $request)
    {
        return [
            'banner_title'     => $request->banner_title,
            'breadcrumb_child' => $request->breadcrumb_child,
            'intro_heading'    => $request->intro_heading,
            'intro_text'       => $request->intro_text,
            'form_sub_heading' => $request->form_sub_heading,
            'form_heading'     => $request->form_heading,
            'notify_email'     => $request->notify_email,
            'notify_cc'        => $request->notify_cc,
        ];
    }

    private function rules()
    {
        return [
            'banner_title'     => 'nullable|string|max:255',
            'breadcrumb_child' => 'nullable|string|max:255',
            'banner_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'intro_heading'    => 'nullable|string',
            'intro_text'       => 'nullable|string',
            'form_sub_heading' => 'nullable|string|max:255',
            'form_heading'     => 'nullable|string|max:255',
            'notify_email'     => 'nullable|email|max:255',
            'notify_cc'        => 'nullable|string|max:500',
        ];
    }

    private function messages()
    {
        return [
            'banner_image.max'   => 'The banner image must not be larger than 4MB.',
            'notify_email.email' => 'Please enter a valid email address.',
        ];
    }

    private function upload($file)
    {
        $destination = public_path('career-uploads/banner');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'career_banner_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteFile($fileName)
    {
        if (!$fileName) {
            return;
        }

        $path = public_path('career-uploads/banner/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
