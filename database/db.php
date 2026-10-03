<?php
// ============================
// Database Configuration
// ============================

$host = "localhost";
$username = "root";
$password = "";
$database = "awi_direction_db";

// ============================
// Create Connection
// ============================

$conn = mysqli_connect($host, $username, $password, $database);

// ============================
// Check Connection
// ============================

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Optional: Set character encoding
mysqli_set_charset($conn, "utf8");
?>