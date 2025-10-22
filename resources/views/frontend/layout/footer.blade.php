<!-- Back to Top Button -->
<button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
    <span class="visually-hidden">Back to top</span>
    <span class="back-to-top__icon"><i class="bi bi-arrow-up"></i></span>
</button>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                <img src="{{ asset(settings('company.logo')) }}" alt="FlockSense Logo" class="footer-logo mb-4">
                <p class="small">{{ settings('company.slogan') }}</p>
                <div class="d-flex gap-3 mt-3">
                @foreach(settings_group('social') as $row)
                   <a href="{{ $row['id'] }}" class="text-secondary fs-5">
                       <i class="text-white fa-brands {{ $row['icon'] }}"></i>
                   </a>
                @endforeach
                </div>
            </div>
            <div class="col-lg-2">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="#solutions" class="footer-link">Solutions</a></li>
                    <li><a href="#resources" class="footer-link">Resources</a></li>
                    <li><a href="pricing.html" class="footer-link">Pricing</a></li>
                    <li><a href="#partners" class="footer-link">Partners</a></li>
                    <li><a href="#about" class="footer-link">About Us</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-bold mb-3">Contact</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="text-white fa-solid fa-envelope me-2"></i>{{ settings('contact.email') }}</li>
                    <li class="mb-2"><i class="text-white fa-solid fa-phone me-2"></i>{{ settings('contact.phone') }}</li>
                    <li>
                        <i class="text-white fa-solid fa-location-dot me-2"></i>{{ settings('contact.address1') }}
                    </li>
                    <li>
                        <i class="text-white fa-solid fa-location-dot me-2"></i>{{ settings('contact.address2') }}
                    </li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold mb-3">Newsletter</h6>
                <p class="small">Stay updated with our latest features and releases.</p>
                <form class="d-flex flex-column" action="#" method="POST" onsubmit="return false;">
                    <div class="mb-2">
                        <input type="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
        <hr class="border-secondary mt-5">
        <p class="small text-center mb-0">&copy; 2025 {{ settings('company.name') }}. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- Leaflet JS -->
<script src="{{ asset('assets/js/leaflet.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('assets/js/landing.js') }}"></script>

@stack('js')

</body>

</html>
