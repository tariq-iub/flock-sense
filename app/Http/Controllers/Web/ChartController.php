<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Imports\ChartDataImport;
use App\Models\Chart;
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
        return view('admin.charts.index', compact('charts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Chart $chart)
    {
        // $chart->load('data');
        $chart = Chart::with('data')->findOrFail($chart->id);
        return view('charts.show', compact('chart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chart $chart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chart $chart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chart $chart)
    {
        //
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
        $file = $request->file('data');
        Excel::import(new ChartDataImport($chart->id), $file);

        return back()->with('success', 'Excel data imported successfully!');
    }
}
