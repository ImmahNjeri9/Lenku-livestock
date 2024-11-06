<?php
include 'db_connect.php';

// Check if 'id' is set in the URL
if (!isset($_GET['id'])) {
    die("Animal ID is required.");
}

$animal_id = $_GET['id']; // Get animal ID from the URL

// Handle record addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_record'])) {
    $record_date = $_POST['record_date'];
    $vaccination = $_POST['vaccination'];
    $treatment = $_POST['treatment'];
    $illness = $_POST['illness'];
    $veterinary_visit = $_POST['veterinary_visit'];

    $stmt = $conn->prepare("INSERT INTO health_records (animal_id, record_date, vaccination, treatment, illness, veterinary_visit) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $animal_id, $record_date, $vaccination, $treatment, $illness, $veterinary_visit);
    $stmt->execute();
}

// Handle record deletion
if (isset($_GET['delete_record'])) {
    $delete_id = $_GET['delete_record'];
    $stmt = $conn->prepare("DELETE FROM health_records WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
}

// Handle record editing
$edit_record = null;
if (isset($_GET['edit_record'])) {
    $edit_id = $_GET['edit_record'];
    $result = $conn->query("SELECT * FROM health_records WHERE id = $edit_id");
    $edit_record = $result->fetch_assoc();
}

// Update record after editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_record'])) {
    $update_id = $_POST['update_id'];
    $record_date = $_POST['record_date'];
    $vaccination = $_POST['vaccination'];
    $treatment = $_POST['treatment'];
    $illness = $_POST['illness'];
    $veterinary_visit = $_POST['veterinary_visit'];

    $stmt = $conn->prepare("UPDATE health_records SET record_date = ?, vaccination = ?, treatment = ?, illness = ?, veterinary_visit = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $record_date, $vaccination, $treatment, $illness, $veterinary_visit, $update_id);
    $stmt->execute();
}

// Fetch all health records for display
$sql = "SELECT * FROM health_records WHERE animal_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();
?>

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
            padding: 20px;
        }
        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        form input {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
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
</head>
<body>

    <!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>        <h1>Livestock Health Records</h1>
    </header>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="container">
        <h2>Health Records for Animal ID: <?php echo $animal_id; ?></h2>

        <!-- Add Record Form -->
        <form method="POST">
            <input type="date" name="record_date" required>
            <input type="text" name="vaccination" placeholder="Vaccination">
            <input type="text" name="treatment" placeholder="Treatment">
            <input type="text" name="illness" placeholder="Illness">
            <input type="text" name="veterinary_visit" placeholder="Veterinary Visit">
            <input type="submit" name="add_record" value="Add Record" class="button">
        </form>

        <!-- Edit Record Form -->
        <?php if ($edit_record): ?>
            <form method="POST">
                <input type="hidden" name="update_id" value="<?php echo $edit_record['id']; ?>">
                <input type="date" name="record_date" value="<?php echo $edit_record['record_date']; ?>" required>
                <input type="text" name="vaccination" value="<?php echo $edit_record['vaccination']; ?>" placeholder="Vaccination">
                <input type="text" name="treatment" value="<?php echo $edit_record['treatment']; ?>" placeholder="Treatment">
                <input type="text" name="illness" value="<?php echo $edit_record['illness']; ?>" placeholder="Illness">
                <input type="text" name="veterinary_visit" value="<?php echo $edit_record['veterinary_visit']; ?>" placeholder="Veterinary Visit">
                <input type="submit" name="update_record" value="Update Record" class="button">
            </form>
        <?php endif; ?>

        <!-- Display Health Records -->
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Vaccination</th>
                    <th>Treatment</th>
                    <th>Illness</th>
                    <th>Veterinary Visit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['record_date']; ?></td>
                        <td><?php echo $row['vaccination']; ?></td>
                        <td><?php echo $row['treatment']; ?></td>
                        <td><?php echo $row['illness']; ?></td>
                        <td><?php echo $row['veterinary_visit']; ?></td>
                        <td>
                            <a href="?id=<?php echo $animal_id; ?>&edit_record=<?php echo $row['id']; ?>" class="button">Edit</a>
                            <a href="?id=<?php echo $animal_id; ?>&delete_record=<?php echo $row['id']; ?>" class="button" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
