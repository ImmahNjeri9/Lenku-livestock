<?php
include 'db_connect.php';

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_id = filter_var($_POST['animal_id'], FILTER_SANITIZE_NUMBER_INT);
    $identification_type = filter_var($_POST['identification_type'], FILTER_SANITIZE_STRING);
    $identification_value = filter_var($_POST['identification_value'], FILTER_SANITIZE_STRING);

    $sql = "UPDATE animal_identification SET animal_id = ?, identification_type = ?, identification_value = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $animal_id, $identification_type, $identification_value, $id);

    if ($stmt->execute()) {
        header("Location: display_animal_identification.php?success=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

// Fetch existing identification details
$sql = "SELECT * FROM animal_identification WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$identification = $result->fetch_assoc();

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
    <title>Edit Animal Identification</title>
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
    <h2>Edit Animal Identification</h2>
    <form method="POST" action="">
        <label for="animal_id">Select Animal</label>
        <select name="animal_id" required>
            <?php foreach ($animals as $animal): ?>
                <option value="<?php echo $animal['id']; ?>" <?php echo ($animal['id'] == $identification['animal_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($animal['animal_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="identification_type">Identification Type</label>
        <input type="text" name="identification_type" value="<?php echo htmlspecialchars($identification['identification_type']); ?>" required>

        <label for="identification_value">Identification Value</label>
        <input type="text" name="identification_value" value="<?php echo htmlspecialchars($identification['identification_value']); ?>" required>

        <button type="submit">Update Identification</button>
    </form>
</body>
</html>
