@extends('frontend.layout.frontend')

@section('content')

    <!-- Forgot Password Hero -->
    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-white mb-3">Forgot your password?</h1>
                    <p class="lead text-white-50 mb-4">Enter the email linked to your account. We’ll send a secure link
                        to reset your password and get you back to your flocks.</p>
                    <div class="row g-3 auth-highlight">
                        <div class="col-sm-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-shield-check"></i></span>
                                <h6 class="text-white mb-1">Secure by design</h6>
                                <p class="text-white-50 mb-0">Time-bound reset links protect your farm data.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-lightning-charge"></i></span>
                                <h6 class="text-white mb-1">Fast recovery</h6>
                                <p class="text-white-50 mb-0">Most users are back in under a minute.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-card shadow-lg">
                        <h4 class="fw-bold mb-2 text-white">Reset your password</h4>
                        <p class="text-white-50 mb-4">We'll email you a secure, time-limited reset link.</p>
                        <form class="row g-3" action="{{ route('forget') }}" method="POST">
                            @csrf
                            <div class="col-12">
                                <label class="form-label text-white-50" for="forgotEmail">Email Address</label>
                                <input type="email" id="forgotEmail" class="form-control" placeholder="you@farm.com"
                                       name="email" required>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-envelope-paper me-2"></i>Send Reset Link
                                </button>
                            </div>
                            <div class="col-12">
                                <p class="small text-white-50 mb-0">
                                    Can't access this email? <a href="{{ route('about') }}" class="text-primary">Contact Support</a> to
                                    verify your identity.
                                </p>
                            </div>
                        </form>
                        <div class="mt-3">
                            <a href="/login" class="small fw-semibold text-primary text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Back to login
                            </a>
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
                    <h2 class="fw-bold text-dark mb-3">How password reset works</h2>
                    <div class="timeline">
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning text-white">1</span>
                            <div>
                                <h6 class="fw-semibold mb-1">Request the link</h6>
                                <p class="text-muted small mb-0">Enter your email to receive a secure, time-limited
                                    reset link.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning text-white">2</span>
                            <div>
                                <h6 class="fw-semibold mb-1">Check your inbox</h6>
                                <p class="text-muted small mb-0">Open the email from FlockSense and click the reset
                                    button.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-dot bg-warning text-white">3</span>
                            <div>
                                <h6 class="fw-semibold mb-1">Create a new password</h6>
                                <p class="text-muted small mb-0">Choose a strong password to keep farms, flocks and data
                                    safe.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-feature-panel">
                        <h5 class="fw-bold text-dark mb-3">Best practices</h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Use 12+ characters
                                with numbers and symbols
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Enable two-factor
                                authentication after reset
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Never reuse
                                passwords across systems
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Use a password manager
                                eliminates the need to memorize
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>Regularly review your account
                                activity to spot unauthorized access quickly
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
