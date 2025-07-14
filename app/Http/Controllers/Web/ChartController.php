<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Imports\ChartDataImport;
use App\Models\Chart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $charts = Chart::with('data')->get();
        $sources = $charts->pluck('source');
        return view(
            'admin.charts.index',
            compact('charts', 'sources')
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
        // No implementation is required
    }

    /**
     * Display the specified resource.
     */
    public function show(Chart $chart)
    {
        // No implementation is required
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chart $chart)
    {
        $chart->load('data');
        return view('admin.charts.edit', compact('chart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chart $chart)
    {
        $validated = $request->validate([
            'chart_name' => 'required|string',
            'source' => 'required|string',
            'description' => 'nullable|string',
            'unit' => 'nullable|string',
            'settings' => 'nullable|string',
        ]);

        Chart::update([
            'chart_name' => $validated['chart_name'],
            'source' => $validated['source'],
            'description' => $validated['description'],
            'unit' => $validated['unit'],
            'settings' => $validated['settings'],
        ]);

        return redirect()
            ->route('charts.index')
            ->with('success', 'Baseline charts updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chart $chart)
    {
        $chart->delete();
        return redirect()
            ->route('charts.index')
            ->with('success', 'Baseline chart deleted successfully.');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'chart_name' => 'required|string',
            'source' => 'required|string',
            'description' => 'nullable|string',
            'unit' => 'nullable|string',
            'settings' => 'nullable|string',
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // Create or find your Chart entry
        $chart = Chart::firstOrCreate([
            'chart_name' => $validated['chart_name'],
            'source' => $validated['source'],
        ], [
            'description' => $validated['description'],
            'unit' => $validated['unit'],
            'settings' => $validated['settings'],
        ]);

        // Import Excel file
        $file = $request->file('file');
        Excel::import(new ChartDataImport($chart->id), $file);

        return back()->with('success', 'Baseline data imported successfully.');
    }

    public function chartData(Chart $chart) : JsonResponse
    {
        $chart->load('data');
        $view = view('admin.charts.data', compact('chart'))->render();
        return response()->json([
            'title' => $chart->chart_name . ' - ' . $chart->source,
            'data' => $view,
        ]);
    }
}
