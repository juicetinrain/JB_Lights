<?php
// ContactUs.php - JB Lights & Sound Contact Page - FIXED VERSION
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

// Database connection for contact form submissions
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

$form_success = false;
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // Sanitize inputs
    $first_name = trim($_POST['first-name'] ?? '');
    $last_name = trim($_POST['last-name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate required fields
    if (!$first_name || !$last_name || !$phone || !$email || !$subject || !$message) {
        $form_error = "Please fill in all required fields.";
    } else {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_error = "Please enter a valid email address.";
        } else {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO contact_submissions (first_name, last_name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $first_name, $last_name, $phone, $email, $subject, $message);
            
            if ($stmt->execute()) {
                $form_success = true;
            } else {
                $form_error = "Sorry, there was an error sending your message. Please try again.";
            }
            $stmt->close();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <<link rel="stylesheet" href="css/main.css">
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
    <section class="contact-hero">
        <div class="contact-hero-content">
            <h1 class="hero-title">Get In Touch</h1>
            <p class="hero-subtitle">We're here to help make your event unforgettable</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Information - Left Side -->
                <div class="contact-info-side">
                    <div class="contact-card">
                        <h2><i class="bi bi-geo-alt-fill"></i> Our Office</h2>
                        <div class="contact-detail">
                            <p><strong>Address:</strong></p>
                            <p>235, Purok 2</p>
                            <p>Bical Mabalacat City</p>
                            <p>Pampanga</p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h2><i class="bi bi-telephone-fill"></i> Contact Us</h2>
                        <div class="contact-detail">
                            <p><strong>Call us at:</strong></p>
                            <p class="contact-highlight">0965-639-6053</p>
                        </div>
                        <div class="contact-detail">
                            <p><strong>Email us at:</strong></p>
                            <p class="contact-highlight">jblightsandsoundrental@gmail.com</p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h2><i class="bi bi-clock-fill"></i> Business Hours</h2>
                        <div class="contact-detail">
                            <p><strong>Monday - Sunday:</strong></p>
                            <p>8:00 AM - 10:00 PM</p>
                            <p><em>24/7 Emergency Event Support</em></p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h2><i class="bi bi-lightning-fill"></i> Quick Response</h2>
                        <div class="contact-detail">
                            <p>We typically respond to inquiries within 1-2 hours during business hours.</p>
                            <div class="response-time">
                                <i class="bi bi-check-circle"></i>
                                <span>Fast Response Guaranteed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map and Form - Right Side -->
                <div class="contact-form-side">
                    <!-- Map Section -->
                    <div class="map-section">
                        <h3><i class="bi bi-map-fill"></i> Find Us</h3>
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7700.616491977902!2d120.60932988654514!3d15.196288408950975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396ede37d6e6fe5%3A0x90106c1ac83ed893!2sJB%20LIGHTS%20AND%20SOUND%20RENTAL!5e0!3m2!1sen!2sph!4v1761188470861!5m2!1sen!2sph" 
                                width="100%" 
                                height="300" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="map-note">
                            <i class="bi bi-info-circle"></i>
                            Visit our office in Bical, Mabalacat City for equipment viewing and consultations.
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="form-section">
                        <h3><i class="bi bi-envelope-fill"></i> Send us a Message</h3>
                        
                        <?php if ($form_success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill"></i>
                                Thank you for your message! We'll get back to you within 24 hours.
                            </div>
                        <?php elseif ($form_error): ?>
                            <div class="alert alert-error">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <?php echo htmlspecialchars($form_error); ?>
                            </div>
                        <?php endif; ?>

                        <form action="ContactUs.php" method="POST" class="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first-name">First Name *</label>
                                    <input type="text" id="first-name" name="first-name" required 
                                           value="<?php echo htmlspecialchars($_POST['first-name'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last-name">Last Name *</label>
                                    <input type="text" id="last-name" name="last-name" required
                                           value="<?php echo htmlspecialchars($_POST['last-name'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" required 
                                           placeholder="09XX-XXX-XXXX"
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required 
                                           placeholder="your.email@example.com"
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <select id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="general" <?php echo ($_POST['subject'] ?? '') === 'general' ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="booking" <?php echo ($_POST['subject'] ?? '') === 'booking' ? 'selected' : ''; ?>>Event Booking</option>
                                    <option value="quotation" <?php echo ($_POST['subject'] ?? '') === 'quotation' ? 'selected' : ''; ?>>Price Quotation</option>
                                    <option value="technical" <?php echo ($_POST['subject'] ?? '') === 'technical' ? 'selected' : ''; ?>>Technical Support</option>
                                    <option value="other" <?php echo ($_POST['subject'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" rows="6" required 
                                          placeholder="Tell us about your event or inquiry..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>

                            <button type="submit" name="submit_contact" class="btn btn-submit">
                                <i class="bi bi-send-fill"></i>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-subtitle">Quick answers to common questions</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>How far in advance should I book?</h4>
                    <p>We recommend booking at least 2-3 weeks in advance for weekends and 4-6 weeks for peak seasons like December.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Do you offer delivery and setup?</h4>
                    <p>Yes! We provide complete delivery, setup, and takedown services for all our equipment rentals.</p>
                </div>
                
                <div class="faq-item">
                    <h4>What areas do you serve?</h4>
                    <p>We serve Pampanga and surrounding areas including Angeles City, San Fernando, and nearby provinces.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Can I view the equipment before booking?</h4>
                    <p>Absolutely! Visit our office in Bical, Mabalacat City to see our equipment and discuss your needs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Contact Section -->
    <section class="emergency-section">
        <div class="container">
            <div class="emergency-card">
                <div class="emergency-icon">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="emergency-content">
                    <h3>Emergency Event Support</h3>
                    <p>Need immediate assistance for an ongoing event? Call our 24/7 emergency support line.</p>
                    <div class="emergency-contact">
                        <i class="bi bi-phone"></i>
                        <span>0965-639-6053</span>
                    </div>
                </div>
                <div class="emergency-action">
                    <a href="tel:+639656396053" class="btn btn-primary">
                        <i class="bi bi-telephone-outbound"></i>
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
                        <div class="logo">
                        <img src="img/jb_logo.jpg" alt="JB Lights & Sound" class="logo-image">
                    </div>
                        <div class="logo-text">
                            <span class="logo-main">JB LIGHTS & SOUND</span>
                            <span class="logo-sub">PROFESSIONAL EVENT SERVICES</span>
                        </div>
                    <p class="footer-desc">Your trusted partner for professional lights and sound rental services in Pampanga and surrounding areas.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-messenger"></i></a>
                        <a href="tel:+639656396053" class="social-link"><i class="bi bi-telephone"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php#services">Services</a></li>
                        <li><a href="index.php#gallery">Gallery</a></li>
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
                    <h4>Contact Info</h4>
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
                <a href="index.php" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-house"></i>
                    HOME
                </a>
                <a href="index.php#services" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-speaker"></i>
                    SERVICES
                </a>
                <a href="index.php#gallery" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-images"></i>
                    GALLERY
                </a>
                <a href="index.php#packages" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-star"></i>
                    PACKAGES
                </a>
                <a href="reservation.php" class="menu-item" onclick="closeNav()">
                    <i class="bi bi-calendar-check"></i>
                    BOOK EVENT
                </a>
                <a href="ContactUs.php" class="menu-item active" onclick="closeNav()">
                    <i class="bi bi-telephone"></i>
                    CONTACT US
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
    <script>
// Contact form validation
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            if (!validateContactForm()) {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        const inputs = contactForm.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                // Clear error when user starts typing
                const formGroup = this.closest('.form-group');
                if (formGroup.classList.contains('error')) {
                    const errorMessage = formGroup.querySelector('.error-message');
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                    formGroup.classList.remove('error');
                }
            });
        });
    }
});

function validateField(field) {
    const value = field.value.trim();
    const formGroup = field.closest('.form-group');
    
    // Remove existing error
    const existingError = formGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    formGroup.classList.remove('error');
    
    // Validate based on field type
    switch(field.type) {
        case 'text':
            if (field.id === 'first-name' || field.id === 'last-name') {
                if (!value) {
                    showFieldError(field, 'This field is required');
                }
            }
            break;
        case 'tel':
            if (!value) {
                showFieldError(field, 'Phone number is required');
            } else if (!validatePhone(value)) {
                showFieldError(field, 'Please enter a valid Philippine mobile number');
            }
            break;
        case 'email':
            if (!value) {
                showFieldError(field, 'Email address is required');
            } else if (!validateEmail(value)) {
                showFieldError(field, 'Please enter a valid email address');
            }
            break;
        case 'select-one':
            if (!value) {
                showFieldError(field, 'Please select a subject');
            }
            break;
        case 'textarea':
            if (!value) {
                showFieldError(field, 'Message is required');
            } else if (value.length < 10) {
                showFieldError(field, 'Message should be at least 10 characters long');
            }
            break;
    }
}
</script>
</body>
</html>