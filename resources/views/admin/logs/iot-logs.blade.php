@extends('layouts.app')

@section('title', 'Standards Data')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Device Logs</h4>
                    <h6>Monitor the IoT devices logging data.</h6>
                </div>
            </div>
            <ul class="table-top-head">
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
                <form id="filterForm" method="GET" action="{{ route('iot.logs') }}" class="row g-2 mb-3">
                    <div class="row align-items-end">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Select Farm</label>
                                        <select id="farmSelect" name="filter[farm_id]" class="select2">
                                            <option value="">Select Farm</option>
                                            @foreach($farms as $farm)
                                                <option value="{{ $farm->id }}" {{ (isset($farmId) && $farmId == $farm->id) ? 'selected' : '' }}>
                                                    {{ $farm->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Select Shed</label>
                                        <select name="filter[shed_id]" id="shedSelect" class="select2" disabled>
                                            <option value="">Select Shed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Select Device</label>
                                        <select name="filter[device_id]" id="deviceSelect" class="select2" disabled>
                                            <option value="">Select Device</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Select Date Range</label>
                                    <div class="input-icon-start position-relative mb-3">
                                        <span class="input-icon-addon fs-16 text-gray-9">
                                            <i class="ti ti-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control date-range bookingrange"
                                               id="date_range" name="filter[date_range]" placeholder="Search Logs">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <button class="btn btn-success w-100" type="submit" id="showLogsBtn" disabled>
                                    Get Device Logs
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
                    <h4>Device Readings</h4>
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
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           aria-label="Print"
                           title=""
                           data-bs-original-title="Print">
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
                                IoT Device Metrics
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="iotChart" height="150" class="w-100"></canvas>
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
                            <th class="text-center">Device</th>
                            <th class="text-center">Log Time</th>
                            <th class="text-center">Temperature (°C)</th>
                            <th class="text-center">Humidity (%)</th>
                            <th class="text-center">NH<sub>3</sub> (ppm)</th>
                            <th class="text-center">CO<sub>2</sub> (ppm)</th>
                            <th class="text-center">Electricity (kWh)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $serial_no = $device->serial_no ?? '';
                            $shed_name = $device?->currentShed()->shed->name ?? '';
                        @endphp
                        @foreach($logs as $row)
                        <tr>
                            <td>
                                <span class="fw-bold">{{  $serial_no }}</span>
                                <br>
                                <small class="text-muted">{{ $shed_name }}</small>
                            </td>
                            <td class="text-center">{{  \Carbon\Carbon::createFromTimestamp($row['timestamp'])->format('d-m-Y H:i:s A') }}</td>
                            <td class="text-center">{{ $row['temperature'] ?? '' }}</td>
                            <td class="text-center">{{ $row['humidity'] ?? '' }}</td>
                            <td class="text-center">{{ $row['ammonia'] ?? '' }}</td>
                            <td class="text-center">{{ $row['carbon_dioxide'] ?? '' }}</td>
                            <td class="text-center">{{ $row['electricity'] ?? '' }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}" type="text/javascript"></script>
    <script>
        const chartData = @json($chart);

        const ctx = document.getElementById('iotChart').getContext('2d');
        const iotChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets,
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { boxWidth: 20 }
                    },
                    tooltip: {
                        mode: 'nearest',
                        intersect: false,
                    },
                    title: {
                        display: true,
                        text: 'IoT Device Environment Logs'
                    },
                    zoom: { // Optional: Add zoom plugin if installed
                        pan: { enabled: true, mode: 'x' },
                        zoom: { wheel: { enabled: true }, pinch: { enabled: true }, mode: 'x' }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Temp / Humidity' },
                    },
                    y2: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Ammonia / CO₂' },
                        grid: { drawOnChartArea: false },
                    },
                    y3: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Electricity' },
                        grid: { drawOnChartArea: false },
                        offset: true,
                    },
                    x: {
                        title: { display: true, text: 'Date/Time' },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 15
                        }
                    }
                }
            }
        });
    </script>

    <script>
        $(function() {
            // When farm changes, load sheds
            $('#farmSelect').on('change', function() {
                var farmId = $(this).val();
                $('#shedSelect').prop('disabled', true).html('<option value="">Select Shed</option>');
                $('#deviceSelect').prop('disabled', true).html('<option value="">Select Device</option>');
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

            // When shed changes, load devices
            $('#shedSelect').on('change', function() {
                var shedId = $(this).val();
                $('#deviceSelect').prop('disabled', true).html('<option value="">Select Device</option>');
                $('#showLogsBtn').prop('disabled', true);
                if (shedId) {
                    $.get('/admin/get-devices', {shed_id: shedId}, function(devices) {
                        $('#deviceSelect').prop('disabled', false);
                        $.each(devices, function(i, device) {
                            $('#deviceSelect').append('<option value="' + device.id + '">' + device.serial_no + '</option>');
                        });
                    });
                }
            });

            // Enable submit when all selects are chosen
            $('#deviceSelect').on('change', function() {
                $('#showLogsBtn').prop('disabled', !$(this).val());
            });

            // Pre-select (when returning after submit)
            @if(isset($farmId) && $farmId)
            $.get('/admin/get-sheds', {farm_id: '{{ $farmId }}'}, function(sheds) {
                $('#shedSelect').prop('disabled', false);
                $.each(sheds, function(i, shed) {
                    var selected = (shed.id == '{{ request('filter.shed_id') }}') ? 'selected' : '';
                    $('#shedSelect').append('<option value="' + shed.id + '" ' + selected + '>' + shed.name + '</option>');
                });

                @if(request('filter.shed_id'))
                $.get('/admin/get-devices', {shed_id: '{{ request('filter.shed_id') }}'}, function(devices) {
                    $('#deviceSelect').prop('disabled', false);
                    $.each(devices, function(i, device) {
                        var selected = (device.id == '{{ request('filter.device_id') }}') ? 'selected' : '';
                        $('#deviceSelect').append('<option value="' + device.id + '" ' + selected + '>' + device.serial_no + '</option>');
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
                var deviceId = $('#deviceSelect').val();
                var dateRange = $('#date_range').val();
                if (deviceId) params.push('filter[device_id]=' + encodeURIComponent(deviceId));
                if (dateRange) params.push('filter[date_range]=' + encodeURIComponent(dateRange));
                var url = "{{ route('iot.export.excel') }}";
                if (params.length) url += '?' + params.join('&');
                window.location = url;
            });

            $("#print").on('click', function () {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var title = 'Device Reading Logs';
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
