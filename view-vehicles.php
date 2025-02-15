<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .faded-card {
            opacity: 0.5;
        }
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 200px; /* Ensure all cards have a consistent height */
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
    <script>
        function applyFilters() {
            let model = document.getElementById("model").value;
            let manufacturer = document.getElementById("manufacturer").value;
            let year = document.getElementById("year").value;
            let amount = document.getElementById("amount").value;
            let status = document.getElementById("status").value;

            window.location.href = `view-vehicles.php?model=${model}&manufacturer=${manufacturer}&year=${year}&amount=${amount}&status=${status}`;
        }
    </script>
</head>
<body>

<!-- 🔹 Navigation Bar -->
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
                <li class="nav-item"><a class="nav-link active" href="index.php"> Log Out </a></li>

            </ul>
        </div>
    </div>
</nav>

<!-- 🔹 Filter Section -->
<div class="container mt-4">
    <div class="card p-4">
        <h4>Filter Vehicles</h4>
        <div class="row">
            <div class="col-md-3 mb-3">
                <input type="text" id="model" class="form-control" placeholder="Model Name">
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" id="manufacturer" class="form-control" placeholder="Manufacturer">
            </div>
            <div class="col-md-2 mb-3">
                <select id="year" class="form-select">
                    <option value="">Select Year</option>
                    <?php for ($i = 2025; $i >= 2000; $i--) echo "<option value='$i'>$i</option>"; ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <input type="number" id="amount" class="form-control" placeholder="Max Rent ₹">
            </div>
            <div class="col-md-2 mb-3">
                <select id="status" class="form-select">
                    <option value="">Availability</option>
                    <option value="Available">Available</option>
                    <option value="Rented">Rented</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary w-100" onclick="applyFilters()">Apply Filters</button>
    </div>
</div>

<!-- 🔹 Display Vehicles -->
<div class="container mt-4">
    <div class="row">
        <?php
        // Database Connection
        $conn = new mysqli("localhost", "root", "", "eRent");
        if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

        // Filters
        $query = "SELECT * FROM vehicles WHERE 1";

        if (!empty($_GET['model'])) { $query .= " AND model_name LIKE '%" . $_GET['model'] . "%'"; }
        if (!empty($_GET['manufacturer'])) { $query .= " AND manufacturer_name LIKE '%" . $_GET['manufacturer'] . "%'"; }
        if (!empty($_GET['year'])) { $query .= " AND model_year = " . $_GET['year']; }
        if (!empty($_GET['amount'])) { $query .= " AND rent_rate <= " . $_GET['amount']; }
        if (!empty($_GET['status'])) { $query .= " AND availability_status = '" . $_GET['status'] . "'"; }
        
        // Sort order: Available → Rented → Maintenance
        $query .= " ORDER BY 
                    CASE availability_status 
                        WHEN 'Available' THEN 1 
                        WHEN 'Rented' THEN 2 
                        WHEN 'Maintenance' THEN 3 
                        ELSE 4 
                    END";
        
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $isAvailable = $row['availability_status'] == "Available";
            $cardClass = $isAvailable ? "" : "faded-card";
            $badgeClass = $row['availability_status'] == "Available" ? "success" : ($row['availability_status'] == "Rented" ? "warning" : "danger");

            echo "<div class='col col-12 col-md-4 d-flex align-items-stretch'>
                    <div class='card mb-4 shadow-sm $cardClass'>
                        <img src='{$row['image_link']}' class='card-img-top' alt='Vehicle'>
                        <div class='card-body'>
                            <h5>{$row['model_name']}</h5>
                            <p>{$row['manufacturer_name']} - ₹{$row['rent_rate']}/day</p>
                            <p><strong>Seats:</strong> {$row['seat_capacity']} | <strong>Battery:</strong> {$row['battery_range']} km</p>
                            <span class='badge bg-$badgeClass'>{$row['availability_status']}</span>
                            " . ($isAvailable ? "<a href='book-vehicle.php?id={$row['id']}' class='btn btn-primary mt-2 w-100'>Book Now</a>" : "") . "
                        </div>
                    </div>
                </div>";
        }
        ?>
    </div>
</div>

</body>
</html>
