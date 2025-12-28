@extends('layouts.app')

@section('title', 'Quick Actions')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Quick Actions</h4>
                    <h6>Manage quick access shortcuts.</h6>
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
                <a href="{{ route('shortcuts.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>Add Shortcut
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
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" id="groupFilter" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-filter me-2"></i>Filter by Group
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="groupFilter">
                            <li><a class="dropdown-item" href="#" data-group="">All Groups</a></li>
                            <li><a class="dropdown-item" href="#" data-group="admin">Admin</a></li>
                            <li><a class="dropdown-item" href="#" data-group="user">User</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>URL</th>
                            <th>Group</th>
                            <th class="text-center">Default</th>
                            <th class="text-center">Users Count</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shortcuts as $shortcut)
                            <tr>
                                <td>
                                    <span class="badge bg-soft-info text-dark border p-2">
                                        <i class="{{ $shortcut->icon }} fs-20"></i>
                                    </span>
                                </td>
                                <td><strong>{{ $shortcut->title }}</strong></td>
                                <td><code>{{ $shortcut->url }}</code></td>
                                <td>
                                    <span class="badge {{ $shortcut->group === 'admin' ? 'bg-soft-primary' : 'bg-soft-secondary' }} text-dark border">
                                        <i class="ti {{ $shortcut->group === 'admin' ? 'ti-shield-check' : 'ti-users' }} me-1"></i>
                                        {{ ucfirst($shortcut->group) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($shortcut->default)
                                        <span class="badge bg-soft-success text-dark border">
                                            <i class="ti ti-check"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge bg-soft-secondary text-dark border">
                                            <i class="ti ti-x"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-soft-info text-dark border">
                                        {{ $shortcut->users->count() }}
                                    </span>
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="{{ route('shortcuts.edit', $shortcut) }}"
                                           class="p-2 border rounded me-2"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Shortcut">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                           class="p-2 border rounded me-2 open-delete-modal"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Shortcut"
                                           data-shortcut-id="{{ $shortcut->id }}"
                                           data-shortcut-name="{{ $shortcut->title }}">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('shortcuts.destroy', $shortcut->id) }}" method="POST" id="delete{{ $shortcut->id }}">
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
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Shortcut</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this shortcut?
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

                // Group filter
                $('.dropdown-item[data-group]').on('click', function(e) {
                    e.preventDefault();
                    var group = $(this).data('group');
                    table.column(3).search(group).draw();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-shortcut-id');
                    const shortcutName = this.getAttribute('data-shortcut-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${shortcutName}" shortcut?`;

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
