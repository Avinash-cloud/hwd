<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $paidOrders = Order::where('payment_status', 'paid')->get();

        $salesCount = $paidOrders->count();
        $totalReceived = (float) $paidOrders->sum('total_amount');

        // Product vs Delivery Breakdown
        $totalProductGross = 0.0;
        $totalDeliveryGross = 0.0;

        foreach ($paidOrders as $o) {
            $productNet = max(0, (float) $o->subtotal - (float) $o->discount_amount);
            $totalProductGross += $productNet;
            $totalDeliveryGross += (float) $o->shipping_amount;
        }

        $productTaxable = round($totalProductGross / 1.18, 2);
        $productGst = round($totalProductGross - $productTaxable, 2);

        $deliveryTaxable = round($totalDeliveryGross / 1.18, 2);
        $deliveryGst = round($totalDeliveryGross - $deliveryTaxable, 2);

        $membershipCount = Membership::where('status', 'active')->count();
        $membershipTotal = (float) Membership::sum('fee_paid');
        $membershipTaxable = round($membershipTotal / 1.18, 2);
        $membershipGst = round($membershipTotal - $membershipTaxable, 2);

        $totalGstAll = $productGst + $deliveryGst + $membershipGst;

        return view('admin.reports.index', compact(
            'salesCount',
            'totalReceived',
            'totalProductGross',
            'productTaxable',
            'productGst',
            'totalDeliveryGross',
            'deliveryTaxable',
            'deliveryGst',
            'membershipCount',
            'membershipTotal',
            'membershipTaxable',
            'membershipGst',
            'totalGstAll'
        ));
    }

    /**
     * Stream CSV export of selected report type.
     */
    public function export(string $type, Request $request): StreamedResponse
    {
        $filename = "haridwar-bliss-{$type}-report-".date('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($type): void {
            $handle = fopen('php://output', 'w');

            if ($type === 'sales') {
                fputcsv($handle, [
                    'Order Number',
                    'Customer Name',
                    'Customer Email',
                    'Date',
                    'Items Subtotal (INR)',
                    'Member Discount (INR)',
                    'Coupon Code',
                    'Coupon Discount (INR)',
                    'Shipping Fee (INR)',
                    'Total Amount Paid (INR)',
                    'Status',
                    'Payment Method',
                ]);

                Order::with('user')->chunk(100, function ($orders) use ($handle): void {
                    foreach ($orders as $order) {
                        fputcsv($handle, [
                            $order->order_number,
                            $order->user->name ?? ($order->shipping_address['recipient_name'] ?? 'N/A'),
                            $order->user->email ?? 'N/A',
                            $order->created_at->format('Y-m-d H:i'),
                            $order->subtotal,
                            $order->discount_amount,
                            $order->coupon_code ?? 'None',
                            $order->coupon_discount ?? 0.00,
                            $order->shipping_amount,
                            $order->total_amount,
                            $order->order_status,
                            $order->payment_method,
                        ]);
                    }
                });
            } elseif ($type === 'memberships') {
                fputcsv($handle, [
                    'Member Name',
                    'Member Email',
                    'Phone',
                    'Fee Paid (INR)',
                    'Started At',
                    'Expires At',
                    'Status',
                    'Payment ID',
                ]);

                Membership::with('user')->chunk(100, function ($memberships) use ($handle): void {
                    foreach ($memberships as $m) {
                        fputcsv($handle, [
                            $m->user->name ?? 'N/A',
                            $m->user->email ?? 'N/A',
                            $m->user->phone ?? 'N/A',
                            $m->fee_paid,
                            $m->starts_at->format('Y-m-d'),
                            $m->expires_at->format('Y-m-d'),
                            $m->status,
                            $m->payment_id,
                        ]);
                    }
                });
            } elseif ($type === 'gst') {
                fputcsv($handle, [
                    'Transaction Type',
                    'Reference No',
                    'Date',
                    'Customer Name',
                    'State',
                    'Product Gross (INR)',
                    'Product Taxable (INR)',
                    'Product GST @ 18% (INR)',
                    'Delivery Fee Gross (INR)',
                    'Delivery Fee Taxable (INR)',
                    'Delivery GST @ 18% (INR)',
                    'Total Amount Received (INR)',
                    'CGST @ 9% (INR)',
                    'SGST @ 9% (INR)',
                    'IGST @ 18% (INR)',
                    'Payment Status',
                ]);

                Order::where('payment_status', 'paid')->chunk(100, function ($orders) use ($handle): void {
                    foreach ($orders as $o) {
                        $productGross = max(0, (float) $o->subtotal - (float) $o->discount_amount);
                        $productTaxable = round($productGross / 1.18, 2);
                        $productTax = round($productGross - $productTaxable, 2);

                        $deliveryGross = (float) $o->shipping_amount;
                        $deliveryTaxable = round($deliveryGross / 1.18, 2);
                        $deliveryTax = round($deliveryGross - $deliveryTaxable, 2);

                        $totalReceived = (float) $o->total_amount;
                        $totalTax = $productTax + $deliveryTax;

                        $state = $o->shipping_address['state'] ?? 'Uttarakhand';
                        $isIntraState = strcasecmp(trim($state), 'Uttarakhand') === 0;

                        $cgst = $isIntraState ? round($totalTax / 2, 2) : 0.00;
                        $sgst = $isIntraState ? round($totalTax / 2, 2) : 0.00;
                        $igst = ! $isIntraState ? $totalTax : 0.00;

                        fputcsv($handle, [
                            'Order',
                            $o->order_number,
                            $o->created_at->format('Y-m-d'),
                            $o->user->name ?? ($o->shipping_address['recipient_name'] ?? 'Devotee'),
                            $state,
                            $productGross,
                            $productTaxable,
                            $productTax,
                            $deliveryGross,
                            $deliveryTaxable,
                            $deliveryTax,
                            $totalReceived,
                            $cgst,
                            $sgst,
                            $igst,
                            $o->payment_status,
                        ]);
                    }
                });
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
