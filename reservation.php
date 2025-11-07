<?php
// reservation.php
// Simple multi-step reservation (no availability check).
// DB config (localhost)
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
  die("DB Connection failed: " . $conn->connect_error);
}

$inserted = false;
$error = '';
$submission = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_submit'])) {
  // Basic server-side sanitize/trim
  $package = trim($_POST['package'] ?? '');
  $event_type = trim($_POST['event_type'] ?? '');
  $event_date = trim($_POST['event_date'] ?? '');
  $event_time = trim($_POST['event_time'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $contact_name = trim($_POST['contact_name'] ?? '');
  $facebook_account = $_POST['facebook_account'];
  $contact_phone = trim($_POST['contact_phone'] ?? '');
  $payment = trim($_POST['payment'] ?? '');
  $notes = trim($_POST['notes'] ?? '');

  // Validate required fields
  if (!$package || !$event_date || !$address || !$contact_name || !$contact_phone || !$payment) {
    $error = "Please complete all required fields.";
  } else {
    // Prevent past dates server-side
    $today = new DateTime();
    $selected = DateTime::createFromFormat('Y-m-d', $event_date);
    if (!$selected || $selected <= $today) {
      $error = "Event date must be after today.";
    } else {
      $stmt = $conn->prepare("INSERT INTO reservations (package, event_type, event_date, event_time, address, contact_name, facebook_account, contact_phone, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssssss", $package, $event_type, $event_date, $event_time, $address, $contact_name, $facebook_account, $contact_phone, $payment, $notes);
      if ($stmt->execute()) {
        $inserted = true;
        $id = $stmt->insert_id;
        $submission = [
          'id' => $id,
          'package' => $package,
          'event_type' => $event_type,
          'event_date' => $event_date,
          'event_time' => $event_time,
          'address' => $address,
          'contact_name' => $contact_name,
          'contact_phone' => $contact_phone,
          'facebook_account' => $facebook_account,
          'payment' => $payment,
          'notes' => $notes
        ];
      } else {
        $error = "Database error: " . $stmt->error;
      }
      $stmt->close();
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Reserve — JB Lights & Sound</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="reservation.css">
</head>
<body>
  <!-- HEADER (site style) -->
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.html">
        <img src="jb-logo.png" alt="JB Logo" class="brand-logo">
        <span class="brand-text">JB Lights & Sound</span>
      </a>
      <nav class="main-nav d-none d-md-block">
        <a href="index.html">HOME</a>
        <a href="index.html#rentals">RENTALS</a>
        <a href="index.html#packages">PACKAGES</a>
      </nav>
      <button class="nav-toggle d-md-none" onclick="location.href='index.html'"><i class="bi bi-list"></i></button>
    </div>
  </header>

  <main class="main-content">
    <div class="container">
      <!-- Progress bar -->
      <div class="progress-shell">
        <div class="progress-track"><div id="progressFill" class="progress-fill" style="width:0%"></div></div>
        <div class="progress-labels">
          <div>Package</div>
          <div>Date</div>
          <div>Details</div>
          <div>Payment</div>
        </div>
      </div>

      <?php if ($inserted): ?>
        <!-- Success panel -->
        <div class="step-card success-card">
          <h3 class="title">Reservation Confirmed</h3>
          <p class="desc">Thanks! Your reservation has been saved. We'll contact you to confirm details.</p>
          <div class="submission-details text-start">
            <div><strong>ID:</strong> <?php echo htmlspecialchars($submission['id']); ?></div>
            <div><strong>Package:</strong> <?php echo htmlspecialchars($submission['package']); ?></div>
            <div><strong>Event:</strong> <?php echo htmlspecialchars($submission['event_type']); ?> — <?php echo htmlspecialchars($submission['event_date']); ?> <?php echo htmlspecialchars($submission['event_time']); ?></div>
            <div><strong>Contact:</strong> <?php echo htmlspecialchars($submission['contact_name']); ?> • <?php echo htmlspecialchars($submission['contact_phone']); ?></div>
            <div><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($submission['address'])); ?></div>
            <div><strong>Payment:</strong> <?php echo htmlspecialchars($submission['payment']); ?></div>
          </div>
          <div class="actions mt-4">
            <a href="reservation.php" class="btn jb-btn">Create another</a>
            <a href="index.html" class="btn btn-outline-secondary ms-2">Return Home</a>
          </div>
        </div>

      <?php else: ?>
        <?php if ($error): ?>
          <div class="alert alert-danger rounded shadow-sm"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Reservation card -->
        <div class="step-card" id="reservationCard">
          <form id="reservationForm" method="POST" novalidate>
            <!-- Step 1: Package -->
            <section id="step-1" class="step active">
              <h2 class="title">Choose Package</h2>
              <p class="desc">Select one of the packages below.</p>

              <div class="row gx-3 mb-3">
                <div class="col-6 col-md-3">
                  <div class="pkg-card" data-value="Basic (₱5,000)" onclick="selectPackage(this)">
                    <div class="pkg-head">BASIC <span class="pkg-price">₱5,000</span></div>
                    <div class="pkg-body">Small events — basic sound & lights</div>
                    <div class="pkg-action"><button type="button" class="btn btn-sm btn-outline-primary" onclick="selectPackage(this.parentElement.parentElement)">Select</button></div>
                  </div>
                </div>

                <div class="col-6 col-md-3">
                  <div class="pkg-card" data-value="Upgraded (₱6,000)" onclick="selectPackage(this)">
                    <div class="pkg-head">UPGRADED <span class="pkg-price">₱6,000</span></div>
                    <div class="pkg-body">Weddings/corporate — enhanced setup</div>
                    <div class="pkg-action"><button type="button" class="btn btn-sm btn-outline-primary" onclick="selectPackage(this.parentElement.parentElement)">Select</button></div>
                  </div>
                </div>

                <div class="col-6 col-md-3">
                  <div class="pkg-card" data-value="Pro (₱7,000)" onclick="selectPackage(this)">
                    <div class="pkg-head">PRO <span class="pkg-price">₱7,000</span></div>
                    <div class="pkg-body">More sound & lights for mid events</div>
                    <div class="pkg-action"><button type="button" class="btn btn-sm btn-outline-primary" onclick="selectPackage(this.parentElement.parentElement)">Select</button></div>
                  </div>
                </div>

                <div class="col-6 col-md-3">
                  <div class="pkg-card" data-value="Mid (₱10,000)" onclick="selectPackage(this)">
                    <div class="pkg-head">MID <span class="pkg-price">₱10,000</span></div>
                    <div class="pkg-body">Large events — full rig</div>
                    <div class="pkg-action"><button type="button" class="btn btn-sm btn-outline-primary" onclick="selectPackage(this.parentElement.parentElement)">Select</button></div>
                  </div>
                </div>
              </div>

              <input type="hidden" name="package" id="inputPackage" required>

              <div class="actions">
                <button type="button" class="btn btn-outline-secondary btn-back" onclick="goStep(1)">Back</button>
                <button type="button" class="btn jb-btn" onclick="goStep(2)">Next: Date</button>
              </div>
            </section>

            <!-- Step 2: Event Details -->
            <section id="step-2" class="step">
              <h2 class="title">Event Details</h2>
              <p class="desc">Provide event name (optional), date and time. Date must be after today.</p>

              <div class="row gx-3 mb-3">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Event name (optional)</label>
                  <input type="text" id="inputEventType" name="event_type" class="form-control form-control-lg" placeholder="e.g., Wedding, Birthday">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Event Date</label>
                  <input type="date" id="inputDate" name="event_date" class="form-control form-control-lg" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Approx. Start Time (optional)</label>
                  <input type="time" id="inputTime" name="event_time" class="form-control form-control-lg">
                </div>
              </div>

              <div class="actions">
                <button type="button" class="btn btn-outline-secondary btn-back" onclick="goStep(1)">Back</button>
                <button type="button" class="btn jb-btn" onclick="goStep(3)">Next: Address</button>
              </div>
            </section>

            <!-- Step 3: Address & Contact -->
            <section id="step-3" class="step">
              <h2 class="title">Event address & contact</h2>
              <p class="desc">Provide address and contact person for coordination.</p>

              <div class="mb-3">
                <label class="form-label">Full Event Address</label>
                <textarea id="inputAddress" name="address" class="form-control form-control-lg" rows="3" placeholder="House no., street, barangay, city, province" required></textarea>
              </div>

              <div class="row gx-3">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Contact Name</label>
                  <input type="text" id="inputName" name="contact_name" class="form-control form-control-lg" placeholder="Full name" required>
                </div>
                <div class="form-group mb-3">
                  <label for="facebook_account">Facebook Account (Messenger)</label>
                  <input type="text" id="facebook_account" name="facebook_account" class="form-control" placeholder="Optional – e.g. JB Lights & Sound Client">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Contact Phone</label>
                  <input type="text" id="inputPhone" name="contact_phone" class="form-control form-control-lg" placeholder="09xx-xxx-xxxx" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Notes (optional)</label>
                <textarea id="inputNotes" name="notes" class="form-control" rows="2"></textarea>
              </div>

              <div class="hint">We'll use these to coordinate delivery & set up.</div>

              <div class="actions">
                <button type="button" class="btn btn-outline-secondary btn-back" onclick="goStep(2)">Back</button>
                <button type="button" class="btn jb-btn" onclick="goStep(4)">Next: Payment</button>
              </div>
            </section>

            <!-- Step 4: Payment & Review -->
            <section id="step-4" class="step">
              <h2 class="title">Payment & Review</h2>
              <p class="desc">Choose payment (GCash requires downpayment). Review details then confirm.</p>

              <div class="payment-box mb-3">
                <div class="form-check payment-row mb-2">
                  <input class="form-check-input" type="radio" name="payment" id="payCash" value="Cash" required>
                  <label class="form-check-label" for="payCash"><strong>Cash on Delivery</strong> — pay after the event</label>
                </div>
                <div class="form-check payment-row mb-2">
                  <input class="form-check-input" type="radio" name="payment" id="payGcash" value="GCash">
                  <label class="form-check-label" for="payGcash"><strong>GCash</strong> — downpayment only</label>
                </div>
              </div>

              <div class="review-card mb-3">
                <div class="review-row"><div class="r-label">Package</div><div id="reviewPackage">—</div></div>
                <div class="review-row"><div class="r-label">Event</div><div id="reviewEvent">—</div></div>
                <div class="review-row"><div class="r-label">Address</div><div id="reviewAddress">—</div></div>
                <div class="review-row"><div class="r-label">Contact</div><div id="reviewContact">—</div></div>
                <div class="review-row"><div class="r-label">Payment</div><div id="reviewPayment">—</div></div>
                <div class="review-row"><div class="r-label">Downpayment</div><div id="reviewDown">—</div></div>
              </div>

              <div class="actions">
                <button type="button" class="btn btn-outline-secondary btn-back" onclick="goStep(3)">Back</button>
                <button type="button" class="btn jb-btn" onclick="openPreviewModal()">Review & Confirm</button>
              </div>
            </section>

            <input type="hidden" name="final_submit" value="1">
          </form>
        </div>

      <?php endif; ?>

    </div>
  </main>

  <!-- Preview Modal -->
  <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Review & Confirm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modalReview"></div>
          <div id="gcashBox" class="d-flex gap-3 align-items-center mt-3" style="display:none;">
            <div class="gcash-qr" style="width:120px;height:120px;border:1px solid #eef3fb;border-radius:6px;display:flex;align-items:center;justify-content:center;">
              <img src="gcash-qr.png" alt="GCash QR" style="max-width:100%;max-height:100%">
            </div>
            <div>
              <div><strong>GCash Account</strong></div>
              <div>JB Lights & Sound</div>
              <div>0965-639-6053</div>
              <div class="mt-2"><strong>Downpayment:</strong> <span id="modalDown">—</span></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="modalConfirm" class="btn jb-btn">Confirm Reservation</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-left">
        <div><i class="bi bi-telephone-fill"></i> 0965-639-6053</div>
        <div><i class="bi bi-envelope-fill"></i> jblightsandsoundrental@gmail.com</div>
        <div><i class="bi bi-geo-alt-fill"></i> 235, Purok 2, Bical, Mabalacat City, Pampanga</div>
      </div>
      <div class="footer-logo"><img src="jb-logo.png" alt="JB Logo"></div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="reservation.js"></script>
</body>
</html>