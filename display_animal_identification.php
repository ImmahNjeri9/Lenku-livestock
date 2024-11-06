<?php
include 'db_connect.php';

$search = '';
if (isset($_GET['search'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
}

$sql = "SELECT ai.id, a.animal_name, ai.identification_type, ai.identification_value 
        FROM animal_identification ai 
        JOIN animals a ON ai.animal_id = a.id 
        WHERE a.animal_name LIKE ? OR ai.identification_type LIKE ?";
$stmt = $conn->prepare($sql);
$like = "%$search%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Animal Identifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: calc(100% - 120px);
            display: inline-block;
        }
        .search-bar button {
            display: inline-block;
        }
        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>        <h1>Livestock Identification</h1>
    </header>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>


    <h2>Animal Identifications</h2>
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by Animal Name or ID" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Animal Name</th>
                <th>Identification Type</th>
                <th>Identification Value</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
             <?php if ($result->num_rows > 0): ?>
        <?php 
            $counter = 1; // Initialize counter
            while ($row = $result->fetch_assoc()): 
        ?>
            <tr>
                <td><?php echo $counter++; ?></td> <!-- Incrementing counter for each row -->
                        <td><?php echo htmlspecialchars($row['animal_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['identification_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['identification_value']); ?></td>
                        <td>
                            <a href="edit_identification.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_identification.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No identifications found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table> <a href="add_animal_identification.php" class="button">Add Livestock Identification</a>
</body>
</html>
