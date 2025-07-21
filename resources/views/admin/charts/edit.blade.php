@extends('layouts.app')

@section('title', "Edit - {{ $chart->chart_name }}")

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Edit - {{ $chart->chart_name }}</h4>
                    <h6>Edit standard and its data.</h6>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>{{ $chart->chart_name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('charts.update', $chart) }}"
                      class="row g-3 needs-validation" novalidate
                      method="POST">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="chart_name" class="form-label">Chart Name <span class="text-danger">*</span></label>
                            <input type="text" name="chart_name" id="chart_name" class="form-control"
                                   value="{{ old('chart_name', $chart->chart_name) }}" required>
                            <div class="invalid-feedback">
                                You have to name baseline data for identification.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
                            <input type="text" name="source" id="source" class="form-control"
                                   value="{{ old('source', $chart->source) }}" required>
                            <div class="invalid-feedback">
                                Please mention the source for baseline data.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" name="unit" id="unit" class="form-control"
                                   value="{{ old('unit', $chart->unit) }}" required>
                            <div class="invalid-feedback">
                                Please mention recording unit ( g, Kg etc.).
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      rows="3" placeholder="Description">{{ old('description', $chart->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="settings" class="form-label">Settings</label>
                            <textarea class="form-control" name="settings" id="settings"
                                      rows="3" placeholder="Settings in JSON Format">{{ old('settings', $chart->settings) }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Save Chart Credentials</button>
                        <a href="{{ route('charts.index') }}" class="btn btn-warning me-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">

            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <h4>{{ $chart->chart_name }} - Data</h4>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th>Type</th>
                            <th>Day</th>
                            <th>Weight</th>
                            <th>Daily Gain</th>
                            <th>Avg Daily Gain</th>
                            <th>Daily Intake</th>
                            <th>Cumulative Intake</th>
                            <th>FCR</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($chart->data as $row)
                            <tr>
                                <td>
                                    <input type="text" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="type"
                                           value="{{ $row->type }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="day"
                                           value="{{ $row->day }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="weight"
                                           value="{{ $row->weight }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="daily_gain"
                                           value="{{ $row->daily_gain }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="avg_daily_gain"
                                           value="{{ $row->avg_daily_gain }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="daily_intake"
                                           value="{{ $row->daily_intake }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="cum_intake"
                                           value="{{ $row->cum_intake }}">
                                </td>
                                <td>
                                    <input type="number" step="any" class="form-control live-update"
                                           data-id="{{ $row->id }}" data-field="fcr"
                                           value="{{ $row->fcr }}">
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
                    "fixedHeader": true,
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
                    table.column(1).search(selected).draw();
                });
                $('#statusFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(5).search(selected).draw();
                });
            }
        });
    </script>
    <script>
        document.querySelectorAll('.live-update').forEach(function(input) {
            input.addEventListener('input', function(e) {
                let id = e.target.dataset.id;
                let field = e.target.dataset.field;
                let value = e.target.value;

                fetch('{{ route('charts.data.update') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        field: field,
                        value: value
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        // Optionally show a notification or update UI
                        if(data.success){
                            e.target.classList.add('bg-success-transparent');
                        } else {
                            e.target.classList.add('bg-success-transparent');
                        }
                    })
                    .catch(error => {
                        e.target.classList.add('bg-danger-transparent');
                    });
            });
        });
    </script>
@endpush
