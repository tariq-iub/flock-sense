<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Imports\ChartDataImport;
use App\Models\Chart;
use App\Models\ChartData;
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

        $chart->update([
            'chart_name' => $validated['chart_name'],
            'source' => $validated['source'],
            'description' => $validated['description'],
            'unit' => $validated['unit'],
            'settings' => $validated['settings'],
        ]);

        return redirect()
            ->route('charts.index')
            ->with('success', "Standard chart: {$chart->chart_name} data has been updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chart $chart)
    {
        $name = $chart->chart_name;
        $chart->delete();
        return redirect()
            ->route('charts.index')
            ->with('success', "Standard chart: {$name} has been deleted successfully.");
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

        return back()->with('success', 'Standard data imported successfully.');
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

    public function toggle(Chart $chart)
    {
        $chart->is_active = !$chart->is_active;
        $chart->save();
        return redirect()->back()->with([
            'success' => "Status of Standard: {$chart->chart_name} has been changed successfully."
        ]);
    }

    public function data_update(Request $request)
    {
        $row = ChartData::find($request->id);
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found'], 404);
        }

        $row->{$request->field} = $request->value;
        $row->save();

        return response()->json(['success' => true]);
    }
}
