@extends('layouts.app')

@section('title', 'Newsletters')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Newsletters</h4>
                    <h6>Manage email newsletters and broadcasts.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="{{ route('newsletters.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>Create Newsletter
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
                        <option value="">All Statuses</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="sending">Sending</option>
                        <option value="sent">Sent</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Scheduled</th>
                                <th>Progress</th>
                                <th>Created</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newsletters as $newsletter)
                                <tr>
                                    <td>
                                        <a href="{{ route('newsletters.show', $newsletter) }}">
                                            <strong>{{ $newsletter->subject }}</strong>
                                        </a>
                                        @if($newsletter->preview_text)
                                            <br>
                                            <small class="text-muted">{{ mb_strlen($newsletter->preview_text) > 80 ? mb_substr($newsletter->preview_text, 0, 80) . '...' : $newsletter->preview_text }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($newsletter->status)
                                            @case('draft')
                                                <span class="badge bg-gray-300">Draft</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-info-transparent">Pending</span>
                                                @break
                                            @case('sending')
                                                <span class="badge bg-success-transparent">Sending</span>
                                                @break
                                            @case('sent')
                                                <span class="badge bg-success">Sent</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-primary-transparent">{{ $newsletter->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($newsletter->send_at)
                                            {{ $newsletter->send_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Not scheduled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            @if($newsletter->target_count > 0)
                                                <?php $progress = ($newsletter->sent_count / $newsletter->target_count) * 100; ?>
                                                <div class="progress-bar @if($newsletter->status === 'failed') bg-danger @elseif($newsletter->status === 'sent') bg-success @else bg-primary @endif"
                                                     role="progressbar"
                                                     style="width: {{ min($progress, 100) }}%"
                                                     aria-valuenow="{{ $newsletter->sent_count }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="{{ $newsletter->target_count }}">
                                                </div>
                                            @else
                                                <div class="progress-bar bg-secondary"
                                                     role="progressbar"
                                                     style="width: 0%">
                                                </div>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            {{ $newsletter->sent_count }} / {{ $newsletter->target_count }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $newsletter->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            @if($newsletter->status === 'draft')
                                                <form action="{{ route('newsletters.queueSend', $newsletter) }}" method="POST" id="queue{{ $newsletter->id }}" class="d-inline-block me-2">
                                                    @csrf
                                                    <button type="submit" class="p-2 border rounded bg-transparent" title="Queue for Sending">
                                                        <i data-feather="send" class="feather-send"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a class="me-2 p-2" href="{{ route('newsletters.show', $newsletter) }}">
                                                <i data-feather="eye" class="feather-eye"></i>
                                            </a>
                                            <a class="me-2 p-2" href="{{ route('newsletters.edit', $newsletter) }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);"
                                               data-bs-toggle="modal"
                                               data-bs-target="#delete-modal"
                                               data-item-id="{{ $newsletter->id }}"
                                               data-item-name="{{ $newsletter->subject }}"
                                               class="p-2 open-delete-modal">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                            <form action="{{ route('newsletters.destroy', $newsletter) }}" method="POST" id="delete{{ $newsletter->id }}">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="p-5 px-3 text-center">
                        <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                            <i class="ti ti-trash fs-24 text-danger"></i>
                        </span>
                        <h4 class="fs-20 fw-bold mb-2 mt-1">Delete Newsletter</h4>
                        <p class="mb-0 fs-16" id="delete-modal-message">
                            Are you sure you want to delete this newsletter?
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
                    table.column(1).search(selected).draw();
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-item-id');
                    const itemName = this.getAttribute('data-item-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${itemName}"?`;
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
