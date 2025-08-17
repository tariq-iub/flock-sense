<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Farm;
use App\Models\Shed;
use Illuminate\Http\Request;

class ShedController extends Controller
{
    protected array $types = [
        'default',
        'brooder',
        'layer',
        'broiler',
        'hatchery',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farms = Farm::all();
        $sheds = Shed::with(['farm.owner', 'latestFlock'])->get();

        $cities = $sheds->pluck('farm.city.name')
            ->unique()
            ->values()
            ->toArray();

        return view(
            'admin.sheds.index',
            [
                'sheds' => $sheds,
                'cities' => $cities,
                'farms' => $farms,
                'types' => $this->types,
            ]
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
            'farm_id' => 'required|exists:farms,id',
            'name' => 'required|string|min:3|max:190',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|in:'.implode(',', $this->types),
            'description' => 'nullable|string',
        ]);

        Shed::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Shed has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shed $shed)
    {
        // Return shed as JSON for the edit modal (can add relationships if needed)
        return response()->json($shed);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shed $shed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shed $shed)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:190',
            'farm_id' => 'required|exists:farms,id',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|string|in:'.implode(',', $this->types),
            'description' => 'nullable|string',
        ]);

        $shed->update($validated);

        // Refresh same page
        return redirect()->back()
            ->with('success', 'Shed has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shed $shed)
    {
        $shed->delete();
        return redirect()
            ->route('admin.sheds.index')
            ->with('success', 'Shed has been deleted successfully.');
    }

    public function shedData($shedId)
    {
        $shed = Shed::with('farm.owner', 'latestFlock', 'latestFlocks.breed')
            ->find($shedId);
        $farm = $shed->farm;
        $breeds = Breed::all();
        $types = [
            'default',
            'brooder',
            'layer',
            'broiler',
            'hatchery',
        ];

        $view = view(
            'admin.sheds.shed_card',
            compact('shed', 'farm', 'breeds')
        )->render();

        return response()->json(['html' => $view]);
    }
}
