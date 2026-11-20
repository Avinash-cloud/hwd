<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Order;
use App\Services\InventoryService;
use App\Services\NimbusPostService;
use App\Services\OrderMailService;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService,
        protected InventoryService $inventoryService,
        protected NimbusPostService $nimbusPostService,
        protected OrderMailService $orderMailService
    ) {}

    public function index(Request $request): View
    {
        $query = Order::with(['user', 'batch', 'items.product'])->latest();

        // Default to 'new' orders if no status parameter is provided
        $currentStatus = $request->has('status') ? $request->get('status') : 'new';

        if ($currentStatus && $currentStatus !== 'all') {
            if ($currentStatus === 'new') {
                $query->whereIn('order_status', ['new', 'placed']);
            } elseif ($currentStatus === 'dispatched') {
                $query->whereIn('order_status', ['dispatched', 'in_transit']);
            } else {
                $query->where('order_status', $currentStatus);
            }
        }

        if ($request->boolean('member_free_only')) {
            $query->whereHas('items', function ($q): void {
                $q->where('is_free_monthly_bottle', true);
            });
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhere('coupon_code', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $batches = Batch::where('status', 'ready')->get();

        // Status Counts for Tabs
        $counts = [
            'new' => Order::whereIn('order_status', ['new', 'placed'])->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'batched' => Order::where('order_status', 'batched')->count(),
            'dispatched' => Order::whereIn('order_status', ['dispatched', 'in_transit'])->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
            'all' => Order::count(),
            'member_free' => Order::whereHas('items', fn ($q) => $q->where('is_free_monthly_bottle', true))->count(),
        ];

        return view('admin.orders.index', compact('orders', 'batches', 'currentStatus', 'counts'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'batch', 'items.product']);
        $batches = Batch::all();
        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('admin.orders.show', compact('order', 'batches', 'qrSvg', 'verificationUrl'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => 'required|in:new,placed,confirmed,batched,dispatched,in_transit,delivered,cancelled',
            'batch_id' => 'nullable|exists:batches,id',
            'courier_name' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'admin_notes' => 'nullable|string',
        ]);

        $previousStatus = $order->order_status;
        $newStatus = $validated['order_status'];

        if ($newStatus === 'dispatched' && ! $order->dispatched_at) {
            $validated['dispatched_at'] = now();
        } elseif ($newStatus === 'delivered' && ! $order->delivered_at) {
            $validated['delivered_at'] = now();
        } elseif ($newStatus === 'cancelled') {
            $validated['cancelled_at'] = now();
            $validated['cancellation_reason'] = $validated['admin_notes'] ?? 'Cancelled by administrator.';

            // Cancel on NimbusPost if active shipment exists
            if ($order->hasNimbusPostShipment()) {
                $this->nimbusPostService->cancelShipment($order, $validated['cancellation_reason']);
                $validated['nimbuspost_status'] = 'cancelled';
            }
        }

        // Automatic and reversible inventory sync
        $this->inventoryService->syncStatus($order, $newStatus);

        $order->update($validated);

        // Send appropriate customer emails based on status change
        if ($newStatus === 'dispatched' && $previousStatus !== 'dispatched') {
            $this->orderMailService->sendOrderDispatched($order);
        } elseif ($newStatus === 'delivered' && $previousStatus !== 'delivered') {
            $this->orderMailService->sendOrderDelivered($order);
        } elseif ($newStatus === 'cancelled' && $previousStatus !== 'cancelled') {
            $this->orderMailService->sendOrderCancelled($order, $validated['cancellation_reason'] ?? null);
        }

        return back()->with('success', "Order #{$order->order_number} status updated to {$order->order_status}.");
    }

    /**
     * Book order shipment with NimbusPost.
     */
    public function createNimbusShipment(Order $order): RedirectResponse
    {
        $result = $this->nimbusPostService->createShipment($order);

        if ($result['success']) {
            if ($order->order_status === 'new') {
                $order->update(['order_status' => 'confirmed']);
            }

            return back()->with(
                'success',
                "Shipment successfully booked with NimbusPost! AWB: {$order->fresh()->awb_number} ({$order->fresh()->courier_name})."
            );
        }

        return back()->with('error', 'NimbusPost Error: '.($result['error'] ?? 'Could not create shipment.'));
    }

    /**
     * Cancel an active NimbusPost shipment from admin.
     */
    public function cancelNimbusShipment(Request $request, Order $order): RedirectResponse
    {
        $reason = $request->input('reason', 'Cancelled by administrator in store panel.');
        $result = $this->nimbusPostService->cancelShipment($order, $reason);

        if ($result['success']) {
            return back()->with('success', "NimbusPost shipment for AWB {$order->awb_number} has been cancelled.");
        }

        return back()->with('error', 'NimbusPost Error: '.($result['error'] ?? 'Failed to cancel shipment.'));
    }

    /**
     * Refresh real-time tracking checkpoints from NimbusPost.
     */
    public function refreshTracking(Order $order): RedirectResponse
    {
        $awb = $order->awb_number ?: $order->tracking_number;

        if (! $awb) {
            return back()->with('error', 'No AWB or tracking number exists for this order.');
        }

        $result = $this->nimbusPostService->trackShipment($awb);

        if ($result['success']) {
            $checkpoints = $result['checkpoints'] ?? [];
            if (! empty($checkpoints)) {
                $order->update([
                    'tracking_history' => $checkpoints,
                    'nimbuspost_status' => $result['current_status'] ?? $order->nimbuspost_status,
                ]);
            }

            return back()->with(
                'success',
                "Tracking updated for AWB {$awb}. Current Status: ".($result['current_status'] ?? 'Updated')
            );
        }

        return back()->with('error', 'Tracking Error: '.($result['error'] ?? 'Unable to retrieve tracking information.'));
    }

    /**
     * Redirect to shipping label or fallback packing slip.
     */
    public function downloadLabel(Order $order): RedirectResponse|View
    {
        if ($order->label_url && filter_var($order->label_url, FILTER_VALIDATE_URL)) {
            return redirect()->away($order->label_url);
        }

        $awb = $order->awb_number ?: $order->tracking_number;
        if ($awb) {
            $labelUrl = $this->nimbusPostService->getShippingLabel($awb);
            if ($labelUrl && filter_var($labelUrl, FILTER_VALIDATE_URL)) {
                $order->update(['label_url' => $labelUrl]);

                return redirect()->away($labelUrl);
            }
        }

        return $this->packingSlip($order);
    }

    /**
     * Bulk create NimbusPost shipments for selected orders.
     */
    public function bulkNimbusShip(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $orders = Order::whereIn('id', $validated['order_ids'])->get();
        $successCount = 0;
        $failedCount = 0;

        foreach ($orders as $order) {
            $result = $this->nimbusPostService->createShipment($order);
            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        return back()->with(
            'success',
            "Bulk NimbusPost booking completed: {$successCount} order(s) booked successfully".($failedCount > 0 ? ", {$failedCount} failed." : '.')
        );
    }

    /**
     * Bulk operations for assigning batches or mass updating order status.
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'bulk_action' => 'required|in:update_status,assign_batch',
            'target_status' => 'required_if:bulk_action,update_status|nullable|in:new,placed,confirmed,batched,dispatched,in_transit,delivered,cancelled',
            'target_batch_id' => 'required_if:bulk_action,assign_batch|nullable|exists:batches,id',
        ]);

        $orderIds = $validated['order_ids'];
        $count = count($orderIds);

        if ($validated['bulk_action'] === 'update_status') {
            $targetStatus = $validated['target_status'];
            $orders = Order::with('items.product')->whereIn('id', $orderIds)->get();

            foreach ($orders as $order) {
                $this->inventoryService->syncStatus($order, $targetStatus);

                $updates = ['order_status' => $targetStatus];
                if ($targetStatus === 'dispatched' && ! $order->dispatched_at) {
                    $updates['dispatched_at'] = now();
                } elseif ($targetStatus === 'delivered' && ! $order->delivered_at) {
                    $updates['delivered_at'] = now();
                }

                $order->update($updates);
            }

            return back()->with('success', "Bulk update completed: {$count} order(s) updated to '{$targetStatus}'.");
        }

        if ($validated['bulk_action'] === 'assign_batch') {
            $batch = Batch::findOrFail($validated['target_batch_id']);
            Order::whereIn('id', $orderIds)->update(['batch_id' => $batch->id]);

            return back()->with('success', "Bulk assignment completed: {$count} order(s) assigned to batch {$batch->batch_number}.");
        }

        return back();
    }

    public function packingSlip(Order $order): View
    {
        $order->load(['user', 'batch', 'items.product']);
        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('admin.orders.packing-slip', compact('order', 'qrSvg', 'verificationUrl'));
    }
}
