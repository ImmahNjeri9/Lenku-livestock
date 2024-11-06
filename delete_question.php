<?php
include 'db_connection.php';
session_start();

// Check if $questionId is set from GET or POST request
$questionId = null;
if (isset($_GET['id'])) {
    $questionId = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $questionId = $_POST['id'];
}

if ($questionId !== null) {
    // First, delete all likes associated with the question
    $deleteQuestionLikesSql = "DELETE FROM likes WHERE question_id = ?";
    $stmt = $conn->prepare($deleteQuestionLikesSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Then, delete all replies associated with the answers for the question
    $deleteRepliesSql = "DELETE FROM new_replies WHERE answer_id IN (SELECT id FROM answer WHERE question_id = ?)";
    $stmt = $conn->prepare($deleteRepliesSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Next, delete all likes associated with the answers for the question
    $deleteLikesSql = "DELETE FROM likes WHERE answer_id IN (SELECT id FROM answer WHERE question_id = ?)";
    $stmt = $conn->prepare($deleteLikesSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Now, delete all answers associated with the question
    $deleteAnswersSql = "DELETE FROM answer WHERE question_id = ?";
    $stmt = $conn->prepare($deleteAnswersSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    
    // Finally, delete the question
    $deleteQuestionSql = "DELETE FROM question WHERE id = ?";
    $stmt = $conn->prepare($deleteQuestionSql);
    $stmt->bind_param("i", $questionId);
    $stmt->execute();

    // Check for errors or success
    if ($stmt->affected_rows > 0) {
        echo "Question and its associated records deleted successfully.";
    } else {
        echo "Error deleting question or its associated records.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Question ID not specified.";
}

// Close connection
$conn->close();
?>
