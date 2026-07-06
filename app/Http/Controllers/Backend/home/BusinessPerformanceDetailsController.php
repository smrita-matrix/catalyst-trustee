<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\BusinessPerformanceDetails;


class BusinessPerformanceDetailsController extends Controller
{
    public function index()
    {
        $business_performance = BusinessPerformanceDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.business-performance-details.index', compact('business_performance'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.business-performance-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $categories = $this->buildCategories($request);

        BusinessPerformanceDetails::create([
            'sub_heading' => $request->sub_heading,
            'heading'     => $request->heading,
            'categories'  => $categories,
            'years'       => $this->buildYears($request, count($categories)),
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('business-performance-details.index')->with('message', 'Business Performance section added successfully!');
    }

    public function edit($id)
    {
        $business_performance = BusinessPerformanceDetails::findOrFail($id);

        return view('backend.home-page.business-performance-details.edit', compact('business_performance'));
    }

    public function update(Request $request, $id)
    {
        $business_performance = BusinessPerformanceDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $categories = $this->buildCategories($request);

        $business_performance->update([
            'sub_heading' => $request->sub_heading,
            'heading'     => $request->heading,
            'categories'  => $categories,
            'years'       => $this->buildYears($request, count($categories)),
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('business-performance-details.index')->with('message', 'Business Performance section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $business_performance = BusinessPerformanceDetails::findOrFail($id);
            $business_performance->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('business-performance-details.index')->with('message', 'Business Performance section deleted successfully!');
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
            'sub_heading'   => 'nullable|string|max:255',
            'heading'       => 'nullable|string|max:255',
            'cat_label'     => 'required|array|min:1',
            'cat_label.*'   => 'nullable|string|max:100',
            'cat_color.*'   => 'nullable|string|max:20',
            'year'          => 'nullable|array',
            'year.*'        => 'nullable|string|max:50',
            'value'         => 'nullable|array',
        ];
    }

    private function messages()
    {
        return [
            'cat_label.required' => 'At least one category is required.',
        ];
    }

    /**
     * Build the categories array [{ label, color }] from the fixed inputs.
     */
    private function buildCategories(Request $request)
    {
        $labels = $request->input('cat_label', []);
        $colors = $request->input('cat_color', []);

        $categories = [];
        foreach ($labels as $i => $label) {
            $label = trim((string) $label);
            if ($label === '') {
                continue;
            }
            $categories[] = [
                'label' => $label,
                'color' => trim((string) ($colors[$i] ?? '#cccccc')),
            ];
        }

        return $categories;
    }

    /**
     * Build the years array [{ year, values: [..] }].
     * Values are submitted as value[<catIndex>][<rowIndex>] so each row collects
     * one value per category. Rows with no year and all-empty values are skipped.
     */
    private function buildYears(Request $request, $categoryCount)
    {
        $years  = $request->input('year', []);
        $values = $request->input('value', []); // value[catIndex][rowIndex]

        $result = [];

        foreach ($years as $rowIndex => $year) {
            $year = trim((string) $year);

            $rowValues = [];
            $hasValue = false;
            for ($c = 0; $c < $categoryCount; $c++) {
                $raw = $values[$c][$rowIndex] ?? '';
                $num = ($raw === '' || $raw === null) ? 0 : (float) $raw;
                if ($raw !== '' && $raw !== null) {
                    $hasValue = true;
                }
                // store as int when whole, else float
                $rowValues[] = ($num == (int) $num) ? (int) $num : $num;
            }

            if ($year === '' && !$hasValue) {
                continue;
            }

            $result[] = [
                'year'   => $year,
                'values' => $rowValues,
            ];
        }

        return $result;
    }
}
