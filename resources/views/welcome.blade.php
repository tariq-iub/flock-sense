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
                            <div class="community-icons">
                                <a href="#"><svg height="30" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M437.5 0h-363C33.5 0 0 33.5 0 74.5v363C0 478.5 33.5 512 74.5 512h363c41 0 74.5-33.5 74.5-74.5v-363C512 33.5 478.5 0 437.5 0zM160 422h-60V202h60v220zm-30-252c-19.9 0-36-16.1-36-36s16.1-36 36-36 36 16.1 36 36-16.1 36-36 36zm302 252h-60v-107c0-25.6-9.1-43.1-31.8-43.1-17.4 0-27.8 11.7-32.4 23-1.7 4.1-2.1 9.9-2.1 15.7V422h-60s.8-186 0-205h60v29c8-12.4 22.3-30.1 54.3-30.1 39.6 0 69.4 25.8 69.4 81.3V422z"></path></svg></a>
                                <a href="#"><svg height="30" viewBox="0 0 24 24"><path d="M22.54 6.42a8.63 8.63 0 0 1-2.5.69 4.35 4.35 0 0 0 1.9-2.4 8.72 8.72 0 0 1-2.75 1.05 4.35 4.35 0 0 0-7.41 3.96 12.35 12.35 0 0 1-8.97-4.55 4.36 4.36 0 0 0 1.35 5.82 4.29 4.29 0 0 1-2-.55v.06a4.35 4.35 0 0 0 3.5 4.26 4.36 4.36 0 0 1-1.14.15 4.29 4.29 0 0 1-.82-.08 4.35 4.35 0 0 0 4.06 3.02A8.71 8.71 0 0 1 2 19.54a12.29 12.29 0 0 0 6.66 1.95c8 0 12.37-6.63 12.37-12.38 0-.19 0-.39-.01-.58A8.78 8.78 0 0 0 24 5.11a8.63 8.63 0 0 1-2.46.68z"></path></svg></a>
                                <a href="#"><svg height="30" viewBox="0 0 24 24"><path d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 4.4 2.86 8.13 6.84 9.45.5.1.68-.22.68-.48v-1.7c-2.78.6-3.37-1.3-3.37-1.3-.45-1.16-1.1-1.48-1.1-1.48-.9-.62.07-.61.07-.61 1 .07 1.52 1.02 1.52 1.02.88 1.5 2.32 1.06 2.88.8.09-.64.34-1.07.62-1.31-2.22-.25-4.56-1.11-4.56-4.95 0-1.1.39-2 .1-2.71 0 0 .84-.27 2.75 1.02a9.51 9.51 0 0 1 5 0c1.91-1.3 2.75-1.02 2.75-1.02.3.71.1 1.61.05 2.71 0 3.85-2.34 4.69-4.57 4.94.36.31.68.91.68 1.85v2.75c0 .26.18.59.69.48a10.01 10.01 0 0 0 6.84-9.45c0-5.5-4.46-9.96-9.96-9.96z"></path></svg></a>
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

