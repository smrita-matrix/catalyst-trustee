<?php

namespace App\Mail;

use App\Models\Grievance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Acknowledgement sent back to the investor who submitted the grievance. */
class GrievanceAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Grievance $grievance)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We have received your grievance - Catalyst Trusteeship Limited');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.grievance.acknowledgement');
    }
}
