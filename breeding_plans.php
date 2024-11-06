<?php
include 'db_connect.php';

// Initialize variables for form data
$animal_id = "";
$planned_mating_date = "";
$genetics_info = "";
$productivity_data = "";
$health_data = "";
$message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $planned_mating_date = $_POST['planned_mating_date'];
    $genetics_info = $_POST['genetics_info'];
    $productivity_data = $_POST['productivity_data'];
    $health_data = $_POST['health_data'];

    // Prepare and execute the SQL insert statement
    $stmt = $conn->prepare("INSERT INTO breeding_plans (animal_id, planned_mating_date, genetics_info, productivity_data, health_data) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $animal_id, $planned_mating_date, $genetics_info, $productivity_data, $health_data);

    if ($stmt->execute()) {
        $message = "Breeding plan added successfully!";
    } else {
        $message = "Error adding breeding plan: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Add Breeding Plan</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class='container'>
    <h2>Add a New Breeding Plan</h2>

    <!-- Display message if any -->
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Form to add breeding plan -->
    <form method='POST' action='' class='breeding-form'>
        <label for="animal_id">Animal:</label>
        <select name='animal_id' required>
            <option value="">Select an animal</option>
            <?php
            // Fetch available animals from the database
            $animal_result = $conn->query("SELECT id, animal_name FROM animals");
            while ($row = $animal_result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['animal_name']) . "</option>";
            }
            ?>
        </select>

        <label for="planned_mating_date">Planned Mating Date:</label>
        <input type='date' name='planned_mating_date' required value='<?php echo htmlspecialchars($planned_mating_date); ?>'>

        <label for="genetics_info">Genetics Info:</label>
        <textarea name='genetics_info' required><?php echo htmlspecialchars($genetics_info); ?></textarea>

        <label for="productivity_data">Productivity Data:</label>
        <textarea name='productivity_data' required><?php echo htmlspecialchars($productivity_data); ?></textarea>

        <label for="health_data">Health Data:</label>
        <textarea name='health_data' required><?php echo htmlspecialchars($health_data); ?></textarea>

        <input type='submit' value='Add Breeding Plan' class='button'>
    </form>

    <div class='button-container'>
        <a href='display_breeding_plans.php' class='button'>View Breeding Plans</a>
    </div>
</div>

<?php $conn->close(); ?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f8ff;
        color: #333;
        padding: 20px;
    }

    .container {
        margin: 20px auto;
        max-width: 1000px;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    h2 {
        text-align: center;
        color: #4CAF50;
    }

    .breeding-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
    }

    input[type="text"],
    input[type="date"],
    select,
    textarea {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: calc(70% - 20px);
        margin-bottom: 15px;
    }

    input[type="submit"] {
        background-color: #28a745;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
    }

    .button {
        background-color: #007bff;
        color: white !important;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none !important;
        transition: background-color 0.3s;
        margin: 5px;
    }

    .button:hover {
        background-color: #0056b3;
    }

    .button-container {
        text-align: center;
        margin-top: 20px;
    }.header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Distribute space between items */
    padding: 20px;
}

.header img {
    height: 80px;
    margin-right: 10px; /* Space between logo and text */
}

.header span {
    font-family: 'Berlin Sans FB';
    font-size: 1.5rem;
    color: white;
    margin-left: -20px;
}

.header h1 {
    flex-grow: 1; /* Allow h1 to take available space */
    margin-right: 250px; /* Space between logo and text */
}

</style>

<?php include 'footer.php'; ?>
