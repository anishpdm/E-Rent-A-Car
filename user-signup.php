<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eRent";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $aadhar = $_POST['aadhar_number'];
    $pan = $_POST['pan_card'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate Aadhar (16 digits)
    if (!preg_match("/^\d{16}$/", $aadhar)) {
        echo "<script>alert('Aadhar number must be exactly 16 digits.'); window.history.back();</script>";
        exit();
    }

    // Validate Age (18+)
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($dobDate)->y;
    if ($age < 18) {
        echo "<script>alert('You must be at least 18 years old.'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO users (first_name, middle_name, last_name, address, aadhar, pan, dob, email, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $address, $aadhar, $pan, $dob, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful!'); window.location.href = 'user-signin.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("https://stimg.cardekho.com/images/carexteriorimages/930x620/Tata/Nexon-EV-2023/11024/1694146347051/front-left-side-47.jpg");
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        body * {
            position: relative;
            z-index: 2;
            color: black;
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
    <form id="signupForm" method="POST">
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
                <label for="pan_card" class="form-label">Driving License</label>
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
        <br><br>
        <a class="btn btn-success w-100" href="user-signin.php">Click Here for User Sign In</a>
    </form>
</div>

<script>
document.getElementById("signupForm").addEventListener("submit", function (event) {
    let aadhar = document.getElementById("aadhar_number").value;
    let dob = document.getElementById("dob").value;
    let dobDate = new Date(dob);
    let today = new Date();
    let age = today.getFullYear() - dobDate.getFullYear();
    let monthDiff = today.getMonth() - dobDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dobDate.getDate())) {
        age--;
    }

    if (!/^\d{16}$/.test(aadhar)) {
        alert("Aadhar number must be exactly 16 digits.");
        event.preventDefault();
    }

    if (age < 18) {
        alert("You must be at least 18 years old.");
        event.preventDefault();
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
