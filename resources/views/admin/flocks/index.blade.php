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

                @if ($errors->any() || session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>
                                <i class="feather-alert-triangle flex-shrink-0 me-2"></i>
                                There were some errors with your submission:
                            </strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                                @if(session('error'))
                                    <li>{{ session('error') }}</li>
                                @endif
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
                                            Farm: {{ $shed->farm?->name }}<br>
                                            Owner: {{ $shed->farm?->owner?->name }}
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

    <!-- Edit Flock Modal -->
    <div class="modal fade" id="editFlockModal" tabindex="-1" aria-labelledby="editFlockModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editFlockForm" action="#" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFlockModalLabel">Edit Flock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_flock_id">

                        <div class="row">
                            <!-- Flock Name -->
                            <div class="col-lg-6 mb-3">
                                <label for="edit_name" class="form-label">Flock Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                                <div class="invalid-feedback">Flock name should be entered for identification.</div>
                            </div>

                            <!-- Shed -->
                            <div class="col-lg-6 mb-3">
                                <label for="edit_shed_id" class="form-label">Shed<span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select-edit" id="edit_shed_id" name="shed_id" required>
                                    @foreach($sheds as $shed)
                                    <option value="{{ $shed->id }}">{{ $shed->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Select a shed as flock is cycled in a shed.</div>
                            </div>

                            <!-- Breed -->
                            <div class="col-lg-6 mb-3">
                                <label for="edit_breed_id" class="form-label">Breed<span class="text-danger ms-1">*</span></label>
                                <select class="form-select basic-select-edit" id="edit_breed_id" name="breed_id" required>
                                    <option value="">Select Breed</option>
                                    @foreach($breeds as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Flock breed is required.</div>
                            </div>

                            <!-- Chicken Count -->
                            <div class="col-lg-6 mb-3">
                                <label for="edit_chicken_count" class="form-label">Chicken Count<span class="text-danger ms-1">*</span></label>
                                <input type="number" id="edit_chicken_count" name="chicken_count" class="form-control" required>
                                <div class="invalid-feedback">Please mention the flock initial chicken count.</div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-lg-6 mb-3">
                                <label for="edit_start_date" class="form-label">Start Date<span class="text-danger ms-1">*</span></label>
                                <input type="date" class="form-control"
                                       id="edit_start_date" name="start_date" required/>
                                <div class="invalid-feedback">Flock cycle start date is required.</div>
                            </div>

                            <!-- End Date -->
                            <div class="col-lg-6 mb-3">
                                <label for="edit_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control"
                                       id="edit_end_date" name="end_date"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success me-2">Update Flock</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            const editModalEl = document.getElementById('editFlockModal');
            const editModal = new bootstrap.Modal(editModalEl);
            const form = document.getElementById('editFlockForm');

            const field = {
                id:            document.getElementById('edit_flock_id'),
                name:          document.getElementById('edit_name'),
                shed_id:       document.getElementById('edit_shed_id'),
                breed_id:      document.getElementById('edit_breed_id'),
                chicken_count: document.getElementById('edit_chicken_count'),
                start_date:    document.getElementById('edit_start_date'),
                end_date:      document.getElementById('edit_end_date'),
            };

            // Delegated click for all .edit-flock buttons
            document.addEventListener('click', async function (e) {
                const btn = e.target.closest('.edit-flock');
                if (!btn) return;

                const flockId = btn.getAttribute('data-flock-id');
                if (!flockId) return;

                try {
                    // Fetch flock JSON
                    const res = await fetch(`{{ url('/admin/flocks') }}/${flockId}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) throw new Error('Failed to load flock data');
                    const data = await res.json();

                    // Populate fields
                    field.id.value            = data.id ?? '';
                    field.name.value          = data.name ?? '';
                    field.shed_id.value       = data.shed_id ?? '';
                    field.breed_id.value      = data.breed_id ?? '';
                    field.chicken_count.value = data.chicken_count ?? '';

                    // Dates are already Y-m-d from controller
                    field.start_date.value = data.start_date ?? '';
                    field.end_date.value   = data.end_date ?? '';

                    // Set form action to update route
                    form.setAttribute('action', `{{ url('/admin/flocks') }}/${flockId}`);

                    // Show modal
                    editModal.show();

                    $('.basic-select-edit').select2({
                        dropdownParent: $('#editFlockModal'),
                        width: '100%'
                    });
                } catch (err) {
                    console.error(err);
                    alert('Unable to load flock details. Please try again.');
                }
            });

            // Optional: client-side bootstrap validation
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>

    <script>
        let deleteId = null;
        function OpenModal(ctrl) {
            deleteId = $(ctrl).data('flock-id');
            let name = $(ctrl).data('flock-name');

            document.getElementById('delete-modal-message').textContent =
                `Are you sure you want to delete "${name}" data?`;

            var modal = new bootstrap.Modal(document.getElementById('delete-modal'));
            modal.show();
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteId) {
                document.getElementById('delete' + deleteId).submit();
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.basic-select').select2({
                dropdownParent: $('#addFlockModal'), // ensures it works inside Bootstrap modal
                width: '100%'
            });
        });
    </script>
@endpush
