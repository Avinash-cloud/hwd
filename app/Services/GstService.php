<?php

namespace App\Services;

class GstService
{
    /**
     * Calculate reverse GST from inclusive selling price.
     * Formula:
     * Taxable Value = Selling Price / (1 + (GST % / 100))
     * GST Amount = Selling Price - Taxable Value
     */
    public static function calculate(float $inclusivePrice, float $gstPercentage = 18.00): array
    {
        $inclusive = round($inclusivePrice, 2);
        $rate = round($gstPercentage, 2);

        if ($rate <= 0 || $inclusive <= 0) {
            return [
                'inclusive' => $inclusive,
                'rate' => $rate,
                'taxable' => $inclusive,
                'gst' => 0.00,
                'cgst' => 0.00,
                'sgst' => 0.00,
                'igst' => 0.00,
            ];
        }

        $taxable = round($inclusive / (1 + ($rate / 100)), 2);
        $gst = round($inclusive - $taxable, 2);
        $halfGst = round($gst / 2, 2);

        return [
            'inclusive' => $inclusive,
            'rate' => $rate,
            'taxable' => $taxable,
            'gst' => $gst,
            'cgst' => $halfGst,
            'sgst' => $halfGst,
            'igst' => $gst,
        ];
    }
}
