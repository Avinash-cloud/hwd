<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function index(): View
    {
        $batches = Batch::withCount(['products', 'orders'])->latest('collection_date')->paginate(15);

        return view('admin.batches.index', compact('batches'));
    }

    public function create(): View
    {
        return view('admin.batches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|unique:batches,batch_number|max:50',
            'sourcing_ghat' => 'required|string|max:255',
            'collection_date' => 'required|date',
            'packaging_date' => 'required|date',
            'description' => 'nullable|string',
            'purity_notes' => 'nullable|string',
            'lab_certificate_path' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'status' => 'required|in:ready,dispatched,archived',
        ]);

        Batch::create($validated);

        return redirect()->route('admin.batches.index')->with('success', 'Sacred Gangajal Batch created.');
    }

    public function edit(Batch $batch): View
    {
        return view('admin.batches.edit', compact('batch'));
    }

    public function update(Request $request, Batch $batch): RedirectResponse
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:50|unique:batches,batch_number,'.$batch->id,
            'sourcing_ghat' => 'required|string|max:255',
            'collection_date' => 'required|date',
            'packaging_date' => 'required|date',
            'description' => 'nullable|string',
            'purity_notes' => 'nullable|string',
            'lab_certificate_path' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'status' => 'required|in:ready,dispatched,archived',
        ]);

        $batch->update($validated);

        return redirect()->route('admin.batches.index')->with('success', 'Batch details updated successfully.');
    }
}
