@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1">Welcome, {{ $user->name }}</h3>
                    <p>You are <span class="text-primary fw-bold">Farm Manager</span> | {{ $user->phone }}</p>
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
                        <canvas id="adgChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-inline-flex">
                            <span class="title-icon bg-soft-danger fs-16 me-2"><i class="ti ti-table"></i></span>
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
                                @forelse($environment as $row)
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
                                @empty
                                    <span class="text-bg-danger">No data available.</span>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-4 col-xl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-2">Recent Transactions</h5>
                        <a href="purchase-transaction.html" class="btn btn-light btn-sm mb-2">View All</a>
                    </div>
                    <div class="card-body pb-2">
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-02.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Stellar Dynamics</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#12457</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$245</h6>
                                <p class="fs-13">Basic</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-03.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Quantum Nexus</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#65974</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$395</h6>
                                <p class="fs-13">Enterprise</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-05.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Aurora Technologies</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#22457</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$145</h6>
                                <p class="fs-13">Advanced</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-07.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">TerraFusion Energy</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#43412</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$145</h6>
                                <p class="fs-13">Enterprise</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-1">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-08.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Epicurean Delights</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#43567</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$977</h6>
                                <p class="fs-13">Premium</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recent Transactions -->

            <!-- Recently Registered -->
            <div class="col-xxl-4 col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-2">Recently Registered</h5>
                        <a href="purchase-transaction.html" class="btn btn-light btn-sm mb-2">View All</a>
                    </div>
                    <div class="card-body pb-2">
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-11.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Pitch</a></h6>
                                    <p class="fs-13">Basic (Monthly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>150 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-12.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Initech</a></h6>
                                    <p class="fs-13">Enterprise (Yearly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>200 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-13.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Umbrella Corp</a></h6>
                                    <p class="fs-13">Advanced (Monthly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>129 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-14.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Capital Partners</a></h6>
                                    <p class="fs-13">Enterprise (Monthly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>103 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-1">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-15.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Massive Dynamic</a></h6>
                                    <p class="fs-13">Premium (Yearly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>108 Users</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recent Registered -->

            <!-- Recent Plan Expired -->
            <div class="col-xxl-4 col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-2">Recent Plan Expired</h5>
                        <a href="purchase-transaction.html" class="btn btn-light btn-sm mb-2">View All</a>
                    </div>
                    <div class="card-body pb-2">
                        <div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/icons/company-icon-16.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Silicon Corp</a></h6>
                                        <p class="fs-13">Expired : 10 Apr 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/icons/company-icon-14.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Hubspot</a></h6>
                                        <p class="fs-13">Expired : 12 Jun 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/icons/company-icon-18.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Licon Industries</a></h6>
                                        <p class="fs-13">Expired : 16 Jun 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/company/company-07.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">TerraFusion Energy</a></h6>
                                        <p class="fs-13">Expired : 12 May 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-1">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/company/company-08.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Epicurean Delights</a></h6>
                                        <p class="fs-13">Expired : 15 May 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recent Plan Expired -->
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Normalize datasets: object -> array
            const raw = @json($datasets ?? []);
            const datasetsArr = Array.isArray(raw) ? raw : Object.values(raw);

            // Prepare series: ensure numbers + {x,y} parsing disabled
            const prepared = datasetsArr.map((ds, i) => ({
                label: ds.label ?? `Series ${i+1}`,
                data: (Array.isArray(ds.data) ? ds.data : []).map(p => ({
                    x: Number(p.x),
                    y: Number(p.y)
                })),
                parsing: false,
                borderWidth: 2,
                pointRadius: 2,
                tension: 0.2
            }));

            const el = document.getElementById('mortalityChart');
            if (!el) return;

            new Chart(el, {
                type: 'line',
                data: { datasets: prepared },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'nearest', intersect: false },
                    scales: {
                        x: {
                            type: 'linear',               // <-- key change: age is numeric
                            title: { display: true, text: 'Age (days)' },
                            ticks: { precision: 0 }       // whole-day ticks
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Mortality Rate (%)' },
                            ticks: { callback: v => v + '%' }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (c) => `${c.dataset.label}: ${(c.parsed.y ?? 0).toFixed(2)}% (Day ${c.parsed.x})`
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const labels   = @json($adgData['labels'] ?? []);
            const datasets = @json($adgData['datasets'] ?? []);
            console.log(labels);
            console.log(datasets);
            // Prepare datasets for grouped bars
            const prepared = (Array.isArray(datasets) ? datasets : []).map((ds, i) => ({
                type: 'bar',
                label: ds.label ?? `Flock ${i+1}`,
                data: ds.data?.map(v => (v === null ? null : Number(v))) ?? [],
                // Optional styling (kept minimal; Chart.js can auto-color):
                borderWidth: 1,
                // barThickness: 18, // uncomment if you want fixed width bars
            }));

            const el = document.getElementById('adgChart');
            if (!el) return;

            new Chart(el, {
                data: {
                    labels: labels.map(n => Number(n)), // category labels (ages)
                    datasets: prepared
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: false, text: 'Average Daily Gain by Age (g/bird/day)' },
                        tooltip: {
                            callbacks: {
                                title: (items) => `Age: ${items?.[0]?.label} days`,
                                label: (ctx) => {
                                    const v = ctx.parsed.y ?? 0;
                                    return `${ctx.dataset.label}: ${v} g`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'category',     // grouped bars per Age
                            title: { display: true, text: 'Age (days)' },
                            ticks: { precision: 0 }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'ADG (g/bird/day)' }
                        }
                    }
                }
            });
        });
    </script>
@endpush



