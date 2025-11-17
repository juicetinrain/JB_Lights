<?php
// ContactUs.php - BULLETPROOF WORKING VERSION
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
    <style>
        /* Quick contact styles if main.css isn't loading */
        .dark-mode { background: #000; color: #fff; }
        .contact-card { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; }
        .form-control { background: #2a2a2a; border: 1px solid #444; color: white; }
        .form-control:focus { background: #2a2a2a; border-color: #0066ff; color: white; }
        .btn-primary { background: linear-gradient(135deg, #0066ff, #0047b3); border: none; }
    </style>
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
    <section class="hero-section" style="height: 60vh; min-height: 500px; display: flex; align-items: center; justify-content: center; text-align: center; background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://i.imgur.com/dBvINgC.jpeg') center/cover;">
        <div class="hero-content">
            <div class="hero-badge" style="background: #0066ff; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; margin-bottom: 2rem; display: inline-block;">GET IN TOUCH</div>
            <h1 class="hero-title" style="font-size: 3rem; font-weight: 800; margin-bottom: 1rem;">
                <span style="display: block;">Contact JB Lights</span>
                <span style="background: linear-gradient(135deg, #0066ff, #3385ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">& Sound</span>
            </h1>
            <p class="hero-subtitle" style="font-size: 1.2rem; color: #b0b0b0; margin-bottom: 2rem;">Ready to make your event unforgettable? Reach out to us for professional sound and lighting solutions.</p>
            <div class="hero-buttons">
                <a href="tel:+639656396053" class="btn btn-primary">
                    <i class="bi bi-telephone"></i>
                    Call Us Now
                </a>
                <a href="#contact-form" class="btn btn-secondary" style="background: transparent; color: #b0b0b0; border: 2px solid #333;">
                    <i class="bi bi-envelope"></i>
                    Send Message
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="services-section" id="contact-form" style="padding: 4rem 0; background: #0a0a0a;">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
                <h2 class="section-title" style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 1rem;">GET IN TOUCH</h2>
                <p class="section-subtitle" style="font-size: 1.1rem; color: #b0b0b0; margin-bottom: 1.5rem;">We're here to help bring your event vision to life</p>
                <div class="section-divider" style="width: 60px; height: 3px; background: linear-gradient(135deg, #0066ff, #0047b3); margin: 0 auto; border-radius: 2px;"></div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact-card">
                        <div class="form-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #0066ff;">
                            <i class="bi bi-envelope" style="font-size: 2rem; color: #0066ff;"></i>
                            <h3 style="color: white; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Send us a Message</h3>
                        </div>
                        
                        <?php if ($form_success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #22c55e; border-radius: 8px;">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php echo $form_success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($form_error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; border-radius: 8px;">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <?php echo $form_error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="contact-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="first_name" class="form-label" style="color: white; font-weight: 600;">First Name *</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" required 
                                               value="<?php echo htmlspecialchars($first_name); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="last_name" class="form-label" style="color: white; font-weight: 600;">Last Name *</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" required
                                               value="<?php echo htmlspecialchars($last_name); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label" style="color: white; font-weight: 600;">Phone Number *</label>
                                        <input type="tel" id="phone" name="phone" class="form-control" required 
                                               placeholder="09123456789"
                                               maxlength="11"
                                               value="<?php echo htmlspecialchars($phone); ?>">
                                        <small class="form-text" style="color: #666;">11-digit Philippine mobile number (09XXXXXXXXX)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label" style="color: white; font-weight: 600;">Email Address *</label>
                                        <input type="email" id="email" name="email" class="form-control" required 
                                               placeholder="your.email@example.com"
                                               value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="subject" class="form-label" style="color: white; font-weight: 600;">Subject *</label>
                                <select id="subject" name="subject" class="form-select" required>
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry" <?php echo $subject === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="Event Booking" <?php echo $subject === 'Event Booking' ? 'selected' : ''; ?>>Event Booking</option>
                                    <option value="Price Quotation" <?php echo $subject === 'Price Quotation' ? 'selected' : ''; ?>>Price Quotation</option>
                                    <option value="Technical Support" <?php echo $subject === 'Technical Support' ? 'selected' : ''; ?>>Technical Support</option>
                                    <option value="Other" <?php echo $subject === 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="message" class="form-label" style="color: white; font-weight: 600;">Message *</label>
                                <textarea id="message" name="message" class="form-control" rows="5" required 
                                          placeholder="Tell us about your event or inquiry..."><?php echo htmlspecialchars($message); ?></textarea>
                                <div class="char-count" style="text-align: right; font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                                    <span id="charCount">0</span> / 1000 characters
                                </div>
                            </div>

                            <button type="submit" name="submit_contact" class="btn btn-primary btn-lg w-100" style="padding: 1rem 2rem; background: linear-gradient(135deg, #0066ff, #0047b3); border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600;">
                                <i class="bi bi-send"></i>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
    
    <!-- Simple JavaScript for character count -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageInput = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            
            if (messageInput && charCount) {
                messageInput.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
                charCount.textContent = messageInput.value.length;
            }
        });
    </script>
</body>
</html>