<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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
            return view('dashboards.admin');
        } elseif ($user->hasRole('owner')) {
            return view('dashboards.owner');
        } elseif ($user->hasRole('manager')) {
            return view('dashboards.manager');
        }

        return abort(403, 'Unauthorized access: No appropriate role found.');
    }
}
