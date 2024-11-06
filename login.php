<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Cascadia;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ec008c;
    background: linear-gradient(to bottom right, #cdc1e0 30%, #e7cadb 20%, #e3b9ca 40%, #d699b3 30%, #d078a1 80%, #b7598d 50%);
        }

        .login-container {
            width: 90%;
            max-width: 1000px;
            display: flex;
            flex-wrap: wrap; /* Allow wrapping of items */
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }


        .welcome-section {
            flex: 1;
            min-width: 300px;
            background: url('https://i.im.ge/2024/08/31/fxbi88.pic4.png') no-repeat center center;
            background-size: cover;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
    margin-top: 100px; /* Adjust this value to move the h2 down */
            position: relative;
            z-index: 1;
        }

        .welcome-section p {
            font-size: 18px;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .welcome-section .url-link {
            color: white;
            text-decoration: none;
            position: absolute;
            bottom: 10px;
            left: 40px;
            z-index: 1;
        }

        .login-section {
            flex: 1;
            min-width: 300px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(128, 0, 128, 0.13); /* Purplish transparent color */
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
            font-size: 40px;
            font-weight: bold;
            margin: 0;
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
            font-size: 16px;
            background-color: rgba(128, 0, 128, 0.1);
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input-field:hover {
            border-color: #7d7d7d;
            background-color: white;
            box-shadow: 0 0 4px 2px rgba(0, 0, 255, 0.5);
        }

        .input-field:focus {
            outline: #7d7d7d;
            border-color: #7d7d7d;
            box-shadow: 0 0 4px 2px rgba(0, 0, 255, 0.5);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 18px;
            background-color: #7d7d7d;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease-out, border-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #6c6c6c;
        }

        .btn-submit:active, 
        .btn-submit:focus {
            border: 2px solid black;
            background-color: #6c6c6c;
            transform: scale(0.95);
            outline: none;
        }

        .forgot-password {
            text-align: center;
            margin: 20px 0;
        }

        .forgot-password .link {
            color: #7d7d7d;
            text-decoration: none;
        }

        .forgot-password .link:hover {
            color: #333;
        }

        .signup {
            text-align: center;
        }

        .signup p {
            margin: 0;
        }

        .signup .link {
            color: #7d7d7d;
            text-decoration: none;
            font-weight: bold;
        }

        .signup .link:hover {
            color: #333;
        }

        /* Media query for phone screens */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .welcome-section,
            .login-section {
                width: 100%;
                min-width: unset;
                padding: 20px;
            }

            .welcome-section {
                order: -1; /* Move welcome section above login section */
            }
       
    </style>
</head>
<body>
    <div class="login-container">
        

        <div class="login-section">
            <div class="header">
                <header class="header">
                    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo" style="height: 80px;">
                </header>
                <h2>Login</h2>
               <br> <p>Enter your credentials to login</br></p>
            </div>

            <form id="loginForm" action="login_process.php" method="POST">
                <input type="text" id="username" name="username" class="input-field" placeholder="Username" required>
                <input type="password" id="password" name="password" class="input-field" placeholder="Password" required>
                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="forgot-password">
                <a href="password_reset.php" class="link">Forgot password?</a>
            </div>

            <div class="signup">
                <p>Don't have an account? <a href="signup.php" class="link">Sign up</a></p>
            </div>
        </div>
    </div>
</body>
</html>
