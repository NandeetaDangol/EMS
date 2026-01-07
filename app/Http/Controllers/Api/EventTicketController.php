<?php

namespace App\Http\Controllers;

use App\Models\EventTicket;
use Illuminate\Http\Request;

class EventTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = EventTicket::with('event');

        // Filter by event if provided
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by availability
        if ($request->has('available_only') && $request->boolean('available_only')) {
            $query->where('quantity_available', '>', 0)
                ->where('is_active', true);
        }

        $tickets = $query->paginate(15);

        return response()->json($tickets);
    }

    /**
     * Display the specified event ticket
     */
    public function show($id)
    {
        $ticket = EventTicket::with('event')->findOrFail($id);

        return response()->json($ticket);
    }

    /**
     * Store a newly created event ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity_total' => 'required|integer|min:1',
            'quantity_sold' => 'nullable|integer|min:0',
            'quantity_available' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sale_end' => 'nullable|date|after:now',
        ]);

        // Calculate quantity_available if not provided
        if (!isset($validated['quantity_available'])) {
            $validated['quantity_available'] = $validated['quantity_total'] - ($validated['quantity_sold'] ?? 0);
        }

        // Ensure quantity_sold is set
        if (!isset($validated['quantity_sold'])) {
            $validated['quantity_sold'] = 0;
        }

        // Validate quantities match
        if ($validated['quantity_sold'] + $validated['quantity_available'] != $validated['quantity_total']) {
            return response()->json([
                'message' => 'Quantity mismatch: sold + available must equal total'
            ], 422);
        }

        $ticket = EventTicket::create($validated);

        return response()->json([
            'message' => 'Event ticket created successfully',
            'ticket' => $ticket->load('event')
        ], 201);
    }

    /**
     * Update the specified event ticket
     */
    public function update(Request $request, $id)
    {
        $ticket = EventTicket::findOrFail($id);

        $validated = $request->validate([
            'event_id' => 'sometimes|exists:events,id',
            'ticket_type' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'quantity_total' => 'sometimes|integer|min:1',
            'quantity_sold' => 'sometimes|integer|min:0',
            'quantity_available' => 'sometimes|integer|min:0',
            'is_active' => 'boolean',
            'sale_end' => 'nullable|date',
        ]);

        // If updating quantities, validate they match
        $quantityTotal = $validated['quantity_total'] ?? $ticket->quantity_total;
        $quantitySold = $validated['quantity_sold'] ?? $ticket->quantity_sold;
        $quantityAvailable = $validated['quantity_available'] ?? $ticket->quantity_available;

        if ($quantitySold + $quantityAvailable != $quantityTotal) {
            return response()->json([
                'message' => 'Quantity mismatch: sold + available must equal total'
            ], 422);
        }

        $ticket->update($validated);

        return response()->json([
            'message' => 'Event ticket updated successfully',
            'ticket' => $ticket->fresh('event')
        ]);
    }

    public function destroy($id)
    {
        $ticket = EventTicket::findOrFail($id);

        // Check if tickets have been sold
        if ($ticket->quantity_sold > 0) {
            return response()->json([
                'message' => 'Cannot delete ticket with existing sales'
            ], 422);
        }

        $ticket->delete();

        return response()->json([
            'message' => 'Event ticket deleted successfully'
        ]);
    }
}
