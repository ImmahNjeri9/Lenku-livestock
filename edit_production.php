<?php 
include 'db_connect.php';

// Fetch the record to edit
$id = $_GET['id'];
$sql = "SELECT * FROM production_data WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

// Fetch animal names for the dropdown
$animals_sql = "SELECT id, animal_name FROM animals ORDER BY animal_name";
$animals_result = $conn->query($animals_sql);

// Handle form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $production_type = $_POST['production_type'];
    $production_date = $_POST['production_date'];
    $quantity = $_POST['quantity'];
    $comments = $_POST['comments'];

    $update_stmt = $conn->prepare("UPDATE production_data SET animal_id = ?, production_type = ?, production_date = ?, quantity = ?, comments = ? WHERE id = ?");
    $update_stmt->bind_param("issssi", $animal_id, $production_type, $production_date, $quantity, $comments, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert-success'>Production data updated successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}
?>

<div class="container">
    <h2>Edit Production Data</h2>
    <form method="POST" action="">
        <label for="animal_id">Animal Name:</label>
        <select id="animal_id" name="animal_id" required>
            <?php while ($animal = $animals_result->fetch_assoc()): ?>
                <option value="<?php echo $animal['id']; ?>" <?php if ($animal['id'] == $record['animal_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($animal['animal_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <label for="production_type">Production Type:</label>
        <select id="production_type" name="production_type" required>
            <option value="Milk" <?php if ($record['production_type'] == 'Milk') echo 'selected'; ?>>Milk</option>
            <option value="Meat" <?php if ($record['production_type'] == 'Meat') echo 'selected'; ?>>Meat</option>
            <option value="Wool" <?php if ($record['production_type'] == 'Wool') echo 'selected'; ?>>Wool</option>
        </select>

        <label for="production_date">Production Date:</label>
        <input type="date" id="production_date" name="production_date" value="<?php echo htmlspecialchars($record['production_date']); ?>" required>

        <label for="quantity">Quantity Produced:</label>
        <input type="number" id="quantity" name="quantity" step="0.01" value="<?php echo htmlspecialchars($record['quantity']); ?>" required>

        <label for="comments">Comments:</label>
        <textarea id="comments" name="comments"><?php echo htmlspecialchars($record['comments']); ?></textarea>

        <input type="submit" value="Update Production" class="button">
    </form>
</div>

<style>
    /* Include the same styles as before for consistency */
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
    input[type="date"],
    input[type="number"],
    select,
    textarea {
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
