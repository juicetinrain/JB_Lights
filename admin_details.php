<?php
// admin_details.php - Handle details view for admin panel
require_once 'db/db_connect.php';

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    exit('Access Denied');
}

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    exit('Invalid ID');
}

switch ($type) {
    case 'booking':
        showBookingDetails($id);
        break;
    case 'user':
        showUserDetails($id);
        break;
    case 'inventory':
        showInventoryDetails($id);
        break;
    case 'contact':
        showContactDetails($id);
        break;
    default:
        http_response_code(400);
        exit('Invalid type');
}

function showBookingDetails($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    
    if (!$booking) {
        echo '<div class="alert alert-danger">Booking not found.</div>';
        return;
    }
    ?>
    <div class="details-section">
        <h6>Booking Information</h6>
        <div class="detail-item">
            <span class="detail-label">Booking ID:</span>
            <span class="detail-value">#<?php echo $booking['id']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Status:</span>
            <span class="detail-value status-pill status-<?php echo strtolower($booking['status']); ?>">
                <?php echo $booking['status']; ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Created:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($booking['created_at'])); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Last Updated:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($booking['updated_at'])); ?></span>
        </div>
    </div>

    <div class="details-section">
        <h6>Event Details</h6>
        <div class="detail-item">
            <span class="detail-label">Event Type:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['event_type']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Event Date:</span>
            <span class="detail-value"><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Package:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['package']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Event Address:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['event_address']); ?></span>
        </div>
        <?php if ($booking['event_location']): ?>
        <div class="detail-item">
            <span class="detail-label">Location Coordinates:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['event_location']); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="details-section">
        <h6>Contact Information</h6>
        <div class="detail-item">
            <span class="detail-label">Contact Name:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['contact_name']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['contact_email']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Phone:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['contact_phone']); ?></span>
        </div>
    </div>

    <div class="details-section">
        <h6>Payment Information</h6>
        <div class="detail-item">
            <span class="detail-label">Total Amount:</span>
            <span class="detail-value">₱<?php echo number_format($booking['total_amount'], 2); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Payment Method:</span>
            <span class="detail-value"><?php echo strtoupper($booking['payment_method']); ?></span>
        </div>
        <?php if ($booking['payment_method'] === 'gcash' && $booking['downpayment_amount'] > 0): ?>
        <div class="detail-item">
            <span class="detail-label">Downpayment Amount:</span>
            <span class="detail-value">₱<?php echo number_format($booking['downpayment_amount'], 2); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Remaining Balance:</span>
            <span class="detail-value">₱<?php echo number_format($booking['total_amount'] - $booking['downpayment_amount'], 2); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

function showUserDetails($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        echo '<div class="alert alert-danger">User not found.</div>';
        return;
    }
    ?>
    <div class="details-section">
        <h6>User Information</h6>
        <div class="detail-item">
            <span class="detail-label">User ID:</span>
            <span class="detail-value">#<?php echo $user['id']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Name:</span>
            <span class="detail-value"><?php echo htmlspecialchars($user['name']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email:</span>
            <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Phone:</span>
            <span class="detail-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">User Type:</span>
            <span class="detail-value badge <?php echo $user['user_type'] === 'admin' ? 'bg-primary' : 'bg-secondary'; ?>">
                <?php echo ucfirst($user['user_type']); ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Member Since:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($user['created_at'])); ?></span>
        </div>
    </div>

    <?php if ($user['address']): ?>
    <div class="details-section">
        <h6>Address Information</h6>
        <div class="detail-item">
            <span class="detail-label">Address:</span>
            <span class="detail-value"><?php echo htmlspecialchars($user['address']); ?></span>
        </div>
    </div>
    <?php endif; ?>
    <?php
}

function showInventoryDetails($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    
    if (!$item) {
        echo '<div class="alert alert-danger">Inventory item not found.</div>';
        return;
    }
    ?>
    <div class="details-section">
        <h6>Item Information</h6>
        <div class="detail-item">
            <span class="detail-label">Item ID:</span>
            <span class="detail-value">#<?php echo $item['id']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Item Name:</span>
            <span class="detail-value"><?php echo htmlspecialchars($item['item_name']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Category:</span>
            <span class="detail-value"><?php echo htmlspecialchars($item['category']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Brand:</span>
            <span class="detail-value"><?php echo htmlspecialchars($item['brand'] ?? 'N/A'); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Condition:</span>
            <span class="detail-value status-pill status-<?php echo strtolower($item['condition']); ?>">
                <?php echo $item['condition']; ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Created:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($item['created_at'])); ?></span>
        </div>
    </div>

    <div class="details-section">
        <h6>Quantity Information</h6>
        <div class="detail-item">
            <span class="detail-label">Total Quantity:</span>
            <span class="detail-value"><?php echo $item['quantity']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Available Quantity:</span>
            <span class="detail-value"><?php echo $item['available_quantity']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Currently in Use:</span>
            <span class="detail-value"><?php echo $item['quantity'] - $item['available_quantity']; ?></span>
        </div>
    </div>
    <?php
}

function showContactDetails($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM contact_submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contact = $result->fetch_assoc();
    
    if (!$contact) {
        echo '<div class="alert alert-danger">Contact message not found.</div>';
        return;
    }
    ?>
    <div class="details-section">
        <h6>Contact Information</h6>
        <div class="detail-item">
            <span class="detail-label">Message ID:</span>
            <span class="detail-value">#<?php echo $contact['id']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Name:</span>
            <span class="detail-value"><?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email:</span>
            <span class="detail-value"><?php echo htmlspecialchars($contact['email']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Phone:</span>
            <span class="detail-value"><?php echo htmlspecialchars($contact['phone']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">IP Address:</span>
            <span class="detail-value"><?php echo htmlspecialchars($contact['ip_address'] ?? 'N/A'); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Submitted:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($contact['submitted_at'])); ?></span>
        </div>
    </div>

    <div class="details-section">
        <h6>Message Details</h6>
        <div class="detail-item">
            <span class="detail-label">Subject:</span>
            <span class="detail-value"><?php echo htmlspecialchars($contact['subject']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Message:</span>
            <span class="detail-value"><?php echo nl2br(htmlspecialchars($contact['message'])); ?></span>
        </div>
    </div>
    <?php
}
?>