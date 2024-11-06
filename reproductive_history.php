<?php
include 'db_connect.php';

// Fetch animal names for the dropdown
$animals = [];
$result = $conn->query("SELECT id, animal_name FROM animals");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $animals[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $breeding_cycle_start = $_POST['breeding_cycle_start'];
    $breeding_cycle_end = $_POST['breeding_cycle_end'];
    $fertility_status = $_POST['fertility_status'];
    $pregnancy_status = $_POST['pregnancy_status'];
    $birth_date = $_POST['birth_date'];

    $stmt = $conn->prepare("INSERT INTO reproductive_history (animal_id, breeding_cycle_start, breeding_cycle_end, fertility_status, pregnancy_status, birth_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $animal_id, $breeding_cycle_start, $breeding_cycle_end, $fertility_status, $pregnancy_status, $birth_date);

    if ($stmt->execute()) {
        echo "<div class='alert-success'>Reproductive history recorded successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
$conn->close();
?>
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
<h1>Add Reproduction <h1>
</header>
<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class="container">
    <h2>Record Reproductive History</h2>
    <form method="POST" action="">
        <label for="animal_id">Animal Name:</label>
        <select id="animal_id" name="animal_id" required>
            <option value="">Select an animal</option>
            <?php foreach ($animals as $animal): ?>
                <option value="<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['animal_name']); ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="breeding_cycle_start">Breeding Cycle Start:</label>
        <input type="date" id="breeding_cycle_start" name="breeding_cycle_start" required>
        
        <label for="breeding_cycle_end">Breeding Cycle End:</label>
        <input type="date" id="breeding_cycle_end" name="breeding_cycle_end" required>
        
        <label for="fertility_status">Fertility Status:</label>
        <input type="text" id="fertility_status" name="fertility_status" required>
        
        <label for="pregnancy_status">Pregnancy Status:</label>
        <input type="text" id="pregnancy_status" name="pregnancy_status" required>
        
        <label for="birth_date">Birth Date:</label>
        <input type="date" id="birth_date" name="birth_date">
        
        <input type="submit" value="Record History" class="btn-submit">
    </form>
</div>

<a href="display_reproductive_history.php" class="button">View Reproductive History</a>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f8ff;
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #4CAF50;
    }

    label {
        margin-top: 10px;
        display: block;
        font-weight: bold;
    }

    select, input[type="text"], input[type="date"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
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
    }.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF; /* Bootstrap primary color */
    color: white !important;
    text-align: center;
    text-decoration: none !important;
    border-radius: 5px;
    border: none;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #0056b3; /* Darker shade on hover */
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
    margin-right: 20px; /* Space between logo and text */
}

</style>

<?php include 'footer.php'; ?>
