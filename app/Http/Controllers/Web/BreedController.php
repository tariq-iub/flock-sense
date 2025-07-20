<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breeds = Breed::withCount('flocks')
            ->orderBy('name')
            ->get();

        $categories = ['broiler', 'layer'];

        return view(
            'admin.breeds.index',
            compact('breeds', 'categories')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No implementation required
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:190|unique:breeds',
            'category' => 'required|string|max:190',
        ]);

        Breed::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
        ]);

        return redirect()
            ->route('breeding.index')
            ->with('success', 'Breed is created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Breed $breed)
    {
        // No implementation is required
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breed $breed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $breedId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:190|unique:breeds',
            'category' => 'required|string|max:190',
        ]);

        $breed = Breed::find($breedId);
        $breed->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
        ]);

        return redirect()
            ->route('breeding.index')
            ->with('success', 'Breed is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($breedId)
    {
        $breed = Breed::findOrFail($breedId);
        $breed->delete();
        return redirect()
            ->route('breeding.index')
            ->with('success', 'Breed is deleted successfully.');
    }
}
