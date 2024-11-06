<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock System Dashboard</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Header */
        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease-out;
        }

        /* Header Flexbox */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
        }

        .header img {
            height: 80px;
            margin-right: 10px;
        }

        .header span {
            font-family: 'Berlin Sans FB';
            font-size: 1.5rem;
            color: white;
            margin-left: -20px;
        }

        .header h1 {
            flex-grow: 1;
            margin-right: 250px;
        }

        /* Main Dashboard Container */
        .container {
            width: 90%;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            animation: fadeIn 1.5s ease;
        }

        /* Dashboard Cards */
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        /* Card Titles */
        .card h2 {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        /* Add images within cards */
        .card img {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 120px;
            height: 120px;
            opacity: 0.1;
            transition: opacity 0.3s;
        }

        .card:hover img {
            opacity: 0.3;
        }

        /* Health Overview */
        .health-overview {
            background: #f44336;
            color: white;
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            position: relative;
            z-index: 1000;
        }

        /* Animal Images - Animations */
        .animated-animals {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .animated-animals img {
            width: 150px;
            height: auto;
            animation: float 3s ease-in-out infinite;
            margin: 0 10px;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
        <span>Lenku Livestock</span>
        <h1>Dashboard</h1>
    </header>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Dashboard Cards -->
    <div class="container">
        <div class="card stats-overview" onclick="location.href='view_animals.php';">
            <h2>Livestock Stats Overview</h2>
            <p>Total Animals: 350</p>
            <p>Active Alerts: 5</p>
            <img src="https://i.im.ge/2024/10/04/khrJsq.image-2024-10-04-135949657.png" alt="Animal Image" />
        </div>

        <div class="card health-overview" onclick="location.href='health_records.php';">
            <h2>Health Overview</h2>
            <p>Recent Vaccinations: 12</p>
            <p>Pending Health Checks: 3</p>
            <img src="https://i.im.ge/2024/10/04/khuusc.image-2024-10-04-140417512.png" alt="Health Image" />
        </div>

        <div class="card feed-management" onclick="location.href='display_feed_inventory.php';">
            <h2>Feed Management</h2>
            <p>Current Feed Stock: 2,000 kg</p>
            <p>Feed Orders: 450 kg pending</p>
            <img src="https://i.im.ge/2024/10/04/khuoUW.image-2024-10-04-140248316.png" alt="Feed Image" />
        </div>

        <div class="card alerts" onclick="location.href='faqs.php';">
            <h2>Community Notifications</h2>
            <p>New Questions: 10</p>
        </div>
    </div>

    <!-- Animated Animal Images -->
    <div class="animated-animals">
        <img src="https://i.im.ge/2024/10/04/khuusc.image-2024-10-04-140417512.png" alt="Goat Image" />
        <img src="https://i.im.ge/2024/10/04/khuSma.image-2024-10-04-140536512.png" alt="Pig Image" />
        <img src="https://i.postimg.cc/28PKZhrt/Guwif4-F-Imgur.png" alt="Chicken Image" />
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Livestock Information System. All rights reserved.</p>
    </footer>

</body>
</html>
