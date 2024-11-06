<?php
include 'db_connect.php';

// Fetch the record to edit
$id = $_GET['id'];
$sql = "SELECT * FROM dietary_plans WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

// Fetch animals for the dropdown
$animals_sql = "SELECT id, animal_name FROM animals ORDER BY animal_name";
$animals_result = $conn->query($animals_sql);

// Handle form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $health_status = $_POST['health_status'];
    $productivity_level = $_POST['productivity_level'];
    $feeding_schedule = $_POST['feeding_schedule'];

    $update_stmt = $conn->prepare("UPDATE dietary_plans SET animal_id = ?, breed = ?, age = ?, health_status = ?, productivity_level = ?, feeding_schedule = ? WHERE id = ?");
    $update_stmt->bind_param("isssssi", $animal_id, $breed, $age, $health_status, $productivity_level, $feeding_schedule, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert-success'>Dietary plan updated successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dietary Plan</title>
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
        h2 {
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
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
</head>
<body>
    <div class="container">
        <h2>Edit Dietary Plan</h2>
        <form method="POST" action="">
            <label for="animal_id">Animal Name:</label>
            <select id="animal_id" name="animal_id" required>
                <?php while ($animal = $animals_result->fetch_assoc()): ?>
                    <option value="<?php echo $animal['id']; ?>" <?php if ($animal['id'] == $record['animal_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($animal['animal_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="breed">Breed:</label>
            <input type="text" id="breed" name="breed" value="<?php echo htmlspecialchars($record['breed']); ?>" required>

            <label for="age">Age (in years):</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($record['age']); ?>" required>

            <label for="health_status">Health Status:</label>
            <input type="text" id="health_status" name="health_status" value="<?php echo htmlspecialchars($record['health_status']); ?>" required>

            <label for="productivity_level">Productivity Level:</label>
            <input type="text" id="productivity_level" name="productivity_level" value="<?php echo htmlspecialchars($record['productivity_level']); ?>" required>

            <label for="feeding_schedule">Feeding Schedule:</label>
            <textarea id="feeding_schedule" name="feeding_schedule" required><?php echo htmlspecialchars($record['feeding_schedule']); ?></textarea>

            <input type="submit" value="Update Plan">
        </form>
    </div>
</body>
</html>
