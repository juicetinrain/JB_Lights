<?php
// index.php - JB Lights & Sound Homepage (Dark Mode) - FIXED VERSION
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JB Lights & Sound - Professional Event Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="dark-mode">
        <!-- Header - CLEAN & SIMPLE -->
    <header class="main-header">
    <div class="header-container">
        <a href="index.php" class="logo">
    <img src="img/JB_logo.jpg" alt="JB Lights & Sound" class="logo-image">
    <div class="logo-text">
        <span class="logo-main">JB LIGHTS & SOUND</span>
        <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
    </div>
</a>
            <nav class="main-nav">
                <button class="menu-toggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
            <div class="hero-particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
        </div>
        
        <div class="hero-content">
            <div class="hero-badge">PREMIUM EVENT SERVICES</div>
            <h1 class="hero-title">
                <span class="title-line">ALL YOUR LIGHTS AND SOUNDS</span>
                <span class="title-line highlight">SERVICE NEEDS</span>
            </h1>
            <p class="hero-subtitle">Professional sound, lighting, and stage production for unforgettable moments</p>
            <div class="hero-buttons">
                <a href="reservation.php" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i>
                    BOOK YOUR EVENT
                </a>
                <a href="#services" class="btn btn-secondary">
                    <i class="bi bi-play-circle"></i>
                    VIEW SERVICES
                </a>
            </div>
            
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Events Served</div>
                </div>
                <div class="stat">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Equipment Types</div>
                </div>
                <div class="stat">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
        
        <div class="hero-scroll">
            <div class="scroll-indicator">
                <span>Scroll to explore</span>
                <div class="scroll-arrow"></div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">OUR SERVICES</h2>
                <p class="section-subtitle">Complete event production solutions</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-speaker"></i>
                    </div>
                    <h3>SOUND SYSTEMS</h3>
                    <p>Professional audio equipment for crystal clear sound quality at any event size</p>
                    <ul class="service-features">
                        <li>Line Array Systems</li>
                        <li>Mixing Consoles</li>
                        <li>Wireless Microphones</li>
                        <li>Monitor Systems</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h3>LIGHTING</h3>
                    <p>Dynamic lighting setups to create the perfect atmosphere and mood</p>
                    <ul class="service-features">
                        <li>LED Moving Heads</li>
                        <li>Laser Systems</li>
                        <li>DMX Control</li>
                        <li>Effect Lighting</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-grid-3x3"></i>
                    </div>
                    <h3>STAGE & TRUSSES</h3>
                    <p>Professional staging solutions with aluminum truss systems</p>
                    <ul class="service-features">
                        <li>Aluminum Trussing</li>
                        <li>Stage Platforms</li>
                        <li>Safety Certified</li>
                        <li>Quick Setup</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-display"></i>
                    </div>
                    <h3>VISUAL DISPLAYS</h3>
                    <p>High-impact visual solutions including LED walls and projection</p>
                    <ul class="service-features">
                        <li>LED Video Walls</li>
                        <li>HD Projectors</li>
                        <li>Video Mapping</li>
                        <li>Content Management</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-mic"></i>
                    </div>
                    <h3>PRODUCTION</h3>
                    <p>Complete event production services from planning to execution</p>
                    <ul class="service-features">
                        <li>Event Planning</li>
                        <li>Technical Direction</li>
                        <li>Operator Services</li>
                        <li>Equipment Transport</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h3>SUPPORT</h3>
                    <p>Comprehensive technical support and equipment rental services</p>
                    <ul class="service-features">
                        <li>24/7 Support</li>
                        <li>Equipment Rental</li>
                        <li>Setup & Teardown</li>
                        <li>Maintenance</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">OUR WORK</h2>
                <p class="section-subtitle">Featured events and productions</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="img/wedding-event.jpg" alt="Wedding Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>WEDDING CELEBRATION</h4>
                                <p>Complete sound and lighting for romantic ceremonies</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="img/corporate-event.jpg" alt="Corporate Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>CORPORATE EVENT</h4>
                                <p>Professional AV solutions for business conferences</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="img/concert-event.jpg" alt="Concert Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>LIVE CONCERT</h4>
                                <p>Full production for musical performances</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="img/pageant-event.jpg" alt="Pageant Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>BEAUTY PAGEANT</h4>
                                <p>Stage production for competitions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-section" id="packages">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">EVENT PACKAGES</h2>
                <p class="section-subtitle">Tailored solutions for every occasion</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="packages-grid">
                <div class="package-card">
                    <div class="package-header">
                        <h3>BASIC</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">5,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Basic Sound System</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Basic Lighting Setup</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>4 Hours Service</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>1 Technician</span>
                        </div>
                    </div>
                    <a href="reservation.php" class="btn btn-outline">SELECT PACKAGE</a>
                </div>
                
                <div class="package-card featured">
                    <div class="package-badge">MOST POPULAR</div>
                    <div class="package-header">
                        <h3>PROFESSIONAL</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">7,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Professional Sound System</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Full Lighting Effects</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>6 Hours Service</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>2 Technicians</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Wireless Microphones</span>
                        </div>
                    </div>
                    <a href="reservation.php" class="btn btn-primary">SELECT PACKAGE</a>
                </div>
                
                <div class="package-card">
                    <div class="package-header">
                        <h3>PREMIUM</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">10,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Premium Sound System</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>Advanced Lighting Rig</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>8 Hours Service</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>2 Technicians</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>LED Video Wall</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check"></i>
                            <span>DJ Support</span>
                        </div>
                    </div>
                    <a href="reservation.php" class="btn btn-outline">SELECT PACKAGE</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>READY TO CREATE SOMETHING AMAZING?</h2>
                <p>Let's discuss your event and bring your vision to life with professional sound and lighting solutions</p>
                <div class="cta-buttons">
                    <a href="reservation.php" class="btn btn-primary">
                        <i class="bi bi-calendar-event"></i>
                        BOOK YOUR EVENT
                    </a>
                    <a href="tel:+639656396053" class="btn btn-secondary">
                        <i class="bi bi-telephone"></i>
                        CALL US NOW
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                        <div class="logo">
                        <img src="img/jb_logo.jpg" alt="JB Lights & Sound" class="logo-image">
                    </div>
                        <div class="logo-text">
                            <span class="logo-main">JB LIGHTS & SOUND</span>
                            <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
                        </div>
                    <p class="footer-desc">Your premier partner for professional event production services in Pampanga and surrounding areas.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-messenger"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="tel:+639656396053" class="social-link"><i class="bi bi-telephone"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>QUICK LINKS</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="#packages">Packages</a></li>
                        <li><a href="reservation.php">Book Event</a></li>
                    </ul>
                </div>
                
                <div class="footer-services">
                    <h4>OUR SERVICES</h4>
                    <ul>
                        <li>Sound Systems</li>
                        <li>Lighting Equipment</li>
                        <li>Stage & Trusses</li>
                        <li>LED Video Walls</li>
                        <li>Event Production</li>
                        <li>Technical Support</li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>CONTACT INFO</h4>
                    <div class="contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>235, Purok 2, Bical, Mabalacat City, Pampanga</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone"></i>
                        <span>0965-639-6053</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope"></i>
                        <span>jblightsandsoundrental@gmail.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <span>24/7 Emergency Support</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 JB Lights & Sound. All rights reserved.</p>
            </div>
        </div>
    </footer>

        <!-- Side Navigation -->
    <div id="mySidenav" class="sidenav">
        <div class="sidenav-header">
            <div class="logo">
                <div class="logo-icon">
                    <span class="j-letter">J</span>
                    <span class="b-letter">B</span>
                </div>
                <div class="logo-text">
                    <span class="logo-main">JB LIGHTS & SOUND</span>
                </div>
            </div>
            <button class="closebtn" onclick="closeNav()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="sidenav-content">
            <!-- Clickable Profile Section -->
            <div class="user-profile" onclick="closeNav()">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-info">
                    <p class="user-name">Welcome to JB Lights</p>
                    <p class="user-email">Event Services</p>
                </div>
            </div>

            <nav class="sidenav-menu">
                <a href="#services" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-speaker"></i>
                    SERVICES
                </a>
                <a href="#gallery" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-images"></i>
                    GALLERY
                </a>
                <a href="#packages" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-star"></i>
                    PACKAGES
                </a>
                <a href="reservation.php" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-calendar-check"></i>
                    BOOK EVENT
                </a>
                <a href="ContactUs.php" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-telephone"></i>
                    CONTACT
                </a>
            </nav>

            <div class="sidenav-footer">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="bi bi-telephone"></i>
                        <span>0965-639-6053</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
                <button class="close-menu-btn" onclick="closeNav()">
                    CLOSE MENU
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="home.js"></script>
</body>
</html>