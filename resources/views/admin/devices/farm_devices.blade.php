@extends('layouts.app')

@section('title', 'IoT Devices')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Farm Devices</h4>
                    <h6>Linking / Delinking of IoT devices with farms.</h6>
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
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center row-gap-3">

                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th class="w-100">Device</th>
                            <th>Capabilities</th>
                            <th>Appliances</th>
                            <th>Linked Shed</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($devices as $device)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $device->serial_no }}</span>
                                    <br>
                                    <small class="text-muted">{{ $device->model_number . ' (Firmware: ' . $device->firmware_version . ')' }}</small>
                                </td>
                                <td>
                                    @foreach($device->capabilities as $cap)
                                        <span class="badge bg-gradient text-dark border">
                                        <i class="{{ $cap->icon }}"></i>
                                        {{ ucfirst($cap->name) }}
                                    </span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0);"
                                       class="btn btn-sm btn-outline-info view-appliance"
                                       title="View Device Appliances"
                                       data-device-id="{{ $device->id }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#appliancesModal">
                                        View
                                    </a>
                                </td>
                                <td>
                                    @php
                                    $currentShed = $device->currentShed();
                                    @endphp
                                    @if($currentShed)
                                        <span class="text-info fw-bold">{{ $currentShed->shed->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $currentShed->shed->farm->name }}</small>
                                    @else
                                        <span class="badge bg-soft-danger">Not Linked</span>
                                    @endif
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        @if($currentShed)
                                        <a href="javascript:void(0);"
                                           class="p-2 border rounded me-2" style="background-color: #FFEDE9 !important; color: #FF0000 !important;"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delink Shed"
                                           onclick="(new bootstrap.Modal(document.getElementById('delinkDeviceModal'))).show();">
                                            <i class="ti ti-ban"></i>
                                        </a>
                                        @else
                                        <a href="javascript:void(0);"
                                           class="p-2 border rounded me-2" style="background-color: #CBEFD4 !important; color: #3EB780 !important;"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Link Shed"
                                           onclick="(new bootstrap.Modal(document.getElementById('linkDeviceModal'))).show();">
                                            <i class="ti ti-jump-rope fs-16"></i>
                                        </a>
                                        @endif
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

    <!-- Link Device Modal -->
    <div class="modal fade" id="linkDeviceModal" tabindex="-1" aria-labelledby="linkDeviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('farm.devices.link') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Link IoT Device to Shed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label for="shed_id" class="form-label">Select Shed<span class="text-danger">*</span></label>
                            <select class="form-select" name="shed_id" required>
                                @foreach($sheds as $shed)
                                    <option value="{{ $shed->id }}">{{ $shed->name }} (Farm: {{ $shed->farm->name }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Shed is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="device_id" class="form-label">Select Device<span class="text-danger">*</span></label>
                            <select class="form-select" name="device_id" required>
                                @foreach($availableDevices as $device)
                                    <option value="{{ $device->id }}">{{ $device->serial_no }} ({{ $device->model_number }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Device is required.</div>
                        </div>
                        <div class="col-md-12">
                            <label for="location_in_shed" class="form-label">Location in Shed (optional)</label>
                            <input type="text" class="form-control" name="location_in_shed" placeholder="e.g. North corner">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-success me-2">Link Device</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delink Device Modal -->
    <div class="modal fade" id="delinkDeviceModal" tabindex="-1" aria-labelledby="delinkDeviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('farm.devices.delink') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Delink IoT Device from Shed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label for="shed_id" class="form-label">Select Shed<span class="text-danger">*</span></label>
                            <select class="form-select" name="shed_id" required>
                                @foreach($sheds as $shed)
                                    <option value="{{ $shed->id }}">{{ $shed->name }} (Farm: {{ $shed->farm->name }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Shed is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="device_id" class="form-label">Select Device<span class="text-danger">*</span></label>
                            <select class="form-select" name="device_id" required>
                                @foreach($linkedDevices as $device)
                                    <option value="{{ $device->id }}">{{ $device->serial_no }} ({{ $device->model_number }})</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Device is required.</div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-danger me-2">Delink Device</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Appliances Modal -->
    <div class="modal fade" id="appliancesModal" tabindex="-1" aria-labelledby="appliancesModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appliancesModalLabel">
                        Device Appliances
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="applianceModalContent">
                    <div class="text-center text-muted">
                        <div class="spinner-border text-success" role="status"></div>
                        <div class="mt-2">Loading appliance data...</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // Datatable
            if($('.datatable-custom').length > 0) {
                var table = $('.datatable-custom').DataTable({
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    "ordering": true,
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search",
                        sLengthMenu: 'Rows Per Page _MENU_ Entries',
                        info: "_START_ - _END_ of _TOTAL_ items",
                        paginate: {
                            next: ' <i class=" fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i> '
                        },
                    },
                    initComplete: (settings, json)=> {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                    },
                });

                $('#statusFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(1).search(selected).draw();
                });
            }
        });
    </script>

    <script>
        $(document).on('click', '.view-appliance', function () {
            let deviceId = $(this).data('device-id');
            $('#applianceModalContent').html('<div class="text-center"><div class="spinner-border text-success"></div></div>');

            $.get(`/admin/iot/devices/${deviceId}/appliances`, function (data) {
                $('#applianceModalContent').html(data.html);
            }).fail(function () {
                $('#applianceModalContent').html('<div class="alert alert-danger">Failed to load appliances.</div>');
            });
        });
    </script>
@endpush
