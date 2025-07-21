<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pricings = Pricing::orderBy('sort_order')
            ->get();
        return view('admin.pricings.index', compact('pricings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pricings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'billing_interval' => 'required|in:monthly,yearly,weekly,one_time',
            'trial_period_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Pricing::create($validated);

        return redirect()->route('pricings.index')
            ->with('success', 'Pricing package has been added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pricing $pricing)
    {
        return response()->json($pricing);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pricing $pricing)
    {
        return view('admin.pricings.edit', compact('pricing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pricing $pricing)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'billing_interval' => 'required|in:monthly,yearly,weekly,one_time',
            'trial_period_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $pricing->update($validated);

        return redirect()->route('pricings.index')
            ->with('success', 'Pricing package has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pricing $pricing)
    {
        $pricing->delete();
        return redirect()->route('pricings.index')
            ->with('success', 'Pricing package has been deleted successfully.');
    }
}
