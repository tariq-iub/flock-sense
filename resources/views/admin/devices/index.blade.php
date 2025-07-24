@extends('layouts.app')

@section('title', 'IoT Devices')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">IoT Devices</h4>
                    <h6>Manage IoT devices inventory.</h6>
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
                <a href="{{ route('iot.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>Add Device
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
                        <tr>
                            <th>Serial No</th>
                            <th>Model</th>
                            <th>Manufacturer</th>
                            <th>Firmware</th>
                            <th>Connectivity</th>
                            <th>Capabilities</th>
                            <th class="text-center">Battery Operated</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($devices as $device)
                            <tr>
                                <td>{{ $device->serial_no }}</td>
                                <td>{{ $device->model_number }}</td>
                                <td>{{ $device->manufacturer }}</td>
                                <td>{{ $device->firmware_version }}</td>
                                <td>{{ $device->connectivity_type }}</td>
                                <td>
                                    @foreach($device->capabilities as $cap)
                                        <span class="badge bg-soft-info text-dark border">
                                            <i class="{{ $cap->icon }}"></i>
                                            {{ ucfirst($cap->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-dark me-1">
                                        <i class="bi {{ $device->battery_operated ? 'bi-battery-charging' : 'bi-plug' }}"></i>
                                        {{ $device->battery_operated ? 'Battery' : 'Wired' }}
                                    </span>
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="{{ route('iot.show', $device) }}"
                                           class="p-2 border rounded me-2"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Show Device">
                                            <i class="ti ti-list"></i>
                                        </a>

                                        <a href="{{ route('iot.edit', $device) }}"
                                           class="p-2 border rounded me-2"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Device">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                           class="p-2 border rounded me-2 open-delete-modal"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Device"
                                           data-device-id="{{ $device->id }}"
                                           data-device-name="{{ $device->serial_no }}">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('iot.destroy', $device->id) }}" method="POST" id="delete{{ $device->id }}">
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

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Breed</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this IoT device data?
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
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-device-id');
                    const deviceName = this.getAttribute('data-device-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${deviceName}" data?`;

                    var modal = new bootstrap.Modal(document.getElementById('delete-modal'));
                    modal.show();
                });
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (deleteId) {
                    document.getElementById('delete' + deleteId).submit();
                }
            });
        });
    </script>
@endpush
