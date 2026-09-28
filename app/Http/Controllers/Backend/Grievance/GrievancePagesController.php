<?php

namespace App\Http\Controllers\Backend\Grievance;

use App\Http\Controllers\Controller;
use App\Models\GrievancePage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Grievance > Pages.
 *
 * Each row here is one page in the Grievance menu. A page can carry one of
 * the two grievance forms, or none at all where it is simply something to
 * read, so a new one needs nothing but this form.
 */
class GrievancePagesController extends Controller
{
    public function index()
    {
        $pages = GrievancePage::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('backend.grievance.pages.index', compact('pages'));
    }

    public function create()
    {
        $page = new GrievancePage(['form_type' => 'none', 'status' => 1]);

        return view('backend.grievance.pages.create', compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = $this->payload($request);
        $data['created_at'] = Carbon::now();
        $data['created_by'] = Auth::id();
        $data['banner_image']  = $this->file($request, 'banner_image', 'grievance/banner', 'grievance_banner');
        $data['document_file'] = $this->file($request, 'document_file', 'grievance/documents', 'grievance_doc');

        GrievancePage::create(array_filter($data, fn ($v) => $v !== null));

        return redirect()->route('grievance-pages.index')->with('message', 'Page added successfully!');
    }

    public function edit($id)
    {
        $page = GrievancePage::whereNull('deleted_at')->findOrFail($id);

        return view('backend.grievance.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = GrievancePage::whereNull('deleted_at')->findOrFail($id);

        $request->validate($this->rules($page->id), $this->messages());

        $data = $this->payload($request);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($file = $this->file($request, 'banner_image', 'grievance/banner', 'grievance_banner')) {
            $this->remove($page->banner_image, 'grievance/banner');
            $data['banner_image'] = $file;
        }

        if ($file = $this->file($request, 'document_file', 'grievance/documents', 'grievance_doc')) {
            $this->remove($page->document_file, 'grievance/documents');
            $data['document_file'] = $file;
        }

        $page->update($data);

        return redirect()->route('grievance-pages.index')->with('message', 'Page has been successfully updated!');
    }

    public function destroy($id)
    {
        $page = GrievancePage::whereNull('deleted_at')->findOrFail($id);

        // A deleted page keeps its row but frees its address, so the same
        // one can be used again without the delete having to be undone.
        $page->update([
            'slug'       => 'deleted-' . $page->id . '-' . $page->slug,
            'status'     => 0,
            'deleted_at' => Carbon::now(),
            'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('grievance-pages.index')->with('message', 'Page deleted successfully!');
    }

    /** Show or hide a page without deleting it. */
    public function toggle($id)
    {
        $page = GrievancePage::whereNull('deleted_at')->findOrFail($id);

        $page->update([
            'status'      => $page->status ? 0 : 1,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return back()->with('message', $page->status ? 'Page is now showing.' : 'Page is now hidden.');
    }

    /* ------------------------------------------------------------------ */

    private function payload(Request $request): array
    {
        return [
            'title'            => $request->title,
            'slug'             => $this->slug($request),
            'sort_order'       => (int) ($request->sort_order ?? 0),
            'status'           => $request->has('status') ? 1 : 0,
            'banner_title'     => $request->banner_title,
            'breadcrumb_child' => $request->breadcrumb_child ?: $request->title,
            'heading'          => $request->heading,
            'intro'            => $request->intro,
            'body'             => $request->body,
            'form_type'        => array_key_exists((string) $request->form_type, GrievancePage::FORMS)
                                  ? $request->form_type : 'none',
            'link_target'      => array_key_exists((string) $request->link_target, GrievancePage::LINK_TARGETS)
                                  ? $request->link_target : 'page',
            'contacts'         => $this->contacts($request),
            'note'             => $request->note,
            'document_label'   => $request->document_label,
            'external_link'    => $request->external_link,
        ];
    }

    /**
     * The address of the page.
     *
     * A page that already has one keeps it, so an address that has been shared
     * does not change under someone just because the title was tidied up.
     */
    private function slug(Request $request): string
    {
        return Str::slug($request->slug ?: $request->title);
    }

    /** The people to write to: [{ label, value, kind }]. */
    private function contacts(Request $request): array
    {
        $labels = $request->input('contact_label', []);
        $values = $request->input('contact_value', []);
        $kinds  = $request->input('contact_kind', []);

        $out = [];
        foreach ($labels as $i => $label) {
            $label = trim((string) $label);
            $value = trim((string) ($values[$i] ?? ''));

            if ($label === '' && $value === '') { continue; }

            $kind = (string) ($kinds[$i] ?? 'text');

            $out[] = [
                'label' => $label,
                'value' => $value,
                'kind'  => array_key_exists($kind, GrievancePage::CONTACT_KINDS) ? $kind : 'text',
            ];
        }

        return $out;
    }

    private function rules($ignoreId = null): array
    {
        return [
            'title'         => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'sort_order'    => 'nullable|integer',
            'form_type'     => 'nullable|string|in:' . implode(',', array_keys(GrievancePage::FORMS)),
            'link_target'   => 'nullable|string|in:' . implode(',', array_keys(GrievancePage::LINK_TARGETS)),
            'banner_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'document_file' => 'nullable|mimes:pdf|max:20480',
            'external_link' => 'nullable|string|max:500',
        ];
    }

    private function messages(): array
    {
        return [
            'title.required'     => 'Please give the page a title.',
            'document_file.mimes' => 'The document must be a PDF.',
        ];
    }

    /** Save an uploaded file, returning its name, or null when none was sent. */
    private function file(Request $request, string $field, string $folder, string $prefix): ?string
    {
        if (!$request->hasFile($field)) { return null; }

        $file = $request->file($field);
        $path = public_path($folder);

        if (!is_dir($path)) { mkdir($path, 0775, true); }

        $name = $prefix . '_' . time() . '_' . Str::random(8) . '.'
                . strtolower($file->getClientOriginalExtension());

        $file->move($path, $name);

        return $name;
    }

    private function remove(?string $name, string $folder): void
    {
        if (!$name) { return; }

        $path = public_path($folder . '/' . $name);

        if (is_file($path)) { @unlink($path); }
    }
}
