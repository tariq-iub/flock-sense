<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ManagerAnalyticsService;
use Carbon\Carbon;
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
            $farm = $user->managedFarms()
                ->first()
                ->load('sheds.latestFlock');

            $filters['farm_id'] = $farm->id;
            $flocks = $farm->sheds->pluck('latestFlock')->toArray();

            foreach ($flocks as $flock) {
                if ($flock == null) {
                    continue;
                }
                $filters['start_date'] = Carbon::parse($flock['start_date'])->format('Y-m-d');
                $filters['end_date'] = Carbon::parse($flock['end_date'])->format('Y-m-d');
            }

            $data = $this->managerAnalyticsService->getAnalyticsData($filters)[0];
            $mortality_data = $this->managerAnalyticsService->getMortalityRateData($filters);
            $adgData = $this->managerAnalyticsService->adgData($filters);
            $environment_data = $this->managerAnalyticsService->environmentData($filters);

            return view(
                'dashboards.manager',
                [
                    'user' => $user,
                    'farm' => $farm,
                    'data' => $data,
                    'datasets' => $mortality_data,
                    'adgData' => $adgData,
                    'environment' => $environment_data,
                ]);
        }

        return abort(403, 'Unauthorized access: No appropriate role found.');
    }
}
