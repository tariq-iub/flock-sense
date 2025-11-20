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
                    <div class="card-body d-flex flex-row justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash3.svg') }}" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent d-flex flex-column">
                                <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_birds_current }}">0</span></h5>
                                Total Live Birds
                            </div>
                        </div>
                        <div class="fw-semibold mb-0">
                            <span class="bg-soft-info p-3">{{ $data->avg_livability_pct }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash3 w-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <div class="dash-widgetimg">
                                <span><img src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_mortalities_window }}">0</span></h5>
                                Total Mortalities
                            </div>
                        </div>
                        <div class="fw-semibold mb-0">
                            <span class="bg-soft-danger p-3">{{ $data->mortality_rate_pct }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mortality and ADG Graphs --}}
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

        {{-- Environment and IoT Snapshot --}}
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

        {{-- IoT Log Graphs --}}
        <div class="row">
            <div class="col-xl-6 col-sm-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-pink fs-16 me-2"><i class="ti ti-chart-line"></i></span>
                            <h5 class="card-title mb-0">Temperature</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="height: 420px;">
                        <div id="tempChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-sm-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-skyblue fs-16 me-2"><i class="ti ti-map-pin-check"></i></span>
                            <h5 class="card-title mb-0">Humidity</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="height: 420px;">
                        <div id="humidityChart"></div>
                    </div>
                </div>
            </div>

{{--            <div class="col-4 d-flex">--}}
{{--                <div class="card flex-fill">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="d-inline-flex align-items-center">--}}
{{--                            <span class="title-icon bg-soft-orange fs-16 me-2"><i class="ti ti-chart-pie"></i></span>--}}
{{--                            <h5 class="card-title mb-0">CO<sub>2</sub> / NH<sub>3</sub> Trend</h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body pb-0" style="height: 420px;">--}}
{{--                        <div id="gasChart"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        {{-- Environment Alerts --}}
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

@push('js')
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
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

            const el = document.getElementById('adgFeedChart');
            if (!el) return;

            new Chart(el, {
                data: {
                    labels: labels.map(n => Number(n)), // Age (days)
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
                            grid: { drawOnChartArea: false } // keep grids readable
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
    <script>
        const chartData = @json($iotChartData);

        // Helper: build rangeArea data [{ x, y: [lower, upper] }]
        function buildRangeAreaData(lowerArr, upperArr) {
            return lowerArr.map((low, idx) => ({
                x: chartData.labels[idx],
                y: [Number(low), Number(upperArr[idx])]
            }));
        }

        // Helper: build line data [{ x, y, marker: { size, fillColor } }]
        function buildLineData(values, lowerArr, upperArr, inRangeColor, outRangeColor) {
            return values.map((v, idx) => {
                const val = Number(v);
                const low = Number(lowerArr[idx]);
                const up  = Number(upperArr[idx]);
                const inRange = val >= low && val <= up;

                return {
                    x: chartData.labels[idx],
                    y: val,
                    marker: {
                        size: 3,
                        fillColor: inRange ? inRangeColor : outRangeColor
                    }
                };
            });
        }

        // Common x-axis config
        const commonXAxis = {
            type: 'category',
            categories: chartData.labels,
            tickAmount: 24, // roughly 1 label per ~1-2 hours
            axisBorder: {show: false},
            axisTicks: {show: false},
            labels: {
                rotate: -45,
                hideOverlappingLabels: true
            }
        };

        // ======================================================
        // 1) TEMPERATURE CHART (Temp1 + Temp2 + Safe Band)
        // ======================================================
        const tempRangeData = buildRangeAreaData(
            chartData.tempSafeLower,
            chartData.tempSafeUpper
        );

        const temp1LineData = buildLineData(
            chartData.temp1,
            chartData.tempSafeLower,
            chartData.tempSafeUpper,
            '#007bff', // in-range
            '#dc3545'  // out-of-range
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
                height: 350,
                zoom: { enabled: true },
                toolbar: { show: false },
                fontFamily: "inherit",
                parentHeightOffset: 0,
            },
            series: [
                // {
                //     name: 'Temp Safe Range',
                //     type: 'rangeArea',
                //     data: tempRangeData
                // },
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
                title: { text: 'Temperature' },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                // width: [0, 2, 2],
                width: [2, 2],
                curve: 'smooth'
            },
            fill: {
                // opacity: [0.15, 0.8, 0.8]
                opacity: [1, 1]
            },
            markers: {
                size: 0
            },
            legend: {
                position: 'top',
                horizontalAlign: "right",
            },
            tooltip: {
                shared: true,
                intersect: false,
            },
        };

        new ApexCharts(document.querySelector('#tempChart'), tempOptions).render();


        // ======================================================
        // 2) HUMIDITY CHART (+ Safe Band)
        // ======================================================
        const humRangeData = buildRangeAreaData(
            chartData.humSafeLower,
            chartData.humSafeUpper
        );

        const humLineData = buildLineData(
            chartData.humidity,
            chartData.humSafeLower,
            chartData.humSafeUpper,
            '#36a2eb',
            '#dc3545'
        );

        const humidityOptions = {
            chart: {
                type: 'rangeArea',
                height: 350,
                zoom: { enabled: true },
                toolbar: { show: false }
            },
            series: [
                {
                    name: 'Humidity Safe Range',
                    type: 'rangeArea',
                    data: humRangeData
                },
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
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: [0, 2],
                curve: 'smooth'
            },
            fill: {
                opacity: [0.15, 0.8]
            },
            markers: {
                size: 0
            },
            legend: {
                position: 'top',
                horizontalAlign: "right",
            },
            tooltip: {
                shared: true
            },
        };

        new ApexCharts(document.querySelector('#humidityChart'), humidityOptions).render();


        // ======================================================
        // 3) GAS CHART (NH3 + CO2 + Safe Bands)
        // NOTE: for simplicity, both share same axis here.
        //       If you want dual y-axes, we can wire that next.
        // ======================================================
        const nh3RangeData = buildRangeAreaData(
            chartData.nh3SafeLower,
            chartData.nh3SafeUpper
        );
        const nh3LineData = buildLineData(
            chartData.nh3,
            chartData.nh3SafeLower,
            chartData.nh3SafeUpper,
            '#9966ff',
            '#dc3545'
        );

        const co2RangeData = buildRangeAreaData(
            chartData.co2SafeLower,
            chartData.co2SafeUpper
        );
        const co2LineData = buildLineData(
            chartData.co2,
            chartData.co2SafeLower,
            chartData.co2SafeUpper,
            '#ff6384',
            '#dc3545'
        );

        const gasOptions = {
            chart: {
                type: 'rangeArea',
                height: 350,
                zoom: { enabled: true },
                toolbar: { show: false }
            },
            series: [
                {
                    name: 'NH₃ Safe Range',
                    type: 'rangeArea',
                    data: nh3RangeData
                },
                {
                    name: 'NH₃ (ppm)',
                    type: 'line',
                    data: nh3LineData
                },
                {
                    name: 'CO₂ Safe Range',
                    type: 'rangeArea',
                    data: co2RangeData
                },
                {
                    name: 'CO₂ (ppm)',
                    type: 'line',
                    data: co2LineData
                }
            ],
            xaxis: commonXAxis,
            yaxis: {
                title: { text: 'ppm (NH₃ & CO₂)' }
                // For *proper* dual-axis (NH3 vs CO2),
                // we can configure yaxis: [{...}, {...}] and link series.
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: [0, 2, 0, 2],
                curve: 'smooth'
            },
            fill: {
                opacity: [0.15, 0, 0.08, 0]
            },
            markers: {
                size: 0
            },
            legend: {
                position: 'top'
            },
            tooltip: {
                shared: true
            },
        };

        // new ApexCharts(document.querySelector('#gasChart'), gasOptions).render();
    </script>
@endpush



