<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\DeviceApplianceResource;
use App\Models\DeviceAppliance;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceApplianceController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appliances = DeviceAppliance::all();
        return DeviceApplianceResource::collection($appliances);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'type' => 'required|string',
            'name' => 'nullable|string',
            'channel' => 'nullable|integer',
            'config' => 'nullable|array',
            'status' => 'sometimes|boolean',
            'metrics' => 'nullable|array',
        ]);

        // Set status_updated_at if status is provided
        if (isset($validated['status'])) {
            $validated['status_updated_at'] = now();
        }

        $appliance = DeviceAppliance::create($validated);
        return new DeviceApplianceResource($appliance);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeviceAppliance $deviceAppliance)
    {
        return new DeviceApplianceResource($deviceAppliance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeviceAppliance $deviceAppliance)
    {
        $validated = $request->validate([
            'type' => 'sometimes|string',
            'name' => 'nullable|string',
            'channel' => 'nullable|integer',
            'config' => 'nullable|array',
            'status' => 'sometimes|boolean',
            'metrics' => 'nullable|array',
        ]);

        // Set status_updated_at if status is being updated
        if (isset($validated['status'])) {
            $validated['status_updated_at'] = now();
        }

        $deviceAppliance->update($validated);
        return new DeviceApplianceResource($deviceAppliance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeviceAppliance $deviceAppliance)
    {
        $deviceAppliance->delete();
        return response()->json(['message' => 'Appliance deleted successfully']);
    }

    /**
     * Fetch appliances by shed ID
     */
    public function fetchByShed($shedId)
    {
        $shed = Shed::findOrFail($shedId);
        $appliances = DeviceAppliance::whereIn('device_id', $shed->devices()->pluck('devices.id'))->get();
        return DeviceApplianceResource::collection($appliances);
    }

    /**
     * Fetch appliances by device ID
     */
    public function fetchByDevice($deviceSerial)
    {
        $device = Device::where('serial_no', $deviceSerial)->firstOrFail();
        $appliances = DeviceAppliance::where('device_id', $device->id)->get();
        return DeviceApplianceResource::collection($appliances);
    }


    /**
     * Fetch only IDs of appliances by device serial
     */
    public function fetchDeviceApplianceIds($deviceSerial)
    {
        $device = Device::where('serial_no', $deviceSerial)->firstOrFail();
        $applianceIds = DeviceAppliance::where('device_id', $device->id)->pluck('id');
        return response()->json(['data' => $applianceIds]);
    }


    /**
     * Update status for a specific appliance
     */
    public function updateStatus(Request $request, DeviceAppliance $deviceAppliance)
    {
        $validated = $request->validate([
            'status' => 'required|boolean',
            'metrics' => 'nullable|array',
        ]);

        $deviceAppliance->updateStatus(
            $validated['status'],
            $validated['metrics'] ?? null
        );

        return new DeviceApplianceResource($deviceAppliance);
    }

    /**
     * Update multiple appliance statuses at once
     */
    public function updateAllStatuses(Request $request)
    {
        $validated = $request->validate([
            'statuses' => 'required|array',
            'statuses.*.appliance_id' => 'required|exists:device_appliances,id',
            'statuses.*.status' => 'required|boolean',
            'statuses.*.metrics' => 'nullable|array',
        ]);

        foreach ($validated['statuses'] as $item) {
            $appliance = DeviceAppliance::find($item['appliance_id']);
            $appliance->updateStatus(
                $item['status'],
                $item['metrics'] ?? null
            );
        }

        return response()->json(['message' => 'Statuses updated successfully']);
    }

    /**
     * Get all statuses (maintains compatibility with old status controller)
     */
    public function getAllStatuses()
    {
        $appliances = DeviceAppliance::all();
        $statuses = $appliances->map(function ($appliance) {
            return [
                'id' => $appliance->id,
                'device_appliance_id' => $appliance->id,
                'appliance' => $appliance,
                'status' => $appliance->status,
                'metrics' => $appliance->metrics,
                'updated_at' => $appliance->status_updated_at
            ];
        });

        return response()->json(['data' => $statuses]);
    }

    /**
     * Get status info for a specific appliance
     */
    public function getStatus(DeviceAppliance $deviceAppliance)
    {
        return response()->json([
            'data' => [
                'id' => $deviceAppliance->id,
                'device_appliance_id' => $deviceAppliance->id,
                'appliance' => $deviceAppliance,
                'status' => $deviceAppliance->status,
                'metrics' => $deviceAppliance->metrics,
                'updated_at' => $deviceAppliance->status_updated_at
            ]
        ]);
    }
}
