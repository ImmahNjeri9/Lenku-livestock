<?php
include 'db_connect.php';

// Handle form submission for adding production data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal_id = $_POST['animal_id'];
    $production_type = $_POST['production_type'];
    $production_date = $_POST['production_date'];
    $quantity = $_POST['quantity'];
    $comments = $_POST['comments'];

    // Insert new production record
    $stmt = $conn->prepare("INSERT INTO production_data (animal_id, production_type, production_date, quantity, comments) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $animal_id, $production_type, $production_date, $quantity, $comments);

    if ($stmt->execute()) {
        echo "<div class='alert-success'>Production data recorded successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Add Production Data</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class='container'>
    <h2>New Production Record</h2>

    <form method="POST" action="">
        <label for="animal_id">Animal:</label>
        <select id="animal_id" name="animal_id" required>
            <?php
            // Fetch animals for the dropdown
            $animal_query = "SELECT id, animal_name FROM animals ORDER BY animal_name";
            $animal_result = $conn->query($animal_query);
            while ($animal = $animal_result->fetch_assoc()) {
                echo "<option value='" . $animal['id'] . "'>" . htmlspecialchars($animal['animal_name']) . "</option>";
            }
            ?>
        </select>

        <label for="production_type">Production Type:</label>
        <select id="production_type" name="production_type" required>
            <option value="Milk">Milk</option>
            <option value="Meat">Meat</option>
            <option value="Wool">Wool</option>
            <option value="Eggs">Eggs</option>

        </select>

        <label for="production_date">Production Date:</label>
        <input type="date" id="production_date" name="production_date" required>

        <label for="quantity">Quantity Produced:</label>
        <input type="number" id="quantity" name="quantity" step="0.01" required>

        <label for="comments">Comments:</label>
        <textarea id="comments" name="comments"></textarea>

        <input type="submit" value="Add Production" class="button">
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="display_production.php" class="button">View Production Data</a>
    </div>
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
        max-width: 600px;
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
        margin-top: 10px;
    }

    select, input[type="date"], input[type="number"], textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .alert-success, .alert-error {
        margin: 15px 0;
        padding: 15px;
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

    .button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #0056b3;
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
    margin-right: 250px; /* Space between logo and text */
}


</style>

<?php include 'footer.php'; ?>
