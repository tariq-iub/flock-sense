@extends('layouts.app')

@section('title', $device->serial_no)

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Device Details</h4>
                    <h6>Manage IoT devices inventory.</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="{{ route('iot.index') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>Back to List
                </a>
            </div>
        </div>
        <div class="card shadow rounded-4 p-0 mx-auto" style="max-width: 668px;">
            <div class="card-header bg-gradient d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-cpu-fill text-primary me-2"></i>
                    <span class="fw-semibold fs-14" data-bs-toggle="tooltip" title="Unique Serial Number">{{ $device->serial_no }}</span>
                </span>
                <span>
                    <span class="badge rounded-pill {{ $device->is_online ? 'bg-success' : 'bg-danger' }} fs-12">
                        <i class="bi {{ $device->is_online ? 'bi-wifi' : 'bi-wifi-off' }}"></i>
                        {{ $device->is_online ? 'Online' : 'Offline' }}
                    </span>
                </span>
            </div>

            <div class="card-body bg-light">
                {{-- Basic Info --}}
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-5 text-end text-muted">Serial Number : </div>
                    <div class="col-7">
                        <span class="fw-semibold">
                            <i class="bi bi-upc me-1"></i>
                            {{ $device->serial_no ?? '-' }}
                        </span>
                    </div>

                    <div class="col-5 text-end text-muted">Model : </div>
                    <div class="col-7 fw-bold">{{ $device->model_number ?? '-' }}</div>

                    <div class="col-5 text-end text-muted">Manufacturer : </div>
                    <div class="col-7">
                        <span class="fw-semibold">
                            <i class="bi bi-building me-1"></i>
                            {{ $device->manufacturer ?? '-' }}
                        </span>
                    </div>

                    <div class="col-5 text-end text-muted">Firmware : </div>
                    <div class="col-7">
                        <span class="badge bg-info-subtle text-dark" data-bs-toggle="tooltip" title="Firmware version running">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            {{ $device->firmware_version ?? '-' }}
                        </span>
                    </div>
                </div>
                <hr class="my-2">

                {{-- Connectivity & Capabilities --}}
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-5 text-end text-muted">Connectivity : </div>
                    <div class="col-7 fw-bold">
                        <i class="bi bi-broadcast-pin me-1"></i>
                        {{ $device->connectivity_type }}
                    </div>
                    <div class="col-5 text-end text-muted">Capabilities : </div>
                    <div class="col-7 d-flex flex-wrap gap-1">
                        @foreach($device->capabilities as $cap)
                            <span class="badge bg-gradient text-dark border" data-bs-toggle="tooltip" title="Monitors {{ ucfirst($cap->name) }}">
                                <i class="{{ $cap->icon }}"></i>
                                {{ ucfirst($cap->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <hr class="my-2">

                {{-- Status and Health --}}
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-5 text-end text-muted">Battery : </div>
                    <div class="col-7">
                    <span class="badge bg-secondary-subtle text-dark me-1">
                        <i class="bi {{ $device->battery_operated ? 'bi-battery-charging' : 'bi-plug' }}"></i>
                        {{ $device->battery_operated ? 'Battery' : 'Wired' }}
                    </span>
                        @if($device->battery_operated)
                            <span class="d-inline-block" style="min-width:70px;" data-bs-toggle="tooltip" title="Battery Level">
                                <div class="progress" style="height: 18px;">
                                    <div class="progress-bar {{ $device->battery_level > 30 ? 'bg-success' : 'bg-danger' }}"
                                         role="progressbar"
                                         style="width: {{ $device->battery_level ?? 0 }}%;"
                                         aria-valuenow="{{ $device->battery_level ?? 0 }}"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <small class="fw-bold">{{ $device->battery_level ?? '-' }}%</small>
                                    </div>
                                </div>
                            </span>
                        @endif
                    </div>
                    <div class="col-5 text-end text-muted">Signal : </div>
                    <div class="col-7">
                    <span data-bs-toggle="tooltip" title="Signal Strength">
                        <i class="bi bi-bar-chart-steps text-primary"></i>
                        <span class="d-inline-block" style="width:70px;">
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ ($device->signal_strength ?? 0) > 50 ? 'bg-success' : 'bg-warning' }}"
                                     role="progressbar"
                                     style="width: {{ $device->signal_strength ?? 0 }}%;"
                                     aria-valuenow="{{ $device->signal_strength ?? 0 }}"
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </span>
                        <span class="fw-bold ms-1">{{ $device->signal_strength ?? '-' }}%</span>
                    </span>
                    </div>
                </div>
                <hr class="my-2">

                {{-- Timeline and status --}}
                <div class="row g-3 align-items-center">
                    <div class="col-5 text-end text-muted">Last Heartbeat : </div>
                    <div class="col-7">
                        <span data-bs-toggle="tooltip" title="{{ $device->last_heartbeat }}">
                            <i class="bi bi-clock-history text-info"></i>
                            {{ $device->last_heartbeat ? \Carbon\Carbon::parse($device->last_heartbeat)->diffForHumans() : '-' }}
                        </span>
                    </div>
                    <div class="col-5 text-end text-muted ">Added At : </div>
                    <div class="col-7">
                        <span data-bs-toggle="tooltip" title="{{ $device->created_at }}">
                            <i class="bi bi-calendar-event text-secondary"></i>
                            {{ $device->created_at ? \Carbon\Carbon::parse($device->created_at)->diffForHumans() : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'))
            tooltipTriggerList.forEach(function(el) {
                new bootstrap.Tooltip(el)
            });
        });
    </script>
@endpush
