<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV Charging Stations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map { height: 500px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">EV Charging Stations</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <h4>Available Charging Stations</h4>
            <input type="text" id="searchBox" class="form-control mb-2" placeholder="Search by name or location...">
            <ul id="stationList" class="list-group"></ul>
        </div>
        <div class="col-md-8">
            <div id="map"></div>
        </div>
    </div>
</div>

<script>
    var map = L.map('map').setView([10, 76], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch('get_charging_stations.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(station => {
                let marker = L.marker([station.Latitude, station.Longitude]).addTo(map)
                    .bindPopup(`<strong>${station.chargingStationName}</strong><br>${station.Location}<br>Pin: ${station.PinCode}`);
                
                let listItem = document.createElement("li");
                listItem.className = "list-group-item";
                listItem.innerHTML = `<strong>${station.chargingStationName}</strong> - ${station.Location}`;
                listItem.onclick = () => {
                    map.setView([station.Latitude, station.Longitude], 14);
                    marker.openPopup();
                };
                document.getElementById("stationList").appendChild(listItem);
            });
        });
</script>

</body>
</html>
