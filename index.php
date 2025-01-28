<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - E-Vehicle Booking</title>
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
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .login-card h2 {
            color: #333;
        }
        .login-card .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-card text-center">
                <img src="https://png.pngtree.com/png-vector/20191101/ourmid/pngtree-cartoon-color-simple-male-avatar-png-image_1934459.jpg" alt="Admin" class="profile-image">
                <h2 class="text-center">E-Vehicle Rental -  Admin Login</h2>
                <form  method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <a href="user-signup.php"> Click Here for User SignUp</a> 
                    <br> <br>
                    <a href="user-signin.php"> Click Here for User Sign In</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- admin_login.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Default admin credentials
    if ($username === 'admin' && $password === '12345') {
        header('Location: add-vehicle.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href = 'index.php';</script>";
    }
}
?>
