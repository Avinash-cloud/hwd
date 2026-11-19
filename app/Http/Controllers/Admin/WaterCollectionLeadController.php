<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaterCollectionLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class WaterCollectionLeadController extends Controller
{
    /**
     * Display a listing of Kumbh water collection leads.
     */
    public function index(Request $request): View
    {
        if (! WaterCollectionLead::hasTable()) {
            $leads = new LengthAwarePaginator([], 0, 15);
            $counts = [
                'total' => 0,
                'pending' => 0,
                'contacted' => 0,
                'scheduled' => 0,
                'collected' => 0,
                'dispatched' => 0,
                'delivered' => 0,
            ];

            return view('admin.water-leads.index', compact('leads', 'counts'));
        }

        $query = WaterCollectionLead::with('package')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('lead_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('hotel_or_dharamshala', 'like', "%{$search}%")
                    ->orWhere('delivery_address', 'like', "%{$search}%")
                    ->orWhere('delivery_city', 'like', "%{$search}%")
                    ->orWhere('delivery_state', 'like', "%{$search}%")
                    ->orWhere('delivery_pincode', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(15)->withQueryString();

        $counts = [
            'total' => WaterCollectionLead::count(),
            'pending' => WaterCollectionLead::where('status', 'pending')->count(),
            'contacted' => WaterCollectionLead::where('status', 'contacted')->count(),
            'scheduled' => WaterCollectionLead::where('status', 'scheduled')->count(),
            'collected' => WaterCollectionLead::where('status', 'collected')->count(),
            'dispatched' => WaterCollectionLead::where('status', 'dispatched')->count(),
            'delivered' => WaterCollectionLead::where('status', 'delivered')->count(),
        ];

        return view('admin.water-leads.index', compact('leads', 'counts'));
    }

    /**
     * Display the specified lead.
     */
    public function show(WaterCollectionLead $lead): View
    {
        $lead->load('package');

        return view('admin.water-leads.show', compact('lead'));
    }

    /**
     * Update the lead's status, notes, or pickup schedule.
     */
    public function update(Request $request, WaterCollectionLead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,contacted,scheduled,collected,dispatched,delivered,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:3000'],
            'pickup_date' => ['nullable', 'date'],
        ]);

        $lead->update($validated);

        return back()->with('success', "Lead #{$lead->lead_number} updated successfully.");
    }

    /**
     * Delete the specified lead.
     */
    public function destroy(WaterCollectionLead $lead): RedirectResponse
    {
        $leadNumber = $lead->lead_number;
        $lead->delete();

        return redirect()->route('admin.water-collection-leads.index')
            ->with('success', "Lead #{$leadNumber} deleted successfully.");
    }
}
