<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM nutritional_analysis WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: display_nutritional_analysis.php?message=deleted");
        exit;
    } else {
        echo "<div class='alert-error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>
