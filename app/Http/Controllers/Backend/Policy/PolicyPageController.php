<?php

namespace App\Http\Controllers\Backend\Policy;

use App\Http\Controllers\Controller;
use App\Models\PolicyPage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Policy Pages — the legal pages linked from the footer.
 *
 * One row per page (Privacy Policy, Terms of Use, Disclaimer …). The body is a
 * repeater of headings and paragraphs, so a new section is added from the
 * dashboard without a developer.
 */
class PolicyPageController extends Controller
{
    public function index()
    {
        $pages = PolicyPage::whereNull('deleted_at')->ordered()->get();

        return view('backend.policy.index', compact('pages'));
    }

    public function create()
    {
        $page = new PolicyPage();

        return view('backend.policy.create', compact('page'));
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

        PolicyPage::create($data);

        return redirect()->route('policy-pages.index')->with('message', 'Policy page saved successfully!');
    }

    public function edit($id)
    {
        $page = PolicyPage::findOrFail($id);

        return view('backend.policy.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = PolicyPage::findOrFail($id);

        $request->validate($this->rules($page->id), $this->messages());

        $data = $this->payload($request, $page);
        $data['modified_at'] = Carbon::now();
        $data['modified_by'] = Auth::id();

        if ($request->hasFile('banner_image')) {
            $this->deleteFile($page->banner_image);
            $data['banner_image'] = $this->upload($request->file('banner_image'));
        }

        $page->update($data);

        return redirect()->route('policy-pages.index')->with('message', 'Policy page has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            PolicyPage::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('policy-pages.index')->with('message', 'Policy page deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function payload(Request $request, ?PolicyPage $page = null): array
    {
        // An empty slug box means "work it out from the title", which is what an
        // admin expects. An existing page keeps its slug so live links survive.
        $slug = trim((string) $request->input('slug'));
        if ($slug === '') {
            $slug = $page?->slug ?: Str::slug($request->input('title'));
        }

        return [
            'title'            => $request->title,
            'slug'             => Str::slug($slug),
            'breadcrumb_child' => $request->breadcrumb_child,
            'intro_text'       => $request->intro_text,
            'sections'         => $this->cleanSections($request->input('sections', [])),
            'effective_on'     => $request->effective_on ?: null,
            'show_in_footer'   => $request->boolean('show_in_footer'),
            'status'           => $request->boolean('status'),
            'sort_order'       => (int) $request->input('sort_order', 0),
        ];
    }

    /** Drop the blank rows an admin leaves behind in the repeater. */
    private function cleanSections($rows): array
    {
        $clean = [];

        foreach ((array) $rows as $row) {
            $heading = trim((string) ($row['heading'] ?? ''));
            $body    = trim((string) ($row['body'] ?? ''));

            if ($heading === '' && $body === '') {
                continue;
            }

            $clean[] = ['heading' => $heading, 'body' => $body];
        }

        return $clean;
    }

    private function rules($ignoreId = null): array
    {
        return [
            'title'            => 'required|string|max:255',
            'slug'             => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', Rule::unique('policy_pages', 'slug')->ignore($ignoreId)],
            'breadcrumb_child' => 'nullable|string|max:255',
            'banner_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'intro_text'       => 'nullable|string',
            'sections'         => 'nullable|array',
            'sections.*.heading' => 'nullable|string|max:255',
            'sections.*.body'    => 'nullable|string',
            'effective_on'     => 'nullable|date',
            'sort_order'       => 'nullable|integer|min:0',
        ];
    }

    private function messages(): array
    {
        return [
            'title.required'   => 'Please enter the page title.',
            'slug.regex'       => 'The web address may use small letters, numbers and hyphens only — for example privacy-policy.',
            'slug.unique'      => 'Another policy page already uses that web address.',
            'banner_image.max' => 'The banner image must not be larger than 8MB.',
        ];
    }

    private function upload($file): string
    {
        $destination = public_path('policy/banner');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'policy_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteFile($fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = public_path('policy/banner/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
