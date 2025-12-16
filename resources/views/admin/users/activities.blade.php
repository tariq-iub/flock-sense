@extends('layouts.app')

@section('title', 'User Activities')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">User Activities</h4>
                    <h6>View and monitor user / clients activities.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                        <i class="ti ti-chevron-up"></i>
                    </a>
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

        {{-- Filter Card --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('clients.activities') }}" class="row g-3 align-items-end">
                    {{-- Model Name --}}
                    <div class="col-md-3">
                        <label class="form-label">Model Name</label>
                        <select name="model" class="form-select">
                            <option value="">All Models</option>
                            @foreach($models as $model)
                                @php
                                    $baseName = class_basename($model);
                                @endphp
                                <option value="{{ $model }}"
                                    @selected(($filters['model'] ?? '') === $model)>
                                    {{ $baseName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- User --}}
                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @selected(($filters['user_id'] ?? '') == $user->id)>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control"
                               value="{{ $filters['date_from'] ?? '' }}">
                    </div>

                    {{-- Date To --}}
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control"
                               value="{{ $filters['date_to'] ?? '' }}">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-success w-50">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('clients.activities') }}" class="btn btn-outline-secondary w-50">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Activities Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 14%">Date / Time</th>
                            <th style="width: 36%">Description</th>
                            <th style="width: 15%">Model</th>
                            <th style="width: 15%">User Name</th>
                            <th style="width: 10%" class="text-center">Properties</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activities as $activity)
                            @php
                                $modelName = $activity->subject_type
                                    ? class_basename($activity->subject_type)
                                    : '-';

                                $tableName = $activity->subject_type
                                    ? \Illuminate\Support\Str::snake(
                                        \Illuminate\Support\Str::pluralStudly($modelName)
                                      )
                                    : '-';

                                $propertiesJson = $activity->properties
                                    ? json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                    : '{}';
                            @endphp

                            <tr>
                                {{-- Date/Time --}}
                                <td>
                                    <div class="small text-muted">
                                        {{ $activity->created_at?->timezone(config('app.timezone'))->format('d-m-Y H:i:s') ?? '-' }}
                                    </div>
                                    <div class="small text-secondary">
                                        {{ $activity->created_at?->diffForHumans() }}
                                    </div>
                                </td>

                                {{-- Description --}}
                                <td>
                                    <div class="">{{ $activity->description }}</div>
                                    <div class="small text-muted">
                                        <span class="badge rounded-pill bg-warning-gradient text-dark border me-1">
                                            {{ $activity->event ?? 'event' }}
                                        </span>
                                        @if($activity->log_name)
                                            <span class="badge bg-info-subtle text-secondary-emphasis">
                                                {{ $activity->log_name }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Model (Table Name) --}}
                                <td>
                                    <div class="fw-semibold">{{ $modelName }}</div>
                                    <div class="small text-muted">
                                        <code><i class="bi bi-database me-1"></i>{{ $tableName }}</code>
                                    </div>
                                </td>

                                {{-- User Name --}}
                                <td>
                                    @if($activity->causer)
                                        <div class="fw-semibold">{{ $activity->causer->name }}</div>
                                        <div class="small text-muted">{{ $activity->causer->email }}</div>
                                    @else
                                        <span class="badge bg-secondary">System</span>
                                    @endif
                                </td>

                                {{-- Properties --}}
                                @php
                                    // Build raw JSON string once
                                    $propertiesJson = $activity->properties
                                        ? json_encode($activity->properties, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                                        : '{}';
                                @endphp
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info js-activity-properties"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="View properties"
                                            data-description="{{ e($activity->description) }}"
                                            data-properties='@json($activity->properties ?? (object)[])'>
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No activities found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Properties Modal --}}
    <div class="modal fade" id="activityPropertiesModal" tabindex="-1"
         aria-labelledby="activityPropertiesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityPropertiesModalLabel">Activity Properties</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <pre id="activityPropertiesContent"
                     class="bg-info-subtle text-secondary p-3 rounded small mb-0"
                     style="max-height: 60vh; overflow: auto;"></pre>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl   = document.getElementById('activityPropertiesModal');
            const contentEl = document.getElementById('activityPropertiesContent');
            const titleEl   = document.getElementById('activityPropertiesModalLabel');

            if (!modalEl || !contentEl || !titleEl) return;

            const bsModal = new bootstrap.Modal(modalEl);

            document.querySelectorAll('.js-activity-properties').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const description = this.getAttribute('data-description') || 'Activity Properties';
                    const properties  = this.getAttribute('data-properties') || '{}';

                    titleEl.textContent = description;

                    try {
                        const parsed = JSON.parse(properties);
                        contentEl.textContent = JSON.stringify(parsed, null, 2);
                    } catch (e) {
                        contentEl.textContent = properties;
                    }

                    bsModal.show();
                });
            });

            // Enable Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
