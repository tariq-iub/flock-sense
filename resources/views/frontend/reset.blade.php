@extends('frontend.layout.frontend')

@section('content')

    <!-- Reset Password Hero -->
    <header class="hero-section auth-hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-white mb-3">Create a new password</h1>
                    <p class="lead text-white-50 mb-4">Choose a strong password to keep your farm data secure. You’ll be
                        signed in after a successful reset.</p>
                    <div class="row g-3 auth-highlight">
                        <div class="col-sm-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-lock-fill"></i></span>
                                <h6 class="text-white mb-1">Strong by default</h6>
                                <p class="text-white-50 mb-0">Guided rules help you set a robust password.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="auth-highlight-card">
                                <span class="auth-highlight-card__icon"><i class="bi bi-shield-check"></i></span>
                                <h6 class="text-white mb-1">Secure flow</h6>
                                <p class="text-white-50 mb-0">Encrypted end-to-end reset with device checks.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-card shadow-lg">
                        <h4 class="fw-bold mb-2 text-white">Reset Your Password</h4>
                        <p class="text-white-50 mb-4">Enter and confirm your new password.</p>
                        <form id="resetForm" class="row g-3" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token ?? '' }}">
                            <input type="hidden" name="email" value="{{ $email ?? request('email') }}">
                            <div class="col-12">
                                <label class="form-label text-white-50" for="newPassword">New password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="newPassword" name="password" placeholder="••••••••••"
                                           autocomplete="new-password" required>
                                    <button class="btn btn-outline-light" type="button" id="toggleNew" aria-label="Show password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white-50" for="confirmPassword">Confirm password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                                           placeholder="••••••••••" autocomplete="new-password" required>
                                    <button class="btn btn-outline-light" type="button" id="toggleConfirm" aria-label="Show password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="progress" role="progressbar" aria-label="Password strength" aria-valuemin="0" aria-valuemax="100">
                                    <div id="strengthBar" class="progress-bar bg-danger" style="width: 0%"></div>
                                </div>
                                <p id="strengthText" class="small text-white-50 mt-1 mb-0">Strength: too weak</p>
                            </div>

                            <div class="col-12">
                                <ul class="small text-white-50 mb-0 list-unstyled" id="rules">
                                    <li id="rule-length"><i class="bi bi-dot me-1"></i>8+ characters</li>
                                    <li id="rule-upper"><i class="bi bi-dot me-1"></i>At least one uppercase letter</li>
                                    <li id="rule-lower"><i class="bi bi-dot me-1"></i>At least one lowercase letter</li>
                                    <li id="rule-number"><i class="bi bi-dot me-1"></i>At least one number</li>
                                    <li id="rule-symbol"><i class="bi bi-dot me-1"></i>At least one symbol</li>
                                    <li id="rule-match"><i class="bi bi-dot me-1"></i>Passwords match</li>
                                </ul>
                            </div>

                            <div class="col-12 d-grid">
                                <button id="submitBtn" type="submit" class="btn btn-primary" disabled>
                                    <i class="bi bi-arrow-repeat me-2"></i>Update Password
                                </button>
                            </div>
                            <div class="col-12">
                                <p class="small text-white-50 mb-0">
                                    Having trouble? <a href="{{ route('about') }}" class="text-primary">Contact Support</a>.</p>
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
                    <h2 class="fw-bold text-dark mb-3">Tips for a great password</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="auth-feature-tile">
                                <span class="auth-feature-tile__icon"><i class="bi bi-lightbulb"></i></span>
                                <h6 class="fw-semibold mb-1">Use passphrases</h6>
                                <p class="small text-muted mb-0">Combine memorable words plus numbers and symbols.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="auth-feature-tile">
                                <span class="auth-feature-tile__icon"><i class="bi bi-shield-lock"></i></span>
                                <h6 class="fw-semibold mb-1">Avoid reuse</h6>
                                <p class="small text-muted mb-0">Never reuse passwords across other systems.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="auth-feature-tile">
                                <span class="auth-feature-tile__icon"><i class="bi bi-key"></i></span>
                                <h6 class="fw-semibold mb-1">Unique for teams</h6>
                                <p class="small text-muted mb-0">Keep personal and shared credentials separate.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="auth-feature-tile">
                                <span class="auth-feature-tile__icon"><i class="bi bi-qr-code-scan"></i></span>
                                <h6 class="fw-semibold mb-1">Enable 2FA</h6>
                                <p class="small text-muted mb-0">Turn on multi-factor after reset for extra security.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-feature-panel">
                        <h5 class="fw-bold text-dark mb-3">After you reset</h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>We sign you in automatically</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>All active sessions are reviewed</li>
                            <li class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Re-link devices if prompted</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-dark py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h2 class="fw-bold text-primary mb-2">Enterprise-grade security</h2>
                    <p class="text-white-50 mb-0">Reset flows are encrypted, audited and protected with anomaly detection
                        to keep your operations safe.</p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="support-card">
                                <span class="support-card__icon"><i class="bi bi-fingerprint"></i></span>
                                <h6 class="text-white mb-1">Device attestation</h6>
                                <p class="text-white-50 small mb-0">Checks devices during sensitive changes.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="support-card">
                                <span class="support-card__icon"><i class="bi bi-shield-lock"></i></span>
                                <h6 class="text-white mb-1">Encrypted storage</h6>
                                <p class="text-white-50 small mb-0">Passwords hashed with industry best practices.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        (function () {
            const newPw = document.getElementById('newPassword');
            const confirmPw = document.getElementById('confirmPassword');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const submitBtn = document.getElementById('submitBtn');

            const rules = {
                length: document.getElementById('rule-length'),
                upper: document.getElementById('rule-upper'),
                lower: document.getElementById('rule-lower'),
                number: document.getElementById('rule-number'),
                symbol: document.getElementById('rule-symbol'),
                match: document.getElementById('rule-match'),
            };

            function assess(pw) {
                const checks = {
                    length: pw.length >= 8,
                    upper: /[A-Z]/.test(pw),
                    lower: /[a-z]/.test(pw),
                    number: /\d/.test(pw),
                    symbol: /[^A-Za-z0-9]/.test(pw),
                };
                let score = Object.values(checks).filter(Boolean).length;
                return { checks, score };
            }

            function paintRule(el, ok) {
                el.classList.toggle('text-success', ok);
                el.classList.toggle('text-white-50', !ok);
            }

            function update() {
                const pw = newPw.value;
                const { checks, score } = assess(pw);
                const matches = pw.length > 0 && pw === confirmPw.value;

                paintRule(rules.length, checks.length);
                paintRule(rules.upper, checks.upper);
                paintRule(rules.lower, checks.lower);
                paintRule(rules.number, checks.number);
                paintRule(rules.symbol, checks.symbol);
                paintRule(rules.match, matches);

                let pct = (score / 5) * 100;
                strengthBar.style.width = pct + '%';
                strengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');
                if (pct < 40) {
                    strengthBar.classList.add('bg-danger');
                    strengthText.textContent = 'Strength: too weak';
                } else if (pct < 80) {
                    strengthBar.classList.add('bg-warning');
                    strengthText.textContent = 'Strength: fair';
                } else {
                    strengthBar.classList.add('bg-success');
                    strengthText.textContent = 'Strength: strong';
                }

                submitBtn.disabled = !(score === 5 && matches);
            }

            newPw.addEventListener('input', update);
            confirmPw.addEventListener('input', update);
            update();

            function toggle(id, btnId) {
                const input = document.getElementById(id);
                const btn = document.getElementById(btnId);
                btn.addEventListener('click', () => {
                    const isPw = input.type === 'password';
                    input.type = isPw ? 'text' : 'password';
                    const icon = btn.querySelector('i');
                    icon.classList.toggle('bi-eye', !isPw);
                    icon.classList.toggle('bi-eye-slash', isPw);
                });
            }
            toggle('newPassword', 'toggleNew');
            toggle('confirmPassword', 'toggleConfirm');
        })();
    </script>
@endpush
