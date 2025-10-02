@extends('frontend.layout.frontend')

@section('content')

    <!-- Register hero -->
    <header class="hero-section auth-hero register-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-white mb-3">Create your FlockSense account</h1>
                    <p class="lead text-white-50 mb-4">
                        Bring every flock, facility and team member together. Start with
                        a basic plan, no credit card required.
                    </p>
                    <ul class="auth-benefits list-unstyled text-white-50 mb-0">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Unlimited collaborators with role-based access
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Automated alerts for mortality, feed and climate
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            ne-page ROI model tailored to your flock size and costs
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Live sustainability dashboard for auditors and integrators
                        </li>
                    </ul>
                </div>

                <div class="col-lg-5 offset-lg-1 order-1 order-lg-2">
                    <div class="auth-card shadow-lg">
                        <h4 class="fw-bold mb-3 text-white">Create Your Account</h4>
                        <p class="text-white-50 mb-4">Tell us about your farm to personalise recommendations.</p>
                        <form class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white-50" for="registerName">Full Name</label>
                                <input type="text" id="registerName" class="form-control" placeholder="Adeel Ahmed"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50" for="registerCompany">Farm / Company</label>
                                <input type="text" id="registerCompany" class="form-control"
                                       placeholder="FlockSense Farms" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50" for="registerPhone">Phone</label>
                                <input type="tel" id="registerPhone" class="form-control" placeholder="0300 1234567"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50" for="registerEmail">Email</label>
                                <input type="email" id="registerEmail" class="form-control" placeholder="you@farm.com"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50" for="registerPassword">Password</label>
                                <input type="password" id="registerPassword" class="form-control" placeholder="********"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white-50" for="registerConfirm">Confirm Password</label>
                                <input type="password" id="registerConfirm" class="form-control" placeholder="********"
                                       required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white-50" for="registerRole">Primary Role</label>
                                <select id="registerRole" class="form-select" required>
                                    <option value="" selected disabled>Select your role</option>
                                    <option>Farm Owner / Grower</option>
                                    <option>Farm Supervisor</option>
                                    <option>Nutritionist / Vet</option>
                                    <option>Integrator / Processor</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white-50" for="registerScale">
                                    Annual Bird Placement
                                </label>
                                <select id="registerScale" class="form-select" required>
                                    <option value="" selected disabled>Select Scale</option>
                                    <option>Up to 50,000 birds</option>
                                    <option>50,000 - 250,000 birds</option>
                                    <option>250,000 - 1 million birds</option>
                                    <option>1 million+ birds</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="terms">
                                    <label class="form-check-label text-white-50" for="terms">
                                        I agree to the
                                        <a href="javascript:void(0);" class="link-warning" data-bs-toggle="modal" data-bs-target="#termsPrivacyModal">
                                            Terms & Privacy Policy
                                        </a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary">Create Account</button>
                            </div>
                        </form>
                        <p class="text-white-50 small mt-3 mb-0">
                            Already using FlockSense?
                            <a href="/login" class="fw-semibold text-primary">Log in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="section-light py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold text-dark mb-3">Launch with confidence in 3 simple steps</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning text-white">1</span>
                            <div>
                                <h6 class="fw-semibold mb-1">Share your farm profile</h6>
                                <p class="text-muted small mb-0">Tell us about sheds, hardware and current processes so
                                    we can tailor the rollout.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning text-white">2</span>
                            <div>
                                <h6 class="fw-semibold mb-1">Connect sensors and devices</h6>
                                <p class="text-muted small mb-0">Our specialists help connect controllers, climate
                                    stations and mobile users in under a week.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning text-white">3</span>
                            <div>
                                <h6 class="fw-semibold mb-1">Train your team</h6>
                                <p class="text-muted small mb-0">Role-based training ensures supervisors and growers
                                    adopt the platform from day one.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-feature-panel">
                        <h5 class="fw-bold text-dark mb-3">Included in every new account</h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Basic plan include farm
                                monitoring
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Preconfigured data retention,
                                role scopes, and immutable logs
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>IoT hardware
                                onboarding session
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Executive dashboard preloaded
                                with industry KPIs (FCR, PEF, CV, livability)
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Dedicated success
                                manager
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Access to community playbooks
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- One-Pager Terms & Privacy Modal -->
    <div class="modal fade" id="termsPrivacyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">FlockSense — Terms & Privacy</h5>
                        <small class="text-muted">
                            Effective: October 1, 2025 • Jurisdiction: Pakistan • Support: 9:00 AM–5:00 PM (PKT)
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- TERMS (condensed) -->
                    <section class="mb-4">
                        <h6 class="text-uppercase text-primary">Terms of Service</h6>
                        <ol class="ps-3 mb-0">
                            <li><strong>Who we are.</strong> “FlockSense”, “we”, “us” provides IoT-enabled poultry farm tools (devices, dashboards, audit prep, exports). Contact: <a href="mailto:support@flocksense.example">support@flocksense.example</a>.</li>
                            <li><strong>Agreement.</strong> By using our web/mobile apps, APIs, or connecting devices, you accept these Terms on behalf of yourself or your organization.</li>
                            <li><strong>Accounts.</strong> Keep registration info accurate and credentials secure. You’re responsible for activity under your account.</li>
                            <li><strong>Subscriptions & fees.</strong> Paid features require an active plan. Fees are billed per plan or order form; taxes are your responsibility.</li>
                            <li><strong>Availability.</strong> We target high uptime but don’t guarantee uninterrupted service. Maintenance and emergencies may occur. SLAs apply only if agreed in writing.</li>
                            <li><strong>Devices & firmware.</strong> You’re responsible for safe installation and operation. Supported devices may receive OTA firmware updates.</li>
                            <li><strong>Informational only.</strong> Outputs (alerts, analytics, scores) are informational and not veterinary, medical, safety, or legal advice.</li>
                            <li><strong>Your data.</strong> You own your input/operational data; you grant us a license to host/process it to provide and improve the service.</li>
                            <li><strong>Acceptable use.</strong> Don’t break the law, infringe rights, upload malware, abuse the API, or attempt unauthorized access. We may suspend/terminate for violations.</li>
                            <li><strong>AI/ML features.</strong> We use ML to power features for your account. We will not use identifiable farm data to train generalized models without your explicit opt-in.</li>
                            <li><strong>Third-party services.</strong> Integrations are governed by their own terms; we aren’t responsible for their acts/omissions.</li>
                            <li><strong>IP & feedback.</strong> We retain rights to our software/brand. You grant us a license to use feedback.</li>
                            <li><strong>Privacy.</strong> See “Privacy” below. A DPA is available upon request.</li>
                            <li><strong>Termination.</strong> Either party may terminate as permitted. We provide a reasonable data-export window unless prohibited by law.</li>
                            <li><strong>Disclaimers & limitation.</strong> Service is “as is.” To the extent allowed by law, we disclaim implied warranties. Our total liability is limited to amounts paid in the 12 months before the claim; no indirect or consequential damages.</li>
                            <li><strong>Governing law.</strong> Pakistan law governs. Venue is the competent courts in Pakistan unless we agree otherwise in writing.</li>
                        </ol>
                    </section>

                    <hr>

                    <!-- PRIVACY (condensed) -->
                    <section class="mb-2">
                        <h6 class="text-uppercase text-primary">Privacy Policy</h6>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <ul class="ps-3 mb-2">
                                    <li><strong>Data we collect:</strong> account/contact data; farm/shed/flock and operational records; device/telemetry (e.g., temperature, humidity, NH₃, CO₂, firmware); usage/cookies; support communications; integration data you connect.</li>
                                    <li><strong>How we use it:</strong> provide/secure our services; configure devices and firmware; analytics/alerts; product improvement; service notices and security alerts (marketing only with consent where required); legal compliance.</li>
                                    <li><strong>Sharing:</strong> processors (hosting, storage, analytics, comms, billing) under contract; integrations you authorize; advisors; lawful authorities; corporate transactions with safeguards. We do <em>not</em> sell personal data.</li>
                                </ul>
                            </div>
                            <div class="col-12 col-md-6">
                                <ul class="ps-3 mb-2">
                                    <li><strong>Legal bases (where applicable):</strong> contract performance, legitimate interests (security/improvement), consent (certain cookies/marketing), legal obligations.</li>
                                    <li><strong>Security & retention:</strong> encryption in transit, access controls, monitoring; retain as needed for service/legal duties; aggregated/anonymous data may be kept.</li>
                                    <li><strong>Your rights:</strong> subject to law, request access, correction, deletion, restriction, portability, or object; withdraw consent where used. Contact: <a href="mailto:privacy@flocksense.example">privacy@flocksense.example</a>.</li>
                                    <li><strong>AI/ML controls:</strong> identifiable farm data won’t be used to train generalized models without your explicit opt-in; manage via in-app settings or by email.</li>
                                </ul>
                            </div>
                        </div>
                        <p class="small text-primary mb-0">
                            Questions? <a href="mailto:privacy@flocksense.example">privacy@flocksense.example</a> • Support: 9:00 AM–5:00 PM (PKT)
                        </p>
                    </section>
                </div>

                <div class="modal-footer flex-column flex-sm-row align-items-stretch gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Enable "Accept" only when the checkbox is checked
        (function(){
            const cb = document.getElementById('agreeTP');
            const btn = document.getElementById('acceptTP');
            if(cb && btn){
                cb.addEventListener('change', ()=> btn.disabled = !cb.checked);
                btn.addEventListener('click', ()=>{
                    // Example: persist consent (localStorage or your API)
                    localStorage.setItem('flocksense_accept_tnp', String(Date.now()));
                    // Close modal (Bootstrap)
                    const modalEl = document.getElementById('termsPrivacyModal');
                    const instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    instance.hide();
                });
            }
        })();
    </script>
@endpush
