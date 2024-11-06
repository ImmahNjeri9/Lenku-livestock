<?php
include 'db_connect.php';

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_id = filter_var($_POST['animal_id'], FILTER_SANITIZE_NUMBER_INT);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);

    $sql = "UPDATE movement_tracking SET animal_id = ?, location = ?, action = ?, movement_date = ?, reason = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssi", $animal_id, $location, $action, $movement_date, $reason, $id);

    if ($stmt->execute()) {
        header("Location: display_movement_tracking.php?success=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

// Fetch existing movement details
$sql = "SELECT * FROM movement_tracking WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movement = $result->fetch_assoc();

$sql = "SELECT id, animal_name FROM animals";
$animals_result = $conn->query($sql);
$animals = $animals_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movement Tracking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Edit Movement Tracking</h2>
    <form method="POST" action="">
    <label for="animal_id">Select Animal</label>
    <select name="animal_id" required>
        <?php foreach ($animals as $animal): ?>
            <option value="<?php echo $animal['id']; ?>" <?php echo ($animal['id'] == $movement['animal_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($animal['animal_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="location">Location</label>
    <input type="text" name="location" value="<?php echo htmlspecialchars($movement['location']); ?>" required>

    <label for="action">Action</label>
    <input type="text" name="action" value="<?php echo htmlspecialchars($movement['action']); ?>" required>

    <label for="movement_date">Date (YYYY-MM-DD)</label>
    <input type="movement_date" name="movement_date" value="<?php echo htmlspecialchars($movement['movement_date']); ?>" required>

    <label for="reason">Reason</label>
    <input type="text" name="reason" value="<?php echo htmlspecialchars($movement['reason']); ?>" required>

    <button type="submit">Update Movement</button>
</form>

</body>
</html>
