<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records - Livestock Information System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .button:active {
            transform: translateY(0);
        }
        .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-container input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        .add-record {
            margin-bottom: 20px;
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
    <span>Lenku Livestock</span>        <h1>Health Records</h1>
    </header>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>



    <div class="container">
        <div class="search-container">
            <div class="add-record">
                <a href="add_health_record.php" class="button">Add Health Record</a>
            </div>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by Animal Name" required>
                <input type="submit" value="Search" class="button">
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Animal Name</th>
                    <th>Illness</th>
                    <th>Treatment</th>
                    <th>Vaccination</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
               <?php
// Fetching records with join and search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$query = "SELECT hr.*, a.animal_name FROM health_records hr 
          JOIN animals a ON hr.animal_id = a.id 
          WHERE a.animal_name LIKE '%$search%'";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<tr><td colspan='6'>Error: " . mysqli_error($conn) . "</td></tr>";
} elseif (mysqli_num_rows($result) > 0) {
    $counter = 1; // Initialize counter
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$counter}</td> <!-- Displaying counter -->
                <td>{$row['animal_name']}</td>
                <td>{$row['illness']}</td>
                <td>{$row['treatment']}</td>
                <td>{$row['vaccination']}</td>
                <td>
                    <a href='edit_record.php?id={$row['id']}' class='button'>Edit</a>
                    <a href='delete_record.php?id={$row['id']}' class='button' style='background-color: #dc3545;'>Delete</a>
                </td>
              </tr>";
        $counter++; // Increment counter for each row
    }
} else {
    echo "<tr><td colspan='6'>No records found.</td></tr>";
}
?>

            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
