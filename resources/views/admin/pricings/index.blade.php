@extends('layouts.app')

@section('title', 'Pricing Plans')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">FlockSense Pricing Plans</h4>
                    <h6>Manage, compare, and extend SaaS pricing for poultry farm automation.</h6>
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
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPricingModal">
                    <i class="ti ti-circle-plus me-1"></i> Add Pricing
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
                    <select id="intervalFilter" class="form-select">
                        <option value="">All Billing Intervals</option>
                        @foreach(['monthly','yearly','weekly','one_time'] as $interval)
                            <option value="{{ $interval }}">{{ ucfirst($interval) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover datatable-custom align-middle">
                        <thead class="thead-light">
                        <tr>
                            <th>Plan</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Interval</th>
                            <th class="text-center">Trial</th>
                            <th class="text-center">Limits</th>
                            <th class="text-center">Features</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Sort</th>
                            <th class="text-center">Created</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pricings as $plan)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $plan->name }}</span>
                                    @if(isset($plan->meta['badge']) && $plan->meta['badge'])
                                        <span class="badge bg-primary ms-1">{{ $plan->meta['badge'] }}</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $plan->description }}</small>
                                </td>
                                <td class="text-center fs-12">
                                    <span class="fw-semibold">{{ number_format($plan->price, 2) }} {{ $plan->currency }}</span>
                                </td>
                                <td class="text-center">{{ ucfirst($plan->billing_interval) }}</td>
                                <td class="text-center">
                                    {{ $plan->trial_period_days ? $plan->trial_period_days.' days' : '-' }}
                                </td>
                                <td class="text-center">
                                    <span title="Farms" class="me-2"><i class="ti ti-home"></i> {{ $plan->max_farms }}</span>
                                    <span title="Sheds" class="me-2"><i class="ti ti-building"></i> {{ $plan->max_sheds }}</span>
                                    <span title="Flocks" class="me-2"><i class="ti ti-feather"></i> {{ $plan->max_flocks }}</span>
                                    <span title="Devices" class="me-2"><i class="ti ti-cpu"></i> {{ $plan->max_devices }}</span>
                                    <span title="Users"><i class="ti ti-users"></i> {{ $plan->max_users }}</span>
                                </td>
                                <td class="text-center">
                                    @php $ff = $plan->feature_flags ?? []; @endphp
                                    <span class="badge {{ !empty($ff['auto_control']) && $ff['auto_control'] ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="ti ti-power"></i> Auto
                                </span>
                                    <span class="badge {{ !empty($ff['reporting']) && $ff['reporting'] ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="ti ti-chart-bar"></i> Reports
                                </span>
                                    <span class="badge {{ !empty($ff['analytics']) && $ff['analytics'] ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="ti ti-database"></i> Analytics
                                </span>
                                    @if(isset($ff['support']))
                                        <span class="badge bg-info text-dark"><i class="ti ti-headphones"></i> {{ ucfirst(str_replace('_', ' ', $ff['support'])) }}</span>
                                    @endif
                                    @if(isset($ff['history_days']))
                                        <span class="badge bg-warning text-dark" title="Data History"><i class="ti ti-calendar"></i> {{ $ff['history_days'] }}d</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($plan->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $plan->sort_order }}</td>
                                <td class="text-center">{{ $plan->created_at->format('d-m-Y') }}</td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="javascript:void(0)"
                                           class="me-2 d-flex align-items-center p-2 border rounded edit-pricing"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Plan"
                                           data-pricing-id="{{ $plan->id }}"
                                           data-pricing-name="{{ $plan->name }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Plan"
                                           data-pricing-id="{{ $plan->id }}"
                                           data-pricing-name="{{ $plan->name }}"
                                           class="p-2 open-delete-modal">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('pricings.destroy', $plan->id) }}" method="POST" id="delete{{ $plan->id }}">
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
    @include('admin.pricings._add_modal')

    <!-- Edit Pricing Modal -->
    @include('admin.pricings._edit_modal')

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Pricing Plan</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this plan?
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
                    var updateUrl = '/admin/pricings/' + pricingId;

                    document.getElementById('editPricingModalLabel').textContent = "Edit Plan - " + pricingName;

                    $.get('/admin/pricings/' + pricingId, function(plan) {
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
