@extends('layouts.app')

@section('title', 'Expense Heads')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Expense Heads</h4>
                    <h6>Manage expense heads and categories.</h6>
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
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Expense
                </a>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row mb-3">
                @if (session('success'))
                <div class="alert alert-success d-flex align-items-center justify-content-between" expense="alert">
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
                <div class="alert alert-danger alert-dismissible fade show" expense="alert">
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
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th class="text-center">Status</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>
                                    {{ $expense->category }}
                                </td>
                                <td>{{ $expense->item }}</td>
                                <td class="text-wrap">
                                   {{ $expense->description }}
                                </td>
                                <td>
                                    @if($expense->is_active)
                                        <span class="p-1 pe-2 rounded-1 text-primary bg-success-transparent fs-10">
                                        <i class="ti ti-check me-1 fs-11"></i> Active
                                    </span>
                                    @else
                                        <span class="p-1 pe-2 rounded-1 text-danger bg-danger-transparent fs-10">
                                        <i class="ti ti-ban me-1 fs-11"></i> Blocked
                                    </span>
                                    @endif
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="{{ route('expenses.toggle', $expense) }}"
                                           class="me-2 d-flex align-items-center p-2 border rounded"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Toggle Status">
                                            <i class="ti ti-shield"></i>
                                        </a>

                                        <a href="javascript:void(0)"
                                           class="me-2 d-flex align-items-center p-2 border rounded edit-expense"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Expense"
                                           data-expense-id="{{ $expense->id }}"
                                           data-expense-name="{{ $expense->item }}">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        @if(Auth::user()->hasRole('admin'))
                                        <a href="javascript:void(0);"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Expense"
                                           data-expense-id="{{ $expense->id }}"
                                           data-expense-name="{{ $expense->item }}"
                                           class="p-2 open-delete-modal"
                                           onclick="(new bootstrap.Modal(document.getElementById('delete-modal'))).show();">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" id="delete{{ $expense->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('expenses.store') }}" class="needs-validation" novalidate method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                    <select class="select" id="category" name="category" required>
                                        @foreach($categories as $row)
                                            <option value="{{ $row }}">{{ $row }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Expense category is required.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="item" class="form-label">Item Name<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" id="item" name="item" required placeholder="Facility Rent or Lease">
                                    <div class="invalid-feedback">
                                        Item name must be provided.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"
                                              rows="4" placeholder="Detailed description of the expense..."></textarea>
                                </div>

                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Is Active</label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit expense Modal -->
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editexpenseModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editexpenseForm" action="" class="needs-validation" novalidate method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editexpenseModalLabel">Edit expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" id="edit-expense-id" name="id" value="">
                                <div class="mb-3">
                                    <label for="name" class="form-label">expense Name<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" id="edit-name" name="name" required>
                                    <div class="invalid-feedback">
                                        expense name is required.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- expense Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Expense</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this expense?
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
                    table.column(0).search(selected).draw();
                });
            }
        });
    </script>
    <script>
        $(function(){
            $('.expenses-data').on('click', function() {
                var expenseId = $(this).data('expense-id');
                var expenseName = $(this).data('expense-name');

                // Show loading spinner in modal body
                $('#attachexpensesModal .modal-body').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');
                $('#attachexpensesModalLabel').text('Attach expenses - ' + expenseName);

                // Open the modal
                var modal = new bootstrap.Modal(document.getElementById('attachExpensesModal'));
                modal.show();

                // Fetch the attached expenses via AJAX
                $.ajax({
                    url: '/admin/expenses/' + expenseId + '/expenses',
                    method: 'GET',
                    success: function(data) {
                        $('#attachexpensesModalLabel').text('Attach expenses - ' + data.expense_name);
                        $('#attachexpensesModal .modal-body').html(data.html);
                    },
                    error: function() {
                        $('#attachexpensesModal .modal-body').html('<div class="alert alert-danger">Failed to load expenses.</div>');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-expense').forEach(function(button) {
                button.addEventListener('click', function() {
                    var expenseId = this.getAttribute('data-expense-id');
                    var expenseName = this.getAttribute('data-expense-name');
                    // Set the form action dynamically
                    var form = document.getElementById('editexpenseForm');
                    form.action = '/admin/expenses/' + expenseId;

                    // Set hidden and visible values
                    document.getElementById('edit-expense-id').value = expenseId;
                    document.getElementById('edit-name').value = expenseName;
                    // Update modal title (optional)
                    document.getElementById('editexpenseModalLabel').textContent = "Edit expense - " + expenseName;

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById('editexpenseModal'));
                    modal.show();
                });
            });
        });
    </script>
@endpush
