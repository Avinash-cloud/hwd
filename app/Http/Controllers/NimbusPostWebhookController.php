<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryService;
use App\Services\NimbusPostService;
use App\Services\OrderMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NimbusPostWebhookController extends Controller
{
    public function __construct(
        protected NimbusPostService $nimbusPostService,
        protected OrderMailService $orderMailService,
        protected InventoryService $inventoryService
    ) {}

    /**
     * Handle incoming webhooks from NimbusPost.
     */
    public function handle(Request $request): JsonResponse
    {
        Log::info('NimbusPost Webhook received:', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // Verify webhook secret if configured
        $signature = $request->header('x-nimbus-signature')
            ?? $request->header('X-NimbusPost-Signature')
            ?? $request->header('X-Hmac-SHA256')
            ?? $request->header('X-Webhook-Token')
            ?? $request->query('token')
            ?? $request->input('webhook_secret');

        if (! $this->nimbusPostService->verifyWebhookSignature($signature, $request->getContent())) {
            Log::warning('NimbusPost webhook signature mismatch.');

            return response()->json(['error' => 'Unauthorized signature'], 401);
        }

        $payload = $request->all();

        // Extract parameters from wrapped or flat payload
        $data = $payload['data'] ?? ($payload['shipment'] ?? $payload);

        $awb = $data['awb'] ?? ($data['awb_number'] ?? ($payload['awb'] ?? ($payload['awb_number'] ?? null)));
        $orderId = $data['order_id'] ?? ($data['order_number'] ?? ($payload['order_id'] ?? ($payload['order_number'] ?? null)));
        $shipmentId = $data['shipment_id'] ?? ($payload['shipment_id'] ?? null);

        $currentStatus = $data['current_status'] ?? ($data['status'] ?? ($payload['current_status'] ?? ($payload['status'] ?? '')));
        $statusCode = strtoupper((string) ($data['status_code'] ?? ($payload['status_code'] ?? '')));
        $courierName = $data['courier_name'] ?? ($payload['courier_name'] ?? null);
        $location = $data['location'] ?? ($data['hub'] ?? ($payload['location'] ?? null));
        $remarks = $data['remarks'] ?? ($data['message'] ?? ($payload['remarks'] ?? ''));
        $timestamp = $data['timestamp'] ?? ($payload['timestamp'] ?? now()->toIso8601String());

        // Find the corresponding Order
        $order = null;

        if ($awb) {
            $order = Order::where('awb_number', $awb)
                ->orWhere('tracking_number', $awb)
                ->first();
        }

        if (! $order && $orderId) {
            $order = Order::where('order_number', $orderId)
                ->orWhere('nimbuspost_order_id', (string) $orderId)
                ->first();
        }

        if (! $order && $shipmentId) {
            $order = Order::where('nimbuspost_shipment_id', (string) $shipmentId)->first();
        }

        if (! $order) {
            Log::warning("NimbusPost webhook: No order found for AWB: {$awb}, Order ID: {$orderId}, Shipment ID: {$shipmentId}");

            return response()->json([
                'status' => false,
                'message' => 'No order found matching the provided identifiers.',
            ], 200);
        }

        // Ensure relationship items are available
        $order->loadMissing(['items.product', 'batch', 'user']);

        // Append tracking checkpoint
        $history = $order->tracking_history ?? [];
        $history[] = [
            'status' => $currentStatus,
            'status_code' => $statusCode,
            'location' => $location,
            'remarks' => $remarks,
            'courier_name' => $courierName,
            'timestamp' => $timestamp,
        ];
        $order->tracking_history = $history;
        $order->nimbuspost_status = $currentStatus;

        if ($courierName && empty($order->courier_name)) {
            $order->courier_name = $courierName;
        }

        if ($awb && empty($order->awb_number)) {
            $order->awb_number = $awb;
            if (empty($order->tracking_number)) {
                $order->tracking_number = $awb;
            }
        }

        $normalizedStatus = strtolower(trim((string) $currentStatus));

        // Event: Dispatched / In Transit
        if (
            str_contains($normalizedStatus, 'transit') ||
            str_contains($normalizedStatus, 'picked') ||
            str_contains($normalizedStatus, 'dispatched') ||
            str_contains($normalizedStatus, 'shipped') ||
            in_array($statusCode, ['IT', 'PICKED_UP', 'IN_TRANSIT', 'DISPATCHED'])
        ) {
            $wasAlreadyDispatched = in_array($order->order_status, ['dispatched', 'in_transit', 'delivered']);

            if (! $wasAlreadyDispatched) {
                $order->order_status = 'dispatched';
                if (! $order->dispatched_at) {
                    $order->dispatched_at = now();
                }

                $this->orderMailService->sendOrderDispatched($order);
            }
        }

        // Event: Out for Delivery
        if (
            str_contains($normalizedStatus, 'out for delivery') ||
            str_contains($normalizedStatus, 'out_for_delivery') ||
            in_array($statusCode, ['OFD', 'OUT_FOR_DELIVERY'])
        ) {
            $ofdCacheKey = 'order_ofd_mail_sent_'.$order->id;
            if (! Cache::has($ofdCacheKey)) {
                Cache::put($ofdCacheKey, true, now()->addDays(7));
                $this->orderMailService->sendOrderOutForDelivery($order);
            }
        }

        // Event: Delivered
        if (
            str_contains($normalizedStatus, 'delivered') && ! str_contains($normalizedStatus, 'rto') ||
            in_array($statusCode, ['DEL', 'DELIVERED'])
        ) {
            $wasAlreadyDelivered = $order->order_status === 'delivered';

            $order->order_status = 'delivered';
            if (! $order->delivered_at) {
                $order->delivered_at = now();
            }

            if (! $wasAlreadyDelivered) {
                $this->orderMailService->sendOrderDelivered($order);
            }
        }

        // Event: Cancelled / RTO
        if (
            str_contains($normalizedStatus, 'cancel') ||
            str_contains($normalizedStatus, 'rto') ||
            in_array($statusCode, ['CAN', 'CANCELLED', 'RTO'])
        ) {
            if ($order->order_status !== 'cancelled') {
                $this->inventoryService->syncStatus($order, 'cancelled');
                $order->order_status = 'cancelled';
                $order->cancelled_at = now();
                $order->cancellation_reason = 'Cancelled via courier status update: '.($remarks ?: $currentStatus);

                $this->orderMailService->sendOrderCancelled($order, $order->cancellation_reason);
            }
        }

        $order->save();

        Log::info("NimbusPost Webhook processed successfully for Order #{$order->order_number} (Status: {$order->order_status})");

        return response()->json([
            'status' => true,
            'message' => 'NimbusPost webhook processed successfully.',
            'order_number' => $order->order_number,
            'order_status' => $order->order_status,
        ], 200);
    }
}
