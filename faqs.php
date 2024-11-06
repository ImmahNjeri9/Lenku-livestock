<?php
ob_start();
include 'db_connection.php'; // Make sure this file sets up the $conn variable

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

function getAnswerCount($questionId) {
    global $conn;
    $count_sql = "SELECT COUNT(*) AS count FROM answer WHERE question_id = " . $questionId;
    $count_result = $conn->query($count_sql);
    $count_row = $count_result->fetch_assoc();
    return $count_row['count'];
}

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


// Store the current URL in the session if it's not already set
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (!isset($_SESSION['redirect_after_login'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Q&A</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
       
        .user-icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px; /* Size of the circle */
            height: 40px; /* Size of the circle */
            border-radius: 50%;
        background-color: #d81b60; /* Updated background color */
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
        body {
        background-color: #fce4ec; /* Updated background color */
            
            font-size: 14px;

        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .form-container {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .form-container h3 {
            margin-top: 0;
        color: #d81b60; /* Updated heading color */
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
        }

        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .form-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-container input[type="submit"]:hover {
        background-color: #c2185b; /* Slightly darker pink */
        }

        .question-container {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }

        .question-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        background-color: #d81b60; /* Updated avatar color */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
        }

        .question-header .user-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .username {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .user-bio {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .time {
            font-size: 12px;
            color: #999;
            margin-left: 10px;
        }

        .question-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .question-content {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .question-actions {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .question-actions button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .question-actions button:hover {
            background-color: #0056b3;
        }

  .question-actions love-button {
    background: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s;
}

.question-actions love-button.loved {
    color: #ff5b5b;
}

.question-actions love-button span {
    font-size: 18px; /* Adjust size as needed */
    margin-left: 5px;
}


        .question-actions .three-dots-menu {
            position: relative;
            cursor: pointer;
            background: transparent;

            color: black;
            border: none;
            font-size: 16px;
        }

        .question-actions .three-dots-menu .menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .question-actions .three-dots-menu .menu a {
            display: block;
            padding: 10px;
        color: #d81b60; /* Updated link color */
            text-decoration: none;
        }

        .question-actions .three-dots-menu .menu a:hover {
            background-color: #f0f0f0;
        }

        .answer-form, .answers {
            display: none;
        }

        .answer-form {
            margin-top: 20px;
        }

        
.answer-container {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: relative;
}

.answer-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.answer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
        background-color: #d81b60; /* Updated avatar color */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 10px;
}

.answer-info {
    flex-grow: 1;
}

.answer-username {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.answer-time {
    font-size: 12px;
    color: #999;
    margin-left: 10px;
}

.answer-content {
    font-size: 16px;
    color: #555;
    line-height: 1.6;
    margin-top: 10px;
}

.answer-actions {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.answer-actions button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    transition: background-color 0.3s;
    margin-right: 10px;
}

.answer-actions button:hover {
    background-color: #0056b3;
}

.answer-actions .three-dots-menu {
    position: relative;
    cursor: pointer;
    background: transparent;
    color: black;
    border: none;
    font-size: 16px;
}

.answer-actions .three-dots-menu .menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 10;
}

.answer-actions .three-dots-menu .menu a {
    display: block;
    padding: 10px;
    color: #007bff;
    text-decoration: none;
}

.answer-actions .three-dots-menu .menu a:hover {
    background-color: #f0f0f0;
}

.answer-form {
    margin-top: 20px;
}

.answer-form textarea {
    width: 100%;
    height: 80px;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    box-sizing: border-box;
}

.answer-form input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.answer-form input[type="submit"]:hover {
    background-color: #0056b3;
}

.edited {
    font-style: italic;
    color: grey;
    font-weight: normal;
    font-size: 14px;
    opacity: 0.5;
}

h3 {
  color: #333;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  font-size: 14px;
  color: #555;
  display: block;
  margin-bottom: 5px;
}

.form-input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
  box-sizing: border-box;
}

textarea.form-input {
  height: 100px;
  resize: vertical;
}

.submit-btn {
  background-color: #2979ff;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 10px;
  display: block;
  width: 100%;
}

.submit-btn:hover {
  background-color: #005ecb;
}.edited {
    font-style: italic; /* Italic text */
    color: grey; /* Grey color */
    font-weight: normal; /* Ensure it's not bold */
    font-size: 14px; /* Adjust size if needed */
    opacity: 0.5; /* Slight transparency */
}
.reply-form {
    display: none;
}

.reply-container {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.reply-header {

    display: flex;
}


    

        


.reply-header .username {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.reply-header .time {
    font-size: 12px;
    color: #999;
    margin-left: 10px;
}

.reply-content {
    font-size: 16px;
    color: #555;
    line-height: 1.6;
    margin-top: 10px;
}

.reply-actions {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.reply-actions button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    transition: background-color 0.3s;
    margin-right: 10px;
}

.reply-actions button:hover {
    background-color: #0056b3;
}

.reply-actions .three-dots-menu {
    position: relative;
    cursor: pointer;
    background: transparent;
    color: black;
    border: none;
    font-size: 16px;
}
.love-button {
    background-color: #007bff;
    border: none;
    color: white;
    cursor: pointer;
}

.three-dots-menu {
    background: transparent;
    border: none;
    cursor: pointer;
    position: relative;
    margin-left: 10px;
}
.reply-actions .three-dots-menu .menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    z-index: 10;
}

.reply-actions .three-dots-menu .menu a {
    display: block;
    padding: 10px;
    color: #007bff;
    text-decoration: none;
}

.three-dots-menu:hover .menu {
    display: block;
}
.menu a {
    display: block;
    padding: 8px 12px;
    text-decoration: none;
    color: #333;
}

.menu a:hover {
    background-color: #f1f1f1; /* Background on hover */
}
 .uploaded-image {
    width: 50%; /* Make the container full width */
    max-width: 800px; /* Optional: Set a maximum width */
    margin: 0 auto; /* Center the image container */
}

.uploaded-image img {
    width: 50%; /* Make the image fill the container */
    height: auto; /* Maintain aspect ratio */
    object-fit: cover; /* Optionally cover the space */
    border-radius: 8px; /* Optional: Add rounded corners */
}
.search-container {
    text-align: center; /* Center the search bar */
    margin: 20px 0; /* Add some space around it */
}

.search-container input[type="text"] {
    padding: 10px;
    width: 300px; /* Adjust width as needed */
    border: 1px solid #ccc;
    border-radius: 4px; /* Rounded corners */
    outline: none; /* Remove default outline */
}

.search-container button {
    padding: 10px;
    border: none;
    background-color: #007BFF; /* Button color */
    color: white; /* Text color */
    border-radius: 4px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
}

.search-container button:hover {
    background-color: #0056b3; /* Darken button on hover */
}
.centered-heading {
    font-family: 'Cascadia Code', monospace; /* Use Cascadia font */
    text-align: center; /* Center the text */
    margin: 20px 0; /* Optional: add some margin for spacing */
}
.welcome-container {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    max-width: 800px;
    margin: 20px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
    text-align: center;
}

.welcome-container h2 {
        color: #d81b60; /* Updated heading color */
    font-size: 24px;
    margin-bottom: 15px;
}

.welcome-container p {
    color: #555;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.welcome-container strong {
    color: #2c7a7b;
}

.welcome-container p:last-child {
    font-weight: bold;
    color: #2c7a7b;
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
document.addEventListener('DOMContentLoaded', function() {
    // Function to update button state based on response data
    function updateButtonState(button, liked, likeCount) {
        if (liked) {
            button.classList.add('loved');
            button.innerHTML = ❤️ <span class='like-count' id='like-count-reply-${button.dataset.id}'>${likeCount}</span>`;
        } else {
            button.classList.remove('loved');
            button.innerHTML = `♡ <span class='like-count' id='like-count-reply-${button.dataset.id}'>${likeCount}</span>`;
        }
    }

    // Function to fetch initial like status for all reply buttons
    function fetchInitialStates() {
        document.querySelectorAll('.love-button').forEach(function(button) {
            const replyId = button.getAttribute('data-id');

            fetch('like_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    reply_id: replyId
                })
            })
            .then(response => response.json())
            .then(data => {
                updateButtonState(button, data.liked, data.like_count);
            })
            .catch(error => console.error('Error fetching like status:', error));
        });
    }

    // Fetch initial states on page load
    fetchInitialStates();

    // Attach click event handler to all reply buttons
    document.querySelectorAll('.love-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const button = this;
            const replyId = button.getAttribute('data-id');

            // Prevent multiple clicks
            button.disabled = true;

            fetch('like_reply.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    reply_id: replyId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update button state based on server response
                updateButtonState(button, data.liked, data.like_count);
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                button.disabled = false; // Re-enable the button after processing
            });
        });
    });
});
</script>

    <script>
        function toggleAnswers(questionId) {
            var answers = document.getElementById('answers-' + questionId);
            var answerForm = document.getElementById('answer-form-' + questionId);
            if (answers.style.display === 'block') {
                answers.style.display = 'none';
                answerForm.style.display = 'none';
            } else {
                answers.style.display = 'block';
                answerForm.style.display = 'block';
            }
        }

        function toggleMenu(event) {
            event.stopPropagation();
            var menu = event.currentTarget.querySelector('.menu');
            var menus = document.querySelectorAll('.menu');
            menus.forEach(function(m) {
                if (m !== menu) {
                    m.style.display = 'none';
                }
            });
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        document.addEventListener('click', function() {
            var menus = document.querySelectorAll('.menu');
            menus.forEach(function(menu) {
                menu.style.display = 'none';
            });
        });

        document.querySelectorAll('.three-dots-menu').forEach(function(menuButton) {
            menuButton.addEventListener('click', toggleMenu);
        });

        document.querySelectorAll('.love-button').forEach(function(button) {
            button.addEventListener('click', function() {
                this.classList.toggle('loved');
            });
        });
    </script>
</head>
<body>
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Q&A Forum</h1>
</header>
<!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>


<?php




if (isset($_GET['query'])) {
    // Sanitize the input
    $search_query = trim(htmlspecialchars($_GET['query']));

    // SQL query to search for questions
    $sql = "
        SELECT q.id FROM question q
        WHERE q.title LIKE ? OR q.content LIKE ?
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("ss", $like_query, $like_query);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetch the first result
        $row = $result->fetch_assoc();
        // Redirect to the specific question's page
        header("Location: faq_detail.php?id=" . htmlspecialchars($row['id']));
        exit; // Always call exit after a redirect
    } else {
        echo "No results found.";
    }

    $stmt->close();
}

ob_end_flush(); // Send output to the browser

?>

    <div class="container">
  <div class="search-container">
    <form action="faqs.php" method="GET" onsubmit="return handleSearch()">
        <input type="text" name="query" placeholder="Search..." required>
        <button type="submit">Search</button>
    </form>
</div>

<script>
function handleSearch() {
    const query = document.querySelector('input[name="query"]').value;
    window.location.href = 'faqs.php?query=' + encodeURIComponent(query);
    return false; // Prevent default form submission
}
</script>

<div class="welcome-container">
    <h2>Welcome to the Lenku Livestock Q&A Community! 🐄🐑🐓</h2>
    <p>We’re thrilled to welcome you to the <strong>Lenku Livestock Online Information System</strong>—your go-to space for everything livestock! Whether you’re a seasoned farmer, a budding enthusiast, or simply curious about livestock management, you’ve found the perfect place to connect, learn, and grow.</p>
    
    <p>Here in the Lenku Livestock Community, we believe that knowledge is best when shared. Our community is designed for <strong>asking questions, offering advice, and exchanging ideas</strong> on everything from <strong>breeding and nutrition</strong> to <strong>health management and market trends</strong>.</p>
    
    <p>Got a question about the best feed for your cattle or how to boost productivity on your farm? Ask away! 🤔 Maybe you’ve got tips and tricks you’ve learned over the years—don’t hesitate to help a fellow farmer out. 🙌</p>
    
    <p><strong>Let’s get the conversation started—your knowledge is just as valuable as your curiosity!</strong> Welcome to the <strong>Lenku Livestock family</strong>—where farming isn’t just a job, it’s a community.
	
    <?php if ($loggedIn): ?>
<p></p>
    <?php else: ?>
<h6>To ask and answer questions, you have to <a href="login.php">sign in</a>.</h6>
<?php endif; ?>
</p>
</div>

<div class="form-container">
  <h3>Ask a Question</h3>
 <form action="post_question.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required class="form-input">
    </div>

    <div class="form-group">
        <label for="content">Question:</label>
        <textarea id="content" name="content" required class="form-input"></textarea>
    </div>

    <div class="form-group">
        <label for="image">Upload Image (optional):</label>
        <input type="file" id="image" name="image" accept="image/*" class="form-input">
    </div>

    <button type="submit" class="submit-btn">Submit Question</button>
</form>

</div>
<h3 class="centered-heading">Recent Questions</h3>

       <?php
date_default_timezone_set('Africa/Nairobi'); // Set timezone

function timeAgo($datetime, $full = false) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->days < 7) {
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    } else {
        return $ago->format('F j, Y'); // e.g., "September 9, 2022"
    }
}

$sql = "SELECT q.id, q.title, q.content, q.image, q.created_at, q.last_edited_at, u.id AS user_id, u.username, u.bio 
        FROM question q 
        JOIN users u ON q.user_id = u.id 
        ORDER BY q.created_at DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($question = $result->fetch_assoc()) {
        $usernameInitial = strtoupper(substr($question['username'], 0, 1));
        $isUserLoggedIn = isset($_SESSION['user_id']);
        $isQuestionOwner = $isUserLoggedIn && $_SESSION['user_id'] == $question['user_id'];

        echo "<div class='question-container'>";
        echo "<div class='question-header'>";
        echo "<div class='avatar'>{$usernameInitial}</div>";
        echo "<div class='user-info'>";
        echo "<div class='username'>" . htmlspecialchars($question['username']) . "</div>";
        echo "<div class='user-bio'>" . htmlspecialchars($question['bio']) . "</div>";
        echo "<div class='time'>" . timeAgo($question['created_at']) . "</div>";
        echo "</div></div>";

       echo "<div class='question-title'>" . htmlspecialchars($question['title']);

if (!empty($question['last_edited_at'])) {
    echo " <span class='edited'>(Edited)</span>";
}
echo "</div>";

echo "<div class='question-content'>";
if (!empty($question['image'])) {
    $image_name = basename($question['image']); // Get just the filename
    $image_path = __DIR__ . '/uploads/' . $image_name; // Full path for checking existence

    if (file_exists($image_path)) {
        echo "<div class='uploaded-image'>";
        echo "<img src='" . htmlspecialchars('uploads/' . $image_name) . "' alt='Question Image' style='max-width: 100%; height: auto;' />";
        echo "</div>";
    } else {
        echo "Image file does not exist.";
    }
}

echo "<p>" . nl2br(htmlspecialchars($question['content'])) . "</p>";
echo "</div>";


        echo "<div class='question-actions'>";
        echo "<button onclick='toggleAnswers(" . $question['id'] . ")'>Answers (" . getAnswerCount($question['id']) . ")</button>";
        echo "<button class='love-button' data-id='" . $question['id'] . "' data-type='question'>♡ <span class='like-count' id='like-count-question-" . $question['id'] . "'>0</span></button>";

        // Three dots menu for Edit/Delete actions
        if ($isQuestionOwner) {
            echo "<button class='three-dots-menu'><i class='fas fa-ellipsis-v'></i>";
            echo "<div class='menu'>";
            echo "<a href='edit_question.php?id=" . $question['id'] . "'>Edit</a>";
            echo "<a href='delete_question.php?id=" . $question['id'] . "'>Delete</a>";
            echo "</div></button>";
        }
        echo "</div>"; // End of question-actions

        // Answer form
        echo "<div class='form-container answer-form' id='answer-form-" . $question['id'] . "'>";
        echo "<h3>Answer the Question</h3>";
        echo "<form action='post_answer.php' method='POST'>";
        echo "<input type='hidden' name='question_id' value='" . $question['id'] . "'>";
        echo "<label for='answer'>Your Answer:</label>";
        echo "<textarea id='answer' name='content' required></textarea><br>";
        echo "<input type='submit' value='Submit Answer'>";
        echo "</form></div>";

        // Fetch answers
        echo "<div class='answers' id='answers-" . $question['id'] . "'>";
        $answers_sql = "SELECT a.id, a.content, a.created_at, a.last_edited_at, a.user_id, u.username, u.bio 
                        FROM answer a 
                        JOIN users u ON a.user_id = u.id 
                        WHERE a.question_id = " . $question['id'] . " 
                        ORDER BY a.created_at DESC";
        $answers_result = $conn->query($answers_sql);

        if ($answers_result && $answers_result->num_rows > 0) {
            while ($answer = $answers_result->fetch_assoc()) {
                $answerUsernameInitial = strtoupper(substr($answer['username'], 0, 1));
                echo "<div class='answer'>";
                echo "<div class='answer-header'>";
                echo "<div class='avatar'>{$answerUsernameInitial}</div>";
                echo "<div class='user-info'>";
                echo "<div class='username'>" . htmlspecialchars($answer['username']) . "</div>";
                echo "<div class='user-bio'>" . htmlspecialchars($answer['bio']) . "</div>";
                echo "<div class='time'>" . timeAgo($answer['created_at']) . "</div>";
                echo "</div></div>";

                echo "<p>" . nl2br(htmlspecialchars($answer['content']));
                if (!empty($answer['last_edited_at'])) {
                    echo " <span class='edited'>(Edited)</span>";
                }
                echo "</p>";
                echo "<div class='answer-actions'>";
                echo "<button class='love-button' data-id='" . $answer['id'] . "' data-type='answer'>♡ <span class='like-count' id='like-count-answer-" . $answer['id'] . "'>0</span></button>";

                // Check if the user is the owner of the answer
                $isAnswerOwner = $isUserLoggedIn && $_SESSION['user_id'] == $answer['user_id'];
                if ($isAnswerOwner) {
                    echo "<button class='three-dots-menu'><i class='fas fa-ellipsis-v'></i>";
                    echo "<div class='menu'>";
                    echo "<a href='edit_answer.php?id=" . $answer['id'] . "'>Edit</a>";
                    echo "<a href='delete_answer.php?id=" . $answer['id'] . "'>Delete</a>";
                    echo "</div></button>";
                }

                echo "<button class='reply-button' onclick='toggleReplyForm(" . $answer['id'] . ")'>Reply</button>";
                echo "</div>"; // End of answer-actions

                // Reply Form
              echo "<div class='reply-form' id='reply-form-" . $answer['id'] . "' style='display:none;'>";
echo "<h4>Reply</h4>";
echo "<form action='new_rep.php' method='POST'>";
echo "<input type='hidden' name='answer_id' value='" . $answer['id'] . "'>";
echo "<textarea name='content' required></textarea><br>";
echo "<input type='submit' value='Submit Reply'>";
echo "</form></div>";

// Fetch replies
$replies_sql = "
    SELECT r.id, r.content, r.created_at, r.user_id, u.username, 
           COUNT(l.id) AS like_count 
    FROM new_replies r 
    JOIN users u ON r.user_id = u.id 
    LEFT JOIN reply_likes l ON r.id = l.reply_id 
    WHERE r.answer_id = ? 
    GROUP BY r.id 
    ORDER BY r.created_at ASC";

$stmt = $conn->prepare($replies_sql);
$stmt->bind_param('i', $answer['id']);
$stmt->execute();
$replies_result = $stmt->get_result();

// Display the number of replies
$replyCount = $replies_result ? $replies_result->num_rows : 0;
echo "<p class='reply-count'>Replies: " . $replyCount . "</p>";

if ($replyCount > 0) {
    while ($reply = $replies_result->fetch_assoc()) {
        $avatarInitial = strtoupper(substr($reply['username'], 0, 1));
        $replyOwnerUsername = htmlspecialchars($reply['username']);
        $answerOwnerUsername = htmlspecialchars($answer['username']);
        $likeCount = $reply['like_count'] ?: 0;

        echo "<div class='reply'>";
        echo "<div class='reply-header'>";

        // Display avatar initial
                echo "<div class='avatar'>{$answerUsernameInitial}</div>";

        // Display formatted reply message
        echo "<div class='reply-info'>";
        echo "<strong>{$replyOwnerUsername}</strong> replied to <strong>{$answerOwnerUsername}</strong>";
        echo "<div class='time'>" . timeAgo($reply['created_at']) . "</div>";
        echo "<p>" . nl2br(htmlspecialchars($reply['content'])) . "</p>";
        echo "<button class='love-button' data-id='" . $reply['id'] . "' data-type='reply'>♡ <span class='like-count' id='like-count-reply-" . $reply['id'] . "'>{$likeCount}</span></button>";

        // Three-dots menu
        if (isset($reply['user_id']) && $isUserLoggedIn && $_SESSION['user_id'] == $reply['user_id']) {
            echo "<button class='three-dots-menu'><i class='fas fa-ellipsis-v'></i>";
            echo "<div class='menu'>";
            echo "<a href='edit_reply.php?id=" . $reply['id'] . "'>Edit</a>";
            echo "<a href='delete_reply.php?id=" . $reply['id'] . "' onclick='return confirm(\"Are you sure you want to delete this reply?\");'>Delete</a>";
            echo "</div></button>";
        }

        echo "</div>"; // End of reply-info
        echo "</div>"; // End of reply-header
        echo "</div>"; // End of reply
    }
} else {
    echo "<p class='no-replies-message' style='display:none;'>No replies yet.</p>";
}


                echo "</div>"; // End of answers
            }
        } else {
            echo "<p>No answers yet.</p>";
        }
        echo "</div>"; // End of answers div
        echo "</div>"; // End of question-container
    }
} else {
    echo "<p>No questions found.</p>";
}
?>


    </div>
  


 <footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <p>&copy; 2024 Lenku Livestock Online Information System. All rights reserved.</p>
        <nav>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="privacy.php" class="text-white">Privacy Policy</a></li>
                <li class="list-inline-item"><a href="terms.php" class="text-white">Terms of Service</a></li>
            </ul>
        </nav>
    </div>
    </footer>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.welcome').addClass('visible');
        });
    </script><script>
  document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.section');

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, { threshold: 0.1 }); // Adjust the threshold as needed

    sections.forEach(section => {
      observer.observe(section);
    });
  });
</script>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
            function toggleAnswers(questionId) {
                var answers = document.getElementById('answers-' + questionId);
                var answerForm = document.getElementById('answer-form-' + questionId);
                if (answers.style.display === 'block') {
                    answers.style.display = 'none';
                    answerForm.style.display = 'none';
                } else {
                    answers.style.display = 'block';
                    answerForm.style.display = 'block';
                }
            }

            function toggleMenu(event) {
                event.stopPropagation();
                var menu = event.currentTarget.querySelector('.menu');
                var menus = document.querySelectorAll('.menu');
                menus.forEach(function(m) {
                    if (m !== menu) {
                        m.style.display = 'none';
                    }
                });
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            }

            document.querySelectorAll('.three-dots-menu').forEach(function(menuButton) {
                menuButton.addEventListener('click', toggleMenu);
            });

            document.addEventListener('click', function() {
                var menus = document.querySelectorAll('.menu');
                menus.forEach(function(menu) {
                    menu.style.display = 'none';
                });
            });

            document.querySelectorAll('.love-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    button.classList.toggle('loved');
                });
            });

            document.querySelectorAll('.toggle-answers').forEach(function(button) {
                button.addEventListener('click', function() {
                    var questionId = button.getAttribute('data-question-id');
                    toggleAnswers(questionId);
                });
            });
        });
    </script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update button state based on response data
    function updateButtonState(button, liked, likeCount) {
        if (liked) {
            button.classList.add('loved');
            button.innerHTML = `❤️ <span class='like-count' id='like-count-${button.dataset.type}-${button.dataset.id}'>${likeCount}</span>`;
        } else {
            button.classList.remove('loved');
            button.innerHTML = `♡ <span class='like-count' id='like-count-${button.dataset.type}-${button.dataset.id}'>${likeCount}</span>`;
        }
    }

    // Function to fetch initial like status for all buttons
    function fetchInitialStates() {
        document.querySelectorAll('.love-button').forEach(function(button) {
            const itemId = button.getAttribute('data-id');
            const itemType = button.getAttribute('data-type');

            fetch('check_like_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    item_id: itemId,
                    item_type: itemType
                })
            })
            .then(response => response.json())
            .then(data => {
                updateButtonState(button, data.liked, data.like_count);
            })
            .catch(error => console.error('Error fetching like status:', error));
        });
    }

    // Fetch initial states on page load
    fetchInitialStates();

    // Attach click event handler to all buttons
    document.querySelectorAll('.love-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const button = this;
            const itemId = button.getAttribute('data-id');
            const itemType = button.getAttribute('data-type');

            // Prevent multiple clicks
            button.disabled = true;

            fetch('like_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    item_id: itemId,
                    item_type: itemType
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update button state based on server response
                updateButtonState(button, data.liked, data.like_count);
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                button.disabled = false; // Re-enable the button after processing
            });
        });
    });
});
</script>
<script>
function toggleReplyForm(answerId) {
    const replyForm = document.getElementById('reply-form-' + answerId);
    if (replyForm.style.display === 'none' || replyForm.style.display === '') {
        replyForm.style.display = 'block';
    } else {
        replyForm.style.display = 'none';
    }
}

</script><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

