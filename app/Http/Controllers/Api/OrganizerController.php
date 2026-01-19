<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizerController extends Controller
{
    /**
     * Display a listing of organizers
     */
    public function index(Request $request)
    {
        $query = Organizer::with(['user', 'approvedBy']);

        // Filter by approval status
        if ($request->has('status')) {
            $query->where('approval_status', $request->status);
        }

        // Search by organization name
        if ($request->has('search')) {
            $query->where('organization_name', 'like', '%' . $request->search . '%');
        }

        $organizers = $query->latest()->paginate(15);

        return view('organizers.index', compact('organizers'));
    }

    /**
     * Show the form for creating a new organizer
     */
    public function create()
    {
        return view('organizers.create');
    }

    /**
     * Store a newly created organizer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $organizer = Organizer::create([
            'user_id' => Auth::id(),
            'organization_name' => $validated['organization_name'],
            'description' => $validated['description'] ?? null,
            'approval_status' => 'pending',
        ]);

        return redirect()
            ->route('organizers.show', $organizer)
            ->with('success', 'Organizer application submitted successfully. Awaiting approval.');
    }

    /**
     * Display the specified organizer
     */
    public function show(Organizer $organizer)
    {
        $organizer->load(['user', 'approvedBy', 'events']);

        return view('organizers.show', compact('organizer'));
    }

    /**
     * Show the form for editing the organizer
     */
    public function edit(Organizer $organizer)
    {
        // Only allow the organizer owner to edit
        if ($organizer->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('organizers.edit', compact('organizer'));
    }

    /**
     * Update the specified organizer
     */
    public function update(Request $request, Organizer $organizer)
    {
        // Only allow the organizer owner to update
        if ($organizer->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $organizer->update($validated);

        return redirect()
            ->route('organizers.show', $organizer)
            ->with('success', 'Organizer updated successfully.');
    }

    /**
     * Remove the specified organizer
     */
    public function destroy(Organizer $organizer)
    {
        // Only allow the organizer owner or admin to delete
        if ($organizer->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $organizer->delete();

        return redirect()
            ->route('organizers.index')
            ->with('success', 'Organizer deleted successfully.');
    }

    /**
     * Approve an organizer (Admin only)
     */
    public function approve(Organizer $organizer)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $organizer->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Organizer approved successfully.');
    }

    /**
     * Reject an organizer (Admin only)
     */
    public function reject(Organizer $organizer)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $organizer->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Organizer rejected.');
    }

    /**
     * Suspend an organizer (Admin only)
     */
    public function suspend(Organizer $organizer)
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $organizer->update([
            'approval_status' => 'suspended',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Organizer suspended.');
    }

    /**
     * Get organizers pending approval (Admin only)
     */
    public function pending()
    {
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $organizers = Organizer::with(['user'])
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(15);

        return view('organizers.pending', compact('organizers'));
    }

    /**
     * Get current user's organizer profile
     */
    public function myProfile()
    {
        $organizer = Organizer::with(['events'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$organizer) {
            return redirect()
                ->route('organizers.create')
                ->with('info', 'You need to create an organizer profile first.');
        }

        return view('organizers.my-profile', compact('organizer'));
    }
}