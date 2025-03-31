<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user-signin.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'eRent');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT first_name, middle_name, last_name, address, aadhar, pan, dob, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update user details
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];
    $dob = $_POST['dob'];

    // Validation
    if (!preg_match('/^\d{16}$/', $aadhar)) {
        $error = "Aadhar number must be exactly 16 digits.";
    } elseif (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan)) {
        $error = "Invalid PAN format (Example: ABCDE1234F).";
    } elseif ((date('Y') - date('Y', strtotime($dob))) < 18) {
        $error = "User must be at least 18 years old.";
    } else {
        $updateSQL = "UPDATE users SET first_name=?, middle_name=?, last_name=?, address=?, aadhar=?, pan=?, dob=? WHERE id=?";
        $stmt = $conn->prepare($updateSQL);
        $stmt->bind_param("sssssssi", $first_name, $middle_name, $last_name, $address, $aadhar, $pan, $dob, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully'); window.location.href='user-profile.php';</script>";
        } else {
            $error = "Update failed. Please try again.";
        }
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="#">E-Vehicle Rental | Users </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link active" href="view-vehicles.php">View Vehicles</a></li>
                <li class="nav-item"><a class="nav-link active" href="user-profile.php">View Profile</a></li>
                <li class="nav-item"><a class="nav-link active" href="view-user-booking.php">View Booking</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php"> Log Out </a></li>

            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2 class="text-center">User Profile</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateForm()">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= $user['first_name'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Middle Name</label>
            <input type="text" name="middle_name" class="form-control" value="<?= $user['middle_name'] ?>">
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= $user['last_name'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="<?= $user['address'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Aadhar (16 digits)</label>
            <input type="text" name="aadhar" id="aadhar" class="form-control" value="<?= $user['aadhar'] ?>" required>
            <small id="aadharError" class="text-danger"></small>
        </div>
        <div class="mb-3">
            <label>PAN (ABCDE1234F format)</label>
            <input type="text" name="pan" id="pan" class="form-control" value="<?= $user['pan'] ?>" required>
            <small id="panError" class="text-danger"></small>
        </div>
        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" value="<?= $user['dob'] ?>" required>
            <small id="dobError" class="text-danger"></small>
        </div>
        <div class="mb-3">
            <label>Email (Cannot be changed)</label>
            <input type="email" class="form-control" value="<?= $user['email'] ?>" disabled>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        <!-- <a href="logout.php" class="btn btn-danger w-100 mt-2">Logout</a> -->
    </form>
</div>

<script>
function validateForm() {
    let valid = true;
    let aadhar = document.getElementById("aadhar").value;
    let pan = document.getElementById("pan").value;
    let dob = document.getElementById("dob").value;
    let aadharError = document.getElementById("aadharError");
    let panError = document.getElementById("panError");
    let dobError = document.getElementById("dobError");

    // Reset error messages
    aadharError.textContent = "";
    panError.textContent = "";
    dobError.textContent = "";

    // Aadhar Validation
    if (!/^\d{16}$/.test(aadhar)) {
        aadharError.textContent = "Aadhar number must be 16 digits.";
        valid = false;
    }

    // PAN Validation
    if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(pan)) {
        panError.textContent = "Invalid PAN format (Example: ABCDE1234F).";
        valid = false;
    }

    // DOB Validation (User must be at least 18 years old)
    let birthYear = new Date(dob).getFullYear();
    let currentYear = new Date().getFullYear();
    if ((currentYear - birthYear) < 18) {
        dobError.textContent = "You must be at least 18 years old.";
        valid = false;
    }

    return valid;
}
</script>
</body>
</html>
