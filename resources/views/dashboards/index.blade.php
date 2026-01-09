@extends('layouts.app')
@section('title', 'Dashboard - FlockSense Manager')

@section('content')
    <div class="content">
        {{-- Header Section --}}
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1">Welcome, {{ $user->name }}</h2>
                <p>You are privileged as <span class="text-primary fw-bold">Farm Manager</span> | {{ $user->phone }}</p>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh" onclick="location.reload()">
                        <i class="ti ti-refresh"></i>
                    </a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse">
                        <i data-feather="chevron-up" class="feather-16"></i>
                    </a>
                </li>
            </ul>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 1: EXECUTIVE OVERVIEW --}}
        {{-- ============================================ --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2"><i class="ti ti-chart-line"></i></span>
                    <h4 class="mb-0 fw-bold text-primary">Executive Overview</h4>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Total Sheds --}}
            <div class="col-xl-3 col-lg-6 col-sm-6 col-12 d-flex">
                <div class="card dash-widget w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/shed-icon.svg') }}" alt="Sheds"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $farm->sheds->count() }}">0</span></h5>
                            <p class="mb-0">Total Sheds</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Flocks --}}
            @php
                $flock = null;
                if(count($flocks) == 1)
                    $flock = $flocks[0];
            @endphp
            <div class="col-xl-3 col-lg-6 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash1 w-100">
                    <div class="card-body d-flex flex-row justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/hen-icon.svg') }}" alt="Flocks"></span>
                            </div>
                            <div class="dash-widgetcontent d-flex flex-column">
                                <h5 class="mb-1"><span class="counters" data-count="{{ $data->active_flocks }}">0</span></h5>
                                Active Flocks
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-0">
                            @if($flock)
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($flock['start_date'])->format('d, M Y') }}</span>
                                <span class="text-info fs-10">Start Count: {{ number_format($flock['chicken_count']) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Live Birds --}}
            <div class="col-xl-3 col-lg-6 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash2 w-100">
                    <div class="card-body d-flex flex-row justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash3.svg') }}" alt="Live Birds"></span>
                            </div>
                            <div class="dash-widgetcontent d-flex flex-column">
                                <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_birds_current }}">0</span></h5>
                                Total Live Birds
                            </div>
                        </div>
                        <div class="fw-semibold mb-0">
                            <span class="badge bg-soft-success p-3">{{ number_format($data->avg_livability_pct, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Mortalities --}}
            <div class="col-xl-3 col-lg-6 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash3 w-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash4.svg') }}" alt="Mortalities"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_mortalities_window }}">0</span></h5>
                                Total Mortalities
                            </div>
                        </div>
                        <div class="fw-semibold mb-0">
                            <span class="badge bg-soft-danger p-3">{{ number_format($data->mortality_rate_pct, 2) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 2: FLOCK HEALTH & PERFORMANCE --}}
        {{-- ============================================ --}}
        <div class="row mb-3 mt-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2"><i class="ti ti-heart-rate-monitor"></i></span>
                    <h4 class="mb-0 fw-bold text-success">Flock Health & Performance</h4>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Mortality Rate Chart --}}
            <div class="col-xxl-8 col-xl-7 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-danger fs-16 me-2"><i class="ti ti-alert-triangle"></i></span>
                            <h5 class="card-title mb-0">Daily Mortality Rate Trend</h5>
                        </div>
                        <span class="badge bg-soft-danger">Target: &lt; 0.274%</span>
                    </div>
                    <div class="card-body pb-0">
                        <canvas id="mortalityChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            {{-- Performance Metrics --}}
            <div class="col-xxl-4 col-xl-5 col-sm-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-success fs-16 me-2"><i class="ti ti-chart-pie"></i></span>
                            <h5 class="card-title mb-0">Key Performance Indicators</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="performance-metrics">
                            {{-- FCR --}}
                            <div class="metric-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted fs-12">Feed Conversion Ratio</span>
                                        <h4 class="mb-0 fw-bold text-primary">{{ number_format($data->avg_fcr ?? 0, 2) }}</h4>
                                    </div>
                                    <div class="metric-icon bg-soft-primary p-3 rounded">
                                        <i class="ti ti-chart-bar fs-24 text-primary"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Lower is better (Target: 1.5-1.8)</small>
                            </div>

                            {{-- PEF --}}
                            <div class="metric-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted fs-12">Production Efficiency Factor</span>
                                        <h4 class="mb-0 fw-bold text-success">{{ number_format($data->avg_pef ?? 0, 0) }}</h4>
                                    </div>
                                    <div class="metric-icon bg-soft-success p-3 rounded">
                                        <i class="ti ti-chart-line fs-24 text-success"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Higher is better (Target: &gt; 300)</small>
                            </div>

                            {{-- Livability --}}
                            <div class="metric-item p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted fs-12">Livability Rate</span>
                                        <h4 class="mb-0 fw-bold text-info">{{ number_format($data->avg_livability_pct ?? 0, 1) }}%</h4>
                                    </div>
                                    <div class="metric-icon bg-soft-info p-3 rounded">
                                        <i class="ti ti-heart fs-24 text-info"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Target: &gt; 95%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Weight Gain & Feed Consumption --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-warning fs-16 me-2"><i class="ti ti-chart-bar"></i></span>
                            <h5 class="card-title mb-0">Weight Gain & Feed Consumption Trends</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="height: 420px;">
                        <canvas id="adgFeedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 3: ENVIRONMENTAL MONITORING --}}
        {{-- ============================================ --}}
        <div class="row mb-3 mt-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-info me-2"><i class="ti ti-temperature"></i></span>
                    <h4 class="mb-0 fw-bold text-info">Environmental Monitoring</h4>
                </div>
            </div>
        </div>

        {{-- Environment & IoT Snapshot Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-inline-flex">
                            <span class="title-icon bg-soft-primary fs-16 me-2"><i class="ti ti-building"></i></span>
                            <h5 class="card-title mt-2">Real-Time Environment Snapshot (Latest Readings)</h5>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm mt-2" onclick="location.reload()">
                            <i class="ti ti-refresh me-1"></i> Refresh
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Shed Name</th>
                                    <th class="text-center">Shed Temp (°C)</th>
                                    <th class="text-center">Brooder Temp (°C)</th>
                                    <th class="text-center">Humidity (%)</th>
                                    <th class="text-center">CO<sub>2</sub> (ppm)</th>
                                    <th class="text-center">NH<sub>3</sub> (ppm)</th>
                                    <th class="text-center">Last Reading</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($shedEnvironment as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row->shed_name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-pink">{{ number_format($row->shed_temperature_c, 1) }}°C</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-orange">{{ number_format($row->brooder_temperature_c, 1) }}°C</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-skyblue">{{ number_format($row->humidity_pct, 1) }}%</span>
                                        </td>
                                        <td class="text-center">{{ number_format($row->co2_ppm, 0) }}</td>
                                        <td class="text-center">{{ number_format($row->nh3_ppm, 1) }}</td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($row->last_reading)->format('d-m-Y h:i A') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ ($row->status == 'OK') ? 'success' : 'danger' }}">
                                                {{ $row->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="ti ti-alert-circle fs-24 mb-2"></i>
                                            <p class="mb-0">No environmental data available</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- IoT Time Series Charts --}}
        <div class="row">
            {{-- Temperature Chart --}}
            <div class="col-xl-6 col-sm-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-pink fs-16 me-2"><i class="ti ti-temperature"></i></span>
                            <h5 class="card-title mb-0">Temperature Trends (Last 7 Days)</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="height: 420px;">
                        <div id="tempChart"></div>
                    </div>
                </div>
            </div>

            {{-- Humidity Chart --}}
            <div class="col-xl-6 col-sm-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-skyblue fs-16 me-2"><i class="ti ti-droplet"></i></span>
                            <h5 class="card-title mb-0">Humidity Trends (Last 7 Days)</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="height: 420px;">
                        <div id="humidityChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 4: OPERATIONAL EFFICIENCY --}}
        {{-- ============================================ --}}
        <div class="row mb-3 mt-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-warning me-2"><i class="ti ti-settings"></i></span>
                    <h4 class="mb-0 fw-bold text-warning">Operational Efficiency</h4>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Feed Consumption --}}
            <div class="col-xl-4 col-lg-6 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted fs-12">Total Feed Consumed</span>
                                <h3 class="mb-0 fw-bold text-warning mt-2">{{ number_format($data->feed_kg_window ?? 0, 0) }} kg</h3>
                                <small class="text-muted">Current reporting period</small>
                            </div>
                            <div class="metric-icon bg-soft-warning p-3 rounded">
                                <i class="ti ti-apple fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Water Consumption --}}
            <div class="col-xl-4 col-lg-6 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted fs-12">Total Water Consumed</span>
                                <h3 class="mb-0 fw-bold text-info mt-2">{{ number_format($data->water_l_window ?? 0, 0) }} L</h3>
                                <small class="text-muted">Current reporting period</small>
                            </div>
                            <div class="metric-icon bg-soft-info p-3 rounded">
                                <i class="ti ti-droplet fs-24 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Adjusted FCR --}}
            <div class="col-xl-4 col-lg-12 col-sm-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted fs-12">Adjusted FCR</span>
                                <h3 class="mb-0 fw-bold text-primary mt-2">{{ number_format($data->avg_adj_fcr ?? 0, 2) }}</h3>
                                <small class="text-muted">Corrected for mortality</small>
                            </div>
                            <div class="metric-icon bg-soft-primary p-3 rounded">
                                <i class="ti ti-calculator fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Environment Alerts Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-inline-flex">
                            <span class="title-icon bg-soft-danger fs-16 me-2"><i class="ti ti-alert-triangle"></i></span>
                            <h5 class="card-title mt-2">Recent Environment & Mortality Alerts</h5>
                        </div>
                        <a href="{{ route('iot.alerts') }}" class="btn btn-danger btn-sm mt-2">
                            <i class="ti ti-bell me-1"></i> View All Alerts
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
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
                                @forelse($environmentAlerts as $row)
                                    <tr>
                                        <td class="fw-semibold">{{ $row->shed_name }}</td>
                                        <td>{{ $row->flock_name ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $row->alert_type == 'ENV' ? 'warning' : 'danger' }}">
                                                {{ $row->alert_type }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $row->parameter }}</td>
                                        <td class="text-center fw-bold text-danger">{{ $row->avg_value }}</td>
                                        <td class="text-center">{{ $row->threshold }}</td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($row->alert_time)->format('d-m-Y h:i A') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="ti ti-check-circle fs-24 mb-2 text-success"></i>
                                            <p class="mb-0">No recent alerts - All systems operating normally</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script>
        // ======================================================
        // MORTALITY RATE CHART
        // ======================================================
        document.addEventListener('DOMContentLoaded', () => {
            const raw = @json($datasets ?? []);
            const datasetsArr = Array.isArray(raw) ? raw : Object.values(raw);

            const prepared = datasetsArr.map((ds, i) => ({
                label: ds.label ?? `Series ${i+1}`,
                data: (Array.isArray(ds.data) ? ds.data : []).map(p => ({
                    x: Number(p.x),
                    y: Number(p.y)
                })),
                parsing: false,
                borderWidth: 2,
                pointRadius: 3,
                tension: 0.3
            }));

            const el = document.getElementById('mortalityChart');
            if (!el) return;

            // Plugin to draw acceptable limit line
            const mortalityLimitLine = {
                id: 'mortalityLimitLine',
                afterDatasetsDraw(chart, args, pluginOptions) {
                    const { ctx, chartArea, scales } = chart;
                    if (!chartArea || !scales?.y) return;
                    const value = typeof pluginOptions?.value === 'number' ? pluginOptions.value : 0.274;
                    const y = scales.y.getPixelForValue(value);
                    if (!isFinite(y)) return;

                    ctx.save();
                    ctx.strokeStyle = pluginOptions?.color || '#dc3545';
                    ctx.lineWidth = 2;
                    ctx.setLineDash([6, 4]);
                    ctx.beginPath();
                    ctx.moveTo(chartArea.left, y);
                    ctx.lineTo(chartArea.right, y);
                    ctx.stroke();
                    ctx.setLineDash([]);

                    const label = pluginOptions?.label || 'Acceptable Limit (0.274%)';
                    ctx.fillStyle = pluginOptions?.color || '#dc3545';
                    ctx.font = 'bold 12px sans-serif';
                    const textWidth = ctx.measureText(label).width;
                    const textX = Math.max(chartArea.left + 6, chartArea.right - textWidth - 6);
                    const textY = Math.max(chartArea.top + 12, y - 6);
                    ctx.fillText(label, textX, textY);
                    ctx.restore();
                }
            };

            new Chart(el, {
                type: 'line',
                data: { datasets: prepared },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'nearest', intersect: false },
                    scales: {
                        x: {
                            type: 'linear',
                            title: { display: true, text: 'Age (days)' },
                            ticks: { precision: 0 }
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
                        },
                        mortalityLimitLine: {
                            value: 0.274,
                            color: '#dc3545',
                            label: 'Acceptable Limit (0.274%)'
                        }
                    }
                },
                plugins: [mortalityLimitLine]
            });
        });
    </script>

    <script>
        // ======================================================
        // ADG & FEED CHART
        // ======================================================
        document.addEventListener('DOMContentLoaded', () => {
            const labels   = @json($adgData['labels'] ?? []);
            const datasets = @json($adgData['datasets'] ?? []);

            const el = document.getElementById('adgFeedChart');
            if (!el) return;

            new Chart(el, {
                data: {
                    labels: labels.map(n => Number(n)),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'ADG (bars) vs Cumulative Feed (line)' },
                        tooltip: {
                            callbacks: {
                                title: items => `Age: ${items?.[0]?.label} days`,
                                label: (ctx) => {
                                    const y = ctx.parsed.y ?? 0;
                                    if (ctx.dataset.yAxisID === 'yFeed') {
                                        return `${ctx.dataset.label}: ${y} kg`;
                                    }
                                    return `${ctx.dataset.label}: ${y} g`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'category',
                            title: { display: true, text: 'Age (days)' },
                            ticks: { precision: 0 }
                        },
                        yAdg: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            title: { display: true, text: 'ADG (g/bird/day)' }
                        },
                        yFeed: {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            title: { display: true, text: 'Cumulative Feed (kg)' },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        });
    </script>

    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script>
        const chartData = @json($iotChartData);

        function buildLineData(values, lowerArr, upperArr, inRangeColor, outRangeColor) {
            return values.map((v, idx) => {
                const val = Number(v);
                const low = Number(lowerArr[idx]);
                const up  = Number(upperArr[idx]);
                const inRange = val >= low && val <= up;

                return {
                    x: chartData.labels[idx],
                    y: val,
                    fillColor: inRange ? inRangeColor : outRangeColor
                };
            });
        }

        const commonXAxis = {
            type: 'category',
            categories: chartData.labels,
            tickAmount: 24,
            axisBorder: {show: false},
            axisTicks: {show: false},
            labels: {
                rotate: -45,
                hideOverlappingLabels: true
            }
        };

        // TEMPERATURE CHART
        const temp1LineData = buildLineData(
            chartData.temp1,
            chartData.tempSafeLower,
            chartData.tempSafeUpper,
            '#007bff',
            '#dc3545'
        );

        const temp2LineData = buildLineData(
            chartData.temp2,
            chartData.tempSafeLower,
            chartData.tempSafeUpper,
            '#ffc107',
            '#dc3545'
        );

        const tempOptions = {
            chart: {
                type: 'line',
                height: 400,
                zoom: { enabled: true },
                toolbar: { show: true },
                fontFamily: "inherit",
            },
            series: [
                {
                    name: 'Shed Temp (°C)',
                    type: 'line',
                    data: temp1LineData
                },
                {
                    name: 'Brooder Temp (°C)',
                    type: 'line',
                    data: temp2LineData
                }
            ],
            xaxis: commonXAxis,
            yaxis: {
                min: 15,
                title: { text: 'Temperature (°C)' },
            },
            dataLabels: { enabled: false },
            stroke: { width: [2, 2], curve: 'smooth' },
            fill: { opacity: [0.8, 0.8] },
            markers: { size: 0 },
            legend: { position: 'top', horizontalAlign: "right" },
            tooltip: { shared: true, intersect: false },
        };

        new ApexCharts(document.querySelector('#tempChart'), tempOptions).render();

        // HUMIDITY CHART
        const humLineData = buildLineData(
            chartData.humidity,
            chartData.humSafeLower,
            chartData.humSafeUpper,
            '#36a2eb',
            '#dc3545'
        );

        const humidityOptions = {
            chart: {
                type: 'line',
                height: 400,
                zoom: { enabled: true },
                toolbar: { show: true },
                fontFamily: "inherit",
            },
            series: [
                {
                    name: 'Humidity (%)',
                    type: 'line',
                    data: humLineData
                }
            ],
            xaxis: commonXAxis,
            yaxis: {
                min: 20,
                title: { text: '% Relative Humidity' }
            },
            dataLabels: { enabled: false },
            stroke: { width: [2], curve: 'smooth' },
            fill: { opacity: [0.8] },
            markers: { size: 0 },
            legend: { position: 'top', horizontalAlign: "right" },
            tooltip: { shared: true, intersect: false },
        };

        new ApexCharts(document.querySelector('#humidityChart'), humidityOptions).render();
    </script>
@endpush
