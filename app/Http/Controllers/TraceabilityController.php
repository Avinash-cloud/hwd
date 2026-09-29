<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Order;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TraceabilityController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Public verification endpoint when scanning order bottle QR code.
     */
    public function verify(string $token, Request $request): View
    {
        // Try finding by QR token first, or by batch number
        $order = Order::with(['batch', 'items.product', 'user'])
            ->where('qr_token', $token)
            ->first();

        $batch = null;

        if ($order && $order->batch) {
            $batch = $order->batch;
        } else {
            // Check if token is a batch number directly
            $batch = Batch::where('batch_number', $token)->first();
        }

        if (! $batch && ! $order) {
            abort(404, 'Sacred batch or order verification record not found.');
        }

        $verificationUrl = url('/verify/'.$token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('traceability.verify', compact('order', 'batch', 'token', 'qrSvg', 'verificationUrl'));
    }
}
