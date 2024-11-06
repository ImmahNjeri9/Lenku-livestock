<?php
include 'db_connect.php';

// Handle deletion
$id = $_GET['id'];
$sql = "DELETE FROM feed_inventory WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<div class='alert-success'>Feed inventory deleted successfully!</div>";
} else {
    echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
}

$stmt->close();
?>

<div class="container">
    <h2>Feed Inventory Management</h2>
    <a href="display_feed_inventory.php" class="button">Back to Feed Inventory</a>
</div>
