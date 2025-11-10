<?php
// profile.php
require_once 'db/db_connect.php';

if (!isLoggedIn()) {
    header('Location: login_register.php');
    exit();
}

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/profile.css">
</head>
<body></body>
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
    <main class="profile-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="profile-card">
                        <!-- Profile Header -->
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <i class="fas fa-user" style="font-size: 3rem; line-height: 120px;"></i>
                            </div>
                            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                            <p>Customer | <?php echo htmlspecialchars($user['email']); ?></p>
                            <button class="btn btn-light">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                        </div>

                        <div class="p-4">
                            <!-- Personal Information -->
                            <div class="info-section">
                                <h4 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Full Name:</strong>
                                        <p class="text-white"><?php echo htmlspecialchars($user['name']); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Email Address:</strong>
                                        <p class="text-white"><?php echo htmlspecialchars($user['email']); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Phone Number:</strong>
                                        <p class="text-white"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Member Since:</strong>
                                        <p class="text-white"><?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                                    </div>
                                    <?php if (!empty($user['address'])): ?>
                                    <div class="col-12 mb-3">
                                        <strong>Address:</strong>
                                        <p class="text-white"><?php echo htmlspecialchars($user['address']); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Booking Statistics -->
                            <div class="info-section">
                                <h4 class="section-title"><i class="fas fa-chart-bar me-2"></i>Booking Statistics</h4>
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 rounded" style="background: var(--dark);">
                                            <h3 class="text-primary mb-0">8</h3>
                                            <small class="text-muted">Total Bookings</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 rounded" style="background: var(--dark);">
                                            <h3 class="text-success mb-0">2</h3>
                                            <small class="text-muted">Upcoming</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 rounded" style="background: var(--dark);">
                                            <h3 class="text-info mb-0">6</h3>
                                            <small class="text-muted">Completed</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 rounded" style="background: var(--dark);">
                                            <h3 class="text-warning mb-0">0</h3>
                                            <small class="text-muted">Cancelled</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="info-section">
                                <h4 class="section-title"><i class="fas fa-history me-2"></i>Recent Activity</h4>
                                <div class="p-3 rounded" style="background: var(--dark);">
                                    <p class="mb-2"><strong>Last Booking:</strong> Oct 20, 2025 – Wedding Event (Premium Sound Package)</p>
                                    <p class="mb-2"><strong>Next Booking:</strong> Dec 15, 2025 – Corporate Event</p>
                                    <p class="mb-0"><strong>Preferred Service:</strong> Concert Lighting & Stage Sound</p>
                                </div>
                            </div>

                            <!-- Account Actions -->
                            <div class="info-section">
                                <h4 class="section-title"><i class="fas fa-cog me-2"></i>Account Settings</h4>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                    </button>
                                    <a href="my-bookings.php" class="btn btn-outline-primary">
                                        <i class="fas fa-history me-2"></i>Booking History
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 text-end" style="border-top: 1px solid var(--border);">
                            <a href="logout.php" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
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
    <!-- Custom JS -->
    <script src="js/common.js"></script>
</body>
</html>