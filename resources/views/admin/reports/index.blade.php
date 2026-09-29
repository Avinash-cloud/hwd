<x-admin-layout title="Reports & GST Analytics">
    <x-slot name="header">
        Reports & Data Export Center
    </x-slot>

    <!-- Financial KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">Paid Product Orders</span>
            <span class="text-2xl font-serif font-extrabold text-white mt-2 block">₹{{ number_format($totalSales, 2) }}</span>
            <span class="text-[11px] text-slate-500">{{ $salesCount }} orders recorded</span>
        </div>

        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">5-Year Membership Fees</span>
            <span class="text-2xl font-serif font-extrabold text-[#D97706] mt-2 block">₹{{ number_format($membershipTotal, 2) }}</span>
            <span class="text-[11px] text-slate-500">{{ $membershipCount }} active subscribers</span>
        </div>

        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">Estimated 18% GST Aggregate</span>
            <span class="text-2xl font-serif font-extrabold text-[#0369A1] mt-2 block">₹{{ number_format($gstEstimated, 2) }}</span>
            <span class="text-[11px] text-slate-500">CGST + SGST (Exportable below)</span>
        </div>
    </div>

    <!-- Downloadable Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-xs text-slate-300">
        <!-- Sales Report Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] uppercase font-mono font-bold text-[#D97706]">Order Logs</span>
                <h3 class="font-serif font-bold text-base text-white mt-1">Full Sales & Order Report</h3>
                <p class="text-slate-400 text-[11px] mt-1 leading-relaxed">
                    Item-by-item sales log including customer contact, item totals, member discounts, shipping rates, and payment channels.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'sales') }}" class="w-full text-center py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 transition">
                Download CSV Export ↓
            </a>
        </div>

        <!-- Membership Report Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] uppercase font-mono font-bold text-[#D97706]">Subscription Data</span>
                <h3 class="font-serif font-bold text-base text-white mt-1">5-Year Membership Report</h3>
                <p class="text-slate-400 text-[11px] mt-1 leading-relaxed">
                    Active subscriber roster with activation timestamps, 5-year expiry deadlines, contact phone numbers, and payment tokens.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'memberships') }}" class="w-full text-center py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 transition">
                Download CSV Export ↓
            </a>
        </div>

        <!-- GST Report Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between space-y-4">
            <div>
                <span class="text-[10px] uppercase font-mono font-bold text-[#D97706]">Tax Accounting</span>
                <h3 class="font-serif font-bold text-base text-white mt-1">GST Transaction Summary</h3>
                <p class="text-slate-400 text-[11px] mt-1 leading-relaxed">
                    Breakdown of taxable turnover, IGST, and CGST/SGST tax liabilities compliant with Indian GST reporting requirements.
                </p>
            </div>
            <a href="{{ route('admin.reports.export', 'gst') }}" class="w-full text-center py-2.5 bg-[#D97706]/20 hover:bg-[#D97706]/30 text-[#D97706] font-bold rounded-xl border border-[#D97706]/40 transition">
                Download GST Summary CSV ↓
            </a>
        </div>
    </div>
</x-admin-layout>
