<?php
include 'db_connect.php';

// Fetch the record to edit
$id = $_GET['id'];
$sql = "SELECT * FROM reproductive_history WHERE id = ?";
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
    $breeding_cycle_start = $_POST['breeding_cycle_start'];
    $breeding_cycle_end = $_POST['breeding_cycle_end'];
    $fertility_status = $_POST['fertility_status'];
    $pregnancy_status = $_POST['pregnancy_status'];
    $birth_date = $_POST['birth_date'];

    $update_stmt = $conn->prepare("UPDATE reproductive_history SET animal_id = ?, breeding_cycle_start = ?, breeding_cycle_end = ?, fertility_status = ?, pregnancy_status = ?, birth_date = ? WHERE id = ?");
    $update_stmt->bind_param("isssssi", $animal_id, $breeding_cycle_start, $breeding_cycle_end, $fertility_status, $pregnancy_status, $birth_date, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert-success'>Reproductive history updated successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}
?>

<div class="container">
    <h2>Edit Reproductive History</h2>
    <form method="POST" action="">
        <label for="animal_id">Animal Name:</label>
        <select id="animal_id" name="animal_id" required>
            <?php while ($animal = $animals_result->fetch_assoc()): ?>
                <option value="<?php echo $animal['id']; ?>" <?php if ($animal['id'] == $record['animal_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($animal['animal_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <label for="breeding_cycle_start">Breeding Cycle Start:</label>
        <input type="date" id="breeding_cycle_start" name="breeding_cycle_start" value="<?php echo htmlspecialchars($record['breeding_cycle_start']); ?>" required>
        
        <label for="breeding_cycle_end">Breeding Cycle End:</label>
        <input type="date" id="breeding_cycle_end" name="breeding_cycle_end" value="<?php echo htmlspecialchars($record['breeding_cycle_end']); ?>" required>
        
        <label for="fertility_status">Fertility Status:</label>
        <input type="text" id="fertility_status" name="fertility_status" value="<?php echo htmlspecialchars($record['fertility_status']); ?>" required>
        
        <label for="pregnancy_status">Pregnancy Status:</label>
        <input type="text" id="pregnancy_status" name="pregnancy_status" value="<?php echo htmlspecialchars($record['pregnancy_status']); ?>" required>
        
        <label for="birth_date">Birth Date:</label>
        <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($record['birth_date']); ?>">
        
        <input type="submit" value="Update History">
    </form>
</div>

<style>
    /* Include the same styles as in the previous file for consistency */
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
