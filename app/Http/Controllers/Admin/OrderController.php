<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Order;
use App\Services\InventoryService;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService,
        protected InventoryService $inventoryService
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

        if ($validated['order_status'] === 'dispatched' && ! $order->dispatched_at) {
            $validated['dispatched_at'] = now();
        } elseif ($validated['order_status'] === 'delivered' && ! $order->delivered_at) {
            $validated['delivered_at'] = now();
        }

        // Automatic and reversible inventory sync
        $this->inventoryService->syncStatus($order, $validated['order_status']);

        $order->update($validated);

        return back()->with('success', "Order #{$order->order_number} status updated to {$order->order_status}.");
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
