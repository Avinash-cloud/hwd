<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;

class CartService
{
    /**
     * Get raw cart contents from session.
     *
     * @return array<int, array{product_id: int, quantity: int}>
     */
    public function getRawCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Add or increment item in cart.
     */
    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->getRawCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Update item quantity in cart.
     */
    public function update(int $productId, int $quantity): void
    {
        $cart = $this->getRawCart();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);
    }

    /**
     * Remove item from cart.
     */
    public function remove(int $productId): void
    {
        $cart = $this->getRawCart();
        unset($cart[$productId]);
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

        // Temporarily clear coupon from session to check raw subtotal
        session()->forget('applied_coupon');
        $details = $this->getDetails($user);

        $validation = $coupon->validateForSubtotal($details['subtotal'] - $details['member_discount']);
        if (! $validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        session()->put('applied_coupon', $code);

        return ['success' => true, 'message' => "Coupon '{$code}' applied successfully!"];
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
     *
     * Rules:
     * - If active member and free_first_bottle_enabled is true:
     *   - Member can claim 1 product for free (₹0) per calendar month.
     *   - If multiple eligible products are in the cart, the lower-priced eligible item is free.
     *   - Additional eligible products/bottles charged at standard/member price.
     *   - If user already claimed this calendar month, standard/member pricing applies.
     *
     * @return array{
     *     items: array<int, array>,
     *     subtotal: float,
     *     discount: float,
     *     discount_amount: float,
     *     member_discount: float,
     *     coupon_discount: float,
     *     coupon_code: ?string,
     *     shipping: float,
     *     shipping_amount: float,
     *     total: float,
     *     total_amount: float,
     *     total_items_count: int,
     *     free_bottle_applied: bool,
     *     monthly_quota_used: bool,
     *     can_claim_monthly_free: bool,
     *     gangajal_count: int
     * }
     */
    public function getDetails(?User $user = null): array
    {
        $rawCart = $this->getRawCart();
        $isMember = (bool) ($user && ($user->hasActiveMembership() || $user->is_admin));
        $freeBottleRuleEnabled = (bool) SiteSetting::get('free_first_bottle_enabled', '1');
        $shippingFee = (float) SiteSetting::get('shipping_charge', '149.00');

        $hasClaimedThisMonth = $user ? $user->hasClaimedMonthlyFreeProduct() : false;
        $canClaimMonthlyFree = $isMember && $freeBottleRuleEnabled && ! $hasClaimedThisMonth;

        $items = [];
        $subtotal = 0.0;
        $memberDiscount = 0.0;
        $gangajalCount = 0;
        $freeBottleApplied = false;

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
                'total' => 0.0,
                'total_amount' => 0.0,
                'total_items_count' => 0,
                'free_bottle_applied' => false,
                'monthly_quota_used' => $hasClaimedThisMonth,
                'can_claim_monthly_free' => $canClaimMonthlyFree,
                'gangajal_count' => 0,
            ];
        }

        $products = Product::whereIn('id', array_keys($rawCart))->get()->keyBy('id');

        // Step 1: Identify the lowest-priced eligible product in cart if member can claim monthly free
        $freeItemProductId = null;
        if ($canClaimMonthlyFree) {
            $minEligiblePrice = PHP_FLOAT_MAX;
            foreach ($rawCart as $pId => $entry) {
                /** @var Product|null $p */
                $p = $products->get($pId);
                if ($p && ($p->is_member_free_eligible || $p->is_gangajal)) {
                    $uPrice = (float) $p->price;
                    if ($uPrice < $minEligiblePrice) {
                        $minEligiblePrice = $uPrice;
                        $freeItemProductId = $p->id;
                    }
                }
            }
        }

        // Step 2: Build line items
        foreach ($rawCart as $productId => $entry) {
            /** @var Product|null $product */
            $product = $products->get($productId);
            if (! $product) {
                continue;
            }

            $qty = $entry['quantity'];
            $unitPrice = (float) $product->price;
            $lineSubtotal = $unitPrice * $qty;
            $lineDiscount = 0.0;
            $hasFreeBottle = false;

            if ($product->is_gangajal) {
                $gangajalCount += $qty;
            }

            // Check if this item is the chosen free monthly item
            if ($canClaimMonthlyFree && ! $freeBottleApplied && $product->id === $freeItemProductId) {
                // 1 unit is free
                $hasFreeBottle = true;
                $freeBottleApplied = true;
                $lineDiscount += $unitPrice;

                // Remaining units of this product get member discount only if non-gangajal with positive member_price
                if ($qty > 1 && $isMember && ! $product->is_gangajal && $product->member_price !== null && (float) $product->member_price > 0) {
                    $extraDiscount = max(0, $unitPrice - (float) $product->member_price) * ($qty - 1);
                    $lineDiscount += $extraDiscount;
                }
            } elseif ($isMember && ! $product->is_gangajal && $product->member_price !== null && (float) $product->member_price > 0) {
                // Member discount on non-free items
                $memberSavings = max(0, $unitPrice - (float) $product->member_price);
                $lineDiscount += $memberSavings * $qty;
            }

            $lineTotal = max(0, $lineSubtotal - $lineDiscount);
            $subtotal += $lineSubtotal;
            $memberDiscount += $lineDiscount;

            $items[] = [
                'product' => $product,
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image_url,
                'compare_price' => $product->compare_price ? (float) $product->compare_price : null,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
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
            'total' => $total,
            'total_amount' => $total,
            'total_items_count' => $this->count(),
            'free_bottle_applied' => $freeBottleApplied,
            'monthly_quota_used' => $hasClaimedThisMonth,
            'can_claim_monthly_free' => $canClaimMonthlyFree,
            'gangajal_count' => $gangajalCount,
        ];
    }
}
