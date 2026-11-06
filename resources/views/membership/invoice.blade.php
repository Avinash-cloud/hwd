<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tax Invoice - {{ $membership->resolved_invoice_number }} - Haridwar Bliss</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-card { border: none !important; box-shadow: none !important; max-width: 100% !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans p-4 sm:p-8 antialiased">

    <!-- Top Action Bar -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ auth()->user()->is_admin ? route('admin.memberships.index') : route('dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold rounded-lg shadow-xs transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print / Save as PDF</span>
            </button>
        </div>
    </div>

    <!-- Main Invoice Document -->
    <div class="invoice-card max-w-3xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-10 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-6 border-b border-slate-200">
            <div class="flex items-start gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Blu Bliss" class="w-14 h-14 rounded-xl object-cover ring-1 ring-amber-200 shrink-0 mt-1">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-serif font-bold italic text-xl text-slate-900 leading-tight drop-shadow-[1.5px_1.5px_0px_#00E5FF]">Blu Bliss</h1>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-amber-100 text-amber-900 font-bold uppercase tracking-wider">Devotee Membership</span>
                    </div>
                    <p class="text-xs text-slate-900 font-bold mt-0.5">HARIDWAR BLISS FOOD & BEVERAGES PRIVATE LIMITED</p>
                    <p class="text-[11px] text-slate-600 font-mono mt-0.5">CIN: <strong class="text-slate-800">U11040UT2025PTC019429</strong></p>
                    <p class="text-[11px] text-slate-500 leading-tight mt-1">
                        G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand - 249403<br>
                        Phone: <span class="font-mono">+91 9891289446</span> • Email: <span class="font-mono">sdenterprisesghaziabad@gmail.com</span><br>
                        GSTIN: 05AABCH1234F1Z5 • State Code: 05 (Uttarakhand)
                    </p>
                </div>
            </div>

            <div class="sm:text-right shrink-0">
                <span class="inline-block px-3 py-1 bg-amber-50 text-[#D97706] border border-amber-200 text-xs font-bold rounded-full uppercase tracking-wider mb-2">
                    Tax Invoice
                </span>
                <div class="text-xs text-slate-600 space-y-0.5">
                    <p><strong class="text-slate-900">Invoice No:</strong> <span class="font-mono text-slate-800">{{ $membership->resolved_invoice_number }}</span></p>
                    <p><strong class="text-slate-900">Date:</strong> {{ $membership->created_at->format('d M Y') }}</p>
                    <p><strong class="text-slate-900">Status:</strong> <span class="text-emerald-700 font-bold uppercase">{{ $membership->status }}</span></p>
                </div>
            </div>
        </div>

        <!-- Mandatory Legal & Product Designation Banner -->
        <div class="py-2.5 px-4 bg-amber-50/90 border border-amber-200/90 rounded-xl flex flex-wrap items-center justify-between gap-2 text-xs">
            <div>
                <span class="text-slate-500 text-[11px]">Billed Offering:</span>
                <strong class="text-amber-950 font-bold block text-sm tracking-wide">Pure Haridwar Gangajal (Packaged) - 5-Year Membership</strong>
            </div>
            <div class="text-right text-[11px] text-slate-600">
                <span>CIN: <strong class="font-mono text-slate-800">U11040UT2025PTC019429</strong></span><br>
                <span>SAC Code: <strong class="font-mono text-slate-800">9995</strong> (18% GST Included)</span>
            </div>
        </div>

        <!-- Bill To & Sanctity Validity Period -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="bg-slate-50/70 border border-slate-200 rounded-xl p-4">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Billed To (Devotee Member)</span>
                <p class="font-bold text-sm text-slate-900">{{ $membership->user->name }}</p>
                <p class="text-slate-600 mt-1">{{ $membership->user->email }}</p>
                @if ($membership->user->phone)
                    <p class="text-slate-600">{{ $membership->user->phone }}</p>
                @endif
                <p class="text-[11px] text-slate-400 mt-2 font-mono">Member ID: HB-USR-{{ str_pad((string) $membership->user->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="bg-amber-50/50 border border-amber-200 rounded-xl p-4">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 block mb-2">Membership Entitlement</span>
                <p class="font-bold text-sm text-slate-900">5-Year Sacred Devotee Circle</p>
                <div class="mt-2 space-y-1 text-slate-600">
                    <p><strong>Starts At:</strong> {{ $membership->starts_at->format('d M Y') }}</p>
                    <p><strong>Expires At:</strong> {{ $membership->expires_at->format('d M Y') }} (60 Months)</p>
                    <p class="text-amber-800 text-[11px] mt-1 font-medium">✓ Includes 1 complimentary monthly Gangajal bottle quota</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700 uppercase text-[10px] tracking-wider font-bold">
                    <tr>
                        <th class="p-3">Description</th>
                        <th class="p-3 text-center">SAC Code</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Taxable Value</th>
                        <th class="p-3 text-right">GST Rate</th>
                        <th class="p-3 text-right">GST Amount</th>
                        <th class="p-3 text-right">Total (INR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="p-3 font-medium text-slate-900">
                            Haridwar Bliss 5-Year Sacred Membership
                            <span class="block text-[11px] text-slate-500">60-Month Sacred Brahmakund Gangajal & Vedic Devotee Circle Privileges</span>
                        </td>
                        <td class="p-3 text-center font-mono text-slate-600">9995</td>
                        <td class="p-3 text-center text-slate-700">1</td>
                        <td class="p-3 text-right font-mono text-slate-700">₹{{ number_format($taxBreakup['taxable'], 2) }}</td>
                        <td class="p-3 text-right font-mono text-slate-700">{{ number_format($taxBreakup['rate'], 1) }}%</td>
                        <td class="p-3 text-right font-mono text-slate-700">₹{{ number_format($taxBreakup['gst'], 2) }}</td>
                        <td class="p-3 text-right font-mono font-bold text-slate-900">₹{{ number_format($taxBreakup['inclusive'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tax Breakdown Summary -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 pt-2">
            <div class="text-xs text-slate-500 space-y-2 max-w-sm">
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                    <p class="font-bold text-slate-800 mb-1">Payment Method: <span class="uppercase text-[#D97706]">{{ $membership->payment_method ?? 'Razorpay' }}</span></p>
                    <p class="font-mono text-[11px]">Transaction Ref: {{ $membership->payment_id ?? 'N/A' }}</p>
                    <p class="text-[10px] text-slate-400 mt-1">Paid on {{ $membership->created_at->format('d M Y, h:i A') }}</p>
                </div>

                <div class="p-2.5 text-[11px] text-slate-500">
                    <p><strong>Terms & Conditions Audit:</strong></p>
                    <p>Accepted electronically on {{ $membership->terms_accepted_at ? $membership->terms_accepted_at->format('d M Y H:i:s') : $membership->created_at->format('d M Y H:i:s') }}</p>
                    <p class="font-mono text-[10px]">Audit IP: {{ $membership->terms_ip_address ?? '127.0.0.1' }} • Version: {{ $membership->terms_version ?? '1.0' }}</p>
                </div>
            </div>

            <div class="w-full sm:w-72 bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs space-y-2">
                <div class="flex justify-between text-slate-600">
                    <span>Taxable Base Value:</span>
                    <span class="font-mono">₹{{ number_format($taxBreakup['taxable'], 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>CGST (9.0%):</span>
                    <span class="font-mono">₹{{ number_format($taxBreakup['cgst'], 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>SGST (9.0%):</span>
                    <span class="font-mono">₹{{ number_format($taxBreakup['sgst'], 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-700 font-semibold pt-1 border-t border-slate-200">
                    <span>Total Tax (18%):</span>
                    <span class="font-mono">₹{{ number_format($taxBreakup['gst'], 2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-slate-900 pt-2 border-t border-slate-300">
                    <span>Total Amount Paid:</span>
                    <span class="font-mono text-[#D97706]">₹{{ number_format($taxBreakup['inclusive'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer & Sign-off -->
        <div class="pt-6 border-t border-slate-200 text-[10px] text-slate-500 text-center space-y-1 leading-relaxed">
            <p class="font-bold text-slate-800 text-xs">Pure Haridwar Gangajal (Packaged)</p>
            <p>Issued by <strong>HARIDWAR BLISS FOOD & BEVERAGES PRIVATE LIMITED</strong> · CIN: <strong class="font-mono text-slate-700">U11040UT2025PTC019429</strong></p>
            <p>Registered Address: G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Shivalik Nagar, Hardwar, Haridwar- 249403, Uttarakhand</p>
            <p>Devotee Helpline: +91 9891289446 · Official Email: sdenterprisesghaziabad@gmail.com</p>
            <p class="text-slate-400 pt-1">This is a computer-generated tax invoice and requires no physical signature. Har Har Gange!</p>
        </div>
    </div>

</body>
</html>

