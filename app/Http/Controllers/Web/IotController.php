<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use App\Models\Connectivity;
use App\Models\Device;
use App\Models\Shed;
use App\Models\ShedDevice;
use App\Models\DeviceEvent;
use App\Services\DeviceEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::with('capabilities')
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'admin.devices.index',
            compact('devices')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $capabilities = Capability::all();
        $connectivities = Connectivity::all();

        return view(
            'admin.devices.create',
            [
                'device' => null,
                'capabilities' => $capabilities,
                'connectivities' => $connectivities
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_no' => 'required|string|unique:devices,serial_no',
            'model_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'firmware_version' => 'nullable|string',
            'connectivity_type' => 'required|string',
            'battery_operated' => 'boolean',
            'capabilities' => 'array',
        ]);

        $device = Device::create([
            'serial_no' => $validated['serial_no'],
            'model_number' => $validated['model_number'],
            'manufacturer' => $validated['manufacturer'],
            'firmware_version' => $validated['firmware_version'],
            'connectivity_type' => $validated['connectivity_type'],
            'battery_operated' => $validated['battery_operated']
        ]);

        $device->capabilities()->syncWithoutDetaching($validated['capabilities']);

        return redirect()->route('iot.index')
            ->with('success', 'Device has been added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        $capabilities = Capability::all();
        $connectivities = Connectivity::all();
        $device->load(['capabilities', 'appliances', 'readings', 'events']);

        return view(
            'admin.devices.show',
            [
                'device' => $device,
                'capabilities' => $capabilities,
                'connectivities' => $connectivities
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        $capabilities = Capability::all();
        $connectivities = Connectivity::all();
        $device->load(['appliances', 'readings', 'events']);

        return view(
            'admin.devices.create',
            [
                'device' => $device,
                'capabilities' => $capabilities,
                'connectivities' => $connectivities
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'serial_no' => 'required|string',
            'model_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'firmware_version' => 'nullable|string',
            'connectivity_type' => 'required|string',
            'capabilities' => 'required|array',
            'battery_operated' => 'boolean',
        ]);

        $validated['capabilities'] = json_encode($validated['capabilities']);
        $device->update($validated);

        return redirect()->route('iot.index')
            ->with('success', 'Device has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('iot.index')
            ->with('success', 'Device has been deleted successfully.');
    }

    public function farmDevices()
    {
        $devices = Device::all();
        $sheds = Shed::with('farm')->get();
        $availableDevices = Device::whereDoesntHave('shedDevices', fn($q) => $q->where('is_active', true))->get();
        $linkedDevices = Device::whereHas('shedDevices', fn($q) => $q->where('is_active', true))->get();

        return view(
            'admin.devices.farm_devices',
            compact('devices', 'sheds', 'availableDevices', 'linkedDevices')
        );
    }

    /**
     * Link a device to a shed.
     */
    public function link(Request $request)
    {
        $request->validate([
            'shed_id' => 'required|exists:sheds,id',
            'device_id' => 'required|exists:devices,id',
            'location_in_shed' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($request) {
            $shedDevice = ShedDevice::updateOrCreate([
                'shed_id' => $request->shed_id,
                'device_id' => $request->device_id,
            ], [
                'location_in_shed' => $request->location_in_shed,
                'is_active' => true,
            ]);

            app(DeviceEventService::class)->logEvent(
                $request->device_id,
                'linked',
                [
                    'shed_id'  => $request->shed_id,
                    'location' => $request->location_in_shed,
                ],
                'info',
                now()
            );

            return redirect()->back()
                ->with('success', "Device has been linked with Shed: {$shedDevice->shed->name} successfully.");
        });

        return redirect()->back()
            ->with('error', 'Transaction error: Shed device or event cannot be saved.');
    }

    /**
     * Delink (deactivate) a device from a shed.
     */
    public function delink(Request $request)
    {
        $request->validate([
            'shed_id' => 'required|exists:sheds,id',
            'device_id' => 'required|exists:devices,id',
        ]);

        DB::transaction(function () use ($request) {
            $shedDevice = ShedDevice::where('shed_id', $request->shed_id)
                ->with('shed')
                ->where('device_id', $request->device_id)
                ->where('is_active', true)
                ->latest()
                ->first();

            if ($shedDevice) {
                $shedDevice->update(['is_active' => false]);

                app(DeviceEventService::class)->logEvent(
                    $request->device_id,
                    'delinked',
                    [
                        'shed_id'  => $request->shed_id,
                        'location' => $request->location_in_shed,
                    ],
                    'info',
                    now()
                );
            }

            return redirect()->back()
                ->with('success', "Device has been delinked from Shed: {$shedDevice->shed->name} successfully.");
        });

        return redirect()->back()
            ->with('error', 'Transaction error: Shed device or event cannot be saved.');
    }
}
