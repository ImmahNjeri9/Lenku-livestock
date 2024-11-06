<?php
// Include PHPMailer and Composer's autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Adjust the path if necessary

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

function sendVerificationEmail($email, $token) {
    $verification_link = "http://localhost/lenku_livestock/verify_email.php?token=" . urlencode($token);

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'njeriimma39@gmail.com';               // SMTP username
        $mail->Password   = 'tajl lwly jgui mfte';                  // SMTP password (Use App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        if (empty($email)) {
            throw new Exception("Recipient email address is missing.");
        }
        $mail->setFrom('njeriimma39@gmail.com', 'Lenku Livestock');
        $mail->addAddress($email);                                  // Add a recipient

        // Content
        $mail->isHTML(true);                                       // Set email format to HTML
        $mail->Subject = 'Email Verification';
        $mail->Body    = 'To verify your email, please click the link below:<br><br>
                          <a href="' . $verification_link . '">Verify Email</a>';
        $mail->AltBody = 'To verify your email, please click the link below:
                          ' . $verification_link;

        // For development (Debugging):
        // $mail->SMTPDebug = 2;  // 2 for detailed debug output

        // For production (disable debugging):
        $mail->SMTPDebug = 0;  // Disable debug output

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log the error
        $logFile = __DIR__ . '/logs/php_error.log'; // Ensure this path is outside the web root
        error_log("Mailer Error: " . $mail->ErrorInfo . "\n" . $e->getTraceAsString(), 3, $logFile);

        // Display a generic message to the user
        echo "An error occurred while sending the email. Please try again later.";

        return false;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmPassword'])) {
        // Handle signup form
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        // Initialize response variables
        $errorMessage = '';
        $successMessage = '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = 'Invalid email address';
        } elseif ($password !== $confirmPassword) {
            $errorMessage = 'Passwords do not match';
        } else {
            // Check if the username or email already exists
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                if ($stmt->rowCount() > 0) {
                    $errorMessage = 'Username or email is already taken';
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Generate a unique token for email verification
                    $token = bin2hex(random_bytes(32));

                    // Insert user into the database
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_verified, created_at) VALUES (?, ?, ?, 0, NOW())");
                    $stmt->execute([$username, $email, $hashed_password]);

                    // Insert token into the database
                    $stmt = $pdo->prepare("INSERT INTO email_verification_tokens (token, email) VALUES (?, ?)");
                    $stmt->execute([$token, $email]);

                    // Send verification email
                    if (sendVerificationEmail($email, $token)) {
                        // Redirect to the verification instruction page
                        $successMessage = 'Registration successful! Please check your email to verify your account.';
                        header("Location: verification_instructions.php?message=" . urlencode($successMessage));
                        exit();
                    } else {
                        $errorMessage = 'Failed to send verification email';
                    }
                }
            } catch (PDOException $e) {
                $errorMessage = "SQL Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* General styles */
        body , html {
            height: 100%;
            font-family: Cascadia;

            line-height: 1.6;
            background: #ec008c;
    background: linear-gradient(to bottom right, #cdc1e0 25%, #e7cadb 20%, #e3b9ca 40%, #d699b3 30%, #d078a1 80%, #b7598d 50%);

            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .signup-container {
    width: 100%;
            max-width: 1500px;
    display: flex;
    flex-wrap: wrap;
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}


        .welcome-section {
            flex: 1;
            min-width: 300px;
            background:  url('https://i.im.ge/2024/08/31/fxbi88.pic4.png') no-repeat center center;

            background-size: cover;
    padding: 60px 40px; /* Increased top padding */
            display: flex;
            flex-direction: column;
    justify-content: space-between; /* This will distribute space evenly */
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(128, 0, 128, 0.5); /* Purplish transparent color */
            z-index: 1;
        }

        .welcome-section .content {
            position: relative;
            z-index: 2;
        }


        .welcome-section .logo {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        
 .welcome-section h2 {
            font-size: 36px;
            margin-bottom: 10px;
    margin-top: 150px; /* Adjust this value to move the h2 down */
            position: relative;
            z-index: 1;
        }

      .welcome-section p {
    font-size: 18px;
    margin: 0; /* Remove bottom margin */
    z-index: 1;
    align-self: flex-end; /* Align paragraph to the bottom */
}

        .welcome-section .url-link {
            color: white;
            text-decoration: none;
            position: absolute;
            bottom: 10px;
            left: 40px;
            z-index: 1;
        }

        .signup-section {
            flex: 1;
            min-width: 300px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(128, 0, 128, 0.13); /* Purplish transparent color */
        }

       
        h2 {
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .text-muted {
            color: #7d7d7d;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 18px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
            background-color: rgba(128, 0, 128, 0.1);

        }
        .form-control:hover {
            border-color: #7d7d7d;
            background-color: white;
            box-shadow: 0 0 4px 2px rgba(0, 0, 255, 0.5);
        }


        .form-control.invalid {
            border-color: red;
        }
        .form-control:focus {
            outline: #7d7d7d;
            border-color: #7d7d7d;
            box-shadow: 0 0 4px 2px rgba(0, 0, 255, 0.5);
       

        }
        .btn-submit {
            background-color: #7d7d7d;
            color: #fff;
            border: none;
            border-radius: 18px;
            padding: 10px 15px;

            width: 100%;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease-out;
        }
        .btn-submit:hover {
            background-color: #6c6c6c;
        }
        .btn-submit:active {
            transform: scale(0.95);
        }
        .text-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(128, 0, 128, 0.1);
            border: 2px solid #7d7d7d;
            border-radius: 25px;
            padding: 10px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .text-button img {
            margin-right: 10px;
        }
        .text-button:hover {
            border-color: #7d7d7d;
            background-color: #f1f1f1;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        .text-center a {
            color: #7d7d7d;
            text-decoration: none;
            font-weight: bold;
        }
        .text-center a:hover {
            color: #333;
        }.header {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 60px; /* Adjust based on your needs */
}

.logo {
    height: 50px;
}

        /* Media query for phone screens */
        @media (max-width: 700px) {
    .signup-container {
        flex-direction: column;
        width: 90%;
        max-width: 90%;
    }

    .welcome-section {
        flex: 1 0 500px; /* Allow it to grow, but not below 400px */
        width: 100%; /* Use full width */
                padding: 40px 20px; /* Adjust for smaller screens */
        min-width: 300px; /* Keep it from compressing too much */

    }

    .signup-section {
        flex: 1 0 400px; /* Match the signup section */
        width: 100%; /* Use full width */
        padding: 20px;
        margin: auto; /* Center the sections */
    }

    .welcome-section {
        order: -1; /* Move welcome section above signup section */
    }
}


    </style>
</head>
<body>
      <div class="signup-container">
        

        <div class="signup-section">
        <header class="header">
            <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo" style="height: 80px;">
        </header>

        <h2>Sign Up</h2>
        <p class="text-muted">Create your account</p>

        <form method="POST" action="signup.php">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <input type="password" name="confirmPassword" class="form-control" placeholder="Confirm Password" required>
            <button type="submit" class="btn-submit">Sign Up</button>
        </form>

        <?php if (isset($errorMessage)): ?>
            <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>

        <div class="text-center">
            <p>Already have an account?</p>
            <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
