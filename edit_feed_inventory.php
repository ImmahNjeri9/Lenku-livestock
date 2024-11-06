<?php
include 'db_connect.php';

// Fetch the record to edit
$id = $_GET['id'];
$sql = "SELECT * FROM feed_inventory WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

// Handle form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feed_type = $_POST['feed_type'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $expiration_date = $_POST['expiration_date'];

    $update_stmt = $conn->prepare("UPDATE feed_inventory SET feed_type = ?, quantity = ?, unit = ?, expiration_date = ? WHERE id = ?");
    $update_stmt->bind_param("sdssi", $feed_type, $quantity, $unit, $expiration_date, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert-success'>Feed inventory updated successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feed Inventory</title>
  <style>
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
    }
</style>


</head>
<body>
    <div class="container">
        <h2>Edit Feed Inventory</h2>
        <form method="POST" action="">
            <label for="feed_type">Feed Type:</label>
            <input type="text" id="feed_type" name="feed_type" value="<?php echo htmlspecialchars($record['feed_type']); ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" step="0.01" value="<?php echo htmlspecialchars($record['quantity']); ?>" required>

            <label for="unit">Unit:</label>
            <input type="text" id="unit" name="unit" value="<?php echo htmlspecialchars($record['unit']); ?>" required>

            <label for="expiration_date">Expiration Date:</label>
            <input type="date" id="expiration_date" name="expiration_date" value="<?php echo htmlspecialchars($record['expiration_date']); ?>" required>

            <input type="submit" value="Update Inventory">
        </form>
    </div>
</body>
</html>
