<?php
// admin_details.php - Updated with enhanced booking details
require_once 'db/db_connect.php';

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    exit('Invalid ID');
}

// Function to check if column exists in table
function columnExists($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    return $result->num_rows > 0;
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
    case 'cancellation':
        showCancellationDetails($id);
        break;
    default:
        http_response_code(400);
        exit('Invalid type');
}

function showBookingDetails($id) {
    global $conn;
    
    // Build query based on available columns
    $query = "SELECT r.*";
    
    // Check if users table has name and phone columns
    if (columnExists($conn, 'users', 'name') && columnExists($conn, 'users', 'phone')) {
        $query .= ", u.name as user_name, u.phone as user_phone";
    }
    
    $query .= " FROM reservations r";
    
    if (columnExists($conn, 'users', 'name') && columnExists($conn, 'users', 'phone')) {
        $query .= " LEFT JOIN users u ON r.contact_email = u.email";
    }
    
    $query .= " WHERE r.id = ?";
    
    $stmt = $conn->prepare($query);
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
        <h6><i class="bi bi-info-circle me-2"></i>Booking Information</h6>
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
        <h6><i class="bi bi-calendar-event me-2"></i>Event Details</h6>
        <div class="detail-item">
            <span class="detail-label">Event Type:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['event_type']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Event Date:</span>
            <span class="detail-value"><?php echo date('M j, Y', strtotime($booking['event_date'])); ?></span>
        </div>
        
        <?php if (isset($booking['start_time']) && $booking['start_time']): ?>
        <div class="detail-item">
            <span class="detail-label">Start Time:</span>
            <span class="detail-value"><?php echo date('g:i A', strtotime($booking['start_time'])); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($booking['end_time']) && $booking['end_time']): ?>
        <div class="detail-item">
            <span class="detail-label">End Time:</span>
            <span class="detail-value"><?php echo date('g:i A', strtotime($booking['end_time'])); ?></span>
        </div>
        <?php endif; ?>
        
        <div class="detail-item">
            <span class="detail-label">Package:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['package']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Event Address:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['event_address']); ?></span>
        </div>
        
        <?php if (isset($booking['event_location']) && $booking['event_location']): ?>
        <div class="detail-item">
            <span class="detail-label">Location Coordinates:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['event_location']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($booking['landmark_notes']) && $booking['landmark_notes']): ?>
        <div class="detail-item">
            <span class="detail-label">Landmark Notes:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['landmark_notes']); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="details-section">
        <h6><i class="bi bi-person-lines-fill me-2"></i>Contact Information</h6>
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
        
        <?php if (isset($booking['preferred_contact']) && $booking['preferred_contact']): ?>
        <div class="detail-item">
            <span class="detail-label">Preferred Contact:</span>
            <span class="detail-value text-capitalize"><?php echo str_replace('_', ' ', $booking['preferred_contact']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($booking['social_media_handle']) && $booking['social_media_handle']): ?>
        <div class="detail-item">
            <span class="detail-label">Social Media Handle:</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['social_media_handle']); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="details-section">
        <h6><i class="bi bi-credit-card me-2"></i>Payment Information</h6>
        <div class="detail-item">
            <span class="detail-label">Total Amount:</span>
            <span class="detail-value">₱<?php echo number_format($booking['total_amount'], 2); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Payment Method:</span>
            <span class="detail-value text-uppercase"><?php echo $booking['payment_method']; ?></span>
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
        <h6><i class="bi bi-person-badge me-2"></i>User Information</h6>
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

    <?php if (isset($user['address']) && $user['address']): ?>
    <div class="details-section">
        <h6><i class="bi bi-geo-alt me-2"></i>Address Information</h6>
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
        <h6><i class="bi bi-box-seam me-2"></i>Item Information</h6>
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
        <h6><i class="bi bi-clipboard-data me-2"></i>Quantity Information</h6>
        <div class="detail-item">
            <span class="detail-label">Total Quantity:</span>
            <span class="detail-value"><?php echo $item['quantity']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Available Quantity:</span>
            <span class="detail-value <?php echo $item['available_quantity'] == 0 ? 'text-danger' : 'text-success'; ?>">
                <?php echo $item['available_quantity']; ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Currently in Use:</span>
            <span class="detail-value <?php echo ($item['quantity'] - $item['available_quantity']) > 0 ? 'text-warning' : 'text-secondary'; ?>">
                <?php echo $item['quantity'] - $item['available_quantity']; ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Availability Status:</span>
            <span class="detail-value">
                <?php if ($item['available_quantity'] == 0): ?>
                    <span class="badge bg-danger">Out of Stock</span>
                <?php elseif ($item['available_quantity'] < $item['quantity'] * 0.3): ?>
                    <span class="badge bg-warning">Low Stock</span>
                <?php else: ?>
                    <span class="badge bg-success">In Stock</span>
                <?php endif; ?>
            </span>
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
        <h6><i class="bi bi-person-lines-fill me-2"></i>Contact Information</h6>
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
        <h6><i class="bi bi-chat-left-text me-2"></i>Message Details</h6>
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

function showCancellationDetails($id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT cr.*, r.*, u.name as user_name, u.email as user_email, u.phone as user_phone
        FROM cancellation_requests cr 
        JOIN reservations r ON cr.reservation_id = r.id 
        LEFT JOIN users u ON cr.user_id = u.id 
        WHERE cr.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cancellation = $result->fetch_assoc();
    
    if (!$cancellation) {
        echo '<div class="alert alert-danger">Cancellation request not found.</div>';
        return;
    }
    ?>
    <div class="details-section">
        <h6><i class="bi bi-info-circle me-2"></i>Cancellation Request Information</h6>
        <div class="detail-item">
            <span class="detail-label">Request ID:</span>
            <span class="detail-value">#<?php echo $cancellation['id']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Status:</span>
            <span class="detail-value status-pill status-<?php echo $cancellation['status']; ?>">
                <?php echo ucfirst($cancellation['status']); ?>
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Requested:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($cancellation['created_at'])); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Last Updated:</span>
            <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($cancellation['updated_at'])); ?></span>
        </div>
    </div>

    <div class="details-section">
        <h6><i class="bi bi-calendar-event me-2"></i>Booking Information</h6>
        <div class="detail-item">
            <span class="detail-label">Booking ID:</span>
            <span class="detail-value">#<?php echo $cancellation['reservation_id']; ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Event Type:</span>
            <span class="detail-value"><?php echo htmlspecialchars($cancellation['event_type']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Event Date:</span>
            <span class="detail-value"><?php echo date('M j, Y', strtotime($cancellation['event_date'])); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Package:</span>
            <span class="detail-value"><?php echo htmlspecialchars($cancellation['package']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Total Amount:</span>
            <span class="detail-value">₱<?php echo number_format($cancellation['total_amount'], 2); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Booking Status:</span>
            <span class="detail-value status-pill status-<?php echo strtolower($cancellation['booking_status']); ?>">
                <?php echo $cancellation['booking_status']; ?>
            </span>
        </div>
    </div>

    <div class="details-section">
        <h6><i class="bi bi-person-lines-fill me-2"></i>Customer Information</h6>
        <div class="detail-item">
            <span class="detail-label">Customer Name:</span>
            <span class="detail-value"><?php echo htmlspecialchars($cancellation['contact_name']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email:</span>
            <span class="detail-value"><?php echo htmlspecialchars($cancellation['contact_email']); ?></span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Phone:</span>
            <span class="detail-value"><?php echo htmlspecialchars($cancellation['contact_phone']); ?></span>
        </div>
    </div>

    <div class="details-section">
        <h6><i class="bi bi-chat-left-text me-2"></i>Cancellation Details</h6>
        <div class="detail-item">
            <span class="detail-label">Reason:</span>
            <span class="detail-value"><?php echo nl2br(htmlspecialchars($cancellation['reason'])); ?></span>
        </div>
        <?php if ($cancellation['admin_notes']): ?>
        <div class="detail-item">
            <span class="detail-label">Admin Notes:</span>
            <span class="detail-value"><?php echo nl2br(htmlspecialchars($cancellation['admin_notes'])); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

?>