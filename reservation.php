<?php include("db_connect.php"); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $package = $_POST["package"];
  $event_date = $_POST["event_date"];
  $address = $_POST["address"];
  $payment = $_POST["payment"];

  $sql = "INSERT INTO reservations (package, event_date, address, payment_method) 
          VALUES ('$package', '$event_date', '$address', '$payment')";
  
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Reservation submitted successfully!');</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservation | JB Lights & Sound</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="index.html">JB Lights & Sound</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- HERO HEADER -->
<section class="hero d-flex align-items-center justify-content-center text-center text-white" style="height:60vh; background:url('hero-bg.jpg') center/cover no-repeat;">
  <div class="bg-dark bg-opacity-50 p-4 rounded">
    <h1 class="fw-bold">Make a Reservation</h1>
    <p>Secure your event today with JB Lights & Sound.</p>
  </div>
</section>

<!-- RESERVATION FORM -->
<div class="container my-5">
  <div class="card shadow-lg p-4 mx-auto" style="max-width:600px;">
    <form method="POST" id="reservationForm">
      <!-- Step Navigation -->
      <div class="text-center mb-4">
        <span class="badge bg-primary" id="step-indicator">Step 1 of 4</span>
      </div>

      <!-- Step 1 -->
      <div class="step" id="step1">
        <h4 class="text-center text-primary mb-3">Choose a Package</h4>
        <select name="package" class="form-select form-select-lg mb-4" required>
          <option value="">-- Select Package --</option>
          <option value="Basic">Basic Package</option>
          <option value="Premium">Premium Package</option>
          <option value="Ultimate">Ultimate Package</option>
        </select>
        <div class="text-center">
          <button type="button" class="btn btn-warning fw-semibold" onclick="nextStep(2)">Next</button>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="step d-none" id="step2">
        <h4 class="text-center text-primary mb-3">Select Event Date</h4>
        <input type="date" name="event_date" class="form-control form-control-lg mb-4" required>
        <div class="text-center">
          <button type="button" class="btn btn-secondary me-2" onclick="prevStep(1)">Back</button>
          <button type="button" class="btn btn-warning fw-semibold" onclick="nextStep(3)">Next</button>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="step d-none" id="step3">
        <h4 class="text-center text-primary mb-3">Enter Event Address</h4>
        <textarea name="address" rows="3" class="form-control form-control-lg mb-4" required></textarea>
        <div class="text-center">
          <button type="button" class="btn btn-secondary me-2" onclick="prevStep(2)">Back</button>
          <button type="button" class="btn btn-warning fw-semibold" onclick="nextStep(4)">Next</button>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="step d-none" id="step4">
        <h4 class="text-center text-primary mb-3">Select Payment Method</h4>
        <div class="form-check mb-3">
          <input type="radio" name="payment" value="Cash on Delivery" class="form-check-input" id="cash" required>
          <label class="form-check-label fs-5" for="cash">Cash on Delivery (Pay after event)</label>
        </div>
        <div class="form-check mb-4">
          <input type="radio" name="payment" value="GCash (Downpayment)" class="form-check-input" id="gcash">
          <label class="form-check-label fs-5" for="gcash">GCash (Downpayment required)</label>
        </div>
        <div class="text-center">
          <button type="button" class="btn btn-secondary me-2" onclick="prevStep(3)">Back</button>
          <button type="submit" class="btn btn-success fw-semibold">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-light py-4 text-center">
  <p class="mb-0">© 2025 JB Lights & Sound | All Rights Reserved</p>
</footer>

<script>
let currentStep = 1;

function showStep(step) {
  document.querySelectorAll(".step").forEach(s => s.classList.add("d-none"));
  document.getElementById("step" + step).classList.remove("d-none");
  document.getElementById("step-indicator").innerText = `Step ${step} of 4`;
}

function nextStep(step) {
  currentStep = step;
  showStep(step);
}

function prevStep(step) {
  currentStep = step;
  showStep(step);
}
</script>

</body>
</html>
