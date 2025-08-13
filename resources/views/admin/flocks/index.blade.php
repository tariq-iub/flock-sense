@extends('layouts.app')

@section('title', 'Flocks')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Flocks</h4>
                    <h6>Manage flock and their cycle information.</h6>
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
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">All Sheds</div>
                        <input id="shedSearch" type="text" class="form-control form-control-sm w-50" placeholder="Search Shed">
                    </div>
                    <div class="card-body">
                        <div class="list-group" id="shedList">
                            @foreach($sheds as $shed)
                                <a href="javascript:void(0)"
                                   class="list-group-item list-group-item-action d-flex w-100 justify-content-between shed-item"
                                   data-shed-id="{{ $shed->id }}">
                                    <div>
                                        <h5 class="text-info fs-14 mb-1">
                                            <i class="bi bi-building-fill me-2"></i>
                                            {{ $shed->name }}
                                        </h5>
                                        <small class="text-body-secondary">
                                            Farm: {{ $shed->farm->name }}<br>
                                            Owner: {{ $shed->farm->owner->name }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($shed->latestFlock)
                                            <span class="text-info fs-12 mb-1">{{ $shed->latestFlock->name }}</span>
                                            <div class="text-body-secondary fs-10">
                                                Started on {{ $shed->latestFlock->start_date->format('d-m-Y') }}<br>
                                                Age is {{ $shed->latestFlock->age }} Days
                                            </div>
                                        @else
                                            <span class="badge bg-soft-danger">No Flock Cycled</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div id="noResults" class="text-danger small px-3 py-2 d-none">No sheds found.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8" id="flockDetailsContainer">

            </div>
        </div>
    </div>

    {{-- Reusable Edit Shed Modal --}}
    <div class="modal fade" id="editShedModal" tabindex="-1" aria-labelledby="editShedModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editShedForm" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editShedModalLabel">Edit Shed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_shed_id">

                        <div class="row">
                            {{-- Shed Name --}}
                            <div class="col-lg-6 mb-3">
                                <label for="edit_name" class="form-label">Shed Name <span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                                <div class="invalid-feedback">Shed name is required.</div>
                            </div>

                            {{-- Farm --}}
                            <div class="col-lg-6 mb-3">
                                <label for="edit_farm_id" class="form-label">Farm <span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select" id="edit_farm_id" name="farm_id" required>
                                    <option value="">Select Farm</option>
                                    {{-- options filled by JS --}}
                                </select>
                                <div class="invalid-feedback">Farm is required.</div>
                            </div>

                            {{-- Capacity --}}
                            <div class="col-lg-6 mb-3">
                                <label for="edit_capacity" class="form-label">Capacity <span class="text-danger ms-1">*</span></label>
                                <input type="number" id="edit_capacity" name="capacity" class="form-control" required>
                                <div class="invalid-feedback">Shed capacity is required to enter.</div>
                            </div>

                            {{-- Type --}}
                            <div class="col-lg-6 mb-3">
                                <label for="edit_type" class="form-label">Shed Type <span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select" id="edit_type" name="type" required>
                                    <option value="">Select Type</option>
                                    {{-- options filled by JS --}}
                                </select>
                                <div class="invalid-feedback">Shed type is required.</div>
                            </div>

                            {{-- Description --}}
                            <div class="col-lg-12 mb-3">
                                <label for="edit_description" class="form-label">Other Details</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success me-2">Update Shed</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
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
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Farm</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this farm data?
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

@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('shedSearch');
            const items = Array.from(document.querySelectorAll('#shedList .list-group-item'));
            const noResults = document.getElementById('noResults');

            function filter() {
                const q = input.value.trim().toLowerCase();
                let visible = 0;

                items.forEach(el => {
                    const text = el.textContent.replace(/\s+/g, ' ').toLowerCase();
                    // Match all tokens (supports multi-word queries like "ali farm-1")
                    const match = q.split(/\s+/).every(token => text.includes(token));
                    const hide = q && !match;
                    el.classList.toggle('d-none', hide);
                    if (!hide) visible++;
                });

                if (noResults) noResults.classList.toggle('d-none', visible > 0);
            }

            // Simple debounce
            let t;
            input.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(filter, 150);
            });

            // Initial state
            filter();
        });
    </script>
    <script>
        $(function(){
            $('#shedList').on('click', '.shed-item', function() {
                var shedId = $(this).data('shed-id');

                // Optionally, show a loading spinner
                $('#flockDetailsContainer').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');

                $.get('/admin/sheds/' + shedId + '/data', function(data) {
                    $('#flockDetailsContainer').html(data.html);

                    // If you use Bootstrap's JS tabs, initialize them
                    var triggerTabList = [].slice.call(document.querySelectorAll('#flockDetailsContainer .nav-link'));
                    triggerTabList.forEach(function (triggerEl) {
                        var tabTrigger = new bootstrap.Tab(triggerEl);
                    });
                }).fail(function() {
                    $('#flockDetailsContainer').html('<div class="alert alert-danger"><i class="feather-alert-triangle flex-shrink-0 me-2"></i>Failed to load shed flocks details.</div>');
                });
            });
        });
    </script>

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

                $('#cityFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(1).search(selected).draw();
                });

                $('#typeFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(3).search(selected).draw();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Blade-provided data (minimal & fast)
            const farmsData = @json($farms->map(fn($f) => ['id' => $f->id, 'name' => $f->name]));
            const typesData = @json($types); // e.g. ['broiler','breeder','layer']

            const form   = document.getElementById('editShedForm');
            const modalEl = document.getElementById('editShedModal');
            const modal  = new bootstrap.Modal(modalEl);

            const idEl   = document.getElementById('edit_shed_id');
            const nameEl = document.getElementById('edit_name');
            const farmEl = document.getElementById('edit_farm_id');
            const capEl  = document.getElementById('edit_capacity');
            const typeEl = document.getElementById('edit_type');
            const descEl = document.getElementById('edit_description');

            // Populate static selects once
            function populateFarms(selectedId = null) {
                farmEl.innerHTML = `<option value="">Select Farm</option>`;
                farmsData.forEach(f => {
                    farmEl.innerHTML += `<option value="${f.id}" ${Number(f.id) === Number(selectedId) ? 'selected' : ''}>${f.name}</option>`;
                });
            }
            function populateTypes(selected = null) {
                typeEl.innerHTML = `<option value="">Select Type</option>`;
                typesData.forEach(t => {
                    typeEl.innerHTML += `<option value="${t}" ${t === selected ? 'selected' : ''}>${t.charAt(0).toUpperCase()+t.slice(1)}</option>`;
                });
            }

            // Open modal handler (event delegation in case rows are dynamic)
            document.body.addEventListener('click', async (e) => {
                const btn = e.target.closest('.edit-shed-btn');
                if (!btn) return;

                const shedId = btn.getAttribute('data-shed-id');
                if (!shedId) return;

                // Fetch shed data from show endpoint
                const res = await fetch(`/admin/sheds/${shedId}`, { headers: { 'Accept': 'application/json' }});
                if (!res.ok) {
                    alert('Failed to load shed.');
                    return;
                }
                const shed = await res.json();

                // Fill fields
                idEl.value   = shed.id;
                nameEl.value = shed.name ?? '';
                capEl.value  = shed.capacity ?? '';
                descEl.value = shed.description ?? '';

                // Populate selects with selected values
                populateFarms(shed.farm_id ?? null);
                populateTypes(shed.type ?? null);

                // Set form action to update route
                form.action = `/admin/sheds/${shed.id}`;

                // If you use Select2 on these selects, re-init safely:
                $('#edit_farm_id, #edit_type').each(function(){
                  if ($(this).hasClass('select2-hidden-accessible')) $(this).select2('destroy');
                  $(this).select2({ dropdownParent: $('#editShedModal'), width: '100%' });
                });

                modal.show();
            });

            // Optional client-side Bootstrap validation
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
                // Default submit → PUT (via @method('PUT')) → page refresh with flash message
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-shed-id');
                    const shedName = this.getAttribute('data-shed-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${shedName}" data?`;

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
    <script>
        $(document).ready(function () {
            $('.basic-select').select2({
                dropdownParent: $('#addShedModal'), // ensures it works inside Bootstrap modal
                width: '100%'
            });
        });
    </script>
@endpush
