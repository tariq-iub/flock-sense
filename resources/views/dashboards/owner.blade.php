@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1">Welcome, {{ $user->name }}</h3>
                    <p>You are <span class="text-primary fw-bold">Farm Owner</span> | {{ $user->phone }}</p>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh">
                        <i class="ti ti-refresh"></i>
                    </a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse" class="">
                        <i data-feather="chevron-up" class="feather-16"></i>
                    </a>
                </li>
            </ul>
        </div>


        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/shed-icon.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $farm->sheds->count() }}">0</span></h5>
                            <p class="mb-0">Total Sheds</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash1 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/hen-icon.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $data->active_flocks }}">0</span></h5>
                            <p class="mb-0">Active Flocks</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash2 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/dash3.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_birds_current }}">0</span></h5>
                            <div class="mb-0">
                                Total Live Birds
                                <span class="badge bg-soft-info ms-5">{{ $data->avg_livability_pct }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash3 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_mortalities_window }}">0</span></h5>
                            <p class="mb-0">
                                Total Mortalities
                                <span class="badge bg-soft-danger ms-5">{{ $data->mortality_rate_pct }}%</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-8 col-xl-7 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-info fs-16 me-2"><i class="ti ti-info-circle"></i></span>
                            <h5 class="card-title mb-0">Daily Mortality Rate</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <canvas id="mortalityChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-xl-5 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-warning fs-16 me-2"><i class="ti ti-chart-bar"></i></span>
                            <h5 class="card-title mb-0">Weight Gain Trends</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="height: 420px;">
                        <canvas id="adgFeedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-inline-flex">
                            <span class="title-icon bg-soft-primary fs-16 me-2"><i class="ti ti-building"></i></span>
                            <h5 class="card-title mt-2">Environment & IoT Snapshot</h5>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-light btn-sm mt-2">Refresh</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Shed Name</th>
                                    <th class="text-center">Shed Temperature</th>
                                    <th class="text-center">Brooder Temperature</th>
                                    <th class="text-center">Humidity (%)</th>
                                    <th class="text-center">CO<sub>2</sub> (ppm)</th>
                                    <th class="text-center">NH<sub>3</sub> (ppm)</th>
                                    <th class="text-center">Last Reading</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shedEnvironment as $row)
                                    <tr>
                                        <td>{{ $row->shed_name }}</td>
                                        <td class="text-center">{{ $row->shed_temperature_c }}</td>
                                        <td class="text-center">{{ $row->brooder_temperature_c }}</td>
                                        <td class="text-center">{{ $row->humidity_pct }}</td>
                                        <td class="text-center">{{ $row->co2_ppm }}</td>
                                        <td class="text-center">{{ $row->nh3_ppm }}</td>
                                        <td class="text-center">{{ $row->last_reading }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-{{ ($row->status == "OK") ? 'success' : 'danger' }}">{{ $row->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-inline-flex">
                            <span class="title-icon bg-soft-danger fs-16 me-2"><i class="ti ti-haze"></i></span>
                            <h5 class="card-title mt-2">Environment Alerts</h5>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-light btn-sm mt-2">Refresh</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Shed Name</th>
                                    <th>Flock</th>
                                    <th class="text-center">Alert Type</th>
                                    <th class="text-center">Parameter</th>
                                    <th class="text-center">Value</th>
                                    <th class="text-center">Threshold</th>
                                    <th class="text-center">Alert Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($environmentAlerts as $row)
                                    <tr>
                                        <td>{{ $row->shed_name }}</td>
                                        <td>{{ $row->flock_name }}</td>
                                        <td class="text-center">{{ $row->alert_type }}</td>
                                        <td class="text-center">{{ $row->parameter }}</td>
                                        <td class="text-center">{{ $row->avg_value }}</td>
                                        <td class="text-center">{{ $row->threshold }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($row->alert_time)->format('d-m-Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
