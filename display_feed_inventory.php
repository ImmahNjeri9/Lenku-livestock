<?php
include 'db_connect.php';

// Handle search query
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch feed inventory with optional search
$sql = "SELECT * FROM feed_inventory";
if ($search_query) {
    $sql .= " WHERE feed_type LIKE ?";
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
    <title>Feed Inventory</title>
    <style>
        /* Same styles as before */
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
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px; /* Add spacing above the button */
        }
        input[type="submit"]:hover {
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
        .button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px; /* Adjusted padding for buttons */
            text-decoration: none;
            border-radius: 4px;
            margin: 5px; /* Space around buttons */
            display: inline-block; /* Ensures buttons align properly */
            transition: background-color 0.3s, transform 0.2s;
        }
        .button:hover {
            background-color: #0056b3;
            transform: translateY(-2px); /* Slight lift on hover */
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
    <span>Lenku Livestock</span>
    <h1>Livestock Feed Inventory</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class="container">
    <h2>Feed Inventory</h2>
    
    <!-- Search Form -->
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search by Feed Type" value="<?php echo htmlspecialchars($search_query); ?>">
        <input type="submit" value="Search">
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Feed Type</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Expiration Date</th>
            <th>Actions</th>
        </tr>
        <?php 
        $counter = 1; // Initialize counter
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo htmlspecialchars($row['feed_type']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['unit']); ?></td>
                <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                <td>
                    <a href="edit_feed_inventory.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                    <a href="delete_feed_inventory.php?id=<?php echo $row['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="add_feed_inventory.php" class="button">Add Feed Inventory</a>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
