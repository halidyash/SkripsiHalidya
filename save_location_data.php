<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gps_tracking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $tracking_id = $_POST['tracking_id']; // Receive tracking_id from client

    $sql = "INSERT INTO location_data (date, time, latitude, longitude, jam_mulai, jam_selesai, tracking_id)
            VALUES ('$date', '$time', '$latitude', '$longitude', '$jam_mulai', '$jam_selesai', '$tracking_id')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

