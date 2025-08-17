<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Farm;
use App\Models\Flock;
use App\Models\Shed;
use Illuminate\Http\Request;

class FlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sheds = Shed::with(['farm.owner', 'latestFlock'])->get();
        $breeds = Breed::all();
        $farms = Farm::all();
        $types = [];

        return view(
            'admin.flocks.index',
            [
                'sheds' => $sheds,
                'breeds' => $breeds,
                'farms' => $farms,
                'types' => $types,
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
            'name' => 'required|string|max:255',
            'shed_id' => 'required|integer|exists:sheds,id',
            'breed_id' => 'required|integer|exists:breeds,id',
            'chicken_count' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        Flock::create($validated);

        return redirect()->back()
            ->with('success', 'Flock has been added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Flock $flock)
    {
        // Ensure dates formatted for <input type="date">
        return response()->json([
            'id' => $flock->id,
            'name' => $flock->name,
            'shed_id' => $flock->shed_id,
            'breed_id' => $flock->breed_id,
            'chicken_count' => $flock->chicken_count,
            'start_date' => optional($flock->start_date)->format('Y-m-d'),
            'end_date' => optional($flock->end_date)->format('Y-m-d'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flock $flock)
    {
        // No implementation is required
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flock $flock)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shed_id' => 'required|integer|exists:sheds,id',
            'breed_id' => 'required|integer|exists:breeds,id',
            'chicken_count' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $flock->update($validated);

        return redirect()->back()
            ->with('success', 'Flock has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flock $flock)
    {
        $flock->load('productionLogs');
        if ($flock->productionLogs->count() == 0) {
            $flock->delete();
            return redirect()->back()
                ->with('success', 'Flock has been deleted successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'Flock cannot be deleted because it has been used in production.');
        }
    }
}
