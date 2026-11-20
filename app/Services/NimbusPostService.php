<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NimbusPostService
{
    protected string $baseUrl;

    protected ?string $apiKey;

    protected ?string $apiSecret;

    protected string $warehouseName;

    protected ?string $webhookSecret;

    protected bool $mockMode;

    public function __construct()
    {
        $config = config('services.nimbuspost', []);
        $this->baseUrl = rtrim((string) ($config['base_url'] ?? 'https://api.nimbuspost.com/v1'), '/');
        $this->apiKey = $config['api_key'] ?? ($config['api_token'] ?? null);
        $this->apiSecret = $config['api_secret'] ?? null;
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

        // Auto fallback to mock if no API key provided in local/testing
        if (empty($this->apiKey)) {
            return app()->environment('local', 'testing');
        }

        return false;
    }

    /**
     * Retrieve access token from config, cache, or via login/key exchange.
     */
    public function getToken(): ?string
    {
        if ($this->isMockMode()) {
            return 'mock_nimbuspost_token_'.md5($this->warehouseName);
        }

        if (empty($this->apiKey)) {
            Log::warning('NimbusPost credentials missing: please configure NIMBUSPOST_API_KEY in .env');

            return null;
        }

        // If no secret provided, API key is used directly as Bearer token
        if (empty($this->apiSecret)) {
            return $this->apiKey;
        }

        $cachedToken = Cache::get('nimbuspost_bearer_token');
        if ($cachedToken) {
            return $cachedToken;
        }

        // When both API Key and API Secret are provided, exchange credentials for session token
        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/users/login", [
                'email' => $this->apiKey,
                'password' => $this->apiSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['data'] ?? ($data['token'] ?? null);

                if ($token) {
                    Cache::put('nimbuspost_bearer_token', $token, now()->addHours(23));

                    return $token;
                }
            }
        } catch (\Throwable $e) {
            Log::info('NimbusPost login exchange note: '.$e->getMessage().'. Using API key directly.');
        }

        // Fallback: Use the API key directly as the authorization token
        return $this->apiKey;
    }

    /**
     * Build an authenticated HTTP client including Bearer token, api-key, and api-secret headers.
     */
    protected function client(int $timeout = 30): PendingRequest
    {
        $token = $this->getToken();
        $headers = [
            'Accept' => 'application/json',
        ];

        if ($this->apiKey) {
            $headers['api-key'] = $this->apiKey;
            $headers['key'] = $this->apiKey;
        }

        if ($this->apiSecret) {
            $headers['api-secret'] = $this->apiSecret;
            $headers['secret'] = $this->apiSecret;
        }

        $http = Http::timeout($timeout)->withHeaders($headers);

        if ($token) {
            $http = $http->withToken($token);
        }

        return $http;
    }

    /**
     * Create/book a shipment order with NimbusPost.
     */
    public function createShipment(Order $order, array $customParams = []): array
    {
        // If order already has a shipment booked on NimbusPost, return it
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

        $order->loadMissing(['items.product', 'batch']);

        $weightKg = (float) ($customParams['weight'] ?? 0.5);
        $lengthCm = (int) ($customParams['length'] ?? 15);
        $breadthCm = (int) ($customParams['breadth'] ?? 10);
        $heightCm = (int) ($customParams['height'] ?? 10);

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->product_name,
                'qty' => $item->quantity,
                'units' => $item->quantity,
                'price' => (float) $item->unit_price,
                'selling_price' => (float) $item->unit_price,
                'sku' => $item->product?->sku ?? ('HB-SKU-'.$item->product_id),
                'hsn' => $item->hsn_code ?? '22019010',
                'discount' => 0,
            ];
        }

        $shippingAddress = $order->shipping_address ?? [];
        $recipientName = $order->customer_name;
        $recipientPhone = $order->customer_phone ?? '9876543210';
        $recipientEmail = $order->customer_email ?? 'devotee@haridwarbliss.com';

        $payload = [
            'order_number' => $order->order_number,
            'shipping_charges' => (float) $order->shipping_amount,
            'discount' => (float) ($order->discount_amount + $order->coupon_discount),
            'cod_charges' => 0.00,
            'payment_type' => strtolower((string) $order->payment_method) === 'cod' ? 'cod' : 'prepaid',
            'order_amount' => (float) $order->total_amount,
            'total_amount' => (float) $order->total_amount,
            'package_number' => $order->order_number,
            'weight' => $weightKg,
            'length' => $lengthCm,
            'breadth' => $breadthCm,
            'height' => $heightCm,
            'pickup_details' => [
                'warehouse_name' => $this->warehouseName,
                'name' => 'Haridwar Bliss Sacred Dispatch',
                'phone' => '9876543210',
                'address' => 'Har Ki Pauri Ghat Road, Sacred Consecration Center',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'pincode' => '249401',
                'country' => 'India',
            ],
            'consignee' => [
                'name' => $recipientName,
                'phone' => $recipientPhone,
                'email' => $recipientEmail,
                'address' => $shippingAddress['address_line1'] ?? '',
                'address_2' => $shippingAddress['address_line2'] ?? ($shippingAddress['landmark'] ?? ''),
                'city' => $shippingAddress['city'] ?? 'Haridwar',
                'state' => $shippingAddress['state'] ?? 'Uttarakhand',
                'pincode' => $shippingAddress['postal_code'] ?? '249401',
                'country' => $shippingAddress['country'] ?? 'India',
            ],
            'order_items' => $items,
        ];

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
                'nimbuspost_status' => 'manifested',
                'tracking_history' => [
                    [
                        'status' => 'Manifest Generated',
                        'status_code' => 'MANIFESTED',
                        'location' => 'Haridwar Bliss Fulfillment Facility',
                        'remarks' => 'Shipment created and AWB generated on NimbusPost.',
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

        try {
            $response = $this->client(30)->post("{$this->baseUrl}/shipments", $payload);
            $result = $response->json();

            if ($response->successful() && ! empty($result['status'])) {
                $data = $result['data'] ?? [];
                $orderId = $data['order_id'] ?? $data['id'] ?? null;
                $shipmentId = $data['shipment_id'] ?? null;
                $awbNumber = $data['awb_number'] ?? $data['awb'] ?? null;
                $courierName = $data['courier_name'] ?? 'NimbusPost Express';
                $courierId = $data['courier_id'] ?? null;
                $labelUrl = $data['label'] ?? $data['label_url'] ?? null;

                $order->update([
                    'nimbuspost_order_id' => (string) $orderId,
                    'nimbuspost_shipment_id' => (string) $shipmentId,
                    'awb_number' => (string) $awbNumber,
                    'tracking_number' => $order->tracking_number ?: (string) $awbNumber,
                    'courier_name' => $courierName,
                    'courier_id' => (string) $courierId,
                    'label_url' => $labelUrl,
                    'nimbuspost_status' => 'booked',
                    'tracking_history' => [
                        [
                            'status' => 'Shipment Booked',
                            'status_code' => 'BOOKED',
                            'location' => 'Haridwar Facility',
                            'remarks' => 'Shipment created with courier partner: '.$courierName,
                            'timestamp' => now()->toIso8601String(),
                        ],
                    ],
                ]);

                return [
                    'success' => true,
                    'order_id' => $orderId,
                    'shipment_id' => $shipmentId,
                    'awb_number' => $awbNumber,
                    'courier_name' => $courierName,
                    'label_url' => $labelUrl,
                    'data' => $data,
                ];
            }

            $errorMessage = $result['message'] ?? 'Failed to create shipment on NimbusPost.';
            Log::error('NimbusPost Create Shipment Error: '.$errorMessage, ['response' => $result]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'response' => $result,
            ];
        } catch (\Throwable $e) {
            Log::error('NimbusPost createShipment exception: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel an active shipment on NimbusPost.
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

        if (! $awb) {
            return [
                'success' => false,
                'error' => 'No AWB number associated with this shipment to cancel.',
            ];
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
            $response = $this->client(20)->post("{$this->baseUrl}/shipments/cancel", [
                'awb' => $awb,
            ]);

            $result = $response->json();

            if ($response->successful() && ! empty($result['status'])) {
                if ($order) {
                    $history = $order->tracking_history ?? [];
                    $history[] = [
                        'status' => 'Cancelled on NimbusPost',
                        'status_code' => 'CANCELLED',
                        'location' => 'NimbusPost Portal',
                        'remarks' => $reason ?? ($result['message'] ?? 'Shipment successfully cancelled on courier network.'),
                        'timestamp' => now()->toIso8601String(),
                    ];

                    $order->update([
                        'nimbuspost_status' => 'cancelled',
                        'tracking_history' => $history,
                    ]);
                }

                return [
                    'success' => true,
                    'message' => $result['message'] ?? 'Shipment cancelled successfully.',
                    'data' => $result['data'] ?? [],
                ];
            }

            $errorMessage = $result['message'] ?? 'Unable to cancel shipment on NimbusPost.';
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
            $response = $this->client(20)->get("{$this->baseUrl}/shipments/track", [
                'awb' => $awb,
            ]);

            $result = $response->json();

            if ($response->successful() && ! empty($result['status'])) {
                $data = $result['data'] ?? [];

                return [
                    'success' => true,
                    'awb' => $awb,
                    'current_status' => $data['current_status'] ?? ($data['status'] ?? 'Unknown'),
                    'courier_name' => $data['courier_name'] ?? null,
                    'checkpoints' => $data['scans'] ?? ($data['history'] ?? []),
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Unable to retrieve tracking info.',
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
     * Generate or retrieve shipping label URL.
     */
    public function getShippingLabel(string $awb): ?string
    {
        if ($this->isMockMode()) {
            return url('/admin/orders/label/'.$awb);
        }

        try {
            $response = $this->client(20)->get("{$this->baseUrl}/shipments/label", ['awb' => $awb]);
            $result = $response->json();

            if ($response->successful() && ! empty($result['status'])) {
                return $result['data']['label'] ?? ($result['data']['url'] ?? null);
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
            $response = $this->client(25)->post("{$this->baseUrl}/shipments/manifest", [
                'awbs' => $awbs,
            ]);

            $result = $response->json();
            if ($response->successful() && ! empty($result['status'])) {
                return [
                    'success' => true,
                    'manifest_url' => $result['data']['manifest_url'] ?? null,
                    'data' => $result['data'] ?? [],
                ];
            }

            return ['success' => false, 'error' => $result['message'] ?? 'Manifest generation failed'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check pincode serviceability and rates.
     */
    public function checkServiceability(string $originPincode, string $destPincode, float $weight = 0.5): array
    {
        if ($this->isMockMode()) {
            return [
                'success' => true,
                'serviceable' => true,
                'couriers' => [
                    ['name' => 'Delhivery Surface', 'rate' => 90.00, 'etd' => '3-4 days'],
                    ['name' => 'Blue Dart Air', 'rate' => 140.00, 'etd' => '1-2 days'],
                ],
            ];
        }

        try {
            $response = $this->client(15)->get("{$this->baseUrl}/courier/serviceability", [
                'origin' => $originPincode,
                'destination' => $destPincode,
                'weight' => $weight,
            ]);

            $result = $response->json();
            if ($response->successful() && ! empty($result['status'])) {
                return [
                    'success' => true,
                    'couriers' => $result['data'] ?? [],
                ];
            }

            return ['success' => false, 'error' => $result['message'] ?? 'Serviceability check failed'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verify NimbusPost webhook secret/signature if configured.
     */
    public function verifyWebhookSignature(?string $providedSecret): bool
    {
        if (empty($this->webhookSecret)) {
            return true;
        }

        return hash_equals((string) $this->webhookSecret, (string) $providedSecret);
    }
}
