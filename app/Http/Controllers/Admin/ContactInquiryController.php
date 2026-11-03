<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactInquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactInquiry::latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(15)->withQueryString();
        $unreadCount = ContactInquiry::where('status', 'unread')->count();

        return view('admin.contact-inquiries.index', compact('inquiries', 'unreadCount'));
    }

    public function show(ContactInquiry $inquiry): View
    {
        if ($inquiry->status === 'unread') {
            $inquiry->update(['status' => 'read']);
        }

        return view('admin.contact-inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, ContactInquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        if ($validated['status'] === 'replied' && ! $inquiry->replied_at) {
            $validated['replied_at'] = now();
        }

        $inquiry->update($validated);

        return back()->with('success', 'Inquiry status updated successfully.');
    }

    public function destroy(ContactInquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.contact-inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}
