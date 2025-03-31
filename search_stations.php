<?php
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search = $conn->real_escape_string($search);

if (!empty($search)) {
    $query = "SELECT id, chargingStationName, Location, PinCode, Latitude, Longitude 
              FROM chargingStations 
              WHERE Location LIKE '%$search%' OR PinCode LIKE '%$search%'";
} else {
    $query = "SELECT id, chargingStationName, Location, PinCode, Latitude, Longitude FROM chargingStations";
}

$result = $conn->query($query);
$stations = [];

while ($station = $result->fetch_assoc()) {
    $stations[] = $station;
}

echo json_encode($stations);
$conn->close();
?>
