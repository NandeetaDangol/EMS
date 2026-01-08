<?php

namespace App\Http\Controllers;

use App\Models\EventTicket;
use Illuminate\Http\Request;

class EventTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = EventTicket::with('event');

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->boolean('available_only')) {
            $query->where('quantity_available', '>', 0)->where('is_active', true);
        }

        return response()->json($query->paginate(15));
    }

    public function show($id)
    {
        return response()->json(EventTicket::with('event')->findOrFail($id));
    }

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

        $validated['quantity_sold'] = $validated['quantity_sold'] ?? 0;
        $validated['quantity_available'] = $validated['quantity_available'] ?? ($validated['quantity_total'] - $validated['quantity_sold']);

        if ($validated['quantity_sold'] + $validated['quantity_available'] != $validated['quantity_total']) {
            return response()->json(['message' => 'Quantity mismatch'], 422);
        }

        $ticket = EventTicket::create($validated);

        return response()->json(['message' => 'Ticket created', 'ticket' => $ticket->load('event')], 201);
    }

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

        $total = $validated['quantity_total'] ?? $ticket->quantity_total;
        $sold = $validated['quantity_sold'] ?? $ticket->quantity_sold;
        $available = $validated['quantity_available'] ?? $ticket->quantity_available;

        if ($sold + $available != $total) {
            return response()->json(['message' => 'Quantity mismatch'], 422);
        }

        $ticket->update($validated);

        return response()->json(['message' => 'Ticket updated', 'ticket' => $ticket->fresh('event')]);
    }

    public function destroy($id)
    {
        $ticket = EventTicket::findOrFail($id);

        if ($ticket->quantity_sold > 0) {
            return response()->json(['message' => 'Cannot delete ticket with sales'], 422);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted']);
    }
}
