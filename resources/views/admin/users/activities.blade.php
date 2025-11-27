@extends('layouts.app')

@section('title', 'User Activities')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">User Activities</h4>
                    <h6>View and monitor user / clients activities.</h6>
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
                    <select id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Blocked">Blocked</option>
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th class="w-100">Role</th>
                            <th class="text-center">Guard</th>
                            <th class="text-center">Users Attach</th>
                            <th class="text-center">Create Date</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach([] as $role)
                            <tr>
                                <td class="w-100">
                                    {{ ucfirst($role->name) }}
                                </td>
                                <td class="text-center">{{ ucfirst($role->guard_name) }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                       class="btn btn-sm btn-outline-info users-data"
                                       data-role-id="{{ $role->id }}"
                                       data-role-name="{{ ucfirst($role->name) }}"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title=""
                                       data-bs-original-title="View Attach Users">
                                        View
                                    </a>
                                </td>
                                <td class="text-center">
                                   {{ $role->created_at->format('d-m-Y') }}
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="{{ route('roles.permissions', $role) }}" class="me-2 d-flex align-items-center p-2 border rounded"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Manage Permissions">
                                            <i class="ti ti-shield"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                           class="me-2 d-flex align-items-center p-2 border rounded edit-role"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Role"
                                           data-role-id="{{ $role->id }}"
                                           data-role-name="{{ $role->name }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
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
                    table.column(5).search(selected).draw();
                });
            }
        });
    </script>
    <script>
        $(function(){
            $('.users-data').on('click', function() {
                var roleId = $(this).data('role-id');
                var roleName = $(this).data('role-name');

                // Show loading spinner in modal body
                $('#attachUsersModal .modal-body').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');
                $('#attachUsersModalLabel').text('Attach Users - ' + roleName);

                // Open the modal
                var modal = new bootstrap.Modal(document.getElementById('attachUsersModal'));
                modal.show();

                // Fetch the attached users via AJAX
                $.ajax({
                    url: '/admin/roles/' + roleId + '/users',
                    method: 'GET',
                    success: function(data) {
                        $('#attachUsersModalLabel').text('Attach Users - ' + data.role_name);
                        $('#attachUsersModal .modal-body').html(data.html);
                    },
                    error: function() {
                        $('#attachUsersModal .modal-body').html('<div class="alert alert-danger">Failed to load users.</div>');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-role').forEach(function(button) {
                button.addEventListener('click', function() {
                    var roleId = this.getAttribute('data-role-id');
                    var roleName = this.getAttribute('data-role-name');
                    // Set the form action dynamically
                    var form = document.getElementById('editRoleForm');
                    form.action = '/admin/roles/' + roleId;

                    // Set hidden and visible values
                    document.getElementById('edit-role-id').value = roleId;
                    document.getElementById('edit-name').value = roleName;
                    // Update modal title (optional)
                    document.getElementById('editRoleModalLabel').textContent = "Edit Role - " + roleName;

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
                    modal.show();
                });
            });
        });
    </script>
@endpush
