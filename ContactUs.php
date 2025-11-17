<?php
// ContactUs.php - WORKING VERSION WITH FULL DESIGN
require_once 'db/db_connect.php';

$form_success = false;
$form_error = '';

// Initialize variables
$first_name = $last_name = $phone = $email = $subject = $message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // Simple validation
    if (empty($first_name) || empty($last_name) || empty($phone) || empty($email) || empty($subject) || empty($message)) {
        $form_error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_error = "Please enter a valid email address.";
    } else {
        // Clean phone number
        $clean_phone = preg_replace('/\D/', '', $phone);
        
        // Check if phone is valid
        if (strlen($clean_phone) !== 11 || !preg_match('/^09\d{9}$/', $clean_phone)) {
            $form_error = "Please enter a valid Philippine mobile number (09XXXXXXXXX).";
        } else {
            // Insert into database using prepared statement
            $sql = "INSERT INTO contact_submissions (first_name, last_name, phone, email, subject, message, submitted_at, ip_address) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssss", $first_name, $last_name, $clean_phone, $email, $subject, $message, $ip_address);
                
                if ($stmt->execute()) {
                    $form_success = "Thank you for your message! We will get back to you within 24 hours.";
                    // Clear form fields
                    $first_name = $last_name = $phone = $email = $subject = $message = '';
                } else {
                    $form_error = "Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $form_error = "Database preparation error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/contact.css">
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
            <div class="hero-badge">GET IN TOUCH</div>
            <h1 class="hero-title">
                <span class="title-line">Contact JB Lights</span>
                <span class="title-line highlight">& Sound</span>
            </h1>
            <p class="hero-subtitle">Ready to make your event unforgettable? Reach out to us for professional sound and lighting solutions.</p>
            <div class="hero-buttons">
                <a href="tel:+639656396053" class="btn btn-primary">
                    <i class="bi bi-telephone"></i>
                    Call Us Now
                </a>
                <a href="#contact-form" class="btn btn-secondary">
                    <i class="bi bi-envelope"></i>
                    Send Message
                </a>
            </div>
        </div>
        
        <div class="hero-scroll">
            <div class="scroll-indicator">
                <span>Scroll to contact</span>
                <div class="scroll-arrow"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="services-section" id="contact-form">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">GET IN TOUCH</h2>
                <p class="section-subtitle">We're here to help bring your event vision to life</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-grid">
                        <!-- Contact Information - Left Side -->
                        <div class="contact-info-side">
                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="contact-content">
                                    <h3>Our Office</h3>
                                    <div class="contact-detail">
                                        <p><strong>Address:</strong></p>
                                        <p>235, Purok 2</p>
                                        <p>Bical, Mabalacat City</p>
                                        <p>Pampanga</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="contact-content">
                                    <h3>Contact Us</h3>
                                    <div class="contact-detail">
                                        <p><strong>Call us at:</strong></p>
                                        <p class="contact-highlight">09656396053</p>
                                    </div>
                                    <div class="contact-detail">
                                        <p><strong>Email us at:</strong></p>
                                        <p class="contact-highlight">jblightsandsoundrental@gmail.com</p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="contact-content">
                                    <h3>Business Hours</h3>
                                    <div class="contact-detail">
                                        <p><strong>Monday - Sunday:</strong></p>
                                        <p>8:00 AM - 10:00 PM</p>
                                        <p><em>24/7 emergency event support</em></p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-lightning"></i>
                                </div>
                                <div class="contact-content">
                                    <h3>Quick Response</h3>
                                    <div class="contact-detail">
                                        <p>We typically respond to inquiries within 1-2 hours during business hours.</p>
                                        <div class="response-time">
                                            <i class="bi bi-check-circle"></i>
                                            <span>Fast response guaranteed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form Side - Right Side -->
                        <div class="contact-form-side">
                            <!-- Map Above Form -->
                            <div class="contact-card mb-4">
                                <h3 class="mb-3"><i class="bi bi-map me-2"></i>Find Our Location</h3>
                                <div class="map-container">
                                    <iframe 
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7700.616491977902!2d120.60932988654514!3d15.196288408950975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396ede37d6e6fe5%3A0x90106c1ac83ed893!2sJB%20LIGHTS%20AND%20SOUND%20RENTAL!5e0!3m2!1sen!2sph!4v1761188470861!5m2!1sen!2sph" 
                                        width="100%" 
                                        height="300" 
                                        style="border:0; border-radius: 8px;" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                                <div class="map-note text-center mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Visit our office in Bical, Mabalacat City for equipment viewing and consultations.
                                </div>
                            </div>

                            <!-- Contact Form -->
                            <div class="contact-card">
                                <div class="form-header">
                                    <i class="bi bi-envelope"></i>
                                    <h3>Send us a Message</h3>
                                </div>
                                
                                <?php if ($form_success): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle me-2"></i>
                                        <?php echo $form_success; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($form_error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        <?php echo $form_error; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" class="contact-form" id="contactForm">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="first_name">First Name *</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control" required 
                                                   value="<?php echo htmlspecialchars($first_name); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Last Name *</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control" required
                                                   value="<?php echo htmlspecialchars($last_name); ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="phone">Phone Number *</label>
                                            <input type="tel" id="phone" name="phone" class="form-control" required 
                                                   placeholder="09123456789"
                                                   maxlength="11"
                                                   pattern="[0-9]{11}"
                                                   value="<?php echo htmlspecialchars($phone); ?>">
                                            <small class="form-text">11-digit Philippine mobile number (09XXXXXXXXX)</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email Address *</label>
                                            <input type="email" id="email" name="email" class="form-control" required 
                                                   placeholder="your.email@example.com"
                                                   value="<?php echo htmlspecialchars($email); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Subject *</label>
                                        <select id="subject" name="subject" class="form-select" required>
                                            <option value="">Select a subject</option>
                                            <option value="General Inquiry" <?php echo $subject === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                                            <option value="Event Booking" <?php echo $subject === 'Event Booking' ? 'selected' : ''; ?>>Event Booking</option>
                                            <option value="Price Quotation" <?php echo $subject === 'Price Quotation' ? 'selected' : ''; ?>>Price Quotation</option>
                                            <option value="Technical Support" <?php echo $subject === 'Technical Support' ? 'selected' : ''; ?>>Technical Support</option>
                                            <option value="Other" <?php echo $subject === 'Other' ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message *</label>
                                        <textarea id="message" name="message" class="form-control" rows="6" required 
                                                  placeholder="Tell us about your event or inquiry..."><?php echo htmlspecialchars($message); ?></textarea>
                                        <div class="char-count">
                                            <span id="charCount">0</span> / 1000 characters
                                        </div>
                                    </div>

                                    <button type="submit" name="submit_contact" class="btn btn-primary btn-submit" id="submitBtn">
                                        <i class="bi bi-send"></i>
                                        Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="services-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">FREQUENTLY ASKED QUESTIONS</h2>
                <p class="section-subtitle">Quick answers to common questions</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="faq-grid">
                        <div class="contact-card">
                            <h4><i class="bi bi-question-circle me-2"></i>How far in advance should I book?</h4>
                            <p>We recommend booking at least 2-3 weeks in advance for weekends and 4-6 weeks for peak seasons like December.</p>
                        </div>
                        
                        <div class="contact-card">
                            <h4><i class="bi bi-question-circle me-2"></i>Do you offer delivery and setup?</h4>
                            <p>Yes! We provide complete delivery, setup, and takedown services for all our equipment rentals.</p>
                        </div>
                        
                        <div class="contact-card">
                            <h4><i class="bi bi-question-circle me-2"></i>What areas do you serve?</h4>
                            <p>We serve Pampanga and surrounding areas including Angeles City, San Fernando, and nearby provinces.</p>
                        </div>
                        
                        <div class="contact-card">
                            <h4><i class="bi bi-question-circle me-2"></i>Can I view the equipment before booking?</h4>
                            <p>Absolutely! Visit our office in Bical, Mabalacat City to see our equipment and discuss your needs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Contact Section -->
    <section class="cta-section">
        <div class="container">
            <div class="emergency-card">
                <div class="emergency-icon">
                    <i class="bi bi-telephone" style="font-size: 3rem;"></i>
                </div>
                <div class="emergency-content">
                    <h3>Emergency Event Support</h3>
                    <p>Need immediate assistance for an ongoing event? Call our 24/7 emergency support line.</p>
                    <div class="emergency-contact">
                        <i class="bi bi-telephone"></i>
                        <span>09656396053</span>
                    </div>
                </div>
                <div class="emergency-action">
                    <a href="tel:+639656396053" class="btn btn-primary">
                        <i class="bi bi-telephone"></i>
                        Call Now
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
    <script src="js/contact.js"></script>
</body>
</html>