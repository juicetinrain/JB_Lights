<?php
// admin.php - Enhanced with full inventory CRUD and better details
require_once 'db/db_connect.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: login_register.php');
    exit();
}

// Handle all actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // Existing status updates
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

    // Handle delete user
    if ($_POST['action'] === 'delete_user') {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND user_type != 'admin'");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Handle delete contact
    if ($_POST['action'] === 'delete_contact') {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // INVENTORY CRUD OPERATIONS
    if ($_POST['action'] === 'add_inventory') {
        $item_name = trim($_POST['item_name']);
        $category = trim($_POST['category']);
        $brand = trim($_POST['brand']);
        $quantity = intval($_POST['quantity']);
        $condition = trim($_POST['condition']);
        
        if ($item_name && $category && $quantity > 0) {
            $stmt = $conn->prepare("INSERT INTO inventory (item_name, category, brand, quantity, available_quantity, condition) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiis", $item_name, $category, $brand, $quantity, $quantity, $condition);
            $stmt->execute();
            $stmt->close();
        }
    }

    if ($_POST['action'] === 'update_inventory') {
        $id = intval($_POST['id']);
        $item_name = trim($_POST['item_name']);
        $category = trim($_POST['category']);
        $brand = trim($_POST['brand']);
        $quantity = intval($_POST['quantity']);
        $available_quantity = intval($_POST['available_quantity']);
        $condition = trim($_POST['condition']);
        
        if ($id > 0 && $item_name && $category && $quantity > 0) {
            $stmt = $conn->prepare("UPDATE inventory SET item_name = ?, category = ?, brand = ?, quantity = ?, available_quantity = ?, condition = ? WHERE id = ?");
            $stmt->bind_param("sssiisi", $item_name, $category, $brand, $quantity, $available_quantity, $condition, $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    if ($_POST['action'] === 'delete_inventory') {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    if ($_POST['action'] === 'return_equipment') {
        $id = intval($_POST['id']);
        $returned_quantity = intval($_POST['returned_quantity']);
        
        if ($id > 0 && $returned_quantity > 0) {
            // Get current inventory
            $stmt = $conn->prepare("SELECT quantity, available_quantity FROM inventory WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $item = $result->fetch_assoc();
            $stmt->close();
            
            if ($item) {
                $new_available = min($item['available_quantity'] + $returned_quantity, $item['quantity']);
                $stmt = $conn->prepare("UPDATE inventory SET available_quantity = ? WHERE id = ?");
                $stmt->bind_param("ii", $new_available, $id);
                $stmt->execute();
                $stmt->close();
            }
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

// Get all bookings with user information
$result = $conn->query("
    SELECT r.*, u.name as user_name, u.phone as user_phone 
    FROM reservations r 
    LEFT JOIN users u ON r.contact_email = u.email 
    ORDER BY r.created_at DESC
");
$bookings = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $result->free();
}

// Get recent bookings for dashboard
$recent_result = $conn->query("
    SELECT r.*, u.name as user_name 
    FROM reservations r 
    LEFT JOIN users u ON r.contact_email = u.email 
    ORDER BY r.created_at DESC LIMIT 5
");
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

// Inventory statistics
$total_items = array_sum(array_column($inventory, 'quantity'));
$available_items = array_sum(array_column($inventory, 'available_quantity'));
$in_use_items = $total_items - $available_items;
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
                        <!-- Update the Dashboard Tab in admin.php -->
<div id="dashboard-tab" class="tab-content <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
    <!-- Enhanced Quick Stats - Matching Inventory Tab Style -->
    <div class="row mb-4">
        <!-- Bookings Stats -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $pending_bookings; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon bg-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $confirmed_bookings; ?></div>
                    <div class="stat-label">Confirmed</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
        
        <!-- Users & Inventory Stats -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon bg-info">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $total_inventory; ?></div>
                    <div class="stat-label">Inventory Items</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Additional Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $total_contacts; ?></div>
                    <div class="stat-label">Contact Messages</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="bi bi-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $total_items; ?></div>
                    <div class="stat-label">Total Equipment</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $available_items; ?></div>
                    <div class="stat-label">Available Equipment</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="admin-stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $in_use_items; ?></div>
                    <div class="stat-label">Equipment In Use</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown Chart -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="admin-content-card">
                <div class="content-header">
                    <h3 class="content-title">
                        <i class="bi bi-bar-chart me-2"></i>
                        Bookings Overview
                    </h3>
                </div>
                <div class="chart-container">
                    <div class="status-chart">
                        <div class="chart-bar pending" style="width: <?php echo $total_bookings > 0 ? ($pending_bookings / $total_bookings) * 100 : 0; ?>%">
                            <span class="chart-label">Pending: <?php echo $pending_bookings; ?></span>
                        </div>
                        <div class="chart-bar confirmed" style="width: <?php echo $total_bookings > 0 ? ($confirmed_bookings / $total_bookings) * 100 : 0; ?>%">
                            <span class="chart-label">Confirmed: <?php echo $confirmed_bookings; ?></span>
                        </div>
                        <div class="chart-bar cancelled" style="width: <?php echo $total_bookings > 0 ? ($cancelled_bookings / $total_bookings) * 100 : 0; ?>%">
                            <span class="chart-label">Cancelled: <?php echo $cancelled_bookings; ?></span>
                        </div>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color pending"></span>
                            <span>Pending (<?php echo $total_bookings > 0 ? number_format(($pending_bookings / $total_bookings) * 100, 1) : 0; ?>%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color confirmed"></span>
                            <span>Confirmed (<?php echo $total_bookings > 0 ? number_format(($confirmed_bookings / $total_bookings) * 100, 1) : 0; ?>%)</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color cancelled"></span>
                            <span>Cancelled (<?php echo $total_bookings > 0 ? number_format(($cancelled_bookings / $total_bookings) * 100, 1) : 0; ?>%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-lg-4 mb-4">
            <div class="admin-content-card h-100">
                <h3 class="content-title">
                    <i class="bi bi-speedometer2 me-2"></i>
                    System Status
                </h3>
                <div class="system-status">
                    <div class="status-item">
                        <div class="status-indicator online"></div>
                        <div class="status-info">
                            <div class="status-name">Database</div>
                            <div class="status-desc">Connected and running</div>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-indicator online"></div>
                        <div class="status-info">
                            <div class="status-name">Web Server</div>
                            <div class="status-desc">Operational</div>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-indicator <?php echo $pending_bookings > 5 ? 'warning' : 'online'; ?>"></div>
                        <div class="status-info">
                            <div class="status-name">Pending Bookings</div>
                            <div class="status-desc"><?php echo $pending_bookings; ?> require attention</div>
                        </div>
                    </div>
                    <div class="status-item">
                        <div class="status-indicator <?php echo $available_items < 10 ? 'warning' : 'online'; ?>"></div>
                        <div class="status-info">
                            <div class="status-name">Equipment Availability</div>
                            <div class="status-desc"><?php echo $available_items; ?> items available</div>
                        </div>
                    </div>
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
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_bookings) === 0): ?>
                                <tr><td colspan="7" class="text-center py-4">No recent bookings found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recent_bookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['id']; ?></td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-name"><?php echo htmlspecialchars($booking['contact_name']); ?></div>
                                                <div class="customer-email"><?php echo htmlspecialchars($booking['contact_email']); ?></div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></td>
                                        <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                                <i class="bi bi-circle-fill me-1"></i>
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
                    <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#inventoryModal" data-action="add">
                        <i class="bi bi-plus-circle me-2"></i>Add Equipment
                    </button>
                    <button class="btn btn-info w-100 mb-2">
                        <i class="bi bi-download me-2"></i>Export Reports
                    </button>
                </div>
            </div>
        </div>
    </div>
</div><!-- Bookings Tab - Updated with better search and status design -->
<div id="bookings-tab" class="tab-content <?php echo $active_tab === 'bookings' ? 'active' : ''; ?>">
    <div class="admin-content-card">
        <div class="content-header">
            <h3 class="content-title">
                <i class="bi bi-calendar-check me-2"></i>
                Bookings Management
            </h3>
            <div class="filters">
                <!-- Improved Search Box -->
                <div class="search-box" style="width: 300px;">
                    <input type="text" id="bookingsSearch" class="form-control form-control-sm" placeholder="Search bookings by name, email, or package...">
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
        
        <!-- Bookings Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card stat-card-sm">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $total_bookings; ?></div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card stat-card-sm">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $pending_bookings; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card stat-card-sm">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $confirmed_bookings; ?></div>
                        <div class="stat-label">Confirmed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card stat-card-sm">
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
                            <tr data-booking-id="<?php echo $booking['id']; ?>" class="booking-row status-<?php echo strtolower($booking['status']); ?>">
                                <td>
                                    <div class="booking-id">#<?php echo $booking['id']; ?></div>
                                    <small class="text-muted"><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name"><?php echo htmlspecialchars($booking['contact_name']); ?></div>
                                        <?php if (isset($booking['user_name']) && $booking['user_name']): ?>
                                            <small class="text-muted">User: <?php echo htmlspecialchars($booking['user_name']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($booking['contact_email']); ?></td>
                                <td><?php echo htmlspecialchars($booking['contact_phone']); ?></td>
                                <td>
                                    <span class="event-type"><?php echo htmlspecialchars($booking['event_type']); ?></span>
                                </td>
                                <td>
                                    <div class="event-date"><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></div>
                                    <?php if (isset($booking['start_time']) && $booking['start_time']): ?>
                                        <small class="text-muted"><?php echo date('g:i A', strtotime($booking['start_time'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                <td>
                                    <div class="amount">₱<?php echo number_format($booking['total_amount'], 2); ?></div>
                                    <?php if ($booking['payment_method'] === 'gcash' && $booking['downpayment_amount'] > 0): ?>
                                        <small class="text-success">DP: ₱<?php echo number_format($booking['downpayment_amount'], 2); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                        <i class="bi bi-circle-fill me-1"></i>
                                        <?php echo $booking['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary view-details" 
                                                data-type="booking" 
                                                data-id="<?php echo $booking['id']; ?>"
                                                title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <select class="form-select form-select-sm change-status" data-id="<?php echo $booking['id']; ?>" title="Change Status">
                                            <option value="">Change</option>
                                            <option value="Pending" <?php echo $booking['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Confirmed" <?php echo $booking['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirm</option>
                                            <option value="Cancelled" <?php echo $booking['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancel</option>
                                        </select>
                                        <button class="btn btn-sm btn-danger delete-item" 
                                                data-type="booking" 
                                                data-id="<?php echo $booking['id']; ?>"
                                                title="Delete Booking">
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
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inventoryModal" data-action="add">
                    <i class="bi bi-plus-circle me-1"></i> Add Item
                </button>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $total_inventory; ?></div>
                        <div class="stat-label">Total Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $available_items; ?></div>
                        <div class="stat-label">Available Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $in_use_items; ?></div>
                        <div class="stat-label">In Use</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo $total_items; ?></div>
                        <div class="stat-label">Total Quantity</div>
                    </div>
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
                        <th>Total Qty</th>
                        <th>Available</th>
                        <th>In Use</th>
                        <th>Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($inventory) === 0): ?>
                        <tr><td colspan="9" class="text-center py-4">No inventory items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($inventory as $item): ?>
                            <tr data-item-id="<?php echo $item['id']; ?>">
                                <td>#<?php echo $item['id']; ?></td>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                <td><?php echo htmlspecialchars($item['brand'] ?? 'N/A'); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>
                                    <span class="<?php echo $item['available_quantity'] == 0 ? 'text-danger' : 'text-success'; ?>">
                                        <?php echo $item['available_quantity']; ?>
                                    </span>
                                </td>
                                <td><?php echo $item['quantity'] - $item['available_quantity']; ?></td>
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
                                        <button class="btn btn-sm btn-outline-warning edit-inventory" 
                                                data-id="<?php echo $item['id']; ?>"
                                                data-item='<?php echo json_encode($item); ?>'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info return-equipment" 
                                                data-id="<?php echo $item['id']; ?>"
                                                data-item='<?php echo json_encode($item); ?>'>
                                            <i class="bi bi-arrow-return-left"></i>
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
    
    <!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="inventoryModalLabel">
                    <i class="bi bi-box-seam me-2"></i>
                    Add Inventory Item
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inventoryForm" method="POST">
                <input type="hidden" name="action" id="inventoryAction" value="add_inventory">
                <input type="hidden" name="id" id="inventoryId" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Name *</label>
                            <input type="text" class="form-control" name="item_name" id="itemName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select class="form-select" name="category" id="itemCategory" required>
                                <option value="">Select Category</option>
                                <option value="Sound">Sound</option>
                                <option value="Lighting">Lighting</option>
                                <option value="Stage">Stage & Trusses</option>
                                <option value="Video">Video Walls</option>
                                <option value="Power">Power Equipment</option>
                                <option value="Accessories">Accessories</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand" id="itemBrand" placeholder="e.g., JBL, Behringer, ADJ">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Condition *</label>
                            <select class="form-select" name="condition" id="itemCondition" required>
                                <option value="excellent">Excellent</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="needs_repair">Needs Repair</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Quantity *</label>
                            <input type="number" class="form-control" name="quantity" id="itemQuantity" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Quantity *</label>
                            <input type="number" class="form-control" name="available_quantity" id="itemAvailable" min="0" required>
                            <small class="text-muted">For new items, set equal to total quantity</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Return Equipment Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="returnModalLabel">
                    <i class="bi bi-arrow-return-left me-2"></i>
                    Return Equipment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="returnForm" method="POST">
                <input type="hidden" name="action" value="return_equipment">
                <input type="hidden" name="id" id="returnItemId" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <input type="text" class="form-control" id="returnItemName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Currently In Use</label>
                        <input type="text" class="form-control" id="currentlyInUse" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Return *</label>
                        <input type="number" class="form-control" name="returned_quantity" id="returnQuantity" min="1" required>
                        <small class="text-muted">Enter the number of items being returned</small>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Return Equipment</button>
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
    <script src="js/admin.js"></script>
</body>
</html>