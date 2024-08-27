<?php
// Konfigurasi basis data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gps_tracking";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil semua data polyline dari basis data
$sql = "SELECT polyline FROM polyline_data";
$result = $conn->query($sql);

$polylines = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $polylines[] = json_decode($row["polyline"], true);
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($polylines);

$conn->close();
?>
