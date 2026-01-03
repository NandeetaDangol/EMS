<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::all());
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->user_type, ['admin', 'organizer'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event = Event::create([
            'organizer_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'event_type' => $request->event_type,
        ]);
        return response()->json($event, 201);
    }

    public function show($id)
    {
        return response()->json(Event::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if ($request->user()->user_type !== 'admin' && $event->organizer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event->update($request->all());

        return response()->json($event);
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if ($request->user()->user_type !== 'admin' && $event->organizer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted']);
    }
}
