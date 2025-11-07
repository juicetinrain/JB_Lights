<?php
// reservation.php - JB Lights & Sound Reservation System
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$inserted = false;
$error = '';
$submission = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_submit'])) {
    // Sanitize inputs
    $package = trim($_POST['package'] ?? '');
    $event_type = trim($_POST['event_type'] ?? '');
    $event_date = trim($_POST['event_date'] ?? '');
    $event_time = trim($_POST['event_time'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $facebook_account = trim($_POST['facebook_account'] ?? '');
    $contact_phone = trim($_POST['contact_phone'] ?? '');
    $payment = trim($_POST['payment'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    // Validate required fields
    if (!$package || !$event_date || !$address || !$contact_name || !$contact_phone || !$payment) {
        $error = "Please complete all required fields.";
    } else {
        // Validate date
        $today = new DateTime();
        $selected = DateTime::createFromFormat('Y-m-d', $event_date);
        if (!$selected || $selected <= $today) {
            $error = "Event date must be after today.";
        } else {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO reservations (package, event_type, event_date, event_time, address, contact_name, facebook_account, contact_phone, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $package, $event_type, $event_date, $event_time, $address, $contact_name, $facebook_account, $contact_phone, $payment, $notes);
            
            if ($stmt->execute()) {
                $inserted = true;
                $id = $stmt->insert_id;
                $submission = [
                    'id' => $id,
                    'package' => $package,
                    'event_type' => $event_type,
                    'event_date' => $event_date,
                    'event_time' => $event_time,
                    'address' => $address,
                    'contact_name' => $contact_name,
                    'contact_phone' => $contact_phone,
                    'facebook_account' => $facebook_account,
                    'payment' => $payment,
                    'notes' => $notes
                ];
            } else {
                $error = "Database error: " . $stmt->error;
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
    <title>Book Your Event - JB Lights & Sound</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
        <!-- Header - MATCHES INDEX.PHP -->
    <header class="main-header">
        <div class="header-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <span class="j-letter">J</span>
                    <span class="b-letter">B</span>
                </div>
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

    <main class="main-content">
        <div class="container">
            <!-- Progress Bar -->
            <div class="progress-shell">
                <div class="progress-track">
                    <div id="progressFill" class="progress-fill"></div>
                </div>
                <div class="progress-labels">
                    <div>Package</div>
                    <div>Date & Time</div>
                    <div>Location & Contact</div>
                    <div>Review & Pay</div>
                </div>
            </div>

            <?php if ($inserted): ?>
                <!-- Success Message -->
                <div class="step-card success-card">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2>Reservation Confirmed!</h2>
                    <p class="success-message">Thank you for your booking. We'll contact you within 24 hours to confirm details.</p>
                    
                    <div class="booking-details">
                        <h5>Booking Details:</h5>
                        <div class="detail-item">
                            <strong>Booking ID:</strong> #<?php echo htmlspecialchars($submission['id']); ?>
                        </div>
                        <div class="detail-item">
                            <strong>Package:</strong> <?php echo htmlspecialchars($submission['package']); ?>
                        </div>
                        <div class="detail-item">
                            <strong>Event:</strong> <?php echo htmlspecialchars($submission['event_type'] ?: 'Not specified'); ?> 
                            on <?php echo htmlspecialchars($submission['event_date']); ?>
                            <?php echo $submission['event_time'] ? 'at ' . htmlspecialchars($submission['event_time']) : ''; ?>
                        </div>
                        <div class="detail-item">
                            <strong>Contact:</strong> <?php echo htmlspecialchars($submission['contact_name']); ?> • 
                            <?php echo htmlspecialchars($submission['contact_phone']); ?>
                        </div>
                        <div class="detail-item">
                            <strong>Payment Method:</strong> <?php echo htmlspecialchars($submission['payment']); ?>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="reservation.php" class="btn btn-primary">Book Another Event</a>
                        <a href="index.php" class="btn btn-outline">Back to Home</a>
                    </div>
                </div>

            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <!-- Reservation Form -->
                <div class="step-card">
                    <form id="reservationForm" method="POST" novalidate>
                        <input type="hidden" name="final_submit" value="1">

                        <!-- Step 1: Package Selection -->
                        <section id="step-1" class="step active">
                            <h2>Choose Your Package</h2>
                            <p class="step-description">Select the perfect package for your event</p>

                            <div class="packages-grid">
                                <div class="package-card" data-value="Basic Package (₱5,000)">
                                    <div class="package-header">
                                        <h4>BASIC</h4>
                                        <div class="price">₱5,000</div>
                                    </div>
                                    <div class="package-features">
                                        <div>✓ Basic Sound System</div>
                                        <div>✓ Basic Lighting</div>
                                        <div>✓ 4 Hours Service</div>
                                        <div>✓ 1 Technician</div>
                                    </div>
                                    <button type="button" class="btn-select" onclick="selectPackage(this)">Select</button>
                                </div>

                                <div class="package-card" data-value="Upgraded Package (₱6,000)">
                                    <div class="package-header">
                                        <h4>UPGRADED</h4>
                                        <div class="price">₱6,000</div>
                                    </div>
                                    <div class="package-features">
                                        <div>✓ Enhanced Sound System</div>
                                        <div>✓ Basic + Effects Lighting</div>
                                        <div>✓ 5 Hours Service</div>
                                        <div>✓ 1 Technician</div>
                                    </div>
                                    <button type="button" class="btn-select" onclick="selectPackage(this)">Select</button>
                                </div>

                                <div class="package-card" data-value="Professional Package (₱7,000)">
                                    <div class="package-header">
                                        <h4>PROFESSIONAL</h4>
                                        <div class="price">₱7,000</div>
                                    </div>
                                    <div class="package-features">
                                        <div>✓ Professional Sound System</div>
                                        <div>✓ Full Lighting Effects</div>
                                        <div>✓ 6 Hours Service</div>
                                        <div>✓ 2 Technicians</div>
                                    </div>
                                    <button type="button" class="btn-select" onclick="selectPackage(this)">Select</button>
                                </div>

                                <div class="package-card" data-value="Premium Package (₱10,000)">
                                    <div class="package-header">
                                        <h4>PREMIUM</h4>
                                        <div class="price">₱10,000</div>
                                    </div>
                                    <div class="package-features">
                                        <div>✓ Premium Sound System</div>
                                        <div>✓ Advanced Lighting Rig</div>
                                        <div>✓ 8 Hours Service</div>
                                        <div>✓ 2 Technicians</div>
                                        <div>✓ DJ Support</div>
                                    </div>
                                    <button type="button" class="btn-select" onclick="selectPackage(this)">Select</button>
                                </div>
                            </div>

                            <input type="hidden" name="package" id="inputPackage" required>

                            <div class="form-actions">
                                <button type="button" class="btn btn-outline" onclick="goStep(2)">Next: Date & Time</button>
                            </div>
                        </section>

                        <!-- Step 2: Event Details -->
                        <section id="step-2" class="step">
                            <h2>Event Details</h2>
                            <p class="step-description">Tell us about your event</p>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="inputEventType">Event Type *</label>
                                    <select id="inputEventType" name="event_type" class="form-control" required>
                                        <option value="">Select event type</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Birthday">Birthday Party</option>
                                        <option value="Corporate">Corporate Event</option>
                                        <option value="Anniversary">Anniversary</option>
                                        <option value="Christmas Party">Christmas Party</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="inputDate">Event Date *</label>
                                    <input type="date" id="inputDate" name="event_date" class="form-control" required>
                                    <small class="form-text">Please select a future date</small>
                                </div>

                                <div class="form-group">
                                    <label for="inputTime">Start Time</label>
                                    <input type="time" id="inputTime" name="event_time" class="form-control">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-outline" onclick="goStep(1)">Back</button>
                                <button type="button" class="btn btn-primary" onclick="goStep(3)">Next: Location & Contact</button>
                            </div>
                        </section>

                                                  <!-- Step 3: Location & Contact -->
                          <section id="step-3" class="step">
                              <h2>Location & Contact Details</h2>
                              <p class="step-description">Where and how can we reach you?</p>

                              <div class="form-group">
                                  <label for="inputAddress">Event Address *</label>
                                  <input type="text" id="inputAddress" name="address" class="form-control" 
                                        placeholder="Click on the map or type address and press Enter" required>
                              </div>

                              <div class="map-container">
                                  <div class="map-instructions">
                                      <i class="bi bi-info-circle"></i> Click anywhere on the map to set your event location
                                  </div>
                                  <div id="map"></div>
                                  <div class="map-help">
                                      <i class="bi bi-lightbulb"></i>
                                      Click on the map or drag the marker to set your exact location. The address will update automatically.
                                  </div>
                              </div>

                              <div class="form-grid">
                                  <div class="form-group">
                                      <label for="inputName">Contact Person Name *</label>
                                      <input type="text" id="inputName" name="contact_name" class="form-control" required>
                                  </div>

                                  <div class="form-group">
                                      <label for="inputPhone">Contact Number *</label>
                                      <input type="tel" id="inputPhone" name="contact_phone" class="form-control" 
                                            placeholder="09XX-XXX-XXXX" required>
                                  </div>

                                  <div class="form-group">
                                      <label for="facebook_account">Facebook Account (Optional)</label>
                                      <input type="text" id="facebook_account" name="facebook_account" class="form-control"
                                            placeholder="For easier communication">
                                  </div>
                              </div>

                              <div class="form-group">
                                  <label for="inputNotes">Additional Notes</label>
                                  <textarea id="inputNotes" name="notes" class="form-control" rows="3" 
                                            placeholder="Any special requests or instructions..."></textarea>
                              </div>

                              <div class="form-actions">
                                  <button type="button" class="btn btn-outline" onclick="goStep(2)">Back</button>
                                  <button type="button" class="btn btn-primary" onclick="goStep(4)">Next: Review & Pay</button>
                              </div>
                          </section>

                        <!-- Step 4: Payment & Review -->
                        <section id="step-4" class="step">
                            <h2>Review & Payment</h2>
                            <p class="step-description">Confirm your details and choose payment method</p>

                            <div class="review-section">
                                <h5>Your Booking Summary</h5>
                                <div class="review-details">
                                    <div class="review-item">
                                        <span>Package:</span>
                                        <span id="reviewPackage">—</span>
                                    </div>
                                    <div class="review-item">
                                        <span>Event:</span>
                                        <span id="reviewEvent">—</span>
                                    </div>
                                    <div class="review-item">
                                        <span>Address:</span>
                                        <span id="reviewAddress">—</span>
                                    </div>
                                    <div class="review-item">
                                        <span>Contact:</span>
                                        <span id="reviewContact">—</span>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-section">
                                <h5>Payment Method *</h5>
                                <div class="payment-options">
                                    <div class="payment-option">
                                        <input type="radio" id="payCash" name="payment" value="Cash" required>
                                        <label for="payCash">
                                            <i class="bi bi-cash-coin"></i>
                                            <div>
                                                <strong>Cash on Delivery</strong>
                                                <small>Pay after the event</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <input type="radio" id="payGcash" name="payment" value="GCash">
                                        <label for="payGcash">
                                            <i class="bi bi-phone"></i>
                                            <div>
                                                <strong>GCash</strong>
                                                <small>30% downpayment required</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div id="downpaymentInfo" class="downpayment-info" style="display: none;">
                                    <div class="downpayment-amount">
                                        <strong>Downpayment Required:</strong>
                                        <span id="reviewDown">—</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-outline" onclick="goStep(3)">Back</button>
                                <button type="button" class="btn btn-primary" onclick="openPreviewModal()">
                                    Review & Confirm Booking
                                </button>
                            </div>
                        </section>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Your Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalReview"></div>
                    <div id="gcashBox" class="gcash-payment" style="display: none;">
                        <hr>
                        <h6>GCash Payment Instructions</h6>
                        <div class="gcash-details">
                            <div class="gcash-qr">
                                <div class="qr-placeholder">
                                    <i class="bi bi-qr-code"></i>
                                    <small>GCash QR Code</small>
                                </div>
                            </div>
                            <div class="gcash-info">
                                <p><strong>Send payment to:</strong></p>
                                <p>Account Name: <strong>JB Lights & Sound</strong></p>
                                <p>Mobile: <strong>0965-639-6053</strong></p>
                                <p>Downpayment Amount: <strong id="modalDown">—</strong></p>
                                <small class="text-muted">Please send screenshot of payment confirmation</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="modalConfirm" class="btn btn-primary">Confirm Booking</button>
                </div>
            </div>
        </div>
    </div>

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
                        <li><a href="index.php#services">Services</a></li>
                        <li><a href="index.php#featured">Gallery</a></li>
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
                <a href="reservation.php" class="menu-item active">
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="home.js"></script>
    <script src="reservation.js"></script>
</body>
</html>