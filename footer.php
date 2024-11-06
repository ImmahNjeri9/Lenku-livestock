<!DOCTYPE html>
<html lang="en">
<head>
    <style>
      
        .footer {
            background-color: #fff; /* White background for the footer */
            padding: 20px 0;
            text-align: center;
            border-top: 1px solid #ddd;
            color: #333; /* Dark text color */
        }

        .footer a {
            color: #d81b60;
        }

        .footer a:hover {
            text-decoration: underline;
        }


        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            position: relative;
            z-index: 1000;
        }
        footer {
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        footer a {
            color: white;
            text-decoration: none;
            padding: 0 10px;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #81C784;
        }
    </style>
</head>
<body>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Livestock Information System | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms & Conditions</a></p>
    </footer>
</body>
</html>
