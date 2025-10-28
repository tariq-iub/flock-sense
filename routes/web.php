<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BreedController;
use App\Http\Controllers\Web\ChartController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\DailyReportsController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ExpenseController;
use App\Http\Controllers\Web\FarmController;
use App\Http\Controllers\Web\FeedController;
use App\Http\Controllers\Web\FlockController;
use App\Http\Controllers\Web\IotController;
use App\Http\Controllers\Web\LogsController;
use App\Http\Controllers\Web\MapController;
use App\Http\Controllers\Web\MedicineController;
use App\Http\Controllers\Web\PartnerController;
use App\Http\Controllers\Web\PricingController;
use App\Http\Controllers\Web\ProductionLogController;
use App\Http\Controllers\Web\ReportsController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\ShedController;
use App\Http\Controllers\Web\ShortcutController;
use App\Models\Flock;
use App\Models\Shed;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', function () {
    return view('frontend.index');
});

Route::get('/features', function () {
    return view('frontend.features');
})->name('features');

Route::get('/partners', function () {
    return view('frontend.partners');
})->name('partners');

Route::get('/blogs', function () {
    return view('frontend.blog');
})->name('blogs');

Route::get('/events', function () {
    return view('frontend.events');
})->name('events');

Route::get('/pricing', function () {
    return view('frontend.pricing');
})->name('pricing');

Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

// Login and forget-password routes (outside admin group)
Route::get('/login', function () {
    return view('frontend.login');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', function () {
    return view('frontend.register');
});

// Force Reset Password
Route::get('/required-reset/{user}', function ($user) {
    return view('auth.force-reset-password', compact('user'));
})->name('required.reset');

Route::put('/force-reset/{user}', [AuthController::class, 'forceReset'])->name('force.reset');

Route::get('/forget-password', function () {
    return view('frontend.forgot');
});
Route::post('/forget-password', [AuthController::class, 'forgotPassword'])->name('forget');

// Password reset routes
Route::get('/reset-password/{token}', function ($token) {
    $email = request('email');

    return view('frontend.reset', compact('token', 'email'));
})->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verification');
})->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
//    ->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin|owner|manager']], function () {

    // Logout route (POST)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Productions Logs
    Route::prefix('productions')->controller(ProductionLogController::class)->group(function () {
        Route::get('/', 'index')->name('productions.index');
        Route::get('/export/excel', 'exportExcel')->name('productions.export.excel');
    });

    Route::get('/iot/logs', [LogsController::class, 'deviceLogs'])->name('iot.logs');
    Route::get('/iot/export/excel', [LogsController::class, 'exportExcel'])->name('iot.export.excel');

    // Daily Reports
    Route::get('/daily-reports', [DailyReportsController::class, 'index'])->name('daily.reports');
    Route::get('/daily-report-card/{version}', [DailyReportsController::class, 'getReportCard'])->name('daily.report.card');

    // Settings
    Route::get('/setting/personal', [SettingController::class, 'personal'])->name('setting.personal');

    Route::get('/get-sheds', function (\Illuminate\Http\Request $request) {
        return Shed::where('farm_id', $request->farm_id)
            ->select('id', 'name', 'capacity', 'type')
            ->orderBy('name')
            ->get();
    });

    Route::get('/get-flocks', function (\Illuminate\Http\Request $request) {
        return Flock::where('shed_id', $request->shed_id)
            ->select('id', 'name', 'start_date', 'end_date')
            ->orderBy('start_date', 'desc')
            ->get();
    });

    Route::get('/get-devices', function (\Illuminate\Http\Request $request) {
        $shed = Shed::findOrFail($request->shed_id);

        return $shed->devices()
            ->wherePivot('is_active', true)
            ->get();
    });

    // Client
    Route::prefix('clients')->controller(ClientController::class)->group(function () {
        Route::get('/{user}', 'show')->name('clients.show');
        Route::put('/{user}/update-password', 'updatePassword')->name('user.update-password');
    });
    // Reports
    Route::prefix('reports')->controller(ReportsController::class)->group(function () {
        Route::get('/income', 'income')->name('reports.income');
        Route::get('/expenses', 'expenses')->name('reports.expenses');
        Route::get('/tax', 'tax')->name('reports.tax');
        Route::get('/devices-sold', 'devices_sales')->name('reports.devices.sales');
        Route::get('/annual', 'annual')->name('reports.annual');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin-shortcuts', [ShortcutController::class, 'getAdminShortcuts']);
    Route::get('/user-shortcuts', [ShortcutController::class, 'getUserShortcuts']);
    Route::get('/my-shortcuts', [ShortcutController::class, 'getUserPersonalizedShortcuts']);
    Route::get('/shortcuts/{group}', [ShortcutController::class, 'getShortcutsByGroup']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    // Register routes (GET and POST)
    //    Route::get('/register', [AuthController::class, 'register'])->name('register');
    //    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/iot/alerts', [LogsController::class, 'alerts'])->name('iot.alerts');
    Route::get('/iot/events-data/{id}', [LogsController::class, 'events_data'])->name('iot.events.data');
    Route::get('/devices/map', [MapController::class, 'showDeviceMap'])->name('devices.map');

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::get('/public', [SettingController::class, 'publicSettings']);
        Route::get('/general', [SettingController::class, 'general'])->name('setting.general');
        Route::get('/company', [SettingController::class, 'companySettings']);
        Route::get('/social', [SettingController::class, 'socialSettings']);
        Route::get('/contact', [SettingController::class, 'contactSettings']);
        Route::get('/{group}', [SettingController::class, 'byGroup']);
        Route::get('/{group}/{key}', [SettingController::class, 'show']);
        Route::post('/', [SettingController::class, 'store']);
        Route::put('/bulk', [SettingController::class, 'bulkUpdate']);
        Route::put('/{group}/{key}', [SettingController::class, 'update']);
        Route::delete('/{group}/{key}', [SettingController::class, 'destroy']);
    });

    // Resource routes for clients and charts
    Route::resources([
        'shortcuts' => ShortcutController::class,
        'breeding' => BreedController::class,
        'feeds' => FeedController::class,
        'pricing-plans' => PricingController::class,
        'partners' => PartnerController::class,
    ]);

    // Partner Routes
    Route::prefix('partners')->controller(PartnerController::class)->group(function () {
        Route::post('/{partner}/add-keyword', 'addKeyword');
        Route::post('/{partner}/remove-keyword', 'removeKeyword');
        Route::get('partners-keywords', 'getAllKeywords');
    });


    // Users and Clients
    Route::prefix('clients')->controller(ClientController::class)->group(function () {
        Route::get('/', 'index')->name('clients.index');
        Route::post('/', 'store')->name('clients.store');
        Route::get('/{user}/edit', 'edit')->name('clients.edit');
        Route::put('/{user}', 'update')->name('clients.update');
        Route::delete('/{user}', 'destroy')->name('clients.destroy');
    });

    // Farms
    Route::prefix('farms')->controller(FarmController::class)->group(function () {
        Route::get('/', 'index')->name('admin.farms.index');
        Route::post('/', 'store')->name('admin.farms.store');
        Route::get('/{farm}', 'show')->name('admin.farms.show');
        Route::put('/{farm}', 'update')->name('admin.farms.update');
        Route::delete('/{farm}', 'destroy')->name('admin.farms.destroy');
        Route::get('/{farm}/data', 'farmData')->name('farms.data');
        Route::put('/{farm}/assign-manager', 'assignManager');
    });

    // Sheds
    Route::prefix('sheds')->controller(ShedController::class)->group(function () {
        Route::get('/', 'index')->name('admin.sheds.index');
        Route::post('/', 'store')->name('admin.sheds.store');
        Route::get('/{shed}', 'show')->name('admin.sheds.show');
        Route::put('/{shed}', 'update')->name('admin.sheds.update');
        Route::delete('/{shed}', 'destroy')->name('admin.sheds.destroy');
        Route::get('/{shed}/data', 'shedData')->name('sheds.data');
    });

    // Flocks
    Route::prefix('flocks')->controller(FlockController::class)->group(function () {
        Route::get('/', 'index')->name('admin.flocks.index');
        Route::post('/', 'store')->name('admin.flocks.store');
        Route::get('/{flock}', 'show')->name('admin.flocks.show');
        Route::put('/{flock}', 'update')->name('admin.flocks.update');
        Route::delete('/{flock}', 'destroy')->name('admin.flocks.destroy');
    });

    // Medicines
    Route::prefix('medicines')->controller(MedicineController::class)->group(function () {
        Route::get('/', 'index')->name('admin.medicines.index');
        Route::post('/', 'store')->name('admin.medicines.store');
        Route::put('/{user}', 'update')->name('admin.medicines.update');
        Route::delete('/{user}', 'destroy')->name('admin.medicines.destroy');
    });

    // Charts (Standard) Data Routes
    Route::prefix('charts')->controller(ChartController::class)->group(function () {
        Route::get('/', 'index')->name('charts.index');
        Route::get('/{chart}/edit', 'edit')->name('charts.edit');
        Route::put('/{chart}', 'update')->name('charts.update');
        Route::delete('/{chart}', 'destroy')->name('charts.destroy');
        Route::post('/import', 'import')->name('charts.import');
        Route::get('/data/{chart}', 'chartData')->name('charts.data');
        Route::get('/{chart}/toggle', 'toggle')->name('charts.toggle');
        Route::post('/data/update', 'data_update')->name('charts.data.update');

    });

    // Roles
    Route::prefix('roles')->controller(RoleController::class)->group(function () {
        Route::get('/', 'index')->name('roles.index');
        Route::post('/', 'store')->name('roles.store');
        Route::put('/{role}', 'update')->name('roles.update');
        Route::delete('/{role}', 'destroy')->name('roles.destroy');
        Route::get('/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
        Route::post('/permissions', [RoleController::class, 'setPermissions'])->name('roles.set-permissions');
        Route::get('/{role}/users', [RoleController::class, 'attachedUsers'])->name('roles.users');
    });

    // IoT
    Route::prefix('iot')->controller(IotController::class)->group(function () {
        Route::get('/farm-devices', 'farmDevices')->name('farm.devices');
        Route::post('/farm-devices/link', 'link')->name('farm.devices.link');
        Route::post('/farm-devices/delink', 'delink')->name('farm.devices.delink');

        Route::get('/', 'index')->name('iot.index');
        Route::get('/create', 'create')->name('iot.create');
        Route::post('/', 'store')->name('iot.store');
        Route::get('/{device}', 'show')->name('iot.show');
        Route::get('/{device}/edit', 'edit')->name('iot.edit');
        Route::put('/{device}', 'update')->name('iot.update');
        Route::delete('/{device}', 'destroy')->name('iot.destroy');
        Route::get('/devices/{device}/appliances', 'fetchAppliances');
    });

    // Expenses
    Route::prefix('expenses')->controller(ExpenseController::class)->group(function () {
        Route::get('/', 'index')->name('expenses.index');
        Route::post('/', 'store')->name('expenses.store');
        Route::get('/{expense}', 'show')->name('expenses.show');
        Route::put('/{expense}', 'update')->name('expenses.update');
        Route::delete('/{expense}', 'destroy')->name('expenses.destroy');
        Route::get('/{expense}/toggle', 'toggle')->name('expenses.toggle');
    });

    // Productions Logs
    Route::prefix('productions')->controller(ProductionLogController::class)->group(function () {
        Route::get('/create', 'create')->name('productions.create');
        Route::post('/', 'store')->name('productions.store');
        Route::get('/{productionLog}', 'show')->name('productions.show');
        Route::get('/{productionLog}/edit', 'edit')->name('productions.edit');
        Route::put('/{productionLog}', 'update')->name('productions.update');
        Route::delete('/{productionLog}', 'destroy')->name('productions.destroy');
    });
});
