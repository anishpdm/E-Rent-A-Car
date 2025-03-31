<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (!isset($_GET['id'])) {
    die("Booking ID is required.");
}
$booking_id = $_GET['id'];

// Fetch booking details
$booking_query = "SELECT * FROM bookings WHERE id = $booking_id";
$booking_result = $conn->query($booking_query);
if ($booking_result->num_rows == 0) {
    die("Booking not found.");
}
$booking = $booking_result->fetch_assoc();
$vehicle_id = $booking['vehicle_id'];

// Return vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update return status in bookings table
    $return_query = "UPDATE bookings SET return_status = 1, return_date = NOW() WHERE id = $booking_id";

    if ($conn->query($return_query) === TRUE) {
        // Mark vehicle as available
        $update_vehicle_query = "UPDATE vehicles SET availability_status = 'Available' WHERE id = $vehicle_id";
        $conn->query($update_vehicle_query);

        echo "<script>alert('Vehicle returned successfully!'); window.location.href='add-vehicle.php';</script>";
    } else {
        echo "<script>alert('Return failed. Try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- 🔹 Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">E-Vehicle Rental</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="add-vehicle.php">Add Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="search-vehicle.php">Search Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="list-booking.php">Return Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Log Out</a></li>

            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h4>Return Vehicle</h4>
            <p>Are you sure you want to return this vehicle?</p>
            <form method="POST">
                <button type="submit" class="btn btn-danger">Confirm Return</button>
                <a href="add-vehicles.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
