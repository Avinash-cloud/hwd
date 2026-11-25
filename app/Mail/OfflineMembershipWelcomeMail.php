<?php

namespace App\Mail;

use App\Models\Membership;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfflineMembershipWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Membership $membership,
        public ?string $setPasswordUrl = null,
        public ?CarbonInterface $prepaidUntil = null,
        public bool $hasRazorpayMandate = false
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Blu Bliss Sacred Membership Is Now Active on Our Portal',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.memberships.offline-welcome',
            with: [
                'membership' => $this->membership,
                'setPasswordUrl' => $this->setPasswordUrl,
                'prepaidUntil' => $this->prepaidUntil,
                'hasRazorpayMandate' => $this->hasRazorpayMandate,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
