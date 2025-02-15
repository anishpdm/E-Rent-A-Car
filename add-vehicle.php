<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Electric Vehicle</title>
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
            </ul>
        </div>
    </div>
</nav>

<!-- 🔹 Vehicle Form -->
<div class="container mt-4">
    <div class="card p-4">
        <h2 class="text-center">Add Electric Vehicle</h2>
        <form action="add-vehicle.php" method="POST">
            <div class="mb-3"><label class="form-label">Vehicle Number</label>
                <input type="text" class="form-control" name="vehicle_number" required></div>
            <div class="mb-3"><label class="form-label">Model Name</label>
                <input type="text" class="form-control" name="model_name" required></div>
            <div class="mb-3"><label class="form-label">Manufacturer Name</label>
                <input type="text" class="form-control" name="manufacturer_name" required></div>
            <div class="mb-3"><label class="form-label">Model Year</label>
                <input type="number" class="form-control" name="model_year" required min="2000"></div>
            <div class="mb-3"><label class="form-label">Image Link</label>
                <input type="url" class="form-control" name="image_link" required></div>
            <div class="mb-3"><label class="form-label">Seat Capacity</label>
                <input type="number" class="form-control" name="seat_capacity" required min="1"></div>
            <div class="mb-3"><label class="form-label">Battery Range (km)</label>
                <input type="text" class="form-control" name="battery_range" required></div>
            <div class="mb-3"><label class="form-label">Availability Status</label>
                <select class="form-control" name="availability_status" required>
                    <option value="Available">Available</option>
                    <option value="Rented">Rented</option>
                    <option value="Maintenance">Maintenance</option>
                </select></div>
            <div class="mb-3"><label class="form-label">Rent Rate (per day ₹)</label>
                <input type="number" class="form-control" name="rent_rate" required min="100" step="0.01"></div>
            <button type="submit" class="btn btn-primary w-100">Add Vehicle</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// 🔹 Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "eRent");
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_number, model_name, manufacturer_name, model_year, image_link, seat_capacity, battery_range, availability_status, rent_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissssd", $_POST['vehicle_number'], $_POST['model_name'], $_POST['manufacturer_name'], $_POST['model_year'], $_POST['image_link'], $_POST['seat_capacity'], $_POST['battery_range'], $_POST['availability_status'], $_POST['rent_rate']);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle Added Successfully!'); window.location.href = 'add-vehicle.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); $conn->close();
}
?>
