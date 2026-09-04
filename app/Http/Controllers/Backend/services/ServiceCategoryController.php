<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ServiceCategory;


class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.services.service-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.services.service-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = [
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->has('status') ? 1 : 0,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ];

        if ($request->hasFile('icon')) {
            $data['icon'] = $this->uploadIcon($request->file('icon'));
        }

        ServiceCategory::create($data);

        return redirect()->route('service-category.index')->with('message', 'Category added successfully!');
    }

    public function edit($id)
    {
        $category = ServiceCategory::findOrFail($id);

        return view('backend.services.service-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'sort_order'  => $request->sort_order ?? 0,
            'status'      => $request->has('status') ? 1 : 0,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ];

        if ($request->hasFile('icon')) {
            if ($category->icon) {
                $this->deleteIcon($category->icon);
            }
            $data['icon'] = $this->uploadIcon($request->file('icon'));
        }

        $category->update($data);

        return redirect()->route('service-category.index')->with('message', 'Category has been successfully updated!');
    }

    /**
     * Show or hide a whole group in the website menu, straight from the list.
     *
     * Hiding the group takes every service under it off the menu too, so this
     * is the quick way to remove a full heading such as "GIFT City Services".
     */
    public function toggleStatus($id)
    {
        try {
            $category = ServiceCategory::findOrFail($id);

            $category->update([
                'status'      => $category->status ? 0 : 1,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);

            return redirect()->back()->with('message', '"' . $category->name . '" is now '
                . ($category->status ? 'showing in' : 'hidden from') . ' the website menu.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = ServiceCategory::findOrFail($id);
            $category->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('service-category.index')->with('message', 'Category deleted successfully!');
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
            'name'       => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
            'icon'       => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:8192',
        ];
    }

    private function messages()
    {
        return [
            'name.required' => 'The Category Name is required.',
            'icon.max'      => 'The icon must not be larger than 8MB.',
        ];
    }

    private function uploadIcon($file)
    {
        $destination = public_path('services/categories');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'cat_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteIcon($fileName)
    {
        $path = public_path('services/categories/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
