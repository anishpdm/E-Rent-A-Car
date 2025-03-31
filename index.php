<?php
$conn = new mysqli("localhost", "root", "", "eRent");
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available EVs
$car_query = "SELECT id, model_name, manufacturer_name, rent_rate, image_link FROM vehicles WHERE availability_status='Available'";
$car_result = $conn->query($car_query);

// Fetch all charging stations for initial map display
$station_query = "SELECT id, chargingStationName, Location, PinCode, Latitude, Longitude FROM chargingStations";
$station_result = $conn->query($station_query);

$stations = [];
while ($station = $station_result->fetch_assoc()) {
    $stations[] = $station;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV Booking & Charging</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .map-container {
            height: 400px;
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="font-sans">

    <header class="bg-gray-100 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold">EV Drive & Charge</div>
            <nav class="space-x-4">
                <a href="#" class="hover:text-blue-500">Home</a>
                <a href="#book-ev" class="hover:text-blue-500">Book EV</a>
                <a href="#find-charging" class="hover:text-blue-500">Find Charging</a>
                <a href="login.php" class="hover:text-blue-500">Admin LogIn</a>
                <a href="user-signin.php" class="hover:text-blue-500">User LogIn</a>
            </nav>
        </div>
    </header>

    <section class="bg-blue-100 py-20 text-center">
        <div class="container mx-auto">
            <h1 class="text-4xl font-bold mb-4">Drive Electric, Charge Anywhere</h1>
            <p class="text-lg mb-8">Seamless EV booking and charging station finder.</p>
            <div class="flex justify-center space-x-4">
                <a href="#book-ev" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">Book EV</a>
                <a href="#find-charging" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded">Find Charging</a>
            </div>
        </div>
    </section>

    <section id="book-ev" class="py-16">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-center">Book Your EV</h2>
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
               
                <div class="md:w-3/4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php while ($car = $car_result->fetch_assoc()) { ?>
                            <div class="border rounded p-4">
                                <img src="<?= $car['image_link'] ?>" alt="<?= $car['model_name'] ?>" class="w-full mb-2">
                                <h3 class="font-semibold"><?= $car['manufacturer_name'] ?> - <?= $car['model_name'] ?></h3>
                                <p>Rent: ₹<?= $car['rent_rate'] ?>/day</p>
                                <br>
                                <a href="login.php" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded mt-2">Book Now</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="find-charging" class="bg-gray-100 py-16">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-center">Find Charging Stations</h2>
            
            <!-- Search Box -->
            <input type="text" id="searchInput" placeholder="Enter Location or Pin Code" class="w-full p-3 border rounded">
            
            <div class="map-container mt-4" id="charging-map"></div>
            
            <!-- Results -->
            <div id="stationList" class="mt-4">
                <p class="text-center text-gray-500">Search results will appear here...</p>
            </div>
        </div>
    </section>

   


    <footer class="bg-gray-800 text-white p-4 text-center">
        <p>&copy; 2025 EV Drive & Charge. All rights reserved.</p>
    </footer>





    <script>
        var map = L.map('charging-map').setView([10.8505, 76.2711], 7); // Default Kerala
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var chargingStations = <?= json_encode($stations) ?>;
        var markers = [];

        function updateMap(stations) {
            markers.forEach(marker => map.removeLayer(marker)); // Clear previous markers
            markers = [];

            stations.forEach(station => {
                let marker = L.marker([station.Latitude, station.Longitude])
                    .addTo(map)
                    .bindPopup(`<b>${station.chargingStationName}</b><br>${station.Location}, ${station.PinCode}`);
                markers.push(marker);
            });
        }

        // Initial load of stations
        updateMap(chargingStations);

        // AJAX Search
        $("#searchInput").on("keyup", function () {
            let query = $(this).val().trim();
            if (query.length < 2) return;

            $.ajax({
                url: "search_stations.php",
                type: "GET",
                data: { search: query },
                success: function (response) {
                    let stations = JSON.parse(response);
                    updateMap(stations);

                    let output = stations.length > 0 ? "" : "<p class='text-center text-red-500'>No stations found.</p>";
                    stations.forEach(station => {
                        output += `<div class="border p-3 mb-2">
                                    <strong>${station.chargingStationName}</strong><br>
                                    ${station.Location}, ${station.PinCode}
                                </div>`;
                    });
                    $("#stationList").html(output);
                }
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
