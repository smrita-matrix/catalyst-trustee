<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactEnquiryAcknowledgement;
use App\Mail\ContactEnquiryReceived;
use App\Models\ContactEnquiry;
use App\Models\ContactPageDetails;
use App\Models\ContactOffice;
use App\Models\FooterDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contact()
    {
        $content = ContactPageDetails::whereNull('deleted_at')->latest('id')->first();

        $offices = ContactOffice::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $mainOffices   = $offices->where('type', 'main')->values();
        $branchOffices = $offices->where('type', 'branch')->values();

        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.contact.index', compact('content', 'mainOffices', 'branchOffices', 'footer'));
    }

    /** Store an enquiry from the Contact Us form, then notify both sides. */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $enquiry = ContactEnquiry::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'mobile'     => $validated['mobile'],
            'email'      => $validated['email'],
            'service'    => $validated['service'],
            'location'   => $validated['location'] ?? null,
            'comments'   => $validated['comments'],
            'ip_address' => $request->ip(),
            'created_at' => Carbon::now(),
        ]);

        $this->sendNotifications($enquiry);

        return redirect()->route('frontend.thank_you')->with([
            'thankyou_heading' => 'Thank You',
            'thankyou_message' => 'Your enquiry has been received. A member of our team will get back to you shortly.',
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Email the team and acknowledge to the enquirer. Mail trouble must never
     * cost us the enquiry, so failures are logged and swallowed.
     */
    private function sendNotifications(ContactEnquiry $enquiry): void
    {
        $adminEmail = optional(
            ContactPageDetails::whereNull('deleted_at')->latest('id')->first()
        )->notify_email;

        try {
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ContactEnquiryReceived($enquiry));
            }

            Mail::to($enquiry->email)->send(new ContactEnquiryAcknowledgement($enquiry));
        } catch (\Throwable $e) {
            Log::error('Contact enquiry mail failed for #' . $enquiry->id . ': ' . $e->getMessage());
        }
    }

    private function rules()
    {
        return [
            // Letters, spaces, apostrophes, dots and hyphens only - no digits.
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'last_name'  => ['required', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'mobile'     => ['required', 'string', 'max:20', 'regex:/^[0-9+\s-]{7,20}$/'],
            'email'      => ['required', 'email', 'max:255'],
            'service'    => ['required', 'string', 'max:255'],
            'location'   => ['nullable', 'string', 'max:255'],
            'comments'   => ['required', 'string', 'max:2000'],
        ];
    }

    private function messages()
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.regex'    => 'The first name may contain letters only - no numbers.',
            'last_name.required'  => 'Please enter your last name.',
            'last_name.regex'     => 'The last name may contain letters only - no numbers.',
            'mobile.required'     => 'Please enter your mobile number.',
            'mobile.regex'        => 'The mobile number may contain digits only.',
            'email.required'      => 'Please enter your email address.',
            'email.email'         => 'Please enter a valid email address.',
            'service.required'    => 'Please choose a service.',
            'comments.required'   => 'Please enter your comments or questions.',
            'comments.max'        => 'Please keep your message within 2000 characters.',
        ];
    }
}
