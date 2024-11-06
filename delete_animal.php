<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "DELETE FROM animals WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: view_animals.php?success=1");
            exit;
        } else {
            echo "<p style='color: red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
        }
        $stmt->close();
    }
} else {
    echo "<p style='color: red;'>Error: No ID provided.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Livestock Record</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .confirm-box { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 400px; margin: auto; }
        button { background-color: #f44336; color: white; border: none; padding: 10px; margin-top: 10px; border-radius: 5px; }
        button:hover { background-color: #e53935; }
    </style>
</head>
<body>
    <div class="confirm-box">
        <h2>Delete Livestock Record</h2>
        <p>Are you sure you want to delete this livestock record?</p>
        <form method="POST" action="?id=<?php echo htmlspecialchars($id); ?>">
            <button type="submit">Yes, Delete</button>
            <a href="view_animals.php" style="text-decoration: none; color: #2196F3;">Cancel</a>
        </form>
    </div>
</body>
</html>
