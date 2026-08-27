<?php

namespace App\Http\Controllers\Backend\PublicNotice;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeBannerDetails;
use App\Models\NoticeCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Public Notice > Menu & Pages.
 *
 * Manages the whole Public Notice mega-menu as a tree. A row can be a column
 * heading, a menu link, or a flyout link depending on which parent is chosen —
 * so new categories, sub-categories and pages are added without touching code.
 */
class NoticeCategoryController extends Controller
{
    /**
     * One screen, three tabs — the three things an admin actually does:
     * 1. Categories     — the menu columns
     * 2. Sub Categories — a heading inside a column that opens a fly-out
     * 3. Pages          — the actual pages, filed under a category or a sub category
     */
    public function index(Request $request)
    {
        $all = NoticeCategory::whereNull('deleted_at')->ordered()->get();

        $categories = $all->whereNull('parent_id')->values();
        $categoryIds = $categories->pluck('id');

        // Level 2 rows that act as headings (they hold pages inside a fly-out).
        $subCategories = $all->filter(fn ($c) =>
            $categoryIds->contains($c->parent_id) && $c->link_type === 'none'
        )->values();

        // Anything that renders a page, at either level.
        $pages = $all->where('link_type', '!=', 'none')->whereNotNull('parent_id')->values();

        $tab = in_array($request->query('tab'), ['categories', 'subcategories', 'pages'], true)
            ? $request->query('tab')
            : 'categories';

        // Fallback banner, used by any page that has not set its own.
        $banner = NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();

        return view('backend.public-notice.categories.index', compact('categories', 'subCategories', 'pages', 'all', 'tab', 'banner'));
    }

    /** Save the shared banner that pages fall back to when they have none of their own. */
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
            if ($banner && $banner->background_image) {
                $this->deleteFile($banner->background_image, 'public-notice/banner');
            }
            $data['background_image'] = $this->uploadFile($request->file('background_image'), 'public-notice/banner', 'notice_banner');
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

        return redirect()->route('notice-category.index')->with('message', 'Default banner saved successfully!');
    }

    /**
     * What each page design looks like, previewed with a real page that already
     * uses it, plus which document columns that design actually shows.
     */
    public const LAYOUT_GUIDE = [
        'bomsc' => [
            'summary' => 'Documents grouped under a date pill, shown two per row with a numbered badge.',
            'fields'  => ['Group (the date pill)', 'Title', 'PDF'],
        ],
        'boc' => [
            'summary' => 'Documents grouped into collapsible months, four per row. The first group opens by default.',
            'fields'  => ['Group (the month)', 'Title', 'PDF'],
        ],
        'auc' => [
            'summary' => 'A flat grid of notice cards with a date on top and a short description.',
            'fields'  => ['Date', 'Title', 'Description', 'PDF'],
        ],
        'list' => [
            'summary' => 'A flat grid of cards with a calendar icon, title and description. No grouping.',
            'fields'  => ['Title (e.g. FY 2025-26)', 'Description', 'PDF'],
        ],
        'grouped' => [
            'summary' => 'Documents grouped into collapsible sections (usually financial years), four per row.',
            'fields'  => ['Group (e.g. FY 2025-26)', 'Title', 'PDF'],
        ],
        'status' => [
            'summary' => 'Wide boxes with a calendar icon, title and a longer description underneath.',
            'fields'  => ['Title (e.g. FY 2025-26)', 'Description', 'PDF'],
        ],
    ];

    public function layoutGuide()
    {
        $guide = [];

        foreach (NoticeCategory::LAYOUTS as $key => $label) {
            $pages = NoticeCategory::live()->where('link_type', 'page')->where('layout', $key)->ordered()->get();

            // Prefer a page that already has documents, so the preview is not empty.
            $example = $pages->first(fn ($p) => $p->notices()->where('status', 1)->exists()) ?: $pages->first();

            $guide[] = [
                'key'     => $key,
                'label'   => $label,
                'summary' => self::LAYOUT_GUIDE[$key]['summary'] ?? '',
                'fields'  => self::LAYOUT_GUIDE[$key]['fields'] ?? [],
                'sample'  => $example && $example->slug ? route('frontend.notice_page', $example->slug) : null,
                'example' => $example->name ?? null,
                'used_by' => $pages->count(),
            ];
        }

        return view('backend.public-notice.layout-guide', compact('guide'));
    }

    public function create(Request $request)
    {
        $level    = $this->level($request->query('level'));
        $category = new NoticeCategory(['status' => 1, 'link_type' => $level === 2 ? 'none' : 'page']);
        $parents  = $this->parentOptions(null, $level);
        $parentId = $request->query('parent');

        return view('backend.public-notice.categories.create', compact('category', 'parents', 'parentId', 'level'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['created_at'] = Carbon::now();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('icon')) {
            $data['icon'] = $this->uploadFile($request->file('icon'), 'public-notice/icons', 'icon');
        }
        if ($request->hasFile('document_file')) {
            $data['document_file'] = $this->uploadFile($request->file('document_file'), 'public-notice/documents', 'doc');
        }
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->uploadFile($request->file('banner_image'), 'public-notice/banner', 'banner');
        }

        $category = NoticeCategory::create($data);

        $this->syncDocuments($request, $category);

        return redirect()->route('notice-category.index', ['tab' => $this->tabFor($category)])
            ->with('message', 'Saved successfully!');
    }

    public function edit($id)
    {
        $category = NoticeCategory::findOrFail($id);

        // Which tab this row belongs to, so the form shows the matching fields.
        $level   = !$category->parent_id ? 1 : ($category->link_type === 'none' ? 2 : 3);
        $parents = $this->parentOptions($category->id, $level);

        $documents = $category->notices()
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.public-notice.categories.edit', compact('category', 'parents', 'level', 'documents'));
    }

    public function update(Request $request, $id)
    {
        $category = NoticeCategory::findOrFail($id);

        $request->validate($this->rules($category->id), $this->messages());

        $data = $this->payload($request, $category);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($request->hasFile('icon')) {
            $this->deleteFile($category->icon, 'public-notice/icons');
            $data['icon'] = $this->uploadFile($request->file('icon'), 'public-notice/icons', 'icon');
        }
        if ($request->hasFile('document_file')) {
            $this->deleteFile($category->document_file, 'public-notice/documents');
            $data['document_file'] = $this->uploadFile($request->file('document_file'), 'public-notice/documents', 'doc');
        }
        if ($request->hasFile('banner_image')) {
            $this->deleteFile($category->banner_image, 'public-notice/banner');
            $data['banner_image'] = $this->uploadFile($request->file('banner_image'), 'public-notice/banner', 'banner');
        }

        $category->update($data);

        $this->syncDocuments($request, $category);

        return redirect()->route('notice-category.index', ['tab' => $this->tabFor($category->fresh())])
            ->with('message', 'Updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $category = NoticeCategory::findOrFail($id);

            if ($category->children()->exists()) {
                return redirect()->back()->with('error', 'Remove the items inside this one before deleting it.');
            }

            $category->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('notice-category.index')->with('message', 'Menu item deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    /** Fields shared by store() and update(). */
    private function payload(Request $request, ?NoticeCategory $existing = null)
    {
        $linkType = $request->input('link_type', 'page');

        // Built from the name on creation, then left alone — renaming a page
        // must not silently change a URL that is already published.
        $slug = $existing && $existing->slug
            ? $existing->slug
            : $this->uniqueSlug($request);

        return [
            'parent_id'     => $request->parent_id ?: null,
            'name'          => $request->name,
            'slug'          => $linkType === 'page' ? $slug : null,
            'link_type'     => $linkType,
            'layout'        => $linkType === 'page' ? $request->layout : null,
            'external_link' => $linkType === 'url' ? $request->external_link : null,
            'page_title'    => $request->page_title ?: $request->name,
            'page_intro'    => $request->page_intro,
            'banner_title'  => $request->banner_title,
            'alert_heading' => $request->alert_heading,
            'alert_text'    => $request->alert_text,
            'sort_order'    => $request->sort_order ?? 0,
            'status'        => $request->has('status') ? 1 : 0,
        ];
    }

    /**
     * The public URL is built from the page name, so the admin never types it.
     * A numeric suffix is added if another page already holds the same slug.
     */
    private function uniqueSlug(Request $request)
    {
        $base = Str::slug($request->name);
        if ($base === '') {
            $base = 'page';
        }

        $slug  = $base;
        $count = 2;

        while (NoticeCategory::where('slug', $slug)
            ->when($request->route('category'), fn ($q, $id) => $q->where('id', '!=', $id))
            ->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Save the documents typed into the page form.
     *
     * Rows are matched by their hidden id, so editing keeps a document's
     * uploaded file; a row removed in the form is soft-deleted here.
     */
    private function syncDocuments(Request $request, NoticeCategory $category): void
    {
        // Only real pages list documents.
        if ($category->link_type !== 'page') {
            return;
        }

        $ids          = $request->input('doc_id', []);
        $titles       = $request->input('doc_title', []);
        $groups       = $request->input('doc_group', []);
        $dates        = $request->input('doc_date', []);
        $descriptions = $request->input('doc_description', []);
        $links        = $request->input('doc_link', []);

        $keptIds = [];
        $order   = 0;

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);

            if ($title === '') {
                continue; // blank rows are ignored
            }

            $order += 10;

            $existing = !empty($ids[$i])
                ? Notice::where('id', $ids[$i])->where('notice_category_id', $category->id)->first()
                : null;

            $data = [
                'notice_category_id' => $category->id,
                'period'             => trim((string) ($groups[$i] ?? '')),
                'notice_date'        => trim((string) ($dates[$i] ?? '')),
                'title'              => $title,
                'description'        => trim((string) ($descriptions[$i] ?? '')),
                'document_link'      => trim((string) ($links[$i] ?? '')) ?: null,
                'sort_order'         => $order,
                'status'             => 1,
            ];

            $file = $request->file("doc_file.$i");
            if ($file && $file->isValid()) {
                if ($existing && $existing->document_file) {
                    $this->deleteFile($existing->document_file, 'public-notice/documents');
                }
                $data['document_file'] = $this->uploadFile($file, 'public-notice/documents', 'notice');
            }

            if ($existing) {
                $existing->update($data + ['modified_at' => Carbon::now(), 'modified_by' => Auth::id()]);
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = Notice::create($data + ['created_at' => Carbon::now(), 'created_by' => Auth::id()])->id;
            }
        }

        // Anything the admin deleted from the table disappears from the page.
        Notice::where('notice_category_id', $category->id)
            ->whereNull('deleted_at')
            ->whereNotIn('id', $keptIds ?: [0])
            ->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::id()]);
    }

    /** Which index tab a row should be listed under. */
    private function tabFor(NoticeCategory $category)
    {
        if (!$category->parent_id) {
            return 'categories';
        }

        return $category->link_type === 'none' ? 'subcategories' : 'pages';
    }

    /** Normalise the tab/level a form is working at. */
    private function level($value)
    {
        return in_array((int) $value, [1, 2, 3], true) ? (int) $value : 1;
    }

    /**
     * Valid parents for the level being edited:
     *   level 1 — none (it is a top-level column)
     *   level 2 — the columns
     *   level 3 — the columns and the sub categories
     */
    private function parentOptions($excludeId = null, $level = 3)
    {
        if ($level === 1) {
            return collect();
        }

        return NoticeCategory::whereNull('deleted_at')
            ->where(function ($q) use ($level) {
                $q->whereNull('parent_id');

                if ($level === 3) {
                    // Sub categories may also hold pages.
                    $q->orWhere(function ($sub) {
                        $sub->where('link_type', 'none')
                            ->whereIn('parent_id', function ($top) {
                                $top->select('id')->from('notice_categories')
                                    ->whereNull('parent_id')->whereNull('deleted_at');
                            });
                    });
                }
            })
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->ordered()
            ->get();
    }

    private function rules($id = null)
    {
        return [
            'name'          => 'required|string|max:255',
            'parent_id'     => 'nullable|integer|exists:notice_categories,id',
            'link_type'     => 'required|in:page,pdf,url,none',
            'layout'        => 'nullable|in:' . implode(',', array_keys(NoticeCategory::LAYOUTS)),
            'external_link' => 'nullable|url|max:500',
            'sort_order'    => 'nullable|integer',
            'icon'          => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'document_file' => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:20480',
            'banner_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'banner_title'  => 'nullable|string|max:255',
            'doc_title'         => 'nullable|array',
            'doc_title.*'       => 'nullable|string|max:255',
            'doc_group.*'       => 'nullable|string|max:100',
            'doc_date.*'        => 'nullable|string|max:100',
            'doc_description.*' => 'nullable|string',
            'doc_link.*'        => 'nullable|string|max:500',
            'doc_file.*'        => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:20480',
        ];
    }

    private function messages()
    {
        return [
            'name.required'      => 'The Menu Name is required.',
            'link_type.required' => 'Please choose what this menu item opens.',
            'external_link.url'  => 'The external link must be a full URL (https://...).',
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

    private function deleteFile($fileName, $folder)
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
