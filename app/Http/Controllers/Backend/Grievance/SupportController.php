<?php

namespace App\Http\Controllers\Backend\Grievance;

use App\Http\Controllers\Controller;
use App\Models\GrievancePageDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Grievance > Contact for Support.
 *
 * This menu item has no page of its own — it opens a PDF directly, so this
 * screen does one thing: upload or replace that PDF.
 */
class SupportController extends Controller
{
    public function index()
    {
        $content = GrievancePageDetails::whereNull('deleted_at')->latest('id')->first();

        return view('backend.grievance.support.index', compact('content'));
    }

    public function update(Request $request)
    {
        $request->validate(
            ['support_pdf' => 'required|mimes:pdf|max:20480'],
            [
                'support_pdf.required' => 'Please choose a PDF to upload.',
                'support_pdf.mimes'    => 'The Contact for Support file must be a PDF.',
                'support_pdf.max'      => 'The PDF must not be larger than 20MB.',
            ]
        );

        $content = GrievancePageDetails::whereNull('deleted_at')->latest('id')->first();

        $fileName = $this->upload($request->file('support_pdf'));

        if ($content) {
            $this->deleteFile($content->support_pdf);
            $content->update([
                'support_pdf' => $fileName,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);
        } else {
            // No content row yet — create one holding just the PDF.
            GrievancePageDetails::create([
                'support_pdf' => $fileName,
                'created_at'  => Carbon::now(),
                'created_by'  => Auth::id(),
            ]);
        }

        return redirect()->route('grievance-support.index')->with('message', 'Contact for Support PDF uploaded successfully!');
    }

    /** Remove the PDF so the menu item stops linking anywhere. */
    public function destroy()
    {
        $content = GrievancePageDetails::whereNull('deleted_at')->latest('id')->first();

        if ($content && $content->support_pdf) {
            $this->deleteFile($content->support_pdf);
            $content->update([
                'support_pdf' => null,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);
        }

        return redirect()->route('grievance-support.index')->with('message', 'Contact for Support PDF removed.');
    }

    /* ------------------------------------------------------------------ */

    private function upload($file)
    {
        $destination = public_path('grievance/documents');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'support_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteFile($fileName)
    {
        if (!$fileName) {
            return;
        }

        $path = public_path('grievance/documents/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
