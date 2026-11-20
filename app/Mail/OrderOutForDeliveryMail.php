<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderOutForDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Out for Delivery Today! Sacred Gangajal #{$this->order->order_number} - Haridwar Bliss",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.out-for-delivery',
            with: [
                'order' => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
