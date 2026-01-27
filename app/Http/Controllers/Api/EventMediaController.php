<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventMedia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EventMediaController extends Controller
{
    public function index(Request $request, $eventId): JsonResponse
    {
        $event = Event::findOrFail($eventId);

        $query = EventMedia::where('event_id', $eventId);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('file_url', 'like', "%{$search}%");
        }

        $media = $query->orderBy('uploaded_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'event' => $event,
            'media' => $media
        ]);
    }

    public function store(Request $request, $eventId): JsonResponse
    {
        $event = Event::findOrFail($eventId);

        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png|max:51200',
        ]);

        $file = $request->file('file');
        $path = $file->store('event_media', 'public');

        $media = EventMedia::create([
            'event_id' => $eventId,
            'file_url' => $path,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Media uploaded successfully',
            'data' => $media
        ], 201);
    }

    public function show($eventId, $mediaId): JsonResponse
    {
        $media = EventMedia::where('event_id', $eventId)
            ->with('event')
            ->findOrFail($mediaId);

        return response()->json([
            'data' => $media
        ]);
    }

    public function destroy($eventId, $mediaId): JsonResponse
    {
        $media = EventMedia::where('event_id', $eventId)
            ->findOrFail($mediaId);

        if (Storage::disk('public')->exists($media->file_url)) {
            Storage::disk('public')->delete($media->file_url);
        }

        $media->delete();

        return response()->json([
            'message' => 'Media deleted successfully'
        ]);
    }
}
