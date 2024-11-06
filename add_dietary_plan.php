<?php
include 'db_connect.php';

// Handle form submission for dietary plans
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $breed = $_POST['breed'];
    $health_status = $_POST['health_status'];
    $productivity_level = $_POST['productivity_level'];
    $feeding_schedule = $_POST['feeding_schedule'];

    $stmt = $conn->prepare("INSERT INTO dietary_plans (animal_id, breed, health_status, productivity_level, feeding_schedule) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $animal_id, $breed, $health_status, $productivity_level, $feeding_schedule);

    if ($stmt->execute()) {
        echo "<div class='alert-success'>Dietary plan created successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
}

// Fetch animals for the dropdown
$animals_sql = "SELECT id, animal_name, breed, age FROM animals ORDER BY animal_name";
$animals_result = $conn->query($animals_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Dietary Plan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            color: #333;
            padding: 20px;
        }
        .container {
            margin: 20px auto;
            max-width: 800px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0056b3;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: #0056b3;
            outline: none;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
            width: 100%;
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
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            background-color: #007bff;
            color: white !important;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none !important;
            transition: background-color 0.3s;
            margin: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
.header {
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
</head>
<body>

<!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Create Dietary Plan</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

    <div class="container">
        <h2>Create Dietary Plan</h2>
        <form method="POST" action="">
            <label for="animal_id">Animal Name:</label>
            <select id="animal_id" name="animal_id" required onchange="updateDetails()">
                <option value="">Select an animal</option>
                <?php while ($animal = $animals_result->fetch_assoc()): ?>
                    <option value="<?php echo $animal['id']; ?>" data-breed="<?php echo $animal['breed']; ?>" data-age="<?php echo $animal['age']; ?>">
                        <?php echo htmlspecialchars($animal['animal_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="breed">Breed:</label>
            <input type="text" id="breed" name="breed" readonly required>

            <label for="age">Age (in years):</label>
            <input type="text" id="age" name="age" readonly required>

            <label for="health_status">Health Status:</label>
            <input type="text" id="health_status" name="health_status" required>

            <label for="productivity_level">Productivity Level:</label>
            <input type="text" id="productivity_level" name="productivity_level" required>

            <label for="feeding_schedule">Feeding Schedule:</label>
            <textarea id="feeding_schedule" name="feeding_schedule" required></textarea>

            <input type="submit" value="Create Plan">
        </form>

        <div class="button-container">
            <a href="display_dietary_plans.php" class="button">View Dietary Plans</a>
        </div>
    </div>


<?php include 'footer.php'; ?>
    <script>
        function updateDetails() {
            const animalSelect = document.getElementById('animal_id');
            const selectedOption = animalSelect.options[animalSelect.selectedIndex];
            const breedInput = document.getElementById('breed');
            const ageInput = document.getElementById('age');

            if (selectedOption) {
                breedInput.value = selectedOption.getAttribute('data-breed');
                ageInput.value = selectedOption.getAttribute('data-age');
            } else {
                breedInput.value = '';
                ageInput.value = '';
            }
        }
    </script>
</body>
</html>
