<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Dispatch Packing Slip - {{ $order->order_number }}</title>
        @vite(['resources/css/app.css'])
        <style>
            @media print {
                .no-print { display: none !important; }
                body { background: white !important; font-size: 11pt !important; }
            }
        </style>
    </head>
    <body class="bg-slate-100 p-4 sm:p-10 text-slate-900 font-sans">
        <div class="max-w-2xl mx-auto bg-white border border-slate-300 p-8 rounded-2xl shadow-sm space-y-6">
            <div class="no-print flex justify-between items-center pb-4 border-b border-slate-200">
                <span class="text-xs font-bold text-slate-500">Warehouse Dispatch Manifest</span>
                <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg">
                    Print Slip
                </button>
            </div>

            <div class="flex justify-between items-start border-b-2 border-slate-900 pb-4">
                <div>
                    <h1 class="font-serif font-bold text-xl text-slate-900">Haridwar Bliss — Dispatch Slip</h1>
                    <p class="text-xs text-slate-500">Brahmakund Sacred Bottling Unit · Har Ki Pauri</p>
                </div>
                <div class="text-right">
                    <strong class="font-mono text-base block">{{ $order->order_number }}</strong>
                    <span class="text-xs text-slate-500">AWB: {{ $order->tracking_number ?? 'PENDING' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 text-xs pb-4 border-b border-slate-200">
                <div>
                    <span class="font-bold text-slate-400 uppercase text-[10px] block mb-1">Ship To Devotee:</span>
                    <strong class="font-bold text-slate-900 block">{{ $order->shipping_address['recipient_name'] ?? $order->user->name }}</strong>
                    <p>{{ $order->shipping_address['address_line1'] ?? '' }} {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                    <p>Phone: {{ $order->shipping_address['phone'] ?? $order->user->phone }}</p>
                </div>

                <div class="flex items-center justify-end gap-3 text-right">
                    <div class="space-y-1">
                        <span class="font-bold text-slate-400 uppercase text-[10px] block">Traceability Batch:</span>
                        <strong class="font-mono text-slate-900 block">{{ $order->batch?->batch_number ?? 'HB-GANG-001' }}</strong>
                        <span class="text-[10px] text-slate-500 block">Har Ki Pauri Brahmakund</span>
                    </div>
                    <div class="w-16 h-16 p-1 border border-slate-300 rounded flex items-center justify-center">
                        {{-- {!! $qrSvg !!} --}}
                        <img src={!! $qrSvg !!} alt="">
                    </div>
                </div>
            </div>

            <div class="space-y-2 text-xs">
                <span class="font-bold text-slate-400 uppercase text-[10px] block">Package Items:</span>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 text-[10px]">
                            <th class="py-1.5">Product</th>
                            <th class="py-1.5 text-center">Qty</th>
                            <th class="py-1.5 text-right">Verification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-2">
                                    <strong class="text-slate-900">{{ $item->product_name }}</strong>
                                    @if ($item->is_free_monthly_bottle)
                                        <span class="text-[10px] text-[#0369A1] block font-bold">★ Member 1st Bottle</span>
                                    @endif
                                </td>
                                <td class="py-2 text-center">{{ $item->quantity }}</td>
                                <td class="py-2 text-right text-[#0369A1] font-bold">Passed Inspection</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-200 text-[10px] text-slate-400 text-center">
                Packed with reverence under hermetic sanitary seal. Har Har Gange!
            </div>
        </div>
    </body>
</html>
