<?php
session_start();
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['chargingStationName'];
    $location = $_POST['Location'];
    $pinCode = $_POST['PinCode'];
    $latitude = $_POST['Latitude'];
    $longitude = $_POST['Longitude'];

    $stmt = $conn->prepare("INSERT INTO chargingStations (chargingStationName, Location, PinCode, Latitude, Longitude) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidd", $name, $location, $pinCode, $latitude, $longitude);
    
    if ($stmt->execute()) {
        echo "<script>alert('Charging Station added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add station. Try again.');</script>";
    }
}

// Fetch all charging stations
$result = $conn->query("SELECT * FROM chargingStations");
$stations = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Charging Stations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>
<body>
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
    <h2>Add Charging Station</h2>
    <form method="POST" class="mb-4">
        <div class="mb-2">
            <label class="form-label">Station Name:</label>
            <input type="text" name="chargingStationName" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Location:</label>
            <input type="text" name="Location" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Pin Code:</label>
            <input type="text" name="PinCode" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Latitude:</label>
            <input type="text" name="Latitude" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Longitude:</label>
            <input type="text" name="Longitude" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Station</button>
    </form>
    
    <h3>Charging Stations</h3>
    <div id="map" style="height: 400px;"></div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([10.8505, 76.2711], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var stations = <?php echo json_encode($stations); ?>;
    stations.forEach(station => {
        if (station.Latitude && station.Longitude) {
            L.marker([station.Latitude, station.Longitude])
                .addTo(map)
                .bindPopup(`<b>${station.chargingStationName}</b><br>${station.Location}`);
        }
    });
</script>
</body>
</html>