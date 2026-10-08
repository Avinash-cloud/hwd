<x-admin-layout title="Reports & Analytics">
    <x-slot name="header">Reports</x-slot>

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-900">Reports & GST Analytics</h1>
        <p class="text-xs text-slate-500 mt-0.5">Financial summaries, order exports, subscription logs, and GST compliance records.</p>
    </div>

    <!-- Financial KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">Total Product Sales</span>
            <span class="text-2xl font-semibold text-slate-900 font-mono mt-1.5 block">₹{{ number_format($totalSales, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block">{{ $salesCount }} orders recorded</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">5-Year Membership Fees</span>
            <span class="text-2xl font-semibold text-slate-900 font-mono mt-1.5 block">₹{{ number_format($membershipTotal, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block">{{ $membershipCount }} active subscribers</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <span class="text-[11px] uppercase font-semibold tracking-wider text-slate-500 block">Estimated 18% GST Aggregate</span>
            <span class="text-2xl font-semibold text-slate-900 font-mono mt-1.5 block">₹{{ number_format($gstEstimated, 2) }}</span>
            <span class="text-xs text-slate-500 mt-1 block">CGST + SGST liabilities</span>
        </div>
    </div>

    <!-- Downloadable Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-700">
        <!-- Sales Report Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    Order Logs
                </span>
                <h2 class="text-sm font-semibold text-slate-900 mt-3">Full Sales & Order Report</h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    Itemized sales transactions including devotee contact, line items, member discounts, shipping charges, and payment references.
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
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between space-y-4">
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
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                    Tax Accounting
                </span>
                <h2 class="text-sm font-semibold text-slate-900 mt-3">GST Transaction Summary</h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    Breakdown of taxable turnover, IGST, and CGST/SGST tax liabilities compliant with Indian GST reporting requirements.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'gst') }}"
               class="w-full text-center py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs transition inline-flex items-center justify-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-amber-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export GST Summary CSV</span>
            </a>
        </div>
    </div>
</x-admin-layout>
