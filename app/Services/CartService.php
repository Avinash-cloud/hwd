<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use App\Models\User;

class CartService
{
    /**
     * Generate unique session key for product and optional variant.
     */
    public function makeKey(int $productId, ?int $variantId = null): string
    {
        return $variantId ? "{$productId}_{$variantId}" : (string) $productId;
    }

    /**
     * Get raw cart contents from session.
     *
     * @return array<string, array{product_id: int, variant_id: ?int, quantity: int}>
     */
    public function getRawCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Add or increment item in cart.
     */
    public function add(int $productId, int $quantity = 1, ?int $variantId = null): void
    {
        $cart = $this->getRawCart();
        $key = $this->makeKey($productId, $variantId);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Update item quantity in cart.
     */
    public function update(string|int $itemKey, int $quantity): void
    {
        $cart = $this->getRawCart();
        $key = (string) $itemKey;

        if ($quantity <= 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);
    }

    /**
     * Remove item from cart.
     */
    public function remove(string|int $itemKey): void
    {
        $cart = $this->getRawCart();
        $key = (string) $itemKey;
        unset($cart[$key]);
        session()->put('cart', $cart);
    }

    /**
     * Clear entire cart.
     */
    public function clear(): void
    {
        session()->forget('cart');
        session()->forget('applied_coupon');
    }

    /**
     * Get total quantity of items in cart.
     */
    public function count(): int
    {
        $count = 0;
        foreach ($this->getRawCart() as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    /**
     * Apply coupon code to cart.
     *
     * @return array{success: bool, message: string}
     */
    public function applyCoupon(string $code, ?User $user = null): array
    {
        $code = strtoupper(trim($code));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        $details = $this->getDetails($user);
        $netSubtotal = max(0, $details['subtotal'] - $details['member_discount']);

        $validation = $coupon->validateForSubtotal($netSubtotal);
        if (! $validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        session()->put('applied_coupon', $coupon->code);

        return ['success' => true, 'message' => 'Sacred coupon code applied successfully!'];
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon(): void
    {
        session()->forget('applied_coupon');
    }

    /**
     * Calculate detailed cart summary including Haridwar Bliss monthly subscription rules and coupons.
     */
    public function getDetails(?User $user = null): array
    {
        $rawCart = $this->getRawCart();
        $isMember = (bool) ($user && ($user->hasActiveMembership() || $user->is_admin));
        $freeBottleRuleEnabled = (bool) SiteSetting::get('free_first_bottle_enabled', '1');

        $standardShippingFee = (float) SiteSetting::get('shipping_charge', '149.00');
        $memberShippingSetting = SiteSetting::get('member_shipping_charge');
        $isMemberShippingApplied = false;

        if ($isMember && $memberShippingSetting !== null && $memberShippingSetting !== '') {
            $shippingFee = (float) $memberShippingSetting;
            $isMemberShippingApplied = true;
        } else {
            $shippingFee = $standardShippingFee;
        }

        $hasClaimedThisMonth = $user ? $user->hasClaimedMonthlyFreeProduct() : false;
        $canClaimMonthlyFree = $isMember && $freeBottleRuleEnabled && ! $hasClaimedThisMonth;

        $items = [];
        $subtotal = 0.0;
        $memberDiscount = 0.0;
        $gangajalCount = 0;
        $freeBottleApplied = false;

        $totalTaxable = 0.0;
        $totalGst = 0.0;

        if (empty($rawCart)) {
            return [
                'items' => [],
                'subtotal' => 0.0,
                'discount' => 0.0,
                'discount_amount' => 0.0,
                'member_discount' => 0.0,
                'coupon_discount' => 0.0,
                'coupon_code' => null,
                'shipping' => 0.0,
                'shipping_amount' => 0.0,
                'is_member_shipping' => $isMemberShippingApplied,
                'member_shipping_charge' => ($memberShippingSetting !== null && $memberShippingSetting !== '') ? (float) $memberShippingSetting : null,
                'standard_shipping_charge' => $standardShippingFee,
                'total' => 0.0,
                'total_amount' => 0.0,
                'total_items_count' => 0,
                'free_bottle_applied' => false,
                'monthly_quota_used' => $hasClaimedThisMonth,
                'can_claim_monthly_free' => $canClaimMonthlyFree,
                'gangajal_count' => 0,
                'total_taxable_amount' => 0.0,
                'total_gst_amount' => 0.0,
                'cgst_amount' => 0.0,
                'sgst_amount' => 0.0,
                'igst_amount' => 0.0,
            ];
        }

        // Fetch products and variants
        $productIds = [];
        $variantIds = [];
        foreach ($rawCart as $entry) {
            $productIds[] = $entry['product_id'];
            if (! empty($entry['variant_id'])) {
                $variantIds[] = $entry['variant_id'];
            }
        }

        $products = Product::whereIn('id', array_unique($productIds))->get()->keyBy('id');
        $variants = ! empty($variantIds) ? ProductVariant::whereIn('id', array_unique($variantIds))->get()->keyBy('id') : collect();

        // Step 1: Identify lowest-priced eligible product for monthly free quota
        $freeItemKey = null;
        if ($canClaimMonthlyFree) {
            $minEligiblePrice = PHP_FLOAT_MAX;
            foreach ($rawCart as $key => $entry) {
                /** @var Product|null $p */
                $p = $products->get($entry['product_id']);
                if ($p && ($p->is_member_free_eligible || $p->is_gangajal)) {
                    $v = ! empty($entry['variant_id']) ? $variants->get($entry['variant_id']) : null;
                    $uPrice = $v ? (float) $v->price : (float) $p->price;
                    if ($uPrice < $minEligiblePrice) {
                        $minEligiblePrice = $uPrice;
                        $freeItemKey = $key;
                    }
                }
            }
        }

        // Step 2: Build line items
        foreach ($rawCart as $key => $entry) {
            /** @var Product|null $product */
            $product = $products->get($entry['product_id']);
            if (! $product) {
                continue;
            }

            /** @var ProductVariant|null $variant */
            $variant = ! empty($entry['variant_id']) ? $variants->get($entry['variant_id']) : null;

            $qty = $entry['quantity'];
            $unitPrice = $variant ? (float) $variant->price : (float) $product->price;
            $memberPrice = $variant ? $variant->member_price : $product->member_price;
            $comparePrice = $variant ? $variant->compare_price : $product->compare_price;
            $hsnCode = ($variant && $variant->hsn_code) ? $variant->hsn_code : ($product->hsn_code ?? '22019010');
            $gstRate = (float) (($variant && $variant->gst_percentage) ? $variant->gst_percentage : ($product->gst_percentage ?? 18.00));

            $lineSubtotal = $unitPrice * $qty;
            $lineDiscount = 0.0;
            $hasFreeBottle = false;

            if ($product->is_gangajal) {
                $gangajalCount += $qty;
            }

            // Check if this item is the chosen free monthly item
            if ($canClaimMonthlyFree && ! $freeBottleApplied && $key === $freeItemKey) {
                $hasFreeBottle = true;
                $freeBottleApplied = true;
                $lineDiscount += $unitPrice;

                if ($qty > 1 && $isMember && ! $product->is_gangajal && $memberPrice !== null && (float) $memberPrice > 0) {
                    $extraDiscount = max(0, $unitPrice - (float) $memberPrice) * ($qty - 1);
                    $lineDiscount += $extraDiscount;
                }
            } elseif ($isMember && ! $product->is_gangajal && $memberPrice !== null && (float) $memberPrice > 0) {
                $memberSavings = max(0, $unitPrice - (float) $memberPrice);
                $lineDiscount += $memberSavings * $qty;
            }

            $lineTotal = max(0, $lineSubtotal - $lineDiscount);
            $subtotal += $lineSubtotal;
            $memberDiscount += $lineDiscount;

            // Reverse GST calculation for this line
            $lineTax = GstService::calculate($lineTotal, $gstRate);
            $totalTaxable += $lineTax['taxable'];
            $totalGst += $lineTax['gst'];

            $displayName = $product->name.($variant ? " ({$variant->name})" : '');

            $items[] = [
                'key' => (string) $key,
                'product' => $product,
                'product_id' => $product->id,
                'variant' => $variant,
                'variant_id' => $variant?->id,
                'variant_name' => $variant?->name,
                'name' => $displayName,
                'image' => $product->image_url,
                'compare_price' => $comparePrice ? (float) $comparePrice : null,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'hsn_code' => $hsnCode,
                'gst_percentage' => $gstRate,
                'taxable_amount' => $lineTax['taxable'],
                'gst_amount' => $lineTax['gst'],
                'line_subtotal' => $lineSubtotal,
                'line_discount' => $lineDiscount,
                'line_total' => $lineTotal,
                'has_free_bottle' => $hasFreeBottle,
                'is_free_monthly_bottle' => $hasFreeBottle,
                'is_eligible_for_free' => (bool) ($product->is_member_free_eligible || $product->is_gangajal),
            ];
        }

        // Step 3: Handle Coupon Code
        $couponCode = session('applied_coupon');
        $couponDiscount = 0.0;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            $netBeforeCoupon = max(0, $subtotal - $memberDiscount);

            if ($coupon && $coupon->validateForSubtotal($netBeforeCoupon)['valid']) {
                $couponDiscount = $coupon->calculateDiscount($netBeforeCoupon);
            } else {
                session()->forget('applied_coupon');
                $couponCode = null;
            }
        }

        $totalDiscount = $memberDiscount + $couponDiscount;
        $shipping = count($items) > 0 ? $shippingFee : 0.0;
        $total = max(0, ($subtotal - $totalDiscount) + $shipping);

        $halfGst = round($totalGst / 2, 2);

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $totalDiscount,
            'discount_amount' => $totalDiscount,
            'member_discount' => $memberDiscount,
            'coupon_discount' => $couponDiscount,
            'coupon_code' => $couponCode,
            'shipping' => $shipping,
            'shipping_amount' => $shipping,
            'is_member_shipping' => $isMemberShippingApplied,
            'member_shipping_charge' => ($memberShippingSetting !== null && $memberShippingSetting !== '') ? (float) $memberShippingSetting : null,
            'standard_shipping_charge' => $standardShippingFee,
            'total' => $total,
            'total_amount' => $total,
            'total_items_count' => $this->count(),
            'free_bottle_applied' => $freeBottleApplied,
            'monthly_quota_used' => $hasClaimedThisMonth,
            'can_claim_monthly_free' => $canClaimMonthlyFree,
            'gangajal_count' => $gangajalCount,
            'total_taxable_amount' => round($totalTaxable, 2),
            'total_gst_amount' => round($totalGst, 2),
            'cgst_amount' => $halfGst,
            'sgst_amount' => $halfGst,
            'igst_amount' => round($totalGst, 2),
        ];
    }
}
