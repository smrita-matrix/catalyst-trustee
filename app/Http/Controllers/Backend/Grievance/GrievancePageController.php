<?php

namespace App\Http\Controllers\Backend\Grievance;

use App\Http\Controllers\Controller;
use App\Models\GrievancePageDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Grievance > Page Content.
 *
 * Edits the wording around the Investor Grievance form and the PDF that the
 * "Contact for Support" menu item opens. The form fields themselves are fixed.
 */
class GrievancePageController extends Controller
{
    public function index()
    {
        $content = GrievancePageDetails::whereNull('deleted_at')->latest('id')->first();

        return view('backend.grievance.page-details.index', compact('content'));
    }

    public function create()
    {
        $content = new GrievancePageDetails();

        return view('backend.grievance.page-details.create', compact('content'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['created_at'] = Carbon::now();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->upload($request->file('banner_image'), 'grievance/banner', 'grievance_banner');
        }
        if ($request->hasFile('support_pdf')) {
            $data['support_pdf'] = $this->upload($request->file('support_pdf'), 'grievance/documents', 'support');
        }

        GrievancePageDetails::create($data);

        return redirect()->route('grievance-page.index')->with('message', 'Grievance page content saved successfully!');
    }

    public function edit($id)
    {
        $content = GrievancePageDetails::findOrFail($id);

        return view('backend.grievance.page-details.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = GrievancePageDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($request->hasFile('banner_image')) {
            $this->deleteFile($content->banner_image, 'grievance/banner');
            $data['banner_image'] = $this->upload($request->file('banner_image'), 'grievance/banner', 'grievance_banner');
        }
        if ($request->hasFile('support_pdf')) {
            $this->deleteFile($content->support_pdf, 'grievance/documents');
            $data['support_pdf'] = $this->upload($request->file('support_pdf'), 'grievance/documents', 'support');
        }

        $content->update($data);

        return redirect()->route('grievance-page.index')->with('message', 'Grievance page content has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            GrievancePageDetails::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('grievance-page.index')->with('message', 'Grievance page content deleted successfully!');
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
            'banner_title'       => $request->banner_title,
            'breadcrumb_child'   => $request->breadcrumb_child,
            'intro_text'         => $request->intro_text,
            'holder_heading'     => $request->holder_heading,
            'instrument_heading' => $request->instrument_heading,
            'complaint_options'  => $this->cleanList($request->input('complaint_options', [])),
            'notes'              => $this->cleanList($request->input('notes', [])),
            'notify_email'       => $request->notify_email,
        ];
    }

    /** Drop the blank rows an admin leaves behind in a repeater. */
    private function cleanList($values)
    {
        $trimmed = array_map(fn ($v) => trim((string) $v), (array) $values);

        return array_values(array_filter($trimmed, fn ($v) => $v !== ''));
    }

    private function rules()
    {
        return [
            'banner_title'        => 'nullable|string|max:255',
            'breadcrumb_child'    => 'nullable|string|max:255',
            'banner_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'intro_text'          => 'nullable|string',
            'holder_heading'      => 'nullable|string|max:255',
            'instrument_heading'  => 'nullable|string|max:255',
            'complaint_options'   => 'nullable|array',
            'complaint_options.*' => 'nullable|string|max:255',
            'notes'               => 'nullable|array',
            'notes.*'             => 'nullable|string',
            'notify_email'        => 'nullable|email|max:255',
            'support_pdf'         => 'nullable|mimes:pdf|max:20480',
        ];
    }

    private function messages()
    {
        return [
            'support_pdf.mimes' => 'The Contact for Support file must be a PDF.',
            'banner_image.max'  => 'The banner image must not be larger than 4MB.',
        ];
    }

    private function upload($file, $folder, $prefix)
    {
        $destination = public_path($folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = $prefix . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteFile($fileName, $folder)
    {
        if (!$fileName) {
            return;
        }

        $path = public_path($folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
