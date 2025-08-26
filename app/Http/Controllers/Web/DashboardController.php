<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ManagerAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(public ManagerAnalyticsService $managerAnalyticsService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthenticated');
        }

        if ($user->hasRole('admin')) {
            return view('dashboards.admin', compact('user'));
        } elseif ($user->hasRole('owner')) {
            return view('dashboards.owner', compact('user'));
        } elseif ($user->hasRole('manager')) {
            $farm = $user->managedFarms()->first();
            $filters['farm_id'] = $farm->id;
            $filters['start_date'] = $user->managedFarms()->pluck('farm_id')->first();
            $filters['end_date'] = $user->managedFarms()->pluck('farm_id')->first();
            return $this->managerAnalyticsService->getAnalyticsData($filters);
            return view(
                'dashboards.manager',
                compact('user')
            );
        }

        return abort(403, 'Unauthorized access: No appropriate role found.');
    }
}
