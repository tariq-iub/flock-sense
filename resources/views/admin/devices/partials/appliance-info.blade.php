<div class="table-responsive">
    <table class="table align-middle">
        <thead class="table-light">
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Key</th>
            <th>Channel</th>
            <th>Status</th>
            <th>Source</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @forelse($device->appliances as $appliance)
            <tr>
                <td><i class="bi bi-plug"></i> {{ ucfirst($appliance->type) }}</td>
                <td>{{ $appliance->name ?? 'N/A' }}</td>
                <td>{{ $appliance->key ?? '—' }}</td>
                <td>{{ $appliance->channel ?? '-' }}</td>
                <td>
                    @if($appliance->status)
                        <span class="badge bg-success">ON</span>
                    @else
                        <span class="badge bg-danger">OFF</span>
                    @endif
                </td>
                <td>{{ ucfirst($appliance->last_command_source ?? 'N/A') }}</td>
                <td>{{ $appliance->status_updated_at ? $appliance->status_updated_at->diffForHumans() : '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No appliances linked to this device.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
