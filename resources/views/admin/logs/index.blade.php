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
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="javascript:void(0);" class="btn btn-primary" id="excelExportBtn"
                   data-bs-toggle="tooltip" data-bs-placement="top"
                   aria-label="Excel" data-bs-original-title="Excel">
                    <i class="ti ti-file-excel me-1"></i>Excel
                </a>

                <a href="javascript:void(0);" id="print" class="btn btn-primary"
                   data-bs-toggle="tooltip" data-bs-placement="top"
                   aria-label="Print" data-bs-original-title="Print">
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

        <div class="card">
            <div class="card-header">
                {{-- Filter Form --}}
                <form id="filterForm" method="GET" action="{{ route('productions.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <select id="farmSelect" class="select2">
                            <option value="">Select Farm</option>
                            @foreach($farms as $farm)
                                <option value="{{ $farm->id }}" {{ (isset($farmId) && $farmId == $farm->id) ? 'selected' : '' }}>
                                    {{ $farm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filter[shed_id]" id="shedSelect" class="select2" disabled>
                            <option value="">Select Shed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filter[flock_id]" id="flockSelect" class="select2" disabled>
                            <option value="">Select Flock</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success float-end" id="showLogsBtn" disabled>
                            Show Logs
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body printArea">
                <div class="table-responsive">
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
                                <td class="text-center">{{ $log->day_feed_consumed }} / {{ $log->night_feed_consumed }}</td>
                                <td class="text-center">{{ $log->day_water_consumed }} / {{ $log->night_water_consumed }}</td>
                                <td class="text-center">{{ $log->weightLog->weighted_chickens_count ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->total_weight ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->avg_weight ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->avg_weight_gain ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->aggregated_total_weight ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->feed_efficiency ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->feed_conversion_ratio ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->adjusted_feed_conversion_ratio ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->fcr_standard_diff ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->coefficient_of_variation ?? '' }}</td>
                                <td class="text-center">{{ $log->weightLog->production_efficiency_factor ?? '' }}</td>
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
