<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to edit replies.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_id'])) {
    $reply_id = $_POST['reply_id'];
    $content = $_POST['content'];

    // Update the reply in the database
    $sql = "UPDATE new_replies SET content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $content, $reply_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo "Reply updated successfully!";
        // You might want to redirect to another page here
    } else {
        echo "Error updating reply: " . htmlspecialchars($stmt->error);
    }
}

// Fetch the existing reply to show in the form
if (isset($_GET['id'])) {
    $reply_id = $_GET['id'];
    
    $sql = "SELECT content FROM new_replies WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $reply_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reply = $result->fetch_assoc();
        ?>
        <form action="edit_reply.php" method="POST">
            <input type="hidden" name="reply_id" value="<?php echo htmlspecialchars($reply_id); ?>">
            <textarea name="content" required><?php echo htmlspecialchars($reply['content']); ?></textarea><br>
            <input type="submit" value="Update Reply">
        </form>
        <?php
    } else {
        echo "Reply not found or you do not have permission to edit this reply.";
    }
} 
?>
