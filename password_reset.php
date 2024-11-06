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
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function sendResetEmail($email, $token) {
    $reset_link = "http://localhost/lenku_livestock/password_reset.php?token=" . urlencode($token);

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
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'To reset your password, please click the link below:<br><br>
                          <a href="' . $reset_link . '">Reset Password</a>';
        $mail->AltBody = 'To reset your password, please click the link below:
                          ' . $reset_link;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function validateToken($token) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM password_reset_tokens WHERE token = ?");
    $stmt->execute([$token]);
    return $stmt->fetchColumn() > 0;
}

function updatePassword($token, $new_password) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        // Fetch the email associated with the token
        $stmt = $pdo->prepare("SELECT email FROM password_reset_tokens WHERE token = ?");
        $stmt->execute([$token]);
        $email = $stmt->fetchColumn();
        
        if (!$email) {
            throw new Exception("Token not found");
        }

        // Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Prepare the SQL statement
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
if (!$stmt->execute([$hashed_password, $email])) {
    $errorInfo = $stmt->errorInfo();
    throw new Exception("Failed to update password: " . $errorInfo[2]);
}

// Check if the password was updated
if ($stmt->rowCount() == 0) {
    throw new Exception("No rows updated. Check if the email exists or if the new password is the same as the old one.");
}

        // Optionally, delete the token or mark it as used
        $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
        $stmt->execute([$token]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Password update failed: " . $e->getMessage());
        return false;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        // Handle password reset request form
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email address'); window.location.href='password_reset.php';</script>";
            exit;
        }

        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (token, email) VALUES (?, ?)");
        $stmt->execute([$token, $email]);

        if (sendResetEmail($email, $token)) {
            echo "<script>alert('Password reset instructions sent to your email'); window.location.href='password_reset.php';</script>";
        } else {
            echo "<script>alert('Failed to send email'); window.location.href='password_reset.php';</script>";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['token'])) {
        // Handle password reset form submission
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $token = $_POST['token'];

        if ($new_password !== $confirm_password) {
            echo "<script>alert('Passwords do not match'); window.location.href='password_reset.php?token=" . urlencode($token) . "';</script>";
            exit;
        }

        if (!validateToken($token)) {
            echo "<script>alert('Invalid or expired token'); window.location.href='password_reset.php';</script>";
            exit;
        }

        if (updatePassword($token, $new_password)) {
            echo "<script>alert('Password updated successfully!'); window.location.href='password_reset.php';</script>";
        } else {
            echo "<script>alert('Failed to update password'); window.location.href='password_reset.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Full viewport height */
            background: #ec008c;
    background: linear-gradient(to bottom right, #cdc1e0 30%, #e7cadb 20%, #e3b9ca 40%, #d699b3 30%, #d078a1 80%, #b7598d 50%);

        }
        .container {
         max-width: 400px;
            background-color: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-weight: bold;
            margin: 0;
            font-size: 25px;
        }
        .header p {
            font-size: 16px;
            color: #7d7d7d;
            margin: 0;
        }
        .input-field {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 18px;
            font-size: 15px;
            background-color: rgba(128, 0, 128, 0.1); /* Colors.purple.withOpacity(0.1) */
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s ease;
        }

        
.input-field:hover {
            border-color: #7d7d7d;
            background-color: white;box-shadow: 0 0 4px 2px rgba(0, 0, 255, 0.5); /* Blue glowing effect */
}

/* Style for input field on focus */
.input-field:focus {
    outline: #7d7d7d;
    border-color: #7d7d7d; /* Optional: Change border color on focus */
    box-shadow: 0 0 4px 2px rgba(0, 0, 255, 0.5); /* Blue glowing effect */
}
.btn-submit {
    width: 100%;
    padding: 12px;
    border: none; /* No border initially */
    border-radius: 18px;
    background-color: #7d7d7d;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease-out, border-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #6c6c6c;
    /* Optionally, you can add styles for hover state if needed */
}

.btn-submit:active, 
.btn-submit:focus {
    border: 2px solid black; /* Black border when clicked or focused */
    background-color: #6c6c6c; /* Keep the background color change on active */
    transform: scale(0.95); /* Optional: Scale down the button when pressed */
    outline: none; /* Remove the default focus outline */
        }
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-login .link {
            color: #7d7d7d;
            text-decoration: none;
            font-weight: bold;
        }
        .back-to-login .link:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo" style="height: 80px;">
        </header>

        <?php if (isset($_GET['token']) && validateToken($_GET['token'])): ?>
            <div class="header">
                <h1>Set New Password</h1>			
             <br>   <p>Enter your new password below</br></p>
            </div>
            <form method="POST" action="password_reset.php">
                <input type="password" name="new_password" class="input-field" placeholder="New Password" required>
                <input type="password" name="confirm_password" class="input-field" placeholder="Confirm Password" required>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <button type="submit" class="btn-submit">Submit</button>
            </form>
        <?php else: ?>
            <div class="header">
                <h2>Reset Password</h2>
                <p>Enter your email to receive instructions to reset your password.</p>
            </div>
            <form method="POST" action="password_reset.php">
                <input type="email" name="email" class="input-field" placeholder="Email" required>
                <button type="submit" class="btn-submit">Continue</button>
            </form>
        <?php endif; ?>
        <div class="back-to-login">
            <a href="login.php" class="link">Back to Login</a>
        </div>
    </div>
</body>
</html>
