<?php

namespace App\Http\Controllers\Backend\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ContactPageDetails;
use App\Models\ContactOffice;

class ContactController extends Controller
{
    public function index()
    {
        $content = ContactPageDetails::whereNull('deleted_at')->latest('id')->first();
        $offices = ContactOffice::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('backend.contact.index', compact('content', 'offices'));
    }

    /* -------------------- Page content (single record) -------------------- */

    public function editContent()
    {
        $content = ContactPageDetails::whereNull('deleted_at')->latest('id')->first();
        return view('backend.contact.content', compact('content'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'banner_background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'form_image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'services_options'        => 'nullable|string|max:5000',
            'location_options'        => 'nullable|string|max:5000',
            'notify_email'            => 'nullable|email|max:255',
            'notify_cc'               => 'nullable|string|max:500',
        ]);

        $content = ContactPageDetails::whereNull('deleted_at')->latest('id')->first();

        $data = $request->only([
            'banner_title', 'banner_breadcrumb_parent',
            'info_heading', 'phone', 'phone_link', 'email', 'email_link', 'address', 'address_link',
            'enquiry_heading', 'form_heading', 'services_options', 'location_options',
            'notify_email', 'notify_cc',
            'office_heading', 'main_office_subtitle', 'other_office_subtitle', 'notice_text',
        ]);

        foreach (['banner_background_image' => 'banner', 'form_image' => 'form'] as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($content && $content->$field) {
                    $this->deleteImage($content->$field, $folder);
                }
                $data[$field] = $this->uploadImage($request->file($field), $folder);
            }
        }

        if ($content) {
            $data['modified_at'] = Carbon::now();
            $data['modified_by'] = Auth::id();
            $content->update($data);
        } else {
            $data['created_at'] = Carbon::now();
            $data['created_by'] = Auth::id();
            ContactPageDetails::create($data);
        }

        return redirect()->route('contact.index')->with('message', 'Contact page content saved successfully!');
    }

    /* -------------------- Offices CRUD -------------------- */

    public function create(Request $request)
    {
        $type = $request->query('type', 'branch');
        if (!array_key_exists($type, ContactOffice::TYPES)) {
            $type = 'branch';
        }
        return view('backend.contact.create', compact('type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'     => 'required|string|max:20',
            'city'     => 'required|array',
            'city.*'   => 'nullable|string|max:255',
        ], [
            'city.required' => 'Please add at least one office.',
        ]);

        $type = array_key_exists($request->type, ContactOffice::TYPES) ? $request->type : 'branch';

        $cities    = $request->input('city', []);
        $roles     = $request->input('role', []);
        $addresses = $request->input('address', []);
        $contacts  = $request->input('contact', []);
        $emails    = $request->input('email', []);
        $maps      = $request->input('map_link', []);
        $tags      = $request->input('tag', []);
        $orders    = $request->input('sort_order', []);

        $added = 0;
        foreach ($cities as $i => $city) {
            $city = trim((string) $city);
            if ($city === '') {
                continue;
            }
            ContactOffice::create([
                'type'       => $type,
                'city'       => $city,
                'role'       => trim((string) ($roles[$i] ?? '')),
                'address'    => trim((string) ($addresses[$i] ?? '')),
                'contact'    => trim((string) ($contacts[$i] ?? '')),
                'email'      => trim((string) ($emails[$i] ?? '')),
                'map_link'   => trim((string) ($maps[$i] ?? '')),
                'tag'        => trim((string) ($tags[$i] ?? '')),
                'sort_order' => $orders[$i] ?? 0,
                'status'     => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
            $added++;
        }

        if ($added === 0) {
            return redirect()->back()->withInput()->with('error', 'Please fill at least one office (City is required).');
        }

        return redirect()->route('contact.index')->with('message', $added . ' office(s) added successfully!');
    }

    public function edit($id)
    {
        $office = ContactOffice::findOrFail($id);
        return view('backend.contact.edit', compact('office'));
    }

    public function update(Request $request, $id)
    {
        $office = ContactOffice::findOrFail($id);

        $request->validate([
            'type'    => 'required|string|max:20',
            'city'    => 'required|string|max:255',
            'status'  => 'nullable|in:0,1',
        ], [
            'city.required' => 'The City is required.',
        ]);

        $type = array_key_exists($request->type, ContactOffice::TYPES) ? $request->type : 'branch';

        $office->update([
            'type'       => $type,
            'city'       => $request->city,
            'role'       => $request->role,
            'address'    => $request->address,
            'contact'    => $request->contact,
            'email'      => $request->email,
            'map_link'   => $request->map_link,
            'tag'        => $request->tag,
            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->status ?? 1,
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('contact.index')->with('message', 'Office has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $office = ContactOffice::findOrFail($id);
            $office->update(['deleted_at' => Carbon::now(), 'deleted_by' => Auth::id()]);
            return redirect()->route('contact.index')->with('message', 'Office deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* -------------------- Helpers -------------------- */

    private function uploadImage($file, $folder)
    {
        $destination = public_path('contact-media/' . $folder);
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }
        $fileName = 'contact_' . $folder . '_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function deleteImage($fileName, $folder)
    {
        $path = public_path('contact-media/' . $folder . '/' . $fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
