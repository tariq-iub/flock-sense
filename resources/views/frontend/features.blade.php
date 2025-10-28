@extends('frontend.layout.frontend')

@section('content')

    <!-- Features Hero -->
    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-bold text-white mb-3">Features for Smart Poultry Farming</h1>
                    <p class="lead text-white-50 mb-4">From 24/7 telemetry and automation to benchmarking, compliance
                        and sustainability — FlockSense unifies hardware, data and people into a single farm
                        performance platform.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-warning text-dark"><i class="bi bi-activity me-1"></i>Monitoring</span>
                        <span class="badge bg-warning text-dark"><i class="bi bi-cpu me-1"></i>Intelligence</span>
                        <span class="badge bg-warning text-dark"><i class="bi bi-shield-check me-1"></i>Security</span>
                        <span class="badge bg-warning text-dark"><i
                                class="bi bi-graph-up-arrow me-1"></i>Benchmarking</span>
                        <span class="badge bg-warning text-dark"><i class="bi bi-recycle me-1"></i>Sustainability</span>
                        <span class="badge bg-warning text-dark"><i class="bi bi-patch-check me-1"></i>Compliance</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="auth-card text-white">
                        <h5 class="mb-3">At a Glance</h5>
                        <!-- KPI Radar only (transparent background over hero) -->
                        <div>
                            <div style="position:relative;height:340px">
                                <canvas id="kpiRadar" style="background:transparent"></canvas>
                            </div>
                        </div>
                        <p class="small text-white-50 mt-3 mb-0 text-center">The featured KPIs delivered by our system.</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Overview grid -->
    <section class="section-light py-5">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-3">SMART FEATURES</h2>
            <p class="text-muted mb-4">Bridging the poultry performance gap with real‑time benchmarking and embedded
                energy management.</p>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="mb-3">
                            <img src="{{ asset('assets/img/clock.svg') }}" alt="24/7 Real-Time Monitoring" width="85" height="85">
                        </div>
                        <h5 class="fw-semibold">24/7 Real-Time Monitoring</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-patch-check" style="font-size:64px;color:#556D6F" aria-hidden="true"></i>
                        </div>
                        <h5 class="fw-semibold">Compliance & Assurance</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="mb-3">
                            <img src="{{ asset('assets/img/ops.svg') }}" alt="Operations & Labour Control" width="85" height="85">
                        </div>
                        <h5 class="fw-semibold">Operations & Labour Control</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-graph-up-arrow" style="font-size:64px;color:#556D6F" aria-hidden="true"></i>
                        </div>
                        <h5 class="fw-semibold">Benchmarking & Auditing</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Remote Monitoring -->
    <section class="section-dark py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0 order-2 order-md-2">
                    <h3 class="display-5 fw-bold mb-3">24/7 Live Farm Monitoring</h3>
                    <p class="lead mb-3">Manage and control complete operations with real-time team alerts.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Critical alerts on
                            temperature, humidity and gases</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Track mortality,
                            FCR, CV%, vaccination & medicine dosage</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Notifications for
                            feed delivery and equipment breakdown</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Alarms during
                            complete power shutdown</li>
                    </ul>
                </div>
                <div class="col-md-6 order-1 order-md-1 text-center">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                        <video src="{{ asset('assets/img/remote_monitoring.mp4') }}" poster="assets/img/remote_monitoring.png" autoplay
                               muted loop playsinline></video>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Farm Performance Suite -->
    <section class="section-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h3 class="display-5 fw-bold text-dark mb-3">Farm Performance Suite</h3>
                    <p class="lead mb-3">Complete farm management at your fingertips.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Role-based access to
                            mobile, tablet and web portals</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Operations and labour
                            control</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>SOPs for streamlined
                            operations</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Continuous
                            professional development of staff</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Liaise with
                            third‑party integrators</li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/img/farm_suite.png') }}" alt="Farm Performance Suite" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Data Driven Intelligence -->
    <section class="section-dark py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0 order-2 order-md-2">
                    <h3 class="display-5 fw-bold mb-3">Data Driven Intelligence</h3>
                    <p class="lead mb-3">Deep dive into your poultry farm data for valuable insights.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Real-time
                            interactive dashboards of all KPIs</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>AI‑powered
                            predictability of business outcomes</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Digital‑twins for
                            what‑if scenarios</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Daily autonomous
                            weight estimation</li>
                    </ul>
                </div>
                <div class="col-md-6 order-1 order-md-1 text-center">
                    <img src="{{ asset('assets/img/performance_intelligence.png') }}" alt="Data Driven Intelligence" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Bio & Physical Security -->
    <section class="section-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h3 class="display-5 fw-bold text-dark mb-3">Bio & Physical Security</h3>
                    <p class="lead mb-3">Secure the farm, secure humanity.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Entry disinfection
                            tracking</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Visitor logging and
                            access control</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Store‑room open‑door
                            monitoring</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Time‑stamped log of
                            shed entry</li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/img/security.png') }}" alt="Farm Security" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Benchmarking & Auditing -->
    <section class="section-dark py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0 order-2 order-md-2">
                    <h3 class="display-5 fw-bold mb-3">Benchmarking & Auditing</h3>
                    <p class="lead mb-3">Improve your farm produce to export quality.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Farm‑to‑farm
                            benchmarking</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>International
                            standards comparison</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Custom & scheduled
                            PDF and Excel reporting</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Real‑time equipment
                            and operations audit</li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/img/benchmarking.png') }}" alt="Benchmarking & Auditing" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Sustainability & Resource Management -->
    <section class="section-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h3 class="display-5 fw-bold text-dark mb-3">Sustainability & Resource Management</h3>
                    <p class="lead mb-3">Sub‑meter energy monitoring and real‑time tariff‑based alerts.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Eco‑friendly
                            ventilation and lighting schedules</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Integrated energy &
                            water intensity tracking</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Peak demand alerts
                            and generator runtime tracking</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-primary"></i>Cost of production by
                            batch and shed</li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/img/resource_management.png') }}" alt="Resource Management" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Compliance & Assurance -->
    <section class="section-dark py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0 order-2 order-md-2">
                    <h3 class="display-5 fw-bold mb-3">Compliance & Assurance</h3>
                    <p class="lead mb-3">Prove compliance with local and international standards.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Immutable audit
                            logs and role‑based access</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Standards mapping
                            and certification checklists</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Digital signatures
                            and report templates</li>
                        <li class="mb-2"><i class="fa-solid fa-circle-check me-2 text-secondary"></i>Automated evidence
                            capture and storage</li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/img/compliance.png') }}" alt="Compliance & Assurance" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA: Advanced Controllers -->
    <section class="section-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="display-6 fw-bold text-dark mb-2">Advanced Smart Controllers</h3>
                    <p class="mb-3 text-muted">Precision actuation for ventilation, heating, cooling and lighting with
                        safe fallbacks, OTA rules and schedules.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-cpu me-2 text-primary"></i>Edge rules engine with safety
                            interlocks</li>
                        <li class="mb-2"><i class="bi bi-wifi me-2 text-primary"></i>Backhaul: LoRaWAN, Wi‑Fi, NB‑IoT
                        </li>
                        <li class="mb-2"><i class="bi bi-shield-lock me-2 text-primary"></i>Secure device identity (PKI)
                            and OTA updates</li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <img src="{{ asset('assets/img/smart_controller.png') }}" alt="Advanced controllers" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <!-- Chart.js for KPI Radar -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // Initialize transparent KPI radar (normalized 0–100) when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('kpiRadar');
            if (!ctx) return;

            const labels = [
                'Energy Saving',
                'Feed Efficiency',
                'Weight Gain',
                'FCR',
                'PEF',
                'CV',
                'Telemetry Uptime',
                'Health',
                'Mortality Rate'
            ];

            // Demo values are normalized 0–100 for display only
            const values = [72, 78, 81, 65, 70, 60, 96, 84, 25];

            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Production KPIs',
                        data: values,
                        borderColor: 'rgba(135, 199, 108, 0.95)',
                        backgroundColor: 'rgba(135, 199, 108, 0.22)',
                        pointBackgroundColor: 'rgba(135, 199, 108, 1)',
                        pointBorderColor: '#ffffff',
                        pointRadius: 3,
                        pointHoverRadius: 4,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#ffffff' } },
                        tooltip: { enabled: true }
                    },
                    elements: { line: { tension: 0.2 } },
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            angleLines: { color: 'rgba(255,255,255,0.25)' },
                            grid: { color: 'rgba(255,255,255,0.18)' },
                            pointLabels: { color: '#ffffff', font: { size: 11 } },
                            ticks: { display: false, backdropColor: 'rgba(0,0,0,0)' }
                        }
                    }
                }
            });
        });
    </script>
@endpush
