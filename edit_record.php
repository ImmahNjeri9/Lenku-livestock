<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Health Record - Livestock Information System</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS for styling -->
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
    <header>
        <h1>Edit Health Record</h1>
    </header>

    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($conn, $_GET['id']);
            $query = "SELECT * FROM health_records WHERE id = '$id'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $record = mysqli_fetch_assoc($result);
            } else {
                echo "<p style='color: red;'>Record not found.</p>";
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $animal_id = mysqli_real_escape_string($conn, $_POST['animal_id']);
            $illness = mysqli_real_escape_string($conn, $_POST['illness']);
            $treatment = mysqli_real_escape_string($conn, $_POST['treatment']);
            $vaccination = mysqli_real_escape_string($conn, $_POST['vaccination']);

            $update_query = "UPDATE health_records SET animal_id = '$animal_id', illness = '$illness', treatment = '$treatment', vaccination = '$vaccination' WHERE id = '$id'";

            if (mysqli_query($conn, $update_query)) {
                echo "<p style='color: green;'>Health record updated successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }

        $animals_query = "SELECT id, animal_name FROM animals";
        $animals_result = mysqli_query($conn, $animals_query);
        ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <label for="animal_id">Select Animal:</label>
            <select id="animal_id" name="animal_id" required>
                <option value="">Select Animal</option>
                <?php while ($animal = mysqli_fetch_assoc($animals_result)): ?>
                    <option value="<?php echo $animal['id']; ?>" <?php echo ($animal['id'] == $record['animal_id']) ? 'selected' : ''; ?>>
                        <?php echo $animal['animal_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="illness">Common Health Illness:</label>
            <select id="illness" name="illness" required onchange="updateTreatmentOptions()">
                <option value="">Select Illness</option>
                <option value="FMD" <?php echo ($record['illness'] == 'FMD') ? 'selected' : ''; ?>>Foot and Mouth Disease</option>
                <option value="CBPP" <?php echo ($record['illness'] == 'CBPP') ? 'selected' : ''; ?>>Contagious Bovine Pleuropneumonia</option>
                <option value="Brucellosis" <?php echo ($record['illness'] == 'Brucellosis') ? 'selected' : ''; ?>>Brucellosis</option>
                <option value="Black Quarter" <?php echo ($record['illness'] == 'Black Quarter') ? 'selected' : ''; ?>>Black Quarter</option>
                <option value="Anthrax" <?php echo ($record['illness'] == 'Anthrax') ? 'selected' : ''; ?>>Anthrax</option>
                <option value="Lumpy Skin Disease" <?php echo ($record['illness'] == 'Lumpy Skin Disease') ? 'selected' : ''; ?>>Lumpy Skin Disease</option>
                <option value="Sheep and Goat Pox" <?php echo ($record['illness'] == 'Sheep and Goat Pox') ? 'selected' : ''; ?>>Sheep and Goat Pox</option>
                <option value="Newcastle Disease" <?php echo ($record['illness'] == 'Newcastle Disease') ? 'selected' : ''; ?>>Newcastle Disease</option>
                <option value="Avian Influenza" <?php echo ($record['illness'] == 'Avian Influenza') ? 'selected' : ''; ?>>Avian Influenza</option>
                <option value="Rabies" <?php echo ($record['illness'] == 'Rabies') ? 'selected' : ''; ?>>Rabies</option>
                <option value="BVD" <?php echo ($record['illness'] == 'BVD') ? 'selected' : ''; ?>>Bovine Viral Diarrhea</option>
                <option value="Leptospirosis" <?php echo ($record['illness'] == 'Leptospirosis') ? 'selected' : ''; ?>>Leptospirosis</option>
                <option value="Coccidiosis" <?php echo ($record['illness'] == 'Coccidiosis') ? 'selected' : ''; ?>>Coccidiosis</option>
                <option value="PPR" <?php echo ($record['illness'] == 'PPR') ? 'selected' : ''; ?>>Peste des Petits Ruminants</option>
                <option value="Porcine Parvovirus" <?php echo ($record['illness'] == 'Porcine Parvovirus') ? 'selected' : ''; ?>>Porcine Parvovirus</option>
                <option value="Internal Parasites" <?php echo ($record['illness'] == 'Internal Parasites') ? 'selected' : ''; ?>>Internal Parasites</option>
                <option value="External Parasites" <?php echo ($record['illness'] == 'External Parasites') ? 'selected' : ''; ?>>External Parasites</option>
                <option value="Milk Fever" <?php echo ($record['illness'] == 'Milk Fever') ? 'selected' : ''; ?>>Milk Fever</option>
                <option value="Grass Tetany" <?php echo ($record['illness'] == 'Grass Tetany') ? 'selected' : ''; ?>>Grass Tetany</option>
                <option value="Metritis" <?php echo ($record['illness'] == 'Metritis') ? 'selected' : ''; ?>>Metritis</option>
            </select>

            <label for="treatment">Treatment:</label>
            <select id="treatment" name="treatment" required>
                <option value="">Select Treatment</option>
                <!-- The treatments will be populated by the JavaScript function -->
            </select>

            <label for="vaccination">Vaccination:</label>
            <select id="vaccination" name="vaccination" required>
                <option value="">Select Vaccination</option>
                <option value="FMD Vaccine" <?php echo ($record['vaccination'] == 'FMD Vaccine') ? 'selected' : ''; ?>>FMD Vaccine</option>
                <option value="CBPP Vaccine" <?php echo ($record['vaccination'] == 'CBPP Vaccine') ? 'selected' : ''; ?>>CBPP Vaccine</option>
                <option value="Brucellosis Vaccine" <?php echo ($record['vaccination'] == 'Brucellosis Vaccine') ? 'selected' : ''; ?>>Brucellosis Vaccine</option>
                <option value="Black Quarter Vaccine" <?php echo ($record['vaccination'] == 'Black Quarter Vaccine') ? 'selected' : ''; ?>>Black Quarter Vaccine</option>
                <option value="Anthrax Vaccine" <?php echo ($record['vaccination'] == 'Anthrax Vaccine') ? 'selected' : ''; ?>>Anthrax Vaccine</option>
                <option value="Lumpy Skin Disease Vaccine" <?php echo ($record['vaccination'] == 'Lumpy Skin Disease Vaccine') ? 'selected' : ''; ?>>Lumpy Skin Disease Vaccine</option>
                <option value="Sheep and Goat Pox Vaccine" <?php echo ($record['vaccination'] == 'Sheep and Goat Pox Vaccine') ? 'selected' : ''; ?>>Sheep and Goat Pox Vaccine</option>
                <option value="Newcastle Disease Vaccine" <?php echo ($record['vaccination'] == 'Newcastle Disease Vaccine') ? 'selected' : ''; ?>>Newcastle Disease Vaccine</option>
                <option value="Avian Influenza Vaccine" <?php echo ($record['vaccination'] == 'Avian Influenza Vaccine') ? 'selected' : ''; ?>>Avian Influenza Vaccine</option>
                <option value="Rabies Vaccine" <?php echo ($record['vaccination'] == 'Rabies Vaccine') ? 'selected' : ''; ?>>Rabies Vaccine</option>
                <option value="Bovine Viral Diarrhea Vaccine" <?php echo ($record['vaccination'] == 'Bovine Viral Diarrhea Vaccine') ? 'selected' : ''; ?>>Bovine Viral Diarrhea Vaccine</option>
                <option value="Leptospirosis Vaccine" <?php echo ($record['vaccination'] == 'Leptospirosis Vaccine') ? 'selected' : ''; ?>>Leptospirosis Vaccine</option>
                <option value="Coccidiosis Vaccine" <?php echo ($record['vaccination'] == 'Coccidiosis Vaccine') ? 'selected' : ''; ?>>Coccidiosis Vaccine</option>
                <option value="PPR Vaccine" <?php echo ($record['vaccination'] == 'PPR Vaccine') ? 'selected' : ''; ?>>Peste des Petits Ruminants Vaccine</option>
                <option value="Porcine Parvovirus Vaccine" <?php echo ($record['vaccination'] == 'Porcine Parvovirus Vaccine') ? 'selected' : ''; ?>>Porcine Parvovirus Vaccine</option>
            </select>

            <input type="submit" value="Update Health Record" class="button">
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
