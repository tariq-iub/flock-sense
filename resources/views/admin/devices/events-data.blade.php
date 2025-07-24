<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title m-0">
            <i class="bi bi-bell me-1 text-danger"></i>
            Alerts for <strong>{{ $device->serial_no }}</strong>
        </div>
        <span class="badge bg-primary">{{ $events->count() }} total</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                <tr>
                    <th>Event Type</th>
                    <th class="text-center">Severity</th>
                    <th>Details</th>
                    <th class="text-center">Occurred At</th>
                </tr>
                </thead>
                <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ ucfirst($event->event_type) }}</td>
                        <td class="text-center">
                                <span class="badge bg-{{ $event->severity === 'critical' ? 'danger' : ($event->severity === 'warning' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($event->severity) }}
                                </span>
                        </td>
                        <td>
                            @foreach((array) json_decode($event->details, true) as $key => $value)
                                <div><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</div>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $event->occurred_at->format('d M Y H:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No events found for this device.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
