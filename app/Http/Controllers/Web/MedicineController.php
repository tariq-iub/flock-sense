<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the medicines.
     */
    public function index()
    {
        $medicines = Medicine::orderBy('code')->get();

        return view(
            'admin.medicines.index',
            compact('medicines')
        );
    }

    /**
     * Show the form for creating a new medicine.
     */
    public function create()
    {
        // No code implementation is required
    }

    /**
     * Store a newly created medicine in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:medicines,code|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Medicine::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine has been added successfully.');
    }

    /**
     * Display the specified medicine.
     */
    public function show(Medicine $medicine)
    {
        // No implementation is required
    }

    /**
     * Show the form for editing the specified medicine.
     */
    public function edit(Medicine $medicine)
    {
        // No implementaiton is required
    }

    /**
     * Update the specified medicine in storage.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'code' => 'required|max:255|unique:medicines,code,' . $medicine->id,
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $medicine->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine has been updated successfully.');
    }

    /**
     * Remove the specified medicine from storage.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')
            ->with('success', 'Medicine has been deleted successfully.');
    }
}
