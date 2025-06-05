<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShedResource;
use App\Models\Shed;
use Illuminate\Http\Request;

class ShedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShedResource::collection(Shed::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'exists:farms,id'],
            'name' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:default,brooder,layer,broiler,hatchery'],
            'description' => ['nullable', 'string'],
        ]);

        $shed = Shed::create($validated);

        return response()->json([
            'message' => 'Shed created successfully.',
            'shed' => ShedResource::make($shed),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shed $shed)
    {
        return ShedResource::make($shed);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shed $shed)
    {
        $validated = $request->validate([
            'farm_id' => ['exists:farms,id'],
            'name' => ['string'],
            'capacity' => ['integer', 'min:1'],
            'type' => ['in:default,brooder,layer,broiler,hatchery'],
            'description' => ['nullable', 'string'],
        ]);

        $shed->update($validated);

        return response()->json([
            'message' => 'Shed updated successfully.',
            'shed' => ShedResource::make($shed),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shed $shed)
    {
        $shed->delete();

        return response()->json([
            'message' => 'Shed deleted successfully.',
        ]);
    }
}
