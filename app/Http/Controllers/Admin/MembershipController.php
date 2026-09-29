<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(Request $request): View
    {
        $query = Membership::with('user')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $memberships = $query->paginate(20)->withQueryString();

        return view('admin.memberships.index', compact('memberships'));
    }

    public function toggleStatus(Membership $membership): RedirectResponse
    {
        $newStatus = $membership->status === 'active' ? 'cancelled' : 'active';
        $membership->update(['status' => $newStatus]);

        return back()->with('success', "Membership for {$membership->user->name} marked as {$newStatus}.");
    }
}
