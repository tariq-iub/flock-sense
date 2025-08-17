@extends('layouts.app')

@section('title', 'Production Logs')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Production Logs</h4>
                    <h6>View and manage production logs of flocks.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh" aria-describedby="tooltip189061"><i class="ti ti-refresh"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="container-fluid">
            <div class="row mb-3">
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert">
                        <div>
                            <i class="feather-check-circle flex-shrink-0 me-2"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fas fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>
                            <i class="feather-alert-triangle flex-shrink-0 me-2"></i>
                            There were some errors with your submission:
                        </strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fas fa-xmark"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body pb-1">
                <form id="filterForm" method="GET" action="{{ route('productions.index') }}" class="row g-2 mb-3">
                    <div class="row align-items-end">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Select Farm</label>
                                        <select id="farmSelect" class="select2">
                                            <option value="">Select Farm</option>
                                            @foreach($farms as $farm)
                                                <option value="{{ $farm->id }}" {{ (isset($farmId) && $farmId == $farm->id) ? 'selected' : '' }}>
                                                    {{ $farm->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Select Shed</label>
                                        <select name="filter[shed_id]" id="shedSelect" class="select2" disabled>
                                            <option value="">Select Shed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Select Flock</label>
                                        <select name="filter[flock_id]" id="flockSelect" class="select2" disabled>
                                            <option value="">Select Flock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <button class="btn btn-success w-100" type="submit" id="showLogsBtn" disabled>
                                    Get Production Logs
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card no-search">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div>
                    <h4>Production Logs</h4>
                </div>
                <ul class="table-top-head">
                    <li class="me-2">
                        <a href="javascript:void(0);" class="btn btn-outline-success" id="excelExportBtn"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           aria-label="Excel" data-bs-original-title="Excel">
                            <i class="ti ti-file-excel fs-16"></i>
                        </a>
                    </li>
                    <li class="me-2">
                        <a href="javascript:void(0);" id="print" class="btn btn-outline-success"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           aria-label="Print" data-bs-original-title="Print">
                            <i class="ti ti-printer fs-16"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">

                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Graph Trends
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="productionCombinedChart" height="150" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive printArea">
                    <table class="table table-nowrap table-hover mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>Log Date</th>
                            <th>Flock</th>
                            <th>Shed</th>

                            <th class="text-center">Age (Days)</th>
                            <th class="text-center">Mortality (D/N)</th>
                            <th class="text-center">Opening Count</th>
                            <th class="text-center">Net Count</th>
                            <th class="text-center">Livability (%)</th>
                            <th class="text-center">Feed (D/N)</th>
                            <th class="text-center">Water (D/N)</th>
                            <th class="text-center">Medicine (D/N)</th>

                            <th class="text-center">Weighted Chickens</th>
                            <th class="text-center">Recorded Weight</th>
                            <th class="text-center">Avg Weight</th>
                            <th class="text-center">Avg Weight Gain</th>
                            <th class="text-center">Flock Weight</th>
                            <th class="text-center">Feed Efficiency</th>
                            <th class="text-center">FCR</th>
                            <th class="text-center">Adjusted FCR</th>
                            <th class="text-center">FCR Diff</th>
                            <th class="text-center">CV (%)</th>
                            <th class="text-center">PEF</th>
                            <th class="hide-it">User</th>
                            <th class="no-sort hide-it"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    {{ $log->production_log_date->format('d-m-Y') }}
                                </td>
                                <td>{{ optional($log->flock)->name }}</td>
                                <td>{{ optional($log->shed)->name }}</td>

                                <td class="text-center">{{ $log->age }}</td>
                                <td class="text-center">{{ $log->day_mortality_count }} / {{ $log->night_mortality_count }}</td>
                                <td class="text-center">{{ $log->flock->chicken_count }}</td>
                                <td class="text-center">{{ $log->net_count }}</td>
                                <td class="text-center">{{ $log->livability }}</td>
                                <td class="text-center">{{ number_format($log->day_feed_consumed / 1000, 0) }} / {{ number_format($log->night_feed_consumed / 1000, 0) }}</td>
                                <td class="text-center">{{ $log->day_water_consumed }} / {{ $log->night_water_consumed }}</td>
                                <td class="text-center">{{ $log->day_medicine }} / {{ $log->night_medicine }}</td>

                                <td class="text-center">{{ $log->weightLog->weighted_chickens_count ?? '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->total_weight / 1000, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->avg_weight / 1000, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->avg_weight_gain / 1000, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->aggregated_total_weight / 1000, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->feed_efficiency, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->feed_conversion_ratio, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->adjusted_feed_conversion_ratio, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->fcr_standard_diff, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->coefficient_of_variation, 2) : '' }}</td>
                                <td class="text-center">{{ ($log->weightLog) ? number_format($log->weightLog->production_efficiency_factor, 2) : '' }}</td>
                                <td class="hide-it">{{ optional($log->user)->name }}</td>
                                <td class="action-table-data hide-it">
                                    <div class="action-icon d-inline-flex">
                                        <a href="{{ route('productions.show', $log->id) }}"
                                           class="me-2 d-flex align-items-center p-2 border rounded"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="View Details">
                                            <i class="ti ti-list"></i>
                                        </a>
                                        <a href="{{ route('productions.edit', $log->id) }}"
                                           class="me-2 d-flex align-items-center p-2 border rounded edit-log"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="Edit Log"
                                           data-log-id="{{ $log->id }}"
                                           data-log-flock="{{ optional($log->flock)->name }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="Delete Log"
                                           data-log-id="{{ $log->id }}"
                                           data-log-flock="{{ optional($log->flock)->name }}"
                                           class="p-2 open-delete-modal">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('productions.destroy', $log->id) }}" method="POST" id="delete{{ $log->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Example Delete Modal (similar to your template) -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Production Log</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this production log?
                        </p>
                        <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary fs-13 fw-medium p-2 px-3 me-2" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-danger fs-13 fw-medium p-2 px-3" id="confirm-delete-btn">
                                Yes Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('productionCombinedChart').getContext('2d');
            const chart = new Chart(ctx, {
                data: {
                    labels: @json($ages),
                    datasets: [
                        // Line datasets (already present)
                        {
                            label: 'Daily Mortality',
                            data: @json($dailyMortality),
                            borderColor: 'rgba(220,38,38,1)',
                            backgroundColor: 'rgba(220,38,38,0.1)',
                            type: 'line',
                            yAxisID: 'y',
                            tension: 0.3,
                            order: 1,
                        },
                        {
                            label: 'Livability (%)',
                            data: @json($livability),
                            borderColor: 'rgba(34,197,94,1)',
                            backgroundColor: 'rgba(34,197,94,0.1)',
                            type: 'line',
                            yAxisID: 'y1',
                            tension: 0.3,
                            order: 1,
                        },
                        {
                            label: 'Daily Feed Consumption (Kg)',
                            data: @json($dailyFeed),
                            borderColor: 'rgba(59,130,246,1)',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            type: 'line',
                            yAxisID: 'y2',
                            tension: 0.3,
                            order: 1,
                        },

                        // Bar datasets
                        {
                            label: 'Feed Conv. Ratio',
                            data: @json($feedConversionRatio),
                            backgroundColor: 'rgba(168,85,247,0.7)', // purple-400
                            type: 'bar',
                            yAxisID: 'y3',
                            order: 2,
                        },
                        {
                            label: 'CV (%)',
                            data: @json($coefficientOfVariation),
                            backgroundColor: 'rgba(251,191,36,0.7)', // yellow-400
                            type: 'bar',
                            yAxisID: 'y4',
                            order: 2,
                        },
                        {
                            label: 'Prod. Eff. Factor',
                            data: @json($productionEfficiencyFactor),
                            backgroundColor: 'rgba(52,211,153,0.7)', // green-400
                            type: 'bar',
                            yAxisID: 'y5',
                            order: 2,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Production Parameters by Age' }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Age (Days)' }},
                        y: { // For Daily Mortality
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Daily Mortality' },
                        },
                        y1: { // For Livability
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Livability (%)' },
                        },
                        y2: { // For Daily Feed
                            type: 'linear',
                            display: false,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                        },
                        y3: { // For Feed Conv. Ratio
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { drawOnChartArea: false },
                            offset: true,
                            title: { display: true, text: 'FCR' },
                        },
                        y4: { // For CV
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            offset: true,
                            title: { display: true, text: 'CV (%)' },
                        },
                        y5: { // For Prod. Eff. Factor
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            offset: true,
                            title: { display: true, text: 'Prod. Eff. Factor' },
                        }
                    }
                }
            });
        });
    </script>

    <script>
        $(function() {
            // When farm changes, load sheds
            $('#farmSelect').on('change', function() {
                var farmId = $(this).val();
                $('#shedSelect').prop('disabled', true).html('<option value="">Select Shed</option>');
                $('#flockSelect').prop('disabled', true).html('<option value="">Select Flock</option>');
                $('#showLogsBtn').prop('disabled', true);
                if (farmId) {
                    $.get('/admin/get-sheds', {farm_id: farmId}, function(sheds) {
                        $('#shedSelect').prop('disabled', false);
                        $.each(sheds, function(i, shed) {
                            $('#shedSelect').append('<option value="' + shed.id + '">' + shed.name + '</option>');
                        });
                    });
                }
            });

            // When shed changes, load flocks
            $('#shedSelect').on('change', function() {
                var shedId = $(this).val();
                $('#flockSelect').prop('disabled', true).html('<option value="">Select Flock</option>');
                $('#showLogsBtn').prop('disabled', true);
                if (shedId) {
                    $.get('/admin/get-flocks', {shed_id: shedId}, function(flocks) {
                        $('#flockSelect').prop('disabled', false);
                        $.each(flocks, function(i, flock) {
                            $('#flockSelect').append('<option value="' + flock.id + '">' + flock.name + '</option>');
                        });
                    });
                }
            });

            // Enable submit when all selects are chosen
            $('#flockSelect').on('change', function() {
                $('#showLogsBtn').prop('disabled', !$(this).val());
            });

            // Pre-select (when returning after submit)
            @if(isset($farmId) && $farmId)
            $.get('/admin/get-sheds', {farm_id: '{{ $farmId }}'}, function(sheds) {
                $('#shedSelect').prop('disabled', false);
                $.each(sheds, function(i, shed) {
                    var selected = (shed.id == '{{ request('filter.shed_id') }}') ? 'selected' : '';
                    $('#shedSelect').append('<option value="'+shed.id+'" '+selected+'>'+shed.name+'</option>');
                });

                @if(request('filter.shed_id'))
                $.get('/admin/get-flocks', {shed_id: '{{ request('filter.shed_id') }}'}, function(flocks) {
                    $('#flockSelect').prop('disabled', false);
                    $.each(flocks, function(i, flock) {
                        var selected = (flock.id == '{{ request('filter.flock_id') }}') ? 'selected' : '';
                        $('#flockSelect').append('<option value="' + flock.id + '" ' + selected + '>' + flock.name + '</option>');
                    });
                    $('#showLogsBtn').prop('disabled', '{{ $farmId ? false : true }}');
                });
                @endif
            });
            @endif

            $('#excelExportBtn').on('click', function (e) {
                e.preventDefault();
                // Gather filter query
                var params = [];
                var shedId = $('#shedSelect').val();
                var flockId = $('#flockSelect').val();
                if (shedId) params.push('filter[shed_id]=' + encodeURIComponent(shedId));
                if (flockId) params.push('filter[flock_id]=' + encodeURIComponent(flockId));
                var url = "{{ route('productions.export.excel') }}";
                if (params.length) url += '?' + params.join('&');
                window.location = url;
            });
        });
    </script>
    <script>
        $(function() {
            // Delete Modal Logic
            let deleteId = null;
            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-log-id');
                    const flockName = this.getAttribute('data-log-flock');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete the production log for "${flockName}"?`;

                    var modal = new bootstrap.Modal(document.getElementById('delete-modal'));
                    modal.show();
                });
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (deleteId) {
                    document.getElementById('delete' + deleteId).submit();
                }
            });

            $("#print").on('click', function () {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var title = 'Production Logs';
                var head = '';
                var options = {
                    mode: mode,
                    popClose: close,
                    popTitle: title,
                    extraHead: head
                };

                $('.hide-it').hide();
                $("div.printArea").printArea(options);
                $('.hide-it').show();
            });
        });
    </script>
@endpush
