<?php

namespace App\Mail;

use App\Models\ContactEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Acknowledgement sent back to the person who enquired. */
class ContactEnquiryAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactEnquiry $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Thank you for contacting us - Catalyst Trusteeship Limited');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact.acknowledgement');
    }
}
