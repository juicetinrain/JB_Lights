<?php
// profile_booking_details.php - Booking details for user profile
require_once 'db/db_connect.php';

if (!isLoggedIn()) {
    http_response_code(401);
    exit('Unauthorized');
}

$id = intval($_GET['id'] ?? 0);
$user = getCurrentUser();

if ($id <= 0) {
    http_response_code(400);
    exit('Invalid ID');
}

// Get booking details - ensure user can only see their own bookings
$stmt = $conn->prepare("
    SELECT r.*, 
           cr.status as cancellation_status,
           cr.reason as cancellation_reason,
           cr.created_at as cancellation_requested_at,
           cr.admin_notes as cancellation_admin_notes
    FROM reservations r 
    LEFT JOIN cancellation_requests cr ON r.id = cr.reservation_id AND cr.user_id = ?
    WHERE r.id = ? AND r.contact_email = ?
");
$user_id = $_SESSION['user_id'];
$stmt->bind_param("iis", $user_id, $id, $user['email']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo '<div class="alert alert-danger">Booking not found or you don\'t have permission to view this booking.</div>';
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

<?php if ($booking['cancellation_status']): ?>
<div class="details-section">
    <h6><i class="bi bi-x-circle me-2"></i>Cancellation Request</h6>
    <div class="detail-item">
        <span class="detail-label">Status:</span>
        <span class="detail-value status-pill status-<?php echo $booking['cancellation_status']; ?>">
            <?php echo ucfirst($booking['cancellation_status']); ?>
        </span>
    </div>
    <div class="detail-item">
        <span class="detail-label">Reason:</span>
        <span class="detail-value"><?php echo htmlspecialchars($booking['cancellation_reason']); ?></span>
    </div>
    <div class="detail-item">
        <span class="detail-label">Requested:</span>
        <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($booking['cancellation_requested_at'])); ?></span>
    </div>
    <?php if ($booking['cancellation_admin_notes']): ?>
    <div class="detail-item">
        <span class="detail-label">Admin Notes:</span>
        <span class="detail-value"><?php echo htmlspecialchars($booking['cancellation_admin_notes']); ?></span>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>