<?php
session_start();
include 'db_connection.php'; // Ensure you have a file to handle the DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate user login
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // Redirect to login if not logged in
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $answer_id = $_POST['answer_id'];
    $content = $_POST['content'];

    // Basic validation
    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO new_replies (user_id, answer_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $user_id, $answer_id, $content);

        if ($stmt->execute()) {
            // Redirect back to the question page or relevant section
            header("Location: faqs.php?id=" . $answer_id); // Adjust the redirect as necessary
            exit();
        } else {
            echo "Error: " . $stmt->error; // Handle the error
        }

        $stmt->close();
    } else {
        echo "Reply content cannot be empty."; // Handle empty content
    }
}

$conn->close();
?>
