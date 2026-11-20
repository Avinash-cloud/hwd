<?php

namespace App\Services;

use App\Mail\OrderCancelledMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderDispatchedMail;
use App\Mail\OrderOutForDeliveryMail;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderMailService
{
    /**
     * Send order confirmation/placed email to customer.
     */
    public function sendOrderPlaced(Order $order): bool
    {
        return $this->sendToDevotee($order, new OrderPlacedMail($order), 'order_placed');
    }

    /**
     * Send shipment dispatched email with AWB tracking details.
     */
    public function sendOrderDispatched(Order $order): bool
    {
        return $this->sendToDevotee($order, new OrderDispatchedMail($order), 'order_dispatched');
    }

    /**
     * Send notification when parcel is out for delivery today.
     */
    public function sendOrderOutForDelivery(Order $order): bool
    {
        return $this->sendToDevotee($order, new OrderOutForDeliveryMail($order), 'order_out_for_delivery');
    }

    /**
     * Send order delivered email with authenticity verification and puja instructions.
     */
    public function sendOrderDelivered(Order $order): bool
    {
        return $this->sendToDevotee($order, new OrderDeliveredMail($order), 'order_delivered');
    }

    /**
     * Send order cancellation notice.
     */
    public function sendOrderCancelled(Order $order, ?string $reason = null): bool
    {
        if ($reason) {
            $order->cancellation_reason = $reason;
        }

        return $this->sendToDevotee($order, new OrderCancelledMail($order), 'order_cancelled');
    }

    /**
     * Safely send mailable to resolved customer email with error handling.
     */
    protected function sendToDevotee(Order $order, mixed $mailable, string $eventType): bool
    {
        $recipientEmail = $order->customer_email;

        if (! $recipientEmail || ! filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            Log::info("Order {$order->order_number}: No valid recipient email found for {$eventType} notification.");

            return false;
        }

        try {
            Mail::to($recipientEmail)->send($mailable);
            Log::info("Order {$order->order_number}: Sent {$eventType} email to {$recipientEmail}");

            return true;
        } catch (\Throwable $e) {
            Log::error("Order {$order->order_number}: Failed to send {$eventType} email to {$recipientEmail}: ".$e->getMessage());

            return false;
        }
    }
}
