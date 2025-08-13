<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;

class DailyReportsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $farms = Farm::all();
        return view(
            'admin.reports.daily-reports',
            compact('farms')
        );
    }
}
