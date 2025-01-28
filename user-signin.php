<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign-In</title>
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
            width: 30%;
        }
        .form-card h2 {
            color: #333;
        }
    </style>
</head>
<body>

<div class="form-card">
    <h2 class="text-center">User Sign-In</h2>
    <form  method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Email Id</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign In</button>
        <br>
        <br>
        <a class="btn btn-success w-100"  href="user-signup.php"> Click Here for User Sign  Up</a>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- signin_process.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain text password from the form

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'eRent');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL Query to fetch the user by username
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Check if query preparation was successful
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the provided password against the hashed password
        if (password_verify($password, $user['password'])) {
            header('Location: welcome.php');
            exit;
        } else {
            echo "<script>alert('Invalid username or password'); window.location.href = 'user-signin.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href = 'user-signin.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

