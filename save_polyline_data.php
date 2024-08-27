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

// Mendapatkan data JSON dari request body
$input = file_get_contents("php://input");
$polyline = json_encode(json_decode($input));

// Memeriksa apakah data polyline tidak kosong
if (!empty($polyline)) {
    // Menyimpan data polyline ke basis data
    $stmt = $conn->prepare("INSERT INTO polyline_data (polyline) VALUES (?)");
    $stmt->bind_param("s", $polyline);

    if ($stmt->execute()) {
        echo "Data polyline berhasil disimpan.";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Data polyline tidak ditemukan.";
}

$conn->close();
?>
