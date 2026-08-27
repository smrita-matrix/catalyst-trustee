<?php

namespace App\Http\Controllers\Backend\Grievance;

use App\Http\Controllers\Controller;
use App\Models\Grievance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/** Grievance > Submissions — what investors have sent through the public form. */
class GrievanceSubmissionController extends Controller
{
    public function index()
    {
        $grievances = Grievance::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.grievance.submissions.index', compact('grievances'));
    }

    public function show($id)
    {
        $grievance = Grievance::findOrFail($id);

        // Opening it counts as reading it.
        if (!$grievance->is_read) {
            $grievance->update([
                'is_read'     => 1,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);
        }

        return view('backend.grievance.submissions.show', compact('grievance'));
    }

    public function destroy($id)
    {
        try {
            Grievance::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('grievance-submission.index')->with('message', 'Grievance deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }
}
