<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\WaterCollectionLead;
use App\Models\WaterCollectionPackage;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WaterCollectionController extends Controller
{
    public function __construct(protected RazorpayService $razorpayService) {}

    /**
     * Display the Kumbh Water Collection lead generation landing page.
     */
    public function index(): View
    {
        if (WaterCollectionPackage::hasTable()) {
            WaterCollectionPackage::seedDefaultPackagesIfEmpty();
            $packages = WaterCollectionPackage::active()->get();
        } else {
            $packages = collect();
        }

        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');
        $supportEmail = SiteSetting::get('support_email', 'sales@haridwarbliss.com');
        $razorpayKeyId = $this->razorpayService->getKeyId();

        return view('pages.water-collection', compact('packages', 'supportPhone', 'supportEmail', 'razorpayKeyId'));
    }

    /**
     * Create Razorpay order for Kumbh water collection booking.
     */
    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'exists:water_collection_packages,id'],
            'quantity_count' => ['required', 'integer', 'min:1', 'max:100'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:25'],
        ]);

        $package = WaterCollectionPackage::findOrFail($validated['package_id']);
        $quantity = (int) $validated['quantity_count'];
        $totalAmount = (float) $package->price * $quantity;
        $receipt = 'kmb_'.time().'_'.Str::random(6);

        $order = $this->razorpayService->createOrder($totalAmount, $receipt, [
            'type' => 'kumbh_water_collection',
            'package_name' => $package->name,
            'quantity' => $quantity,
            'devotee_name' => $validated['name'] ?? '',
            'devotee_mobile' => $validated['mobile'] ?? '',
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'currency' => $order['currency'] ?? 'INR',
            'key' => $this->razorpayService->getKeyId(),
            'name' => 'Haridwar Bliss',
            'description' => "Haridwar Kumbh Water Collection ({$quantity}x {$package->name})",
            'prefill' => [
                'name' => $validated['name'] ?? '',
                'email' => $validated['email'] ?? '',
                'contact' => $validated['mobile'] ?? '',
            ],
            'is_mock' => $order['is_mock'] ?? false,
        ]);
    }

    /**
     * Store a new Kumbh water collection lead with payment processing.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'min:7', 'max:25'],
            'email' => ['nullable', 'email', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_city' => ['nullable', 'string', 'max:100'],
            'delivery_state' => ['nullable', 'string', 'max:100'],
            'delivery_pincode' => ['nullable', 'string', 'max:20'],
            'hotel_or_dharamshala' => ['required', 'string', 'max:255'],
            'pickup_room_or_landmark' => ['nullable', 'string', 'max:255'],
            'package_id' => ['required', 'exists:water_collection_packages,id'],
            'quantity_count' => ['required', 'integer', 'min:1', 'max:100'],
            'pickup_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['nullable', 'string', 'in:razorpay,cod'],
            'razorpay_payment_id' => ['nullable', 'string'],
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        $package = WaterCollectionPackage::findOrFail($validated['package_id']);
        $quantityCount = (int) $validated['quantity_count'];
        $unitPrice = (float) $package->price;
        $totalPrice = $unitPrice * $quantityCount;

        $totalLitres = ((float) $package->quantity_litres) * $quantityCount;
        $waterQuantityString = $totalLitres.' '.($package->unit ?: 'Litres').' ('.$quantityCount.'x '.$package->name.')';

        $paymentMethod = $validated['payment_method'] ?? 'razorpay';
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if ($paymentMethod === 'razorpay') {
            if ($razorpayOrderId && $razorpaySignature && $razorpayPaymentId) {
                $isValid = $this->razorpayService->verifyPaymentSignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);
                if (! $isValid) {
                    return back()->withInput()->with('error', 'Razorpay payment verification failed. Please try again or select Pay on Collection.');
                }
                $paymentStatus = 'paid';
            } elseif ($this->razorpayService->isDummy() && $razorpayPaymentId) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'pending';
            }
        } else {
            $paymentStatus = 'cod';
        }

        $leadNumber = WaterCollectionLead::generateLeadNumber();

        $lead = WaterCollectionLead::create([
            'lead_number' => $leadNumber,
            'name' => trim($validated['name']),
            'mobile' => trim($validated['mobile']),
            'email' => ! empty($validated['email']) ? trim($validated['email']) : null,
            'delivery_address' => trim($validated['delivery_address']),
            'delivery_city' => ! empty($validated['delivery_city']) ? trim($validated['delivery_city']) : null,
            'delivery_state' => ! empty($validated['delivery_state']) ? trim($validated['delivery_state']) : null,
            'delivery_pincode' => ! empty($validated['delivery_pincode']) ? trim($validated['delivery_pincode']) : null,
            'hotel_or_dharamshala' => trim($validated['hotel_or_dharamshala']),
            'pickup_room_or_landmark' => ! empty($validated['pickup_room_or_landmark']) ? trim($validated['pickup_room_or_landmark']) : null,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'water_quantity' => $waterQuantityString,
            'quantity_count' => $quantityCount,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_signature' => $razorpaySignature,
            'pickup_date' => ! empty($validated['pickup_date']) ? $validated['pickup_date'] : null,
            'notes' => ! empty($validated['notes']) ? trim($validated['notes']) : null,
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Haridwar Kumbh Ganga Jal collection request submitted successfully!',
                'lead_number' => $lead->lead_number,
                'redirect_url' => route('water-collection.confirmed', ['lead_number' => $lead->lead_number]),
            ], 201);
        }

        return redirect()->route('water-collection.confirmed', ['lead_number' => $lead->lead_number])
            ->with('success', 'Jai Gange! Your Haridwar Kumbh Ganga Jal collection request has been registered.');
    }

    /**
     * Show lead confirmation page.
     */
    public function confirmed(string $lead_number): View
    {
        $lead = WaterCollectionLead::with('package')->where('lead_number', $lead_number)->firstOrFail();
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');

        return view('pages.water-collection-confirmed', compact('lead', 'supportPhone'));
    }
}
