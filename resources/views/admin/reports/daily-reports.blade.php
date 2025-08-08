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
                        <form id="filterForm" method="GET" action="{{ route('productions.index') }}">
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
                                <select name="filter[shed_id]" id="shedSelect" class="select2" disabled>
                                    <option value="">Select Shed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Select Flock</label>
                                <select name="filter[flock_id]" id="flockSelect" class="select2" disabled>
                                    <option value="">Select Flock</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Select Date</label>
                                <select id="report_date" name="filter[report_date]"  class="select2" disabled>
                                    <option value="">Select Report Date</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-success w-100" type="submit" id="showLogsBtn" disabled>
                                    Get Daily Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body printArea">
                        ...
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
@endpush
