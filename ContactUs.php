<?php
// ContactUs.php - Database only version
require_once 'db/db_connect.php';

$form_success = false;
$form_error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // Sanitize inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($phone) || empty($email) || empty($subject) || empty($message)) {
        $form_error = "PLEASE FILL IN ALL REQUIRED FIELDS.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_error = "PLEASE ENTER A VALID EMAIL ADDRESS.";
    } else {
        // Save to database only
        $stmt = $conn->prepare("INSERT INTO contact_submissions (first_name, last_name, phone, email, subject, message, submitted_at, ip_address) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("sssssss", $first_name, $last_name, $phone, $email, $subject, $message, $ip_address);
        
        if ($stmt->execute()) {
            $form_success = "THANK YOU FOR YOUR MESSAGE! WE WILL GET BACK TO YOU WITHIN 24 HOURS.";
        } else {
            $form_error = "SORRY, THERE WAS AN ERROR SAVING YOUR MESSAGE. PLEASE TRY AGAIN.";
        }
        $stmt->close();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <i class="fas fa-calendar-check"></i> BOOK NOW
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
            <h1 class="hero-title">GET IN TOUCH</h1>
            <p class="hero-subtitle">WE'RE HERE TO HELP MAKE YOUR EVENT UNFORGETTABLE</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Information - Left Side -->
                <div class="contact-info-side">
                    <div class="contact-card">
                        <h2><i class="fas fa-map-marker-alt"></i> OUR OFFICE</h2>
                        <div class="contact-detail">
                            <p><strong>ADDRESS:</strong></p>
                            <p>235, PUROK 2</p>
                            <p>BICAL MABALACAT CITY</p>
                            <p>PAMPANGA</p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h2><i class="fas fa-phone"></i> CONTACT US</h2>
                        <div class="contact-detail">
                            <p><strong>CALL US AT:</strong></p>
                            <p class="contact-highlight">0965-639-6053</p>
                        </div>
                        <div class="contact-detail">
                            <p><strong>EMAIL US AT:</strong></p>
                            <p class="contact-highlight">JBLIGHTSANDSOUNDRENTAL@GMAIL.COM</p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h2><i class="fas fa-clock"></i> BUSINESS HOURS</h2>
                        <div class="contact-detail">
                            <p><strong>MONDAY - SUNDAY:</strong></p>
                            <p>8:00 AM - 10:00 PM</p>
                            <p><em>24/7 EMERGENCY EVENT SUPPORT</em></p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h2><i class="fas fa-bolt"></i> QUICK RESPONSE</h2>
                        <div class="contact-detail">
                            <p>WE TYPICALLY RESPOND TO INQUIRIES WITHIN 1-2 HOURS DURING BUSINESS HOURS.</p>
                            <div class="response-time">
                                <i class="fas fa-check-circle"></i>
                                <span>FAST RESPONSE GUARANTEED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map and Form - Right Side -->
                <div class="contact-form-side">
                    <!-- Map Section -->
                    <div class="map-section">
                        <h3><i class="fas fa-map"></i> FIND US</h3>
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
                            <i class="fas fa-info-circle"></i>
                            VISIT OUR OFFICE IN BICAL, MABALACAT CITY FOR EQUIPMENT VIEWING AND CONSULTATIONS.
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="form-section">
                        <h3><i class="fas fa-envelope"></i> SEND US A MESSAGE</h3>
                        
                        <?php if ($form_success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $form_success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($form_error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $form_error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="ContactUs.php" method="POST" class="contact-form" id="contactForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">FIRST NAME *</label>
                                    <input type="text" id="first_name" name="first_name" required 
                                           value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                                    <div class="error-message" id="firstNameError"></div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">LAST NAME *</label>
                                    <input type="text" id="last_name" name="last_name" required
                                           value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                                    <div class="error-message" id="lastNameError"></div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">PHONE NUMBER *</label>
                                    <input type="tel" id="phone" name="phone" required 
                                           placeholder="09XX-XXX-XXXX"
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                    <div class="error-message" id="phoneError"></div>
                                </div>
                                <div class="form-group">
                                    <label for="email">EMAIL ADDRESS *</label>
                                    <input type="email" id="email" name="email" required 
                                           placeholder="YOUR.EMAIL@EXAMPLE.COM"
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                    <div class="error-message" id="emailError"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">SUBJECT *</label>
                                <select id="subject" name="subject" required>
                                    <option value="">SELECT A SUBJECT</option>
                                    <option value="General Inquiry" <?php echo ($_POST['subject'] ?? '') === 'General Inquiry' ? 'selected' : ''; ?>>GENERAL INQUIRY</option>
                                    <option value="Event Booking" <?php echo ($_POST['subject'] ?? '') === 'Event Booking' ? 'selected' : ''; ?>>EVENT BOOKING</option>
                                    <option value="Price Quotation" <?php echo ($_POST['subject'] ?? '') === 'Price Quotation' ? 'selected' : ''; ?>>PRICE QUOTATION</option>
                                    <option value="Technical Support" <?php echo ($_POST['subject'] ?? '') === 'Technical Support' ? 'selected' : ''; ?>>TECHNICAL SUPPORT</option>
                                    <option value="Other" <?php echo ($_POST['subject'] ?? '') === 'Other' ? 'selected' : ''; ?>>OTHER</option>
                                </select>
                                <div class="error-message" id="subjectError"></div>
                            </div>

                            <div class="form-group">
                                <label for="message">MESSAGE *</label>
                                <textarea id="message" name="message" rows="6" required 
                                          placeholder="TELL US ABOUT YOUR EVENT OR INQUIRY..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                <div class="error-message" id="messageError"></div>
                                <div class="char-count">
                                    <span id="charCount">0</span> / 1000 CHARACTERS
                                </div>
                            </div>

                            <button type="submit" name="submit_contact" class="btn btn-submit" id="submitBtn">
                                <i class="fas fa-paper-plane"></i>
                                SEND MESSAGE
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
                <h2 class="section-title">FREQUENTLY ASKED QUESTIONS</h2>
                <p class="section-subtitle">QUICK ANSWERS TO COMMON QUESTIONS</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>HOW FAR IN ADVANCE SHOULD I BOOK?</h4>
                    <p>WE RECOMMEND BOOKING AT LEAST 2-3 WEEKS IN ADVANCE FOR WEEKENDS AND 4-6 WEEKS FOR PEAK SEASONS LIKE DECEMBER.</p>
                </div>
                
                <div class="faq-item">
                    <h4>DO YOU OFFER DELIVERY AND SETUP?</h4>
                    <p>YES! WE PROVIDE COMPLETE DELIVERY, SETUP, AND TAKEDOWN SERVICES FOR ALL OUR EQUIPMENT RENTALS.</p>
                </div>
                
                <div class="faq-item">
                    <h4>WHAT AREAS DO YOU SERVE?</h4>
                    <p>WE SERVE PAMPANGA AND SURROUNDING AREAS INCLUDING ANGELES CITY, SAN FERNANDO, AND NEARBY PROVINCES.</p>
                </div>
                
                <div class="faq-item">
                    <h4>CAN I VIEW THE EQUIPMENT BEFORE BOOKING?</h4>
                    <p>ABSOLUTELY! VISIT OUR OFFICE IN BICAL, MABALACAT CITY TO SEE OUR EQUIPMENT AND DISCUSS YOUR NEEDS.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Contact Section -->
    <section class="emergency-section">
        <div class="container">
            <div class="emergency-card">
                <div class="emergency-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="emergency-content">
                    <h3>EMERGENCY EVENT SUPPORT</h3>
                    <p>NEED IMMEDIATE ASSISTANCE FOR AN ONGOING EVENT? CALL OUR 24/7 EMERGENCY SUPPORT LINE.</p>
                    <div class="emergency-contact">
                        <i class="fas fa-phone"></i>
                        <span>0965-639-6053</span>
                    </div>
                </div>
                <div class="emergency-action">
                    <a href="tel:+639656396053" class="btn btn-primary">
                        <i class="fas fa-phone"></i>
                        CALL NOW
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
                    <p class="footer-desc">YOUR PREMIER PARTNER FOR PROFESSIONAL EVENT PRODUCTION SERVICES IN PAMPANGA AND SURROUNDING AREAS.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-messenger"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="tel:+639656396053" class="social-link"><i class="fas fa-phone"></i></a>
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
                        <i class="fas fa-phone"></i>
                        <span>0965-639-6053</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>JBLIGHTSANDSOUNDRENTAL@GMAIL.COM</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>235, PUROK 2, BICAL, MABALACAT CITY, PAMPANGA</span>
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
    <script src="js/contact.js"></script>
</body>
</html>