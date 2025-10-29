@extends('layouts.app')

@section('title', 'Partners')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">FlockSense Partners</h4>
                    <h6>Manage partners information.</h6>
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
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                    <i class="ti ti-circle-plus me-1"></i> Add Partner
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
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover datatable-custom align-middle">
                        <thead class="thead-light">
                        <tr>
                            <th>Company</th>
                            <th class="text-center">URL</th>
                            <th class="text-center">Introduction</th>
                            <th class="text-center">Partnership Detail</th>
                            <th class="text-center">Keywords</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Sort</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($partners as $partner)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $partner->company_name }}</span>
                                </td>
                                <td class="fs-11">
                                    <a href="{{ $partner->url }}" target="_blank">{{ $partner->url }}</a>
                                </td>
                                <td>{{ $partner->introduction }}</td>
                                <td>
                                    {{ $partner->partnership_detail }}
                                </td>
                                <td class="text-center">

                                </td>
                                <td class="text-center">
                                    @if($partner->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $partner->sort_order }}</td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="javascript:void(0)"
                                           class="me-2 d-flex align-items-center p-2 border rounded edit-pricing"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Plan"
                                           data-pricing-id="{{ $partner->id }}"
                                           data-pricing-name="{{ $partner->company_name }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Plan"
                                           data-pricing-id="{{ $partner->id }}"
                                           data-pricing-name="{{ $partner->company_name }}"
                                           class="p-2 open-delete-modal">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('pricing-plans.destroy', $partner->id) }}" method="POST" id="delete{{ $partner->id }}">
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

    <!-- Add Pricing Modal -->
    @include('admin.partners._add_modal')

    <!-- Edit Pricing Modal -->
    @include('admin.partners._edit_modal')

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Partner</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this partner?
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
            if($('.datatable-custom').length > 0) {
                var table = $('.datatable-custom').DataTable({
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    "ordering": true,
                    "order": [[7, 'asc']],
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

                $('#intervalFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(2).search(selected).draw();
                });
            }
        });
        // Edit modal JS (to be filled by AJAX if needed)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-pricing').forEach(function(button) {
                button.addEventListener('click', function() {
                    var pricingId = this.getAttribute('data-pricing-id');
                    var pricingName = this.getAttribute('data-pricing-name');

                    var form = document.getElementById('editPricingForm');
                    var updateUrl = '/admin/pricing-plans/' + pricingId;

                    document.getElementById('editPricingModalLabel').textContent = "Edit Plan - " + pricingName;

                    $.get('/admin/pricing-plans/' + pricingId, function(plan) {
                        // Populate fields
                        fillEditPricingModal(plan, updateUrl);

                        var modal = new bootstrap.Modal(document.getElementById('editPricingModal'));
                        modal.show();
                    });
                });
            });
        });
        // Delete modal logic
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;
            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-pricing-id');
                    const planName = this.getAttribute('data-pricing-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${planName}" plan?`;

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
