<?php

namespace App\Mail;

use App\Models\Grievance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent to the Catalyst team when an investor submits a grievance. */
class GrievanceReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Grievance $grievance)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Investor Grievance - ' . $this->grievance->full_name,
            replyTo: [$this->grievance->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.grievance.admin');
    }
}
