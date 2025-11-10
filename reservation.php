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
    $package_key = $_POST['package'];
    $total_amount = $packages[$package_key]['price'];
    
    // Insert reservation
    $stmt = $conn->prepare("INSERT INTO reservations (contact_name, contact_email, contact_phone, event_type, event_date, event_address, package, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssd", $contact_name, $contact_email, $contact_phone, $event_type, $event_date, $event_address, $packages[$package_key]['name'], $total_amount);
    
    if ($stmt->execute()) {
        $reservation_id = $conn->insert_id;
        $success_message = "Reservation submitted successfully! Your Booking ID: #" . $reservation_id;
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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
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

    <!-- Main Content -->
    <main class="reservation-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="reservation-card">
                        <div class="reservation-header text-center mb-4">
                            <h1 class="text-white">Make a Reservation</h1>
                            <p class="text-secondary">Book your lights and sound package for your event</p>
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
                                <h3 class="step-title">1. Select Your Package</h3>
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
                                                    <h6>Sound System:</h6>
                                                    <ul>
                                                        <?php foreach ($package['sound_system'] as $feature): ?>
                                                            <li><?php echo $feature; ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <h6>Lights Setup:</h6>
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
                                    <button type="button" class="btn btn-primary" onclick="nextStep(1)">Next <i class="fas fa-arrow-right ms-2"></i></button>
                                </div>
                            </div>

                            <!-- Step 2: Event Details -->
                            <div class="step-card" id="step2">
                                <h3 class="step-title">2. Event Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Contact Name</label>
                                        <input type="text" class="form-control" name="contact_name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Contact Email</label>
                                        <input type="email" class="form-control" name="contact_email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Contact Phone</label>
                                        <input type="tel" class="form-control" name="contact_phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Event Type</label>
                                        <select class="form-select" name="event_type" required>
                                            <option value="">Select Event Type</option>
                                            <option value="Wedding">Wedding</option>
                                            <option value="Birthday">Birthday</option>
                                            <option value="Corporate">Corporate Event</option>
                                            <option value="Concert">Concert</option>
                                            <option value="Party">Party</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-white">Event Date</label>
                                        <input type="date" class="form-control" name="event_date" min="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label text-white">Event Address</label>
                                        <textarea class="form-control" name="event_address" rows="3" required placeholder="Full event address..."></textarea>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next <i class="fas fa-arrow-right ms-2"></i></button>
                                </div>
                            </div>

                            <!-- Step 3: Review & Submit -->
                            <div class="step-card" id="step3">
                                <h3 class="step-title">3. Review & Submit</h3>
                                <div class="review-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Package Details</h5>
                                            <div id="reviewPackage"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Event Details</h5>
                                            <div id="reviewEvent"></div>
                                        </div>
                                    </div>
                                    <div class="total-section mt-4 p-3 rounded" style="background: var(--dark);">
                                        <h4 class="text-white text-center">Total Amount: <span id="reviewTotal" class="text-primary"></span></h4>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep(3)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                                    <button type="submit" name="submit_reservation" class="btn btn-success">Confirm Reservation <i class="fas fa-check ms-2"></i></button>
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
    <script>
        // Navigation functions
        function openNav() {
            document.getElementById("sidenav").style.width = "320px";
        }

        function closeNav() {
            document.getElementById("sidenav").style.width = "0";
        }

        // Step navigation
        let currentStep = 1;

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Show current step
            document.getElementById('step' + step).classList.add('active');
            currentStep = step;
        }

        function nextStep(step) {
            if (validateStep(step)) {
                if (step === 2) {
                    updateReview();
                }
                showStep(step + 1);
            }
        }

        function prevStep(step) {
            showStep(step - 1);
        }

        function validateStep(step) {
            if (step === 1) {
                const selectedPackage = document.querySelector('input[name="package"]:checked');
                if (!selectedPackage) {
                    alert('Please select a package');
                    return false;
                }
            } else if (step === 2) {
                const requiredFields = document.querySelectorAll('#step2 [required]');
                for (let field of requiredFields) {
                    if (!field.value.trim()) {
                        alert('Please fill in all required fields');
                        field.focus();
                        return false;
                    }
                }
                
                // Validate date is not in the past
                const eventDate = new Date(document.querySelector('input[name="event_date"]').value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (eventDate < today) {
                    alert('Event date cannot be in the past');
                    return false;
                }
            }
            return true;
        }

        function updateReview() {
            // Package details
            const selectedPackage = document.querySelector('input[name="package"]:checked');
            if (selectedPackage) {
                const packageLabel = selectedPackage.parentElement.querySelector('.package-header h4').textContent;
                const packagePrice = selectedPackage.parentElement.querySelector('.package-price').textContent;
                document.getElementById('reviewPackage').innerHTML = `
                    <p><strong>Package:</strong> ${packageLabel}</p>
                    <p><strong>Price:</strong> ${packagePrice}</p>
                `;
                document.getElementById('reviewTotal').textContent = packagePrice;
            }

            // Event details
            const contactName = document.querySelector('input[name="contact_name"]').value;
            const contactEmail = document.querySelector('input[name="contact_email"]').value;
            const contactPhone = document.querySelector('input[name="contact_phone"]').value;
            const eventType = document.querySelector('select[name="event_type"]').value;
            const eventDate = document.querySelector('input[name="event_date"]').value;
            const eventAddress = document.querySelector('textarea[name="event_address"]').value;

            document.getElementById('reviewEvent').innerHTML = `
                <p><strong>Name:</strong> ${contactName}</p>
                <p><strong>Email:</strong> ${contactEmail}</p>
                <p><strong>Phone:</strong> ${contactPhone}</p>
                <p><strong>Event Type:</strong> ${eventType}</p>
                <p><strong>Event Date:</strong> ${eventDate}</p>
                <p><strong>Address:</strong> ${eventAddress}</p>
            `;
        }

        // Package selection styling
        document.querySelectorAll('.package-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.package-card').forEach(card => {
                    card.classList.remove('selected');
                });
                if (this.checked) {
                    this.closest('.package-card').classList.add('selected');
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Set minimum date to today
        document.querySelector('input[name="event_date"]').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>