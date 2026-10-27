<?php

namespace App\Http\Controllers\Backend\PublicNotice;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeDetailItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Public Notice > a notice's own page.
 *
 * A notice normally opens one document. Switched to a page, its card on the
 * website leads to a page of its own - a banner, then documents under their
 * headings - as each company under Default Cases does.
 */
class NoticeDetailController extends Controller
{
    public function edit($id)
    {
        $notice   = Notice::with('noticePage', 'parentNotice')->whereNull('deleted_at')->findOrFail($id);
        $items    = $notice->detailItems()->get();
        $category = $notice->noticePage;
        $parent   = $notice->parentNotice;
        $subPages = $notice->subPages()->get();

        return view('backend.public-notice.detail.edit', compact('notice', 'items', 'category', 'parent', 'subPages'));
    }

    /**
     * Start a page inside this one (DHFL > DHFL IBC). It opens straight into
     * its own editor; link to it from this page's documents with its address.
     */
    public function storeSubPage(Request $request, $id)
    {
        $notice = Notice::whereNull('deleted_at')->whereNull('parent_notice_id')->findOrFail($id);

        $request->validate(['sub_title' => 'required|string|max:255'], ['sub_title.required' => 'Give the new page a name.']);

        $sub = new Notice([
            'notice_category_id'  => $notice->notice_category_id,
            'parent_notice_id'    => $notice->id,
            'title'               => $request->sub_title,
            'has_detail_page'     => 1,
            'detail_banner_title' => $request->sub_title,
            // Starts on the parent's banner until it is given its own.
            'detail_banner_image' => $notice->detail_banner_image,
            'sort_order'          => ($notice->subPages()->max('sort_order') ?? 0) + 10,
            'status'              => 1,
            'created_at'          => Carbon::now(),
            'created_by'          => Auth::id(),
        ]);
        $sub->detail_slug = $this->uniqueSlug($sub, $request->sub_title);
        $sub->save();

        return redirect()->route('notice-detail.edit', $sub->id)->with('message', 'Page added - now add its documents.');
    }

    /** Remove a page inside another notice's page. Cards on the list are removed from their page's form instead. */
    public function destroySubPage($id)
    {
        $sub = Notice::whereNull('deleted_at')->whereNotNull('parent_notice_id')->findOrFail($id);
        $sub->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::id()]);

        return redirect()->route('notice-detail.edit', $sub->parent_notice_id)->with('message', 'Page removed.');
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::whereNull('deleted_at')->findOrFail($id);

        $request->validate([
            'detail_banner_title' => 'required|string|max:255',
            'detail_banner_image' => ($notice->detail_banner_image ? 'nullable' : 'required')
                . '|image|mimes:jpg,jpeg,png,webp|max:8192',
            'item_title.*'        => 'nullable|string|max:500',
            'item_group.*'        => 'nullable|string|max:255',
            'item_link.*'         => 'nullable|string|max:500',
            'item_file.*'         => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:20480',
        ], [
            'detail_banner_title.required' => 'The Banner Heading is required.',
            'detail_banner_image.required' => 'The Banner Image is required.',
        ]);

        $data = [
            'has_detail_page'     => $request->has('has_detail_page') ? 1 : 0,
            'detail_banner_title' => $request->detail_banner_title,
            // Made once from the heading, then kept: the address may already be shared.
            'detail_slug'         => $notice->detail_slug ?: $this->uniqueSlug($notice, $request->detail_banner_title),
            'modified_at'         => Carbon::now(),
            'modified_by'         => Auth::id(),
        ];

        if ($request->hasFile('detail_banner_image')) {
            $this->deleteFile($notice->detail_banner_image, 'public-notice/detail-banner');
            $data['detail_banner_image'] = $this->uploadFile($request->file('detail_banner_image'), 'public-notice/detail-banner', 'detail_banner');
        }

        $notice->update($data);

        $this->syncItems($request, $notice);

        return redirect()->route('notice-detail.edit', $notice->id)->with('message', 'Page saved successfully!');
    }

    /**
     * Save the documents table. Rows are matched on their hidden id so an
     * edit keeps the uploaded file; a row removed in the form is soft-deleted.
     */
    private function syncItems(Request $request, Notice $notice): void
    {
        $ids        = $request->input('item_id', []);
        $titles     = $request->input('item_title', []);
        $groups     = $request->input('item_group', []);
        $links      = $request->input('item_link', []);
        $highlights = $request->input('item_highlight', []);

        $keptIds = [];
        $order   = 0;

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            if ($title === '') {
                continue;
            }

            $order += 10;

            $existing = !empty($ids[$i])
                ? NoticeDetailItem::where('id', $ids[$i])->where('notice_id', $notice->id)->first()
                : null;

            $highlight = !empty($highlights[$i]) ? 1 : 0;

            $row = [
                'notice_id'     => $notice->id,
                'group_title'   => $highlight ? null : (trim((string) ($groups[$i] ?? '')) ?: null),
                'title'         => $title,
                'document_link' => trim((string) ($links[$i] ?? '')) ?: null,
                'is_highlight'  => $highlight,
                'sort_order'    => $order,
                'status'        => 1,
            ];

            $file = $request->file("item_file.$i");
            if ($file && $file->isValid()) {
                if ($existing && $existing->document_file) {
                    $this->deleteFile($existing->document_file, 'public-notice/detail-documents');
                }
                $row['document_file'] = $this->uploadFile($file, 'public-notice/detail-documents', 'detail');
            }

            if ($existing) {
                $existing->update($row + ['modified_at' => Carbon::now(), 'modified_by' => Auth::id()]);
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = NoticeDetailItem::create($row + ['created_at' => Carbon::now(), 'created_by' => Auth::id()])->id;
            }
        }

        NoticeDetailItem::where('notice_id', $notice->id)
            ->whereNull('deleted_at')
            ->whereNotIn('id', $keptIds ?: [0])
            ->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::id()]);
    }

    /** A slug no other notice on the same page is using. */
    private function uniqueSlug(Notice $notice, $heading)
    {
        $base = Str::slug($heading ?: $notice->title) ?: 'details';
        $slug = $base;
        $n    = 2;

        while (Notice::where('notice_category_id', $notice->notice_category_id)
            ->when($notice->parent_notice_id,
                fn ($q) => $q->where('parent_notice_id', $notice->parent_notice_id),
                fn ($q) => $q->whereNull('parent_notice_id'))
            ->where('id', '!=', (int) $notice->id)
            ->where('detail_slug', $slug)
            ->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
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
        // Imported documents and the starting banner are shared between
        // companies; keep a file another row still uses.
        $inUse = $folder === 'public-notice/detail-banner'
            ? Notice::whereNull('deleted_at')->where('detail_banner_image', $fileName)->count()
            : NoticeDetailItem::whereNull('deleted_at')->where('document_file', $fileName)->count();
        if ($inUse > 1) {
            return;
        }
        $path = public_path($folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
