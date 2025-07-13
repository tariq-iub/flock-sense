<section id="contact" class="alt-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="{{ asset('assets/img/contact.jpg') }}" class="img-fluid rounded-3" alt="Contact Image">
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">Contact Us</h2>
                <form>
                    <input type="text" class="form-control mb-3" placeholder="Your Name">
                    <input type="email" class="form-control mb-3" placeholder="Your Email">
                    <textarea class="form-control mb-3" rows="4" placeholder="Your Message"></textarea>
                    <button class="btn btn-success">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="footer pt-5 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('assets/img/logo.png') }}" height="50" class="me-2" alt="Flock Sense">
                </div>
                <p class="text-muted">Transforming poultary farming with smart technology</p>
                <div>
                    <a href="#" class="text-muted me-2"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-muted me-2"><i class="fab fa-x"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <h6>Products</h6>
                <ul class="list-unstyled">
                    <li><a href="#features" class="text-muted text-decoration-none">Features</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Solutions</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Pricing</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Resources</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-muted text-decoration-none">Documentation</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Blog</a></li>
                    <li><a href="#contact" class="text-muted text-decoration-none">Support</a></li>
                    <li><a href="#contact" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Contact</h6>
                <p class="text-muted mb-1"><i class="fa fa-address-card"></i> 123 Farm Lane, AgriTech City</p>
                <p class="text-muted mb-1"><i class="fa fa-at"></i> info@flocksense.ai</p>
                <p class="text-muted"><i class="fa fa-phone"></i> +1 (800) 123-4567</p>
            </div>
        </div>
        <hr>
        <div class="text-center mt-4">&copy; 2025 Flock Sense. All rights reserved.</div>
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
</body>
</html>
