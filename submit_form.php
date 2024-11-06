<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer's autoloader

// Start session
session_start();

// Initialize variables
$loggedIn = false;
$username = '';
$userEmail = '';
$feedbackMessage = '';
$feedbackSent = false;

// Check if the user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $loggedIn = true;
    $username = $_SESSION['username'] ?? '';
    $userEmail = $_SESSION['email'] ?? '';
} else {
    $feedbackMessage = 'To send feedback, you need to <a href="login.php">log in</a>.';
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($loggedIn) {
        // Retrieve form data
        $formUsername = isset($_POST['username']) ? trim(htmlspecialchars($_POST['username'])) : '';
        $formEmail = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
        $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

        // Debugging output
        echo 'Debug: Submitted Username: ' . $formUsername . '<br>';
        echo 'Debug: Submitted Email: ' . $formEmail . '<br>';
        echo 'Debug: Session Username: ' . $username . '<br>';
        echo 'Debug: Session Email: ' . $userEmail . '<br>';

        // Check if the submitted username and email match the logged-in user
        if (strcasecmp($formUsername, $username) !== 0 || strcasecmp($formEmail, $userEmail) !== 0) {
            $feedbackMessage = 'The username and email do not match our records.';
        } elseif (empty($formUsername) || empty($formEmail) || empty($message)) {
            $feedbackMessage = 'All fields are required.';
        } elseif (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
            $feedbackMessage = 'Invalid email format.';
        } else {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();                                    // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';             // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                           // Enable SMTP authentication
                $mail->Username   = 'njeriimma39@gmail.com';       // SMTP username
                $mail->Password   = 'tajl lwly jgui mfte';            // SMTP password (app password for Gmail)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
                $mail->Port       = 587;                            // TCP port to connect to

                // Recipients
                $mail->setFrom($formEmail, $formUsername);
                $mail->addAddress('njeriimma39@gmail.com', 'Lenku Livestock');        // Add a recipient

                // Content
                $mail->isHTML(true);                               // Set email format to HTML
                $mail->Subject = 'Contact Form Submission';
                $mail->Body    = "Username: $formUsername<br>Email: $formEmail<br><br>Message:<br>$message";

                $mail->send();
                $feedbackMessage = 'Thank you for your message. We will get back to you shortly.';
                $feedbackSent = true;
            } catch (Exception $e) {
                $feedbackMessage = 'Sorry, something went wrong. Please try again later.';
                echo 'Debug: Mail exception: ' . $e->getMessage() . '<br>';
            }
        }
    }
}
?>
