<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }


$bookings_query = "SELECT b.id AS booking_id, v.model_name, v.manufacturer_name, v.rent_rate, v.image_link, b.booking_date, b.payment_status, v.id AS vehicle_id FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id where b.return_status=0";
$result = $conn->query($bookings_query);
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
                <li class="nav-item"><a class="nav-link" href="add-vehicle.php">Add Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="search-vehicle.php">Search Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="list-booking.php">Return Vehicle</a></li>
                <li class="nav-item"><a class="nav-link" href="add-charge.php">Add Charging Station </a></li>

                <li class="nav-item"><a class="nav-link" href="index.php">Log Out</a></li>

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
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['model_name'] ?></td>
                    <td><?= $row['manufacturer_name'] ?></td>
                    <td>₹<?= $row['rent_rate'] ?></td>
                    <td><?= $row['booking_date'] ?></td>
                    <td><span class="badge bg-success">Paid</span></td>
                    <td>
                        <a href="return-vehicle.php?id=<?= $row['booking_id'] ?>" class="btn btn-warning">Return Vehicle</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
