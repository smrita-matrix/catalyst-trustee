<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\FooterDetails;


class FooterDetailsController extends Controller
{
    private const IMAGE_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    public function index()
    {
        $footer = FooterDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.footer-details.index', compact('footer'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.footer-details.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $data = [
            'description'  => $request->description,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'social_links' => $this->buildSocialLinks($request),
            'created_at'   => Carbon::now(),
            'created_by'   => Auth::id(),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadImage($request->file('logo'));
        }

        FooterDetails::create($data);

        return redirect()->route('footer-details.index')->with('message', 'Footer added successfully!');
    }

    public function edit($id)
    {
        $footer = FooterDetails::findOrFail($id);

        return view('backend.home-page.footer-details.edit', compact('footer'));
    }

    public function update(Request $request, $id)
    {
        $footer = FooterDetails::findOrFail($id);

        $request->validate($this->rules(), $this->messages());

        $data = [
            'description'  => $request->description,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'social_links' => $this->buildSocialLinks($request),
            'modified_at'  => Carbon::now(),
            'modified_by'  => Auth::id(),
        ];

        if ($request->hasFile('logo')) {
            if ($footer->logo) {
                $this->deleteImage($footer->logo);
            }
            $data['logo'] = $this->uploadImage($request->file('logo'));
        }

        $footer->update($data);

        return redirect()->route('footer-details.index')->with('message', 'Footer has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $footer = FooterDetails::findOrFail($id);
            $footer->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('footer-details.index')->with('message', 'Footer deleted successfully!');
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
            'description'   => 'nullable|string',
            'phone'         => 'nullable|string|max:100',
            'email'         => 'nullable|string|max:150',
            'address'       => 'nullable|string',
            'logo'          => ['nullable', 'file', 'max:2048', $this->imageExtensionRule()],
            'social_icon'   => 'nullable|array',
            'social_icon.*' => 'nullable|string|max:100',
            'social_url.*'  => 'nullable|string|max:255',
        ];
    }

    private function messages()
    {
        return [
            'logo.max' => 'The logo must not be larger than 2MB.',
        ];
    }

    /**
     * Build the social links JSON array [{ icon, url }].
     */
    private function buildSocialLinks(Request $request)
    {
        $icons = $request->input('social_icon', []);
        $urls  = $request->input('social_url', []);

        $links = [];
        foreach ($icons as $i => $icon) {
            $icon = trim((string) $icon);
            $url  = trim((string) ($urls[$i] ?? ''));

            if ($icon === '' && $url === '') {
                continue;
            }

            $links[] = [
                'icon' => $icon,
                'url'  => $url,
            ];
        }

        return $links;
    }

    private function uploadImage($file)
    {
        $destination = public_path('home/footer');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'footer_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        $path = public_path('home/footer/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function imageExtensionRule()
    {
        return function ($attribute, $value, $fail) {
            if (!$value) {
                return;
            }

            $ext = strtolower($value->getClientOriginalExtension());
            if (!in_array($ext, self::IMAGE_EXTENSIONS, true)) {
                $fail('The logo must be a file of type: ' . implode(', ', self::IMAGE_EXTENSIONS) . '.');
            }
        };
    }
}
