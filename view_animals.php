<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Animals - Livestock Information System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(90deg, #4CAF50 0%, #81C784 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }


        .no-data {
            text-align: center;
            font-size: 18px;
            color: #999;
            padding: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .search-container {
            margin-bottom: 20px;
        }

                .search-container input, .search-container select {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white !important;
            text-align: center;
            text-decoration: none !important;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .menu {
            position: relative;
            display: inline-block;
        }

        .menu-content {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .menu-content a {
            padding: 10px;
            display: block;
            text-decoration: none;
            color: black;
        }

        .menu-content a:hover {
            background-color: #f1f1f1;
        }
.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF; /* Bootstrap primary color */
    color: white !important;
    text-align: center;
    text-decoration: none !important;
    border-radius: 5px;
    border: none;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}.header {
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
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this animal?")) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_animal.php';

                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'id';
                hiddenField.value = id;

                form.appendChild(hiddenField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function searchAnimals() {
            const query = document.getElementById('search-input').value;

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_animals.php?species=' + encodeURIComponent(query), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('animal-table-body').innerHTML = this.responseText;
                }
            };
            xhr.send();}
        } nav {
            background-color: purple;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
            transition: background-color 0.3s ease;
        }

        nav:hover {
            background-color: pink;
        }

        nav a {
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        nav a:hover {
            background-color: blue;
            transform: translateY(-2px);
        }

        nav a.active {
            background-color: #388E3C;
            border-radius: 3px; /* Optional: add rounded corners for the active link */
        }

    </script>
</head>
<body>
 
    <!-- Header Section -->
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>        <h1>Livestock Breeds</h1>
    </header>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="container">
        <h2>Recorded Livestock Breeds</h2>

        <!-- Search Bar -->
        <div class="search-container">
           <form method="GET" action="">
                <input type="text" name="species" placeholder="Search by Species" value="<?php echo isset($_GET['species']) ? htmlspecialchars($_GET['species']) : ''; ?>">
                <input type="submit" value="Search" class="button">
            </form>
        </div>

       
       

        <?php
// Prepare the SQL query based on search input
$species_filter = isset($_GET['species']) ? $_GET['species'] : '';

$sql = "SELECT * FROM animals";
if ($species_filter) {
    $sql .= " WHERE species LIKE '%" . $conn->real_escape_string($species_filter) . "%'";
}
$result = $conn->query($sql);

if ($result && $result->num_rows > 0): 
    $counter = 1; // Initialize counter
?>
    <table>
        <thead>
            <tr>
                <th>Id</th> <!-- Row Number Header -->
                <th>Animal Name</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Age (yrs)</th>
                <th>Weight (kg)</th>
                <th>Recorded At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $counter++; ?></td> <!-- Incrementing counter for each row -->
                    <td><?php echo htmlspecialchars($row['animal_name']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['species'])); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['breed'])); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight']); ?></td>
                    <td><?php echo (new DateTime($row['created_at']))->format('F jS, Y'); ?></td>
                    <td>
                        <div class="menu">
                            <span class="dots" onclick="toggleMenu(event)">⋮</span>
                            <div class="menu-content">
                                <a href="edit_animal.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <a href="delete_animal.php?id=<?php echo $row['id']; ?>" onclick="confirmDelete('<?php echo $row['id']; ?>'); return false;">Delete</a>

                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-data">No animals have been added yet.</p>
<?php endif; ?>

<a href="add_animal.php" class="button">Add New Livestock</a>
</div>

<?php include 'footer.php'; ?>


    <script>
        function toggleMenu(event) {
            event.stopPropagation();
            const menu = event.currentTarget.nextElementSibling;
            const allMenus = document.querySelectorAll('.menu-content');
            allMenus.forEach(m => {
                if (m !== menu) {
                    m.style.display = 'none';
                }
            });
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        window.onclick = function() {
            const menus = document.querySelectorAll('.menu-content');
            menus.forEach(menu => {
                menu.style.display = 'none';
            });
        }
    </script> <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_animal.php?id=' + id;  // Pass the ID in the URL

                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'id';
                hiddenField.value = id;

                form.appendChild(hiddenField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Other JavaScript functions can go here
    </script>
</body>
</html>
