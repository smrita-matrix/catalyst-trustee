<?php

namespace App\Mail;

use App\Models\ContactEnquiry;
use App\Models\ContactPageDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent to the team when someone submits the Contact Us enquiry form. */
class ContactEnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactEnquiry $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Website Enquiry - ' . $this->enquiry->service,
            cc: $this->ccRecipients(),
            replyTo: [$this->enquiry->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact.admin');
    }

    /** Extra addresses copied in, set in Contact Us > Contact Page. */
    private function ccRecipients(): array
    {
        $raw = optional(
            ContactPageDetails::whereNull('deleted_at')->latest('id')->first()
        )->notify_cc;

        if (!$raw) {
            return [];
        }

        $addresses = preg_split('/[,;]+/', $raw);

        return array_values(array_filter(
            array_map('trim', $addresses),
            fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
        ));
    }
}
