<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eRent";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS eRent";
$conn->query($sql);
$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_number VARCHAR(20) NOT NULL UNIQUE,
    model_name VARCHAR(100) NOT NULL,
    manufacturer_name VARCHAR(100) NOT NULL,
    model_year INT NOT NULL,
    image_link TEXT,
    seat_capacity INT NOT NULL,
    battery_range VARCHAR(50) NOT NULL,
    availability_status ENUM('Available', 'Rented', 'Maintenance') NOT NULL DEFAULT 'Available',
    rent_rate DECIMAL(10,2) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
