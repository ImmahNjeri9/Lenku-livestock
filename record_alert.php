<?php
// Include your database connection function
include 'db_connect.php';

// Connect to the database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $alert_message = $_POST['alert_message'];
    $alert_date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO `alerts` (animal_id, alert_message, alert_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $animal_id, $alert_message, $alert_date);

    if ($stmt->execute()) {
        echo "<div class='alert-success'>New alert recorded successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- HTML form for recording alerts -->
<form method="POST" action="" class="alert-form">
    <h2>Record New Alert</h2>
    <label for="animal_id">Animal ID:</label>
    <input type="number" id="animal_id" name="animal_id" required>
    
    <label for="alert_message">Alert Message:</label>
    <textarea id="alert_message" name="alert_message" required></textarea>
    
    <input type="submit" value="Record Alert" class="btn-submit">
</form>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f8ff;
        color: #333;
        padding: 20px;
    }

    .alert-form {
        background-color: #ffebcd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    h2 {
        color: #ff4500;
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    input[type="number"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .btn-submit {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #218838;
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
</style>
