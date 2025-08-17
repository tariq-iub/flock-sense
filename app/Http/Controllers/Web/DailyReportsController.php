<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\DailyReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DailyReportsController extends Controller
{
    public function __construct(private DailyReportService $dailyReportService) {}

    public function index()
    {
        $farms = Farm::all();

        return view(
            'admin.reports.daily-reports',
            compact('farms')
        );
    }

    public function getReportCard(Request $request, $version = 'en')
    {
        $request->validate([
            'shed_id' => 'required|integer|exists:sheds,id',
            'date' => 'required|date_format:Y-m-d', // Ensure date format
        ]);

        try {
            $payload = $this->dailyReportService->build(
                (int) $request->input('shed_id'),
                (string) $request->input('date'),
                (string) $version
            );

            $html = view(
                'admin.reports.partials.report-card',
                compact('payload', 'version')
            )->render();

            return response()->json($html, 200);

        } catch (NotFoundHttpException $e) {
            // Preserve your original 404 messages
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
