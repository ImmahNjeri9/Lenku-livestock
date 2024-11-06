<?php
session_start();
require 'db_connection.php'; // Adjust path as necessary

// Check if reply_id is set in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reply_id = (int)$_GET['id']; // Ensure it's an integer

    // Check if the user has permission to delete
    $stmt = $conn->prepare("SELECT user_id FROM new_replies WHERE id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reply = $result->fetch_assoc();
        // Check if the current user is the owner of the reply
        if ($reply['user_id'] == $_SESSION['user_id']) {
            // Proceed to delete the reply
            $deleteSql = "DELETE FROM new_replies WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $reply_id);
            if ($deleteStmt->execute()) {
                // Redirect or show a success message
                header("Location: faqs.php?message=Reply deleted successfully.");
                exit();
            } else {
                echo "Error deleting reply.";
            }
            $deleteStmt->close();
        } else {
            echo "You do not have permission to delete this reply.";
        }
    } else {
        echo "Reply not found.";
    }

    $stmt->close();
} else {
    echo "Invalid request: reply_id is missing or invalid.";
}

// Close the database connection
$conn->close();
?>
