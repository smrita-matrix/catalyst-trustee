<?php

namespace App\Http\Controllers\Backend\services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\ServiceDocument;
use App\Models\ServiceDocumentPage;
use App\Models\ProductCategory;

/**
 * The editor behind a service that is a page of papers.
 *
 * The documents are rows rather than a repeater field, so the client can add
 * one without scrolling past everything else on the page, and so a long list
 * stays workable.
 */
class ServiceDocumentsController extends Controller
{
    public function edit($productId)
    {
        $product = ProductCategory::findOrFail($productId);

        $page = ServiceDocumentPage::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $documents = ServiceDocument::where('product_id', $product->id)
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('backend.services.documents.manage', compact('product', 'page', 'documents'));
    }

    public function update(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);

        $request->validate([
            'banner_background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        $page = ServiceDocumentPage::where('product_id', $product->id)
            ->whereNull('deleted_at')->first();

        $data = [
            'product_id'               => $product->id,
            'banner_title'             => $request->banner_title,
            'banner_breadcrumb_parent' => $request->banner_breadcrumb_parent,
            'banner_breadcrumb_child'  => $request->banner_breadcrumb_child,
            'page_title'               => $request->page_title,
            'page_intro'               => $request->page_intro,
        ];

        if ($request->hasFile('banner_background_image')) {
            $data['banner_background_image'] = $this->upload($request->file('banner_background_image'), 'banner', 'sd_banner');
        }

        if ($page) {
            $page->update($data + ['modified_at' => Carbon::now(), 'modified_by' => Auth::id()]);
        } else {
            ServiceDocumentPage::create($data + ['created_at' => Carbon::now(), 'created_by' => Auth::id()]);
        }

        return redirect()->route('service-documents.edit', $product->id)->with('message', 'Page saved successfully!');
    }

    /** Add one document to the page. */
    public function storeDocument(Request $request, $productId)
    {
        $product = ProductCategory::findOrFail($productId);

        $request->validate([
            'title'         => 'required|string|max:255',
            'document_file' => 'nullable|mimes:pdf|max:20480',
            'document_link' => 'nullable|string|max:500',
        ], [
            'title.required'      => 'Please give the document a name.',
            'document_file.mimes' => 'The document must be a PDF.',
        ]);

        ServiceDocument::create([
            'product_id'    => $product->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'document_file' => $request->hasFile('document_file')
                               ? $this->upload($request->file('document_file'), 'files', 'sd_doc')
                               : null,
            'document_link' => $request->document_link,
            'sort_order'    => (int) ($request->sort_order ?? 0),
            'status'        => 1,
            'created_at'    => Carbon::now(),
            'created_by'    => Auth::id(),
        ]);

        return back()->with('message', 'Document added successfully!');
    }

    public function updateDocument(Request $request, $productId, $id)
    {
        $doc = ServiceDocument::whereNull('deleted_at')->findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'document_file' => 'nullable|mimes:pdf|max:20480',
        ]);

        $data = [
            'title'         => $request->title,
            'description'   => $request->description,
            'document_link' => $request->document_link,
            'sort_order'    => (int) ($request->sort_order ?? 0),
            'modified_at'   => Carbon::now(),
            'modified_by'   => Auth::id(),
        ];

        if ($request->hasFile('document_file')) {
            $this->remove($doc->document_file, 'files');
            $data['document_file'] = $this->upload($request->file('document_file'), 'files', 'sd_doc');
        }

        $doc->update($data);

        return back()->with('message', 'Document updated successfully!');
    }

    public function destroyDocument($productId, $id)
    {
        $doc = ServiceDocument::whereNull('deleted_at')->findOrFail($id);

        $doc->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::id()]);

        return back()->with('message', 'Document removed.');
    }

    /* ------------------------------------------------------------------ */

    private function upload($file, string $folder, string $prefix): string
    {
        $path = public_path('service-uploads/documents/' . $folder);

        if (!is_dir($path)) { mkdir($path, 0775, true); }

        $name = $prefix . '_' . time() . '_' . Str::random(8) . '.'
                . strtolower($file->getClientOriginalExtension());

        $file->move($path, $name);

        return $name;
    }

    private function remove(?string $name, string $folder): void
    {
        if (!$name) { return; }

        $path = public_path('service-uploads/documents/' . $folder . '/' . $name);

        if (is_file($path)) { @unlink($path); }
    }
}
