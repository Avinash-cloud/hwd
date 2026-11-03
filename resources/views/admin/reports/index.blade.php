<x-admin-layout title="Reports & Analytics">
    <x-slot name="header">Reports</x-slot>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-900">Reports & GST Analytics</h1>
        <p class="text-xs text-slate-500 mt-0.5">Financial summaries, order exports, subscription logs, and GST compliance records with product vs delivery breakdown.</p>
    </div>

    <!-- Financial KPIs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">Total Received from Users</span>
            <span class="text-2xl font-semibold text-slate-900 font-mono mt-1.5 block">₹{{ number_format($totalReceived, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block">{{ $salesCount }} paid orders recorded</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">Product Collections</span>
            <span class="text-2xl font-semibold text-slate-900 font-mono mt-1.5 block">₹{{ number_format($totalProductGross, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block font-mono">Taxable: ₹{{ number_format($productTaxable, 2) }} (GST: ₹{{ number_format($productGst, 2) }})</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">Delivery Fee Collections</span>
            <span class="text-2xl font-semibold text-slate-900 font-mono mt-1.5 block">₹{{ number_format($totalDeliveryGross, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block font-mono">Taxable: ₹{{ number_format($deliveryTaxable, 2) }} (GST: ₹{{ number_format($deliveryGst, 2) }})</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">Total 18% GST Aggregate</span>
            <span class="text-2xl font-semibold text-amber-700 font-mono mt-1.5 block">₹{{ number_format($totalGstAll, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block">CGST + SGST liabilities</span>
        </div>
    </div>

    <!-- On-Screen Itemized GST Breakdown Table -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs mb-8 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">GST Compliance Breakdown (Product vs Delivery vs Membership)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Granular tax distribution compliant with Indian statutory GST filing guidelines (18% Slab).</p>
            </div>
            <a href="{{ route('admin.reports.export', 'gst') }}"
               class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export GST CSV</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Revenue Stream</th>
                        <th class="py-3 px-4">Gross Collected (INR)</th>
                        <th class="py-3 px-4">Taxable Value (INR)</th>
                        <th class="py-3 px-4">CGST @ 9% (INR)</th>
                        <th class="py-3 px-4">SGST @ 9% (INR)</th>
                        <th class="py-3 px-4">Total Output Tax (INR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono">
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-4 font-sans font-medium text-slate-900">
                            Consecrated Holy Products
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">
                            ₹{{ number_format($totalProductGross, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-700">
                            ₹{{ number_format($productTaxable, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            ₹{{ number_format(round($productGst / 2, 2), 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            ₹{{ number_format(round($productGst / 2, 2), 2) }}
                        </td>
                        <td class="py-3 px-4 font-bold text-amber-700">
                            ₹{{ number_format($productGst, 2) }}
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-4 font-sans font-medium text-slate-900">
                            Shiprocket Delivery & Shipping
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">
                            ₹{{ number_format($totalDeliveryGross, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-700">
                            ₹{{ number_format($deliveryTaxable, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            ₹{{ number_format(round($deliveryGst / 2, 2), 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            ₹{{ number_format(round($deliveryGst / 2, 2), 2) }}
                        </td>
                        <td class="py-3 px-4 font-bold text-amber-700">
                            ₹{{ number_format($deliveryGst, 2) }}
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-4 font-sans font-medium text-slate-900">
                            5-Year Devotee Memberships ({{ $membershipCount }} Active)
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">
                            ₹{{ number_format($membershipTotal, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-700">
                            ₹{{ number_format($membershipTaxable, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            ₹{{ number_format(round($membershipGst / 2, 2), 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            ₹{{ number_format(round($membershipGst / 2, 2), 2) }}
                        </td>
                        <td class="py-3 px-4 font-bold text-amber-700">
                            ₹{{ number_format($membershipGst, 2) }}
                        </td>
                    </tr>

                    <tr class="bg-amber-50/40 font-bold border-t-2 border-slate-300">
                        <td class="py-3.5 px-4 font-sans text-slate-900">
                            Combined Aggregate Total
                        </td>
                        <td class="py-3.5 px-4 text-slate-900">
                            ₹{{ number_format($totalReceived + $membershipTotal, 2) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-900">
                            ₹{{ number_format($productTaxable + $deliveryTaxable + $membershipTaxable, 2) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-900">
                            ₹{{ number_format(round($totalGstAll / 2, 2), 2) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-900">
                            ₹{{ number_format(round($totalGstAll / 2, 2), 2) }}
                        </td>
                        <td class="py-3.5 px-4 text-amber-800 text-sm">
                            ₹{{ number_format($totalGstAll, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Downloadable Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-700">
        <!-- Sales Report Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between space-y-4 shadow-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    Order Logs
                </span>
                <h2 class="text-sm font-semibold text-slate-900 mt-3">Full Sales & Order Report</h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    Itemized sales transactions including devotee contact, items, member discounts, promotional coupons, shipping, and payment status.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'sales') }}"
               class="w-full text-center py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition inline-flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export Sales CSV</span>
            </a>
        </div>

        <!-- Membership Report Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between space-y-4 shadow-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    Subscriptions
                </span>
                <h2 class="text-sm font-semibold text-slate-900 mt-3">5-Year Membership Report</h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    Active devotee subscriber roster with activation timestamps, 5-year expiry dates, contact details, and payment statuses.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'memberships') }}"
               class="w-full text-center py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition inline-flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export Memberships CSV</span>
            </a>
        </div>

        <!-- GST Report Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between space-y-4 shadow-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    Tax Accounting
                </span>
                <h2 class="text-sm font-semibold text-slate-900 mt-3">Itemized GST Audit Summary</h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    Detailed CSV export breaking down Product Gross/Taxable, Delivery Fee Gross/Taxable, CGST, SGST, IGST, and Total Received.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'gst') }}"
               class="w-full text-center py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs transition inline-flex items-center justify-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-amber-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export GST Breakdown CSV</span>
            </a>
        </div>
    </div>
</x-admin-layout>
