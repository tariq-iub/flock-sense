@extends('frontend.layout.frontend')

@section('content')

    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">Blogs</h1>
                    <p class="lead text-white-50 mb-0">Insights, best practices and deep dives into smart poultry operations.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="section-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4">
                        <h5 class="fw-semibold">Designing Resilient IoT for Poultry Sheds</h5>
                        <p class="text-muted small">Connectivity choices (LoRaWAN, Wi‑Fi, NB‑IoT), store‑and‑forward, and safety interlocks.</p>
                        <a href="#" class="btn btn-primary btn-sm">Read more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4">
                        <h5 class="fw-semibold">From Telemetry to Actionable Insights</h5>
                        <p class="text-muted small">How anomaly detection and digital twins improve welfare and feed efficiency.</p>
                        <a href="#" class="btn btn-primary btn-sm">Read more</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100 p-4">
                        <h5 class="fw-semibold">Audit‑Ready Compliance in Poultry</h5>
                        <p class="text-muted small">Immutable logs, signatures and SOP acknowledgements for certifications.</p>
                        <a href="#" class="btn btn-primary btn-sm">Read more</a>
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
