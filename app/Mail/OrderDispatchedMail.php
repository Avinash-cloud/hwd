<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDispatchedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        $awb = $this->order->awb_number ?: $this->order->tracking_number;
        $subject = "Sacred Shipment Dispatched #{$this->order->order_number}".($awb ? " (AWB: {$awb})" : '').' - Haridwar Bliss';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.dispatched',
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
