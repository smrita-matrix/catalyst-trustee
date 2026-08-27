<?php

namespace App\Http\Controllers\Backend\Career;

use App\Http\Controllers\Controller;
use App\Models\CareerApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/** Careers > Applications — resumes received through the website form. */
class CareerApplicationController extends Controller
{
    public function index()
    {
        $applications = CareerApplication::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.career.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = CareerApplication::findOrFail($id);

        // Opening it counts as reading it.
        if (!$application->is_read) {
            $application->update([
                'is_read'     => 1,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);
        }

        return view('backend.career.applications.show', compact('application'));
    }

    /** Serve the CV with its original filename rather than the stored one. */
    public function download($id)
    {
        $application = CareerApplication::findOrFail($id);

        if (!$application->resume_path || !is_file($application->resume_path)) {
            return redirect()->back()->with('error', 'The resume file is missing from the server.');
        }

        return response()->download(
            $application->resume_path,
            $application->resume_original_name ?: basename($application->resume_path)
        );
    }

    public function destroy($id)
    {
        try {
            CareerApplication::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('career-application.index')->with('message', 'Application deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }
}
