<?php include 'db_connect.php'; ?>

<?php
$species_filter = isset($_GET['species']) ? $_GET['species'] : '';

$sql = "SELECT * FROM animals";
if ($species_filter) {
    $sql .= " WHERE species LIKE '%" . $conn->real_escape_string($species_filter) . "%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['animal_name'] . '</td>
            <td>' . ucfirst($row['species']) . '</td>
            <td>' . ucfirst($row['breed']) . '</td>
            <td>' . $row['age'] . '</td>
            <td>' . $row['weight'] . '</td>
            <td>' . (new DateTime($row['created_at']))->format('F jS, Y') . '</td>
            <td>
                <div class="menu">
                    <span class="dots" onclick="toggleMenu(event)">⋮</span>
                    <div class="menu-content">
                        <a href="edit_animal.php?id=' . $row['id'] . '">Edit</a>
                        <a href="#" onclick="confirmDelete(\'' . $row['id'] . '\')">Delete</a>
                    </div>
                </div>
            </td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="8" class="no-data">No matching animals found.</td></tr>';
}
?>
