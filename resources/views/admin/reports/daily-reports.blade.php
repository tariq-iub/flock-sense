@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Daily Reports</h4>
                    <h6>Admin visibility for daily reports of all farms.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="javascript:void(0)" class="btn btn-primary" id="print">
                    <i class="ti ti-printer me-1"></i>Print
                </a>
            </div>
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

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Report Language</label>
                            <select id="version" class="select2">
                                <option value="en">English</option>
                                <option value="ur">Urdu</option>
                            </select>
                        </div>

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
                        <div class="mb-3">
                            <label class="form-label">Select Shed</label>
                            <select id="shedSelect" name="filter[shed_id]" class="select2" disabled>
                                <option value="">Select Shed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Flock</label>
                            <select id="flockSelect" name="filter[flock_id]" class="select2" disabled>
                                <option value="">Select Flock</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Select Date</label>
                            <select id="dateSelect" name="filter[report_date]"  class="select2" disabled>
                                <option value="">Select Report Date</option>
                            </select>
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-outline-success" type="button" id="showLogsBtn" disabled>
                                Get Daily Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card printArea">
                    <div class="card-header">
                        <h4>Daily Production Report</h4>
                    </div>

                    <div class="card-body skeleton">
                        <div id="reportArea">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // When farm changes, load sheds
            $('#farmSelect').on('change', function() {
                var farmId = $(this).val();
                $('#shedSelect').prop('disabled', true).html('<option value="">Select Shed</option>');
                $('#flockSelect').prop('disabled', true).html('<option value="">Select Flock</option>');
                $('#dateSelect').prop('disabled', true).html('<option value="">Select Report Date</option>');
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

            function formatedDates(log_dates)
            {
                const rawDates = Array.isArray(log_dates) ? log_dates : [];

                // Use the date part before 'T' to avoid timezone shifts
                const dates = rawDates
                    .map(s => (typeof s === 'string' ? s.split('T')[0] : ''))
                    .filter(Boolean)
                    // ensure unique and sorted
                    .filter((v, i, arr) => arr.indexOf(v) === i)
                    .sort((a, b) => a.localeCompare(b));

                return dates;
            }

            // When shed changes, load flocks
            $('#shedSelect').on('change', function() {
                var shedId = $(this).val();
                $('#flockSelect').prop('disabled', true).html('<option value="">Select Flock</option>');
                $('#dateSelect').prop('disabled', true).html('<option value="">Select Report Date</option>');
                $('#showLogsBtn').prop('disabled', true);
                if (shedId) {
                    $.get('/admin/get-flocks', {shed_id: shedId}, function(flocks) {
                        $('#flockSelect').prop('disabled', false);
                        $.each(flocks, function(i, flock) {
                            $('#flockSelect').append('<option value="' + flock.id + '">' + flock.name + '</option>');
                        });
                    });

                    $.get(`/api/v1/production/report/headers/${shedId}`, function(data) {
                        $('#dateSelect').prop('disabled', false);
                        var dates = formatedDates(data.production_log_dates);
                        $.each(dates, function(i, date) {
                            $('#dateSelect').append('<option value="' + date + '">' + date + '</option>');
                        });
                    });
                }
            });

            // Enable submit when all selects are chosen
            $('#dateSelect').on('change', function() {
                $('#showLogsBtn').prop('disabled', !$(this).val());
            });
        });
    </script>
    <script>
        $(function() {
            $("#print").on('click', function () {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var title = 'Daily Report';
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
    <script>
        $('#showLogsBtn').on('click', function() {
            var version = $('#version').val();
            var shed_id = $('#shedSelect').val();
            var report_date = $('#dateSelect').val();

            $('#reportArea').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');
            $.get(`/admin/daily-report-card/${version}?shed_id=${shed_id}&date=${report_date}`, function(data) {
                $('#reportArea').html(data);
            })
        });
    </script>
@endpush
