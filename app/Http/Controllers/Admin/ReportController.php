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
        $salesCount = Order::where('payment_status', 'paid')->count();
        $totalSales = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $membershipCount = Membership::where('status', 'active')->count();
        $membershipTotal = (float) Membership::sum('fee_paid');
        $gstEstimated = ($totalSales + $membershipTotal) * 0.18; // 18% standard GST calculation representation

        return view('admin.reports.index', compact(
            'salesCount',
            'totalSales',
            'membershipCount',
            'membershipTotal',
            'gstEstimated'
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
                fputcsv($handle, ['Order Number', 'Customer Name', 'Customer Email', 'Date', 'Items Subtotal (INR)', 'Discount (INR)', 'Shipping (INR)', 'Total (INR)', 'Status', 'Payment Method']);

                Order::with('user')->chunk(100, function ($orders) use ($handle): void {
                    foreach ($orders as $order) {
                        fputcsv($handle, [
                            $order->order_number,
                            $order->user->name ?? 'N/A',
                            $order->user->email ?? 'N/A',
                            $order->created_at->format('Y-m-d H:i'),
                            $order->subtotal,
                            $order->discount_amount,
                            $order->shipping_amount,
                            $order->total_amount,
                            $order->order_status,
                            $order->payment_method,
                        ]);
                    }
                });
            } elseif ($type === 'memberships') {
                fputcsv($handle, ['Member Name', 'Member Email', 'Phone', 'Fee Paid (INR)', 'Started At', 'Expires At', 'Status', 'Payment ID']);

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
                fputcsv($handle, ['Transaction Type', 'Reference No', 'Date', 'Gross Amount (INR)', 'Taxable Value (INR)', 'IGST/CGST+SGST @ 18% (INR)', 'Total (INR)']);

                Order::where('payment_status', 'paid')->chunk(100, function ($orders) use ($handle): void {
                    foreach ($orders as $o) {
                        $gross = (float) $o->total_amount;
                        $taxable = round($gross / 1.18, 2);
                        $tax = round($gross - $taxable, 2);
                        fputcsv($handle, [
                            'Order',
                            $o->order_number,
                            $o->created_at->format('Y-m-d'),
                            $gross,
                            $taxable,
                            $tax,
                            $gross,
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
