<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (!isset($_GET['id'])) {
    die("Vehicle ID is required.");
}
$vehicle_id = $_GET['id'];

// Fetch vehicle details
$vehicle_query = "SELECT * FROM vehicles WHERE id = $vehicle_id";
$vehicle_result = $conn->query($vehicle_query);
if ($vehicle_result->num_rows == 0) {
    die("Vehicle not found.");
}
$vehicle = $vehicle_result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['no_of_days'])) {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $no_of_days = (int)$_POST['no_of_days'];
    $total_amount_paid = $no_of_days * $vehicle['rent_rate'];
    $payment_status = "Success"; // Demo payment success
    $booking_date = date('Y-m-d H:i:s');

    echo $booking_query = "INSERT INTO bookings (user_id, vehicle_id, booking_date, payment_status, no_of_days, total_amount_paid) 
                      VALUES ('$user_id', '$vehicle_id', '$booking_date', '$payment_status', '$no_of_days', '$total_amount_paid')";

    if ($conn->query($booking_query) === TRUE) {
        $conn->query("UPDATE vehicles SET availability_status = 'Rented' WHERE id = $vehicle_id");
        echo "<script>alert('Booking Successful!'); window.location.href='view-vehicles.php';</script>";
    } else {
        echo "<script>alert('Booking Failed. Try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function calculateFare() {
            let rentPerDay = <?= $vehicle['rent_rate'] ?>;
            let days = document.getElementById('no_of_days').value;
            let totalFare = rentPerDay * days;
            document.getElementById('calculatedFare').innerText = '₹' + totalFare;
            document.getElementById('total_amount_paid').value = totalFare;
        }

        function showPaymentModal() {
            let days = document.getElementById('no_of_days').value;
            if (!days || days <= 0) {
                alert('Please enter a valid number of days.');
                return;
            }
            calculateFare();
            var modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }
    </script>
</head>
<body>

<!-- 🔹 Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="#">E-Vehicle Rental | Users</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

<li class="nav-item"><a class="nav-link active" href="view-vehicles.php">View Vehicles</a></li>
<li class="nav-item"><a class="nav-link active" href="user-profile.php">View Profile</a></li>
<li class="nav-item"><a class="nav-link active" href="view-user-booking.php">View Booking</a></li>
<li class="nav-item"><a class="nav-link active" href="index.php"> Log Out </a></li>

</ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card">
        <img src="<?= $vehicle['image_link'] ?>" class="card-img-top" alt="Vehicle">
        <div class="card-body">
            <h4><?= $vehicle['model_name'] ?></h4>
            <p><strong>Manufacturer:</strong> <?= $vehicle['manufacturer_name'] ?></p>
            <p><strong>Year:</strong> <?= $vehicle['model_year'] ?></p>
            <p><strong>Rent:</strong> ₹<?= $vehicle['rent_rate'] ?>/day</p>
            
            <div class="mb-3">
                <label class="form-label">Number of Days</label>
                <input type="number" class="form-control" id="no_of_days" min="1" placeholder="Enter number of days" required>
            </div>
            
            <button class="btn btn-success w-100" onclick="showPaymentModal()">Pay Now</button>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Demo Payment Gateway</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Total Fare: <strong id="calculatedFare">₹0</strong></p>
                <form method="POST">
                    <input type="hidden" name="no_of_days" id="no_of_days_form">
                    <input type="hidden" name="total_amount_paid" id="total_amount_paid">
                    
                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" class="form-control" placeholder="1234 5678 9012 3456" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" placeholder="MM/YY" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CVV</label>
                            <input type="text" class="form-control" placeholder="123" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelector("#paymentModal form").addEventListener("submit", function() {
        let days = document.getElementById("no_of_days").value;
        document.getElementById("no_of_days_form").value = days;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
