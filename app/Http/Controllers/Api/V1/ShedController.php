<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\ShedResource;
use App\Models\Shed;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ShedController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sheds = QueryBuilder::for(Shed::class)
            ->where('farm_id', $request->user()->farms()->pluck('farms.id'))
            ->with('farm')
            ->withCount(['flocks', 'devices'])
            ->allowedFilters(['id', 'name', 'type', 'farm_id'])
            ->allowedIncludes(['farm', 'flocks', 'devices'])
            ->allowedSorts(['id', 'name', 'capacity', 'created_at'])
            ->get();

        return ShedResource::collection($sheds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => ['required', 'exists:farms,id'],
            'name' => ['required', 'string', 'max:255'],
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
        return ShedResource::make(
            Shed::with([
                'farm',
                'flocks.breed',
                'devices' => fn($query) => $query->with('appliances'),
            ])
                ->withCount(['flocks', 'devices'])
                ->findOrFail($shed->id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shed $shed)
    {
        $validated = $request->validate([
            'farm_id' => ['sometimes', 'exists:farms,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'capacity' => ['sometimes', 'integer', 'min:1'],
            'type' => ['sometimes', 'in:default,brooder,layer,broiler,hatchery'],
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
