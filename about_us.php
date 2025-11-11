<?php
// about_us.php - Updated with index.php styling
require_once 'db/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/about.css">
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
            <div class="hero-badge">ABOUT OUR COMPANY</div>
            <h1 class="hero-title">
                <span class="title-line">The Story Behind</span>
                <span class="title-line highlight">JB Lights & Sound</span>
            </h1>
            <p class="hero-subtitle">Your trusted partner in creating unforgettable events with professional sound and lighting solutions</p>
            <div class="hero-buttons">
                <a href="#our-story" class="btn btn-primary">
                    <i class="bi bi-book"></i>
                    Our Story
                </a>
                <a href="ContactUs.php" class="btn btn-secondary">
                    <i class="bi bi-telephone"></i>
                    Get In Touch
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

    <!-- Our Story Section -->
    <section class="story-section" id="our-story">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">OUR STORY</h2>
                <p class="section-subtitle">From humble beginnings to becoming Pampanga's premier event production company</p>
                <div class="section-divider"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="story-card">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <h3 class="text-primary mb-4">Building Dreams Since 2015</h3>
                                <p class="text-white mb-4">
                                    JB Lights & Sound started with a simple passion for creating memorable experiences through exceptional audio and visual production. What began as a small local service has grown into Pampanga's most trusted event production company.
                                </p>
                                <p class="text-white mb-4">
                                    Founded by a team of audio engineers and lighting technicians, we understood the transformative power of professional sound and lighting in making events truly special. From intimate gatherings to large-scale productions, we've dedicated ourselves to delivering excellence in every project.
                                </p>
                                <p class="text-white">
                                    Today, we continue to innovate and expand our services, always staying at the forefront of event technology while maintaining the personal touch that our clients have come to love and trust.
                                </p>
                            </div>
                            <div class="col-lg-6 text-center">
                                <div class="value-icon" style="width: 120px; height: 120px; font-size: 3rem;">
                                    <i class="bi bi-award"></i>
                                </div>
                                <h4 class="text-white mt-3">8+ Years of Excellence</h4>
                                <p class="text-secondary">Trusted by hundreds of clients across Central Luzon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Values -->
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h4>Quality Excellence</h4>
                    <p class="text-secondary">We maintain the highest standards in equipment quality and service delivery for every event.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h4>Client Focused</h4>
                    <p class="text-secondary">Your vision is our priority. We work closely with you to bring your event dreams to life.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h4>Technical Expertise</h4>
                    <p class="text-secondary">Our team consists of certified technicians with years of experience in event production.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="bi bi-lightning"></i>
                    </div>
                    <h4>Innovation Driven</h4>
                    <p class="text-secondary">We continuously upgrade our equipment and techniques to deliver cutting-edge solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <div class="stat-label">Events Served</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">8+</span>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <div class="stat-label">Client Satisfaction</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <div class="stat-label">Support Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">OUR MISSION & VISION</h2>
                <p class="section-subtitle">Driving our commitment to excellence in event production</p>
                <div class="section-divider"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mission-card">
                        <div class="value-icon" style="margin-bottom: 2rem;">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h3 class="text-primary mb-3">Our Mission</h3>
                        <p class="text-white mb-4">
                            To provide exceptional sound and lighting solutions that transform ordinary events into extraordinary experiences. We are committed to delivering reliable, high-quality production services that exceed our clients' expectations while maintaining the highest standards of professionalism and technical excellence.
                        </p>
                        
                        <h3 class="text-primary mb-3">Our Vision</h3>
                        <p class="text-white">
                            To be the leading event production company in Central Luzon, recognized for our innovation, reliability, and unwavering commitment to client satisfaction. We aim to set the standard for professional audio-visual services while continuously evolving to meet the changing needs of the events industry.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">MEET OUR TEAM</h2>
                <p class="section-subtitle">The passionate professionals behind your successful events</p>
                <div class="section-divider"></div>
            </div>

            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <h4>Technical Director</h4>
                    <p class="text-secondary">Audio Engineering Specialist</p>
                    <p class="text-white">With over 10 years in live sound production, ensures every event has crystal-clear audio quality.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <h4>Lighting Designer</h4>
                    <p class="text-secondary">Visual Production Expert</p>
                    <p class="text-white">Creates stunning visual experiences with state-of-the-art lighting equipment and creative design.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <h4>Operations Manager</h4>
                    <p class="text-secondary">Event Coordination</p>
                    <p class="text-white">Coordinates all aspects of your event to ensure seamless execution from setup to teardown.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <h4>Support Technician</h4>
                    <p class="text-secondary">Equipment Specialist</p>
                    <p class="text-white">Maintains and operates all equipment to ensure optimal performance during your event.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>READY TO CREATE SOMETHING AMAZING TOGETHER?</h2>
                <p>Let's discuss your event and bring your vision to life with professional equipment rental solutions</p>
                <div class="cta-buttons">
                    <a href="reservation.php" class="btn btn-primary">
                        <i class="bi bi-calendar-event"></i>
                        BOOK YOUR EVENT
                    </a>
                    <a href="ContactUs.php" class="btn btn-secondary">
                        <i class="bi bi-envelope"></i>
                        CONTACT US
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
                        <span>0965-639-6053</span>
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
            
            <div class="footer-bottom">
                <p>&copy; 2025 JB LIGHTS & SOUND. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

    <!-- Side Navigation -->
    <?php include 'side_nav.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/common.js"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>