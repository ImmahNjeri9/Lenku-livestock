<?php
include 'db_connect.php';

// Handle search query
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch nutritional analysis with optional search
$sql = "SELECT na.id, dp.breed, na.analysis_date, na.protein_content, na.fat_content, na.fiber_content, na.calories, na.recommendations 
        FROM nutritional_analysis na 
        JOIN dietary_plans dp ON na.dietary_plan_id = dp.id";
if ($search_query) {
    $sql .= " WHERE dp.breed LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%{$search_query}%";
    $stmt->bind_param("s", $search_param);
} else {
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Nutritional Analysis</title>
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f8ff;
    color: #333;
    padding: 20px;
}

.container {
    margin: 20px auto;
    max-width: 1500px; /* Increased max-width for container */
    padding: 30px; /* Added more padding for a spacious feel */
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

label {
    display: block;
    margin: 10px 0 5px;
}

input[type="text"],
input[type="number"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"],
.button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

input[type="submit"]:hover,
.button:hover {
    background-color: #0056b3;
    transform: translateY(-2px); /* Slight lift on hover */
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

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px; /* Space above the table */
}

th, td {
    padding: 12px; /* Increased padding for better spacing */
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #007bff;
    color: white;
}

input[type="text"],
input[type="number"],
input[type="date"],
textarea {
    font-size: 14px; /* Adjust font size for better readability */
}

textarea {
    height: 100px; /* Fixed height for textareas */
    resize: vertical; /* Allow vertical resizing only */
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
    <span>Lenku Livestock</span>    <h1>Livestock Nutritional Analysis</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Nutritional Analysis</h2>

        <!-- Search Form -->
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by Breed" value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="submit" value="Search">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Breed</th>
                <th>Analysis Date</th>
                <th>Protein Content (%)</th>
                <th>Fat Content (%)</th>
                <th>Fiber Content (%)</th>
                <th>Calories</th>
                <th>Recommendations</th>
                <th>Actions</th>
            </tr>
            <?php 
            $counter = 1; // Initialize counter
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['breed']); ?></td>
                    <td><?php echo htmlspecialchars($row['analysis_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['protein_content']); ?></td>
                    <td><?php echo htmlspecialchars($row['fat_content']); ?></td>
                    <td><?php echo htmlspecialchars($row['fiber_content']); ?></td>
                    <td><?php echo htmlspecialchars($row['calories']); ?></td>
                    <td><?php echo htmlspecialchars($row['recommendations']); ?></td>
                    <td>
                        <a href="edit_nutritional_analysis.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                        <a href="delete_nutritional_analysis.php?id=<?php echo $row['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this analysis?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="add_nutritional_analysis.php" class="button">Add Nutritional Analysis</a>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>
