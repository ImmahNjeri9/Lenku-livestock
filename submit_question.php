<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lenku";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit a question.";
    exit;
}

// Get and sanitize input
$title = trim($_POST['title']);
$content = trim($_POST['content']);
$user_id = intval($_SESSION['user_id']);

// Check if inputs are empty
if (empty($title) || empty($content)) {
    echo "Title and content cannot be empty.";
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO new_questions (user_id, title, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $content);

// Execute the statement
if ($stmt->execute()) {
    echo "Question submitted successfully. <a href='tutorials.php'>Go back</a>";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
