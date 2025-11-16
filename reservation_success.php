<?php
// reservation_success.php
require_once 'db/db_connect.php';

if (!isLoggedIn()) {
    header('Location: login_register.php');
    exit();
}

$reservation_id = $_GET['id'] ?? 0;
$user = getCurrentUser();

// Get reservation details
$reservation = null;
if ($reservation_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND contact_email = ?");
    $stmt->bind_param("is", $reservation_id, $user['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();
    $stmt->close();
}

if (!$reservation) {
    header('Location: reservation.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmed - JB Lights & Sound</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="dark-mode">
    <header class="main-header">
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
    <main class="reservation-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="success-card">
                        <div class="success-icon">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <h2 class="text-white mb-3">Reservation Confirmed!</h2>
                        <p class="text-secondary mb-4">Thank you for your reservation. We've received your booking details and will contact you shortly.</p>
                        
                        <div class="reservation-details bg-dark rounded p-4 mb-4">
                            <h5 class="text-primary mb-3">Reservation Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Booking ID:</strong> #<?php echo $reservation['id']; ?></p>
                                    <p><strong>Package:</strong> <?php echo htmlspecialchars($reservation['package']); ?></p>
                                    <p><strong>Event Date:</strong> <?php echo date('F j, Y', strtotime($reservation['event_date'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total Amount:</strong> ₱<?php echo number_format($reservation['total_amount'], 2); ?></p>
                                    <p><strong>Payment Method:</strong> <?php echo strtoupper($reservation['payment_method']); ?></p>
                                    <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo $reservation['status']; ?></span></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($reservation['payment_method'] === 'gcash'): ?>
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle me-2"></i>GCash Payment Instructions</h6>
                            <p class="mb-2">Please send the downpayment of <strong>₱<?php echo number_format($reservation['downpayment_amount'], 2); ?></strong> to:</p>
                            <p class="mb-1"><strong>GCash Number:</strong> 0965 639 6053</p>
                            <p class="mb-1"><strong>Account Name:</strong> JB LIGHTS & SOUND</p>
                            <p class="mb-0"><small>Please include your booking ID (#<?php echo $reservation['id']; ?>) in the transaction notes.</small></p>
                        </div>
                        <?php endif; ?>

                        <div class="action-buttons">
                            <a href="profile.php" class="btn btn-primary me-3">
                                <i class="bi bi-person me-2"></i>View My Bookings
                            </a>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-house me-2"></i>Back to Home
                            </a>
                        </div>
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
</body>
</html>