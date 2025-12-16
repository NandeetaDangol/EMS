<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        
        return response()->json([
            'success' => true,
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->user_type, ['organizer', 'admin'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'event_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event = Event::create([
            'organizer_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'event_type' => $request->event_type,
            'status' => $request->status ?? 'draft',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event' => $event,
        ], 201);
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'event' => $event,
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if ($request->user()->user_type !== 'admin' && $event->organizer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'start_datetime' => 'sometimes|date',
            'end_datetime' => 'sometimes|date',
            'event_type' => 'sometimes|string',
            'status' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $event->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'event' => $event,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if ($request->user()->user_type !== 'admin' && $event->organizer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully',
        ]);
    }
}