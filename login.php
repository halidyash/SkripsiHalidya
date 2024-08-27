<?php
session_start();
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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa keberadaan user
    $sql = "SELECT * FROM tbl_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
            header("Location: index.php?error=" . urlencode($error));
            exit();
        }
    } else {
        $error = "Username tidak ditemukan!";
        header("Location: index.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$conn->close();
?>
