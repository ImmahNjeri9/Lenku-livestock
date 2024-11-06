<?php
include 'db_connect.php';

// Check if the ID is set in the query string
if (!isset($_GET['id'])) {
    die("Error: No ID provided.");
}

$id = $_GET['id'];

// Fetch the existing breeding plan
$stmt = $conn->prepare("SELECT * FROM breeding_plans WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Breeding plan not found.");
}

$breeding_plan = $result->fetch_assoc();

// Fetch all animals for the dropdown
$animals_result = $conn->query("SELECT id, animal_name FROM animals");
$animals = [];
while ($row = $animals_result->fetch_assoc()) {
    $animals[] = $row;
}

// Handle form submission for editing the breeding plan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $planned_mating_date = $_POST['planned_mating_date'];
    $genetics_info = $_POST['genetics_info'];
    $productivity_data = $_POST['productivity_data'];
    $health_data = $_POST['health_data'];

    $update_stmt = $conn->prepare("UPDATE breeding_plans SET animal_id = ?, planned_mating_date = ?, genetics_info = ?, productivity_data = ?, health_data = ? WHERE id = ?");
    $update_stmt->bind_param("issssi", $animal_id, $planned_mating_date, $genetics_info, $productivity_data, $health_data, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert-success'>Breeding plan updated successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}

?>

<div class="container">
    <h2>Edit Breeding Plan</h2>
    <form method="POST" action="">
        <label for="animal_id">Animal Name:</label>
        <select id="animal_id" name="animal_id" required>
            <option value="">Select an Animal</option>
            <?php foreach ($animals as $animal): ?>
                <option value="<?php echo $animal['id']; ?>" <?php echo ($animal['id'] == $breeding_plan['animal_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($animal['animal_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label for="planned_mating_date">Planned Mating Date:</label>
        <input type="date" id="planned_mating_date" name="planned_mating_date" value="<?php echo htmlspecialchars($breeding_plan['planned_mating_date']); ?>" required>
        
        <label for="genetics_info">Genetics Info:</label>
        <textarea id="genetics_info" name="genetics_info" required><?php echo htmlspecialchars($breeding_plan['genetics_info']); ?></textarea>
        
        <label for="productivity_data">Productivity Data:</label>
        <textarea id="productivity_data" name="productivity_data" required><?php echo htmlspecialchars($breeding_plan['productivity_data']); ?></textarea>
        
        <label for="health_data">Health Data:</label>
        <textarea id="health_data" name="health_data" required><?php echo htmlspecialchars($breeding_plan['health_data']); ?></textarea>
        
        <input type="submit" value="Update Breeding Plan" class="button">
    </form>
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

    label {
        display: block;
        margin: 10px 0 5px;
    }

    select, input[type="date"], textarea {
        width: calc(100% - 20px);
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
        margin-top: 10px; /* Space above the button */
    }

    .button:hover {
        background-color: #0056b3;
    }
</style>

<?php
$conn->close();
?>
