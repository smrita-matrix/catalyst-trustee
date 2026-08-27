<?php

namespace App\Http\Controllers\Backend\Contact;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/** Contact Us > Enquiries — messages sent through the website form. */
class ContactEnquiryController extends Controller
{
    public function index()
    {
        $enquiries = ContactEnquiry::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.contact.enquiries.index', compact('enquiries'));
    }

    public function show($id)
    {
        $enquiry = ContactEnquiry::findOrFail($id);

        // Opening it counts as reading it.
        if (!$enquiry->is_read) {
            $enquiry->update([
                'is_read'     => 1,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);
        }

        return view('backend.contact.enquiries.show', compact('enquiry'));
    }

    public function destroy($id)
    {
        try {
            ContactEnquiry::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('contact-enquiry.index')->with('message', 'Enquiry deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }
}
