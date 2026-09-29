<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Order;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    public function index(Request $request): View
    {
        $query = Order::with(['user', 'batch', 'items'])->latest();

        if ($status = $request->get('status')) {
            $query->where('order_status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(15)->withQueryString();
        $batches = Batch::where('status', 'ready')->get();

        return view('admin.orders.index', compact('orders', 'batches'));
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
            'order_status' => 'required|in:placed,confirmed,batched,dispatched,in_transit,delivered,cancelled',
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

        $order->update($validated);

        return back()->with('success', "Order #{$order->order_number} status updated to {$order->order_status}.");
    }

    public function packingSlip(Order $order): View
    {
        $order->load(['user', 'batch', 'items.product']);
        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('admin.orders.packing-slip', compact('order', 'qrSvg', 'verificationUrl'));
    }
}
