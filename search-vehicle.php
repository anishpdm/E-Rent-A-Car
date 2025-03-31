<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Electric Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updateStatus(vehicleId) {
            let status = document.getElementById('status_' + vehicleId).value;
            window.location.href = "search-vehicle.php?update_status=" + vehicleId + "&status=" + status;
        }
    </script>
</head>
<body>

<!-- 🔹 Navigation Bar -->
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

<!-- 🔹 Search Form -->
<div class="container mt-4">
    <div class="card p-4">
        <h2 class="text-center">Search Vehicles</h2>
        <form method="GET">
            <div class="mb-3"><input type="text" class="form-control" name="query" placeholder="Enter Model or Manufacturer"></div>
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </form>
    </div>
</div>

<!-- 🔹 Handle Status Update -->
<?php
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (isset($_GET['update_status']) && isset($_GET['status'])) {
    $stmt = $conn->prepare("UPDATE vehicles SET availability_status = ? WHERE id = ?");
    $stmt->bind_param("si", $_GET['status'], $_GET['update_status']);
    $stmt->execute();
    echo "<script>alert('Status Updated!'); window.location.href='search-vehicle.php';</script>";
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    echo "<script>alert('Vehicle Deleted!'); window.location.href='search-vehicle.php';</script>";
}
?>

<!-- 🔹 Display Search Results -->
<div class="container mt-4">
    <div class="row">
        <?php
        if (isset($_GET['query'])) {
            $search = "%" . $_GET['query'] . "%";
            $stmt = $conn->prepare("SELECT * FROM vehicles WHERE model_name LIKE ? OR manufacturer_name LIKE ?");
            $stmt->bind_param("ss", $search, $search);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-4'>
                        <div class='card mb-4'>
                            <img src='{$row['image_link']}' class='card-img-top' alt='Vehicle' style='height: 200px; object-fit: cover;'>
                            <div class='card-body'>
                                <h5>{$row['model_name']}</h5>
                                <p>{$row['manufacturer_name']} - ₹{$row['rent_rate']}/day</p>
                                
                                <p><strong>Status:</strong>
                                    <select class='form-select d-inline-block w-auto' id='status_{$row['id']}' onchange='updateStatus({$row['id']})'>
                                        <option value='Available' " . ($row['availability_status'] == "Available" ? "selected" : "") . ">Available</option>
                                        <option value='Rented' " . ($row['availability_status'] == "Rented" ? "selected" : "") . ">Rented</option>
                                        <option value='Maintenance' " . ($row['availability_status'] == "Maintenance" ? "selected" : "") . ">Maintenance</option>
                                    </select>
                                </p>
                                
                                <button class='btn btn-danger btn-sm' onclick='window.location.href=\"search-vehicle.php?delete={$row['id']}\"'>Delete</button>
                            </div>
                        </div>
                    </div>";
            }

            $stmt->close();
        }
        ?>
    </div>
</div>

</body>
</html>
