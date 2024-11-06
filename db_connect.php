<?php
$servername = "localhost";
$username = "root";  // Default XAMPP MySQL username
$password = "";  // Default no password for MySQL in XAMPP
$dbname = "livestock_db";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
