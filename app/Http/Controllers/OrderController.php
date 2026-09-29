<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Show customer order confirmation and details.
     */
    public function show(string $orderNumber, Request $request): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'batch', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Customer can only view their own order unless admin
        if ($user && $user->id !== $order->user_id && ! $user->is_admin) {
            abort(403, 'Unauthorized to view this order.');
        }

        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('orders.show', compact('order', 'qrSvg', 'verificationUrl'));
    }

    /**
     * Display printable official invoice.
     */
    public function invoice(string $orderNumber, Request $request): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'batch', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($user && $user->id !== $order->user_id && ! $user->is_admin) {
            abort(403, 'Unauthorized to view this invoice.');
        }

        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('orders.invoice', compact('order', 'qrSvg', 'verificationUrl'));
    }
}
