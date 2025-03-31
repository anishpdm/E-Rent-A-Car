<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user = $_SESSION['user_id'];

// Use prepared statement to prevent SQL injection
$bookings_query = $conn->prepare("SELECT 
    b.id AS booking_id, 
    v.model_name, 
    v.manufacturer_name, 
    v.rent_rate, 
    v.image_link, 
    b.booking_date, 
    b.total_amount_paid, 
    b.no_of_days, 
    b.payment_status, 
    b.return_status, 
    v.id AS vehicle_id 
FROM bookings b 
JOIN vehicles v ON b.vehicle_id = v.id 
WHERE b.user_id = ?");
$bookings_query->bind_param("i", $user);
$bookings_query->execute();
$result = $bookings_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
                <li class="nav-item"><a class="nav-link active" href="view-vehicles.php">View Vehicles</a></li>
                <li class="nav-item"><a class="nav-link active" href="user-profile.php">View Profile</a></li>
                <li class="nav-item"><a class="nav-link active" href="view-user-booking.php">View Booking</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3>My Bookings</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vehicle</th>
                <th>Manufacturer</th>
                <th>Rent (₹/day)</th>
                <th>Booking Date</th>
                <th>Booking Days</th>
                <th>Booking Amount</th>
                <th>Payment Status</th>
                <th>Return Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['model_name']) ?></td>
                    <td><?= htmlspecialchars($row['manufacturer_name']) ?></td>
                    <td>₹<?= htmlspecialchars($row['rent_rate']) ?></td>
                    <td><?= htmlspecialchars($row['booking_date']) ?></td>
                    <td><?= htmlspecialchars($row['no_of_days']) ?></td>
                    <td>₹<?= htmlspecialchars($row['total_amount_paid']) ?></td>
                    <td>
                        <?php if ($row['payment_status'] == "Success") { ?>
                            <span class="badge bg-success">Paid</span>
                        <?php } else { ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($row['return_status'] == 1) { ?>
                            <span class="badge bg-success">Returned</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Not Yet Returned</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
