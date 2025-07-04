<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Farm::query();

        if ($request->has('owner_id')) {
            $query->where('owner_id', $request->owner_id);

            return FarmResource::collection(
                $query->with(['owner', 'sheds'])->get()
            );
        }

        return FarmResource::collection(Farm::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'owner_id' => ['required', 'exists:users,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $farm = Farm::create($validated);

        return response()->json([
            'message' => 'Farm created successfully.',
            'farm' => FarmResource::make($farm),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Farm $farm)
    {
        return FarmResource::make($farm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'name' => ['string'],
            'address' => ['string'],
            'owner_id' => ['exists:users,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $farm->update($validated);

        return response()->json([
            'message' => 'Farm updated successfully.',
            'farm' => FarmResource::make($farm),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farm $farm)
    {
        $farm->delete();

        return response()->json([
            'message' => 'Farm deleted successfully.',
        ]);
    }
}
