<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_id = filter_var($_POST['animal_id'], FILTER_SANITIZE_NUMBER_INT);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);

   $sql = "INSERT INTO movement_tracking (animal_id, location, action, movement_date, reason) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $animal_id, $location, $action, $movement_date, $reason);

    if ($stmt->execute()) {
        header("Location: display_movement_tracking.php?success=1");
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
    <title>Add Movement Tracking</title>
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



    </style>
</head>
<body> 
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
        <h1>Record Livestock Movements</h1>
    </header>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>


     <h2>Add Movement Tracking</h2>
    <form method="POST" action="">
        <label for="animal_id">Select Animal</label>
        <select name="animal_id" required>
            <?php foreach ($animals as $animal): ?>
                <option value="<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['animal_name']); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="location">Location</label>
        <input type="text" name="location" placeholder="Location"  required>

        <label for="action">Action</label>
        <input type="text" name="action" placeholder="Action (e.g., moved in, moved out)" required>

        <label for="movement_date">Date (YYYY-MM-DD)</label>
        <input type="date" name="date" required>

        <label for="reason">Reason</label>
        <input type="text" name="reason" placeholder="Reason to Movement"  required>

        <button type="submit">Add Movement</button>
    </form> 
<a href="display_movement_tracking.php" class="button">View Livestock Movement</a>
</body>
</html>
