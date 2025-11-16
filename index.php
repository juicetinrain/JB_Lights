<?php
// config.php - Now using db_connect.php
require_once 'db/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JB Lights & Sound - Professional Event Services</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="dark-mode">
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="https://i.imgur.com/wOkfD9T.jpeg" alt="JB Lights & Sound" class="logo-image">
                <div class="logo-text">
                    <span class="logo-main">JB LIGHTS & SOUND</span>
                    <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
                </div>
            </a>
            <nav class="main-nav">
                <a href="reservation.php" class="btn btn-primary me-2 d-none d-md-inline-block">
                    <i class="bi bi-calendar-check"></i> BOOK NOW
                </a>
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
            <p class="hero-subtitle">Complete sound, lighting, stage, and visual solutions for unforgettable events</p>
            <div class="hero-buttons">
                <a href="reservation.php" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i>
                    BOOK YOUR EVENT
                </a>
                <a href="#services" class="btn btn-secondary">
                    <i class="bi bi-play-circle"></i>
                    VIEW EQUIPMENT
                </a>
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
                <h2 class="section-title">OUR EQUIPMENT CATEGORIES</h2>
                <p class="section-subtitle">Complete event production equipment for all your needs</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-speaker"></i>
                    </div>
                    <h3>SOUND SYSTEM</h3>
                    <p>Professional audio equipment for crystal clear sound quality at any event size</p>
                    <ul class="service-features">
                        <li>Powered Speakers & Stands</li>
                        <li>Mixing Consoles</li>
                        <li>Wireless Microphones</li>
                        <li>Sub Woofers & Monitors</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <h3>LED LIGHTS</h3>
                    <p>Dynamic LED lighting setups to create the perfect atmosphere and mood</p>
                    <ul class="service-features">
                        <li>Frontal LED Lights</li>
                        <li>Backdrop LED Lights</li>
                        <li>Moving Head Lights</li>
                        <li>DMX Control Systems</li>
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
                        <li>T-Bar & Stand Systems</li>
                        <li>Stage Platforms</li>
                        <li>Safety Certified Equipment</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-display"></i>
                    </div>
                    <h3>PROJECTOR</h3>
                    <p>High-quality projection systems for presentations and visual displays</p>
                    <ul class="service-features">
                        <li>HD Projectors</li>
                        <li>Projection Screens</li>
                        <li>Media Players</li>
                        <li>Setup & Calibration</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-collection"></i>
                    </div>
                    <h3>RENTAL EQUIPMENTS</h3>
                    <p>Complete range of event equipment for all your production needs</p>
                    <ul class="service-features">
                        <li>Smoke Machines</li>
                        <li>Light Controllers</li>
                        <li>Cables & XLR</li>
                        <li>Technical Support</li>
                    </ul>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-person-seat"></i>
                    </div>
                    <h3>CHAIRS & FURNITURE</h3>
                    <p>Comfortable seating and furniture solutions for your guests</p>
                    <ul class="service-features">
                        <li>Event Chairs</li>
                        <li>Folding Tables</li>
                        <li>Stage Furniture</li>
                        <li>Setup Services</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">OUR GALLERY</h2>
                <p class="section-subtitle">Featured events and equipment setups</p>
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
                        <img src="https://i.imgur.com/JlgnLNB.jpeg" alt="Corporate Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
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
                        <img src="https://i.imgur.com/hPQPh3F.jpeg" alt="Concert Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
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
                        <img src="https://i.imgur.com/BZ3Vq36.jpeg" alt="Pageant Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>BEAUTY PAGEANT</h4>
                                <p>Stage production for competitions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="https://i.imgur.com/qccFOGP.jpeg" alt="Birthday Event" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>BIRTHDAY PARTY</h4>
                                <p>Sound and lighting for celebrations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <img src="https://i.imgur.com/AgNthiZ.jpeg" alt="Equipment Setup" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgdmlld0JveD0iMCAwIDMwMCAyMjUiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjI1IiBmaWxsPSIjMUEyQTMzIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMTEyLjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FsbGVyeSBJbWFnZTwvdGV4dD4KPC9zdmc+'">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h4>EQUIPMENT SETUP</h4>
                                <p>Professional installation and teardown</p>
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
                <h2 class="section-title">EQUIPMENT PACKAGES</h2>
                <p class="section-subtitle">Complete setup solutions for every event type</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="packages-grid">
                <div class="package-card">
                    <div class="package-header">
                        <h3>BASIC SETUP</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">5,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <h6>SOUND SYSTEM:</h6>
                        <ul>
                            <li>2pcs Powered Speaker w/stand</li>
                            <li>1pc Mixer (16channel)</li>
                            <li>2pcs Wireless Microphone</li>
                            <li>1pc Laptop (Music Only)</li>
                            <li>1 Mic Stand</li>
                            <li>1 Lyrics Stand</li>
                        </ul>
                        <h6>LIGHTS SETUP:</h6>
                        <ul>
                            <li>6pcs Frontal LED Lights</li>
                            <li>6pcs Backdrop LED Lights</li>
                            <li>1pc T-Bar & Stand</li>
                            <li>1pc DMX Light Controller</li>
                            <li>1 Box Wire & XLR</li>
                        </ul>
                    </div>
                    <a href="reservation.php" class="btn btn-outline">SELECT PACKAGE</a>
                </div>
                
                <div class="package-card featured">
                    <div class="package-badge">POPULAR CHOICE</div>
                    <div class="package-header">
                        <h3>UPGRADED SETUP</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">6,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <h6>SOUND SYSTEM:</h6>
                        <ul>
                            <li>2pcs Powered Speaker w/stand</li>
                            <li>1pc Mixer (16channel)</li>
                            <li>2pcs Wireless Microphone</li>
                            <li>1pc Laptop (Music Only)</li>
                            <li>1 Mic Stand</li>
                            <li>1 Lyrics Stand</li>
                        </ul>
                        <h6>LIGHTS SETUP:</h6>
                        <ul>
                            <li>12pcs Frontal LED Lights</li>
                            <li>6pcs Backdrop LED Lights</li>
                            <li>1pc T-Bar & Stand</li>
                            <li>2pcs Moving Head</li>
                            <li>1pc Smoke Machine</li>
                            <li>1pc Light Controller</li>
                            <li>1 Box Wire & XLR</li>
                        </ul>
                    </div>
                    <a href="reservation.php" class="btn btn-primary">SELECT PACKAGE</a>
                </div>
                
                <div class="package-card">
                    <div class="package-header">
                        <h3>PREMIUM SETUP</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">7,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <h6>SOUND SYSTEM:</h6>
                        <ul>
                            <li>2pcs Powered Speaker w/stand</li>
                            <li>1pc Mixer (16channel)</li>
                            <li>2pcs Wireless Microphone</li>
                            <li>1pc Laptop (Music Only)</li>
                            <li>1 Mic Stand</li>
                            <li>1 Lyrics Stand</li>
                        </ul>
                        <h6>LIGHTS SETUP:</h6>
                        <ul>
                            <li>12pcs Frontal LED Lights</li>
                            <li>6pcs Backdrop LED Lights</li>
                            <li>1pc T-Bar & Stand</li>
                            <li>4pcs Moving Head</li>
                            <li>1pc Smoke Machine</li>
                            <li>1pc Light Controller</li>
                            <li>1 Box Wire & XLR</li>
                        </ul>
                    </div>
                    <a href="reservation.php" class="btn btn-outline">SELECT PACKAGE</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3>MID SETUP</h3>
                        <div class="package-price">
                            <span class="currency">₱</span>
                            <span class="amount">10,000</span>
                        </div>
                    </div>
                    <div class="package-features">
                        <h6>SOUND SYSTEM:</h6>
                        <ul>
                            <li>4pcs Powered Speaker w/stand</li>
                            <li>2pcs Powered Monitor</li>
                            <li>2pcs Powered Sub Woofer</li>
                            <li>1pc Mixer (16channel)</li>
                            <li>4pcs Wireless Microphone</li>
                            <li>1pc Laptop (Music Only)</li>
                            <li>2pcs Mic Stand</li>
                            <li>1 Lyrics Stand</li>
                        </ul>
                        <h6>LIGHTS SETUP:</h6>
                        <ul>
                            <li>12pcs Frontal LED Lights</li>
                            <li>12pcs Backdrop LED Lights</li>
                            <li>2pcs T-Bar & Stand</li>
                            <li>6pcs Moving Head</li>
                            <li>1pc Smoke Machine</li>
                            <li>1pc Light Controller</li>
                            <li>1 Box Wire & XLR</li>
                        </ul>
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
                <p>Let's discuss your event and bring your vision to life with professional equipment rental solutions</p>
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
                    <a href="index.php" class="logo">
                        <img src="https://i.imgur.com/wOkfD9T.jpeg" alt="JB Lights & Sound" class="logo-image">
                        <div class="logo-text">
                            <span class="logo-main">JB LIGHTS & SOUND</span>
                            <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
                        </div>
                    </a>
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
                        <li><a href="index.php">HOME</a></li>
                        <li><a href="about_us.php">ABOUT US</a></li>
                        <li><a href="index.php#services">SERVICES</a></li>
                        <li><a href="index.php#packages">PACKAGES</a></li>
                        <li><a href="ContactUs.php">CONTACT</a></li>
                    </ul>
                </div>
                
                <div class="footer-services">
                    <h4>OUR SERVICES</h4>
                    <ul>
                        <li>SOUND SYSTEMS</li>
                        <li>LIGHTING EQUIPMENT</li>
                        <li>STAGE & TRUSSES</li>
                        <li>LED VIDEO WALLS</li>
                        <li>EVENT PRODUCTION</li>
                        <li>TECHNICAL SUPPORT</li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>CONTACT INFO</h4>
                    <div class="contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>235, PUROK 2, BICAL, MABALACAT CITY, PAMPANGA</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone"></i>
                        <span>09656396053</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope"></i>
                        <span>JBLIGHTSANDSOUNDRENTAL@GMAIL.COM</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <span>24/7 EMERGENCY SUPPORT</span>
                    </div>
                </div>
            </div>
            
            <!-- Fixed Footer Bottom -->
            <div class="footer-bottom">
                <div class="container">
                    <div class="footer-bottom-content">
                        <p>&copy; 2025 JB LIGHTS & SOUND. ALL RIGHTS RESERVED.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Side Navigation -->
    <?php include 'side_nav.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/common.js"></script>
    <script src="js/home.js"></script>
</body>
</html>