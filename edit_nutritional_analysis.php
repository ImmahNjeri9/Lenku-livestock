<?php
include 'db_connect.php';

// Fetch the analysis record
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM nutritional_analysis WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$analysis = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dietary_plan_id = $_POST['dietary_plan_id'];
    $analysis_date = $_POST['analysis_date'];
    $protein_content = $_POST['protein_content'];
    $fat_content = $_POST['fat_content'];
    $fiber_content = $_POST['fiber_content'];
    $calories = $_POST['calories'];
    $recommendations = $_POST['recommendations'];

    $update_stmt = $conn->prepare("UPDATE nutritional_analysis SET dietary_plan_id=?, analysis_date=?, protein_content=?, fat_content=?, fiber_content=?, calories=?, recommendations=? WHERE id=?");
    $update_stmt->bind_param("issddddi", $dietary_plan_id, $analysis_date, $protein_content, $fat_content, $fiber_content, $calories, $recommendations, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert-success'>Nutritional analysis updated successfully!</div>";
    } else {
        echo "<div class='alert-error'>Error: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}

// Fetch dietary plans for the dropdown
$plans_sql = "SELECT id, breed FROM dietary_plans ORDER BY id";
$plans_result = $conn->query($plans_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nutritional Analysis</title>
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f8ff;
    color: #333;
    padding: 20px;
}

.container {
    margin: 20px auto;
    max-width: 1000px; /* Increased max-width for container */
    padding: 30px; /* Added more padding for a spacious feel */
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

label {
    display: block;
    margin: 10px 0 5px;
}

input[type="text"],
input[type="number"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"],
.button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    margin: 10px 5px; /* Increased spacing for buttons */
}

input[type="submit"]:hover,
.button:hover {
    background-color: #0056b3;
    transform: translateY(-2px); /* Slight lift on hover */
}

.alert-success, .alert-error {
    margin: 15px 0;
    padding: 10px;
    border-radius: 4px;
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

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px; /* Space above the table */
}

th, td {
    padding: 12px; /* Increased padding for better spacing */
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #007bff;
    color: white;
}

input[type="text"],
input[type="number"],
input[type="date"],
textarea {
    font-size: 14px; /* Adjust font size for better readability */
}

textarea {
    height: 100px; /* Fixed height for textareas */
    resize: vertical; /* Allow vertical resizing only */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Nutritional Analysis</h2>
        <form method="POST" action="">
            <label for="dietary_plan_id">Dietary Plan:</label>
            <select id="dietary_plan_id" name="dietary_plan_id" required>
                <?php while ($plan = $plans_result->fetch_assoc()): ?>
                    <option value="<?php echo $plan['id']; ?>" <?php echo ($analysis['dietary_plan_id'] == $plan['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($plan['id']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="analysis_date">Analysis Date:</label>
            <input type="date" id="analysis_date" name="analysis_date" value="<?php echo htmlspecialchars($analysis['analysis_date']); ?>" required>

            <label for="protein_content">Protein Content (%):</label>
            <input type="number" id="protein_content" name="protein_content" step="0.01" value="<?php echo htmlspecialchars($analysis['protein_content']); ?>" required>

            <label for="fat_content">Fat Content (%):</label>
            <input type="number" id="fat_content" name="fat_content" step="0.01" value="<?php echo htmlspecialchars($analysis['fat_content']); ?>" required>

            <label for="fiber_content">Fiber Content (%):</label>
            <input type="number" id="fiber_content" name="fiber_content" step="0.01" value="<?php echo htmlspecialchars($analysis['fiber_content']); ?>" required>

            <label for="calories">Calories:</label>
            <input type="number" id="calories" name="calories" step="0.01" value="<?php echo htmlspecialchars($analysis['calories']); ?>" required>

            <label for="recommendations">Recommendations:</label>
            <textarea id="recommendations" name="recommendations" required><?php echo htmlspecialchars($analysis['recommendations']); ?></textarea>

            <input type="submit" value="Update Analysis">
        </form>

        <a href="display_nutritional_analysis.php" class="button">View Nutritional Analysis</a>
    </div>
</body>
</html>
