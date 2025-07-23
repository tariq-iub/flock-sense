<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Http\Resources\MedicineResource;
use Spatie\QueryBuilder\QueryBuilder;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $medicines = QueryBuilder::for(Medicine::class)
            ->allowedFilters(['code', 'name'])
            ->allowedSorts(['id', 'code', 'name', 'created_at'])
            ->paginate($request->get('per_page', 100))
            ->appends($request->query());

        return MedicineResource::collection($medicines)->additional([
            'type' => 'success',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:medicines,code',
            'name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::create($validated);

        return (new MedicineResource($medicine))
            ->additional(['type' => 'success']);
    }

    public function show(Medicine $medicine)
    {
        return (new MedicineResource($medicine))
            ->additional(['type' => 'success']);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'code' => 'sometimes|required|string|unique:medicines,code,' . $medicine->id,
            'name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $medicine->update($validated);

        return (new MedicineResource($medicine))
            ->additional(['type' => 'success']);
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'Medicine deleted successfully.',
        ]);
    }
}
