<?php

namespace App\Mail;

use App\Models\CareerApplication;
use App\Models\CareerPageDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent to HR when someone applies, with the CV attached. */
class CareerApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CareerApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application - ' . $this->application->position,
            cc: $this->ccRecipients(),
            replyTo: [$this->application->email],
        );
    }

    /** Extra addresses copied on the notification, set in Careers > Page Content. */
    private function ccRecipients(): array
    {
        $raw = optional(
            CareerPageDetails::whereNull('deleted_at')->latest('id')->first()
        )->notify_cc;

        if (!$raw) {
            return [];
        }

        // Accept a comma or semicolon separated list.
        $addresses = preg_split('/[,;]+/', $raw);

        return array_values(array_filter(
            array_map('trim', $addresses),
            fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
        ));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.career.admin');
    }

    /** The applicant's CV travels with the email. */
    public function attachments(): array
    {
        $path = $this->application->resume_path;

        if (!$path || !is_file($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as($this->application->resume_original_name ?: basename($path)),
        ];
    }
}
