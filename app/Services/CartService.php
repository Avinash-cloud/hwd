<?php

namespace App\Services;

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
     * Calculate detailed cart summary including Haridwar Bliss monthly subscription rules.
     *
     * Rules:
     * - If active member and free_first_bottle_enabled is true:
     *   - 1st bottle of Gangajal is ₹0.
     *   - Additional Gangajal bottles charged at standard price.
     *   - Flat shipping rate is applied per order (default ₹149).
     *
     * @return array{
     *     items: array<int, array>,
     *     subtotal: float,
     *     discount: float,
     *     shipping: float,
     *     total: float,
     *     free_bottle_applied: bool,
     *     gangajal_count: int
     * }
     */
    public function getDetails(?User $user = null): array
    {
        $rawCart = $this->getRawCart();
        $isMember = $user && $user->hasActiveMembership();
        $freeBottleRuleEnabled = (bool) SiteSetting::get('free_first_bottle_enabled', '1');
        $shippingFee = (float) SiteSetting::get('shipping_charge', '149.00');

        $items = [];
        $subtotal = 0.0;
        $discount = 0.0;
        $gangajalCount = 0;
        $freeBottleApplied = false;

        if (empty($rawCart)) {
            return [
                'items' => [],
                'subtotal' => 0.0,
                'discount' => 0.0,
                'shipping' => 0.0,
                'total' => 0.0,
                'free_bottle_applied' => false,
                'gangajal_count' => 0,
            ];
        }

        $products = Product::whereIn('id', array_keys($rawCart))->get()->keyBy('id');

        // First pass to check Gangajal
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

                // If active member and free bottle rule is enabled, the 1st bottle is free
                if ($isMember && $freeBottleRuleEnabled && ! $freeBottleApplied) {
                    $lineDiscount = $unitPrice; // 1 bottle free
                    $freeBottleApplied = true;
                    $hasFreeBottle = true;
                }
            } elseif ($isMember && $product->member_price !== null) {
                // Member discount on non-gangajal items
                $memberSavings = max(0, $unitPrice - (float) $product->member_price);
                $lineDiscount = $memberSavings * $qty;
            }

            $lineTotal = max(0, $lineSubtotal - $lineDiscount);
            $subtotal += $lineSubtotal;
            $discount += $lineDiscount;

            $items[] = [
                'product' => $product,
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'compare_price' => $product->compare_price ? (float) $product->compare_price : null,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'line_subtotal' => $lineSubtotal,
                'line_discount' => $lineDiscount,
                'line_total' => $lineTotal,
                'has_free_bottle' => $hasFreeBottle,
                'is_free_monthly_bottle' => $hasFreeBottle,
            ];
        }

        $shipping = count($items) > 0 ? $shippingFee : 0.0;
        $total = max(0, ($subtotal - $discount) + $shipping);

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'discount_amount' => $discount,
            'shipping' => $shipping,
            'shipping_amount' => $shipping,
            'total' => $total,
            'total_amount' => $total,
            'total_items_count' => $this->count(),
            'free_bottle_applied' => $freeBottleApplied,
            'gangajal_count' => $gangajalCount,
        ];
    }
}
