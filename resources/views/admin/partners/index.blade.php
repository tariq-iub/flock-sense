@extends('layouts.app')

@section('title', 'Partners')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            <th>Company Name</th>
                            <th>Website URL</th>
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
                                {{-- Company column inside the <tr> --}}
                                <td class="w-fit">
                                    <div class="d-flex align-items-center">
                                        @if($partner->media->first())
                                            <img
                                                src="{{ $partner->media->first()->url }}"
                                                alt="{{ $partner->company_name }} logo"
                                                class="bg-light me-2 rounded"
                                                style="width: 64px; height: 32px; object-fit: contain;"
                                            >
                                        @endif
                                        <span class="fw-bold">{{ $partner->company_name }}</span>
                                    </div>
                                </td>

                                <td class="fs-11">
                                    <a href="{{ $partner->url }}" target="_blank">{{ $partner->url }}</a>
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                       class="btn btn-sm btn-outline-info chart-data js-partner-offcanvas"
                                       data-title="Introduction – {{ $partner->company_name }}"
                                       data-content="{{ e($partner->introduction) }}"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="View Introduction">
                                        View
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                       class="btn btn-sm btn-outline-info chart-data js-partner-offcanvas"
                                       data-title="Partnership Detail – {{ $partner->company_name }}"
                                       data-content="{{ e($partner->partnership_detail) }}"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="View Partnership Detail">
                                        View
                                    </a>
                                </td>

                                <td class="text-center">
                                    @if(is_array($partner->support_keywords))
                                        @foreach($partner->support_keywords as $keyword)
                                            @if($keyword !== '')
                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                    {{ $keyword }}
                                                </span>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-flex align-items-center justify-content-center">
                                        <input
                                            class="form-check-input js-partner-status-toggle"
                                            type="checkbox"
                                            role="switch"
                                            id="partnerStatus{{ $partner->id }}"
                                            data-id="{{ $partner->id }}"
                                            @checked($partner->is_active)
                                        >
                                    </div>
                                </td>

                                <td class="text-center">
                                    <input
                                        type="number"
                                        class="form-control bs-spinner"
                                        min="0"
                                        step="1"
                                        value="{{ $partner->sort_order }}"
                                        data-id="{{ $partner->id }}"
                                        style="min-width: 100px;"
                                    >
                                </td>

                                <td class="action-table-data">
                                    <div class="action-icon d-inline-flex">
                                        <a href="javascript:void(0)"
                                           class="me-2 d-flex align-items-center p-2 border rounded edit-partner"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Edit Partner"
                                           data-partner-id="{{ $partner->id }}"
                                           data-partner-name="{{ $partner->company_name }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title=""
                                           data-bs-original-title="Delete Partner"
                                           data-partner-id="{{ $partner->id }}"
                                           data-partner-name="{{ $partner->company_name }}"
                                           class="p-2 open-delete-modal">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                        <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" id="delete{{ $partner->id }}">
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

    <!-- Add Partner Modal -->
    @include('admin.partners._add_modal')

    <!-- Edit Partner Modal -->
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

    {{-- Right offcanvas to show Introduction / Partnership Detail --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="partnerDetailOffcanvas"
         aria-labelledby="partnerDetailOffcanvasLabel" aria-modal="true" role="dialog">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="partnerDetailOffcanvasLabel">Partner Detail</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="partnerDetailOffcanvasBody" class="text-wrap"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
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

        // Edit modal JS
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-partner').forEach(function(button) {
                button.addEventListener('click', function() {
                    var partnerId = this.getAttribute('data-partner-id');
                    var partnerName = this.getAttribute('data-partner-name');

                    var form = document.getElementById('editPartnerForm');
                    var updateUrl = '/admin/partners/' + partnerId;

                    document.getElementById('editPartnerModalLabel').textContent = "Edit Partner - " + partnerName;

                    $.get('/admin/partners/' + partnerId, function(partner) {
                        // Populate fields
                        fillEditPartnerModal(partner, updateUrl);

                        var modal = new bootstrap.Modal(document.getElementById('editPartnerModal'));
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
                    deleteId = this.getAttribute('data-partner-id');
                    const partnerName = this.getAttribute('data-partner-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${partnerName}" partner?`;

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
        // Offcanvas for Introduction / Partnership Detail
        document.addEventListener('DOMContentLoaded', function () {
            const offcanvasEl  = document.getElementById('partnerDetailOffcanvas');
            const bodyEl       = document.getElementById('partnerDetailOffcanvasBody');
            const titleEl      = document.getElementById('partnerDetailOffcanvasLabel');

            if (!offcanvasEl || !bodyEl || !titleEl) return;

            const bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);

            document.querySelectorAll('.js-partner-offcanvas').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const title   = this.getAttribute('data-title') || '';
                    const content = this.getAttribute('data-content') || '';

                    titleEl.textContent = title;
                    // Convert newlines to <br> for nicer display
                    bodyEl.innerHTML = content.replace(/\n/g, '<br>');

                    bsOffcanvas.show();
                });
            });
        });
        // Toggle partner active status via API
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

            if (!csrfToken) return;

            document.querySelectorAll('.js-partner-status-toggle').forEach(function (input) {
                input.addEventListener('change', function () {
                    const partnerId = this.getAttribute('data-id');
                    const isActive  = this.checked;

                    const url = "{{ route('partners.toggle-status', ':id') }}"
                        .replace(':id', partnerId);

                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ is_active: isActive }),
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .catch(error => {
                            console.error('Status toggle failed:', error);
                            // Revert UI if error
                            this.checked = !isActive;
                            alert('Unable to update status. Please try again.');
                        });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

            if (!csrfToken) return;

            document.querySelectorAll('.bs-spinner').forEach(function (input) {
                let debounceTimer = null;

                input.addEventListener('change', function () {
                    const el        = this;
                    const partnerId = el.getAttribute('data-id');
                    const newValue  = parseInt(el.value, 10);

                    if (isNaN(newValue)) {
                        return;
                    }

                    // Debounce to avoid multiple calls when user is clicking fast
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () {
                        updatePartnerSort(partnerId, newValue, el);
                    }, 400);
                });
            });

            function updatePartnerSort(partnerId, sortValue, inputEl) {
                const url = "{{ route('partners.update-sort', ':id') }}".replace(':id', partnerId);

                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ sort_order: sortValue }),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(json => {
                        if (!json.success) {
                            throw new Error('Server error while updating sort order');
                        }
                        // Optionally show a toast or console log
                        // console.log('Sort updated:', json.sort_order);
                    })
                    .catch(error => {
                        console.error('Sort update failed:', error);
                        // Optionally revert to previous value if you stored it somewhere
                        alert('Unable to update sort order. Please try again.');
                    });
            }
        });
    </script>
@endpush
