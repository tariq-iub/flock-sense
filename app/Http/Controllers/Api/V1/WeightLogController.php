<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WeightLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WeightLogController extends Controller
{
    /**
     * Return weight log history.
     */
    public function history(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'flock_id' => 'required|exists:flocks,id',
            'range' => 'nullable|in:all,today,week,month',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = WeightLog::query();

        // ✅ Filter by flock if provided
        if ($request->filled('flock_id')) {
            $query->where('flock_id', $request->flock_id);
        }

        // ✅ Date Range filtering
        if ($request->filled('range') && $request->range !== 'all') {
            $now = now();
            switch ($request->range) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
            }
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'count' => $logs->count(),
            'data' => $logs,
        ]);
    }
}
