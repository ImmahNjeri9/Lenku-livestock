<?php
include 'db_connect.php';

// Fetch animal names for the dropdown
$animals_sql = "SELECT id, animal_name FROM animals ORDER BY animal_name";
$animals_result = $conn->query($animals_sql);

// Handle form submission for recording history
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

// Handle search functionality
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
}

// SQL query to fetch reproductive history
$sql = "SELECT rh.*, a.animal_name FROM reproductive_history rh JOIN animals a ON rh.animal_id = a.id WHERE a.animal_name LIKE ? ORDER BY rh.breeding_cycle_start DESC";
$stmt = $conn->prepare($sql);
$search_param = "%$search_term%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>    <h1>Livestock Reproductive History</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class="container">
    <form method="GET" action="" style="display: flex; justify-content: center; align-items: center;">
        <input type="text" name="search" placeholder="Search by Animal Name" value="<?php echo htmlspecialchars($search_term); ?>" style="flex: 1; padding: 10px; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px;">
        <input type="submit" value="Search" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;">
    </form>

    <h2>Reproductive History</h2>
    <?php
    // Check for results
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Animal Name</th>
                <th>Breeding Cycle Start</th>
                <th>Breeding Cycle End</th>
                <th>Fertility Status</th>
                <th>Pregnancy Status</th>
                <th>Birth Date</th>
                <th>Actions</th>
              </tr>";
        
        while ($row = $result->fetch_assoc()) {
            $formatted_birth_date = $row['birth_date'] ? date("jS M, Y", strtotime($row['birth_date'])) : 'N/A';
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['animal_name']) . "</td>";
            echo "<td>" . htmlspecialchars(date("jS M, Y", strtotime($row['breeding_cycle_start']))) . "</td>";
            echo "<td>" . htmlspecialchars(date("jS M, Y", strtotime($row['breeding_cycle_end']))) . "</td>";
            echo "<td>" . htmlspecialchars($row['fertility_status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pregnancy_status']) . "</td>";
            echo "<td>" . htmlspecialchars($formatted_birth_date) . "</td>";
            echo "<td>
                    <div class='button-container'>
                        <a href='edit_reproductive_history.php?id=" . $row['id'] . "' class='button'>Edit</a>
                        <a href='delete_reproductive_history.php?id=" . $row['id'] . "' class='button' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                    </div>
                  </td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No reproductive history available.</p>";
    }
    ?>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="reproductive_history.php" class="button">Add Reproductive History</a>
    </div>
</div>

<?php
$conn->close();
?>

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
        overflow: auto;
    }

    h2 {
        text-align: center;
        color: #4CAF50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 16px;
    }
    
    th, td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
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

    .button-container {
        display: flex;
        gap: 10px;
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
