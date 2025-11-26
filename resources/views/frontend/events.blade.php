@extends('frontend.layout.frontend')

@section('content')

    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">Upcoming Events</h1>
                    <p class="lead text-white-50 mb-0">Webinars, demos and meetups focused on modern poultry operations.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="section-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark">Webinar</span>
                            <span class="small text-muted"><i class="bi bi-calendar-event me-1"></i>Apr 24</span>
                        </div>
                        <h6 class="fw-semibold mb-1">Optimizing Ventilation & Welfare with IoT</h6>
                        <p class="small text-muted">A practical walkthrough of sensors, alarms and playbooks.</p>
                        <a href="#" class="btn btn-primary btn-sm">Register</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark">Live Demo</span>
                            <span class="small text-muted"><i class="bi bi-calendar-event me-1"></i>May 08</span>
                        </div>
                        <h6 class="fw-semibold mb-1">Farm Command Center: Interactive Tour</h6>
                        <p class="small text-muted">Dashboards, alerts and team workflows across sheds.</p>
                        <a href="#" class="btn btn-primary btn-sm">Reserve Seat</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark">Conference</span>
                            <span class="small text-muted"><i class="bi bi-calendar-event me-1"></i>Jun 12</span>
                        </div>
                        <h6 class="fw-semibold mb-1">Data‑Driven Poultry Summit</h6>
                        <p class="small text-muted">Benchmarks, case studies and sustainability reporting.</p>
                        <a href="#" class="btn btn-primary btn-sm">Get Details</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        (function () {
            if (typeof L === 'undefined') return;
            const mapEl = document.getElementById('officeMap');
            if (!mapEl) return;

            const map = L.map(mapEl, {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([40.0, 20.0], 2);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const locations = [
                { name: 'Norwich, UK', coords: [52.6309, 1.2974], addr: '23 Roundtree Cl, NR7 8SX' },
                { name: 'Islamabad, PK', coords: [33.6844, 73.0479], addr: '300 Street-17, G-15/2' }
            ];

            const markerIcon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [0, -32],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41],
                shadowAnchor: [14, 41]
            });

            const bounds = [];
            locations.forEach(loc => {
                L.marker(loc.coords, { icon: markerIcon })
                    .addTo(map)
                    .bindPopup(`<strong>${loc.name}</strong><br>${loc.addr}`);
                bounds.push(loc.coords);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [20, 20] });
            }

            L.control.zoom({ position: 'bottomright' }).addTo(map);
        })();
    </script>
@endpush
