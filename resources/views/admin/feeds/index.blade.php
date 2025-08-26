@extends('layouts.app')

@section('title', 'Feeds')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Chicken Feeds</h4>
                    <h6>Manage data for chicken feeds.</h6>
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
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeedModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Feed
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
                    <select id="statusFilter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $row)
                            <option value="{{ $row }}">{{ ucfirst($row) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                        <tr>
                            <th class="">Feed Title</th>
                            <th class="text-center">Start Day</th>
                            <th class="text-center">End Day</th>
                            <th class="text-center">Feed Form</th>
                            <th class="">Particle Size</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Profile</th>
                            <th class="no-sort"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($feeds as $feed)
                            <tr>
                                <td>{{ ucfirst($feed->title) }}</td>
                                <td class="text-center">{{ $feed->start_day }}</td>
                                <td class="text-center">{{ $feed->end_day }}</td>
                                <td class="text-center">{{ $feed->feed_form }}</td>
                                <td class="text-wrap">{{ $feed->particle_size }}</td>
                                <td class="text-center">{{ ucfirst($feed->category) }}</td>
                                <td class="text-center">
                                    <div class="action-icon d-inline-flex">
                                        <a href="javascript:void(0)"
                                           class="p-2 border rounded"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Feed Profiles"
                                           data-feed-id="{{ $feed->id }}"
                                           data-feed-name="{{ $feed->name }}"
                                           onclick="(new bootstrap.Modal(document.getElementById('profileModal-{{ $feed->id }}'))).show();">
                                            <i class="ti ti-list"></i>
                                        </a>

                                        <!-- Profile Modal -->
                                        <div class="modal fade" id="profileModal-{{ $feed->id }}" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true" data-bs-backdrop="static">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="profileModalLabel">Feed Profile - {{ $feed->title }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead class="thead-light">
                                                                <tr>
                                                                    <th>Brand</th>
                                                                    <th>Description</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($feed->feedProfiles as $p)
                                                                    <tr>
                                                                        <td>{{ $p->brand }}</td>
                                                                        <td class="text-wrap">
                                                                            {{ $p->description }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="javascript:void(0)"
                                           class="d-flex align-items-center p-2 border rounded edit-feed me-2"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Feed"
                                           data-feed-id="{{ $feed->id }}"
                                           data-feed-name="{{ $feed->title }}">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                           class="p-2 border rounded open-delete-modal"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Feed"
                                           data-feed-id="{{ $feed->id }}"
                                           data-feed-name="{{ $feed->title }}">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('feeds.destroy', $feed->id) }}" method="POST" id="delete{{ $feed->id }}">
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

    <!-- Add Feed Modal -->
    <div class="modal fade" id="addFeedModal" tabindex="-1" aria-labelledby="addFeedModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('feeds.store') }}" class="needs-validation" novalidate method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFeedModalLabel">Add Feed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Feed Title<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                    <div class="invalid-feedback">
                                        Feed title is required.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="start_day" class="form-label">Start Day<span class="text-danger ms-1">*</span></label>
                                    <input type="number" class="form-control" id="start_day" name="start_day" min="0" required>
                                    <div class="invalid-feedback">
                                        Start day is required.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="end_day" class="form-label">End Day</label>
                                    <input type="number" class="form-control" id="end_day" name="end_day" min="0">
                                    <div class="invalid-feedback">
                                        End day is invalid.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="feed_form" class="form-label">Feed Form<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" id="feed_form" name="feed_form" maxlength="100" required>
                                    <div class="invalid-feedback">
                                        Feed form is required.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="particle_size" class="form-label">Particle Size</label>
                                    <textarea class="form-control" id="particle_size" name="particle_size" rows="2"></textarea>
                                    <div class="invalid-feedback">
                                        Particle size is invalid.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                    <select class="select2" id="category" name="category" required>
                                        @foreach(['broiler', 'layer'] as $row)
                                            <option value="{{ $row }}">{{ ucfirst($row) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Category is required.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success me-2">Save Feed</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Feed Modal -->
    <div class="modal fade" id="editFeedModal" tabindex="-1" aria-labelledby="editFeedModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editFeedForm" action="" class="needs-validation" novalidate method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFeedModalLabel">Edit Feed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_feed_id" name="feed_id">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="edit_title" class="form-label">Feed Title<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required>
                                    <div class="invalid-feedback">Feed title is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_start_day" class="form-label">Start Day<span class="text-danger ms-1">*</span></label>
                                    <input type="number" class="form-control" id="edit_start_day" name="start_day" min="0" required>
                                    <div class="invalid-feedback">Start day is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_end_day" class="form-label">End Day</label>
                                    <input type="number" class="form-control" id="edit_end_day" name="end_day" min="0">
                                    <div class="invalid-feedback">End day is invalid.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_feed_form" class="form-label">Feed Form<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" id="edit_feed_form" name="feed_form" maxlength="100" required>
                                    <div class="invalid-feedback">Feed form is required.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_particle_size" class="form-label">Particle Size</label>
                                    <textarea class="form-control" id="edit_particle_size" name="particle_size" rows="2"></textarea>
                                    <div class="invalid-feedback">Particle size is invalid.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_category" class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                    <select class="select2" id="edit_category" name="category" required>
                                        <option value="broiler">Broiler</option>
                                        <option value="layer">Layer</option>
                                    </select>
                                    <div class="invalid-feedback">Category is required.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success me-2">Update Feed</button>
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
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Breed</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this breed data?
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
                    table.column(5).search(selected).draw();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-feed').forEach(function(button) {
                button.addEventListener('click', function() {
                    var feedId = this.getAttribute('data-feed-id');
                    var feedName = this.getAttribute('data-feed-name');

                    var form = document.getElementById('editFeedForm');
                    form.action = '/admin/feeds/' + feedId;

                    // Set hidden and visible values
                    document.getElementById('editFeedModalLabel').textContent = "Edit Feed - " + feedName;

                    $.get('/admin/feeds/' + feedId, function(data) {
                        // Populate fields
                        $('#edit_feed_id').val(data.id);
                        $('#edit_title').val(data.title);
                        $('#edit_start_day').val(data.start_day);
                        $('#edit_end_day').val(data.end_day);
                        $('#edit_feed_form').val(data.feed_form);
                        $('#edit_particle_size').val(data.particle_size);
                        $('#edit_category').val(data.category);
                    });

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById('editFeedModal'));
                    modal.show();
                });
            });

            let deleteId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-feed-id');
                    const feedName = this.getAttribute('data-feed-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${feedName}" data?`;

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
