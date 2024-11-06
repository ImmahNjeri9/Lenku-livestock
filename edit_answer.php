<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $answer_id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM answer WHERE id = ?");
    $stmt->bind_param("i", $answer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $answer = $result->fetch_assoc();

        if ($_SESSION['user_id'] !== $answer['user_id']) {
            echo "You do not have permission to edit this answer.";
            exit();
        }
    } else {
        echo "Answer not found.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer_id = intval($_POST['id']);
    $content = $conn->real_escape_string($_POST['content']);

    $sql = "UPDATE answer SET content = '$content', last_edited_at = NOW() WHERE id = $answer_id AND user_id = " . $_SESSION['user_id'];
    
    if ($conn->query($sql) === TRUE) {
        header('Location: faqs.php?id=' . $answer['question_id']);
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Answer</title>
</head>
<body>
    <h1>Edit Answer</h1>
    <form action="edit_answer.php" method="POST">
        <input type="hidden" name="id" value="<?php echo isset($answer) ? $answer['id'] : ''; ?>">
        <label for="content">Your Answer:</label>
        <textarea id="content" name="content" required><?php echo isset($answer) ? htmlspecialchars($answer['content']) : ''; ?></textarea><br>
        <input type="submit" value="Update Answer">
    </form>
</body>
</html>
