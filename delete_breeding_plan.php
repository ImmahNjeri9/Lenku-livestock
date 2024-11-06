<?php
include 'db_connect.php';

// Check if the ID is set in the query string
if (!isset($_GET['id'])) {
    die("Error: No ID provided.");
}

$id = $_GET['id'];

// Delete the breeding plan
$stmt = $conn->prepare("DELETE FROM breeding_plans WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<div class='alert-success'>Breeding plan deleted successfully!</div>";
} else {
    echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
}

$stmt->close();
$conn->close();
?>

<div style="text-align: center; margin-top: 20px;">
    <a href="display_breeding_plans.php" class="button">Back to Breeding Plans</a>
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
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    h2 {
        text-align: center;
        color: #4CAF50;
    }

    .search-form {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    input[type="text"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: calc(70% - 20px);
        margin-right: 10px;
    }

    input[type="submit"] {
        background-color: #28a745;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 16px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
        margin: 5px; /* Space between buttons */
    }

    .button:hover {
        background-color: #0056b3;
    }

    /* Style for the button container */
    .button-container {
        text-align: center;
        margin-top: 20px;
    }
</style>
