<?php
// Include your database connection function
include 'db_connect.php';

// Connect to the database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alert_message = $_POST['alert_message'];
    $alert_date = date('Y-m-d H:i:s'); // Set the current timestamp as the alert date

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO `alerts` (alert_message, alert_date) VALUES (?, ?)");
    $stmt->bind_param("ss", $alert_message, $alert_date);

    if ($stmt->execute()) {
        $success_message = "New alert recorded successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!-- HTML form for recording alerts -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Alert</title>
    <style>
      body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f8ff; /* Slightly adjusted for better contrast */
    color: #333;
    padding: 20px;
    margin: 0; /* Reset margin for consistency */
}

.alert-form {
    background-color: #ffebcd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Increased shadow for depth */
    transition: box-shadow 0.3s ease; /* Smooth transition for hover */
}

.alert-form:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3); /* Shadow on hover */
}

h2 {
    color: #ff4500;
    margin-bottom: 15px; /* Added bottom margin for spacing */
}

label {
    display: block;
    margin: 10px 0 5px;
}

textarea {
    width: 100%;
    padding: 12px; /* Increased padding for better usability */
    margin-bottom: 15px; /* Increased margin for spacing */
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical; /* Allow vertical resizing */
}

.btn-submit {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 12px 20px; /* Adjusted padding for a more substantial button */
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px; /* Increased font size */
    transition: background-color 0.3s, transform 0.2s; /* Added transition for button */
}

.btn-submit:hover {
    background-color: #218838;
    transform: scale(1.05); /* Slightly enlarge on hover */
}

.alert-success, .alert-error {
    margin: 15px 0;
    padding: 12px; /* Increased padding for alerts */
    border-radius: 4px;
    font-weight: bold; /* Bold text for emphasis */
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .alert-form {
        padding: 15px; /* Reduce padding on smaller screens */
    }

    .btn-submit {
        width: 100%; /* Make the button full-width on small screens */
    }
}.header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Distribute space between items */
    padding: 20px;
}

.header img {
    height: 80px;
    margin-right: 10px; /* Space between logo and text */
}

.header span {
    font-family: 'Berlin Sans FB';
    font-size: 1.5rem;
    color: white;
    margin-left: -20px;
}

.header h1 {
    flex-grow: 1; /* Allow h1 to take available space */
    margin-right: 250px; /* Space between logo and text */
}


    </style>
</head>
<body>
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
<h1>Record Alerts</h1>
</header>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <form method="POST" action="" class="alert-form">
        <h2>Record New Alert</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <label for="alert_message">Alert Message:</label>
        <textarea id="alert_message" name="alert_message" required></textarea>
        
        <input type="submit" value="Record Alert" class="btn-submit">
    </form>
<?php include 'footer.php'; ?>

</body>
</html>
