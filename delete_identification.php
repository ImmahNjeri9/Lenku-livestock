<?php
include 'db_connect.php';

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "DELETE FROM animal_identification WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: display_animal_identification.php?success=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Animal Identification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .confirm-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="confirm-box">
        <h2>Delete Animal Identification</h2>
        <p>Are you sure you want to delete this identification?</p>
        <form method="POST" action="">
            <button type="submit">Yes, Delete</button>
            <a href="display_animal_identification.php" style="text-decoration: none; color: #2196F3;">Cancel</a>
        </form>
    </div>
</body>
</html>
