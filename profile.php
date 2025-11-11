<?php
// profile.php - Updated with index.php styling
require_once 'db/db_connect.php';

if (!isLoggedIn()) {
    header('Location: login_register.php');
    exit();
}

$user = getCurrentUser();
$user_id = $_SESSION['user_id'];

// Handle form submissions
$success_message = '';
$error_message = '';

// Update profile information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validate required fields
    if (empty($name) || empty($email)) {
        $error_message = "Name and email are required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Check if email is already taken by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "Email already exists. Please use a different email.";
        } else {
            // Update user profile
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Profile updated successfully!";
                // Update session data
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $user = getCurrentUser(); // Refresh user data
            } else {
                $error_message = "Error updating profile. Please try again.";
            }
            $stmt->close();
        }
    }
}

// Change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    if ($user_data['password'] !== $current_password) {
        $error_message = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $error_message = "New password must be at least 6 characters long.";
    } else {
        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Password changed successfully!";
        } else {
            $error_message = "Error changing password. Please try again.";
        }
        $stmt->close();
    }
}

// Get user's reservation history
$reservations = [];
$stmt = $conn->prepare("SELECT * FROM reservations WHERE contact_email = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $user['email']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
$stmt->close();

// Calculate statistics
$total_bookings = count($reservations);
$upcoming_bookings = count(array_filter($reservations, function($r) {
    return $r['status'] === 'Confirmed' && strtotime($r['event_date']) >= time();
}));
$completed_bookings = count(array_filter($reservations, function($r) {
    return $r['status'] === 'Confirmed' && strtotime($r['event_date']) < time();
}));
$cancelled_bookings = count(array_filter($reservations, function($r) {
    return $r['status'] === 'Cancelled';
}));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/profile.css">
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
            <div class="hero-badge">MY PROFILE</div>
            <h1 class="hero-title">
                <span class="title-line">Welcome Back,</span>
                <span class="title-line highlight"><?php echo htmlspecialchars($user['name']); ?></span>
            </h1>
            <p class="hero-subtitle">Manage your account, view booking history, and update your preferences</p>
            <div class="hero-buttons">
                <a href="#profile-content" class="btn btn-primary">
                    <i class="bi bi-person"></i>
                    View Profile
                </a>
                <a href="reservation.php" class="btn btn-secondary">
                    <i class="bi bi-calendar-check"></i>
                    New Booking
                </a>
            </div>
        </div>
        
        <div class="hero-scroll">
            <div class="scroll-indicator">
                <span>Scroll to manage</span>
                <div class="scroll-arrow"></div>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <main class="profile-page" id="profile-content">
        <div class="container">
            <!-- Success/Error Messages -->
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Left Column - Profile Info & Stats -->
                <div class="col-lg-4 mb-4">
                    <!-- Profile Card -->
                    <div class="profile-main-card">
                        <div class="profile-header-section">
                            <div class="profile-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                            <p class="mb-3"><?php echo htmlspecialchars($user['email']); ?></p>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil me-2"></i>Edit Profile
                            </button>
                        </div>
                        
                        <div class="profile-details">
                            <div class="detail-item">
                                <i class="bi bi-telephone"></i>
                                <div class="detail-item-content">
                                    <strong>Phone</strong>
                                    <p><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-geo-alt"></i>
                                <div class="detail-item-content">
                                    <strong>Address</strong>
                                    <p><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-calendar"></i>
                                <div class="detail-item-content">
                                    <strong>Member Since</strong>
                                    <p><?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="profile-stats-grid">
                                <div class="profile-stat-card">
                                    <div class="profile-stat-number"><?php echo $total_bookings; ?></div>
                                    <div class="profile-stat-label">Total Bookings</div>
                                </div>
                                <div class="profile-stat-card">
                                    <div class="profile-stat-number"><?php echo $upcoming_bookings; ?></div>
                                    <div class="profile-stat-label">Upcoming</div>
                                </div>
                                <div class="profile-stat-card">
                                    <div class="profile-stat-number"><?php echo $completed_bookings; ?></div>
                                    <div class="profile-stat-label">Completed</div>
                                </div>
                                <div class="profile-stat-card">
                                    <div class="profile-stat-number"><?php echo $cancelled_bookings; ?></div>
                                    <div class="profile-stat-label">Cancelled</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Main Content -->
                <div class="col-lg-8">
                    <!-- Booking History -->
                    <div class="profile-content-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="profile-section-title">
                                <i class="bi bi-clock-history"></i>
                                Booking History
                            </h3>
                            <a href="reservation.php" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-2"></i>New Booking
                            </a>
                        </div>
                        
                        <?php if (empty($reservations)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x" style="font-size: 3rem; color: var(--text-secondary);"></i>
                                <h5 class="mt-3 text-white">No bookings yet</h5>
                                <p class="text-secondary mb-4">Start by making your first reservation</p>
                                <a href="reservation.php" class="btn btn-primary">Book Now</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="bookings-table">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Event Type</th>
                                            <th>Date</th>
                                            <th>Package</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td>#<?php echo $reservation['id']; ?></td>
                                            <td><?php echo htmlspecialchars($reservation['event_type']); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($reservation['event_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['package']); ?></td>
                                            <td>₱<?php echo number_format($reservation['total_amount'], 2); ?></td>
                                            <td>
                                                <span class="status-pill status-<?php echo strtolower($reservation['status']); ?>">
                                                    <?php echo $reservation['status']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Recent Activity & Actions -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="profile-content-card h-100">
                                <h4 class="profile-section-title">
                                    <i class="bi bi-activity"></i>
                                    Recent Activity
                                </h4>
                                <div class="activity-list">
                                    <?php if (!empty($reservations)): ?>
                                        <?php 
                                        $latest_reservation = $reservations[0];
                                        $next_reservation = array_filter($reservations, function($r) {
                                            return $r['status'] === 'Confirmed' && strtotime($r['event_date']) >= time();
                                        });
                                        $next_reservation = !empty($next_reservation) ? array_values($next_reservation)[0] : null;
                                        ?>
                                        <?php if ($next_reservation): ?>
                                            <div class="activity-item">
                                                <i class="bi bi-calendar-check text-primary"></i>
                                                <div class="activity-content">
                                                    <strong>Upcoming Event</strong>
                                                    <p><?php echo date('M j', strtotime($next_reservation['event_date'])); ?> - <?php echo htmlspecialchars($next_reservation['event_type']); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="activity-item">
                                            <i class="bi bi-clock text-info"></i>
                                            <div class="activity-content">
                                                <strong>Last Booking</strong>
                                                <p><?php echo date('M j, Y', strtotime($latest_reservation['created_at'])); ?></p>
                                            </div>
                                        </div>
                                        <div class="activity-item">
                                            <i class="bi bi-currency-dollar text-success"></i>
                                            <div class="activity-content">
                                                <strong>Total Spent</strong>
                                                <p>₱<?php echo number_format(array_sum(array_column($reservations, 'total_amount')), 2); ?></p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-3">
                                            <p class="text-secondary">No recent activity</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="profile-content-card h-100">
                                <h4 class="profile-section-title">
                                    <i class="bi bi-lightning"></i>
                                    Quick Actions
                                </h4>
                                <div class="action-buttons-vertical">
                                    <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                        <i class="bi bi-person me-2"></i>Edit Profile
                                    </button>
                                    <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bi bi-key me-2"></i>Change Password
                                    </button>
                                    <a href="reservation.php" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="bi bi-calendar-plus me-2"></i>New Booking
                                    </a>
                                    <a href="index.php" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-house me-2"></i>Back to Home
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="bi bi-person-gear me-2"></i>Edit Profile Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editProfileForm">
                    <div class="modal-body">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="09123456789" maxlength="11">
                                <div class="form-text">11-digit Philippine mobile number</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your complete address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="bi bi-shield-lock me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="changePasswordForm">
                    <div class="modal-body">
                        <input type="hidden" name="change_password" value="1">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                            <div class="form-text">Password must be at least 6 characters long.</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    <script src="js/profile.js"></script>
</body>
</html>