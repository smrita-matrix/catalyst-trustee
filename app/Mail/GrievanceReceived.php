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

    /**
     * @param  array<int, string>  $copyTo  addresses copied on this notification.
     *                                      Not named $cc - Mailable already has one.
     */
    public function __construct(public Grievance $grievance, public array $copyTo = [])
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // The subject says which form it came from, so the team can tell at
            // a glance without opening it.
            subject: 'New Grievance (' . $this->grievance->type_label . ') - ' . $this->grievance->full_name,
            cc: $this->copyTo,
            replyTo: [$this->grievance->email],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.grievance.admin');
    }
}
