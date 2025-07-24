
<footer class="footer pt-5 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('assets/img/logo-white.png') }}" height="50" class="me-2" alt="Flock Sense">
                </div>
                <p class="text-white-50">Transforming poultary farming with smart technology</p>
                <div>
                    <a href="#" class="me-2"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="me-2"><i class="fab fa-x"></i></a>
                    <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <h6>Products</h6>
                <ul class="list-unstyled">
                    <li><a href="#features" class="text-decoration-none">Features</a></li>
                    <li><a href="#" class="text-decoration-none">Solutions</a></li>
                    <li><a href="#" class="text-decoration-none">Pricing</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Resources</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-decoration-none">Documentation</a></li>
                    <li><a href="#" class="text-decoration-none">Blog</a></li>
                    <li><a href="#contact" class="text-decoration-none">Support</a></li>
                    <li><a href="#contact" class="text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Contact</h6>
                <p class="mb-1"><i class="fa fa-address-card"></i> 123 Farm Lane, AgriTech City</p>
                <p class="mb-1"><i class="fa fa-at"></i> info@flocksense.ai</p>
                <p class="mb-1"><i class="fa fa-phone"></i> +1 (800) 123-4567</p>
            </div>
        </div>
        <hr>
        <div class="text-white-50 text-center mt-4">&copy; 2025 Flock Sense. All rights reserved.</div>
    </div>
</footer>

<button class="scroll-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">↑</button>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
    const scrollBtn = document.querySelector('.scroll-to-top');
    window.addEventListener('scroll', () => {
        scrollBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
</script>

@stack('js')

</body>
</html>
