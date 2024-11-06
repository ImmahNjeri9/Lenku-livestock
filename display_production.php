<?php
include 'db_connect.php';

// Initialize search query
$search_query = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'];
}

// SQL query to fetch production data, filtering by animal name if a search query is provided
$sql = "SELECT pd.*, a.animal_name FROM production_data pd JOIN animals a ON pd.animal_id = a.id";
if ($search_query) {
    $sql .= " WHERE a.animal_name LIKE ? ORDER BY pd.production_date DESC";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_param);
} else {
    $sql .= " ORDER BY pd.production_date DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>    <h1>Livestock Production Data</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class='container'>
    <h2>Production Data</h2>

    <!-- Search form -->
    <form method='POST' action='' class='search-form'>
        <input type='text' name='search_query' placeholder='Search by Animal Name' value='<?php echo htmlspecialchars($search_query); ?>'>
        <input type='submit' value='Search' class='button'>
    </form>

    <!-- Check for results -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Animal Name</th>
                <th>Production Type</th>
                <th>Production Date</th>
                <th>Quantity Produced</th>
                <th>Comments</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['animal_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['production_type']); ?></td>
                    <td><?php echo htmlspecialchars(date("jS M, Y", strtotime($row['production_date']))); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['comments']); ?></td>
                    <td>
                        <a href='edit_production.php?id=<?php echo $row['id']; ?>' class='button'>Edit</a>
                        <a href='delete_production.php?id=<?php echo $row['id']; ?>' class='button' onclick='return confirm("Are you sure you want to delete this production record?");'>Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No production records available.</p>
    <?php endif; ?>

    <div class='button-container'>
        <a href='add_production.php' class='button'>Add Production Data</a>
    </div>
</div>

<?php
$stmt->close();
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
    }

    h2 {
        text-align: center;
        color: #4CAF50;
    }

    .search-form {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    input[type="text"] {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: calc(70% - 20px);
        margin-right: 10px;
    }

    input[type="submit"] {
        background-color: #28a745;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
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

    .button {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
        margin: 5px;
    }

    .button:hover {
        background-color: #0056b3;
    }

    /* Style for the button container */
    .button-container {
        text-align: center;
        margin-top: 20px;
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
