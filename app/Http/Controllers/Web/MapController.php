<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shed;
use App\Models\Device;

class MapController extends Controller
{
    public function showDeviceMap()
    {
        // Get sheds with latest linked devices and status
        $sheds = Shed::with(['farm', 'shedDevices' => function ($query) {
            $query->where('is_active', true)->with('device');
        }])->get();

        return view('admin.devices.map', compact('sheds'));
    }
}
