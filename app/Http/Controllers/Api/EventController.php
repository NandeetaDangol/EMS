<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'event_type' => 'nullable|in:concert,conference,sports,theater,other',
            'search' => 'nullable|string|max:255',
        ]);

        $events = Event::with(['venue', 'category', 'organizer.user', 'eventTickets'])
            ->where('status', 'published')
            ->where('booking_end', '>', now())
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->event_type, fn($q) => $q->where('event_type', $request->event_type))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderBy('start_datetime')
            ->paginate(12);

        return response()->json(['success' => true, 'data' => $events]);
    }

    public function show($id)
    {
        $event = Event::with([
            'venue.venueSeats',
            'category',
            'organizer.user',
            'eventTickets' => fn($q) => $q->where('is_active', true)->where('quantity_available', '>', 0),
            'eventMedia'
        ])->where('status', 'published')->findOrFail($id);

        return response()->json(['success' => true, 'data' => $event]);
    }
}
