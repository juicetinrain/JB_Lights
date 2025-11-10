<?php
// reservation.php
require_once 'dB/db_connect.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login_register.php');
    exit();
}

$user = getCurrentUser();

// Define packages (same as before)
$packages = [
    'basic_setup' => [
        'name' => 'BASIC SETUP',
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
        'name' => 'UPGRADED SETUP',
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
        'name' => 'UPGRADED SETUP',
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
        'name' => 'MID SETUP',
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
        $success_message = "RESERVATION SUBMITTED SUCCESSFULLY! YOUR BOOKING ID: #" . $reservation_id;
    } else {
        $error_message = "ERROR SUBMITTING RESERVATION. PLEASE TRY AGAIN.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/reservation.css">
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

    <!-- Main Content -->
    <main class="reservation-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="reservation-card">
                        <div class="reservation-header text-center mb-4">
                            <h1 class="text-white">MAKE A RESERVATION</h1>
                            <p class="text-secondary">BOOK YOUR LIGHTS AND SOUND PACKAGE FOR YOUR EVENT</p>
                        </div>

                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $success_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" id="reservationForm">
                            <!-- Step 1: Package Selection -->
                            <div class="step-card active" id="step1">
                                <h3 class="step-title">1. SELECT YOUR PACKAGE</h3>
                                <div class="row">
                                    <?php foreach ($packages as $key => $package): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="package-card">
                                            <input type="radio" name="package" id="<?php echo $key; ?>" value="<?php echo $key; ?>" class="package-radio" required>
                                            <label for="<?php echo $key; ?>" class="package-label">
                                                <div class="package-header">
                                                    <h4><?php echo $package['name']; ?></h4>
                                                    <div class="package-price">₱<?php echo number_format($package['price'], 2); ?></div>
                                                </div>
                                                <div class="package-features">
                                                    <h6>SOUND SYSTEM:</h6>
                                                    <ul>
                                                        <?php foreach ($package['sound_system'] as $feature): ?>
                                                            <li><?php echo $feature; ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <h6>LIGHTS SETUP:</h6>
                                                    <ul>
                                                        <?php foreach ($package['lights_setup'] as $feature): ?>
                                                            <li><?php echo $feature; ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(1)">NEXT <i class="fas fa-arrow-right ms-2"></i></button>
                                </div>
                            </div>

                            <!-- Step 2: Event Details with Map -->
                            <div class="step-card" id="step2">
                                <h3 class="step-title">2. EVENT DETAILS & LOCATION</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">EVENT TYPE *</label>
                                        <select class="form-select" name="event_type" required>
                                            <option value="">SELECT EVENT TYPE</option>
                                            <option value="Wedding">WEDDING</option>
                                            <option value="Birthday">BIRTHDAY</option>
                                            <option value="Corporate">CORPORATE EVENT</option>
                                            <option value="Concert">CONCERT</option>
                                            <option value="Party">PARTY</option>
                                            <option value="Other">OTHER</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">EVENT DATE *</label>
                                        <input type="date" class="form-control" name="event_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">EVENT ADDRESS *</label>
                                        <div class="input-group mb-3">
                                            <textarea class="form-control" name="event_address" rows="2" required placeholder="FULL EVENT ADDRESS..."></textarea>
                                            <button type="button" class="btn btn-outline-primary" onclick="searchAddressFromInput()">
                                                <i class="fas fa-search"></i> SEARCH
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">SET LOCATION ON MAP</label>
                                        <div class="map-instructions">
                                            <i class="fas fa-info-circle me-2"></i>
                                            CLICK ON THE MAP TO SET YOUR EXACT EVENT LOCATION OR SEARCH FOR AN ADDRESS ABOVE
                                        </div>
                                        <div class="map-container">
                                            <div id="map"></div>
                                        </div>
                                        <input type="hidden" name="event_location" id="event_location">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(2)"><i class="fas fa-arrow-left me-2"></i> BACK</button>
                                    <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(2)">NEXT <i class="fas fa-arrow-right ms-2"></i></button>
                                </div>
                            </div>

                            <!-- Step 3: Contact Details -->
                            <div class="step-card" id="step3">
                                <h3 class="step-title">3. CONTACT DETAILS</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">CONTACT NAME *</label>
                                        <input type="text" class="form-control" name="contact_name" value="<?php echo htmlspecialchars($user['name']); ?>" required readonly style="background-color: var(--dark-gray); color: var(--text-secondary);">
                                        <small class="text-muted">TAKEN FROM YOUR PROFILE</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">CONTACT EMAIL *</label>
                                        <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars($user['email']); ?>" required readonly style="background-color: var(--dark-gray); color: var(--text-secondary);">
                                        <small class="text-muted">TAKEN FROM YOUR PROFILE</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">CONTACT PHONE *</label>
                                        <input type="tel" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required placeholder="09XXXXXXXXX">
                                        <small class="text-muted">PLEASE ENTER YOUR CURRENT PHONE NUMBER</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(3)"><i class="fas fa-arrow-left me-2"></i> BACK</button>
                                    <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(3)">NEXT <i class="fas fa-arrow-right ms-2"></i></button>
                                </div>
                            </div>

                            <!-- Step 4: Payment Method -->
                            <div class="step-card" id="step4">
                                <h3 class="step-title">4. PAYMENT METHOD</h3>
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="payment-option" onclick="selectPayment('cod')">
                                            <input type="radio" name="payment_method" id="cod" value="cod" required>
                                            <label for="cod" class="w-100">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-1 text-white">CASH ON DELIVERY</h5>
                                                        <p class="mb-0 text-secondary">PAY AFTER THE EVENT - NO DOWNPAYMENT REQUIRED</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="payment-option" onclick="selectPayment('gcash')">
                                            <input type="radio" name="payment_method" id="gcash" value="gcash" required>
                                            <label for="gcash" class="w-100">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-1 text-white">GCASH PAYMENT</h5>
                                                        <p class="mb-0 text-secondary">SECURE ONLINE PAYMENT - 30% DOWNPAYMENT REQUIRED</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="downpayment-info" id="downpaymentInfo">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>GCASH PAYMENT DETAILS</h6>
                                                <p class="mb-1">DOWNPAYMENT: <strong id="downpaymentAmount" class="text-primary">₱0.00</strong></p>
                                                <p class="mb-0">REMAINING BALANCE: <strong id="remainingAmount" class="text-primary">₱0.00</strong></p>
                                            </div>
                                            <div class="alert alert-warning">
                                                <small>
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    AFTER SUBMITTING YOUR RESERVATION, YOU WILL RECEIVE GCASH PAYMENT INSTRUCTIONS VIA EMAIL AND SMS.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(4)"><i class="fas fa-arrow-left me-2"></i> BACK</button>
                                    <button type="button" class="btn btn-primary btn-reservation" onclick="nextStep(4)">NEXT <i class="fas fa-arrow-right ms-2"></i></button>
                                </div>
                            </div>

                            <!-- Step 5: Review & Submit -->
                            <div class="step-card" id="step5">
                                <h3 class="step-title">5. REVIEW & SUBMIT</h3>
                                <div class="review-section">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h5 class="text-primary">PACKAGE DETAILS</h5>
                                            <div id="reviewPackage" class="text-white"></div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <h5 class="text-primary">EVENT DETAILS</h5>
                                            <div id="reviewEvent" class="text-white"></div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <h5 class="text-primary">CONTACT DETAILS</h5>
                                            <div id="reviewContact" class="text-white"></div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <h5 class="text-primary">PAYMENT DETAILS</h5>
                                            <div id="reviewPayment" class="text-white"></div>
                                        </div>
                                    </div>
                                    <div class="total-section mt-4 p-3 rounded">
                                        <h4 class="text-white text-center">TOTAL AMOUNT: <span id="reviewTotal" class="text-primary"></span></h4>
                                        <div id="reviewDownpayment" class="text-center mt-2"></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary btn-reservation" onclick="prevStep(5)"><i class="fas fa-arrow-left me-2"></i> BACK</button>
                                    <button type="submit" name="submit_reservation" class="btn btn-success btn-reservation">CONFIRM RESERVATION <i class="fas fa-check ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Custom JS -->
    <script src="js/common.js"></script>
    <script src="js/reservation.js"></script>
</body>
</html>