@extends('layouts.app')

@section('title', 'Print QR Codes')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Print QR Codes</h4>
                    <h6>Select devices and configure QR code printing options.</h6>
                </div>
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
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('qr-code.print') }}" method="POST" id="qrCodeForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Label Size</label>
                            <select name="label_size" class="form-select" required>
                                <option value="small" selected>Small (50x100mm)</option>
                                <option value="medium">Medium (75x125mm)</option>
                                <option value="large">Large (100x150mm)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Include Device Details</label>
                            <select name="include_details" class="form-select">
                                <option value="0" selected>QR Code Only</option>
                                <option value="1">QR Code with Details</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-printer me-2"></i>Print Selected QR Codes
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Select Devices ({{ count($devices) }} available)</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllDevices()">
                                    <i class="ti ti-checkbox me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllDevices()">
                                    <i class="ti ti-checkbox me-1"></i>Clear All
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datatable-custom">
                            <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Serial No</th>
                                <th>Model</th>
                                <th>Manufacturer</th>
                                <th>Firmware</th>
                                <th>Connectivity</th>
                                <th>Capabilities</th>
                                <th>Assigned Shed</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                               name="devices[]"
                                               value="{{ $device->id }}"
                                               class="form-check-input device-checkbox"
                                               data-serial="{{ $device->serial_no }}">
                                    </td>
                                    <td><strong>{{ $device->serial_no }}</strong></td>
                                    <td>{{ $device->model_number ?? '-' }}</td>
                                    <td>{{ $device->manufacturer ?? '-' }}</td>
                                    <td>{{ $device->firmware_version ?? '-' }}</td>
                                    <td>{{ $device->connectivity_type }}</td>
                                    <td>
                                        @foreach($device->capabilities as $cap)
                                            <span class="badge bg-soft-info text-dark border">
                                                <i class="{{ $cap->icon }}"></i>
                                                {{ ucfirst($cap->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($device->shedDevices->isNotEmpty())
                                            @foreach($device->shedDevices as $shedDevice)
                                                @if($shedDevice->is_active)
                                                    <span class="badge bg-soft-success text-dark border">
                                                        {{ $shedDevice->shed->name ?? 'Unknown' }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge bg-soft-secondary text-dark border">Unassigned</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // Datatable
            if($('.datatable-custom').length > 0) {
                $('.datatable-custom').DataTable({
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    "ordering": true,
                    "pageLength": 25,
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
            }
        });

        // Select all devices
        function selectAllDevices() {
            document.querySelectorAll('.device-checkbox').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        }

        // Clear all devices
        function clearAllDevices() {
            document.querySelectorAll('.device-checkbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkbox functionality
            const selectAllCheckbox = document.getElementById('selectAll');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    document.querySelectorAll('.device-checkbox').forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            }
        });
    </script>
@endpush
