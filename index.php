<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock Information System</title>
    <!-- Font and Icon Libraries -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Body Styling with Parallax Background */
        body {
            background: url('https://i.im.ge/2024/10/27/k8Nmsp.image-2024-10-27-170337476.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #333;
            position: relative;
        }

        /* Overlay for readability */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        /* Ensures content is above overlay */
        .header, .showcase, .features, footer {
            position: relative;
            z-index: 2;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
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

        /* Showcase Section */
        .showcase {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 20px;
            gap: 20px;
        }
        .showcase img {
            width: 45%;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }
        .showcase img:hover {
            transform: scale(1.05);
        }
        .showcase-content {
            width: 50%;
        }
        .showcase-content h2 {
            color: #4CAF50;
            font-size: 2em;
            margin-bottom: 15px;
        }
        .showcase-content p {
            color: white;
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        /* Buttons for Login and Signup */
        .btn-container {
            margin-top: 20px;
        }
        .btn {
            padding: 15px 25px;
            margin: 10px;
            font-size: 1em;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s, box-shadow 0.3s ease;
        }
        .btn-login {
            background-color: #4CAF50;
        }
        .btn-signup {
            background-color: #2196F3;
        }
        .btn:hover {
            background-color: #333;
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        /* Features Section */
        .features {
            width: 100%;
            max-width: 1200px;
            margin: 40px auto;
            text-align: center;
            padding: 20px;
        }
        .features h2 {
            color: #4CAF50;
            font-size: 2em;
            margin-bottom: 20px;
        }
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            text-align: left;
        }
        .feature-item {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }
        .feature-item:hover {
            transform: translateY(-5px);
        }
        .feature-item h3 {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
        }
        .feature-item p {
            font-size: 1em;
            color: #666;
        }

        /* Fade-in Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Footer */
        footer {
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
        <span>Lenku Livestock</span>
    </header>

    <!-- Showcase Section -->
    <section class="showcase">
        <img src="https://i.im.ge/2024/10/27/k8N9Bq.image-2024-10-27-165923626.png" alt="Livestock Image">
        <div class="showcase-content">
            <h2>Efficient Management at Your Fingertips</h2>
            <p>Our Livestock Information System (LIS) offers a centralized platform for managing animal health, feeding, production, and breeding schedules. With LIS, livestock management has never been easier, providing you with the tools you need to optimize productivity and ensure animal welfare.</p>
            <div class="btn-container">
                <button class="btn btn-login" onclick="location.href='login.php'">Log In</button>
                <button class="btn btn-signup" onclick="location.href='signup.php'">Sign Up</button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2>Features of Our System</h2>
        <div class="feature-list">
            <div class="feature-item fade-in">
                <i class="fas fa-stethoscope" style="color: #4CAF50; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Animal Health Tracking</h3>
                <p>Monitor animal health with real-time data and receive timely reminders for vaccinations and check-ups.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-apple-alt" style="color: #FF9800; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Feeding and Nutrition</h3>
                <p>Plan and automate feeding schedules, manage feed inventory, and track intake amounts for each animal.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-venus-mars" style="color: #FF5733; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Breeding Records</h3>
                <p>Keep detailed records of breeding cycles, pregnancies, and other reproductive information.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-chart-line" style="color: #2196F3; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Production Monitoring</h3>
                <p>Track production data such as milk, meat, or wool output, helping optimize yield and performance.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-chart-bar" style="color: #673AB7; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Data Analytics</h3>
                <p>Analyze data trends to make informed decisions that boost productivity and ensure efficient resource use.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-bell" style="color: #E91E63; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Alerts & Notifications</h3>
                <p>Stay updated with alerts on upcoming tasks, health requirements, and low inventory notifications.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-map-marker-alt" style="color: #009688; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Movement Tracking</h3>
                <p>Track animal movement history, including pastures, enclosures, and transportation, ensuring biosecurity and compliance.</p>
            </div>
            <div class="feature-item fade-in">
                <i class="fas fa-comments" style="color: #795548; font-size: 1.5em; margin-bottom: 10px;"></i>
                <h3>Q&A Forum</h3>
                <p>Engage with the livestock community by asking questions and sharing insights to foster knowledge sharing.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; 2024 Lenku Livestock. All Rights Reserved.
    </footer>

    <!-- JavaScript for Scroll Animations -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const elements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            elements.forEach(element => observer.observe(element));
        });
    </script>

</body>
</html>
