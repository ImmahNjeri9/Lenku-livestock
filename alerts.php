<?php
// Include your database connection function, navbar, and footer
include 'db_connect.php';

// Fetch all alerts from the database
$sql = "SELECT alert_message, alert_date FROM `alerts` ORDER BY alert_date DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Alerts</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8; /* Soft background for contrast */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease; /* Animation on load */
        }

        h2 {
            color: #007bff; /* Vibrant header color */
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase; /* Make header more modern */
            animation: slideIn 0.5s ease; /* Header animation */
        }

        .alert-list {
            list-style-type: none;
            padding: 0;
        }

        .alert-item {
            background-color: #e9f8fd; /* Lighter alert background */
            border-left: 5px solid #007bff; /* Vibrant left border */
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease; /* Smooth hover effects */
        }

        .alert-item:hover {
            transform: translateY(-3px); /* Slight lift on hover */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2); /* Increased shadow on hover */
        }

        .alert-message {
            font-size: 18px;
            font-weight: 600; /* Slightly bolder text for emphasis */
            margin-bottom: 8px;
            color: #212529;
        }

        .alert-date {
            font-size: 14px;
            color: #6c757d; /* Muted color for date */
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Distribute space between items */
            padding: 20px;
            background-color: #007bff; /* Header background color */
            color: white; /* Text color in header */
        }

        .header img {
            height: 80px;
            margin-right: 10px; /* Space between logo and text */
        }

        .header span {
            font-family: 'Berlin Sans FB';
            font-size: 1.5rem;
        }

        .header h1 {
            flex-grow: 1; /* Allow h1 to take available space */
            margin-right: 250px; /* Space between logo and text */
        }

        @media (max-width: 480px) {
            .container {
                margin: 15px auto;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Recent Alerts</h1>
</header>

<!-- Navigation Bar -->
<?php include 'navbar.php'; ?>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <ul class="alert-list">
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="alert-item">
                    <div class="alert-message"><?php echo htmlspecialchars($row['alert_message']); ?></div>
                    <div class="alert-date">
                        <?php
                        // Format the date
                        $date = new DateTime($row['alert_date']);
                        echo $date->format('F j, Y, g:i A'); // e.g., October 3, 2024, 2:30 PM
                        ?>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No alerts found.</p>
    <?php endif; ?>

    <!-- Button to Add New Alert -->
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add_alert.php" class="btn-add-alert">Add New Alert</a>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
