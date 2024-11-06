

    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lenku Livestock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMDkHlmO2I8lxljP/oZmlq3DfPjHIH66W9M7My" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="styles.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



    <link rel="stylesheet" href="styles.css">

    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        } /* Header */
        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease-out;
        }


        h1 {
            text-align: center;
            color: #2c3e50;
            margin: 20px 0;
            font-size: 2.5em; /* Adjust size as needed */
        }

        nav {
            background-color: #2c3e50;
            padding: 15px 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            width: 100%; /* Ensure full width */
            justify-content: center; /* Center items */
        }

        li {
            position: relative;
            margin: 0 10px; /* Consistent margin */
        }

        nav a {
             color: white !important;
    text-decoration: none !important;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: flex;
            align-items: center; /* Align icon and text */
        }

        nav a i {
            margin-right: 8px; /* Space between icon and text */
        }

        nav a:hover {
            transform: translateY(-2px);
        }

        /* Dropdown menu styles */
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #34495e;
            z-index: 1;
            top: 100%; /* Positioned below the dropdown link */
            left: 0;
            min-width: 200px;
            border-radius: 5px;
            opacity: 0;
            visibility: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .dropdown-menu.show {
            display: block;
            opacity: 1;
            visibility: visible;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            color: white;
            transition: background-color 0.3s ease;
        }

        .dropdown-menu a:hover {
            background-color: #4a5b72;
        }

        /* User icon container styles */
        .user-icon-container {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        /* Hamburger Menu for Mobile */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger div {
            height: 4px;
            width: 25px;
            background-color: white;
            margin: 3px 0;
            transition: all 0.3s;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            ul {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #2c3e50;
                position: absolute;
                top: 60px; /* Position below the navbar */
                left: 0;
                z-index: 1000;
            }

            .hamburger {
                display: flex;
            }

            .show-menu {
                display: flex;
            }
        

        .user-icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px; /* Size of the circle */
            height: 40px; /* Size of the circle */
            border-radius: 50%;
            background-color: #7f9774; /* Background color of the circle */
            color: white; /* Color of the initial */
            font-size: 1rem; /* Font size of the initial */
            font-weight: bold; /* Font weight of the initial */
        }

        .user-icon-container span {
            line-height: 1; /* Center the text vertically */
        }
        /* Hide the default dropdown toggle indicator of user icon*/
        .user-icon-dropdown-toggle::after {
            display: none;
        }


.user-initial {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.8rem; /* Size of the initial text */
    color: white; /* Color of the initial text */
    background-color: rgba(0, 0, 0, 0.6); /* Background color for contrast */
    border-radius: 50%; /* Circular background */
    padding: 0.2rem 0.5rem; /* Adjust padding as needed */
}

        .features .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .testimonials .testimonial {
            margin-bottom: 2rem;
        }
        .navbar-brand img {
            height: 50px;
            margin-right: 0px;
        }
        .navbar-brand span {
            font-family: 'Berlin Sans FB';
            font-size: 1.5rem;
            color: white;
            margin-left: -20px;
        }
button, a.button {
    color: white !important; /* Set the desired text color */
    background-color: #007bff; /* Set your button background color */
    text-decoration: none !important; /* Remove underline if using <a> */
    border: none; /* Remove default border for <button> */
    padding: 10px 15px; /* Add padding for button size */
    border-radius: 4px; /* Rounded corners */
    cursor: pointer; /* Change cursor to pointer */
    transition: background-color 0.3s ease; /* Smooth background color transition */
}

button:hover, a.button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}/* Animations */
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




    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.getElementById('hamburger');
            const menu = document.getElementById('navbar-menu');

            hamburger.addEventListener('click', function() {
                menu.classList.toggle('show-menu'); // Toggle the visibility of the menu
            });

            const toggleDropdown = (linkId, menuId) => {
                const link = document.getElementById(linkId);
                const menu = document.getElementById(menuId);

                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    menu.classList.toggle('show'); // Toggle dropdown menu visibility
                });
            };

            toggleDropdown('livestock-link', 'livestock-menu');
            toggleDropdown('health-link', 'health-menu');
            toggleDropdown('breeding-link', 'breeding-menu');
            toggleDropdown('nutrition-link', 'nutrition-menu'); // Linking nutrition dropdown
            toggleDropdown('navbarDropdownUser', 'user-menu'); // User management dropdown

            // Close the dropdown if clicking outside of it
            window.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
        });
    </script>
</head>
<body>

    <nav>
        <div class="hamburger" id="hamburger">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <ul id="navbar-menu">

            <li><a href="dash.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="dropdown">
                <a href="#" id="livestock-link"><i class="fas fa-paw"></i> Livestock</a>
                <div class="dropdown-menu" id="livestock-menu">
                    <a href="view_animals.php">Livestock Breeds</a>
                    <a href="display_animal_identification.php">Livestock Identification</a>
                    <a href="display_movement_tracking.php">Movement Tracking</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" id="health-link"><i class="fas fa-heartbeat"></i> Health</a>
                <div class="dropdown-menu" id="health-menu">
                    <a href="health_records.php">Health Records</a>
                    <a href="alerts.php">Potential Alerts</a>
                </div>
            </li>
            <li class="dropdown">
    <a href="#" id="breeding-link"><i class="fas fa-venus-mars"></i> Breeding</a>
                <div class="dropdown-menu" id="breeding-menu">
                    <a href="display_reproductive_history.php">Reproductive History</a>
                    <a href="display_breeding_plans.php">Breeding Plans</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" id="nutrition-link"><i class="fas fa-carrot"></i> Nutrition</a>
                <div class="dropdown-menu" id="nutrition-menu">
                    <a href="display_dietary_plans.php">Dietary Plans</a>
                    <a href="display_feed_inventory.php">Feed Inventory</a>
                    <a href="display_nutritional_analysis.php">Nutritional Analysis</a>
                </div>
            </li>
            <li><a href="display_production.php"><i class="fas fa-chart-line"></i> Production</a></li>
            <li><a href="faqs.php"><i class="fas fa-comments"></i> Community</a></li>
            <li class="dropdown">
              <a href="#" id="navbarDropdownUser" role="button">
    <i class="fas fa-user"></i> User Management
</a>

                <div class="dropdown-menu" id="user-menu">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                        
                </div>
            </li>
        </ul>
    </nav>

</body>
</html>

