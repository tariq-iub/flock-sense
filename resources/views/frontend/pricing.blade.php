@extends('frontend.layout.frontend')

@section('content')

    <!-- Pricing Hero -->
    <header class="hero-section pricing-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-success-subtle text-success mb-3">Flexible pricing for every flock</span>
                    <h1 class="display-4 fw-bold text-white mb-3">Choose the plan that scales with your farm</h1>
                    <p class="lead text-white-50 mb-4">Start free, add real-time monitoring when you are ready. Pay only
                        for the birds you raise.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#plans" class="btn btn-primary btn-lg">
                            View Plans <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#contact" class="btn btn-outline-light btn-lg">
                            Talk to Sales <i class="bi bi-chat-dots ms-2"></i>
                        </a>
                    </div>
                    <div class="row g-4 mt-4 text-white-50">
                        <div class="col-sm-4">
                            <div class="pricing-hero-stat">
                                <h5 class="text-white mb-1">126+</h5>
                                <p class="small mb-0">Flocks monitored daily</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="pricing-hero-stat">
                                <h5 class="text-white mb-1">18%</h5>
                                <p class="small mb-0">Average cost saving</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="pricing-hero-stat">
                                <h5 class="text-white mb-1">4.9/5</h5>
                                <p class="small mb-0">Customer satisfaction</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="pricing-hero-card">
                        <div class="pricing-hero-card__header">
                            <span class="badge bg-light text-dark mb-2">Getting started?</span>
                            <h4 class="text-white mb-0">Basic plan is always free</h4>
                        </div>
                        <ul class="list-unstyled mb-0 text-white-50">
                            <li class="mb-3"><i class="bi bi-check-circle text-primary me-2"></i>Daily farm logbook
                                &amp;
                                supervisor report</li>
                            <li class="mb-3"><i class="bi bi-check-circle text-primary me-2"></i>Feed &amp; water
                                tracking with alerts</li>
                            <li><i class="bi bi-check-circle text-primary me-2"></i>Finance ready expense &amp; sales
                                logs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Plans Section -->
    <section id="plans" class="section-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark mb-2">Transparent plans. Zero surprises.</h2>
                <p class="text-muted">Pick the subscription that matches your farm's growth stage. Upgrade or pause at
                    any time.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="plan-card pricing-plan h-100 p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge rounded-pill text-bg-success mb-2">Start here</span>
                                <h4 class="fw-bold mb-0">Basic</h4>
                            </div>
                            <div class="pricing-plan__price">$0</div>
                        </div>
                        <p class="text-muted mb-4">Ideal for teams going digital for the first time.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Daily supervisor report
                            </li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Farm logbook &amp; task
                                tracking</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>FCR, CV%, PEF auto
                                calculations</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Feed &amp; water
                                consumption analytics</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Expense &amp; sales
                                register</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="#contact" class="btn btn-outline-secondary w-100">Get started free</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plan-card pricing-plan featured h-100 p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge rounded-pill text-bg-warning mb-2">Most popular</span>
                                <h4 class="text-white fw-bold mb-0">Advanced</h4>
                            </div>
                            <div class="pricing-plan__price">$149<span class="pricing-plan__price-note">/mo</span></div>
                        </div>
                        <p class="text-white-50 mb-4">Unlock real-time automation for growing multi-site operations.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Everything in Basic
                            </li>
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Realtime indoor climate
                                monitor</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Autonomous weight
                                estimation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Feed &amp; water
                                activity alerts</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-primary me-2"></i>Huddling &amp;
                                movement detection</li>
                            <li><i class="bi bi-check-circle text-primary me-2"></i>Role-based access controls</li>
                        </ul>
                        <div class="d-grid gap-2">
                            <a href="#contact" class="btn btn-light text-dark">Book a demo</a>
                            <a href="#contact" class="btn btn-outline-light">Talk to product expert</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plan-card pricing-plan enterprise h-100 p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge rounded-pill text-bg-info mb-2">Tailored</span>
                                <h4 class="text-success fw-bold mb-0">Enterprise</h4>
                            </div>
                            <div class="pricing-plan__price">Custom</div>
                        </div>
                        <p class="text-muted mb-4">Built for integrators, contract growers and large-scale operations.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Dedicated success
                                manager</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Custom integrations
                                &amp;
                                API</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Unlimited device
                                onboarding</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>24/7 priority support
                            </li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>On-site training &amp; quarterly
                                audits</li>
                        </ul>
                        <div class="mt-auto">
                            <a href="#contact" class="btn btn-outline-secondary w-100">Schedule a strategy call</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value proposition -->
    <section class="section-dark py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <h2 class="fw-bold text-primary mb-3">Why farms choose FlockSense pricing</h2>
                    <p class="text-white-50 mb-4">A single platform that adapts to each growth stage. Start with smart
                        record keeping and layer on automation, AI and sustainability tools when you are ready.</p>
                    <div class="d-flex gap-3">
                        <div class="pricing-benefit-card">
                            <span class="pricing-benefit-card__icon"><i class="bi bi-broadcast"></i></span>
                            <h6 class="text-white mb-1">Modular hardware</h6>
                            <p class="small text-white-50 mb-0">Add climate sensors and cameras only where you need
                                them.</p>
                        </div>
                        <div class="pricing-benefit-card">
                            <span class="pricing-benefit-card__icon"><i class="bi bi-graph-up-arrow"></i></span>
                            <h6 class="text-white mb-1">Pay per live flock</h6>
                            <p class="small text-white-50 mb-0">Billing pauses automatically between production cycles.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="pricing-feature-tile">
                                <span class="pricing-feature-tile__icon"><i class="bi bi-lightning-charge"></i></span>
                                <h5 class="fw-bold mb-2">Rapid onboarding</h5>
                                <p class="text-muted mb-0">Import existing records, sync IoT devices and go live in
                                    days,
                                    not months.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pricing-feature-tile">
                                <span class="pricing-feature-tile__icon"><i class="bi bi-shield-check"></i></span>
                                <h5 class="fw-bold mb-2">Enterprise security</h5>
                                <p class="text-muted mb-0">Granular permissions, audit logs and encrypted data flows
                                    keep
                                    every stakeholder safe.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pricing-feature-tile">
                                <span class="pricing-feature-tile__icon"><i class="bi bi-cash-coin"></i></span>
                                <h5 class="fw-bold mb-2">Forecasting built-in</h5>
                                <p class="text-muted mb-0">Predict weight, feed consumption and revenue with confidence.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pricing-feature-tile">
                                <span class="pricing-feature-tile__icon"><i class="bi bi-people"></i></span>
                                <h5 class="fw-bold mb-2">Full team visibility</h5>
                                <p class="text-muted mb-0">Role-based dashboards keep growers, vets and supervisors
                                    aligned.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="section-light py-5">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <h2 class="fw-bold text-dark mb-3">Pricing, answered.</h2>
                    <p class="text-muted">Still comparing options? These quick answers help farms decide how to get
                        started.</p>
                    <div class="d-flex align-items-center gap-3 mt-4">
                        <i class="bi bi-headset fs-3 text-primary"></i>
                        <div>
                            <p class="mb-0 text-muted">Need a personalised walkthrough?</p>
                            <a href="#contact" class="fw-semibold text-dark">Book a 20 minute discovery call</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="accordion pricing-faq" id="pricingFaq">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseOne" aria-expanded="true"
                                        aria-controls="faqCollapseOne">
                                    How does "pay per live flock" billing work?
                                </button>
                            </h2>
                            <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne"
                                 data-bs-parent="#pricingFaq">
                                <div class="accordion-body">
                                    We only charge when a flock is active inside your sheds. Billing starts with day-old
                                    chicks and stops automatically at harvest. No flocks on site? Your subscription
                                    pauses
                                    at no extra cost.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseTwo" aria-expanded="false"
                                        aria-controls="faqCollapseTwo">
                                    Can we mix hardware and software plans?
                                </button>
                            </h2>
                            <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo"
                                 data-bs-parent="#pricingFaq">
                                <div class="accordion-body">
                                    Absolutely. Start with software-only logging on remote farms while deploying full
                                    sensor
                                    automation at flagship sites. You can add or remove devices and modules at any time.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseThree" aria-expanded="false"
                                        aria-controls="faqCollapseThree">
                                    Do you offer onboarding or training?
                                </button>
                            </h2>
                            <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree"
                                 data-bs-parent="#pricingFaq">
                                <div class="accordion-body">
                                    Yes. Every plan includes remote onboarding. Advanced and Enterprise subscribers also
                                    receive tailored implementation, on-site training days and quarterly optimisation
                                    sessions.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseFour" aria-expanded="false"
                                        aria-controls="faqCollapseFour">
                                    Can sensors integrate with our existing controllers?
                                </button>
                            </h2>
                            <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour"
                                 data-bs-parent="#pricingFaq">
                                <div class="accordion-body">
                                    Our hardware gateway speaks with the majority of poultry controllers through Modbus,
                                    TCP/IP
                                    or analogue bridges. Enterprise deployments include custom integrations and
                                    localised
                                    support.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
