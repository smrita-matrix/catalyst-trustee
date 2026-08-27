<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FooterDetails;
use App\Models\Grievance;
use App\Models\GrievancePageDetails;
use Carbon\Carbon;
use App\Mail\GrievanceAcknowledgement;
use App\Mail\GrievanceReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GrievanceController extends Controller
{
    public function investorGrievance()
    {
        $content = GrievancePageDetails::whereNull('deleted_at')->latest('id')->first();
        $footer  = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.grievance.investor-grievance', compact('content', 'footer'));
    }

    /**
     * Shared confirmation page. Any form can send a visitor here by flashing
     * `thankyou` details; without them it falls back to a generic message.
     */
    public function thankYou()
    {
        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        $heading = session('thankyou_heading', 'Thank You');
        $message = session('thankyou_message', 'Your submission has been received. Our team will get back to you shortly.');

        return view('frontend.thank-you', compact('footer', 'heading', 'message'));
    }

    /** Store a grievance submitted from the public form. */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $grievance = Grievance::create([
            'full_name'         => $validated['full_name'],
            'pan'               => strtoupper($validated['pan']),
            'email'             => $validated['email'],
            'mobile'            => $validated['mobile'] ?? null,
            'address'           => $validated['address'],
            'issuer_name'       => $validated['issuer_name'],
            'series_name'       => $validated['series_name'] ?? null,
            'isin'              => $validated['isin'],
            'bonds_held'        => $validated['bonds_held'],
            'complaint_types'   => $validated['complaint_types'],
            'complaint_details' => $validated['complaint_details'],
            'ip_address'        => $request->ip(),
            'created_at'        => Carbon::now(),
        ]);

        $this->sendNotifications($grievance);

        return redirect()->route('frontend.thank_you')->with([
            'thankyou_heading' => 'Thank You',
            'thankyou_message' => 'Your grievance has been submitted successfully. Our team will review it and get back to you shortly.',
        ]);
    }

    /* ------------------------------------------------------------------ */

    /**
     * Email the team and acknowledge to the investor.
     *
     * Mail problems must never cost us the grievance, so failures are logged
     * and swallowed — the submission is already saved by this point.
     */
    private function sendNotifications(Grievance $grievance): void
    {
        $adminEmail = optional(
            GrievancePageDetails::whereNull('deleted_at')->latest('id')->first()
        )->notify_email;

        try {
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new GrievanceReceived($grievance));
            }

            Mail::to($grievance->email)->send(new GrievanceAcknowledgement($grievance));
        } catch (\Throwable $e) {
            Log::error('Grievance mail failed for #' . $grievance->id . ': ' . $e->getMessage());
        }
    }

    private function rules()
    {
        return [
            // Letters, spaces, apostrophes, dots and hyphens only — no digits.
            'full_name'         => ['required', 'string', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'pan'               => ['required', 'string', 'regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/'],
            'email'             => ['required', 'email', 'max:255'],
            'mobile'            => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s-]+$/'],
            'address'           => ['required', 'string', 'max:1000'],

            'issuer_name'       => ['required', 'string', 'max:255'],
            'series_name'       => ['nullable', 'string', 'max:255'],
            'isin'              => ['required', 'string', 'max:255'],
            'bonds_held'        => ['required', 'numeric', 'min:1'],

            'complaint_types'   => ['required', 'array', 'min:1'],
            'complaint_types.*' => ['string', 'max:255'],
            'complaint_details' => ['required', 'string', 'max:1000'],
        ];
    }

    private function messages()
    {
        return [
            'full_name.required'       => 'Please enter your full name.',
            'full_name.regex'          => 'The full name may contain letters only — no numbers or symbols.',
            'pan.required'             => 'Please enter your PAN.',
            'pan.regex'                => 'Please enter a valid PAN, for example ABCDE1234F.',
            'email.required'           => 'Please enter your email address.',
            'email.email'              => 'Please enter a valid email address.',
            'mobile.regex'             => 'The mobile number may contain digits only.',
            'address.required'         => 'Please enter your full postal address.',
            'issuer_name.required'     => 'Please enter the debenture issuer name.',
            'isin.required'            => 'Please enter the ISIN.',
            'bonds_held.required'      => 'Please enter the number of bonds held.',
            'bonds_held.numeric'       => 'The number of bonds held must be a number.',
            'complaint_types.required' => 'Please tick at least one complaint particular.',
            'complaint_details.required' => 'Please describe your grievance.',
            'complaint_details.max'    => 'Please keep the description within 1000 characters.',
        ];
    }
}
