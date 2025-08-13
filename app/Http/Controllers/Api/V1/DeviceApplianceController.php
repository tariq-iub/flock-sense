<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Resources\DeviceApplianceResource;
use App\Models\DeviceAppliance;
use App\Models\Device;
use App\Models\Shed;
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
        return response()
            ->json(['message' => 'Appliance deleted successfully']);
    }

    /**
     * Fetch appliances by shed ID
     */
    public function fetchByShed($shedId)
    {
        $shed = Shed::findOrFail($shedId);
        $appliances = DeviceAppliance::whereIn('device_id', $shed->devices()
            ->pluck('devices.id'))
            ->get();
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
     * Update status for a specific appliance by key
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'device_serial' => 'required|string',
            'appliance_key' => 'required|string',
            'status' => 'required|boolean',
            'metrics' => 'nullable|array',
        ]);

        // Get device by serial number
        $device = Device::where('serial_no', $validated['device_serial'])
            ->firstOrFail();

        // Find appliance by device ID and key
        $appliance = DeviceAppliance::where('device_id', $device->id)
            ->where('key', $validated['appliance_key'])
            ->first();

        if (!$appliance) {
            // Create new appliance with default type based on key
            $type = $this->getApplianceTypeFromKey($validated['appliance_key']);
            $appliance = DeviceAppliance::create([
                'device_id' => $device->id,
                'key' => $validated['appliance_key'],
                'type' => $type,
                'name' => ucfirst($type) . ' ' . strtoupper($validated['appliance_key']),
                'status' => $validated['status'],
                'status_updated_at' => now()
            ]);
        } else {
            // Update existing appliance
            $appliance->updateStatus(
                $validated['status'],
                $validated['metrics'] ?? null
            );
        }

        return new DeviceApplianceResource($appliance);
    }

    /**
     * Update multiple appliance statuses at once using keys
     */
    public function updateAllStatuses(Request $request)
    {
        $validated = $request->validate([
            'device_serial' => 'required|string',
            'appliances' => 'required|array',
            'appliances.*' => 'required|boolean', // key => status mapping
        ]);

        // Get device by serial number
        $device = Device::where('serial_no', $validated['device_serial'])->firstOrFail();

        $updatedAppliances = [];

        foreach ($validated['appliances'] as $key => $status) {
            // Find existing appliance or create new one
            $appliance = DeviceAppliance::where('device_id', $device->id)
                ->where('key', $key)
                ->first();

            if (!$appliance) {
                // Create new appliance with default type based on key
                $type = $this->getApplianceTypeFromKey($key);
                $appliance = DeviceAppliance::create([
                    'device_id' => $device->id,
                    'key' => $key,
                    'type' => $type,
                    'name' => ucfirst($type) . ' ' . strtoupper($key),
                    'status' => $status,
                    'status_updated_at' => now()
                ]);
            } else {
                // Update existing appliance
                $appliance->updateStatus($status);
            }

            $updatedAppliances[] = $appliance;
        }

        return response()->json([
            'message' => 'Statuses updated successfully',
        ]);
    }

    /**
     * Helper method to determine appliance type from key
     */
    private function getApplianceTypeFromKey(string $key): string
    {
        $firstChar = strtolower(substr($key, 0, 1));

        return match ($firstChar) {
            'f' => 'fan',
            'b' => 'brooder',
            'c' => 'cooling_pad',
            'l' => 'light',
            'e' => 'exhaust',
            'h' => 'heater',
            default => 'appliance'
        };
    }

    /**
     * Get all appliance statuses
     */
    public function getAllStatuses()
    {
        $appliances = DeviceAppliance::all();
        return response()->json([
            'data' => DeviceApplianceResource::collection($appliances)
        ]);
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
