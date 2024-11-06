<?php
include 'db_connect.php';

// Initialize search variable
$search = '';

// Handle search query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
}

// Fetch dietary plans with optional search
$sql = "SELECT dp.id, a.animal_name, dp.breed, dp.age, dp.health_status, dp.productivity_level, dp.feeding_schedule 
        FROM dietary_plans dp 
        JOIN animals a ON dp.animal_id = a.id 
        WHERE a.animal_name LIKE ? OR dp.breed LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Dietary Plans</title>
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
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .search-container {
            margin-bottom: 20px;
            text-align: right;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 250px;
            margin-right: 10px;
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
</head>
<body>

<!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>    <h1>Livestock Dietary Plans</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class="container">
    <h2>Dietary Plans</h2>

    <!-- Search Form -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by animal name or breed...">
            <input type="submit" value="Search">
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Animal Name</th>
            <th>Breed</th>
            <th>Age</th>
            <th>Health Status</th>
            <th>Productivity Level</th>
            <th>Feeding Schedule</th>
            <th>Actions</th>
        </tr>
        <?php 
        $counter = 1; // Initialize counter
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo htmlspecialchars($row['animal_name']); ?></td>
                <td><?php echo htmlspecialchars($row['breed']); ?></td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['health_status']); ?></td>
                <td><?php echo htmlspecialchars($row['productivity_level']); ?></td>
                <td><?php echo htmlspecialchars($row['feeding_schedule']); ?></td>
                <td>
                    <a href="edit_dietary_plan.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                    <a href="delete_dietary_plan.php?id=<?php echo $row['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<div class="button-container">
    <a href="add_dietary_plan.php" class="button">Add Dietary Plans</a>
</div>
</div>



<?php include 'footer.php'; ?>
</body>
</html>
