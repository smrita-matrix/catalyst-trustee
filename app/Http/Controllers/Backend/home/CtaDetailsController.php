<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\CtaDetails;


class CtaDetailsController extends Controller
{
    public function index()
    {
        $cta = CtaDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.cta-details.index', compact('cta'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.cta-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(true), $this->messages());

        $data = [
            'heading'     => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $this->uploadImage($request->file('background_image'));
        }

        CtaDetails::create($data);

        return redirect()->route('cta-details.index')->with('message', 'CTA section added successfully!');
    }

    public function edit($id)
    {
        $cta = CtaDetails::findOrFail($id);

        return view('backend.home-page.cta-details.edit', compact('cta'));
    }

    public function update(Request $request, $id)
    {
        $cta = CtaDetails::findOrFail($id);

        $request->validate($this->rules(false), $this->messages());

        $data = [
            'heading'     => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ];

        if ($request->hasFile('background_image')) {
            if ($cta->background_image) {
                $this->deleteImage($cta->background_image);
            }
            $data['background_image'] = $this->uploadImage($request->file('background_image'));
        }

        $cta->update($data);

        return redirect()->route('cta-details.index')->with('message', 'CTA section has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $cta = CtaDetails::findOrFail($id);
            $cta->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('cta-details.index')->with('message', 'CTA section deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function rules($isCreate)
    {
        return [
            'heading'          => 'required|string',
            'description'      => 'nullable|string',
            'button_text'      => 'nullable|string|max:100',
            'button_link'      => 'nullable|string|max:255',
            'background_image' => [$isCreate ? 'nullable' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    private function messages()
    {
        return [
            'heading.required'      => 'The Heading is required.',
            'background_image.max'  => 'The background image must not be larger than 2MB.',
            'background_image.image' => 'The background must be an image (jpg, jpeg, png, webp).',
        ];
    }

    private function uploadImage($file)
    {
        $destination = public_path('home/cta');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'cta_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/cta/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
