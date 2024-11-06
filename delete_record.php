<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Health Record - Livestock Information System</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS for styling -->
</head>
<body>
    <header>
        <h1>Delete Health Record</h1>
    </header>

    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($conn, $_GET['id']);
            $query = "SELECT * FROM health_records WHERE id = '$id'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $delete_query = "DELETE FROM health_records WHERE id = '$id'";
                    if (mysqli_query($conn, $delete_query)) {
                        echo "<p style='color: green;'>Health record deleted successfully!</p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    $record = mysqli_fetch_assoc($result);
                    echo "<p>Are you sure you want to delete the record for {$record['illness']} of animal ID {$record['animal_id']}?</p>";
                }
            } else {
                echo "<p style='color: red;'>Record not found.</p>";
            }
        } else {
            echo "<p style='color: red;'>No record ID provided.</p>";
        }
        ?>

        <form method="POST" action="">
            <input type="submit" value="Delete Health Record" class="button">
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
