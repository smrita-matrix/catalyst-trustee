<?php

namespace App\Http\Controllers\Backend\Newsletter;

use App\Http\Controllers\Controller;
use App\Models\NewsMedia;
use App\Models\NewsMediaBannerDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Newsletter > News & Media.
 *
 * The banner sits at the top of the listing screen, with the cards below it,
 * matching how Articles is managed.
 */
class NewsMediaController extends Controller
{
    public function index()
    {
        $banner = NewsMediaBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $items = NewsMedia::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.newsletter.news-media.index', compact('banner', 'items'));
    }

    /* -------------------- Banner (same page) -------------------- */

    public function updateBanner(Request $request)
    {
        $request->validate([
            'title'             => 'nullable|string|max:255',
            'breadcrumb_parent' => 'nullable|string|max:255',
            'breadcrumb_child'  => 'nullable|string|max:255',
            'section_heading'   => 'nullable|string|max:255',
            'background_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $banner = NewsMediaBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $data = [
            'title'             => $request->title,
            'breadcrumb_parent' => $request->breadcrumb_parent,
            'breadcrumb_child'  => $request->breadcrumb_child,
            'section_heading'   => $request->section_heading,
        ];

        if ($request->hasFile('background_image')) {
            if ($banner && $banner->background_image) {
                $this->deleteFile('news-media/banner', $banner->background_image);
            }
            $data['background_image'] = $this->uploadFile($request->file('background_image'), 'news-media/banner', 'nm_banner');
        }

        if ($banner) {
            $data['modified_at'] = Carbon::now();
            $data['modified_by'] = Auth::id();
            $banner->update($data);
        } else {
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::id();
            NewsMediaBannerDetails::create($data);
        }

        return redirect()->route('news-media.index')->with('message', 'Banner saved successfully!');
    }

    /* -------------------- Cards CRUD -------------------- */

    public function create()
    {
        $item = new NewsMedia(['status' => 1, 'sort_order' => 0]);

        return view('backend.newsletter.news-media.create', compact('item'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['created_at'] = Carbon::now();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'news-media/image', 'news');
        }
        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $this->uploadFile($request->file('pdf_file'), 'news-media/pdf', 'news');
        }

        NewsMedia::create($data);

        return redirect()->route('news-media.index')->with('message', 'News & Media item added successfully!');
    }

    public function edit($id)
    {
        $item = NewsMedia::findOrFail($id);

        return view('backend.newsletter.news-media.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = NewsMedia::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $this->deleteFile('news-media/image', $item->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'news-media/image', 'news');
        }
        if ($request->hasFile('pdf_file')) {
            $this->deleteFile('news-media/pdf', $item->pdf_file);
            $data['pdf_file'] = $this->uploadFile($request->file('pdf_file'), 'news-media/pdf', 'news');
        }

        $item->update($data);

        return redirect()->route('news-media.index')->with('message', 'News & Media item has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            NewsMedia::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('news-media.index')->with('message', 'News & Media item deleted successfully!');
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
            'title'      => $request->title,
            'category'   => $request->category,
            'link'       => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->has('status') ? 1 : 0,
        ];
    }

    private function rules()
    {
        return [
            'title'      => 'required|string|max:255',
            'category'   => 'nullable|string|max:100',
            'link'       => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'pdf_file'   => 'nullable|mimes:pdf|max:20480',
        ];
    }

    private function messages()
    {
        return [
            'title.required' => 'The Title is required.',
            'link.url'       => 'The Read More link must be a full URL (https://...).',
            'image.max'      => 'The image must not be larger than 4MB.',
            'pdf_file.mimes' => 'The attachment must be a PDF.',
        ];
    }

    private function uploadFile($file, $folder, $prefix)
    {
        $destination = public_path($folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = $prefix . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteFile($folder, $fileName)
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
