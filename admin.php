<?php
// admin.php - Complete Admin Panel
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
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
        } elseif ($_POST['action'] === 'delete_booking') {
            $id = intval($_POST['id']);
            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// Get filter values
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

// Build query
$query = "SELECT * FROM reservations WHERE 1=1";
if ($search) {
    $query .= " AND (contact_name LIKE '%$search%' OR contact_email LIKE '%$search%' OR contact_phone LIKE '%$search%')";
}
if ($filter && $filter !== 'all') {
    $query .= " AND status = '$filter'";
}
$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
$bookings = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $result->free();
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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                                                    <span class="status-pill status-<?php echo strtolower($item['condition']); ?>">
                                                        <?php echo $item['condition']; ?>
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

            <footer class="main-footer">
                <div>&copy; <?php echo date('Y'); ?> JB Lights & Sound - Admin Dashboard</div>
            </footer>
        </main>
    </div>

    <script src="js/admin.js"></script>
    <script>
        // Tab navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                if (this.classList.contains('logout')) {
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = 'login_register.php';
                    }
                    return;
                }

                // Update active nav item
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');

                // Show corresponding tab
                const tabName = this.dataset.tab;
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                document.getElementById(tabName + '-tab').classList.add('active');
            });
        });

        // Search function
        function performSearch() {
            const searchTerm = document.getElementById('globalSearch').value;
            const url = new URL(window.location);
            url.searchParams.set('search', searchTerm);
            window.location.href = url.toString();
        }

        // Filter bookings
        function filterBookings() {
            const filter = document.getElementById('statusFilter').value;
            const url = new URL(window.location);
            url.searchParams.set('filter', filter);
            window.location.href = url.toString();
        }

        // Delete booking
        function deleteBooking(id) {
            if (confirm('Are you sure you want to delete booking #' + id + '?')) {
                const formData = new FormData();
                formData.append('action', 'delete_booking');
                formData.append('id', id);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(() => {
                    location.reload();
                });
            }
        }

        // Apply existing filters on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const search = urlParams.get('search');
            const filter = urlParams.get('filter');

            if (search) {
                document.getElementById('globalSearch').value = search;
            }
            if (filter) {
                document.getElementById('statusFilter').value = filter;
            }
        });
    </script>
</body>
</html>