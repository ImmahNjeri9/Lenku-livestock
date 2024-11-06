<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lenku";

// Create a database connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check for the token in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token and activate the user's account
    try {
        $stmt = $pdo->prepare("SELECT email FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);
        $email = $stmt->fetchColumn();

        if ($email) {
            // Activate the user's account
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
            $stmt->execute([$email]);

            // Remove the token
            $stmt = $pdo->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
            $stmt->execute([$token]);

            // Redirect to the home page with a success message
            header("Location: index.php?message=Your email has been verified successfully. Welcome to Lenku Livestock!");
            exit();
        } else {
            echo "Invalid token or email already verified.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Token not provided.";
}

