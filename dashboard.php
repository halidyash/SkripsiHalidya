<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracking</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <style>
        #map, #map-laporan {
            height: calc(100vh - 80px);
            /* Adjust according to the height of the navbar */
            margin-top: 5%;
            margin-left: 50px;
            /* Sidebar width */
        }

        #status {
            margin-top: 5%;
        }

        .d-none {
            display: none;
        }

        body {
            background-color: #ffffff;
            overflow: hidden;
            /* Prevent scrolling */
        }

        body div a img {
            background-color: white;
            border-radius: 30%;
        }

        .navbar {
            top: 0;
            width: 100%;
            height: 9%;
            z-index: 1;
            position: fixed;
            background-color: #000050;
        }

        .sidebar {
            background-color: #1665a0;
            color: white;
            height: 100vh;
            position: fixed;
            width: 200px;
            z-index: 0;
            top: 0;
            left: 0;
            padding-top: 60px;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar :hover {
            color: black;
            background-color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }

        .content-section {
            margin-left: 200px;
            padding: 15px;
            overflow-y: auto;
            max-height: calc(100vh - 80px); /* Make sure it doesn't overlap with the navbar */
        }

        /* TABEL GPS */

        #historyTable_wrapper .dataTables_scrollBody {
            max-height: 400px;
            /* Same as above height */
            overflow: auto;
        }

        #historyTable_wrapper .dataTables_scrollBody thead th {
            position: sticky;
            top: 0;
            background: rgb(255, 255, 255);
            z-index: 2;
        }

        .centered-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding-top: 10%;
            overflow-y: hidden;
        }

        @media (max-width: 720px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content-section {
                margin-left: 0;
                padding-top: 60px;
            }

            #map {
                margin-top: 0;
                margin-left: 0;
                padding-top: 0;
                display: block;
                justify-content: center;
                align-items: center;
            }
        }

        .logout-button {
            background-color: #822;
            /* Warna abu-abu */
            color: white;
            /* Warna teks */
            border: none;
            padding: 9px;
            font-size: 13px;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 5px rgba(0, 0, 0, 0.3);
            /* Bayangan */
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            /* Animasi transisi */
        }

        /* TABEL delivery */

        #scheduleTable_wrapper .dataTables_scrollBody {
            max-height: 400px;
            overflow: auto;
        }

        #scheduleTable_wrapper .dataTables_scrollBody thead th {
            position: sticky;
            top: 0;
            background: rgb(255, 255, 255);
            z-index: 2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            box-sizing: border-box;
        }

        th {
            position: relative;
            background-color: #f2f2f2;
        }

        td.customer {
            font-weight: bold;
        }

        .status.selesai {
            font-style: italic;
            color: green;
        }

        .status.lima_menit_lagi {
            font-weight: bold;
            color: red;
        }

        .status.tiga_puluh_menit_lagi {
            font-weight: bold;
            color: orange;
        }

        .status.menunggu {
            font-style: italic;
            color: yellow;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="src/img/logo remove.png" alt="Logo Nihon Seiki Indonesia" width="30" height="30"
                    class="d-inline-block align-top" style="margin-right: 10px;">
                GPS Tracking
                <svg height="25" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                    <path fill="white"
                        d="M112 0C85.5 0 64 21.5 64 48V96H16c-8.8 0-16 7.2-16 16s7.2 16 16 16H64 272c8.8 0 16 7.2 16 16s-7.2 16-16 16H64 48c-8.8 0-16 7.2-16 16s7.2 16 16 16H64 240c8.8 0 16 7.2 16 16s-7.2 16-16 16H64 16c-8.8 0-16 7.2-16 16s7.2 16 16 16H64 208c8.8 0 16 7.2 16 16s-7.2 16-16 16H64V416c0 53 43 96 96 96s96-43 96-96H384c0 53 43 96 96 96s96-43 96-96h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V288 256 237.3c0-17-6.7-33.3-18.7-45.3L512 114.7c-12-12-28.3-18.7-45.3-18.7H416V48c0-26.5-21.5-48-48-48H112zM544 237.3V256H416V160h50.7L544 237.3zM160 368a48 48 0 1 1 0 96 48 48 0 1 1 0-96zm272 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0z" />
                </svg>
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span id="current-time" class="nav-link"></span>
                </li>
                <li class="nav-item">
                    <div class="logout-container">
                        <form method="post" action="logout.php">
                            <input type="submit" class="logout-button" value="Logout">
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" onclick="showContent('home')">Home</a>
        <a href="#" onclick="showContent('lacak')">Lacak Armada</a>
        <a href="#" onclick="showContent('delivery')">Delivery</a>
        <a href="#" onclick="showContent('armada')">Armada</a>
        <a href="#" onclick="showContent('laporan')">Laporan</a>
        <a href="#" onclick="showContent('gps')">Status GPS</a>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div id="home" class="content-section centered-content">
            <div class="col">
                <div class="row text-center">
                </div>
                <div class="row text-center">
                    <h1>Selamat Datang</h1>
                </div>
                <div class="row text-center">
                    <div class="col">
                        <img src="src/img/logo remove.png" alt="Logo Nihon Seiki Indonesia" width="100" height="100">
                    </div>
                </div>
                <div class="row text-center">
                    <h1>Nihon Seiki Indonesia</h1>
                </div>
            </div>
        </div>

        <div id="lacak" class="content-section d-none">
            <div class="row">
                <div class="col-lg-8">
                    <div id="map"></div>
                </div>
                <div class="col-lg-4">
                    <div class="card" style="margin-top: 10%;">
                        <div class="card-header text-center">
                            <h5 class="card-title">Last Data Received</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width: 20%;">Date</td>
                                            <td>: <a id="time_date"></a></td>
                                        </tr>
                                        <tr>
                                            <td>Time</td>
                                            <td>: <a id="time_time"></a></td>
                                        </tr>
                                        <tr>
                                            <td>Latitude</td>
                                            <td>: <a id="latitude"></a> </td>
                                        </tr>
                                        <tr>
                                            <td>Longitude</td>
                                            <td>: <a id="longitude"></a></td>
                                        </tr>
                                        <tr>
                                            <td>Total Waktu</td>
                                            <td>: <a id="total_waktu">00:00:00</a></td>
                                        </tr>
                                        <tr>
                                            <td>Jarak Tempuh</td>
                                            <td>: <a id="jarak_tempuh">0.0</a> km</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <button id="startPolylineButton" class="btn btn-success m-1">Mulai Pelacakan</button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary" id="resetButton"
                                        style="display: none;">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="delivery" class="content-section d-none">
            <h2>Jadwal Delivery</h2>
            <input type="file" id="upload" accept=".xlsx, .xls">
            <br><br>
            <table id="scheduleTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Kawasan</th>
                        <th>Jadwal Keberangkatan</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan dimasukkan di sini oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Armada Section -->
        <div id="armada" class="content-section d-none">
            <h2>Data Armada</h2>
            <input type="file" id="armada-file-input" accept=".xlsx, .xls">
            <br><br>
            <table id="armadaTable" class="table table-bordered display">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Rute</th>
                        <th>Driver</th>
                        <th>Plat</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody id="armadaBody">
                    <!-- Data akan dimasukkan di sini oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="gps" class="content-section d-none">
            <div class="row">
                <div class="col-lg-12">
                    <h5>Status Armada</h2>
                        <table class="table table-bordered" id="historyTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Total Waktu</th>
                                    <th>Jarak Tempuh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- History data will be appended here -->
                            </tbody>
                        </table>
                        <button class="btn btn-primary mt-2" onclick="exportCSV()">Export as CSV</button>
                </div>
            </div>
        </div>

        <!-- New Laporan Section -->
        <div id="laporan" class="content-section d-none">
            <h2>View Polyline</h2>
            <div id="map-laporan" style="height: 500px;"></div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Waktu Total</th>
                        <th>Jarak Total (km)</th>
                        <th>Color</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="polyline-id"></td>
                        <td id="polyline-timestamp"></td>
                        <td id="polyline-total-time"></td>
                        <td id="polyline-total-distance"></td>
                        <td id="polyline-color"></td>
                        <td id="polyline-start-address"></td>
                        <td id="polyline-end-address"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz4fnFO9gybBogGzF0pLv1SrNx8e0zKCX1ljzJ/epesRjhRZjG1mC9bxFSM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-An6oA18A4I4g1XWr6PpGxMn0cp9f8IVF25dQoNgSthc6v7LMz6wz2QAnHgIksyD2"
        crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <!-- Custom JS -->

    <script>
        var map = L.map('map').setView([0, 0], 10);

        //https://github.com/pointhi/leaflet-color-markers
        var blueIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var yellowIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var orangeIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var initialMarker = L.marker([0, 0], { icon: redIcon });
        var updatedMarker = L.marker([0, 0], { icon: blueIcon });
        var finishMarker = L.marker([0, 0], { icon: greenIcon });
        var polyline = L.polyline([], { color: 'blue' });
        var startPolylineButton = document.getElementById('startPolylineButton');
        var resetButton = document.getElementById('resetButton');
        var isPolylineStarted = false;
        var locationData = [];
        var currentIndex = 0;
        var polylineIndex = 0;
        var stat_btn = 0;
        var timerInterval;
        var startTime, endTime;
        var totalDistance = 0;
        var lastLat, lastLng;

        // Adding a base map layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Generate or retrieve tracking ID
        function getTrackingId() {
            let trackingId = localStorage.getItem('trackingId');
            if (!trackingId) {
                trackingId = 'tracking_' + Date.now(); // Create new tracking ID
                localStorage.setItem('trackingId', trackingId);
            }
            return trackingId;
        }

        function clearTrackingId() {
            localStorage.removeItem('trackingId'); // Clear tracking ID when tracking is done
        }

        const trackingId = getTrackingId();

        function saveLocationData(date_now, time_now, latitude, longitude, total_waktu, jarak_tempuh) {
            const data = {
                date: date_now,
                time: time_now,
                latitude: latitude,
                longitude: longitude,
                total_waktu: total_waktu,
                jarak_tempuh: jarak_tempuh,
                tracking_id: trackingId
            };

            fetch('save_location_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(data)
            })
                .then(response => response.text())
                .then(result => {
                    console.log(result);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        fetch('get_location_data.php')
            .then(response => response.json())
            .then(data => {
                for (const [trackingId, coords] of Object.entries(data)) {
                    var latlngs = coords.map(coord => [parseFloat(coord.latitude), parseFloat(coord.longitude)]);
                    var polyline = L.polyline(latlngs, { color: getRandomColor() }).addTo(map);
                    map.fitBounds(polyline.getBounds());
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        function updateMap(data) {
            var latitude = parseFloat(data.field1);
            var longitude = parseFloat(data.field2);
            var date_now = data.created_at.split("T")[0];
            var time_now = data.created_at.split("T")[1].split("Z")[0];

            if (lastLat !== undefined && lastLng !== undefined) {
                totalDistance += calculateDistance(lastLat, lastLng, latitude, longitude);
            }

            lastLat = latitude;
            lastLng = longitude;

            document.getElementById('latitude').textContent = latitude;
            document.getElementById('longitude').textContent = longitude;
            document.getElementById('time_date').textContent = date_now;
            document.getElementById('time_time').textContent = time_now;
            document.getElementById('jarak_tempuh').textContent = totalDistance.toFixed(2);

            // Update marker position
            updateMarker(latitude, longitude, date_now, time_now);
            if (stat_btn == 1) {
                // Update polyline
                polyline.addLatLng([latitude, longitude]);
                polyline.addTo(map);
                // Save to database
                saveLocationData(date_now, time_now, latitude, longitude, document.getElementById('total_waktu').textContent, totalDistance.toFixed(2));
            }

            // Center the map on the updated location
            map.panTo([latitude, longitude]);

            // Append data to history table
            var table = $('#historyTable').DataTable();
            table.row.add([
                '', // This column will be filled by DataTables with row numbers
                date_now,
                time_now,
                latitude,
                longitude,
                document.getElementById('total_waktu').textContent,
                totalDistance.toFixed(2)
            ]).draw(false);
        }

        function saveLocationData(date_now, time_now, latitude, longitude, total_waktu, jarak_tempuh) {
            fetch('save_location_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `date=${date_now}&time=${time_now}&latitude=${latitude}&longitude=${longitude}&total_waktu=${total_waktu}&jarak_tempuh=${jarak_tempuh}`
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error saving data:', error);
                });
        }

        function stopTracking() {
            stat_btn = 0; // Set the tracking status to stopped
            var latLngs = polyline.getLatLngs(); // Get all coordinates from the polyline
            var coordinates = latLngs.map(function (latlng) {
                return { lat: latlng.lat, lng: latlng.lng };
            });
            savePolylineData(coordinates);
        }

        function savePolylineData(coordinates) {
            fetch('save_polyline_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(coordinates)
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error saving polyline data:', error);
                });
        }

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        function createLabelMarker(latlng, label) {
            var icon = L.divIcon({
                className: 'label-marker',
                html: label,
                iconSize: [50, 20] // Size of the label box
            });
            return L.marker(latlng, { icon: icon });
        }
        function loadPolylineData() {
            fetch('load_polyline_data.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach((polylineData, index) => {
                        var coordinates = polylineData.map(function (coord) {
                            return [coord.lat, coord.lng];
                        });
                        var color = getRandomColor();
                        var polyline = L.polyline(coordinates, { color: color }).addTo(map);

                        // Create a label marker at the midpoint of the polyline
                        var midIndex = Math.floor(coordinates.length / 2);
                        var midpoint = coordinates[midIndex];
                        var labelMarker = createLabelMarker(midpoint, 'Delivery ' + (index + 1));
                        labelMarker.addTo(map);
                    });
                })
                .catch(error => {
                    console.error('Error loading polyline data:', error);
                });
        }
        // Panggil fungsi ini saat halaman dimuat atau saat menu lokasi dibuka
        window.onload = function () {
            loadPolylineData();
        };

        // Initializing DataTables 10 layar
        $(document).ready(function () {
            var table = $('#historyTable').DataTable({
                scrollY: '400px',
                scrollCollapse: true,
                paging: false,
                order: [[1, 'desc']], // Order by date (assuming date is the second column)
                pageLength: 10,
                columnDefs: [
                    {
                        targets: 0,
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    }
                ],
            });

            function updateTable(data) {
                var date_now = data.created_at.split("T")[0];
                var time_now = data.created_at.split("T")[1].split("Z")[0];
                var latitude = parseFloat(data.field1);
                var longitude = parseFloat(data.field2);
                var total_waktu = data.field3;
                var jarak_tempuh = data.field4;

                table.row.add([
                    '', // This column will be filled by DataTables with row numbers
                    date_now,
                    time_now,
                    latitude,
                    longitude,
                    total_waktu,
                    jarak_tempuh
                ]).draw(false);
            }

            function fetchData() {
                // Make an API request to obtain location data
                fetch('https://api.thingspeak.com/channels/2567587/feeds/last.json?timezone=Asia%2FJakarta&api_key=K9C1897UZPVDX6RQ')
                    .then(response => response.json())
                    .then(data => {
                        updateMap(data);
                        updateTable(data); // Update DataTable
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Initial fetch when the page loads
            fetchData();

            // Update the map every X milliseconds
            setInterval(fetchData, 1500); // Set the interval according to your requirements
        });

        function updateMarker(latitude, longitude, date_now, time_now) {
            var customPopup = "<b>Stored Data<br>" + date_now + " " + time_now + "</b>";
            var marker;

            if (stat_btn == 0) {
                marker = initialMarker.addTo(map);
            }
            if (stat_btn == 1) {
                marker = updatedMarker.addTo(map);
            }
            if (stat_btn == 2) {
                marker = finishMarker.addTo(map);
            }
            marker.setLatLng([latitude, longitude]).update();
            marker.bindPopup(customPopup).addTo(map);
            map.setView([latitude, longitude], map.getZoom());
        }

        function updatePolyline(data) {
            var coordinates = data.map(location => [location.lat, location.lng]);
            polyline.setLatLngs(coordinates);
            if (stat_btn == 1) {
                polyline.addTo(map);
            }
        }

        function startPolyline() {
            if (stat_btn == 0) {
                stat_btn = 1;
                polylineIndex = currentIndex;
                startPolylineButton.textContent = 'Stop Pelacakan';
                startPolylineButton.classList.remove('btn-success');
                startPolylineButton.classList.add('btn-danger');
                resetButton.style.display = 'none'; // Hide reset button when starting polyline
                startTime = new Date(); // Set start time
                timerInterval = setInterval(updateTimer, 1000); // Start timer
                document.getElementById('total_waktu').textContent = "00:00:00"; // Reset start time display
                document.getElementById('jarak_tempuh').textContent = "0.0"; // Reset distance display
            } else if (stat_btn == 1) {
                stat_btn = 0;
                startPolylineButton.textContent = 'Mulai Pelacakan';
                startPolylineButton.classList.remove('btn-danger');
                startPolylineButton.classList.add('btn-success');
                resetButton.style.display = 'block'; // Show reset button when stopping polyline
                clearInterval(timerInterval); // Stop timer
                endTime = new Date(); // Set end time
                var totalTime = calculateTotalTime(startTime, endTime); // Calculate total time
                document.getElementById('total_waktu').textContent = totalTime; // Set total time display
                stopTracking();
            } else {
                stat_btn = 0;
                startPolylineButton.textContent = 'Start Polyline';
                startPolylineButton.classList.remove('btn-default');
                startPolylineButton.classList.add('btn-success');
            }
            console.log('Button Status:', stat_btn);

        }

        function resetMap() {
            stat_btn = 0;
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
            startPolylineButton.disabled = false;
            document.getElementById('latitude').textContent = "";
            document.getElementById('longitude').textContent = "";
            document.getElementById('time_date').textContent = "";
            document.getElementById('time_time').textContent = "";
            document.getElementById('total_waktu').textContent = "00:00:00";
            document.getElementById('jarak_tempuh').textContent = "0.0";
            initialMarker = L.marker([0, 0], { icon: redIcon });
            updatedMarker = L.marker([0, 0], { icon: blueIcon });
            finishMarker = L.marker([0, 0], { icon: greenIcon });
            polyline = L.polyline([], { color: 'blue' });
            resetButton.style.display = 'none';
            fetchData(); // Fetch new location data after reset
        }

        function exportCSV() {
            const filename = 'historyTable.csv';
            fetch('https://api.thingspeak.com/channels/2567587/feeds.json?timezone=Asia%2FJakarta&api_key=K9C1897UZPVDX6RQ')
                .then(response => response.json())
                .then(data => {
                    const csv = jsonToCSV(data.feeds);
                    downloadCSV(csv, filename);
                })
                .catch(error => console.error('error fetching data:', error));
        }

        // Function to convert JSON to CSV
        function jsonToCSV(jsonData) {
            if (!jsonData || jsonData.length === 0) {
                return '';
            }

            const keys = Object.keys(jsonData[0]);
            const csvRows = [];

            // Header row
            csvRows.push(keys.join(','));

            // Data rows
            for (const row of jsonData) {
                const values = keys.map(key => {
                    let value = row[key];
                    if (key === 'created_at') {
                        value = value.replace('T', ' ');
                    }
                    return value;
                });
                csvRows.push(values.join(','));
            }

            return csvRows.join('\n');
        }

        // Function to download CSV file
        function downloadCSV(csv, filename) {
            const csvFile = new Blob([csv], { type: 'text/csv' });
            const downloadLink = document.createElement('a');

            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';

            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Timer functions
        function updateTimer() {
            var now = new Date();
            var elapsedTime = new Date(now - startTime);
            document.getElementById('total_waktu').textContent = formatTime(elapsedTime);
        }

        function formatTime(date) {
            return date.toISOString().substr(11, 8); // Format as HH:MM:SS
        }

        function calculateTotalTime(start, end) {
            var total = new Date(end - start);
            return formatTime(total);
        }

        function calculateDistance(lat1, lng1, lat2, lng2) {
            function toRadians(degrees) {
                return degrees * Math.PI / 180;
            }

            var R = 6371; // Radius of the Earth in km
            var dLat = toRadians(lat2 - lat1);
            var dLng = toRadians(lng2 - lng1);
            var a = sin(dLat / 2) * sin(dLat / 2) +
                cos(toRadians(lat1)) * cos(toRadians(lat2)) *
                sin(dLng / 2) * sin(dLng / 2);
            var c = 2 * atan2(sqrt(a), sqrt(1 - a));
            var distance = R * c;
            return distance;
        }

        startPolylineButton.addEventListener('click', startPolyline);
        resetButton.addEventListener('click', resetMap);

        function showContent(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('d-none');
            });
            document.getElementById(sectionId).classList.remove('d-none');
            if (sectionId === 'lacak') {
                map.invalidateSize(); // Ensure map renders correctly
            } else if (sectionId === 'laporan') {
                mapLaporan.invalidateSize(); // Ensure map renders correctly
            }
        }

        document.getElementById('upload').addEventListener('change', handleFile, false);

        let globalData = [];
        let table;

        function handleFile(e) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: 'array' });

                const sheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[sheetName];

                const json = XLSX.utils.sheet_to_json(worksheet);

                globalData = json;
                updateScheduleTable(json);
                setInterval(updateStatus, 60000); // Update setiap menit
                updateStatus(); // Panggilan awal untuk mengisi tabel
            };

            reader.readAsArrayBuffer(file);
        }

        function updateScheduleTable(data) {
            if ($.fn.dataTable.isDataTable('#scheduleTable')) {
                table.clear().rows.add(data).draw();
            } else {
                table = $('#scheduleTable').DataTable({
                    scrollY: '400px',
                    scrollCollapse: true,
                    paging: false,
                    order: [
                        [2, 'asc']
                    ],
                    columns: [
                        { data: 'Customer', className: 'customer' }, // Customer
                        { data: 'Kawasan' }, // Kawasan
                        { data: 'jadwal keberangkatan' }, // Jadwal Keberangkatan
                        { data: 'Kendaraan' }, // Kendaraan
                        { data: null, defaultContent: 'Menunggu', className: 'status menunggu' } // Status
                    ]
                });
            }
        }

        function updateStatus() {
            const currentTime = new Date();
            table.rows().every(function() {
                const data = this.data();
                const row = this.node();
                const jadwalCell = data['jadwal keberangkatan'];
                const customerCell = data['Customer'];
                const statusCell = $(row).find('td.status')[0];

                try {
                    const [hour, minute] = jadwalCell.split('.');
                    const scheduleTime = new Date(currentTime);
                    scheduleTime.setHours(parseInt(hour), parseInt(minute), 0, 0);
                    const timeDiff = (scheduleTime - currentTime) / 60000;

                    const previousStatus = statusCell.textContent;
                    $(statusCell).removeClass('selesai lima_menit_lagi tiga_puluh_menit_lagi menunggu');

                    if (timeDiff >= 0 && timeDiff < 15) {
                        statusCell.textContent = '15 menit lagi';
                        $(statusCell).addClass('status lima_menit_lagi');
                        if (previousStatus !== '15 menit lagi') {
                            alert(`Perhatian: Ada pengiriman yang akan berangkat dalam 15 menit!\nPada customer: ${customerCell} di jam: ${jadwalCell}`);
                        }
                    } else if (timeDiff >= 15 && timeDiff < 30) {
                        statusCell.textContent = '30 menit lagi';
                        $(statusCell).addClass('status tiga_puluh_menit_lagi');
                    } else if (timeDiff < 0) {
                        statusCell.textContent = 'Selesai';
                        $(statusCell).addClass('status selesai');
                    } else {
                        statusCell.textContent = 'Menunggu';
                        $(statusCell).addClass('status menunggu');
                    }
                } catch (e) {
                    // Tangani data waktu yang tidak valid
                    statusCell.textContent = 'Menunggu';
                    $(statusCell).addClass('status menunggu');
                }
            });
        }

        function loadArmadaFromExcel(data) {
            try {
                const workbook = XLSX.read(data, { type: 'binary' });
                const sheetName = workbook.SheetNames[0];
                const sheet = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

                console.log(sheet);  // Debug: log the sheet content

                // Membaca header dari sheet untuk memverifikasi kolom
                const headers = sheet[0];
                if (!headers.includes("Hari") || !headers.includes("Rute") || !headers.includes("Driver") || !headers.includes("Plat") || !headers.includes("Aktivitas")) {
                    throw new Error("Nama kolom di file Excel tidak sesuai. Pastikan nama kolom adalah 'Hari', 'Rute', 'Driver', 'Plat', dan 'Aktivitas'.");
                }

                const tbody = document.getElementById('armadaBody');
                tbody.innerHTML = ''; // Clear previous schedule

                // Menampilkan seluruh data dari sheet
                sheet.slice(1).forEach(row => {
                    const rowData = {};
                    headers.forEach((header, index) => {
                        rowData[header] = row[index];
                    });
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${rowData["Hari"]}</td><td>${rowData["Rute"]}</td><td>${rowData["Driver"]}</td><td>${rowData["Plat"]}</td><td>${rowData["Aktivitas"]}</td>`;
                    tbody.appendChild(tr);
                });

                // Menginisialisasi DataTables setelah data dimuat
                if ($.fn.dataTable.isDataTable('#armadaTable')) {
                    $('#armadaTable').DataTable().clear().destroy();
                }
                $('#armadaTable').DataTable({
                    scrollY: '400px',
                    scrollCollapse: true,
                    paging: false
                });
            } catch (error) {
                console.error('Error reading the Excel file:', error);
                alert('Terjadi kesalahan saat membaca file Excel. Pastikan file memiliki format yang benar.');
            }
        }

        function handleArmadaFile(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const data = e.target.result;
                loadArmadaFromExcel(data);
            };
            reader.readAsBinaryString(file);
        }

        window.onload = function() {
            document.getElementById('armada-file-input').addEventListener('change', handleArmadaFile);
        };

        function updateTime() {
            const now = new Date();
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const day = days[now.getDay()];
            const date = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const year = now.getFullYear();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const currentTime = `${day}, ${date}-${month}-${year} ${hours}:${minutes}:${seconds}`;
            document.getElementById('current-time').innerText = currentTime;
        }

        setInterval(updateTime, 1000);
        updateTime(); // panggil fungsi sekali saat halaman dimuat

        // Leaflet map initialization for the Laporan section
        var mapLaporan = L.map('map-laporan').setView([-6.200000, 107.000000], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
        }).addTo(mapLaporan);

        // Function to load and display polyline data
        function loadPolylineData(id, color) {
            fetch(`get_polyline_data.php?id=${id}&color=${color}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        var polyline = data;
                        var latlngs = polyline.polyline.map(function(coord) {
                            return [coord.lat, coord.lng];
                        });
                        var polylineLayer = L.polyline(latlngs, { color: polyline.color }).addTo(mapLaporan);
                        mapLaporan.fitBounds(polylineLayer.getBounds());

                        // Update table with polyline details
                        document.getElementById('polyline-id').textContent = polyline.id;
                        document.getElementById('polyline-timestamp').textContent = polyline.timestamp;
                        document.getElementById('polyline-total-time').textContent = polyline.total_time;
                        document.getElementById('polyline-total-distance').textContent = polyline.total_distance;
                        document.getElementById('polyline-color').textContent = polyline.color;
                        document.getElementById('polyline-start-address').textContent = polyline.start_address;
                        document.getElementById('polyline-end-address').textContent = polyline.end_address;
                    }
                })
                .catch(error => console.error('Error fetching polyline data:', error));
        }

        // Example usage (replace with actual ID and color parameters)
        loadPolylineData(1, '#FF0000');

    </script>
</body>

</html>
