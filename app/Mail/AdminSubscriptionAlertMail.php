<?php

namespace App\Mail;

use App\Models\MembershipSubscription;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminSubscriptionAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $alertSubject,
        public ?MembershipSubscription $subscription = null,
        public ?Order $order = null,
        /** @var array<string, mixed> */
        public array $details = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Haridwar Bliss Admin] {$this->alertSubject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.subscription-alert',
            with: [
                'alertSubject' => $this->alertSubject,
                'subscription' => $this->subscription,
                'order' => $this->order,
                'details' => $this->details,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
