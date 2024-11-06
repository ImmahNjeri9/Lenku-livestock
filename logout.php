<?php
session_start(); // Start the session

// Destroy all session data
$_SESSION = array(); // Clear the session variables

// Destroy the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: index.php");
exit();
?>
