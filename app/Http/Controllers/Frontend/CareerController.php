<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\CareerApplicationAcknowledgement;
use App\Mail\CareerApplicationReceived;
use App\Models\CareerApplication;
use App\Models\CareerOpening;
use App\Models\CareerPageDetails;
use App\Models\FooterDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function careers()
    {
        $content = CareerPageDetails::whereNull('deleted_at')->latest('id')->first();

        $openings = CareerOpening::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.careers.index', compact('content', 'openings', 'footer'));
    }

    /** Store an application, keep the CV, then notify both sides. */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $file = $request->file('resume');

        $application = CareerApplication::create([
            'first_name'           => $validated['first_name'],
            'last_name'            => $validated['last_name'],
            'email'                => $validated['email'],
            'phone'                => $validated['phone'],
            'city'                 => $validated['city'],
            'position'             => $validated['position'],
            'intro'                => $validated['intro'] ?? null,
            'resume_file'          => $this->storeResume($file),
            'resume_original_name' => $file->getClientOriginalName(),
            'ip_address'           => $request->ip(),
            'created_at'           => Carbon::now(),
        ]);

        $this->sendNotifications($application);

        return redirect()->route('frontend.thank_you')->with([
            'thankyou_heading' => 'Thank You',
            'thankyou_message' => 'Your application has been received. Our HR team will review your resume and get back to you shortly.',
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    /**
     * Email HR with the CV attached, and acknowledge to the applicant.
     * Mail trouble must never cost us the application, so failures are logged.
     */
    private function sendNotifications(CareerApplication $application): void
    {
        $adminEmail = optional(
            CareerPageDetails::whereNull('deleted_at')->latest('id')->first()
        )->notify_email;

        try {
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new CareerApplicationReceived($application));
            }

            Mail::to($application->email)->send(new CareerApplicationAcknowledgement($application));
        } catch (\Throwable $e) {
            Log::error('Career application mail failed for #' . $application->id . ': ' . $e->getMessage());
        }
    }

    private function storeResume($file)
    {
        $destination = public_path('career-uploads/resumes');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $fileName = 'resume_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $fileName);

        return $fileName;
    }

    private function rules()
    {
        return [
            // Letters, spaces, apostrophes, dots and hyphens only — no digits.
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'last_name'  => ['required', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'email'      => ['required', 'email', 'max:255'],
            'phone'      => ['required', 'string', 'max:20', 'regex:/^[0-9+\s-]{7,20}$/'],
            'city'       => ['required', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'position'   => ['required', 'string', 'max:255'],
            'intro'      => ['nullable', 'string', 'max:2000'],
            'resume'     => ['required', 'file', 'mimes:pdf,doc,docx', 'max:8192'],
        ];
    }

    private function messages()
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.regex'    => 'The first name may contain letters only — no numbers.',
            'last_name.required'  => 'Please enter your last name.',
            'last_name.regex'     => 'The last name may contain letters only — no numbers.',
            'email.required'      => 'Please enter your email address.',
            'email.email'         => 'Please enter a valid email address.',
            'phone.required'      => 'Please enter your phone number.',
            'phone.regex'         => 'The phone number may contain digits only.',
            'city.required'       => 'Please enter your city.',
            'city.regex'          => 'The city may contain letters only — no numbers.',
            'position.required'   => 'Please choose the position you are applying for.',
            'resume.required'     => 'Please attach your resume.',
            'resume.mimes'        => 'The resume must be a PDF, DOC or DOCX file.',
            'resume.max'          => 'The resume must not be larger than 8MB.',
        ];
    }
}
