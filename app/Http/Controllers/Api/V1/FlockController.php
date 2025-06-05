<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\FlockResource;
use App\Models\Flock;
use Illuminate\Http\Request;

class FlockController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FlockResource::collection(Flock::with(['shed', 'breed'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shed_id' => ['required', 'exists:sheds,id'],
            'breed_id' => ['required', 'exists:breeds,id'],
            'start_date' => ['required', 'date'],
            'initial_quantity' => ['required', 'integer', 'min:1'],
            'current_quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,sold,completed'],
        ]);

        $flock = Flock::create($validated);

        return response()->json([
            'message' => 'Flock created successfully.',
            'flock' => new FlockResource($flock),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Flock $flock)
    {
        return new FlockResource($flock->load(['shed', 'breed']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flock $flock)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'shed_id' => ['sometimes', 'exists:sheds,id'],
            'breed_id' => ['sometimes', 'exists:breeds,id'],
            'start_date' => ['sometimes', 'date'],
            'initial_quantity' => ['sometimes', 'integer', 'min:1'],
            'current_quantity' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'in:active,sold,completed'],
        ]);

        $flock->update($validated);

        return response()->json([
            'message' => 'Flock updated successfully.',
            'flock' => new FlockResource($flock),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flock $flock)
    {
        $flock->delete();

        return response()->json([
            'message' => 'Flock deleted successfully.',
        ]);
    }
}
