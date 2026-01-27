<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\VenueSeat;
use App\Enums\SeatType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VenueSeatController extends Controller
{
    /**
     * Display a listing of seats for a specific venue.
     */
    public function index(Request $request, $venueId)
    {
        $venue = Venue::findOrFail($venueId);

        $seats = VenueSeat::where('venue_id', $venueId)
            ->when($request->section, fn($q) => $q->where('section', $request->section))
            ->when($request->seat_type, fn($q) => $q->where('seat_type', $request->seat_type))
            ->orderBy('section')
            ->orderBy('row')
            ->orderBy('seat_number')
            ->paginate(50);

        return response()->json([
            'venue' => $venue,
            'seats' => $seats
        ]);
    }

    /**
     * Store a newly created seat in storage.
     */
    public function store(Request $request, $venueId)
    {
        $venue = Venue::findOrFail($venueId);

        $validated = $request->validate([
            'section' => 'required|string|max:50',
            'row' => 'required|string|max:20',
            'seat_number' => 'required|string|max:10',
            'seat_type' => ['required', Rule::in(SeatType::values())],
        ]);

        $validated['venue_id'] = $venueId;

        // Check for duplicate seat
        $exists = VenueSeat::where('venue_id', $venueId)
            ->where('section', $validated['section'])
            ->where('row', $validated['row'])
            ->where('seat_number', $validated['seat_number'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A seat with this section, row, and number already exists.'
            ], 422);
        }

        $seat = VenueSeat::create($validated);

        return response()->json([
            'message' => 'Seat created successfully',
            'seat' => $seat
        ], 201);
    }

    /**
     * Display the specified seat.
     */
    public function show($venueId, $seatId)
    {
        $seat = VenueSeat::where('venue_id', $venueId)
            ->findOrFail($seatId);

        return response()->json($seat);
    }

    /**
     * Update the specified seat in storage.
     */
    public function update(Request $request, $venueId, $seatId)
    {
        $seat = VenueSeat::where('venue_id', $venueId)
            ->findOrFail($seatId);

        $validated = $request->validate([
            'section' => 'sometimes|string|max:50',
            'row' => 'sometimes|string|max:20',
            'seat_number' => 'sometimes|string|max:10',
            'seat_type' => ['sometimes', Rule::in(SeatType::values())],
        ]);

        // Check for duplicate if seat identifying fields are being updated
        if ($request->hasAny(['section', 'row', 'seat_number'])) {
            $exists = VenueSeat::where('venue_id', $venueId)
                ->where('section', $request->input('section', $seat->section))
                ->where('row', $request->input('row', $seat->row))
                ->where('seat_number', $request->input('seat_number', $seat->seat_number))
                ->where('id', '!=', $seatId)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'A seat with this section, row, and number already exists.'
                ], 422);
            }
        }

        $seat->update($validated);

        return response()->json([
            'message' => 'Seat updated successfully',
            'seat' => $seat->fresh()
        ]);
    }

    /**
     * Remove the specified seat from storage.
     */
    public function destroy($venueId, $seatId)
    {
        $seat = VenueSeat::where('venue_id', $venueId)
            ->findOrFail($seatId);

        // Check if seat has any bookings
        if ($seat->bookingTickets()->exists()) {
            return response()->json([
                'message' => 'Cannot delete seat with existing bookings.'
            ], 422);
        }

        $seat->delete();

        return response()->json([
            'message' => 'Seat deleted successfully'
        ]);
    }

    // /**
    //  * Bulk create seats for a venue.
    //  */
    // public function bulkStore(Request $request, $venueId)
    // {
    //     $venue = Venue::findOrFail($venueId);

    //     $validated = $request->validate([
    //         'seats' => 'required|array|min:1',
    //         'seats.*.section' => 'required|string|max:50',
    //         'seats.*.row' => 'required|string|max:20',
    //         'seats.*.seat_number' => 'required|string|max:10',
    //         'seats.*.seat_type' => ['required', Rule::in(SeatType::values())],
    //     ]);

    //     $createdSeats = [];
    //     $errors = [];

    //     foreach ($validated['seats'] as $index => $seatData) {
    //         $seatData['venue_id'] = $venueId;

    //         // Check for duplicates
    //         $exists = VenueSeat::where('venue_id', $venueId)
    //             ->where('section', $seatData['section'])
    //             ->where('row', $seatData['row'])
    //             ->where('seat_number', $seatData['seat_number'])
    //             ->exists();

    //         if ($exists) {
    //             $errors[] = "Seat at index {$index} already exists: {$seatData['section']}-{$seatData['row']}-{$seatData['seat_number']}";
    //             continue;
    //         }

    //         $createdSeats[] = VenueSeat::create($seatData);
    //     }

    //     return response()->json([
    //         'message' => count($createdSeats) . ' seats created successfully',
    //         'created' => count($createdSeats),
    //         'errors' => $errors,
    //         'seats' => $createdSeats
    //     ], count($errors) > 0 ? 207 : 201);
    // }
}
