<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farms = Farm::with(['owner', 'managers', 'sheds', 'province', 'district', 'city'])
            ->get();

        $owners = User::all();

        $provinces = Province::select('id', 'name')
            ->orderBy('name')
            ->get();

        $cities = $farms->pluck('city')->unique();

        return view(
            'admin.farms.index',
            compact('farms', 'provinces', 'owners', 'cities')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No implementation is required
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'nullable|exists:pakistan_provinces,id',
            'district_id' => 'nullable|exists:pakistan_districts,id',
            'city_id' => 'nullable|exists:pakistan_tehsils,id',
            'address' => 'required|string|max:500',
            'owner_id' => 'required|exists:users,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $farm = Farm::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Farm is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Farm $farm)
    {
        return response()
            ->json($farm->load(['owner', 'managers', 'sheds', 'province', 'district', 'city']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Farm $farm)
    {
        // No implementation is required
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'nullable|exists:pakistan_provinces,id',
            'district_id' => 'nullable|exists:pakistan_districts,id',
            'city_id' => 'nullable|exists:pakistan_tehsils,id',
            'address' => 'required|string|max:500',
            'owner_id' => 'required|exists:users,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $farm->update($validated);

        return redirect()
            ->route('admin.farms.index')
            ->with('success', 'Farm has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farm $farm)
    {
        $farm->delete();

        return redirect()
            ->route('admin.farms.index')
            ->with('success', 'Farm has been deleted successfully.');
    }

    public function farmData($farmId)
    {
        $farm = Farm::with('sheds.latestFlocks.breed')
            ->find($farmId);

        $view = view('admin.farms.farm_card', compact('farm'))->render();

        return response()->json(['html' => $view]);
    }
}
