<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image:url("https://stimg.cardekho.com/images/carexteriorimages/930x620/Tata/Nexon-EV-2023/11024/1694146347051/front-left-side-47.jpg")  ;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: cover; /* Ensures the image covers the entire background */
    background-position: center; /* Centers the image */
    background-repeat: no-repeat; /* Prevents repeating the image */
    height: 100vh; /* Makes the background cover the full height of the viewport */
    margin: 0; /* Removes default margins */
        }
        body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Black overlay with 50% transparency */
    z-index: 1;
}

body * {
    position: relative;
    z-index: 2; /* Ensures content is above the fading overlay */
    color: black; /* Makes text readable over the darkened background */
}
        .form-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 50%;
        }
        .form-card h2 {
            color: #333;
        }
    </style>
</head>
<body>

<div class="form-card">
    <h2 class="text-center">User Signup</h2>
    <form  method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name">
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="aadhar_number" class="form-label">Aadhar Number</label>
                <input type="text" class="form-control" id="aadhar_number" name="aadhar_number" required>
            </div>
            <div class="col-md-6">
                <label for="pan_card" class="form-label">PAN Card</label>
                <input type="text" class="form-control" id="pan_card" name="pan_card" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email ID</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="col-md-6">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        <br> <br>
                    <a class="btn btn-success w-100"  href="user-signin.php"> Click Here for User Sign In</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- user_signup.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "eRent";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $aadhar = $_POST['aadhar_number'];
    $pan = $_POST['pan_card'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, middle_name, last_name, address, aadhar, pan, dob, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $address, $aadhar, $pan, $dob, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful!'); window.location.href = 'user-signin.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- MySQL table creation code -->
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

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    aadhar VARCHAR(12) NOT NULL,
    pan VARCHAR(10) NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
