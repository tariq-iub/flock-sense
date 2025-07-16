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
                    <select id="shedFilter" class="form-select">
                        <option value="">All Sheds</option>
                        @foreach($shedList as $shed)
                            <option value="{{ $shed->name }}">{{ $shed->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th>
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Flock</th>
                            <th>Shed</th>
                            <th class="text-center">Age (Days)</th>
                            <th class="text-center">Net Count</th>
                            <th class="text-center">Livability (%)</th>
                            <th class="text-center">Mortality (D/N)</th>
                            <th class="text-center">Feed (D/N)</th>
                            <th class="text-center">Water (D/N)</th>
                            <th>User</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox" value="{{ $log->id }}">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td>{{ optional($log->flock)->name }}</td>
                                <td>{{ optional($log->shed)->name }}</td>
                                <td class="text-center">{{ $log->age }}</td>
                                <td class="text-center">{{ $log->net_count }}</td>
                                <td class="text-center">{{ $log->livability }}</td>
                                <td class="text-center">{{ $log->day_mortality_count }} / {{ $log->night_mortality_count }}</td>
                                <td class="text-center">{{ $log->day_feed_consumed }} / {{ $log->night_feed_consumed }}</td>
                                <td class="text-center">{{ $log->day_water_consumed }} / {{ $log->night_water_consumed }}</td>
                                <td>{{ optional($log->user)->name }}</td>
                                <td class="action-table-data">
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
            // DataTable
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
                        $('.dataTables_filter').appendTo('.search-input');
                    },
                });

                // Filter by Shed
                $('#shedFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(2).search(selected).draw();
                });
            }

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
        });
    </script>
@endpush
