<?php
session_start(); // Start the session

// Database connection (replace with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lenku";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is set
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Retrieve form data
    $input = $_POST['username']; // This can be either username or email
    $pass = $_POST['password'];

    // Prepare and execute SQL query to fetch user data by either username or email
    $sql = $conn->prepare("
        SELECT id, username, email, password 
        FROM users 
        WHERE username = ? OR email = ?
    ");
    $sql->bind_param("ss", $input, $input);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // User found
        $row = $result->fetch_assoc();
        
        // Verify the hashed password
        if (password_verify($pass, $row['password'])) {
            // Password is correct, start session and set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email']; // Optional: Store email if needed
            $_SESSION['logged_in'] = true; // Set a flag to indicate user is logged in
            
            // Optional: Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Check if there's a stored URL in the session
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']); // Clear the redirect URL from the session
            } else {
                $redirect_url = 'dash.php'; // Fallback page if no URL is stored
            }

            // Redirect to the stored URL or default page
            header('Location: ' . $redirect_url);
            exit();
        } else {
            // Invalid password
            echo "<script>alert('Invalid username or password'); window.location.href = 'login.php';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Invalid username or password'); window.location.href = 'login.php';</script>";
    }

    // Close statement
    $sql->close();
}

// Close connection
$conn->close();
?>