<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class VenueController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Venue::query();

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $venues = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($venues);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $venue = Venue::create($validated);

        return response()->json([
            'message' => 'Venue created successfully',
            'data' => $venue
        ], 201);
    }

    public function show(Venue $venue): JsonResponse
    {
        $venue->load(['events', 'venueSeats']);

        return response()->json([
            'data' => $venue
        ]);
    }

    public function update(Request $request, Venue $venue): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $venue->update($validated);

        return response()->json([
            'message' => 'Venue updated successfully',
            'data' => $venue->fresh()
        ]);
    }

    public function destroy(Venue $venue): JsonResponse
    {
        if ($venue->events()->exists()) {
            return response()->json([
                'message' => 'Cannot delete venue with associated events'
            ], 422);
        }

        $venue->delete();

        return response()->json([
            'message' => 'Venue deleted successfully'
        ]);
    }

    public function activate(Venue $venue): JsonResponse
    {
        $venue->update(['is_active' => true]);

        return response()->json([
            'message' => 'Venue activated successfully',
            'data' => $venue->fresh()
        ]);
    }

    public function deactivate(Venue $venue): JsonResponse
    {
        $venue->update(['is_active' => false]);

        return response()->json([
            'message' => 'Venue deactivated successfully',
            'data' => $venue->fresh()
        ]);
    }
}
