<?php
// index.php - JB Lights & Sound Homepage
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

// Database connection for featured events (optional)
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
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <div class="logo-icon">JB</div>
                <div class="logo-text">
                    <span class="logo-main">JB Lights & Sound</span>
                    <span class="logo-sub">Professional Event Services</span>
                </div>
            </div>
            
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link active">HOME</a></li>
                    <li><a href="#services" class="nav-link">SERVICES</a></li>
                    <li><a href="#featured" class="nav-link">GALLERY</a></li>
                    <li><a href="reservation.php" class="nav-link btn-reserve">BOOK NOW</a></li>
                    <li><a href="ContactUs.php" class="nav-link">CONTACT</a></li>
                </ul>
                <div class="menu-toggle" onclick="openNav()">
                    <i class="bi bi-list"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <img src="img/cover.jpg" alt="JB Lights and Sounds Event Setup" class="hero-image">
            <div class="hero-overlay"></div>
        </div>

        <!-- Hero Content -->
        <div class="hero-content">
            <h1 class="hero-title">ALL YOUR LIGHTS AND SOUNDS<br>SERVICE NEEDS</h1>
            <p class="hero-subtitle">Professional lights and sounds service for all scales</p>
            <div class="hero-buttons">
                <a href="reservation.php" class="btn btn-primary">Book Your Event</a>
                <a href="ContactUs.php" class="btn btn-secondary">Get In Touch</a>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <h2 class="section-title">Are you looking for affordable and exceptional lights and sound rental in Pampanga? Then you are in the right place!</h2>
            <p class="section-text">
                Here at JB Lights & Sounds, we work hard to guarantee your satisfaction and attend to your needs for events such as birthdays, seminars, wedding, debuts, corporate meetings; you name it! May it be light rental, chairs, tables, LED wall, sounds rental (powered speakers, etc), projector, stage and trusses rental.
            </p>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Events Served</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Equipment Types</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Satisfaction</div>
                </div>
            </div>
            <div class="cta-section">
                <a href="reservation.php" class="btn btn-large">Reserve Now</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <h2 class="section-title">Our Rental Equipment</h2>
            <p class="section-subtitle">Professional equipment for all your event needs</p>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="img/stage.jpg" alt="Stage and Trusses">
                    </div>
                    <h3 class="service-title">STAGE AND TRUSSES</h3>
                    <p class="service-desc">Professional staging solutions for any event size</p>
                    <div class="service-features">
                        <span>Various Sizes</span>
                        <span>Quick Setup</span>
                        <span>Safety Certified</span>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="img/sound.jpg" alt="Sound System">
                    </div>
                    <h3 class="service-title">SOUND SYSTEM</h3>
                    <p class="service-desc">Crystal clear audio for perfect event experience</p>
                    <div class="service-features">
                        <span>Professional Speakers</span>
                        <span>Mixing Boards</span>
                        <span>Wireless Mics</span>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="img/led.jpg" alt="LED Lights">
                    </div>
                    <h3 class="service-title">LED LIGHTS</h3>
                    <p class="service-desc">Dynamic lighting to set the perfect mood</p>
                    <div class="service-features">
                        <span>RGB Lighting</span>
                        <span>DMX Control</span>
                        <span>Various Effects</span>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="img/c&t.jpg" alt="Chairs and Tables">
                    </div>
                    <h3 class="service-title">CHAIRS AND TABLES</h3>
                    <p class="service-desc">Comfortable seating and table arrangements</p>
                    <div class="service-features">
                        <span>Monoblock Chairs</span>
                        <span>Round Tables</span>
                        <span>Long Tables</span>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="img/LW.jpg" alt="LED Wall">
                    </div>
                    <h3 class="service-title">LED WALL</h3>
                    <p class="service-desc">High-resolution displays for impactful visuals</p>
                    <div class="service-features">
                        <span>HD Resolution</span>
                        <span>Various Sizes</span>
                        <span>Easy Setup</span>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="img/projector.jpg" alt="Projectors">
                    </div>
                    <h3 class="service-title">PROJECTORS</h3>
                    <p class="service-desc">Clear projection for presentations and media</p>
                    <div class="service-features">
                        <span>HD Projectors</span>
                        <span>Screens Included</span>
                        <span>All Cables</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-section">
        <div class="container">
            <h2 class="section-title">Event Packages</h2>
            <p class="section-subtitle">Complete solutions for different event types</p>
            
            <div class="packages-grid">
                <div class="package-card featured">
                    <div class="package-badge">Most Popular</div>
                    <div class="package-header">
                        <h3>BASIC PACKAGE</h3>
                        <div class="package-price">₱5,000</div>
                    </div>
                    <div class="package-features">
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>Basic Sound System</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>Basic Lighting Setup</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>4 Hours Service</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>1 Technician</span>
                        </div>
                    </div>
                    <a href="reservation.php" class="btn btn-primary">Select Package</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3>UPGRADED PACKAGE</h3>
                        <div class="package-price">₱6,000</div>
                    </div>
                    <div class="package-features">
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>Enhanced Sound System</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>Basic + Effects Lighting</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>5 Hours Service</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>1 Technician</span>
                        </div>
                    </div>
                    <a href="reservation.php" class="btn btn-outline">Select Package</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3>PROFESSIONAL PACKAGE</h3>
                        <div class="package-price">₱7,000</div>
                    </div>
                    <div class="package-features">
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>Professional Sound System</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>Full Lighting Effects</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>6 Hours Service</span>
                        </div>
                        <div class="feature">
                            <i class="bi bi-check-circle"></i>
                            <span>2 Technicians</span>
                        </div>
                    </div>
                    <a href="reservation.php" class="btn btn-outline">Select Package</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="featured-section" id="featured">
        <div class="container">
            <h2 class="section-title">Featured Events</h2>
            <p class="section-subtitle">See how we bring events to life</p>
            
            <div class="featured-events">
                <div class="featured-event">
                    <div class="event-image">
                        <img src="img/pageant.jpg" alt="Pageant Event">
                    </div>
                    <div class="event-content">
                        <h3>Beauty Pageant Production</h3>
                        <p>Complete sound, lighting, and stage setup for unforgettable pageant experiences. From local competitions to major events, we provide the technical excellence that makes every moment shine.</p>
                        <div class="event-features">
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Full Sound System</span>
                            </div>
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Stage Lighting</span>
                            </div>
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>LED Wall Display</span>
                            </div>
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Professional Audio</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="featured-event reverse">
                    <div class="event-content">
                        <h3>Wedding Celebrations</h3>
                        <p>Make your special day unforgettable with our complete wedding audio-visual solutions. From ceremony to reception, we ensure perfect sound and lighting for every moment.</p>
                        <div class="event-features">
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Ceremony Sound</span>
                            </div>
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Reception Lighting</span>
                            </div>
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Background Music</span>
                            </div>
                            <div class="feature">
                                <i class="bi bi-check-circle"></i>
                                <span>Microphone Setup</span>
                            </div>
                        </div>
                    </div>
                    <div class="event-image">
                        <img src="img/wedding.jpg" alt="Wedding Event">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle">Don't just take our word for it</p>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="bi bi-quote"></i>
                        <p>"JB Lights & Sound made our wedding absolutely magical! The sound was crystal clear and the lighting created the perfect atmosphere."</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Maria Santos</strong>
                        <span>Wedding Client</span>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="bi bi-quote"></i>
                        <p>"Professional service from start to finish. Their equipment is top-notch and the technicians are very knowledgeable."</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>John Reyes</strong>
                        <span>Corporate Event</span>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <i class="bi bi-quote"></i>
                        <p>"We've used JB Lights for multiple events and they never disappoint. Reliable, affordable, and excellent quality!"</p>
                    </div>
                    <div class="testimonial-author">
                        <strong>Sarah Tan</strong>
                        <span>Birthday Party</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Make Your Event Unforgettable?</h2>
            <p>Contact us today for a free consultation and quote</p>
            <div class="cta-buttons">
                <a href="reservation.php" class="btn btn-primary">Book Your Event</a>
                <a href="ContactUs.php" class="btn btn-secondary">
                    <i class="bi bi-telephone"></i>
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon">JB</div>
                        <div class="logo-text">
                            <span class="logo-main">JB Lights & Sound</span>
                            <span class="logo-sub">Professional Event Services</span>
                        </div>
                    </div>
                    <p class="footer-desc">Your trusted partner for professional lights and sound rental services in Pampanga and surrounding areas.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-messenger"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-telephone"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#featured">Gallery</a></li>
                        <li><a href="reservation.php">Book Event</a></li>
                        <li><a href="ContactUs.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-services">
                    <h4>Our Services</h4>
                    <ul>
                        <li>Sound System Rental</li>
                        <li>Lighting Equipment</li>
                        <li>Stage & Trusses</li>
                        <li>LED Walls & Projectors</li>
                        <li>Chairs & Tables</li>
                        <li>Event Production</li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact Us</h4>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>0965-639-6053</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>jblightsandsoundrental@gmail.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>235, Purok 2, Bical, Mabalacat City, Pampanga</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 JB Lights & Sound. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Side Navigation -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
            <i class="bi bi-x-lg"></i>
        </a>
        
        <div class="sidenav-content">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-info">
                    <p class="user-name">Welcome to JB Lights</p>
                    <p class="user-email">Event Services</p>
                </div>
            </div>

            <nav class="sidenav-menu">
                <a href="index.php" class="menu-item">
                    <i class="bi bi-house"></i>
                    HOME
                </a>
                <a href="index.php#services" class="menu-item">
                    <i class="bi bi-speaker"></i>
                    SERVICES
                </a>
                <a href="index.php#featured" class="menu-item">
                    <i class="bi bi-images"></i>
                    GALLERY
                </a>
                <a href="reservation.php" class="menu-item">
                    <i class="bi bi-calendar-check"></i>
                    BOOK EVENT
                </a>
                <a href="ContactUs.php" class="menu-item">
                    <i class="bi bi-telephone"></i>
                    CONTACT US
                </a>
                <a href="#" class="menu-item">
                    <i class="bi bi-credit-card"></i>
                    PAYMENT METHODS
                </a>
                <a href="#" class="menu-item">
                    <i class="bi bi-question-circle"></i>
                    FAQ
                </a>
            </nav>

            <div class="sidenav-footer">
                <div class="contact-info-sidebar">
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>0965-639-6053</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock-fill"></i>
                        <span>8:00 AM - 10:00 PM</span>
                    </div>
                </div>
                <button class="logout-button" onclick="closeNav()">
                    <i class="bi bi-x-circle"></i>
                    CLOSE MENU
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="home.js"></script>
</body>
</html>