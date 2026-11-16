<?php

namespace App\Http\Controllers;

use App\Models\BulkOrderInquiry;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BulkOrderInquiryController extends Controller
{
    /**
     * Store a new bulk order inquiry.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:7', 'max:25'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'message' => ['nullable', 'string', 'max:3000'],
        ]);

        $product = Product::find($validated['product_id']);

        $inquiry = BulkOrderInquiry::create([
            'product_id' => $product?->id,
            'product_name' => $product ? $product->name : 'N/A',
            'name' => trim($validated['name']),
            'email' => ! empty($validated['email']) ? trim($validated['email']) : null,
            'phone' => trim($validated['phone']),
            'quantity' => (int) $validated['quantity'],
            'message' => ! empty($validated['message']) ? trim($validated['message']) : null,
            'status' => 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your bulk inquiry! Our team will contact you shortly on WhatsApp / Phone with customized wholesale pricing.',
                'inquiry_id' => $inquiry->id,
            ], 201);
        }

        return back()->with('success', 'Thank you for your bulk inquiry! Our team will contact you shortly.');
    }
}
