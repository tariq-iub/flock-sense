@extends('layouts.app')

@section('title', 'IoT Device Map')

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #deviceMap { height: 600px; width: 100%; }
        .marker-label {
            font-size: 0.85rem;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">
                        <i class="bi bi-geo-alt-fill text-success"></i> IoT Devices Map
                    </h4>
                    <h6>View IoT devices map.</h6>
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
        <div class="card">
        <div class="card-body p-0">
            <div id="deviceMap"></div>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        const map = L.map('deviceMap').setView([30.1575, 71.5249], 6); // Default view: Pakistan

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        const sheds = @json($sheds);

        sheds.forEach(shed => {
            const lat = shed.farm.latitude;
            const lng = shed.farm.longitude;
            console.log(lat, lng);
            const shedName = shed.name;
            const farmName = shed.farm?.name ?? 'N/A';

            shed.shed_devices.forEach(link => {
                const device = link.device;
                const statusColor = device.is_online ? 'green' : 'red';
                const icon = L.divIcon({
                    html: `<i class="bi bi-cpu-fill" style="color:${statusColor}; font-size: 1.8rem;"></i>`,
                    className: 'custom-icon',
                    iconSize: [20, 20],
                    popupAnchor: [0, -10]
                });

                const popupHtml = `
                <div class="marker-label">
                    <strong>Shed:</strong> ${shedName}<br>
                    <strong>Farm:</strong> ${farmName}<br>
                    <strong>Device:</strong> ${device.serial_no}<br>
                    <strong>Status:</strong>
                        <span class="badge bg-${device.is_online ? 'success' : 'danger'}">
                            ${device.is_online ? 'Online' : 'Offline'}
                        </span><br>
                    <strong>Signal:</strong> ${device.signal_strength ?? '—'}<br>
                    <strong>Battery:</strong> ${device.battery_level ?? '—'}%
                </div>`;

                L.marker([lat, lng], { icon }).addTo(map).bindPopup(popupHtml);
            });
        });
    </script>
@endpush
