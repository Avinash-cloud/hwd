<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NimbusPostService
{
    protected string $baseUrl;

    protected ?string $apiKey;

    protected ?string $apiSecret;

    protected string $warehouseId;

    protected string $warehouseName;

    protected ?string $webhookSecret;

    protected bool $mockMode;

    public function __construct()
    {
        $config = config('services.nimbuspost', []);
        $this->baseUrl = rtrim((string) ($config['base_url'] ?? 'https://api-v2.nimbuspost.com'), '/');
        $this->apiKey = $config['api_key'] ?? null;
        $this->apiSecret = $config['api_secret'] ?? null;
        $this->warehouseId = (string) ($config['warehouse_id'] ?? '7f8a72aa-e52b-4916-b67b-23e78cba50bc');
        $this->warehouseName = $config['warehouse_name'] ?? 'Haridwar Bliss Warehouse';
        $this->webhookSecret = $config['webhook_secret'] ?? null;
        $this->mockMode = (bool) ($config['mock_mode'] ?? false);
    }

    /**
     * Determine if mock mode is active (explicitly set or no API key in non-production).
     */
    public function isMockMode(): bool
    {
        if ($this->mockMode) {
            return true;
        }

        if (empty($this->apiKey) || empty($this->apiSecret)) {
            return app()->environment('local', 'testing');
        }

        return false;
    }

    /**
     * Build an authenticated HTTP client including NimbusPost Partner API v2 headers.
     */
    protected function client(int $timeout = 30): PendingRequest
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'HaridwarBliss/1.0 (Laravel)',
        ];

        if ($this->apiKey) {
            $headers['x-api-key'] = $this->apiKey;
            $headers['api-key'] = $this->apiKey;
        }

        if ($this->apiSecret) {
            $headers['x-api-secret'] = $this->apiSecret;
            $headers['api-secret'] = $this->apiSecret;
        }

        return Http::timeout($timeout)->withHeaders($headers);
    }

    /**
     * List registered pickup warehouses from NimbusPost.
     */
    public function getWarehouses(): array
    {
        if ($this->isMockMode()) {
            return [
                [
                    'warehouse_id' => $this->warehouseId,
                    'warehouse_code' => 'WH-001',
                    'name' => $this->warehouseName,
                    'status' => 'active',
                ],
            ];
        }

        try {
            $response = $this->client(15)->get("{$this->baseUrl}/v2/warehouses");
            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
        } catch (\Throwable $e) {
            Log::warning('NimbusPost getWarehouses error: '.$e->getMessage());
        }

        return [];
    }

    /**
     * Create an order on NimbusPost (without booking courier yet).
     * The order immediately appears in the NimbusPost dashboard under the "New" tab.
     */
    public function createOrder(Order $order, array $customParams = []): array
    {
        if ($order->nimbuspost_order_id) {
            return [
                'success' => true,
                'already_created' => true,
                'order_id' => $order->nimbuspost_order_id,
            ];
        }

        if ($this->isMockMode()) {
            $mockOrderId = 'NPO-MOCK-'.rand(100000, 999999);
            $order->update([
                'nimbuspost_order_id' => $mockOrderId,
                'nimbuspost_status' => 'created',
            ]);

            return [
                'success' => true,
                'order_id' => $mockOrderId,
                'is_mock' => true,
            ];
        }

        $order->loadMissing(['items.product', 'batch']);

        $shippingAddress = $order->shipping_address ?? [];
        $recipientName = $order->customer_name ?: 'Sacred Devotee';
        $recipientEmail = $order->customer_email ?: 'devotee@haridwarbliss.com';

        // Clean Indian 10-digit mobile number
        $phoneDigits = preg_replace('/\D/', '', (string) ($order->customer_phone ?? '9876543210'));
        if (str_starts_with($phoneDigits, '91') && strlen($phoneDigits) === 12) {
            $phoneDigits = substr($phoneDigits, 2);
        }
        $phoneInt = (int) $phoneDigits;
        if ($phoneInt < 1000000000 || $phoneInt > 9999999999) {
            $phoneInt = 9876543210;
        }

        $pincode = (int) ($shippingAddress['postal_code'] ?? 249401);

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->product_name,
                'qty' => (int) $item->quantity,
                'price' => (float) $item->unit_price,
                'sku' => $item->product?->sku ?? ('HB-SKU-'.$item->product_id),
                'hsn' => $item->hsn_code ?? '22019010',
                'tax_rate' => (float) ($item->gst_percentage ?? 18.00),
                'discount_amount' => 0,
            ];
        }

        if (empty($items)) {
            $items[] = [
                'name' => 'Sacred Gangajal Order',
                'qty' => 1,
                'price' => (float) $order->total_amount,
                'sku' => 'HB-SKU-GENERAL',
                'hsn' => '22019010',
                'tax_rate' => 18.00,
                'discount_amount' => 0,
            ];
        }

        $isCod = strtolower((string) $order->payment_method) === 'cod';
        $collectable = $isCod ? (float) $order->total_amount : 0;

        $weightKg = (float) ($customParams['weight'] ?? 0.5);
        $lengthCm = (float) ($customParams['length'] ?? 15);
        $widthCm = (float) ($customParams['width'] ?? ($customParams['breadth'] ?? 10));
        $heightCm = (float) ($customParams['height'] ?? 10);

        $payload = [
            'order_number' => $order->order_number,
            'order_type' => 'b2c',
            'payment_mode' => $isCod ? 'cod' : 'prepaid',
            'order_collectable_amount' => $collectable,
            'warehouse_id' => $this->warehouseId ?: 'WH-001',
            'shipping_address' => [
                'name' => $recipientName,
                'email' => $recipientEmail,
                'address' => $shippingAddress['address_line1'] ?? 'Har Ki Pauri Ghat Road',
                'address_opt' => $shippingAddress['address_line2'] ?? ($shippingAddress['landmark'] ?? ''),
                'pincode' => $pincode,
                'city' => $shippingAddress['city'] ?? 'Haridwar',
                'state' => $shippingAddress['state'] ?? 'Uttarakhand',
                'country' => 'India',
                'phone' => $phoneInt,
            ],
            'items' => $items,
            'package' => [
                'weight' => $weightKg,
                'length' => $lengthCm,
                'width' => $widthCm,
                'height' => $heightCm,
            ],
        ];

        try {
            $response = $this->client(30)->post("{$this->baseUrl}/v2/orders", $payload);
            $result = $response->json();

            if ($response->successful() && ! empty($result['success'])) {
                $orderId = $result['data']['order_id'] ?? null;

                $order->update([
                    'nimbuspost_order_id' => (string) $orderId,
                    'nimbuspost_status' => 'created',
                ]);

                return [
                    'success' => true,
                    'order_id' => $orderId,
                    'order_status' => $result['data']['order_status'] ?? 'created',
                    'data' => $result['data'] ?? [],
                ];
            }

            $errorMessage = $result['error']['detail'] ?? ($result['message'] ?? 'Failed to create order on NimbusPost.');
            Log::error('NimbusPost createOrder error: '.$errorMessage, ['response' => $result]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'response' => $result,
            ];
        } catch (\Throwable $e) {
            Log::error('NimbusPost createOrder exception: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Book courier for an order previously created on NimbusPost.
     * Assigns courier partner, generates AWB, and deducts shipping cost from wallet.
     */
    public function bookShipment(Order $order, ?string $courierId = null): array
    {
        if ($this->isMockMode()) {
            $mockAwb = 'NP'.date('ymd').rand(100000, 999999);
            $mockShipmentId = 'NPS-'.rand(100000, 999999);
            $mockCourier = 'Delhivery Surface';
            $mockLabelUrl = url('/admin/orders/'.$order->id.'/packing-slip');

            $order->update([
                'awb_number' => $mockAwb,
                'tracking_number' => $order->tracking_number ?: $mockAwb,
                'courier_name' => $mockCourier,
                'courier_id' => '1',
                'nimbuspost_shipment_id' => $mockShipmentId,
                'label_url' => $mockLabelUrl,
                'nimbuspost_status' => 'booked',
            ]);

            return [
                'success' => true,
                'awb_number' => $mockAwb,
                'courier_name' => $mockCourier,
                'shipment_id' => $mockShipmentId,
                'label_url' => $mockLabelUrl,
                'is_mock' => true,
            ];
        }

        if (empty($order->nimbuspost_order_id)) {
            $createResult = $this->createOrder($order);
            if (! $createResult['success']) {
                return $createResult;
            }
        }

        $payload = [
            'order_id' => (string) $order->nimbuspost_order_id,
        ];

        if ($courierId) {
            $payload['courier_id'] = $courierId;
        }

        try {
            $response = $this->client(30)->post("{$this->baseUrl}/v2/shipments/book", $payload);
            $result = $response->json();

            if ($response->successful() && ! empty($result['success'])) {
                $booking = $result['data'] ?? [];
                $awb = $booking['awb'] ?? null;
                $courierName = $booking['courier_name'] ?? 'NimbusPost Express';
                $courierId = $booking['courier_id'] ?? null;
                $pickupId = $booking['pickup_id'] ?? null;
                $labelUrl = $booking['label_url'] ?? null;

                // If label_url not populated in booking response, fetch via labels API
                if (empty($labelUrl) && $awb) {
                    $labelUrl = $this->getShippingLabel([$order->nimbuspost_order_id]);
                }

                $history = $order->tracking_history ?? [];
                $history[] = [
                    'status' => 'Shipment Booked',
                    'status_code' => 'BOOKED',
                    'location' => 'Haridwar Facility',
                    'remarks' => "Courier booked: {$courierName} (AWB: {$awb})",
                    'courier_name' => $courierName,
                    'timestamp' => now()->toIso8601String(),
                ];

                $order->update([
                    'awb_number' => (string) $awb,
                    'tracking_number' => $order->tracking_number ?: (string) $awb,
                    'courier_name' => $courierName,
                    'courier_id' => (string) $courierId,
                    'nimbuspost_shipment_id' => (string) ($pickupId ?: $order->nimbuspost_order_id),
                    'label_url' => $labelUrl,
                    'nimbuspost_status' => 'booked',
                    'tracking_history' => $history,
                ]);

                return [
                    'success' => true,
                    'awb_number' => $awb,
                    'courier_name' => $courierName,
                    'courier_id' => $courierId,
                    'label_url' => $labelUrl,
                    'booking' => $booking,
                ];
            }

            $detail = $result['error']['detail'] ?? ($result['message'] ?? 'Failed to book shipment on NimbusPost.');
            $code = $result['error']['details']['code'] ?? ($result['error']['code'] ?? '');

            // Specific handling for insufficient wallet balance
            if ($code === 'INSUFFICIENT_BALANCE' || str_contains(strtolower($detail), 'wallet')) {
                return [
                    'success' => false,
                    'insufficient_balance' => true,
                    'error' => 'Insufficient wallet balance on NimbusPost. Please recharge your wallet to book the courier.',
                    'order_id' => $order->nimbuspost_order_id,
                ];
            }

            Log::error('NimbusPost bookShipment error: '.$detail, ['response' => $result]);

            return [
                'success' => false,
                'error' => $detail,
                'response' => $result,
            ];
        } catch (\Throwable $e) {
            Log::error('NimbusPost bookShipment exception: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create/book shipment end-to-end on NimbusPost.
     * Guarantees order exists in NimbusPost dashboard, then books courier.
     */
    public function createShipment(Order $order, array $customParams = []): array
    {
        // If order already has an active AWB booked on NimbusPost, return it
        if ($order->nimbuspost_shipment_id && $order->awb_number) {
            return [
                'success' => true,
                'already_created' => true,
                'order_id' => $order->nimbuspost_order_id,
                'shipment_id' => $order->nimbuspost_shipment_id,
                'awb_number' => $order->awb_number,
                'courier_name' => $order->courier_name,
                'label_url' => $order->label_url,
            ];
        }

        if ($this->isMockMode()) {
            $mockAwb = 'NP'.date('ymd').rand(100000, 999999);
            $mockShipmentId = 'NPS-'.rand(100000, 999999);
            $mockOrderId = 'NPO-'.rand(100000, 999999);
            $mockCourier = 'Delhivery Surface';
            $mockLabelUrl = url('/admin/orders/'.$order->id.'/packing-slip');

            $order->update([
                'nimbuspost_order_id' => $mockOrderId,
                'nimbuspost_shipment_id' => $mockShipmentId,
                'awb_number' => $mockAwb,
                'tracking_number' => $order->tracking_number ?: $mockAwb,
                'courier_name' => $mockCourier,
                'courier_id' => '1',
                'label_url' => $mockLabelUrl,
                'nimbuspost_status' => 'booked',
                'tracking_history' => [
                    [
                        'status' => 'Manifest Generated',
                        'status_code' => 'MANIFESTED',
                        'location' => 'Haridwar Bliss Fulfillment Facility',
                        'remarks' => 'Shipment created and AWB generated on NimbusPost (Mock Mode).',
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
            ]);

            return [
                'success' => true,
                'order_id' => $mockOrderId,
                'shipment_id' => $mockShipmentId,
                'awb_number' => $mockAwb,
                'courier_name' => $mockCourier,
                'label_url' => $mockLabelUrl,
                'is_mock' => true,
            ];
        }

        // Step 1: Ensure order exists on NimbusPost
        if (empty($order->nimbuspost_order_id)) {
            $orderRes = $this->createOrder($order, $customParams);
            if (! $orderRes['success']) {
                return $orderRes;
            }
        }

        // Step 2: Book courier for the order
        $bookRes = $this->bookShipment($order, $customParams['courier_id'] ?? null);

        if ($bookRes['success']) {
            return $bookRes;
        }

        // If courier booking couldn't complete due to insufficient wallet balance,
        // the order has been created in NimbusPost dashboard under "New" tab.
        if (! empty($bookRes['insufficient_balance'])) {
            return [
                'success' => true,
                'pending_booking' => true,
                'order_id' => $order->nimbuspost_order_id,
                'message' => 'Order created in NimbusPost dashboard. Courier booking pending wallet recharge.',
            ];
        }

        return $bookRes;
    }

    /**
     * Cancel an active shipment or order on NimbusPost.
     */
    public function cancelShipment(Order|string $orderOrAwb, ?string $reason = null): array
    {
        $awb = null;
        $order = null;

        if ($orderOrAwb instanceof Order) {
            $order = $orderOrAwb;
            $awb = $order->awb_number ?: $order->tracking_number;
        } else {
            $awb = $orderOrAwb;
            $order = Order::where('awb_number', $awb)->orWhere('tracking_number', $awb)->first();
        }

        if ($this->isMockMode()) {
            if ($order) {
                $history = $order->tracking_history ?? [];
                $history[] = [
                    'status' => 'Cancelled on NimbusPost',
                    'status_code' => 'CANCELLED',
                    'location' => 'Haridwar Fulfillment Center',
                    'remarks' => $reason ?? 'Shipment cancelled by admin/system.',
                    'timestamp' => now()->toIso8601String(),
                ];

                $order->update([
                    'nimbuspost_status' => 'cancelled',
                    'tracking_history' => $history,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Shipment cancelled on NimbusPost (Mock Mode)',
                'awb' => $awb,
            ];
        }

        try {
            // If order has an AWB booked, cancel via /v2/shipments/cancel
            if ($awb) {
                $response = $this->client(20)->post("{$this->baseUrl}/v2/shipments/cancel", [
                    'awb' => $awb,
                    'reason' => $reason ?? 'Customer or Administrator cancellation request',
                ]);
            } elseif ($order?->nimbuspost_order_id) {
                // If only created in NimbusPost without AWB, cancel via /v2/orders/cancel
                $response = $this->client(20)->post("{$this->baseUrl}/v2/orders/cancel", [
                    'order_ids' => [$order->nimbuspost_order_id],
                    'reason' => $reason ?? 'Cancelled before booking',
                ]);
            } else {
                return [
                    'success' => false,
                    'error' => 'No NimbusPost AWB or Order ID found to cancel.',
                ];
            }

            $result = $response->json();

            if ($response->successful() && ! empty($result['success'])) {
                if ($order) {
                    $history = $order->tracking_history ?? [];
                    $history[] = [
                        'status' => 'Cancelled on NimbusPost',
                        'status_code' => 'CANCELLED',
                        'location' => 'NimbusPost Portal',
                        'remarks' => $reason ?? 'Shipment successfully cancelled.',
                        'timestamp' => now()->toIso8601String(),
                    ];

                    $order->update([
                        'nimbuspost_status' => 'cancelled',
                        'tracking_history' => $history,
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Shipment cancelled successfully on NimbusPost.',
                    'data' => $result['data'] ?? [],
                ];
            }

            $errorMessage = $result['error']['detail'] ?? ($result['message'] ?? 'Unable to cancel shipment on NimbusPost.');
            Log::warning('NimbusPost cancelShipment error: '.$errorMessage, ['response' => $result]);

            return [
                'success' => false,
                'error' => $errorMessage,
            ];
        } catch (\Throwable $e) {
            Log::error('NimbusPost cancelShipment exception: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch real-time tracking checkpoints for a shipment.
     */
    public function trackShipment(string $awb): array
    {
        if ($this->isMockMode()) {
            return [
                'success' => true,
                'awb' => $awb,
                'current_status' => 'In Transit',
                'status_code' => 'IN_TRANSIT',
                'courier_name' => 'Delhivery Surface',
                'checkpoints' => [
                    [
                        'status' => 'Delivered to Mother Ganga Devotee',
                        'location' => 'Destination Hub',
                        'timestamp' => now()->subHours(2)->toIso8601String(),
                        'remarks' => 'Package out for delivery with executive',
                    ],
                    [
                        'status' => 'In Transit',
                        'location' => 'Delhi Sorting Facility',
                        'timestamp' => now()->subDay()->toIso8601String(),
                        'remarks' => 'Sacred parcel dispatched towards destination',
                    ],
                    [
                        'status' => 'Picked Up',
                        'location' => 'Haridwar Bliss Hub',
                        'timestamp' => now()->subDays(2)->toIso8601String(),
                        'remarks' => 'Handed over consecrated batch to courier',
                    ],
                ],
            ];
        }

        try {
            $response = $this->client(20)->get("{$this->baseUrl}/v2/tracking/{$awb}");
            $result = $response->json();

            if ($response->successful() && ! empty($result['success'])) {
                $data = $result['data'] ?? [];

                return [
                    'success' => true,
                    'awb' => $awb,
                    'current_status' => $data['latest']['shipStatus'] ?? ($data['status'] ?? 'Unknown'),
                    'status_code' => $data['latest']['statusCode'] ?? ($data['status_code'] ?? ''),
                    'courier_name' => $data['courierName'] ?? null,
                    'checkpoints' => $data['scans'] ?? [],
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $result['error']['detail'] ?? ($result['message'] ?? 'Unable to retrieve tracking info.'),
            ];
        } catch (\Throwable $e) {
            Log::error('NimbusPost trackShipment exception: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Download shipping label PDF URL for orders.
     */
    public function getShippingLabel(array|string $orderIds, string $labelFormat = 'thermal-4x6'): ?string
    {
        $ids = is_array($orderIds) ? $orderIds : [$orderIds];

        if ($this->isMockMode()) {
            return url('/admin/orders/label/'.($ids[0] ?? 'mock'));
        }

        try {
            $response = $this->client(20)->post("{$this->baseUrl}/v2/shipments/labels", [
                'ids' => $ids,
            ]);
            $result = $response->json();

            if ($response->successful() && ! empty($result['success'])) {
                return $result['data']['url'] ?? null;
            }
        } catch (\Throwable $e) {
            Log::error('NimbusPost getShippingLabel exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Generate manifest for courier pickup handover.
     */
    public function generateManifest(array $awbs): array
    {
        if ($this->isMockMode()) {
            return [
                'success' => true,
                'manifest_url' => url('/admin/orders/manifest/mock_'.time()),
                'awb_count' => count($awbs),
            ];
        }

        try {
            $response = $this->client(25)->post("{$this->baseUrl}/v2/manifests", [
                'awbs' => $awbs,
            ]);

            $result = $response->json();
            if ($response->successful() && ! empty($result['success'])) {
                return [
                    'success' => true,
                    'manifest_url' => $result['data']['url'] ?? ($result['data']['manifest_url'] ?? null),
                    'data' => $result['data'] ?? [],
                ];
            }

            return ['success' => false, 'error' => $result['error']['detail'] ?? ($result['message'] ?? 'Manifest generation failed')];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check pincode serviceability and rates.
     */
    public function checkServiceability(string $originPincode, string $destPincode, float $weightKg = 0.5, string $paymentMode = 'prepaid', float $orderValue = 200.00): array
    {
        if ($this->isMockMode()) {
            return [
                'success' => true,
                'serviceable' => true,
                'couriers' => [
                    ['courierName' => 'Delhivery Surface', 'forward' => 90.00, 'tatDays' => 3],
                    ['courierName' => 'Amazon Surface', 'forward' => 75.00, 'tatDays' => 2],
                ],
            ];
        }

        try {
            $response = $this->client(15)->post("{$this->baseUrl}/v2/serviceability", [
                'pickupPincode' => $originPincode,
                'deliveryPincode' => $destPincode,
                'paymentMode' => strtolower($paymentMode),
                'packages' => [
                    [
                        'weight' => (int) round($weightKg * 1000),
                        'length' => 15,
                        'width' => 10,
                        'height' => 10,
                    ],
                ],
                'orderValuePaise' => (int) round($orderValue * 100),
            ]);

            $result = $response->json();
            if ($response->successful() && ! empty($result['success'])) {
                return [
                    'success' => true,
                    'couriers' => $result['data']['available'] ?? [],
                ];
            }

            return ['success' => false, 'error' => $result['error']['detail'] ?? ($result['message'] ?? 'Serviceability check failed')];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verify NimbusPost webhook secret/signature.
     * Supports both v2 (x-nimbus-signature: sha256=<hex>) and legacy signatures.
     */
    public function verifyWebhookSignature(?string $signature, ?string $rawBody = null): bool
    {
        if (empty($this->webhookSecret)) {
            return true;
        }

        if (empty($signature)) {
            return false;
        }

        // Direct token match (X-Webhook-Token or query token)
        if (hash_equals((string) $this->webhookSecret, (string) $signature)) {
            return true;
        }

        // v2 HMAC SHA256: x-nimbus-signature: sha256=<hex>
        if ($rawBody) {
            $expectedHex = hash_hmac('sha256', $rawBody, $this->webhookSecret);
            $cleanSignature = str_starts_with($signature, 'sha256=')
                ? substr($signature, 7)
                : $signature;

            if (hash_equals($expectedHex, $cleanSignature)) {
                return true;
            }

            // Legacy base64 HMAC
            $expectedBase64 = base64_encode(hash_hmac('sha256', $rawBody, $this->webhookSecret, true));
            if (hash_equals($expectedBase64, $signature)) {
                return true;
            }
        }

        return false;
    }
}
