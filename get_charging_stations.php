<?php
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$query = "SELECT * FROM chargingStations";
$result = $conn->query($query);

$stations = [];
while ($row = $result->fetch_assoc()) {
    $stations[] = $row;
}
echo json_encode($stations);
?>
