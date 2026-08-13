<?php

namespace App\Http\Controllers\Backend\Newsletter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\NewsletterBannerDetails;

class ArticleController extends Controller
{
    public function index()
    {
        $banner = NewsletterBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $articles = Article::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // group by year, numeric years first (desc), then non-numeric (Archive) last
        $groups  = $articles->groupBy(fn ($a) => trim((string) $a->year) !== '' ? $a->year : 'Archive');
        $numeric = $groups->keys()->filter(fn ($y) => is_numeric($y))->sortDesc()->values();
        $others  = $groups->keys()->reject(fn ($y) => is_numeric($y))->values();
        $orderedKeys = $numeric->merge($others);

        return view('backend.newsletter.articles.index', compact('banner', 'groups', 'orderedKeys'));
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

        $banner = NewsletterBannerDetails::whereNull('deleted_at')->latest('id')->first();

        $data = [
            'title'             => $request->title,
            'breadcrumb_parent' => $request->breadcrumb_parent,
            'breadcrumb_child'  => $request->breadcrumb_child,
        ];

        if ($request->hasFile('background_image')) {
            if ($banner && $banner->background_image) {
                $this->deleteFile('newsletter/banner', $banner->background_image);
            }
            $data['background_image'] = $this->uploadFile($request->file('background_image'), 'newsletter/banner', 'nl_banner');
        }

        if ($banner) {
            $data['modified_at'] = Carbon::now();
            $data['modified_by'] = Auth::id();
            $banner->update($data);
        } else {
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::id();
            NewsletterBannerDetails::create($data);
        }

        return redirect()->route('articles.index')->with('message', 'Banner saved successfully!');
    }

    /* -------------------- Articles CRUD -------------------- */

    public function create(Request $request)
    {
        $year = $request->query('year', '');
        return view('backend.newsletter.articles.create', compact('year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|array',
            'title.*'    => 'nullable|string|max:255',
            'year.*'     => 'nullable|string|max:50',
            'sort_order.*' => 'nullable|integer',
            'image.*'    => 'nullable|mimes:webp|max:2048',
            'pdf_file.*' => 'nullable|mimes:pdf|max:25600',
        ], [
            'title.required'  => 'Please add at least one article.',
            'image.*.mimes'   => 'The cover image must be a WebP (.webp) file.',
            'image.*.max'     => 'The cover image must not be larger than 2 MB.',
            'pdf_file.*.mimes'=> 'The PDF must be a .pdf file.',
            'pdf_file.*.max'  => 'The PDF must not be larger than 25 MB.',
        ]);

        $titles = $request->input('title', []);
        $years  = $request->input('year', []);
        $orders = $request->input('sort_order', []);

        $added = 0;

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            if ($title === '') {
                continue;
            }

            $data = [
                'year'       => trim((string) ($years[$i] ?? '')),
                'title'      => $title,
                'sort_order' => $orders[$i] ?? 0,
                'status'     => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ];

            $img = $request->file("image.$i");
            if ($img && $img->isValid()) {
                $data['image'] = $this->uploadFile($img, 'article/image', 'article');
            }
            $pdf = $request->file("pdf_file.$i");
            if ($pdf && $pdf->isValid()) {
                $data['pdf_file'] = $this->uploadFile($pdf, 'article/pdf', 'article_pdf');
            }

            Article::create($data);
            $added++;
        }

        if ($added === 0) {
            return redirect()->back()->withInput()->with('error', 'Please fill at least one article (Title is required).');
        }

        return redirect()->route('articles.index')->with('message', $added . ' article(s) added successfully!');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('backend.newsletter.articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:255',
            'year'       => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status'     => 'nullable|in:0,1',
            'image'      => 'nullable|mimes:webp|max:2048',
            'pdf_file'   => 'nullable|mimes:pdf|max:25600',
        ], [
            'title.required'  => 'The Title is required.',
            'image.mimes'     => 'The cover image must be a WebP (.webp) file.',
            'image.max'       => 'The cover image must not be larger than 2 MB.',
            'pdf_file.mimes'  => 'The PDF must be a .pdf file.',
            'pdf_file.max'    => 'The PDF must not be larger than 25 MB.',
        ]);

        $data = [
            'year'       => $request->year,
            'title'      => $request->title,
            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->status ?? 1,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ];

        if ($request->hasFile('image')) {
            if ($article->image && !\Illuminate\Support\Str::startsWith($article->image, ['http://', 'https://'])) {
                $this->deleteFile('article/image', $article->image);
            }
            $data['image'] = $this->uploadFile($request->file('image'), 'article/image', 'article');
        }
        if ($request->hasFile('pdf_file')) {
            if ($article->pdf_file && !\Illuminate\Support\Str::startsWith($article->pdf_file, ['http://', 'https://'])) {
                $this->deleteFile('article/pdf', $article->pdf_file);
            }
            $data['pdf_file'] = $this->uploadFile($request->file('pdf_file'), 'article/pdf', 'article_pdf');
        }

        $article->update($data);

        return redirect()->route('articles.index')->with('message', 'Article has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('articles.index')->with('message', 'Article deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* -------------------- Helpers -------------------- */

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
        $path = public_path($folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
