<?php
// reservation.php
require_once 'db/db_connect.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login_register.php');
    exit();
}

$user = getCurrentUser();

// Define packages
$packages = [
    'basic_setup' => [
        'name' => 'Basic Setup',
        'price' => 5000,
        'sound_system' => [
            '2pcs Powered Speaker w/stand',
            '1pc Mixer (16channel)',
            '2pcs Wireless Microphone',
            '1pc Laptop (Music Only)',
            '1 Mic Stand',
            '1 Lyrics Stand'
        ],
        'lights_setup' => [
            '6pcs Frontal LED Lights',
            '6pcs Backdrop LED Lights',
            '1pc T-Bar & Stand',
            '1pc DMX Light Controller',
            '1 Box Wire & XLR'
        ]
    ],
    'upgraded_setup_6000' => [
        'name' => 'Upgraded Setup',
        'price' => 6000,
        'sound_system' => [
            '2pcs Powered Speaker w/stand',
            '1pc Mixer (16channel)',
            '2pcs Wireless Microphone',
            '1pc Laptop (Music Only)',
            '1 Mic Stand',
            '1 Lyrics Stand'
        ],
        'lights_setup' => [
            '12pcs Frontal LED Lights',
            '6pcs Backdrop LED Lights',
            '1pc T-Bar & Stand',
            '2pcs Moving Head',
            '1pc Smoke Machine',
            '1pc Light Controller',
            '1 Box Wire & XLR'
        ]
    ],
    'upgraded_setup_7000' => [
        'name' => 'Premium Setup',
        'price' => 7000,
        'sound_system' => [
            '2pcs Powered Speaker w/stand',
            '1pc Mixer (16channel)',
            '2pcs Wireless Microphone',
            '1pc Laptop (Music Only)',
            '1 Mic Stand',
            '1 Lyrics Stand'
        ],
        'lights_setup' => [
            '12pcs Frontal LED Lights',
            '6pcs Backdrop LED Lights',
            '1pc T-Bar & Stand',
            '4pcs Moving Head',
            '1pc Smoke Machine',
            '1pc Light Controller',
            '1 Box Wire & XLR'
        ]
    ],
    'mid_setup' => [
        'name' => 'Mid Setup',
        'price' => 10000,
        'sound_system' => [
            '4pcs Powered Speaker w/stand',
            '2pcs Powered Monitor',
            '2pcs Powered Sub Woofer',
            '1pc Mixer (16channel)',
            '4pcs Wireless Microphone',
            '1pc Laptop (Music Only)',
            '2pcs Mic Stand',
            '1 Lyrics Stand'
        ],
        'lights_setup' => [
            '12pcs Frontal LED Lights',
            '12pcs Backdrop LED Lights',
            '2pcs T-Bar & Stand',
            '6pcs Moving Head',
            '1pc Smoke Machine',
            '1pc Light Controller',
            '1 Box Wire & XLR'
        ]
    ]
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $event_type = $_POST['event_type'];
    $event_date = $_POST['event_date'];
    $event_address = $_POST['event_address'];
    $event_location = $_POST['event_location'] ?? '';
    $package_key = $_POST['package'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $packages[$package_key]['price'];
    
    // Calculate downpayment for GCash
    $downpayment_amount = 0;
    if ($payment_method === 'gcash') {
        $downpayment_amount = $total_amount * 0.30;
    }
    
    // Insert reservation
    $stmt = $conn->prepare("INSERT INTO reservations (contact_name, contact_email, contact_phone, event_type, event_date, event_address, event_location, package, total_amount, payment_method, downpayment_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("ssssssssdsd", $contact_name, $contact_email, $contact_phone, $event_type, $event_date, $event_address, $event_location, $packages[$package_key]['name'], $total_amount, $payment_method, $downpayment_amount);
    
    if ($stmt->execute()) {
        $reservation_id = $conn->insert_id;
        // Redirect to success page instead of showing message
        header("Location: reservation_success.php");
        exit();
    } else {
        $error_message = "Error submitting reservation. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
<script src="js/common.js"></script>
<script src="js/reservation.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/reservation.css">
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
            <div class="hero-badge">Book Your Event</div>
            <h1 class="hero-title">
                <span class="title-line">Reserve Your Lights</span>
                <span class="title-line highlight">& Sound Package</span>
            </h1>
            <p class="hero-subtitle">Complete the form below to book our professional equipment for your unforgettable event</p>
            <div class="hero-buttons">
                <a href="#reservation-form" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i>
                    Start Booking
                </a>
                <a href="tel:+639656396053" class="btn btn-secondary">
                    <i class="bi bi-telephone"></i>
                    Call for Help
                </a>
            </div>
        </div>
        
        <div class="hero-scroll">
            <div class="scroll-indicator">
                <span>Scroll to book</span>
                <div class="scroll-arrow"></div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="reservation-page" id="reservation-form">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">MAKE A RESERVATION</h2>
                <p class="section-subtitle">Book your lights and sound package for your event</p>
                <div class="section-divider"></div>
            </div>

            <div class="reservation-layout">
                <!-- Sidebar -->
                <div class="reservation-sidebar">
                    <div class="sidebar-header">
                        <h3>Booking Progress</h3>
                        <p class="text-secondary">Complete all steps to finalize your reservation</p>
                    </div>

                    <!-- Progress Bar in Sidebar -->
                    <div class="progress-container">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"></div>
                        </div>
                        <div class="progress-text">Step 1 of 5</div>
                    </div>

                    <div class="sidebar-steps">
                        <div class="step-item active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-info">
                                <div class="step-title">Choose Package</div>
                                <div class="step-description">Select your equipment package</div>
                            </div>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-info">
                                <div class="step-title">Event Details</div>
                                <div class="step-description">Date, time, and location</div>
                            </div>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-info">
                                <div class="step-title">Contact Info</div>
                                <div class="step-description">Your contact details</div>
                            </div>
                        </div>
                        <div class="step-item" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-info">
                                <div class="step-title">Payment Method</div>
                                <div class="step-description">Choose how to pay</div>
                            </div>
                        </div>
                        <div class="step-item" data-step="5">
                            <div class="step-number">5</div>
                            <div class="step-info">
                                <div class="step-title">Review & Confirm</div>
                                <div class="step-description">Finalize your booking</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="reservation-main">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="reservationForm">
                        <input type="hidden" name="package" id="selected_package" required>
                        
                        <!-- Step 1: Package Selection -->
                        <div class="step-card active" id="step1">
                            <h3 class="step-title-main">1. Select Your Package</h3>
                            <div class="packages-container">
                                <?php foreach ($packages as $key => $package): ?>
                                <div class="package-card" data-package="<?php echo $key; ?>">
                                    <div class="package-header">
                                        <div class="package-info">
                                            <h4><?php echo $package['name']; ?></h4>
                                            <p class="package-description">Complete equipment setup for your event</p>
                                        </div>
                                        <div class="package-price">₱<?php echo number_format($package['price'], 2); ?></div>
                                    </div>
                                    <div class="package-features">
                                        <div class="feature-section">
                                            <h6>Sound System</h6>
                                            <ul class="feature-list">
                                                <?php foreach ($package['sound_system'] as $feature): ?>
                                                    <li><?php echo $feature; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="feature-section">
                                            <h6>Lights Setup</h6>
                                            <ul class="feature-list">
                                                <?php foreach ($package['lights_setup'] as $feature): ?>
                                                    <li><?php echo $feature; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <button type="button" class="expand-toggle">
                                        <i class="bi bi-chevron-down"></i>
                                        <span>Show Package Details</span>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="step-navigation">
                                <div></div> <!-- Spacer -->
                                <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(1)">
                                    Next <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Event Details with Map -->
                        <div class="step-card" id="step2">
                            <h3 class="step-title-main">2. Event Details & Location</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event type *</label>
                                    <select class="form-select" name="event_type" required>
                                        <option value="">Select event type</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Birthday">Birthday</option>
                                        <option value="Corporate">Corporate event</option>
                                        <option value="Concert">Concert</option>
                                        <option value="Party">Party</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Event date *</label>
                                    <input type="date" class="form-control" name="event_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="time-fields">
                                        <div class="time-field">
                                            <label for="start_time">Start Time *</label>
                                            <input type="time" class="form-control" id="start_time" name="start_time" value="08:00" required>
                                        </div>
                                        <div class="time-field">
                                            <label for="end_time">End Time *</label>
                                            <input type="time" class="form-control" id="end_time" name="end_time" value="17:00" required>
                                        </div>
                                    </div>
                                    <div id="time_error" class="error-message"></div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Event address *</label>
                                    <div class="search-container">
                                        <div class="search-with-button">
                                            <div style="position: relative; flex: 1;">
                                                <input type="text" class="form-control" id="event_address" placeholder="Enter location in Pampanga..." required>
                                                <i class="bi bi-search search-icon"></i>
                                                <div class="autocomplete-results"></div>
                                            </div>
                                            <button type="button" class="btn btn-primary" id="search-address-btn">
                                                <i class="bi bi-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                    <textarea class="form-control d-none" name="event_address" rows="2" required placeholder="Full event address..."></textarea>
                                    <small class="text-muted">Search for locations in Pampanga area only</small>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Set location on map</label>
                                    <div class="map-instructions">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Click on the map to set your exact event location or search for an address above
                                    </div>
                                    <div class="map-container">
                                        <div id="map"></div>
                                    </div>
                                    <input type="hidden" name="event_location" id="event_location">
                                </div>
                            </div>
                            <div class="step-navigation">
                                <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(2)">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(2)">
                                    Next <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Contact Details -->
                        <div class="step-card" id="step3">
                            <h3 class="step-title-main">3. Contact Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact name *</label>
                                    <input type="text" class="form-control" name="contact_name" value="<?php echo htmlspecialchars($user['name']); ?>" required readonly style="background-color: var(--dark-gray); color: var(--text-secondary);">
                                    <small class="text-muted">Taken from your profile</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact email *</label>
                                    <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars($user['email']); ?>" required readonly style="background-color: var(--dark-gray); color: var(--text-secondary);">
                                    <small class="text-muted">Taken from your profile</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Contact phone *</label>
                                    <input type="tel" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required placeholder="09123456789" maxlength="11">
                                    <small class="text-muted">Please enter your current phone number</small>
                                </div>
                            </div>
                            <div class="step-navigation">
                                <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(3)">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(3)">
                                    Next <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Payment Method -->
                        <div class="step-card" id="step4">
                            <h3 class="step-title-main">4. Payment Method</h3>
                            <div class="payment-options">
                                <div class="payment-option" data-payment="cod">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-cash-coin text-success" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">Cash on delivery</h5>
                                            <p class="mb-0 text-secondary">Pay after the event - no downpayment required</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="payment-option" data-payment="gcash">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-phone text-primary" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">GCash payment</h5>
                                            <p class="mb-0 text-secondary">Secure online payment - 30% downpayment required</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="downpayment-info" id="downpaymentInfo">
                                    <div class="alert alert-info">
                                        <h6><i class="bi bi-info-circle me-2"></i>GCash payment details</h6>
                                        <p class="mb-1">Downpayment: <strong id="downpaymentAmount" class="text-primary">₱0.00</strong></p>
                                        <p class="mb-0">Remaining balance: <strong id="remainingAmount" class="text-primary">₱0.00</strong></p>
                                    </div>
                                    <div class="alert alert-warning">
                                        <small>
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            After submitting your reservation, you will receive GCash payment instructions via email and SMS.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="payment_method" id="selected_payment" required>
                            <div class="step-navigation">
                                <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(4)">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(4)">
                                    Next <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 5: Review & Submit -->
                        <div class="step-card" id="step5">
                            <h3 class="step-title-main">5. Review & Submit</h3>
                            <div class="review-section">
                                <div class="review-grid">
                                    <div class="review-item">
                                        <h5>Package details</h5>
                                        <div id="reviewPackage" class="review-content"></div>
                                    </div>
                                    <div class="review-item">
                                        <h5>Event details</h5>
                                        <div id="reviewEvent" class="review-content"></div>
                                    </div>
                                    <div class="review-item">
                                        <h5>Contact details</h5>
                                        <div id="reviewContact" class="review-content"></div>
                                    </div>
                                    <div class="review-item">
                                        <h5>Payment details</h5>
                                        <div id="reviewPayment" class="review-content"></div>
                                    </div>
                                </div>
                                <div class="total-section">
                                    <h4 class="text-white">Total amount: <span id="reviewTotal" class="text-primary"></span></h4>
                                    <div id="reviewDownpayment" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="step-navigation">
                                <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(5)">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </button>
                                <button type="submit" name="submit_reservation" class="btn btn-success btn-reservation">
                                    Confirm reservation <i class="bi bi-check ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

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

    <!-- Leaflet JS - LOAD BEFORE YOUR CUSTOM JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/common.js"></script>
    <script src="js/reservation.js"></script>
</body>
</html>