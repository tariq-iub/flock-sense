<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flock Sense - Smart Farm Management</title>
    <link href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon" rel="shortcut icon">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/landing.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container py-3">
    <header class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
        </div>
        <nav>
            <a href="/" class="me-3 fw-semibold text-decoration-none text-dark">Home</a>
            <a href="#models" class="me-3 fw-semibold text-decoration-none text-dark">Models</a>
            <a href="#features" class="me-3 fw-semibold text-decoration-none text-dark">Features</a>
            <a href="#contact" class="me-3 fw-semibold text-decoration-none text-dark">Contact Us</a>
            <a href="/login" class="login-btn">Login</a>
        </nav>
    </header>
</div>

<section class="alt-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-3 fw-bold"><span style="color:#79c37d">Smart</span> Farm Management</h1>
                <p class="fs-5 mb-4">
                    a Solution designed for Modern Poultry Farming <span class="badge bg-success-subtle text-secondary">Farmer's No. 1 Choice</span>
                </p>
                <div class="input-group mb-4">
                    <input type="text" class="form-control rounded-start-pill" placeholder="What's on your mind?">
                    <button class="btn btn-success rounded-end-pill">Message</button>
                </div>
            </div>
            <div class="col-md-6 position-relative text-center">
                <img src="{{ asset('assets/img/chicken.png') }}" class="img-fluid rounded-4" alt="Chicken">
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
                <h2 class="fw-bold mb-3">Our Models</h2>
                <p>
                    We offer a comprehensive range of hardware and software models specifically designed to optimize environmental monitoring within poultry facilities.
                    Our solutions ensure precise control over critical parameters to support healthier flocks and more efficient farm management.
                </p>
                <ul>
                    <li>Temperature Monitoring: Maintain ideal climate conditions to support bird health and productivity.</li>
                    <li>Humidity Control: Track and manage humidity levels to reduce the risk of disease and respiratory issues.</li>
                    <li>Feeding Schedule Automation: Monitor and automate feeding routines to ensure consistency and reduce manual effort.</li>
                    <li>Multi-Parameter Monitoring: Track additional factors such as light intensity, CO₂ levels, and ventilation status.</li>
                </ul>
                <p>
                    Whether you're managing a single broiler house or an integrated farming system, our solutions are engineered to deliver reliable data and smart control capabilities.
                </p>

            </div>
        </div>
    </div>
</section>

<section id="features" class="alt-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-3">Key Features</h2>
                <ul>
                    <li>Real-time monitoring of poultry conditions</li>
                    <li>Automated alerts and notifications</li>
                    <li>Data analytics for productivity and health</li>
                    <li>Mobile and web-based dashboard</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('assets/img/features.png') }}" class="img-fluid rounded-3" alt="Features Image">
            </div>
        </div>
    </div>
</section>

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
