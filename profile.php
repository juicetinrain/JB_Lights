<?php
// profile.php - Updated with admin panel design and cancellation requests
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

// Handle cancellation requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_cancellation'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $reason = trim($_POST['cancellation_reason']);
    
    if ($reservation_id > 0 && !empty($reason)) {
        // Check if user owns this reservation
        $stmt = $conn->prepare("SELECT id FROM reservations WHERE id = ? AND contact_email = ?");
        $stmt->bind_param("is", $reservation_id, $user['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Check if cancellation request already exists
            $stmt = $conn->prepare("SELECT id FROM cancellation_requests WHERE reservation_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $reservation_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                // Create cancellation request
                $stmt = $conn->prepare("INSERT INTO cancellation_requests (reservation_id, user_id, reason) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $reservation_id, $user_id, $reason);
                
                if ($stmt->execute()) {
                    $success_message = "Cancellation request submitted successfully! We'll review your request shortly.";
                } else {
                    $error_message = "Error submitting cancellation request. Please try again.";
                }
            } else {
                $error_message = "You already have a pending cancellation request for this booking.";
            }
        } else {
            $error_message = "Invalid booking or you don't have permission to cancel this booking.";
        }
        $stmt->close();
    } else {
        $error_message = "Please provide a reason for cancellation.";
    }
}

// Get user's reservation history with cancellation request status
$reservations = [];
$stmt = $conn->prepare("
    SELECT r.*, 
           cr.status as cancellation_status,
           cr.reason as cancellation_reason,
           cr.created_at as cancellation_requested_at
    FROM reservations r 
    LEFT JOIN cancellation_requests cr ON r.id = cr.reservation_id AND cr.user_id = ?
    WHERE r.contact_email = ? 
    ORDER BY r.created_at DESC
");
$stmt->bind_param("is", $user_id, $user['email']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
$stmt->close();

// Get pending cancellation requests count
$pending_cancellations = 0;
$stmt = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM cancellation_requests cr 
    JOIN reservations r ON cr.reservation_id = r.id 
    WHERE cr.user_id = ? AND cr.status = 'pending'
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pending_cancellations = $result->fetch_assoc()['count'];
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

// Get active tab from session or default to dashboard
$active_tab = $_SESSION['profile_active_tab'] ?? 'dashboard';
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
    <link rel="stylesheet" href="css/pages/admin.css">
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
    <main class="admin-page" id="profile-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Profile Sidebar -->
                <div class="col-lg-3 col-xl-2">
                    <div class="admin-sidebar">
                        <div class="sidebar-header">
                            <div class="admin-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                            <p class="text-secondary"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        
                        <nav class="sidebar-nav">
                            <a href="#" class="nav-item <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>" data-tab="dashboard">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'bookings' ? 'active' : ''; ?>" data-tab="bookings">
                                <i class="bi bi-calendar-check"></i>
                                <span>My Bookings</span>
                                <span class="badge bg-primary"><?php echo $total_bookings; ?></span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'cancellations' ? 'active' : ''; ?>" data-tab="cancellations">
                                <i class="bi bi-x-circle"></i>
                                <span>Cancellations</span>
                                <span class="badge bg-warning"><?php echo $pending_cancellations; ?></span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'profile' ? 'active' : ''; ?>" data-tab="profile">
                                <i class="bi bi-person-gear"></i>
                                <span>Profile Settings</span>
                            </a>
                            <div class="sidebar-footer">
                                <a href="logout.php" class="nav-item logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-xl-10">
                    <div class="admin-main-content">
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

                        <!-- Dashboard Tab -->
                        <div id="dashboard-tab" class="tab-content <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
                            <!-- Quick Stats -->
                            <div class="row mb-4">
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $total_bookings; ?></div>
                                            <div class="stat-label">Total Bookings</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon bg-info">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $upcoming_bookings; ?></div>
                                            <div class="stat-label">Upcoming</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon bg-success">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $completed_bookings; ?></div>
                                            <div class="stat-label">Completed</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon bg-danger">
                                            <i class="bi bi-x-circle"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $cancelled_bookings; ?></div>
                                            <div class="stat-label">Cancelled</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Recent Bookings -->
                                <div class="col-lg-8 mb-4">
                                    <div class="admin-content-card">
                                        <div class="content-header">
                                            <h3 class="content-title">
                                                <i class="bi bi-clock-history me-2"></i>
                                                Recent Bookings
                                            </h3>
                                            <a href="#" class="btn btn-primary btn-sm" data-tab="bookings">
                                                View All
                                            </a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="admin-table">
                                                <thead>
                                                    <tr>
                                                        <th>Booking ID</th>
                                                        <th>Event Type</th>
                                                        <th>Event Date</th>
                                                        <th>Package</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($reservations) === 0): ?>
                                                        <tr><td colspan="6" class="text-center py-4">No bookings found.</td></tr>
                                                    <?php else: ?>
                                                        <?php foreach (array_slice($reservations, 0, 5) as $booking): ?>
                                                            <tr>
                                                                <td>#<?php echo $booking['id']; ?></td>
                                                                <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                                                <td><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></td>
                                                                <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                                                <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                                                <td>
                                                                    <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                                                        <i class="bi bi-circle-fill me-1"></i>
                                                                        <?php echo $booking['status']; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="col-lg-4 mb-4">
                                    <div class="admin-content-card h-100">
                                        <h3 class="content-title">
                                            <i class="bi bi-lightning me-2"></i>
                                            Quick Actions
                                        </h3>
                                        <div class="action-buttons-vertical">
                                            <a href="reservation.php" class="btn btn-primary w-100 mb-2">
                                                <i class="bi bi-calendar-plus me-2"></i>New Booking
                                            </a>
                                            <button class="btn btn-outline-primary w-100 mb-2" data-tab="bookings">
                                                <i class="bi bi-calendar-check me-2"></i>View Bookings
                                            </button>
                                            <button class="btn btn-outline-primary w-100 mb-2" data-tab="profile">
                                                <i class="bi bi-person me-2"></i>Edit Profile
                                            </button>
                                            <a href="index.php" class="btn btn-outline-secondary w-100">
                                                <i class="bi bi-house me-2"></i>Back to Home
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- In the bookings-tab section, update the table to show cancellation status better -->
<div id="bookings-tab" class="tab-content <?php echo $active_tab === 'bookings' ? 'active' : ''; ?>">
    <div class="admin-content-card">
        <div class="content-header">
            <h3 class="content-title">
                <i class="bi bi-calendar-check me-2"></i>
                My Bookings
            </h3>
            <div class="filters">
                <div class="search-box">
                    <input type="text" id="bookingsSearch" class="form-control form-control-sm" placeholder="Search bookings...">
                    <i class="bi bi-search search-icon"></i>
                </div>
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Event Type</th>
                        <th>Event Date</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservations) === 0): ?>
                        <tr><td colspan="7" class="text-center py-4">No bookings found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $booking): ?>
                            <tr data-booking-id="<?php echo $booking['id']; ?>" class="booking-row status-<?php echo strtolower($booking['status']); ?>">
                                <td>#<?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></td>
                                <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                            <i class="bi bi-circle-fill me-1"></i>
                                            <?php echo $booking['status']; ?>
                                        </span>
                                        <?php if ($booking['cancellation_status'] === 'pending'): ?>
                                            <small class="text-warning">
                                                <i class="bi bi-clock me-1"></i>Cancellation Requested
                                            </small>
                                        <?php elseif ($booking['cancellation_status'] === 'approved'): ?>
                                            <small class="text-success">
                                                <i class="bi bi-check-circle me-1"></i>Cancellation Approved
                                            </small>
                                        <?php elseif ($booking['cancellation_status'] === 'rejected'): ?>
                                            <small class="text-danger">
                                                <i class="bi bi-x-circle me-1"></i>Cancellation Rejected
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary view-booking-details" 
                                                data-id="<?php echo $booking['id']; ?>"
                                                title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if ($booking['status'] === 'Confirmed' && !$booking['cancellation_status'] && strtotime($booking['event_date']) >= time()): ?>
                                            <button class="btn btn-sm btn-outline-danger request-cancellation" 
                                                    data-id="<?php echo $booking['id']; ?>"
                                                    title="Request Cancellation">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

                        <!-- Cancellations Tab -->
                        <div id="cancellations-tab" class="tab-content <?php echo $active_tab === 'cancellations' ? 'active' : ''; ?>">
                            <div class="admin-content-card">
                                <div class="content-header">
                                    <h3 class="content-title">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Cancellation Requests
                                    </h3>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Event Date</th>
                                                <th>Package</th>
                                                <th>Reason</th>
                                                <th>Requested</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $cancellation_requests = array_filter($reservations, function($r) {
                                                return !empty($r['cancellation_status']);
                                            });
                                            ?>
                                            <?php if (count($cancellation_requests) === 0): ?>
                                                <tr><td colspan="6" class="text-center py-4">No cancellation requests found.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($cancellation_requests as $request): ?>
                                                    <tr>
                                                        <td>#<?php echo $request['id']; ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($request['event_date'])); ?></td>
                                                        <td><?php echo htmlspecialchars($request['package']); ?></td>
                                                        <td>
                                                            <span class="message-preview" data-bs-toggle="tooltip" data-bs-title="<?php echo htmlspecialchars($request['cancellation_reason']); ?>">
                                                                <?php echo strlen($request['cancellation_reason']) > 50 ? substr($request['cancellation_reason'], 0, 50) . '...' : $request['cancellation_reason']; ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M j, Y', strtotime($request['cancellation_requested_at'])); ?></td>
                                                        <td>
                                                            <span class="status-pill status-<?php echo $request['cancellation_status']; ?>">
                                                                <i class="bi bi-circle-fill me-1"></i>
                                                                <?php echo ucfirst($request['cancellation_status']); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Settings Tab -->
                        <div id="profile-tab" class="tab-content <?php echo $active_tab === 'profile' ? 'active' : ''; ?>">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="admin-content-card h-100">
                                        <h3 class="content-title">
                                            <i class="bi bi-person-gear me-2"></i>
                                            Profile Information
                                        </h3>
                                        <form method="POST" id="editProfileForm">
                                            <input type="hidden" name="update_profile" value="1">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Full Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address *</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="09123456789" maxlength="11">
                                                <div class="form-text">11-digit Philippine mobile number</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your complete address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-check-circle me-2"></i>Update Profile
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <div class="admin-content-card h-100">
                                        <h3 class="content-title">
                                            <i class="bi bi-shield-lock me-2"></i>
                                            Change Password
                                        </h3>
                                        <form method="POST" id="changePasswordForm">
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
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-key me-2"></i>Change Password
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">
                        <i class="bi bi-info-circle me-2"></i>
                        Booking Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailsModalBody">
                    <!-- Details content will be loaded here -->
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancellation Request Modal -->
    <div class="modal fade" id="cancellationModal" tabindex="-1" aria-labelledby="cancellationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="cancellationModalLabel">
                        <i class="bi bi-x-circle me-2"></i>
                        Request Cancellation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="cancellationForm">
                    <input type="hidden" name="request_cancellation" value="1">
                    <input type="hidden" name="reservation_id" id="cancellationReservationId" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Reason for Cancellation *</label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="4" required placeholder="Please provide a detailed reason for your cancellation request..."></textarea>
                            <div class="form-text">Your cancellation request will be reviewed by our team. Refunds may be subject to our cancellation policy.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit Request</button>
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
    <!-- Custom JS -->
    <script src="js/common.js"></script>
    <script src="js/profile.js"></script>
</body>
</html>