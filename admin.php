<?php
// config.php - Now using db_connect.php
require_once 'db/db_connect.php';
// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: login_register.php');
    exit();
}
// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - JB Lights & Sound</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="css/pages/admin.css">
</head>
<body>
    <div class="admin-shell">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="jb-logo">JB</div>
                <div class="brand-text">JB Lights & Sound</div>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-tab="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>DASHBOARD</span>
                </a>
                <a href="#" class="nav-item" data-tab="bookings">
                    <i class="fas fa-calendar-alt"></i>
                    <span>BOOKINGS</span>
                </a>
                <a href="#" class="nav-item" data-tab="users">
                    <i class="fas fa-users"></i>
                    <span>USERS</span>
                </a>
                <a href="#" class="nav-item" data-tab="inventory">
                    <i class="fas fa-box"></i>
                    <span>INVENTORY</span>
                </a>
                <a href="#" class="nav-item logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>LOGOUT</span>
                </a>
            </nav>
        </aside>

        <!-- Main Panel -->
        <main class="main-panel">
            <header class="main-header">
                <h1>ADMIN PANEL</h1>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" id="globalSearch" placeholder="Enter search keyword...">
                        <button class="search-btn" onclick="performSearch()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <button class="refresh-btn" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </header>

            <section class="content">
                <!-- Dashboard Tab -->
                <div id="dashboard-tab" class="tab-content active">
                    <div class="content-card">
                        <h3 class="content-title">Dashboard Overview</h3>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo count($bookings); ?></div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo count($users); ?></div>
                                <div class="stat-label">Total Users</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo count($inventory); ?></div>
                                <div class="stat-label">Inventory Items</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php echo count(array_filter($bookings, function($b) { return $b['status'] === 'Confirmed'; })); ?>
                                </div>
                                <div class="stat-label">Confirmed Bookings</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="content-card">
                        <h3 class="content-title">Recent Bookings</h3>
                        <div class="table-responsive">
                            <table class="bookings-table">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Customer Name</th>
                                        <th>Package</th>
                                        <th>Event Date</th>
                                        <th>Phone No.</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($recent_bookings) === 0): ?>
                                        <tr><td colspan="6" class="text-center">No recent bookings.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_bookings as $booking): ?>
                                            <tr>
                                                <td>#<?php echo $booking['id']; ?></td>
                                                <td><?php echo htmlspecialchars($booking['contact_name']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                                <td><?php echo date('m-d-Y', strtotime($booking['event_date'])); ?></td>
                                                <td><?php echo htmlspecialchars($booking['contact_phone']); ?></td>
                                                <td>
                                                    <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
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

                <!-- Bookings Tab -->
                <div id="bookings-tab" class="tab-content">
                    <div class="content-card">
                        <div class="content-header">
                            <h3 class="content-title">Bookings Management</h3>
                            <div class="filters">
                                <select id="statusFilter" class="form-select" onchange="filterBookings()">
                                    <option value="all">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="bookings-table">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Customer Name</th>
                                        <th>Package / Equipment</th>
                                        <th>Event Date</th>
                                        <th>Phone No.</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($bookings) === 0): ?>
                                        <tr><td colspan="7" class="text-center">No bookings found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($bookings as $booking): ?>
                                            <tr>
                                                <td>#<?php echo $booking['id']; ?></td>
                                                <td><?php echo htmlspecialchars($booking['contact_name']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['package']); ?></td>
                                                <td><?php echo date('m-d-Y', strtotime($booking['event_date'])); ?></td>
                                                <td><?php echo htmlspecialchars($booking['contact_phone']); ?></td>
                                                <td>
                                                    <span class="status-pill status-<?php echo strtolower($booking['status']); ?>">
                                                        <?php echo $booking['status']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <select class="form-select form-select-sm change-status" data-id="<?php echo $booking['id']; ?>">
                                                            <option value="">Change Status</option>
                                                            <option value="Pending" <?php echo $booking['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="Confirmed" <?php echo $booking['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirm</option>
                                                            <option value="Cancelled" <?php echo $booking['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancel</option>
                                                        </select>
                                                        <button class="btn-delete" onclick="deleteBooking(<?php echo $booking['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
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
                <div id="users-tab" class="tab-content">
                    <div class="content-card">
                        <h3 class="content-title">Users Management</h3>
                        <div class="table-responsive">
                            <table class="bookings-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Join Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($users) === 0): ?>
                                        <tr><td colspan="5" class="text-center">No users found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td>#<?php echo $user['id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                                <td><?php echo date('m-d-Y', strtotime($user['created_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Inventory Tab -->
                <div id="inventory-tab" class="tab-content">
                    <div class="content-card">
                        <h3 class="content-title">Inventory Management</h3>
                        <div class="table-responsive">
                            <table class="bookings-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Quantity</th>
                                        <th>Available</th>
                                        <th>Condition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($inventory) === 0): ?>
                                        <tr><td colspan="7" class="text-center">No inventory items found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($inventory as $item): ?>
                                            <tr>
                                                <td>#<?php echo $item['id']; ?></td>
                                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                                <td><?php echo htmlspecialchars($item['brand'] ?? 'N/A'); ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td><?php echo $item['available_quantity']; ?></td>
                                                <td>
                                                    <span class="status-pill status-<?php echo strtolower($item['item_condition']); ?>">
                                                        <?php echo $item['item_condition']; ?>
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
            </section>

            <<!-- Footer -->
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
        </main>
    </div>

        <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Admin JS -->
    <script src="js/common.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>