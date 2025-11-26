@extends('frontend.layout.frontend')

@section('content')

    <!-- Demo Login Hero (matches auth aesthetics) -->
    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-white mb-3">Demo access to the FlockSense dashboard</h1>
                    <p class="lead text-white-50 mb-4">Use a preconfigured demo account to explore features and layout.
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-unlock"></i></span>
                                <h6 class="text-white mb-1">Demo credentials</h6>
                                <p class="small text-white-50 mb-0">admin@demo.com or manager@demo.com<br>password: <code>password</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-speedometer2"></i></span>
                                <h6 class="text-white mb-1">Quick tour</h6>
                                <p class="small text-white-50 mb-0">On success, you’re redirected to the sample dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 order-1 order-lg-2">
                    <div class="auth-card shadow-lg">
                        <h4 class="fw-bold mb-3 text-white">Demo login</h4>
                        <p class="text-white-50 mb-4">Enter the demo email and password to continue.</p>
                        <div id="demoAlert" class="alert alert-danger d-none" role="alert" aria-live="polite"></div>
                        <form id="demoLoginForm" class="row g-3" novalidate>
                            <div class="col-12">
                                <label class="form-label text-white-50" for="demoEmail">Email</label>
                                <input type="email" id="demoEmail" class="form-control" placeholder="admin@demo.com" required>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label text-white-50" for="demoPassword">Password</label>
                                    <a href="#" class="small text-white-50">Forgot password?</a>
                                </div>
                                <input type="password" id="demoPassword" class="form-control" placeholder="password" required>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberDemo">
                                    <label class="form-check-label text-white-50" for="rememberDemo">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="small fw-semibold text-primary">Create account</a>
                            </div>
                            <div class="col-12 d-grid">
                                <button id="demoLoginBtn" type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div class="auth-divider">
                            <span>or continue with</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-light" type="button"><i class="fa-brands fa-google me-2"></i>Google</button>
                            <button class="btn btn-outline-light" type="button"><i class="fa-brands fa-microsoft me-2"></i>Microsoft Azure AD</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Secondary section (kept minimal for demo) -->
    <section class="section-light">
        <div class="container">
            <div class="row align-items-start g-4">
                <div class="col-lg-6">
                    <div class="auth-feature-panel">
                        <h5 class="fw-bold text-dark mb-3">What’s included</h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Read-only view of dashboard widgets</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Sample data to explore navigation</li>
                            <li class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Consistent UI with production look-and-feel</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-feature-panel">
                        <h5 class="fw-bold text-dark mb-3">Security Advisory: Demo Environment</h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>This is a demonstration system with client-side authentication validation.</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Data is not encrypted in transit or at rest.</li>
                            <li class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited login attempts allowed.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        // Simple client-side demo auth
        (function () {
            const form = document.getElementById('demoLoginForm');
            const emailEl = document.getElementById('demoEmail');
            const passwordEl = document.getElementById('demoPassword');
            const alertEl = document.getElementById('demoAlert');

            const allowedEmails = new Set(['admin@demo.com', 'manager@demo.com']);
            const requiredPassword = 'password'; // small p

            function showError(msg) {
                alertEl.textContent = msg;
                alertEl.classList.remove('d-none');
            }

            function hideError() {
                alertEl.classList.add('d-none');
                alertEl.textContent = '';
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                hideError();

                const email = (emailEl.value || '').trim().toLowerCase();
                const password = passwordEl.value || '';

                if (!allowedEmails.has(email)) {
                    showError('Use admin@demo.com or manager@demo.com.');
                    emailEl.focus();
                    return;
                }

                if (password !== requiredPassword) {
                    showError('Incorrect password. Hint: password');
                    passwordEl.focus();
                    return;
                }

                // Success: redirect to demo dashboard
                window.location.href = 'dashboard.html';
            });
        })();
    </script>
@endpush
