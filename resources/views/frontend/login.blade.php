@extends('frontend.layout.frontend')

@section('content')

    <!-- Login Hero -->
    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-white mb-3">Welcome back to smarter farm management</h1>
                    <p class="lead text-white-50 mb-4">Log in to monitor bird health, automate alerts and keep every
                        team
                        member aligned—wherever they are.</p>
                    <div class="row g-3 auth-highlight">
                        <div class="col-sm-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-phone"></i></span>
                                <h6 class="text-white mb-1">Access on any device</h6>
                                <p class="text-white-50 mb-0">Stay synced across sheds, hatcheries and head office.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-shield-lock"></i></span>
                                <h6 class="text-white mb-1">Enterprise-grade security</h6>
                                <p class="text-white-50 mb-0">Granular permissions and audit logs keep data secure.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 order-1 order-lg-2">
                    <div class="auth-card shadow-lg">
                        <h4 class="fw-bold mb-3 text-white">Log in</h4>
                        <p class="text-white-50 mb-4">Enter your credentials to access your dashboard.</p>
                        <form class="row g-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="col-12">
                                <label class="form-label text-white-50" for="loginEmail">Email</label>
                                <input type="text" id="loginEmail" name="email" class="form-control" placeholder="you@farm.com" required>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label text-white-50" for="loginPassword">Password</label>
                                    <a href="/forget-password" class="small text-white-50">Forgot Password?</a>
                                </div>
                                <input type="password" id="loginPassword" name="password" class="form-control"
                                       placeholder="********" required>
                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                                    <label class="form-check-label text-white-50" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <a href="/register" class="small fw-semibold text-primary">Create Account</a>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div class="auth-divider">
                            <span>or continue with</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-light"><i
                                    class="fa-brands fa-google me-2"></i>Google</button>
                            <button class="btn btn-outline-light"><i class="fa-brands fa-microsoft me-2"></i>Microsoft
                                Azure AD</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="section-light py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold text-dark mb-3">Single login for every farm role</h2>
                    <p class="text-muted mb-4">Growers, supervisors, nutritionists and veterinarians share the same
                        real-time view with permissions tailored to their tasks.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="auth-feature-tile">
                                <span class="auth-feature-tile__icon"><i class="bi bi-people"></i></span>
                                <h6 class="fw-semibold mb-1">Role-based dashboards</h6>
                                <p class="small text-muted mb-0">Only see the metrics and controls relevant to your
                                    responsibilities.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="auth-feature-tile">
                                <span class="auth-feature-tile__icon"><i class="bi bi-bell"></i></span>
                                <h6 class="fw-semibold mb-1">Smart alerts & tasks</h6>
                                <p class="small text-muted mb-0">Respond faster with personalised alerts across devices.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-video-card">
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
                            <iframe src="https://www.youtube.com/embed/uP7pHcX2unY" title="FlockSense overview"
                                    allowfullscreen></iframe>
                        </div>
                        <p class="small text-muted mt-3 mb-0">4-minute tour: from climate automation to financial
                            snapshots—see how growers stay ahead.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
