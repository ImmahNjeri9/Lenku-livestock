<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_id = filter_var($_POST['animal_id'], FILTER_SANITIZE_NUMBER_INT);
    $identification_type = filter_var($_POST['identification_type'], FILTER_SANITIZE_STRING);
    $identification_value = filter_var($_POST['identification_value'], FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO animal_identification (animal_id, identification_type, identification_value) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $animal_id, $identification_type, $identification_value);

    if ($stmt->execute()) {
        header("Location: display_animal_identification.php?success=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

// Fetch animals for dropdown
$sql = "SELECT id, animal_name FROM animals";
$result = $conn->query($sql);
$animals = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Animal Identification</title>
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
        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body> <header>
        <h1>Record Livestock Identification</h1>
    </header>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>


    <h2>Add Animal Identification</h2>
    <form method="POST" action="">
        <label for="animal_id">Select Animal</label>
        <select name="animal_id" required>
            <option value="">Select Animal</option>
            <?php foreach ($animals as $animal): ?>
                <option value="<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['animal_name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="identification_type">Identification Type</label>
        <input type="text" name="identification_type" placeholder="e.g., RFID, Ear Tag" required>

        <label for="identification_value">Identification Value</label>
        <input type="text" name="identification_value" placeholder="e.g., RFID Number" required>

        <button type="submit">Add Identification</button>
    </form>  <a href="display_animal_identification.php" class="button">View Livestock Identification</a>

</body>
</html>
