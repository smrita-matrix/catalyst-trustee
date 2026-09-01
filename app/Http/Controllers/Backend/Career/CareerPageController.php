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

    /**
     * Build the Life at Catalyst stories from the form.
     *
     * Each story keeps its own set of photos. Pictures already saved arrive as
     * hidden values and newly chosen files arrive as uploads, so the two are
     * joined; anything the admin ticked for removal is dropped and its file
     * deleted. Blank rows are ignored.
     */
    private function cleanStories(Request $request): array
    {
        $rows   = (array) $request->input('life_stories', []);
        $clean  = [];

        foreach ($rows as $i => $row) {
            $story = [
                'title' => trim((string) ($row['title'] ?? '')),
                'text'  => trim((string) ($row['text'] ?? '')),
                'link'  => trim((string) ($row['link'] ?? '')),
            ];

            // Photos already on the page, minus any marked for removal.
            $keep    = array_filter((array) ($row['existing_images'] ?? []));
            $remove  = array_filter((array) ($row['remove_images'] ?? []));

            foreach ($remove as $file) {
                $this->deleteStoryImage($file);
            }

            $images = array_values(array_diff($keep, $remove));

            // Newly chosen files for this story.
            foreach ((array) $request->file("life_stories.$i.images", []) as $file) {
                if ($file && $file->isValid()) {
                    $images[] = $this->uploadStoryImage($file);
                }
            }

            $story['images'] = array_values($images);

            if ($story['title'] === '' && $story['text'] === '' && !$story['images']) {
                continue;
            }

            $clean[] = $story;
        }

        return $clean;
    }

    private function uploadStoryImage($file): string
    {
        $destination = public_path('career-uploads/life');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'life_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteStoryImage($fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = public_path('career-uploads/life/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function payload(Request $request)
    {
        return [
            'banner_title'     => $request->banner_title,
            'breadcrumb_child' => $request->breadcrumb_child,
            'intro_heading'    => $request->intro_heading,
            'intro_text'       => $request->intro_text,
            'life_stories'     => $this->cleanStories($request),
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
            'life_stories'          => 'nullable|array',
            'life_stories.*.title'  => 'nullable|string|max:255',
            'life_stories.*.text'   => 'nullable|string',
            'life_stories.*.link'   => 'nullable|string|max:1000',
            'life_stories.*.images.*' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
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
