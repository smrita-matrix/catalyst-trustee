<?php

namespace App\Http\Controllers\Backend\PublicNotice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Notice;
use App\Models\NoticeBannerDetails;

class NoticeController extends Controller
{
    public function index()
    {
        $banner = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $notices = Notice::whereNull('deleted_at')
            ->orderBy('section', 'asc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.public-notice.notices.index', compact('banner', 'notices'));
    }

    /* -------------------- Banner (same page) -------------------- */

    public function updateBanner(Request $request)
    {
        $request->validate([
            'title'             => 'nullable|string|max:255',
            'breadcrumb_parent' => 'nullable|string|max:255',
            'breadcrumb_child'  => 'nullable|string|max:255',
            'background_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $banner = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $data = [
            'title'             => $request->title,
            'breadcrumb_parent' => $request->breadcrumb_parent,
            'breadcrumb_child'  => $request->breadcrumb_child,
        ];

        if ($request->hasFile('background_image')) {
            $destination = public_path('public-notice/banner');
            if (!is_dir($destination)) {
                mkdir($destination, 0775, true);
            }
            if ($banner && $banner->background_image) {
                $old = $destination . '/' . $banner->background_image;
                if (is_file($old)) {
                    @unlink($old);
                }
            }
            $file = $request->file('background_image');
            $fileName = 'notice_banner_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
            $file->move($destination, $fileName);
            $data['background_image'] = $fileName;
        }

        if ($banner) {
            $data['modified_at'] = Carbon::now();
            $data['modified_by'] = Auth::id();
            $banner->update($data);
        } else {
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::id();
            NoticeBannerDetails::create($data);
        }

        return redirect()->route('notices.index')->with('message', 'Banner saved successfully!');
    }

    public function create(Request $request)
    {
        $section = $request->query('section', 'bomsc');
        if (!array_key_exists($section, Notice::SECTIONS)) {
            $section = 'bomsc';
        }

        return view('backend.public-notice.notices.create', compact('section'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section'        => 'required|string|max:20',
            'title'          => 'required|array',
            'title.*'        => 'nullable|string|max:255',
            'period.*'       => 'nullable|string|max:100',
            'notice_date.*'  => 'nullable|string|max:100',
            'description.*'  => 'nullable|string',
            'sort_order.*'   => 'nullable|integer',
            'document_file.*'=> 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:20480',
        ], [
            'title.required' => 'Please add at least one notice.',
        ]);

        $section = $request->input('section', 'bomsc');
        if (!array_key_exists($section, Notice::SECTIONS)) {
            $section = 'bomsc';
        }

        $titles       = $request->input('title', []);
        $periods      = $request->input('period', []);
        $dates        = $request->input('notice_date', []);
        $descriptions = $request->input('description', []);
        $orders       = $request->input('sort_order', []);

        $added = 0;

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            if ($title === '') {
                continue; // skip empty rows
            }

            $data = [
                'section'       => $section,
                'period'        => trim((string) ($periods[$i] ?? '')),
                'title'         => $title,
                'description'   => trim((string) ($descriptions[$i] ?? '')),
                'notice_date'   => trim((string) ($dates[$i] ?? '')),
                'sort_order'    => $orders[$i] ?? 0,
                'status'        => 1,
                'created_at'    => Carbon::now(),
                'created_by'    => Auth::id(),
            ];

            $newFile = $request->file("document_file.$i");
            if ($newFile && $newFile->isValid()) {
                $data['document_file'] = $this->uploadDocument($newFile);
            }

            Notice::create($data);
            $added++;
        }

        if ($added === 0) {
            return redirect()->back()->withInput()->with('error', 'Please fill at least one notice (Title is required).');
        }

        return redirect()->route('notices.index')->with('message', $added . ' notice(s) added successfully!');
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);

        return view('backend.public-notice.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'section'       => 'nullable|string|max:20',
            'period'        => 'nullable|string|max:100',
            'notice_date'   => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'sort_order'    => 'nullable|integer',
            'status'        => 'nullable|in:0,1',
            'document_file' => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:20480',
        ], [
            'title.required' => 'The Title is required.',
        ]);

        $section = $request->section ?? 'bomsc';
        if (!array_key_exists($section, Notice::SECTIONS)) {
            $section = 'bomsc';
        }

        $data = [
            'section'       => $section,
            'period'        => $request->period,
            'title'         => $request->title,
            'description'   => $request->description,
            'notice_date'   => $request->notice_date,
            'sort_order'    => $request->sort_order ?? 0,
            'status'        => $request->status ?? 1,
            'modified_at'   => Carbon::now(),
            'modified_by'   => Auth::id(),
        ];

        if ($request->hasFile('document_file')) {
            if ($notice->document_file) {
                $this->deleteDocument($notice->document_file);
            }
            $data['document_file'] = $this->uploadDocument($request->file('document_file'));
        }

        $notice->update($data);

        return redirect()->route('notices.index')->with('message', 'Notice has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $notice = Notice::findOrFail($id);
            $notice->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('notices.index')->with('message', 'Notice deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */

    private function uploadDocument($file)
    {
        $destination = public_path('public-notice/documents');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }
        $fileName = 'notice_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteDocument($fileName)
    {
        $path = public_path('public-notice/documents/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
