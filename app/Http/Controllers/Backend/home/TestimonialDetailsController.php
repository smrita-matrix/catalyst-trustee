<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\TestimonialDetails;


class TestimonialDetailsController extends Controller
{
    public function index()
    {
        $testimonial = TestimonialDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.testimonial-details.index', compact('testimonial'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.testimonial-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        TestimonialDetails::create([
            'heading'    => $request->heading,
            'items'      => $this->buildItems($request),
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('testimonial-details.index')->with('message', 'Testimonials section added successfully!');
    }

    public function edit($id)
    {
        $testimonial = TestimonialDetails::findOrFail($id);

        return view('backend.home-page.testimonial-details.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = TestimonialDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $testimonial->update([
            'heading'     => $request->heading,
            'items'       => $this->buildItems($request),
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('testimonial-details.index')->with('message', 'Testimonials section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $testimonial = TestimonialDetails::findOrFail($id);
            $testimonial->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('testimonial-details.index')->with('message', 'Testimonials section deleted successfully!');
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
            'heading'            => 'required|string|max:255',
            'item_text'          => 'nullable|array',
            'item_text.*'        => 'nullable|string',
            'item_name.*'        => 'nullable|string|max:255',
            'item_designation.*' => 'nullable|string|max:255',
            'item_company.*'     => 'nullable|string|max:255',
        ];
    }

    private function messages()
    {
        return [
            'heading.required' => 'The Heading is required.',
        ];
    }

    /** Turn the repeater rows into the stored slide list, dropping empty rows. */
    private function buildItems(Request $request)
    {
        $texts        = $request->input('item_text', []);
        $names        = $request->input('item_name', []);
        $designations = $request->input('item_designation', []);
        $companies    = $request->input('item_company', []);

        $items = [];

        foreach ($texts as $i => $text) {
            $text        = trim((string) $text);
            $name        = trim((string) ($names[$i] ?? ''));
            $designation = trim((string) ($designations[$i] ?? ''));
            $company     = trim((string) ($companies[$i] ?? ''));

            if ($text === '' && $name === '' && $designation === '' && $company === '') {
                continue;
            }

            $items[] = [
                'text'        => $text,
                'name'        => $name,
                'designation' => $designation,
                'company'     => $company,
            ];
        }

        return $items;
    }
}
