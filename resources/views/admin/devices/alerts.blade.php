@extends('layouts.app')

@section('title', 'Device Alerts')

@section('content')
    <div class="content pb-0">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">IoT Devices</div>
                    </div>
                    <div class="card-body">
                        <div class="list-group" id="deviceList">
                            @foreach($devices as $device)
                                <a href="javascript:void(0)"
                                   class="list-group-item list-group-item-action d-flex w-100 justify-content-between device-item"
                                   data-device-id="{{ $device->id }}">
                                    <div>
                                        <h5 class="text-info fs-14 mb-1">
                                            <i class="bi bi-cpu-fill me-2"></i>
                                            {{ $device->serial_no }}
                                        </h5>
                                        <small class="text-body-secondary">
                                            Model: {{ $device->model_number }}<br>
                                            Firmware: {{ $device->firmware_version }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="text-info">{{ $device->currentShed()?->shed->name }}</span>
                                        <br>
                                        <small class="text-body-secondary">
                                            Link {{ $device->currentShed()?->link_date->diffForHumans() }}<br>
                                            {{ $device->currentShed()?->shed->farm->name }}
                                        </small>
                                    </div>

                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8" id="deviceDetailsContainer">

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function(){
            $('#deviceList').on('click', '.device-item', function() {
                const deviceId = $(this).data('device-id');
                const $container = $('#deviceDetailsContainer');

                $container.html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading alerts for device ID: ${deviceId}...</p>
                    </div>
                `);

                $.get(`/admin/iot/events-data/${deviceId}`)
                    .done(function(data) {
                        $container.html(data.html);
                    })
                    .fail(function() {
                        $container.html(`
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Failed to load IoT device alerts.
                            </div>
                        `);
                    });
            });
        });
    </script>
@endpush
