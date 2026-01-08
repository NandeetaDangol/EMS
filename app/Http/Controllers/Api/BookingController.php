<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'event', 'bookingTickets']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'event', 'bookingTickets'])->findOrFail($id);
        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'total_tickets' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:' . implode(',', BookingStatus::values()),
        ]);

        $validated['booking_reference'] = 'BK-' . strtoupper(Str::random(8));
        $validated['status'] = $validated['status'] ?? BookingStatus::PENDING->value;

        $booking = Booking::create($validated);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking->load(['user', 'event'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'total_tickets' => 'sometimes|integer|min:1',
            'subtotal' => 'sometimes|numeric|min:0',
            'total_amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:' . implode(',', BookingStatus::values()),
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking->fresh(['user', 'event'])
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === BookingStatus::CONFIRMED->value) {
            return response()->json([
                'message' => 'Cannot delete confirmed booking'
            ], 422);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
