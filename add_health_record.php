<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Health Record - Livestock Information System</title>
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
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white !important;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
    text-decoration: none !important;

        }
        .button:hover {
            background-color: #0056b3;
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
        function updateTreatmentOptions() {
            const illness = document.getElementById('illness').value;
            const treatmentSelect = document.getElementById('treatment');

            treatmentSelect.innerHTML = ''; // Clear current options

            const treatments = {
                FMD: ['Supportive care', 'Anti-inflammatories'],
                CBPP: ['Antibiotics', 'Anti-inflammatories'],
                Brucellosis: ['No cure; management includes culling'],
                'Black Quarter': ['Antibiotics', 'Supportive care'],
                Anthrax: ['Antibiotics', 'Prevention is crucial'],
                'Lumpy Skin Disease': ['Supportive care', 'Anti-inflammatories'],
                'Sheep and Goat Pox': ['Supportive care', 'Anti-inflammatories'],
                'Newcastle Disease': ['Supportive care', 'Vaccination'],
                'Avian Influenza': ['Supportive care', 'Biosecurity measures'],
                Rabies: ['Vaccination', 'Supportive care'],
                BVD: ['Supportive care'],
                Leptospirosis: ['Antibiotics'],
                Coccidiosis: ['Coccidiostats', 'Supportive care'],
                PPR: ['Supportive care', 'Vaccination'],
                'Porcine Parvovirus': ['Management strategies'],
                'Internal Parasites': ['Deworming agents'],
                'External Parasites': ['Insecticides', 'Acaricides'],
                'Milk Fever': ['Calcium supplements'],
                'Grass Tetany': ['Magnesium supplements'],
                Metritis: ['Antibiotics'],
            };

            if (illness in treatments) {
                treatments[illness].forEach(treatment => {
                    const option = document.createElement('option');
                    option.value = treatment;
                    option.textContent = treatment;
                    treatmentSelect.appendChild(option);
                });
            }
        }
    </script>
</head>
<body>
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
        <h1>Add Health Record</h1>
    </header>

    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>



               

    <div class="container">
        <?php
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['animal_id'])) {
                $animal_id = mysqli_real_escape_string($conn, $_POST['animal_id']);
                $illness = mysqli_real_escape_string($conn, $_POST['illness']);
                $treatment = mysqli_real_escape_string($conn, $_POST['treatment']);
                $vaccination = mysqli_real_escape_string($conn, $_POST['vaccination']);

                // Ensure the animal_id exists in the animals table
                $check_animal_query = "SELECT id FROM animals WHERE id = '$animal_id'";
                $check_result = mysqli_query($conn, $check_animal_query);

                if (mysqli_num_rows($check_result) > 0) {
                    $query = "INSERT INTO health_records (animal_id, illness, treatment, vaccination) VALUES ('$animal_id', '$illness', '$treatment', '$vaccination')";

                    if (mysqli_query($conn, $query)) {
                        echo "<p style='color: green;'>Health record added successfully!</p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Selected animal does not exist.</p>";
                }
            }
        }

        // Fetch existing animals from the database
        $animals_query = "SELECT id, animal_name FROM animals";
        $animals_result = mysqli_query($conn, $animals_query);
        ?>

        <form method="POST" action="">
            <label for="animal_id">Select Animal:</label>
            <select id="animal_id" name="animal_id" required>
                <option value="">Select Animal</option>
                <?php while ($animal = mysqli_fetch_assoc($animals_result)): ?>
                    <option value="<?php echo $animal['id']; ?>"><?php echo $animal['animal_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="illness">Common Health Illness:</label>
            <select id="illness" name="illness" required onchange="updateTreatmentOptions()">
                <option value="">Select Illness</option>
                <option value="FMD">Foot and Mouth Disease</option>
                <option value="CBPP">Contagious Bovine Pleuropneumonia</option>
                <option value="Brucellosis">Brucellosis</option>
                <option value="Black Quarter">Black Quarter</option>
                <option value="Anthrax">Anthrax</option>
                <option value="Lumpy Skin Disease">Lumpy Skin Disease</option>
                <option value="Sheep and Goat Pox">Sheep and Goat Pox</option>
                <option value="Newcastle Disease">Newcastle Disease</option>
                <option value="Avian Influenza">Avian Influenza</option>
                <option value="Rabies">Rabies</option>
                <option value="BVD">Bovine Viral Diarrhea</option>
                <option value="Leptospirosis">Leptospirosis</option>
                <option value="Coccidiosis">Coccidiosis</option>
                <option value="PPR">Peste des Petits Ruminants</option>
                <option value="Porcine Parvovirus">Porcine Parvovirus</option>
                <option value="Internal Parasites">Internal Parasites</option>
                <option value="External Parasites">External Parasites</option>
                <option value="Milk Fever">Milk Fever</option>
                <option value="Grass Tetany">Grass Tetany</option>
                <option value="Metritis">Metritis</option>
            </select>

            <label for="treatment">Treatment:</label>
            <select id="treatment" name="treatment" required>
                <option value="">Select Treatment</option>
            </select>

            <label for="vaccination">Vaccination:</label>
            <select id="vaccination" name="vaccination" required>
                <option value="">Select Vaccination</option>
                <option value="FMD Vaccine">FMD Vaccine</option>
                <option value="CBPP Vaccine">CBPP Vaccine</option>
                <option value="Brucellosis Vaccine">Brucellosis Vaccine</option>
                <option value="Black Quarter Vaccine">Black Quarter Vaccine</option>
                <option value="Anthrax Vaccine">Anthrax Vaccine</option>
                <option value="Lumpy Skin Disease Vaccine">Lumpy Skin Disease Vaccine</option>
                <option value="Sheep and Goat Pox Vaccine">Sheep and Goat Pox Vaccine</option>
                <option value="Newcastle Disease Vaccine">Newcastle Disease Vaccine</option>
                <option value="Avian Influenza Vaccine">Avian Influenza Vaccine</option>
                <option value="Rabies Vaccine">Rabies Vaccine</option>
                <option value="Bovine Viral Diarrhea Vaccine">Bovine Viral Diarrhea Vaccine</option>
                <option value="Leptospirosis Vaccine">Leptospirosis Vaccine</option>
                <option value="Coccidiosis Vaccine">Coccidiosis Vaccine</option>
                <option value="PPR Vaccine">Peste des Petits Ruminants Vaccine</option>
                <option value="Porcine Parvovirus Vaccine">Porcine Parvovirus Vaccine</option>
            </select>

            <input type="submit" value="Add Health Record" class="button">
        </form>
 <a href="health_records.php" class="button"> Health Record</a>


    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
