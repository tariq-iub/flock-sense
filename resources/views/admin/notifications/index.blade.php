@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Notifications</h4>
                    <h6>View all your notifications.</h6>
                </div>
            </div>
            <div class="page-btn">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-checks me-1"></i>Mark All as Read
                    </button>
                </form>
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
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;">Status</th>
                            <th style="width: 80px;">Type</th>
                            <th>Notification</th>
                            <th style="width: 150px;">Date</th>
                            <th style="width: 100px;" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($notifications as $notification)
                            <tr class="{{ $notification->is_read ? '' : 'table-active' }}">
                                <td class="text-center">
                                    @if($notification->is_read)
                                        <span class="badge bg-soft-secondary text-dark border">
                                            <i class="ti ti-check"></i>
                                        </span>
                                    @else
                                        <span class="badge bg-soft-primary text-primary border">
                                            <i class="ti ti-point-filled"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->type === 'report_submitted')
                                        <span class="badge bg-soft-info text-dark border">
                                            <i class="ti ti-file-text me-1"></i>Report
                                        </span>
                                    @elseif($notification->type === 'device_failure')
                                        <span class="badge bg-soft-danger text-dark border">
                                            <i class="ti ti-alert-triangle me-1"></i>Alert
                                        </span>
                                    @else
                                        <span class="badge bg-soft-secondary text-dark border">
                                            <i class="ti ti-bell me-1"></i>Info
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $notification->title }}
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary badge-xs ms-1">New</span>
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-0">{{ $notification->message }}</p>
                                        @if($notification->farm)
                                            <small class="text-muted">
                                                <i class="ti ti-building-farm me-1"></i>{{ $notification->farm->name }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $notification->created_at->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $notification->created_at->format('h:i A') }}</small>
                                    <br>
                                    <small class="text-primary">{{ $notification->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    @if(!$notification->is_read)
                                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Mark as read">
                                                <i class="ti ti-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="ti ti-bell-off fs-40 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No notifications yet</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($notifications->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                            </p>
                        </div>
                        <div>
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
