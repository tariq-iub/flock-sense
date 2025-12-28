@extends('layouts.app')

@section('title', 'View Newsletter')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Newsletter Details</h4>
                    <h6>View newsletter information and delivery status.</h6>
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

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="ti ti-info-circle text-info me-2"></i>Newsletter Info
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase fs-12">Subject</label>
                            <h5 class="mb-0">{{ $newsletter->subject }}</h5>
                        </div>

                        @if($newsletter->preview_text)
                            <div class="mb-3">
                                <label class="form-label text-muted text-uppercase fs-12">Preview Text</label>
                                <p class="mb-0">{{ $newsletter->preview_text }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase fs-12">Status</label>
                            @switch($newsletter->status)
                                @case('draft')
                                    <span class="badge bg-gray-300 fs-13 ms-2">Draft</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-info-transparent fs-13 ms-2">Pending</span>
                                    @break
                                @case('sending')
                                    <span class="badge bg-success-transparent fs-13 ms-2">Sending</span>
                                    @break
                                @case('sent')
                                    <span class="badge bg-success fs-13 ms-2">Sent</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger fs-13 ms-2">Failed</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-13 ms-2">{{ $newsletter->status }}</span>
                            @endswitch
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase fs-12">Scheduled For</label>
                            <p class="mb-0">
                                @if($newsletter->send_at)
                                    {{ $newsletter->send_at->format('F d, Y \a\t h:i A') }}
                                @else
                                    <span class="text-muted">Not scheduled</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase fs-12">Created</label>
                            <p class="mb-0">{{ $newsletter->created_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted text-uppercase fs-12">Last Updated</label>
                            <p class="mb-0">{{ $newsletter->updated_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('newsletters.edit', $newsletter) }}" class="btn btn-success flex-fill">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('newsletters.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="ti ti-chart-pie text-success me-2"></i>Delivery Stats
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center">
                                    <h4 class="mb-1">{{ $newsletter->target_count }}</h4>
                                    <p class="fs-12 mb-0">Target</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center">
                                    <h4 class="mb-1 text-info">{{ $newsletter->sent_count }}</h4>
                                    <p class="fs-12 mb-0">Sent</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center">
                                    <h4 class="mb-1 text-success">{{ $newsletter->deliveries->where('status', 'delivered')->count() }}</h4>
                                    <p class="fs-12 mb-0">Delivered</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-3 text-center">
                                    <h4 class="mb-1 text-danger">{{ $newsletter->deliveries->where('status', 'failed')->count() }}</h4>
                                    <p class="fs-12 mb-0">Failed</p>
                                </div>
                            </div>
                        </div>

                        @if($newsletter->target_count > 0)
                            <?php $progress = ($newsletter->sent_count / $newsletter->target_count) * 100; ?>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-medium">{{ number_format($progress, 1) }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar @if($newsletter->status === 'failed') bg-danger @elseif($newsletter->status === 'sent') bg-success @else bg-primary @endif"
                                         role="progressbar"
                                         style="width: {{ min($progress, 100) }}%"
                                         aria-valuenow="{{ $newsletter->sent_count }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $newsletter->target_count }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($newsletter->last_error)
                            <div class="alert alert-danger mt-3 mb-0">
                                <strong><i class="feather-alert-triangle me-1"></i>Error:</strong>
                                <small>{{ $newsletter->last_error }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="newsletterTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab">
                                    <i class="ti ti-file-text me-1"></i>Content
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="deliveries-tab" data-bs-toggle="tab" data-bs-target="#deliveries" type="button" role="tab">
                                    <i class="ti ti-send me-1"></i>Deliveries
                                    <span class="badge bg-success ms-1">{{ $newsletter->deliveries->count() }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="newsletterTabContent">
                            <div class="tab-pane fade show active" id="content" role="tabpanel">
                                <h5 class="mb-3">HTML Content</h5>
                                <div class="border rounded p-3 bg-light" style="max-height: 500px; overflow-y: auto;">
                                    <pre class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{!! htmlspecialchars($newsletter->content_html) !!}</pre>
                                </div>

                                @if($newsletter->content_text)
                                    <h5 class="mb-3 mt-4">Plain Text Content</h5>
                                    <div class="border rounded p-3 bg-light" style="max-height: 500px; overflow-y: auto;">
                                        <pre class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{{ $newsletter->content_text }}</pre>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="deliveries" role="tabpanel">
                                @if($newsletter->deliveries->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Subscriber</th>
                                                    <th>Status</th>
                                                    <th>Sent At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($newsletter->deliveries as $delivery)
                                                    <tr>
                                                        <td>
                                                            @if($delivery->subscriber)
                                                                <strong>{{ $delivery->subscriber->email }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    @if($delivery->subscriber->name)
                                                                        {{ $delivery->subscriber->name }}
                                                                    @else
                                                                        No name
                                                                    @endif
                                                                </small>
                                                            @else
                                                                <span class="text-muted">Unknown subscriber</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @switch($delivery->status)
                                                                @case('pending')
                                                                    <span class="badge bg-info">Pending</span>
                                                                    @break
                                                                @case('sent')
                                                                    <span class="badge bg-primary">Sent</span>
                                                                    @break
                                                                @case('delivered')
                                                                    <span class="badge bg-success">Delivered</span>
                                                                    @break
                                                                @case('failed')
                                                                    <span class="badge bg-danger">Failed</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-secondary">{{ $delivery->status }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            @if($delivery->sent_at)
                                                                {{ $delivery->sent_at->format('M d, Y H:i') }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="ti ti-inbox fs-40 text-muted mb-3"></i>
                                        <h5 class="text-muted">No deliveries yet</h5>
                                        <p class="text-muted">This newsletter hasn't been sent to any subscribers.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
