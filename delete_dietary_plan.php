<?php
include 'db_connect.php';

// Handle deletion
$id = $_GET['id'];
$sql = "DELETE FROM dietary_plans WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<div class='alert-success'>Dietary plan deleted successfully!</div>";
} else {
    echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
}

$stmt->close();
?>

<div class="container">
    <h2>Dietary Plan Deletion</h2>
    <a href="display_dietary_plans.php" class="button">Back to Dietary Plans</a>
</div>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f8ff;
        color: #333;
        padding: 20px;
    }
    .container {
        margin: 20px auto;
        max-width: 800px;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .alert-success, .alert-error {
        margin: 15px 0;
        padding: 10px;
        border-radius: 4px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .button:hover {
        background-color: #0056b3;
    }
</style>
