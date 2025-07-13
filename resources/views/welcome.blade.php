@extends('layouts.front')
@section('title', 'Smart Farm Management')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/landing.css') }}" rel="stylesheet">
@endpush

@section('content')

    <section class="alt-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-3 fw-bold">
                        <span style="color:#60776F">Smart</span> Farm Management
                    </h1>
                    <p class="fs-4 mb-4">
                        A Solution designed for Modern Poultry Farming
                    </p>
                    <div class="input-group mb-4">
                        <input type="text" class="form-control form-control-lg rounded-start-pill" placeholder="What's on your mind?">
                        <button class="btn btn-success rounded-end-pill">Message</button>
                    </div>

                    <div class="community">
                        <img src="https://randomuser.me/api/portraits/men/11.jpg">
                        <img src="https://randomuser.me/api/portraits/men/31.jpg">
                        <img src="https://randomuser.me/api/portraits/men/41.jpg">
                        <div style="margin-left: 2.5rem;">
                            <p class="fs-3">Join our Community<br>We’re waiting for you</p>
                            <div class="community-icons fs-2">
                                <a href="#" class="text-muted text-decoration-none me-2"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="text-muted text-decoration-none me-2"><i class="fab fa-x"></i></a>
                                <a href="#" class="text-muted text-decoration-none"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 position-relative text-center">
                    <div class="image-frame">
                        <div class="gray-square" style="top:-30px; left:-30px;"></div>
                        <div class="gray-square" style="top:40px; left:60px;"></div>
                        <div class="gray-square" style="top:100px; left:0px;"></div>
                        <img src="{{ asset('assets/img/chicken.png') }}" class="img-fluid main-img rounded-4" alt="Chicken">
                        <div class="gray-square" style="bottom:170px; right:60px;"></div>
                        <div class="gray-square" style="bottom:40px; right:60px;"></div>
                        <div class="gray-square" style="bottom:100px; right:0px;"></div>
                        <div class="badge-circle" style="position:absolute; bottom:-10px; left:10px;">
                            <svg width="160" height="160" viewBox="0 0 160 160">
                                <circle cx="80" cy="80" r="78" fill="#e5ffbe"/>
                                <text font-size="16" fill="#33413d" font-family="Outfit, sans-serif" font-weight="700">
                                    <textPath xlink:href="#topcurve" startOffset="20%">FARMERS CHOICE</textPath>
                                    <textPath xlink:href="#botcurve" startOffset="20%">FARMERS CHOICE</textPath>
                                </text>
                                <defs>
                                    <path id="topcurve" d="M 23,80 A 57,57 0 1,1 137,80" fill="none"/>
                                    <path id="botcurve" d="M 137,80 A 57,57 0 1,1 23,80" fill="none"/>
                                </defs>
                                <!-- Center number -->
                                <text x="50%" y="60%" text-anchor="middle" fill="#33413d" font-size="64" font-family="Outfit, sans-serif" font-weight="700">#1</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="models" class="alt-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('assets/img/model.png') }}" class="img-fluid rounded-3" alt="Model Image">
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold mb-3">Advanced Monitoring Models</h2>
                    <p>
                        FlockSense delivers integrated hardware and software models, enabling intelligent, automated control and detailed insight into your poultry environment.
                    </p>
                    <ul class="list-unstyled fs-5">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-thermometer-sun text-success me-3 fs-3"></i>
                            <div>
                                <strong>Climate Sensors</strong><br>
                                Real-time monitoring of temperature and humidity for optimal flock health.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-droplet-half text-success me-3 fs-3"></i>
                            <div>
                                <strong>Gas & Air Quality</strong><br>
                                Continuous detection of CO₂ and ammonia for a safer environment.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-lightbulb text-success me-3 fs-3"></i>
                            <div>
                                <strong>Smart Lighting</strong><br>
                                Automated control of light intensity and duration for improved growth.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-alarm text-success me-3 fs-3"></i>
                            <div>
                                <strong>Feed & Equipment Alerts</strong><br>
                                Immediate notifications for feed delivery, equipment issues, and power outages.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-sliders2-vertical text-success me-3 fs-3"></i>
                            <div>
                                <strong>Customizable Automation</strong><br>
                                Flexible models for single houses or complex, multi-site farms.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="features" class="alt-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-4">Smart Features</h2>
                    <ul class="list-unstyled fs-5">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-speedometer2 text-success me-3 fs-3"></i>
                            <div>
                                <strong>Real-Time Monitoring</strong><br>
                                24/7 sensor-based tracking of farm conditions.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-bar-chart-line text-success me-3 fs-3"></i>
                            <div>
                                <strong>Live Dashboards</strong><br>
                                Visual KPIs, accessible on web and mobile.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-robot text-success me-3 fs-3"></i>
                            <div>
                                <strong>AI Insights & Alerts</strong><br>
                                Predictive analytics and instant notifications.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-graph-up-arrow text-success me-3 fs-3"></i>
                            <div>
                                <strong>Autonomous Weight Estimation</strong><br>
                                Automated, daily flock weight updates.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-shield-lock text-success me-3 fs-3"></i>
                            <div>
                                <strong>Bio-Security</strong><br>
                                Advanced safety and critical event alarms.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-phone text-success me-3 fs-3"></i>
                            <div>
                                <strong>Mobile Access</strong><br>
                                Manage your farm from anywhere.
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('assets/img/features.png') }}" class="img-fluid rounded-3" alt="Features Image">
                </div>
            </div>
        </div>
    </section>
    <section id="services" class="alt-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('assets/img/services.png') }}" class="img-fluid rounded-3" alt="Services">
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold mb-4">Our Services</h2>
                    <ul class="list-unstyled fs-5">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-globe2 text-success me-3 fs-3"></i>
                            <div>
                                <strong>Remote Management</strong><br>
                                Monitor and control your farm from anywhere.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-person-check text-success me-3 fs-3"></i>
                            <div>
                                <strong>Role-Based Access</strong><br>
                                Custom permissions for staff and managers.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-diagram-3 text-success me-3 fs-3"></i>
                            <div>
                                <strong>Operations Control</strong><br>
                                Streamlined SOPs and labor management.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-award text-success me-3 fs-3"></i>
                            <div>
                                <strong>Staff Training</strong><br>
                                Continuous team development tools.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-plug text-success me-3 fs-3"></i>
                            <div>
                                <strong>Third-Party Integration</strong><br>
                                Connect easily with other farm solutions.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-lightning-charge text-success me-3 fs-3"></i>
                            <div>
                                <strong>Energy Optimization</strong><br>
                                Smart controls to save energy.
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-headset text-success me-3 fs-3"></i>
                            <div>
                                <strong>24/7 Support</strong><br>
                                Always-on expert assistance.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="trusted" class="trusted-section position-relative py-5">
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="mb-4 d-flex align-items-center">
                        <span class="trusted-title me-3">TRUSTED</span>
                        <!-- Inline SVG checkmark badge -->
                        <span class="trusted-badge">
                <svg width="80" height="80" viewBox="0 0 80 80">
                  <circle cx="40" cy="40" r="39" fill="#33413d"/>
                  <path d="M60 27L35 52l-10-10" fill="none" stroke="#d7fc97" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
                    </div>
                    <h3 class="fw-normal trusted-sub mb-3">by Leading Poultry Producers</h3>
                    <div class="trusted-desc mb-5">
                        Empowering farmers to achieve global benchmarks<br>
                        of productivity and sustainability
                    </div>
                    <div class="row g-3 trusted-stats">
                        <div class="col-6 col-md-3">
                            <div class="trusted-stat-card text-center">
                                <div class="trusted-stat-value">500+</div>
                                <div class="trusted-stat-label">Farms</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="trusted-stat-card text-center">
                                <div class="trusted-stat-value">1M+</div>
                                <div class="trusted-stat-label">Birds Monitored</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="trusted-stat-card text-center">
                                <div class="trusted-stat-value">99%</div>
                                <div class="trusted-stat-label">Accuracy</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="trusted-stat-card text-center">
                                <div class="trusted-stat-value">24/7</div>
                                <div class="trusted-stat-label">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block position-relative">
                    <!-- The beak.svg image (right-side background) -->
                    <img src="{{ asset('assets/img/beak.png') }}" alt="Beak"
                         class="trusted-beak-img top-0 end-0" style="z-index:-99;">
                </div>
            </div>
        </div>
    </section>

@endsection

