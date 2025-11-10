<?php
// about_us.php
require_once 'db/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - JB Lights & Sound</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
    <section class="contact-hero">
        <div class="contact-hero-content">
            <h1 class="hero-title">About JB Lights & Sound</h1>
            <p class="hero-subtitle">Your trusted partner in creating unforgettable events</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="services-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="content-card">
                        <h2 class="text-center mb-4">Our Story</h2>
                        <p class="text-white text-center mb-4">
                            JB Lights & Sound has been providing professional event production services since 2015. 
                            We specialize in delivering high-quality sound, lighting, and stage equipment for all types of events.
                        </p>
                        
                        <div class="row mt-5">
                            <div class="col-md-6 mb-4">
                                <div class="service-card">
                                    <div class="service-icon">
                                        <i class="bi bi-award"></i>
                                    </div>
                                    <h4>Experience</h4>
                                    <p>Over 8 years of experience in event production and equipment rental</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="service-card">
                                    <div class="service-icon">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                    <h4>Quality Equipment</h4>
                                    <p>State-of-the-art sound and lighting equipment maintained to the highest standards</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="service-card">
                                    <div class="service-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h4>Professional Team</h4>
                                    <p>Skilled technicians and operators dedicated to your event's success</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="service-card">
                                    <div class="service-icon">
                                        <i class="bi bi-heart"></i>
                                    </div>
                                    <h4>Customer Focus</h4>
                                    <p>Committed to making your event vision a reality with personalized service</p>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="home.js"></script>
</body>
</html>