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

        {{-- Filter Card --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('farm.expenses.index') }}" class="row g-3 align-items-end">
                    {{-- Farm Filter --}}
                    <div class="col-md-3">
                        <label class="form-label">Farm</label>
                        <select name="farm_id" id="farmFilter" class="form-select">
                            <option value="">All Farms</option>
                            @foreach($farms as $farm)
                                <option value="{{ $farm->id }}" {{ request('farm_id') == $farm->id ? 'selected' : '' }}>
                                    {{ $farm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Shed Filter --}}
                    <div class="col-md-3">
                        <label class="form-label">Shed</label>
                        <select name="shed_id" id="shedFilter" class="form-select">
                            <option value="">All Sheds</option>
                            @foreach($sheds as $shed)
                                <option value="{{ $shed->id }}" {{ request('shed_id') == $shed->id ? 'selected' : '' }}>
                                    {{ $shed->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    {{-- Date To --}}
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
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

        {{-- Expenses Table --}}
        @php
            $isPaginatedExpenses = $expenses instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
            $fromItem = $isPaginatedExpenses ? ($expenses->firstItem() ?? 0) : ($expenses->isEmpty() ? 0 : 1);
            $toItem = $isPaginatedExpenses ? ($expenses->lastItem() ?? 0) : $expenses->count();
            $totalItems = $isPaginatedExpenses ? $expenses->total() : $expenses->count();
        @endphp
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set flex-grow-1">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                        <input type="text" id="expenseTableSearch" class="form-control" placeholder="Search expenses">
                    </div>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center row-gap-3">
                    <span class="text-muted small">
                        Showing {{ $fromItem }} - {{ $toItem }} of {{ $totalItems }} expenses
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="farmExpensesTable" class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 8%">Date</th>
                            <th style="width: 12%">Farm</th>
                            <th style="width: 10%">Shed</th>
                            <th style="width: 10%">Flock</th>
                            <th style="width: 15%">Expense Head</th>
                            <th style="width: 15%">Description</th>
                            <th style="width: 8%" class="text-end">Quantity</th>
                            <th style="width: 8%" class="text-end">Unit Cost</th>
                            <th style="width: 10%" class="text-end">Amount</th>
                            <th style="width: 4%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($expenses as $expense)
                            <tr data-searchable="true">
                                <td>
                                    <div class="small">{{ $expense->expense_date->format('d-m-Y') }}</div>
                                    <div class="small text-muted">{{ $expense->expense_date->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $expense->farm->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $expense->shed->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $expense->flock->name ?? '-' }}</div>
                                </td>
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
                                <td>
                                    <div class="small">{{ $expense->description ?? '-' }}</div>
                                    @if($expense->reference_no)
                                        <div class="small text-muted">
                                            <code>{{ $expense->reference_no }}</code>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($expense->quantity)
                                        <div class="small">{{ number_format($expense->quantity, 2) }}</div>
                                        <div class="small text-muted">{{ $expense->unit }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($expense->unit_cost)
                                        <div class="small">{{ number_format($expense->unit_cost, 2) }}</div>
                                        <div class="small text-muted">{{ $expense->currency }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">{{ number_format($expense->amount, 2) }}</div>
                                    <div class="small text-muted">{{ $expense->currency }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('farm.expenses.edit', $expense) }}">
                                                    <i class="ti ti-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger open-delete-modal" href="javascript:void(0)"
                                                   data-expense-id="{{ $expense->id }}"
                                                   data-expense-desc="{{ $expense->description }}">
                                                    <i class="ti ti-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <form action="{{ route('farm.expenses.destroy', $expense->id) }}" method="POST" id="delete{{ $expense->id }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    No expenses found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                        @if($expenses->count())
                            <tr class="search-empty-row d-none">
                                <td colspan="10" class="text-center py-4 text-muted">
                                    No expenses match your search.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-top-0 d-flex flex-wrap justify-content-between align-items-center row-gap-2">
                <div class="text-muted small">
                    Showing {{ $fromItem }} - {{ $toItem }} of {{ $totalItems }} expenses
                </div>
                <div>
                    @if($isPaginatedExpenses)
                        {{ $expenses->onEachSide(1)->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <span class="delete-icon">
                        <i data-feather="x-circle"></i>
                    </span>
                    <h4 class="mt-3">Confirm Deletion</h4>
                    <p class="delete-message">Are you sure you want to delete this expense?</p>
                    <div class="d-flex justify-content-center mt-4">
                        <a href="javascript:void(0);" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</a>
                        <a href="javascript:void(0);" class="btn btn-danger confirm-delete-btn">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('open-delete-modal') || e.target.closest('.open-delete-modal')) {
                e.preventDefault();
                e.stopPropagation();

                const button = e.target.classList.contains('open-delete-modal') ? e.target : e.target.closest('.open-delete-modal');
                const expenseId = button.getAttribute('data-expense-id');
                const expenseDesc = button.getAttribute('data-expense-desc');

                document.querySelector('.delete-message').textContent = `Are you sure you want to delete expense: ${expenseDesc || 'this expense'}?`;

                const confirmBtn = document.querySelector('.confirm-delete-btn');
                confirmBtn.onclick = function() {
                    document.getElementById('delete' + expenseId).submit();
                };

                const deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
                deleteModal.show();
            }
        });

        const searchInput = document.getElementById('expenseTableSearch');
        const table = document.getElementById('farmExpensesTable');

        if (searchInput && table) {
            const searchableRows = table.querySelectorAll('tbody tr[data-searchable="true"]');
            const emptyRow = table.querySelector('tbody .search-empty-row');

            searchInput.addEventListener('input', function() {
                const term = this.value.trim().toLowerCase();
                let visibleCount = 0;

                searchableRows.forEach(function(row) {
                    const rowText = row.textContent.toLowerCase();
                    const shouldHide = term.length && !rowText.includes(term);
                    row.classList.toggle('d-none', shouldHide);

                    if (!shouldHide) {
                        visibleCount++;
                    }
                });

                if (emptyRow) {
                    const showEmptyState = term.length && visibleCount === 0;
                    emptyRow.classList.toggle('d-none', !showEmptyState);
                }
            });
        }

        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
