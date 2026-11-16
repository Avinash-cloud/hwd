<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkOrderInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BulkOrderInquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = BulkOrderInquiry::with('product')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(15)->withQueryString();
        $pendingCount = BulkOrderInquiry::where('status', 'pending')->count();
        $totalCount = BulkOrderInquiry::count();

        return view('admin.bulk-inquiries.index', compact('inquiries', 'pendingCount', 'totalCount'));
    }

    public function show(BulkOrderInquiry $inquiry): View
    {
        $inquiry->load('product');

        return view('admin.bulk-inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, BulkOrderInquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,contacted,quoted,completed,cancelled',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $inquiry->update($validated);

        return back()->with('success', 'Bulk inquiry updated successfully.');
    }

    public function destroy(BulkOrderInquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.bulk-inquiries.index')->with('success', 'Bulk order inquiry deleted successfully.');
    }
}
