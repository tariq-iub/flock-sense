@extends('layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Newsletter Subscribers</h4>
                    <h6>Manage email newsletter subscriptions.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
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
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
                       class="btn @if(request()->get('status') == 'all' || request()->get('status') == null) btn-primary @else btn-outline-primary @endif me-2">
                        All
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'subscribed']) }}"
                       class="btn @if(request()->get('status') == 'subscribed') btn-success @else btn-outline-success @endif me-2">
                        Subscribed
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'unsubscribed']) }}"
                       class="btn @if(request()->get('status') == 'unsubscribed') btn-danger @else btn-outline-danger @endif">
                        Unsubscribed
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable-custom">
                        <thead class="thead-light">
                            <tr>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Subscribed At</th>
                                <th>Last Email Sent</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                                <tr>
                                    <td>
                                        <strong>{{ $subscriber->email }}</strong>
                                        @if($subscriber->ip_address)
                                            <br>
                                            <small class="text-muted fs-12">IP: {{ $subscriber->ip_address }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscriber->status === 'subscribed')
                                            <span class="badge bg-success">Subscribed</span>
                                        @else
                                            <span class="badge bg-danger">Unsubscribed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscriber->source)
                                            <span class="badge bg-info text-uppercase fs-12">{{ $subscriber->source }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscriber->confirmed_at)
                                            {{ $subscriber->confirmed_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscriber->last_sent_at)
                                            {{ $subscriber->last_sent_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            @if($subscriber->status === 'subscribed')
                                                <form action="{{ route('newsletters.subscribers.toggle', $subscriber) }}" method="POST" id="unsubscribe{{ $subscriber->id }}" class="d-inline-block me-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="unsubscribed">
                                                    <button type="submit" class="p-2 border rounded bg-transparent" title="Unsubscribe">
                                                        <i data-feather="user-minus" class="feather-user-minus"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('newsletters.subscribers.toggle', $subscriber) }}" method="POST" id="subscribe{{ $subscriber->id }}" class="d-inline-block me-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="subscribed">
                                                    <button type="submit" class="p-2 border rounded bg-transparent" title="Subscribe">
                                                        <i data-feather="user-plus" class="feather-user-plus"></i>
                                                    </button>
                                                </form>
                                            @endif
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
            }
        });
    </script>
@endpush
