<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'event_type' => 'nullable|in:concert,conference,sports,theater,other',
            'search' => 'nullable|string|max:255',
            'city' => 'nullable|string',
        ]);

        $events = Event::with(['venue', 'category', 'organizer.user', 'eventTickets'])
            ->where('status', 'published')
            ->where('booking_end', '>', now())
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->event_type, fn($q) => $q->where('event_type', $request->event_type))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->city, fn($q) => $q->whereHas('venue', fn($vq) => $vq->where('city', $request->city)))
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

    public function categories()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['events' => fn($q) => $q->where('status', 'published')])
            ->get();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function venues()
    {
        $venues = Venue::where('is_active', true)->get();
        return response()->json(['success' => true, 'data' => $venues]);
    }

    public function featuredEvents()
    {
        $events = Event::with(['venue', 'category', 'organizer.user', 'eventTickets'])
            ->where('status', 'published')
            ->where('booking_end', '>', now())
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->limit(6)
            ->get();

        return response()->json(['success' => true, 'data' => $events]);
    }

    public function myEvents()
    {
        $user = Auth::user();
        if ($user->user_type !== 'organizer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $events = Event::with(['venue', 'category', 'eventTickets'])
            ->where('organizer_id', $user->organizer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $events]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type !== 'organizer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        if ($user->organizer->approval_status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Your organizer account is not approved'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'venue_id' => 'required|exists:venues,id',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'booking_start' => 'required|date',
            'booking_end' => 'required|date|after:booking_start|before:start_datetime',
            'event_type' => 'required|in:concert,conference,sports,theater,other',
            'banner_image' => 'nullable|string',
            'custom_fields' => 'nullable|array',
        ]);

        $event = Event::create([
            ...$validated,
            'organizer_id' => $user->organizer->id,
            'status' => 'draft',
        ]);

        return response()->json(['success' => true, 'data' => $event, 'message' => 'Event created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->user_type !== 'organizer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $event = Event::where('organizer_id', $user->organizer->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'venue_id' => 'sometimes|exists:venues,id',
            'start_datetime' => 'sometimes|date|after:now',
            'end_datetime' => 'sometimes|date|after:start_datetime',
            'booking_start' => 'sometimes|date',
            'booking_end' => 'sometimes|date|after:booking_start',
            'event_type' => 'sometimes|in:concert,conference,sports,theater,other',
            'banner_image' => 'nullable|string',
            'custom_fields' => 'nullable|array',
        ]);

        $event->update($validated);
        return response()->json(['success' => true, 'data' => $event, 'message' => 'Event updated successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->user_type !== 'organizer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $event = Event::where('organizer_id', $user->organizer->id)->findOrFail($id);
        $validated = $request->validate(['status' => 'required|in:draft,published,cancelled']);
        $event->update(['status' => $validated['status']]);

        return response()->json(['success' => true, 'data' => $event, 'message' => 'Event status updated successfully']);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->user_type !== 'organizer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $event = Event::where('organizer_id', $user->organizer->id)->findOrFail($id);

        if ($event->bookings()->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete event with existing bookings'], 400);
        }

        $event->delete();
        return response()->json(['success' => true, 'message' => 'Event deleted successfully']);
    }

    public function statistics($id)
    {
        $user = Auth::user();
        if ($user->user_type !== 'organizer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $event = Event::with(['eventTickets', 'bookings'])
            ->where('organizer_id', $user->organizer->id)
            ->findOrFail($id);

        $stats = [
            'total_tickets' => $event->eventTickets->sum('quantity_total'),
            'tickets_sold' => $event->eventTickets->sum('quantity_sold'),
            'tickets_available' => $event->eventTickets->sum('quantity_available'),
            'total_revenue' => $event->bookings()->where('status', 'confirmed')->sum('total_amount'),
            'total_bookings' => $event->bookings()->count(),
            'confirmed_bookings' => $event->bookings()->where('status', 'confirmed')->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }
}
