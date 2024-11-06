<?php
// Include database connection
include 'db_connection.php';

// Start the session to get the logged-in user's ID
session_start();


// Default values
$loggedIn = false;
$username = '';
$email = '';

// Check if the user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $loggedIn = true;
    $username = $_SESSION['username'] ?? '';
    $email = $_SESSION['email'] ?? '';
} 
// Get the logged-in user's ID (assumed to be stored in session)
$user_id = $_SESSION['user_id'];
// Initialize default values from session
$profile_username = $_SESSION['username'] ?? '';
$profile_email = $_SESSION['email'] ?? '';
$profile_bio = $_SESSION['bio'] ?? ''; // Add this line to get the bio from the session

// Initialize feedback message
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirect = false; // Flag to determine if redirect is needed
}

// Fetch user details (assuming the user's email is also available)
$sql = "SELECT username, bio, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit;
}

// Extract the first initial from the user's email
$email_initial = strtoupper($user['email'][0]);

// Fetch user's questions and count the number of answers each question got
$sql_questions = "
    SELECT question.id, question.title, question.created_at, 
    (SELECT COUNT(*) FROM answer WHERE answer.question_id = question.id) AS answer_count
    FROM question 
    WHERE user_id = ?";
$stmt_questions = $conn->prepare($sql_questions);
$stmt_questions->bind_param("i", $user_id);
$stmt_questions->execute();
$result_questions = $stmt_questions->get_result();

// Fetch user's answers
$sql_answers = "
    SELECT answer.content, question.title, answer.created_at 
    FROM answer 
    JOIN question ON answer.question_id = question.id 
    WHERE answer.user_id = ?";
$stmt_answers = $conn->prepare($sql_answers);
$stmt_answers->bind_param("i", $user_id);
$stmt_answers->execute();
$result_answers = $stmt_answers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
    background-color: #fce4ec; /* Pinkish background color */
          
            font-size: 14px;
        }
        .profile-container {
            background-color: white;
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px; 
            margin: 0 auto;

        }
        .avatar {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
    background-color: #d81b60; /* Dark pink background */
            color: white;
            font-size: 36px;
            font-weight: bold;
            margin: 0 auto 10px;
        }
        .user-info h2 {
            font-size: 24px;
            margin: 0;
        }
        .user-info p {
            font-size: 14px;
            color: #6c757d;
        }
        .tabs {
            margin-top: 20px;
        }
        .tab-links {
            display: flex;
            justify-content: space-around;
            border-bottom: 2px solid #ddd;
        }
        .tab-links a {
            text-decoration: none;
            padding: 10px;
    color: #d81b60; /* Dark pink */
            font-weight: bold;
            cursor: pointer;
        }
        .tab-links a.active {
            color: #000;
    border-bottom: 2px solid #d81b60; /* Dark pink */
        }
        .tab-content {
            display: none;
            padding-top: 10px;
            text-align: left;
        }
        .tab-content.active {
            display: block;
        }
        .question-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .question-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .question-meta {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .answer-btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #e0e0e0;
            border-radius: 5px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }
        .answer-btn:hover {
            background-color: #d6d6d6;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 10px;
        }.answer-item {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

.question-title, .answer-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
}

.question-meta, .answer-meta {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 10px;
}

.question-content, .answer-content {
    font-size: 14px;
    color: #333;
}
/* Container for the tab */
.tab-content {
    max-width: 800px;
    background-color: #fff;
    border-radius: 10px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    margin-bottom: 20px;
    box-sizing: border-box;
    width: 100%;
}

h3 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.75rem;
    color: #333;
}

/* Form styling */
.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"],
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #fafafa;
    font-size: 1rem;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

textarea {
    resize: none; /* Disable resizing */
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #d81b60; /* Dark pink border on focus */
}

/* Button styling */
.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border: none;
}

.btn-primary {
    background-color: #d81b60; /* Dark pink */
    color: #fff;
}

.btn-primary:hover {
    background-color: #c2185b; /* Slightly darker pink */
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.mt-2 {
    margin-top: 10px;
}

/* Additional styling */
hr {
    margin: 2rem 0;
    border: none;
    border-top: 1px solid #ddd;
}

.form-control {
    margin-top: 10px;
}
        .footer-links {
            margin-top: 30px;
            text-align: center;
        }

        .footer-links a {
            margin: 0 10px;
        }

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



        .feedback-message {
            color: #d9534f; /* Default message color (Bootstrap danger) */
            margin-top: 10px;
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
.header {
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
    <script>
        function openTab(tabName) {
            // Hide all tab contents
            const tabs = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            // Remove active class from all tab links
            const tabLinks = document.getElementsByClassName('tab-link');
            for (let i = 0; i < tabLinks.length; i++) {
                tabLinks[i].classList.remove('active');
            }
            // Show the selected tab
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</head>
<body>
                  <!-- Header Section -->
    <header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Profile</h1>
</header>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>


    <div class="profile-container">   
        <!-- Avatar section with the first initial -->
        <div class="avatar">
            <?php echo $email_initial; ?>
        </div>

        <!-- Display user's username and bio -->
        <div class="user-info">
            <h2><?php echo $user['username']; ?></h2>
            <p><?php echo $user['bio']; ?></p>
        </div>

        <!-- Tab links -->
        <div class="tabs">
            <div class="tab-links">
                <a class="tab-link active" onclick="openTab('questions')">Questions</a>
                <a class="tab-link" onclick="openTab('answers')">Answers</a>
        <a class="tab-link" onclick="openTab('settings')">Settings</a> <!-- New Settings tab -->
            </div>

            <!-- Questions tab content -->
            <div id="questions" class="tab-content active">
                <h3><?php echo $result_questions->num_rows; ?> Question(s)</h3>
                <ul>
                    <?php if ($result_questions->num_rows > 0): ?>
                        <?php while ($question = $result_questions->fetch_assoc()) { ?>
                            <div class="question-item">
                                <div class="question-title"><?php echo $question['title']; ?></div>
                                <div class="question-meta">
                                    <?php echo $question['answer_count']; ?> Answer(s) · 
                                    Posted on <?php echo date('D, M j, Y', strtotime($question['created_at'])); ?>
                                </div>
                                <a href="faqs.php?id=<?php echo $question['id']; ?>" class="answer-btn">Answer</a>
                            </div>
                        <?php } ?>
                    <?php else: ?>
                        <li>No questions found.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Answers tab content -->
            <div id="answers" class="tab-content">
                <h3><?php echo $result_answers->num_rows; ?> Answer(s)</h3>
                <ul>
                    <?php if ($result_answers->num_rows > 0): ?>
                        <?php while ($answer = $result_answers->fetch_assoc()) { ?>
                         <div class="answer-item">
                    <div class="question-title">Answer to: "<?php echo $answer['title']; ?>"</div>
                    <div class="question-meta">Answered on <?php echo date('D, M j, Y', strtotime($answer['created_at'])); ?></div>
                    <div class="question-content"><?php echo substr($answer['content'], 0, 150); ?>...</div>
                </div>
                        <?php } ?>
                    <?php else: ?>
                        <li>No answers found.</li>
                    <?php endif; ?>
                </ul>
            </div>

   <!-- Settings tab content -->
<div id="settings" class="tab-content">
    <h3>Update Profile</h3>
    <form action="profile.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($profile_username); ?>" class="form-control">
            <button type="submit" name="change_username" class="btn btn-primary mt-2">Change Username</button>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile_email); ?>" class="form-control">
            <button type="submit" name="change_email" class="btn btn-primary mt-2">Change Email</button>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($profile_bio); ?></textarea>
            <button type="submit" name="change_bio" class="btn btn-primary mt-2">Update Bio</button>
        </div>
        <hr>
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control">
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" class="form-control">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
        </div>
        <button type="submit" name="change_password" class="btn btn-secondary">Change Password</button>
    </form><a href="password_reset.php" class="btn btn-link" style="font-size: 0.875rem;">Forgot Password?</a>

            <!-- Feedback message -->
            <div class="feedback-message">
                <?php echo htmlspecialchars($message); ?>
            </div>


</div>

        </div>
    

        <!-- Footer -->
        <?php include 'footer.php'; ?>

    </div>

<script>
function openTab(tabName) {
    // Hide all tab contents
    const tabs = document.getElementsByClassName('tab-content');
    for (let i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove('active');
    }
    // Remove active class from all tab links
    const tabLinks = document.getElementsByClassName('tab-link');
    for (let i = 0; i < tabLinks.length; i++) {
        tabLinks[i].classList.remove('active');
    }
    // Show the selected tab
    document.getElementById(tabName).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.welcome').addClass('visible');
        });
    </script>

</body>
</html>

<?php
// Close connections
$stmt->close();
$stmt_questions->close();
$stmt_answers->close();
$conn->close();
?>
