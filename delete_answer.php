<?php
// Include your database connection file
require 'db_connection.php'; // Adjust the path as necessary

// Assume $answerId is retrieved from a request, like GET or POST
$answerId = $_GET['id']; // or however you pass the ID

// First, delete all likes associated with the answer
$deleteLikesSql = "DELETE FROM likes WHERE answer_id = ?";
$stmt = $conn->prepare($deleteLikesSql);
$stmt->bind_param("i", $answerId);
$stmt->execute();

// Check if likes were deleted successfully
if ($stmt->affected_rows >= 0) {
    // Next, delete all replies associated with the answer
    $deleteRepliesSql = "DELETE FROM new_replies WHERE answer_id = ?";
    $stmt = $conn->prepare($deleteRepliesSql);
    $stmt->bind_param("i", $answerId);
    $stmt->execute();

    // Check if replies were deleted successfully
    if ($stmt->affected_rows >= 0) {
        // Finally, delete the answer
        $deleteAnswerSql = "DELETE FROM answer WHERE id = ?";
        $stmt = $conn->prepare($deleteAnswerSql);
        $stmt->bind_param("i", $answerId);
        $stmt->execute();

        // Check for errors or success
        if ($stmt->affected_rows > 0) {
            echo "Answer and its replies and likes deleted successfully.";
        } else {
            echo "Error deleting answer.";
        }
    } else {
        echo "Error deleting replies.";
    }
} else {
    echo "Error deleting likes.";
}

// Close the statement
$stmt->close();
$conn->close(); // Close the connection when done
?>
