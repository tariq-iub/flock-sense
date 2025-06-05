<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\BreedResource;
use App\Models\Breed;
use Illuminate\Http\Request;

class BreedController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreedResource::collection(Breed::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:layer,broiler,dual-purpose'],
            'hatching_period' => ['nullable', 'integer', 'min:1'],
        ]);

        $breed = Breed::create($validated);

        return response()->json([
            'message' => 'Breed created successfully.',
            'breed' => new BreedResource($breed),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Breed $breed)
    {
        return new BreedResource($breed);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Breed $breed)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'in:layer,broiler,dual-purpose'],
            'hatching_period' => ['nullable', 'integer', 'min:1'],
        ]);

        $breed->update($validated);

        return response()->json([
            'message' => 'Breed updated successfully.',
            'breed' => new BreedResource($breed),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breed $breed)
    {
        $breed->delete();

        return response()->json([
            'message' => 'Breed deleted successfully.',
        ]);
    }
}
