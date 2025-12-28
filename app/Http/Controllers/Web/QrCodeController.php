<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Display the QR code selection form.
     */
    public function index()
    {
        $devices = Device::with('capabilities', 'shedDevices.shed')
            ->orderBy('serial_no')
            ->get();

        return view('admin.devices.qr-code', compact('devices'));
    }

    /**
     * Generate and display QR codes for printing.
     */
    public function print(Request $request)
    {
        $request->validate([
            'devices' => 'required|array',
            'devices.*' => 'exists:devices,id',
            'label_size' => 'required|in:small,medium,large',
            'include_details' => 'nullable|boolean',
        ]);

        $devices = Device::with('capabilities', 'shedDevices.shed')
            ->whereIn('id', $request->devices)
            ->orderBy('serial_no')
            ->get();

        $labelSize = $request->label_size;
        $includeDetails = $request->boolean('include_details');

        return view('admin.devices.qr-code-print', compact(
            'devices',
            'labelSize',
            'includeDetails'
        ));
    }

    /**
     * Generate and download a single QR code image.
     */
    public function download(Device $device)
    {
        $qrCode = \QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($device->serial_no);

        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }
}
