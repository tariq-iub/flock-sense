<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use http\Client\Request;

class DailyReportsController extends Controller
{
    public function index()
    {
        $farms = Farm::all();

        return view(
            'admin.reports.daily-reports',
            compact('farms')
        );
    }

    public function getReportCard(Request $request)
    {

    }
}
