<?php
// Handle medication addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_medication'])) {
    $medication_name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO medication_records (animal_id, medication_name, dosage, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $animal_id, $medication_name, $dosage, $start_date, $end_date);
    $stmt->execute();
}

// Handle medication deletion
if (isset($_GET['delete_medication'])) {
    $delete_id = $_GET['delete_medication'];
    $stmt = $conn->prepare("DELETE FROM medication_records WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
}

// Handle medication editing
if (isset($_GET['edit_medication'])) {
    $edit_id = $_GET['edit_medication'];
    $result = $conn->query("SELECT * FROM medication_records WHERE id = $edit_id");
    $edit_medication = $result->fetch_assoc();
}

// Update medication after editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_medication'])) {
    $update_id = $_POST['update_id'];
    $medication_name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("UPDATE medication_records SET medication_name = ?, dosage = ?, start_date = ?, end_date = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $medication_name, $dosage, $start_date, $end_date, $update_id);
    $stmt->execute();
}

// Fetch all medication records for display
$sql = "SELECT * FROM medication_records WHERE animal_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Medication Tracking for Animal ID: <?php echo $animal_id; ?></h2>

<!-- Add Medication Form -->
<form method="POST">
    <input type="text" name="medication_name" placeholder="Medication Name" required>
    <input type="text" name="dosage" placeholder="Dosage" required>
    <input type="date" name="start_date" required>
    <input type="date" name="end_date" required>
    <input type="submit" name="add_medication" value="Add Medication">
</form>

<!-- Edit Medication Form -->
<?php if (isset($edit_medication)): ?>
    <form method="POST">
        <input type="hidden" name="update_id" value="<?php echo $edit_medication['id']; ?>">
        <input type="text" name="medication_name" value="<?php echo $edit_medication['medication_name']; ?>" placeholder="Medication Name" required>
        <input type="text" name="dosage" value="<?php echo $edit_medication['dosage']; ?>" placeholder="Dosage" required>
        <input type="date" name="start_date" value="<?php echo $edit_medication['start_date']; ?>" required>
        <input type="date" name="end_date" value="<?php echo $edit_medication['end_date']; ?>" required>
        <input type="submit" name="update_medication" value="Update Medication">
    </form>
<?php endif; ?>

<!-- Display Medication Records -->
<table>
    <thead>
        <tr>
            <th>Medication Name</th>
            <th>Dosage</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['medication_name']; ?></td>
                <td><?php echo $row['dosage']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td>
                    <a href="?id=<?php echo $animal_id; ?>&edit_medication=<?php echo $row['id']; ?>">Edit</a>
                    <a href="?id=<?php echo $animal_id; ?>&delete_medication=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
