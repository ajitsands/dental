<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DenSmart | Modern Dental Management SaaS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --accent: #10b981;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: white;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand, .btn {
            font-family: 'Outfit', sans-serif;
        }

        /* Navbar */
        .navbar {
            padding: 20px 0;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        .navbar.scrolled {
            padding: 12px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 26px;
            color: var(--primary) !important;
        }

        /* Hero Section */
        .hero-section {
            padding: 160px 0 100px;
            background: radial-gradient(circle at top right, rgba(13, 110, 253, 0.05), transparent),
                        radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.05), transparent);
            position: relative;
        }
        .hero-badge {
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .hero-title {
            font-size: 64px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-subtitle {
            font-size: 20px;
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 600px;
        }

        /* Mockup */
        .mockup-wrapper {
            position: relative;
            z-index: 1;
        }
        .mockup-img {
            border-radius: 24px;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.2);
            border: 8px solid white;
            transition: transform 0.5s ease;
        }
        .mockup-img:hover {
            transform: translateY(-10px);
        }
        .mockup-floating-card {
            position: absolute;
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            z-index: 2;
        }

        /* Features */
        .section-title {
            font-weight: 800;
            font-size: 42px;
            margin-bottom: 15px;
        }
        .feature-card {
            padding: 40px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            background: white;
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px -12px rgba(0,0,0,0.08);
            border-color: var(--primary);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 25px;
        }

        /* CTA */
        .cta-section {
            background: var(--primary);
            padding: 80px 40px;
            border-radius: 30px;
            margin: 80px 0;
            color: white;
            text-align: center;
        }
        .btn-light-custom {
            background: white;
            color: var(--primary);
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-light-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Language Badge */
        .lang-badge {
            background: #f1f5f9;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-right: 10px;
        }

        footer {
            padding: 80px 0 40px;
            background: #0f172a;
            color: rgba(255,255,255,0.6);
        }
        footer h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 25px;
        }
        footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: color 0.3s ease;
        }
        footer a:hover {
            color: white;
        }
        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .hero-section {
                padding: 120px 0 60px;
                text-align: center;
            }
            .hero-title {
                font-size: 42px;
            }
            .hero-subtitle {
                margin: 0 auto 30px;
            }
            .hero-section .d-flex {
                justify-content: center;
            }
            .mockup-wrapper {
                margin-top: 50px;
            }
            .mockup-floating-card {
                display: none; /* Hide floating cards on mobile to avoid overlap */
            }
        }

        @media (max-width: 767.98px) {
            .hero-title {
                font-size: 36px;
            }
            .section-title {
                font-size: 32px;
            }
            .cta-section {
                margin: 60px 15px;
                padding: 60px 20px;
                border-radius: 24px;
            }
            .cta-section h2 {
                font-size: 28px;
            }
            .hero-section .btn-lg {
                padding: 12px 25px !important;
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fas fa-tooth me-2"></i>DenSmart</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#about">About</a></li>
                <li class="nav-item ms-lg-3">
                    <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline-primary rounded-pill px-4">Login</a>
                </li>
                <li class="nav-item ms-2">
                    <a href="#" class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#demoModal">Get Started</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="hero-badge"><i class="fas fa-sparkles me-2"></i>The Future of Dental Management</div>
                <h1 class="hero-title">Elevate Your Clinic Experience.</h1>
                <p class="hero-subtitle">The all-in-one SaaS platform for modern dental clinics. Manage branches, patients, and financials with ease—anywhere, anytime.</p>
                <div class="d-flex gap-3">
                    <button class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow" data-bs-toggle="modal" data-bs-target="#demoModal">Request Demo <i class="fas fa-arrow-right ms-2"></i></button>
                    <button class="btn btn-outline-dark btn-lg rounded-pill px-5 py-3"><i class="fas fa-play me-2"></i> See Video</button>
                </div>
                <div class="mt-5 d-flex align-items-center">
                    <div class="lang-badge">ARABIC SUPPORT</div>
                    <div class="lang-badge">RTL READY</div>
                    <div class="lang-badge">DARK MODE</div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left">
                <div class="mockup-wrapper">
                    <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=2070&auto=format&fit=crop" alt="Dashboard Mockup" class="img-fluid mockup-img">
                    <div class="mockup-floating-card shadow" style="top: -30px; right: -20px; animation: float 6s infinite ease-in-out;">
                        <div class="d-flex align-items-center">
                            <div class="bg-success-subtle p-2 rounded-3 me-3">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Success Rate</div>
                                <div class="fw-bold">99.8%</div>
                            </div>
                        </div>
                    </div>
                    <div class="mockup-floating-card shadow" style="bottom: 20px; left: -40px; animation: float 8s infinite ease-in-out -2s;">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-subtle p-2 rounded-3 me-3">
                                <i class="fas fa-wallet text-primary"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Optimize Workflow</div>
                                <div class="fw-bold text-primary">Increase your Revenue upto 60%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section id="features" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Designed for Excellence</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">Every tool you need to run a high-performance dental practice, built into a single, seamless dashboard.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-sitemap"></i></div>
                    <h4>Multi-Branch Support</h4>
                    <p class="text-muted">Manage multiple clinic locations from one master account. Switch between branches instantly.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-tooth"></i></div>
                    <h4>Hybrid Dental Charting</h4>
                    <p class="text-muted">Interactive 5-surface clinical charting with visual icons for cavity, filling, and more.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h4>Smart Scheduling</h4>
                    <p class="text-muted">Manage doctor shifts, chair availability, and patient bookings with a drag-and-drop interface.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-wallet"></i></div>
                    <h4>Financial Auditing</h4>
                    <p class="text-muted">Comprehensive billing system with staff commission tracking and digital wallets for the team.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-language"></i></div>
                    <h4>Fully Localized</h4>
                    <p class="text-muted">Professional Arabic support with full RTL layout and high-contrast dark mode options.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-boxes"></i></div>
                    <h4>Inventory Control</h4>
                    <p class="text-muted">Track supplies, medical materials, and get automated alerts when stock is running low.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats / Social Proof -->
<section class="bg-light py-5">
    <div class="container py-5">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <h2 class="fw-bold text-primary">500+</h2>
                <p class="text-muted mb-0">Clinics Registered</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold text-primary">12k+</h2>
                <p class="text-muted mb-0">Active Patients</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold text-primary">24/7</h2>
                <p class="text-muted mb-0">Priority Support</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold text-primary">99.9%</h2>
                <p class="text-muted mb-0">Uptime SLA</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<div class="container">
    <div class="cta-section shadow-lg" data-aos="zoom-in">
        <h2 class="mb-4">Ready to Transform Your Practice?</h2>
        <p class="mb-5 opacity-75">Join hundreds of dental clinics already scaling with DenSmart.</p>
        <button class="btn btn-light-custom btn-lg" data-bs-toggle="modal" data-bs-target="#demoModal">Start Free Trial <i class="fas fa-rocket ms-2"></i></button>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="text-white"><i class="fas fa-tooth me-2 text-primary"></i>DenSmart</h5>
                <p class="small">The leading dental management platform for the modern era. Scalable, secure, and smart.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Product</h5>
                <a href="#">Features</a>
                <a href="#">Security</a>
                <a href="<?php echo BASE_URL; ?>/auth/login" class="text-primary fw-bold"><i class="fas fa-shield-alt me-1"></i> Superadmin Console</a>
                <a href="#">API</a>
            </div>
            <div class="col-6 col-lg-2">
                <h5>Resources</h5>
                <a href="#">Blog</a>
                <a href="#">Guide</a>
                <a href="#">Help Center</a>
                <a href="#">Training</a>
            </div>
            <div class="col-lg-4">
                <h5>Subscribe</h5>
                <p class="small">Get the latest dental management tips.</p>
                <div class="input-group mb-3 mt-3">
                    <input type="text" class="form-control bg-dark border-0 text-white" placeholder="Email address" style="border-radius: 10px 0 0 10px;">
                    <button class="btn btn-primary" type="button" style="border-radius: 0 10px 10px 0;">Join</button>
                </div>
            </div>
        </div>
        <hr class="mt-5 border-secondary">
        <div class="row mt-4">
            <div class="col-md-6 text-center text-md-start">
                <p class="small mb-0" style="white-space: nowrap;">&copy; 2026 DenSmart SaaS. Powered by <a href="https://sandslab.com" target="_blank" class="text-primary text-decoration-none fw-bold d-inline">SaNDS Lab</a>. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="small mb-0"><a href="#" class="d-inline me-3">Privacy</a> <a href="#" class="d-inline">Terms</a></p>
            </div>
        </div>
    </div>
</footer>

<!-- Demo Request Modal -->
<div class="modal fade" id="demoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-4 border-0">
                <div class="text-center w-100">
                    <h4 class="modal-title fw-bold mb-0">Get a Free Demo</h4>
                    <p class="small mb-0 opacity-75">Our team will reach out to you within 24 hours.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="demoForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Company / Clinic Name</label>
                        <input type="text" name="company" class="form-control py-2" placeholder="e.g. Smile Dental Clinic" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control py-2" placeholder="doctor@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile Number</label>
                            <input type="tel" name="mobile" class="form-control py-2" placeholder="+973 ..." required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Country</label>
                        <select name="country" class="form-select py-2" required>
                            <option value="">Select Country</option>
                            <option value="Bahrain">Bahrain</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="UAE">UAE</option>
                            <option value="Oman">Oman</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Qatar">Qatar</option>
                            <option value="India">India</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Additional Note</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Tell us about your clinic size or specific needs..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow" id="submitDemoBtn">Submit Request <i class="fas fa-paper-plane ms-2"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            document.querySelector('.navbar').classList.add('scrolled');
        } else {
            document.querySelector('.navbar').classList.remove('scrolled');
        }
    });

    // Handle Demo Form Submission
    $('#demoForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#submitDemoBtn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

        $.ajax({
            url: '<?php echo BASE_URL; ?>/home/requestDemo',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(r) {
                if(r.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Received!',
                        text: r.message,
                        confirmButtonColor: '#0d6efd'
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('demoModal')).hide();
                        $('#demoForm')[0].reset();
                        btn.prop('disabled', false).html('Submit Request <i class="fas fa-paper-plane ms-2"></i>');
                    });
                }
            }
        });
    });

    // Floating Animation keyframes (manual injection)
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>
