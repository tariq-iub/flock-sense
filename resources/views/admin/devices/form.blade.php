<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="ti ti-cpu"></i>
            {{ isset($device) ? 'Edit IoT Device' : 'Add New Device' }}
        </h5>
    </div>
    <form action="{{ ($device == null) ? route('iot.store') : route('iot.update', $device) }}" method="POST">
        @csrf
        @if($device != null)
            @method('PUT')
        @endif
        <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">
                    <i class="bi bi-upc-scan"></i> Serial No <span class="text-danger">*</span>
                    <span class="text-muted" data-bs-toggle="tooltip" title="Unique identifier on hardware">?</span>
                </label>
                <input type="text" name="serial_no" class="form-control @error('serial_no') is-invalid @enderror"
                       placeholder="DEV-2024-001"
                       value="{{ old('serial_no', $device->serial_no ?? '') }}" required autocomplete="off">
                @error('serial_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="bi bi-gear"></i> Model Number
                </label>
                <input type="text" name="model_number" class="form-control"
                       placeholder="DHT22-X"
                       value="{{ old('model_number', $device->model_number ?? '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="bi bi-building"></i> Manufacturer
                </label>
                <input type="text" name="manufacturer" class="form-control"
                       placeholder="Acme Devices"
                       value="{{ old('manufacturer', $device->manufacturer ?? '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="bi bi-arrow-repeat"></i> Firmware Version
                </label>
                <input type="text" name="firmware_version" class="form-control"
                       placeholder="1.2.3"
                       value="{{ old('firmware_version', $device->firmware_version ?? '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="bi bi-wifi"></i> Connectivity Type <span class="text-danger">*</span>
                </label>
                <select name="connectivity_type" class="select w-100 @error('connectivity_type') is-invalid @enderror" required>
                    @php
                        $selectedType = old('connectivity_type', $device->connectivity_type ?? 'WiFi');
                    @endphp
                    @foreach($connectivities as $type)
                        <option value="{{ $type->name }}" {{ $selectedType === $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('connectivity_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    <i class="bi bi-list-task"></i> Capabilities <span class="text-danger">*</span>
                    <span class="text-muted" data-bs-toggle="tooltip" title="Hold Ctrl (Cmd on Mac) for multi-select">?</span>
                </label>
                @php
                    $selected = old('capabilities', isset($device) ? json_decode($device->capabilities, true) : []);
                @endphp
                <select name="capabilities[]" class="select2 w-100" multiple required>
                    @foreach($capabilities as $cap)
                        <option value="{{ $cap->name }}" {{ in_array($cap->name, $selected ?? []) ? 'selected' : '' }}>
                            {{ ucfirst($cap->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-check form-switch">
                    {{-- Hidden input to ensure false is sent if not checked --}}
                    <input type="hidden" name="battery_operated" value="0">
                    <input class="form-check-input"
                           type="checkbox"
                           id="battery_operated"
                           name="battery_operated"
                           value="1"
                        {{ old('battery_operated', $device->battery_operated ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="battery_operated">Battery Operated</label>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="submit" class="btn btn-success shadow">
                {{ isset($device) ? 'Update Device' : 'Create Device' }}
            </button>

            <a href="{{ route('iot.index') }}" class="btn btn-secondary shadow">
                Cancel
            </a>
        </div>
    </div>
    </form>
</div>

@push('scripts')
    <script>
        // Bootstrap 5 Tooltip initialization
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endpush

