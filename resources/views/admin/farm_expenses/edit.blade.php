@extends('layouts.app')

@section('title', 'Edit Farm Expense')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Edit Farm Expense</h4>
                    <h6>Update the existing expense details.</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('farm.expenses.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Expenses
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="feather-alert-triangle me-2"></i>Please fix the following issues:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fas fa-xmark"></i>
                        </button>
                    </div>
                @endif

                <form action="{{ route('farm.expenses.update', $farmExpense) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    @include('admin.farm_expenses.partials.form-fields', ['farmExpense' => $farmExpense])

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('farm.expenses.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-device-floppy me-1"></i>Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('admin.farm_expenses.partials.form-scripts')
