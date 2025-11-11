<?php
// admin.php - Enhanced with search, detailed views, and better UI
require_once 'db/db_connect.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: login_register.php');
    exit();
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $id = intval($_POST['id']);
        $status = trim($_POST['status']);
        $allowed = ['Pending','Confirmed','Cancelled'];
        if ($id > 0 && in_array($status, $allowed)) {
            $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Handle delete booking
    if ($_POST['action'] === 'delete_booking') {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Return JSON response for AJAX calls
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    }
}

// Get active tab from session or default to dashboard
$active_tab = $_SESSION['admin_active_tab'] ?? 'dashboard';

// Get all bookings
$result = $conn->query("SELECT * FROM reservations ORDER BY created_at DESC");
$bookings = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $result->free();
}

// Get recent bookings for dashboard
$recent_result = $conn->query("SELECT * FROM reservations ORDER BY created_at DESC LIMIT 5");
$recent_bookings = [];
if ($recent_result) {
    while ($row = $recent_result->fetch_assoc()) {
        $recent_bookings[] = $row;
    }
    $recent_result->free();
}

// Get users
$users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$users = [];
if ($users_result) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
    $users_result->free();
}

// Get inventory
$inventory_result = $conn->query("SELECT * FROM inventory ORDER BY item_name");
$inventory = [];
if ($inventory_result) {
    while ($row = $inventory_result->fetch_assoc()) {
        $inventory[] = $row;
    }
    $inventory_result->free();
}

// Get contact submissions
$contact_result = $conn->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
$contacts = [];
if ($contact_result) {
    while ($row = $contact_result->fetch_assoc()) {
        $contacts[] = $row;
    }
    $contact_result->free();
}

// Calculate statistics
$total_bookings = count($bookings);
$pending_bookings = count(array_filter($bookings, function($b) { return $b['status'] === 'Pending'; }));
$confirmed_bookings = count(array_filter($bookings, function($b) { return $b['status'] === 'Confirmed'; }));
$cancelled_bookings = count(array_filter($bookings, function($b) { return $b['status'] === 'Cancelled'; }));
$total_users = count($users);
$total_inventory = count($inventory);
$total_contacts = count($contacts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/admin.css">
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
                <a href="admin.php" class="btn btn-primary me-2 d-none d-md-inline-block">
                    <i class="bi bi-speedometer2"></i> ADMIN PANEL
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
            <div class="hero-badge">ADMINISTRATION PANEL</div>
            <h1 class="hero-title">
                <span class="title-line">Welcome Back,</span>
                <span class="title-line highlight">Administrator</span>
            </h1>
            <p class="hero-subtitle">Manage bookings, users, inventory, and system operations</p>
            <div class="hero-buttons">
                <a href="#admin-content" class="btn btn-primary">
                    <i class="bi bi-speedometer2"></i>
                    View Dashboard
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-house"></i>
                    Back to Site
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

    <!-- Admin Content -->
    <main class="admin-page" id="admin-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Floating Sidebar -->
                <div class="col-lg-3 col-xl-2">
                    <div class="admin-sidebar">
                        <div class="sidebar-header">
                            <div class="admin-avatar">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            <h4>Admin Panel</h4>
                            <p class="text-secondary">System Administrator</p>
                        </div>
                        
                        <nav class="sidebar-nav">
                            <a href="#" class="nav-item <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>" data-tab="dashboard">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'bookings' ? 'active' : ''; ?>" data-tab="bookings">
                                <i class="bi bi-calendar-check"></i>
                                <span>Bookings</span>
                                <span class="badge bg-primary"><?php echo $total_bookings; ?></span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'users' ? 'active' : ''; ?>" data-tab="users">
                                <i class="bi bi-people"></i>
                                <span>Users</span>
                                <span class="badge bg-success"><?php echo $total_users; ?></span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'inventory' ? 'active' : ''; ?>" data-tab="inventory">
                                <i class="bi bi-box-seam"></i>
                                <span>Inventory</span>
                                <span class="badge bg-info"><?php echo $total_inventory; ?></span>
                            </a>
                            <a href="#" class="nav-item <?php echo $active_tab === 'contacts' ? 'active' : ''; ?>" data-tab="contacts">
                                <i class="bi bi-envelope"></i>
                                <span>Contacts</span>
                                <span class="badge bg-warning"><?php echo $total_contacts; ?></span>
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
                                        <div class="stat-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $total_users; ?></div>
                                            <div class="stat-label">Total Users</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $total_inventory; ?></div>
                                            <div class="stat-label">Inventory Items</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $total_contacts; ?></div>
                                            <div class="stat-label">Contact Messages</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Breakdown -->
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon bg-warning">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $pending_bookings; ?></div>
                                            <div class="stat-label">Pending Bookings</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon bg-success">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $confirmed_bookings; ?></div>
                                            <div class="stat-label">Confirmed Bookings</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="admin-stat-card">
                                        <div class="stat-icon bg-danger">
                                            <i class="bi bi-x-circle"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-number"><?php echo $cancelled_bookings; ?></div>
                                            <div class="stat-label">Cancelled Bookings</div>
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
                                                        <th>Customer</th>
                                                        <th>Package</th>
                                                        <th>Event Date</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($recent_bookings) === 0): ?>
                                                        <tr><td colspan="6" class="text-center py-4">No recent bookings found.</td></tr>
                                                    <?php else: ?>
                                                        <?php foreach ($recent_bookings as $booking): ?>
                                                            <tr>
                                                                <td>#<?php echo $booking['id']; ?></td>
                                                                <td><?php echo htmlspecialchars($booking['contact_name']); ?></td>
                                                                <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                                                <td><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></td>
                                                                <td>
                                                                    <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                                                        <?php echo $booking['status']; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-primary view-details" 
                                                                            data-type="booking" 
                                                                            data-id="<?php echo $booking['id']; ?>">
                                                                        <i class="bi bi-eye"></i>
                                                                    </button>
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
                                            <button class="btn btn-outline-primary w-100 mb-2" data-tab="bookings">
                                                <i class="bi bi-calendar-check me-2"></i>Manage Bookings
                                            </button>
                                            <button class="btn btn-outline-primary w-100 mb-2" data-tab="users">
                                                <i class="bi bi-people me-2"></i>Manage Users
                                            </button>
                                            <button class="btn btn-outline-primary w-100 mb-2" data-tab="inventory">
                                                <i class="bi bi-box-seam me-2"></i>Manage Inventory
                                            </button>
                                            <button class="btn btn-outline-primary w-100 mb-2" data-tab="contacts">
                                                <i class="bi bi-envelope me-2"></i>View Messages
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bookings Tab -->
                        <div id="bookings-tab" class="tab-content <?php echo $active_tab === 'bookings' ? 'active' : ''; ?>">
                            <div class="admin-content-card">
                                <div class="content-header">
                                    <h3 class="content-title">
                                        <i class="bi bi-calendar-check me-2"></i>
                                        Bookings Management
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
                                                <th>Customer Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Event Type</th>
                                                <th>Event Date</th>
                                                <th>Package</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($bookings) === 0): ?>
                                                <tr><td colspan="10" class="text-center py-4">No bookings found.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($bookings as $booking): ?>
                                                    <tr data-booking-id="<?php echo $booking['id']; ?>">
                                                        <td>#<?php echo $booking['id']; ?></td>
                                                        <td><?php echo htmlspecialchars($booking['contact_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($booking['contact_email']); ?></td>
                                                        <td><?php echo htmlspecialchars($booking['contact_phone']); ?></td>
                                                        <td><?php echo htmlspecialchars($booking['event_type']); ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></td>
                                                        <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                                        <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                                        <td>
                                                            <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                                                <?php echo $booking['status']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <button class="btn btn-sm btn-outline-primary view-details" 
                                                                        data-type="booking" 
                                                                        data-id="<?php echo $booking['id']; ?>">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                                <select class="form-select form-select-sm change-status" data-id="<?php echo $booking['id']; ?>">
                                                                    <option value="">Change</option>
                                                                    <option value="Pending" <?php echo $booking['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                    <option value="Confirmed" <?php echo $booking['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirm</option>
                                                                    <option value="Cancelled" <?php echo $booking['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancel</option>
                                                                </select>
                                                                <button class="btn btn-sm btn-danger delete-item" 
                                                                        data-type="booking" 
                                                                        data-id="<?php echo $booking['id']; ?>">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
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

                        <!-- Users Tab -->
                        <div id="users-tab" class="tab-content <?php echo $active_tab === 'users' ? 'active' : ''; ?>">
                            <div class="admin-content-card">
                                <div class="content-header">
                                    <h3 class="content-title">
                                        <i class="bi bi-people me-2"></i>
                                        Users Management
                                    </h3>
                                    <div class="filters">
                                        <div class="search-box">
                                            <input type="text" id="usersSearch" class="form-control form-control-sm" placeholder="Search users...">
                                            <i class="bi bi-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>User Type</th>
                                                <th>Join Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($users) === 0): ?>
                                                <tr><td colspan="7" class="text-center py-4">No users found.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($users as $user): ?>
                                                    <tr data-user-id="<?php echo $user['id']; ?>">
                                                        <td>#<?php echo $user['id']; ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($user['name']); ?>
                                                            <?php if ($user['user_type'] === 'admin'): ?>
                                                                <span class="badge bg-primary ms-1">Admin</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                                        <td>
                                                            <span class="badge <?php echo $user['user_type'] === 'admin' ? 'bg-primary' : 'bg-secondary'; ?>">
                                                                <?php echo ucfirst($user['user_type']); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <button class="btn btn-sm btn-outline-primary view-details" 
                                                                        data-type="user" 
                                                                        data-id="<?php echo $user['id']; ?>">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                                <?php if ($user['user_type'] !== 'admin'): ?>
                                                                <button class="btn btn-sm btn-danger delete-item" 
                                                                        data-type="user" 
                                                                        data-id="<?php echo $user['id']; ?>">
                                                                    <i class="bi bi-trash"></i>
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

                        <!-- Inventory Tab -->
                        <div id="inventory-tab" class="tab-content <?php echo $active_tab === 'inventory' ? 'active' : ''; ?>">
                            <div class="admin-content-card">
                                <div class="content-header">
                                    <h3 class="content-title">
                                        <i class="bi bi-box-seam me-2"></i>
                                        Inventory Management
                                    </h3>
                                    <div class="filters">
                                        <div class="search-box">
                                            <input type="text" id="inventorySearch" class="form-control form-control-sm" placeholder="Search inventory...">
                                            <i class="bi bi-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Brand</th>
                                                <th>Quantity</th>
                                                <th>Available</th>
                                                <th>Condition</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($inventory) === 0): ?>
                                                <tr><td colspan="8" class="text-center py-4">No inventory items found.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($inventory as $item): ?>
                                                    <tr data-item-id="<?php echo $item['id']; ?>">
                                                        <td>#<?php echo $item['id']; ?></td>
                                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                                        <td><?php echo htmlspecialchars($item['brand'] ?? 'N/A'); ?></td>
                                                        <td><?php echo $item['quantity']; ?></td>
                                                        <td><?php echo $item['available_quantity']; ?></td>
                                                        <td>
                                                            <span class="status-pill status-<?php echo strtolower($item['condition']); ?>">
                                                                <?php echo $item['condition']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <button class="btn btn-sm btn-outline-primary view-details" 
                                                                        data-type="inventory" 
                                                                        data-id="<?php echo $item['id']; ?>">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger delete-item" 
                                                                        data-type="inventory" 
                                                                        data-id="<?php echo $item['id']; ?>">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
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

                        <!-- Contacts Tab -->
                        <div id="contacts-tab" class="tab-content <?php echo $active_tab === 'contacts' ? 'active' : ''; ?>">
                            <div class="admin-content-card">
                                <div class="content-header">
                                    <h3 class="content-title">
                                        <i class="bi bi-envelope me-2"></i>
                                        Contact Messages
                                    </h3>
                                    <div class="filters">
                                        <div class="search-box">
                                            <input type="text" id="contactsSearch" class="form-control form-control-sm" placeholder="Search messages...">
                                            <i class="bi bi-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Subject</th>
                                                <th>Message</th>
                                                <th>Submitted At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($contacts) === 0): ?>
                                                <tr><td colspan="8" class="text-center py-4">No contact messages found.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($contacts as $contact): ?>
                                                    <tr data-contact-id="<?php echo $contact['id']; ?>">
                                                        <td>#<?php echo $contact['id']; ?></td>
                                                        <td><?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                                        <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                                        <td>
                                                            <span class="message-preview" data-bs-toggle="tooltip" data-bs-title="<?php echo htmlspecialchars($contact['message']); ?>">
                                                                <?php echo strlen($contact['message']) > 50 ? substr($contact['message'], 0, 50) . '...' : $contact['message']; ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M j, Y g:i A', strtotime($contact['submitted_at'])); ?></td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <button class="btn btn-sm btn-outline-primary view-details" 
                                                                        data-type="contact" 
                                                                        data-id="<?php echo $contact['id']; ?>">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger delete-item" 
                                                                        data-type="contact" 
                                                                        data-id="<?php echo $contact['id']; ?>">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
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
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="detailsModalLabel">
                        <i class="bi bi-info-circle me-2"></i>
                        Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailsModalBody">
                    <!-- Details content will be loaded here -->
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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
    <script src="js/admin.js"></script>
</body>
</html>