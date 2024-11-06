<?php
session_start(); // Start the session at the very beginning of the file

require 'vendor/autoload.php'; // Load PHPMailer classes if using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['bio'])) {
        $message = 'Please log in first to submit your feedback.';
    } else {
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $bio = $_SESSION['bio'];

        $feedbackMessage = htmlspecialchars($_POST['message']);

        // Prepare and bind statement for database
        $stmt = $conn->prepare("INSERT INTO feedback (username, email, bio, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $bio, $feedbackMessage);

        // Execute the statement and check success
        if ($stmt->execute()) {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'njeriimma39@gmail.com';               // SMTP username
                $mail->Password   = 'tajl lwly jgui mfte';                   // SMTP password (Use App Password if 2FA is enabled)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
                $mail->Port       = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('njeriimma39@gmail.com', 'Lenku Livestock'); // Set the "From" address to your email
                $mail->addAddress('njeriimma39@gmail.com');     // Add a recipient

                // Reply-To header
                $mail->addReplyTo($email, $username); // Set the reply-to address to the user's email

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Feedback from ' . htmlspecialchars($username);
                $mail->Body    = "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
                $mail->Body   .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
                $mail->Body   .= "<p><strong>Bio:</strong> " . htmlspecialchars($bio) . "</p>";

                $mail->Body   .= "<p><strong>Feedback:</strong></p>";
                $mail->Body   .= "<p>" . nl2br($feedbackMessage) . "</p>";

                $mail->send();
                $message = 'Thank you, ' . htmlspecialchars($username) . '! Your feedback has been received.';
            } catch (Exception $e) {
                $message = 'Sorry, there was an issue sending your feedback. Please try again later. Mailer Error: ' . $mail->ErrorInfo;
            }

            // Close the statement
            $stmt->close();
        } else {
            $message = 'Sorry, there was an issue saving your feedback. Please try again later.';
        }
    }
}

// Close the database connection
$conn->close();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fce4ec; /* Pinkish background color */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #d81b60; /* Dark pink background */
            color: #fff;
            padding: 1em 0;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 1.8rem; /* Consistent font size */
        }

        main {
            flex: 1;
            padding: 2em 0;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fce4ec;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        section {
            margin-bottom: 2em;
        }

        h2 {
            border-bottom: 2px solid #d81b60; /* Dark pink border */
            padding-bottom: 5px;
            color: #d81b60; /* Dark pink text */
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .info p, .contact-info p {
            margin: 0.5em 0;
            font-size: 1rem;
        }

        .contact-info ul {
            list-style: none;
            padding: 0;
        }

        .contact-info li {
            margin-bottom: 0.5em;
        }

        .contact-info a {
            color: #d81b60; /* Dark pink links */
            text-decoration: none;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .contact-form {
            background-color: #f9f9f9;
            padding: 1em;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1em;
        }

        label {
            display: block;
            margin-bottom: 0.5em;
        }

        input[type="text"], input[type="email"], input[type="bio"], textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="text"]:focus, input[type="email"]:focus,  input[type="bio"]:focus,textarea:focus {
            border-color: #d81b60; /* Dark pink border on focus */
            outline: none;
        }

        button {
            background-color: #d81b60; /* Dark pink background */
            color: #fff;
            border: none;
            padding: 0.7em 1.5em;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        button:hover {
            background-color: #c2185b; /* Slightly darker pink */
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #d81b60; /* Dark pink background */
            color: #fff;
            border-top: 1px solid #ddd;
            margin-top: auto;
        }

        .message {
            font-size: 1rem;
            margin-top: 1em;
            color: #d81b60; /* Dark pink text */
        }
    </style>
</head>
<body>
    
    <main>
        <div class="container">
            <header>
                <h1>Contact Us</h1>
            </header>
            
            <section class="info">
                <h2>Get in Touch</h2>
                <p>If you have any questions, feedback, or need support, please feel free to contact us. We’re here to help!</p>
            </section>

            <section class="contact-info">
                <h2>Contact Information</h2>
                <p>You can reach us through the following methods:</p>
                <ul>
                    <li><strong>Email:</strong> <a href="mailto:njeriimma39@gmail.com">njeriimma39@gmail.com</a></li>
                    <li><strong>Phone:</strong> +254 798 276 565</li>
                    <li><strong>Address:</strong> Namanga, Kenya</li>
                </ul>
            </section>

            <section class="contact-form">
                <h2>Feedback Form</h2>
                <?php
                // PHP code to process form and session data
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (!isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['bio'])) {
                        echo '<p class="message">Please log in first to submit your feedback.</p>';
                    } else {
                        $username = $_SESSION['username'];
                        $email = $_SESSION['email'];
                        $email = $_SESSION['bio'];

                        $message = htmlspecialchars($_POST['message']);

                        // Process the feedback message (e.g., save to database or send via email)
                        // For demonstration, we just display a confirmation message
                        echo '<p class="message">Thank you, ' . htmlspecialchars($username) . '! Your feedback has been received.</p>';
                    }
                }
                ?>

                <form action="" method="post">
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['email'])): ?>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
                        </div>
                         <div class="form-group">
                            <label for="bio">Bio:</label>
                            <?php if (isset($_SESSION['bio'])): ?>
                                <input type="text" id="bio" name="bio" value="<?php echo htmlspecialchars($_SESSION['bio']); ?>" readonly>
                            <?php else: ?>
                                <input type="text" id="bio" name="bio" value="Not provided" readonly>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>
<p class="message">
    You must <a href="login.php" style="color: purple; text-decoration: none;   text-decoration: underline;font-weight: bold;">sign in</a> to submit feedback.
</p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="message">Your Feedback:</label>
                        <textarea id="message" name="message" rows="5" placeholder="Enter your feedback here..."></textarea>
                    </div>
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['email'])): ?>
                        <button type="submit">Send Feedback</button>
                    <?php else: ?>
                        <button type="button" onclick="alert('Please log in first to submit your feedback.')">Send Feedback</button>
                    <?php endif; ?>
                </form>
            </section>

            <footer>
                <p>&copy; 2024 Lenku Livestock. All rights reserved.</p>
            </footer>
        </div>
    </main>

</body>
</html>
