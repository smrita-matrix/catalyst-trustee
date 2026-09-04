<?php

namespace App\Http\Controllers\Backend\about;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\GroupCompaniesOverviewDetails;


class GroupCompaniesOverviewDetailsController extends Controller
{
    public function index()
    {
        $overview = GroupCompaniesOverviewDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.about-us.group-companies.overview-details.index', compact('overview'));
    }

    public function create(Request $request)
    {
        return view('backend.about-us.group-companies.overview-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = [
            'heading'     => $request->heading,
            'description' => $request->description,
            'entities'    => $this->buildEntities($request, []),
            'created_at'  => Carbon::now(),
            'created_by'  => Auth::id(),
        ];

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $this->uploadImage($request->file('main_image'));
        }
        if ($request->hasFile('small_image')) {
            $data['small_image'] = $this->uploadImage($request->file('small_image'));
        }

        GroupCompaniesOverviewDetails::create($data);

        return redirect()->route('group-companies-overview-details.index')->with('message', 'Overview added successfully!');
    }

    public function edit($id)
    {
        $overview = GroupCompaniesOverviewDetails::findOrFail($id);

        return view('backend.about-us.group-companies.overview-details.edit', compact('overview'));
    }

    public function update(Request $request, $id)
    {
        $overview = GroupCompaniesOverviewDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $existingEntities = $overview->entities ?? [];
        $entities = $this->buildEntities($request, $existingEntities);
        $this->cleanupUnusedEntityImages($existingEntities, $entities);

        $data = [
            'heading'     => $request->heading,
            'description' => $request->description,
            'entities'    => $entities,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ];

        if ($request->hasFile('main_image')) {
            if ($overview->main_image) {
                $this->deleteImage($overview->main_image);
            }
            $data['main_image'] = $this->uploadImage($request->file('main_image'));
        }
        if ($request->hasFile('small_image')) {
            if ($overview->small_image) {
                $this->deleteImage($overview->small_image);
            }
            $data['small_image'] = $this->uploadImage($request->file('small_image'));
        }

        $overview->update($data);

        return redirect()->route('group-companies-overview-details.index')->with('message', 'Overview has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $overview = GroupCompaniesOverviewDetails::findOrFail($id);
            $overview->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('group-companies-overview-details.index')->with('message', 'Overview deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function rules()
    {
        return [
            'heading'             => 'required|string',
            'description'         => 'nullable|string',
            'main_image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'small_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'entity_title.*'      => 'nullable|string|max:255',
            'entity_description.*' => 'nullable|string',
            'entity_link.*'       => 'nullable|string|max:255',
            'entity_image.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:8192',
        ];
    }

    private function messages()
    {
        return [
            'heading.required'  => 'The Heading is required.',
            'main_image.max'    => 'The main image must not be larger than 8MB.',
            'small_image.max'   => 'The small image must not be larger than 8MB.',
            'entity_image.*.max' => 'Each entity logo must not be larger than 8MB.',
        ];
    }

    /**
     * Build the entities JSON array [{ image, title, description, link }].
     */
    private function buildEntities(Request $request, array $existingItems)
    {
        $titles       = $request->input('entity_title', []);
        $descriptions = $request->input('entity_description', []);
        $links        = $request->input('entity_link', []);
        $existingImgs = $request->input('entity_existing_image', []);

        $entities = [];

        foreach ($titles as $i => $title) {
            $title       = trim((string) $title);
            $description = trim((string) ($descriptions[$i] ?? ''));
            $link        = trim((string) ($links[$i] ?? ''));
            $existingImg = $existingImgs[$i] ?? null;

            $image = $existingImg;
            $newFile = $request->file("entity_image.$i");
            if ($newFile && $newFile->isValid()) {
                $image = $this->uploadImage($newFile);
                if ($existingImg) {
                    $this->deleteImage($existingImg);
                }
            }

            if ($title === '' && $description === '' && $link === '' && !$image) {
                continue;
            }

            $entities[] = [
                'image'       => $image,
                'title'       => $title,
                'description' => $description,
                'link'        => $link,
            ];
        }

        return $entities;
    }

    private function cleanupUnusedEntityImages(array $oldItems, array $newItems)
    {
        $newImages = array_filter(array_column($newItems, 'image'));

        foreach ($oldItems as $old) {
            $oldImage = $old['image'] ?? null;
            if ($oldImage && !in_array($oldImage, $newImages, true)) {
                $this->deleteImage($oldImage);
            }
        }
    }

    private function uploadImage($file)
    {
        $destination = public_path('about-us/group-companies/overview');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'gc_overview_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('about-us/group-companies/overview/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
