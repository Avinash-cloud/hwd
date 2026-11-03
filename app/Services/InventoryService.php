<?php

namespace App\Services;

use App\Models\Order;

class InventoryService
{
    /**
     * Deduct inventory for an order if not already deducted.
     */
    public function deductForOrder(Order $order): void
    {
        if ($order->inventory_deducted) {
            return;
        }

        $order->loadMissing('items.product');

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        $order->update(['inventory_deducted' => true]);
    }

    /**
     * Restore inventory for an order if previously deducted.
     */
    public function restoreForOrder(Order $order): void
    {
        if (! $order->inventory_deducted) {
            return;
        }

        $order->loadMissing('items.product');

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['inventory_deducted' => false]);
    }

    /**
     * Sync inventory according to the target status.
     *
     * Active fulfillment statuses: confirmed, batched, dispatched, in_transit, delivered -> inventory deducted.
     * Cancelled status -> inventory restored.
     */
    public function syncStatus(Order $order, string $newStatus): void
    {
        if ($newStatus === 'cancelled') {
            $this->restoreForOrder($order);
        } elseif (in_array($newStatus, ['confirmed', 'batched', 'dispatched', 'in_transit', 'delivered'])) {
            $this->deductForOrder($order);
        }
    }
}
