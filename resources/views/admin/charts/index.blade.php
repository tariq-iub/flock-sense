@extends('layouts.app')

@section('title', 'System Users')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Baseline Data</h4>
                    <h6>Manage daily performance baseline and benchmarking data.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importChartModal">
                    <i class="ti ti-circle-plus me-1"></i>Import
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="container mb-3">
                <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert">
                    <div>
                        <i class="feather-check-circle flex-shrink-0 me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="container mb-3">
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
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center row-gap-3">
                    <select id="sourceFilter" class="form-select me-2">
                        <option value="">All Sources</option>
                        @foreach($sources as $source)
                            <option value="{{ $source }}">{{ $source }}</option>
                        @endforeach
                    </select>

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
                            <th class="no-sort">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Chart Name</th>
                            <th>Source</th>
                            <th>Description</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Settings</th>
                            <th class="text-center">Active</th>
                            <th class="text-center">Data</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($charts as $chart)
                            <tr>
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox" value="{{ $chart->id }}">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{ $chart->chart_name }}
                                    </div>
                                </td>
                                <td>{{ $chart->source }}</td>
                                <td>{{ $chart->description }}</td>
                                <td class="text-center">
                                    {{ $chart->unit }}
                                </td>
                                <td class="text-center">
                                    @if($chart->settings == null)
                                        ...
                                    @else
                                        View
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($chart->is_active)
                                    <span class="p-1 pe-2 rounded-1 text-success bg-success-transparent fs-10">
                                        <i class="ti ti-check me-1 fs-11"></i> Active
                                    </span>
                                    @else
                                        <span class="p-1 pe-2 rounded-1 text-danger bg-danger-transparent fs-10">
                                        <i class="ti ti-ban me-1 fs-11"></i> Blocked
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info chart-data" data-chart-id="{{ $chart->id }}"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="View Chart Data">
                                       View
                                    </a>
                                </td>
                                <td class="action-table-data">
                                    <div class="edit-delete-action">
                                        <a class="me-2 edit-icon  p-2" href="product-details.html">
                                            <i data-feather="eye" class="feather-eye"></i>
                                        </a>
                                        <a class="me-2 p-2" href="edit-product.html" >
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete-modal"
                                           data-chart-id="{{ $chart->id }}" data-chart-name="{{ $chart->chart_name }}" class="p-2 open-delete-modal">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('charts.destroy', $chart) }}" method="POST" id="delete{{ $chart->id }}">
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

    <!-- Import Chart Modal -->
    <div class="modal fade" id="importChartModal" tabindex="-1" aria-labelledby="importChartModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('import.chart') }}" class="row g-3 needs-validation" novalidate
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importChartModalLabel">Import Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="chart_name" class="form-label">Chart Name <span class="text-danger">*</span></label>
                            <input type="text" name="chart_name" id="chart_name" class="form-control" required>
                            <div class="invalid-feedback">
                                You have to name baseline data for identification.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
                            <input type="text" name="source" id="source" class="form-control" required>
                            <div class="invalid-feedback">
                                Please mention the source for baseline data.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" name="unit" id="unit" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="settings" class="form-label">Settings</label>
                            <textarea class="form-control" name="settings" id="settings" placeholder="Settings in JSON Format"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="data" class="form-label">Select Excel File <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                            <div class="invalid-feedback">
                                Select the file containing data for baseline validation.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chart Data Modal -->
    <div class="modal fade" id="chartDataModal" tabindex="-1" aria-labelledby="chartDataModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartDataModalLabel">Chart Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="chart-details-loading" class="text-center py-5">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <div id="chart-details-content" style="display:none;">
                        <!-- Chart data table will be injected here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Chart</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this chart and its data?
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
            $('.chart-data').on('click', function() {
                var chartId = $(this).data('chart-id');
                $('#chart-details-loading').show();
                $('#chart-details-content').hide();
                $('#viewChartModalLabel').text('Loading...');

                // Open the modal
                var modal = new bootstrap.Modal(document.getElementById('chartDataModal'));
                modal.show();

                // Fetch chart data from API
                $.ajax({
                    url: '/admin/charts/data/' + chartId,
                    type: 'GET',
                    success: function(chart) {
                        $('#chartDataModalLabel').text(chart.title);
                        $('#chart-details-content').html(chart.data);
                        $('#chart-details-loading').hide();
                        $('#chart-details-content').show();
                    },
                    error: function() {
                        $('#chartDataModalLabel').text('Chart Data Not Found');
                        $('#chart-details-loading').hide();
                        $('#chart-details-content').html('<div class="alert alert-danger">Failed to load chart data.</div>').show();
                    }
                });
            });

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

                $('#sourceFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(2).search(selected).draw();
                });
                $('#statusFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(6).search(selected).draw();
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteChartId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteChartId = this.getAttribute('data-chart-id');
                    const chartName = this.getAttribute('data-chart-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${chartName}" and its data?`;
                });
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (deleteChartId) {
                    document.getElementById('delete' + deleteChartId).submit();
                }
            });
        });
    </script>

@endpush
