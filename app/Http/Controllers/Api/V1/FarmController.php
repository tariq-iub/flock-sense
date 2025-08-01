<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\FarmResource;
use App\Models\Farm;
use App\Services\DynamoDbService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class FarmController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farms = QueryBuilder::for(Farm::class)
            ->where('owner_id', $request->user()->id)
            ->allowedFilters(['id', 'name'])
            ->allowedIncludes(['sheds'])
            ->allowedSorts(['id', 'name'])
            ->with(['sheds.devices.appliances'])
            ->withCount('sheds')
            ->get();

        $dynamo = app(DynamoDbService::class);

        foreach ($farms as $farm) {
            foreach ($farm->sheds as $shed) {
                foreach ($shed->devices as $device) {
                    $data = $dynamo->getSensorData([$device->id], null, true); // correct argument order
                    $device->latest_sensor_data = !empty($data) ? (object)$data[0] : null;
                }
            }
        }

        return FarmResource::collection($farms);
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
    public function show(Request $request, Farm $farm)
    {
        $includes = explode(',', $request->query('include', ''));

        $farm->load(array_filter([
            in_array('owner', $includes) ? 'owner' : null,
            in_array('sheds', $includes) ? 'sheds' : null,
            in_array('managers', $includes) ? 'managers' : null,
            in_array('staff', $includes) ? 'staff' : null,
        ]))->loadCount('sheds');

        return FarmResource::make($farm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'owner_id' => ['sometimes', 'exists:users,id'],
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

    public function createFarmWithShedAndFlock(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'owner_id' => ['required', 'exists:users,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'shed' => ['required', 'array'],
            'shed.name' => ['required', 'string', 'max:255'],
            'shed.capacity' => ['required', 'integer', 'min:1'],
            'shed.type' => ['required', 'in:default,brooder,layer,broiler,hatchery'],
            'shed.description' => ['nullable', 'string'],
            'shed.device_id' => ['nullable', 'exists:devices,id'],

            'flock' => ['required', 'array'],
            'flock.name' => ['required', 'string', 'max:255'],
            'flock.breed_id' => ['required', 'exists:breeds,id'],
            'flock.start_date' => ['required', 'date'],
            'flock.end_date' => ['nullable', 'date'],
            'flock.chicken_count' => ['required', 'integer', 'min:1'],
        ]);

        \DB::beginTransaction();
        try {
            // Step 1: Create the Farm
            $farm = Farm::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'owner_id' => $validated['owner_id'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            // Step 2: Create the Shed
            $shedData = $validated['shed'];
            $shed = $farm->sheds()->create([
                'name' => $shedData['name'],
                'capacity' => $shedData['capacity'],
                'type' => $shedData['type'],
                'description' => $shedData['description'] ?? null,
            ]);

            // Attach device if provided
            if (isset($shedData['device_id'])) {
                $shed->devices()->attach($shedData['device_id'], [
                    'link_date' => now()
                ]);
            }

            // Step 3: Create the Flock
            $flockData = $validated['flock'];
            $flock = $shed->flocks()->create([
                'name' => $flockData['name'],
                'breed_id' => $flockData['breed_id'],
                'start_date' => $flockData['start_date'],
                'end_date' => $flockData['end_date'] ?? null,
                'chicken_count' => $flockData['chicken_count'],
            ]);

            \DB::commit();

            return response()->json([
                'message' => 'Farm, Shed, and Flock created successfully.',
                'farm' => FarmResource::make($farm),
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while creating resources.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
