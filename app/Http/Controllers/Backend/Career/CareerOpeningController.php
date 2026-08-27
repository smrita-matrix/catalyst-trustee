<?php

namespace App\Http\Controllers\Backend\Career;

use App\Http\Controllers\Controller;
use App\Models\CareerOpening;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Careers > Current Openings — the job cards on the Careers page. */
class CareerOpeningController extends Controller
{
    public function index()
    {
        $openings = CareerOpening::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.career.openings.index', compact('openings'));
    }

    public function create()
    {
        $opening = new CareerOpening(['status' => 1, 'sort_order' => 0]);

        return view('backend.career.openings.create', compact('opening'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        CareerOpening::create($this->payload($request) + [
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('career-opening.index')->with('message', 'Opening added successfully!');
    }

    public function edit($id)
    {
        $opening = CareerOpening::findOrFail($id);

        return view('backend.career.openings.edit', compact('opening'));
    }

    public function update(Request $request, $id)
    {
        $opening = CareerOpening::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $opening->update($this->payload($request) + [
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('career-opening.index')->with('message', 'Opening has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            CareerOpening::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('career-opening.index')->with('message', 'Opening deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */

    private function payload(Request $request)
    {
        return [
            'title'         => $request->title,
            'experience'    => $request->experience,
            'vacancies'     => $request->vacancies,
            'qualification' => $request->qualification,
            'location'      => $request->location,
            'description'   => $request->description,
            'sort_order'    => $request->sort_order ?? 0,
            'status'        => $request->has('status') ? 1 : 0,
        ];
    }

    private function rules()
    {
        return [
            'title'         => 'required|string|max:255',
            'experience'    => 'nullable|string|max:100',
            'vacancies'     => 'nullable|string|max:50',
            'qualification' => 'nullable|string|max:255',
            'location'      => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'sort_order'    => 'nullable|integer',
        ];
    }

    private function messages()
    {
        return [
            'title.required' => 'The job title is required.',
        ];
    }
}
