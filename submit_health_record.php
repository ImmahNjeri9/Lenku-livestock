<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];
    $illness = $_POST['illness'];
    $treatment = $_POST['treatment'];
    $vaccination = $_POST['vaccination'];
    $recorded_at = $_POST['recorded_at'];
    $notes = $_POST['notes'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO health_records (animal_id, illness, treatment, vaccination, recorded_at, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $animal_id, $illness, $treatment, $vaccination, $recorded_at, $notes);

    if ($stmt->execute()) {
        echo "Health record added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
