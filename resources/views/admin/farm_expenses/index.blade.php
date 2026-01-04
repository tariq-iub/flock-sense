@extends('layouts.app')

@section('title', 'Farm Expenses')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Farm Expenses</h4>
                    <h6>Manage farm expenses and track spending.</h6>
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
                <a href="{{ route('farm.expenses.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>Add Expense
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

                @if (session('error'))
                    <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
                        <div>
                            <i class="feather-alert-triangle flex-shrink-0 me-2"></i>
                            {{ session('error') }}
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

        @php
            // Support both plain query params and Spatie QueryBuilder style `filter[...]`
            $farmSelected = request('filter.farm_id', request('farm_id'));
            $shedSelected = request('filter.shed_id', request('shed_id'));
            $headSelected = request('filter.head_id', request('head_id'));
            $dateFrom = request('filter.date_from', request('date_from'));
            $dateTo = request('filter.date_to', request('date_to'));
        @endphp

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form id="expenseFiltersForm"
                      method="GET"
                      action="{{ route('farm.expenses.index') }}"
                      class="row g-3 align-items-end">

                    <div class="col-md-2">
                        <select id="farmFilter" name="filter[farm_id]" class="form-select">
                            <option value="">All Farms</option>
                            @foreach($farms as $farm)
                                <option value="{{ $farm->id }}" {{ (string)$farmSelected === (string)$farm->id ? 'selected' : '' }}>
                                    {{ $farm->name ?? $farm->title ?? ('Farm #' . $farm->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select id="shedFilter" name="filter[shed_id]" class="form-select">
                            <option value="">All Sheds</option>
                            @foreach($sheds as $shed)
                                <option value="{{ $shed->id }}" {{ (string)$shedSelected === (string)$shed->id ? 'selected' : '' }}>
                                    {{ $shed->name ?? $shed->title ?? ('Shed #' . $shed->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select id="headFilter" name="filter[head_id]" class="form-select">
                            <option value="">All Expense Heads</option>
                            @foreach($expenseHeadGroups as $category => $items)
                                <optgroup label="{{ $category }}">
                                    @foreach($items as $head)
                                        <option value="{{ $head->id }}" {{ (string)$headSelected === (string)$head->id ? 'selected' : '' }}>
                                            {{ $head->item }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" id="dateFrom" name="filter[date_from]" class="form-control" value="{{ $dateFrom }}" />
                    </div>

                    <div class="col-md-2">
                        <input type="date" id="dateTo" name="filter[date_to]" class="form-control" value="{{ $dateTo }}" />
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-success w-50">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('farm.expenses.index') }}" class="btn btn-outline-secondary w-50">
                            Clear
                        </a>
                    </div>
                </form>
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
                        <option value="">All Flocks</option>
                        @foreach($flocks as $flock)
                            <option value="{{ $flock->name }}">{{ $flock->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Farm</th>
                            <th>Shed</th>
                            <th>Flock</th>
                            <th>Expense Head</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" class="fw-semibold text-info text-decoration-none show-expense"
                                       data-expense-id="{{ $expense->id }}">
                                        #{{ str_pad($expense->id, 3, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td>
                                    <div class="small">{{ optional($expense->expense_date)->format('d-m-Y') ?? '-' }}</div>
                                    @if($expense->expense_date)
                                        <div class="small text-muted">{{ $expense->expense_date->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td>{{ $expense->farm->name ?? $expense->farm->title ?? '-' }}</td>
                                <td>{{ $expense->shed->name ?? $expense->shed->title ?? '-' }}</td>
                                <td>{{ $expense->flock->name ?? $expense->flock->code ?? '-' }}</td>
                                <td>
                                    @if($expense->head)
                                        <div class="small fw-semibold">{{ $expense->head->item }}</div>
                                        <div class="small text-muted">
                                            <span class="badge bg-info-subtle text-secondary-emphasis">
                                                {{ $expense->head->category }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-wrap">
                                    {{ $expense->description ?? '-' }}
                                    @if(!empty($expense->reference_no))
                                        <div class="small text-muted"><code>{{ $expense->reference_no }}</code></div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">{{ number_format((float)($expense->amount ?? 0), 2) }}</div>
                                    @if(!empty($expense->currency))
                                        <div class="small text-muted">{{ $expense->currency }}</div>
                                    @endif
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="{{ route('farm.expenses.edit', $expense) }}"
                                           class="me-2 d-flex align-items-center p-2 border rounded"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           data-bs-original-title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           data-bs-original-title="Delete"
                                           data-expense-id="{{ $expense->id }}"
                                           data-expense-name="{{ $expense->description ?? ('Expense #' . $expense->id) }}"
                                           class="p-2 open-delete-modal">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>

                                        <form action="{{ route('farm.expenses.destroy', $expense->id) }}" method="POST" id="delete{{ $expense->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    No expenses found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
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

    <!-- Expense Detail Modal -->
    <div class="modal fade" id="expense-detail-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="expenseDetailTitle">Expense Detail</h5>
                        <p class="mb-0 small text-muted" id="expenseDetailSub"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Expense ID</span>
                                    <span class="fw-semibold" id="expenseDetailId"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Date</span>
                                    <span class="fw-semibold" id="expenseDetailDate"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Farm</span>
                                    <span class="fw-semibold" id="expenseDetailFarm"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Shed</span>
                                    <span class="fw-semibold" id="expenseDetailShed"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-0">
                                    <span class="text-muted small">Flock</span>
                                    <span class="fw-semibold" id="expenseDetailFlock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Expense Head</span>
                                    <span class="fw-semibold" id="expenseDetailHead"></span>
                                    <span class="badge bg-info-subtle text-secondary-emphasis float-end" id="expenseDetailCategory"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Quantity</span>
                                    <span class="fw-semibold" id="expenseDetailQuantity"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Unit Cost</span>
                                    <span class="fw-semibold" id="expenseDetailUnitCost"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Amount</span>
                                    <span class="fw-bold text-success" id="expenseDetailAmount"></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Reference</span>
                                    <span class="fw-semibold" id="expenseDetailReference"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <div class="text-muted small mb-2">Description</div>
                                <div id="expenseDetailDescription" class="mb-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-success me-2" id="expenseDetailEdit">
                        <i class="ti ti-edit me-2"></i>Edit Expense
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            if ($('.datatable-custom').length > 0) {
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
                    initComplete: (settings, json) => {
                        $('.dataTables_filter').appendTo('.search-input');
                    },
                });

                // Optional: keep table search box behavior consistent
                $('.search-input').on('click', function() {
                    $('.dataTables_filter input').trigger('focus');
                });

                $('#statusFilter').on('change', function() {
                    const selectedText = $(this).find('option:selected').text();
                    const hasValue = $(this).val() !== '';
                    const pattern = hasValue ? '^' + $.fn.dataTable.util.escapeRegex(selectedText) + '$' : '';
                    table.column(4).search(pattern, true, false).draw(); // Flock column
                });
            }

            // Auto-submit server-side filters
            $('#farmFilter, #shedFilter, #headFilter, #dateFrom, #dateTo').on('change', function() {
                $('#expenseFiltersForm').submit();
            });

            // Delete modal
            let deleteId = null;
            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-expense-id');
                    const expenseName = this.getAttribute('data-expense-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${expenseName}" data?`;

                    var modal = new bootstrap.Modal(document.getElementById('delete-modal'));
                    modal.show();
                });
            });

            document.getElementById('confirm-delete-btn')?.addEventListener('click', function() {
                if (deleteId) {
                    document.getElementById('delete' + deleteId).submit();
                }
            });

            // Expense detail modal
            const detailModalEl = document.getElementById('expense-detail-modal');
            const detailModal = detailModalEl ? new bootstrap.Modal(detailModalEl) : null;

            const formatDate = (value) => {
                if (!value) return '-';
                const d = new Date(value);
                if (Number.isNaN(d.getTime())) return value;
                const day = String(d.getDate()).padStart(2, '0');
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const year = d.getFullYear();
                return `${day}-${month}-${year}`;
            };

            $('.show-expense').on('click', function() {
                const expenseId = $(this).data('expense-id');
                if (!expenseId || !detailModal) return;

                // Reset placeholders
                $('#expenseDetailTitle').text('Expense Detail');
                $('#expenseDetailSub').text('');
                $('#expenseDetailId').text('');
                $('#expenseDetailDate').text('');
                $('#expenseDetailFarm').text('');
                $('#expenseDetailShed').text('');
                $('#expenseDetailFlock').text('');
                $('#expenseDetailHead').text('');
                $('#expenseDetailCategory').text('');
                $('#expenseDetailQuantity').text('');
                $('#expenseDetailUnitCost').text('');
                $('#expenseDetailAmount').text('');
                $('#expenseDetailReference').text('');
                $('#expenseDetailDescription').text('');
                $('#expenseDetailEdit').attr('href', '#');

                fetch(`/admin/farm-expenses/${expenseId}`)
                    .then(response => response.json())
                    .then(data => {
                        const padId = String(data.id).padStart(3, '0');
                        $('#expenseDetailTitle').text(`Expense #${padId}`);
                        $('#expenseDetailSub').text(data.description || 'No description added');
                        $('#expenseDetailId').text(`#${padId}`);
                        $('#expenseDetailDate').text(formatDate(data.expense_date));
                        $('#expenseDetailFarm').text(data.farm?.name || '-');
                        $('#expenseDetailShed').text(data.shed?.name || '-');
                        $('#expenseDetailFlock').text(data.flock?.name || '-');
                        $('#expenseDetailHead').text(data.head?.item || '-');
                        $('#expenseDetailCategory').text(data.head?.category || '').toggle(!!data.head?.category);
                        $('#expenseDetailQuantity').text(data.quantity ? `${Number(data.quantity).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${data.unit || ''}` : '-');
                        $('#expenseDetailUnitCost').text(data.unit_cost ? `${Number(data.unit_cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${data.currency || ''}` : '-');
                        $('#expenseDetailAmount').text(data.amount ? `${Number(data.amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${data.currency || ''}` : '-');
                        $('#expenseDetailReference').text(data.reference_no || '-');
                        $('#expenseDetailDescription').text(data.description || '-');
                        $('#expenseDetailEdit').attr('href', `/admin/farm-expenses/${data.id}/edit`);
                        detailModal.show();
                    })
                    .catch(() => {
                        $('#expenseDetailTitle').text('Expense Detail');
                        $('#expenseDetailSub').text('Unable to load expense details right now.');
                        detailModal.show();
                    });
            });
        });
    </script>
@endpush
