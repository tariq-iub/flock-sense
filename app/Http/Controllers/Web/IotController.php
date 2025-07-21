<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use App\Models\Connectivity;
use App\Models\Device;
use Illuminate\Http\Request;

class IotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::orderBy('created_at', 'desc')
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
            'capabilities' => 'required|array',
            'battery_operated' => 'boolean',
        ]);

        $validated['capabilities'] = json_encode($validated['capabilities']);
        Device::create($validated);

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
        $device->load(['appliances', 'readings', 'events']);

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

    public function linking()
    {

    }

    public function alerts()
    {

    }

    public function logs()
    {

    }
}
