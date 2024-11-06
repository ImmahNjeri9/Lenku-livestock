<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $animal_name = filter_var($_POST['animal_name'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $species = filter_var($_POST['species'], FILTER_SANITIZE_STRING);
    $breed = filter_var($_POST['breed'], FILTER_SANITIZE_STRING);
    $weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Prepare and execute SQL statement
    $sql = "UPDATE animals SET animal_name=?, age=?, species=?, breed=?, weight=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $animal_name, $age, $species, $breed, $weight, $id);

    if ($stmt->execute()) {
        // Redirect to view page after successful update
        header("Location: view_animals.php?success=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

// Fetch animal data
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$sql = "SELECT * FROM animals WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Animal - Livestock Information System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        form {
            max-width: 500px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Edit Livestock</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
        <label for="animal_name">Livestock Name</label>
        <input type="text" name="animal_name" value="<?php echo $animal['animal_name']; ?>" placeholder="Livestock Name" required>

        <label for="species">Species</label>
        <select id="species" name="species" required>
            <option value="">Select Species</option>
            <option value="cattle" <?php if ($animal['species'] == 'cattle') echo 'selected'; ?>>Cattle</option>
            <option value="goat" <?php if ($animal['species'] == 'goat') echo 'selected'; ?>>Goat</option>
            <option value="sheep" <?php if ($animal['species'] == 'sheep') echo 'selected'; ?>>Sheep</option>
            <option value="camel" <?php if ($animal['species'] == 'camel') echo 'selected'; ?>>Camel</option>
            <option value="pig" <?php if ($animal['species'] == 'pig') echo 'selected'; ?>>Pig</option>
            <option value="donkey" <?php if ($animal['species'] == 'donkey') echo 'selected'; ?>>Donkey</option>
            <option value="rabbit" <?php if ($animal['species'] == 'rabbit') echo 'selected'; ?>>Rabbit</option>
            <option value="chicken" <?php if ($animal['species'] == 'chicken') echo 'selected'; ?>>Chicken</option>
            <option value="duck" <?php if ($animal['species'] == 'duck') echo 'selected'; ?>>Duck</option>
        </select>

        <label for="breed">Breed</label>
        <select id="breed" name="breed" required>
            <option value="">Select Breed</option>
            <?php
            // Populate breed options based on species
            if ($animal['species'] == 'cattle') {
                $breeds = ['boran', 'fresian', 'sahiwal', 'guernsey', 'ayrshire', 'jersey', 'red_poll', 'charolais', 'simmental', 'hereford', 'brown_swiss', 'angus', 'brahman', 'shorthorn', 'longhorn'];
            } elseif ($animal['species'] == 'goat') {
                $breeds = ['galla', 'saanen', 'boer', 'toggenburg', 'kamorai', 'alpine', 'nubian', 'anglo_nubian', 'small_east_african', 'somali', 'east_african_goat', 'barbari', 'oberhasli', 'pygmy', 'peacock_goat'];
            } elseif ($animal['species'] == 'sheep') {
                $breeds = ['dorper', 'merino', 'blackhead_persian', 'redhead_persian', 'corriedale', 'romney_marsh', 'awassi', 'suffolk', 'rambouillet', 'masham', 'blackface', 'cambridge', 'lincoln', 'finnsheep', 'wensleydale'];
            } elseif ($animal['species'] == 'camel') {
                $breeds = ['somali', 'rendille', 'gabbra', 'turkana', 'oromo', 'kuchi', 'arabian', 'kalari', 'raika', 'karai', 'bishari', 'bedouin', 'magra', 'bikaneri', 'jalori'];
            } elseif ($animal['species'] == 'pig') {
                $breeds = ['large_white', 'landrace', 'duroc', 'hampshire', 'berkshire', 'pietrain', 'meishan', 'gloucestershire_old_spot', 'tamworth', 'saddleback', 'poland_china', 'chester_white', 'kunekune', 'ossabaw_island', 'mangalitsa'];
            } elseif ($animal['species'] == 'donkey') {
                $breeds = ['nubian', 'somali_wild_ass', 'andalusian', 'mammoth_jackstock', 'burro', 'poitou', 'pygmy', 'minature_donkey', 'ethiopian', 'jerusalem', 'sardinian', 'catria', 'greek', 'portuguese', 'tunisian'];
            } elseif ($animal['species'] == 'rabbit') {
                $breeds = ['californian', 'new_zealand_white', 'flemish_giant', 'dutch', 'rex', 'mini_rex', 'lionhead', 'english_angora', 'himalayan', 'french_lop', 'english_lop', 'harlequin', 'holland_lop', 'chinchilla', 'tan'];
            } elseif ($animal['species'] == 'chicken') {
                $breeds = ['rhode_island_red', 'leghorn', 'sussex', 'poultry_hybrid', 'orpington', 'silkie', 'bantam', 'australorp', 'barred_rock', 'brahma', 'ameraucana', 'wyandotte', 'faverolles', 'cornish_cross', 'langshan'];
            } elseif ($animal['species'] == 'duck') {
                $breeds = ['pekin', 'khaki_campbell', 'indian_runner', 'muscovy', 'mallard', 'rouen', 'cayuga', 'buff_orpington', 'crested', 'call_duck', 'silver_appleyard', 'swedish', 'magpie', 'ancona', 'saxony'];
            }

            // Populate breed options
            if (isset($breeds)) {
                foreach ($breeds as $breed_option) {
                    $selected = ($animal['breed'] == $breed_option) ? 'selected' : '';
                    echo "<option value=\"$breed_option\" $selected>" . ucfirst(str_replace('_', ' ', $breed_option)) . "</option>";
                }
            }
            ?>
        </select>
 <label for="age">Age(yrs)</label>
            <input type="number" name="age" value="<?php echo $animal['age']; ?>"placeholder="Age" required>


        <label for="weight">Weight (kg)</label>
        <input type="number" name="weight" value="<?php echo $animal['weight']; ?>" placeholder="Weight" required>

        <button type="submit">Update Animal</button>
    </form>

    <script>
        const speciesSelect = document.getElementById('species');
        const breedSelect = document.getElementById('breed');

        speciesSelect.addEventListener('change', function() {
            const species = this.value;
            breedSelect.innerHTML = ''; // Clear existing options
            let options = '<option value="">Select Breed</option>'; // Add default option

 if (species === 'cattle') {
                breedSelect.innerHTML = `
                    <option value="boran">Boran</option>
                    <option value="fresian">Fresian</option>
                    <option value="sahiwal">Sahiwal</option>
                    <option value="guernsey">Guernsey</option>
                    <option value="ayrshire">Ayrshire</option>
                    <option value="jersey">Jersey</option>
                    <option value="red_poll">Red Poll</option>
                    <option value="charolais">Charolais</option>
                    <option value="simmental">Simmental</option>
                    <option value="hereford">Hereford</option>
                    <option value="brown_swiss">Brown Swiss</option>
                    <option value="angus">Angus</option>
                    <option value="brahman">Brahman</option>
                    <option value="shorthorn">Shorthorn</option>
                    <option value="longhorn">Longhorn</option>
                `;
            } else if (species === 'goat') {
                breedSelect.innerHTML = `
                    <option value="galla">Galla</option>
                    <option value="saanen">Saanen</option>
                    <option value="boer">Boer</option>
                    <option value="toggenburg">Toggenburg</option>
                    <option value="kamorai">Kamorai</option>
                    <option value="alpine">Alpine</option>
                    <option value="nubian">Nubian</option>
                    <option value="anglo_nubian">Anglo-Nubian</option>
                    <option value="small_east_african">Small East African</option>
                    <option value="somali">Somali</option>
                    <option value="east_african_goat">East African Goat</option>
                    <option value="barbari">Barbari</option>
                    <option value="oberhasli">Oberhasli</option>
                    <option value="pygmy">Pygmy</option>
                    <option value="peacock_goat">Peacock Goat</option>
                `;
            } else if (species === 'sheep') {
                breedSelect.innerHTML = `
                    <option value="dorper">Dorper</option>
                    <option value="merino">Merino</option>
                    <option value="blackhead_persian">Blackhead Persian</option>
                    <option value="redhead_persian">Redhead Persian</option>
                    <option value="corriedale">Corriedale</option>
                    <option value="romney_marsh">Romney Marsh</option>
                    <option value="awassi">Awassi</option>
                    <option value="suffolk">Suffolk</option>
                    <option value="rambouillet">Rambouillet</option>
                    <option value="masham">Masham</option>
                    <option value="blackface">Blackface</option>
                    <option value="cambridge">Cambridge</option>
                    <option value="lincoln">Lincoln</option>
                    <option value="finnsheep">Finnsheep</option>
                    <option value="wensleydale">Wensleydale</option>
                `;
            } else if (species === 'camel') {
                breedSelect.innerHTML = `
                    <option value="somali">Somali</option>
                    <option value="rendille">Rendille</option>
                    <option value="gabbra">Gabbra</option>
                    <option value="turkana">Turkana</option>
                    <option value="oromo">Oromo</option>
                    <option value="kuchi">Kuchi</option>
                    <option value="arabian">Arabian</option>
                    <option value="kalari">Kalari</option>
                    <option value="raika">Raika</option>
                    <option value="karai">Karai</option>
                    <option value="bishari">Bishari</option>
                    <option value="bedouin">Bedouin</option>
                    <option value="magra">Magra</option>
                    <option value="bikaneri">Bikaneri</option>
                    <option value="jalori">Jalori</option>
                `;
            }else if (species === 'pig') {
            breedSelect.innerHTML = `
                <option value="large_white">Large White</option>
                <option value="landrace">Landrace</option>
                <option value="duroc">Duroc</option>
                <option value="hampshire">Hampshire</option>
                <option value="berkshire">Berkshire</option>
                <option value="pietrain">Pietrain</option>
                <option value="meishan">Meishan</option>
                <option value="gloucestershire_old_spot">Gloucestershire Old Spot</option>
                <option value="tamworth">Tamworth</option>
                <option value="saddleback">Saddleback</option>
                <option value="poland_china">Poland China</option>
                <option value="chester_white">Chester White</option>
                <option value="kunekune">Kunekune</option>
                <option value="ossabaw_island">Ossabaw Island</option>
                <option value="mangalitsa">Mangalitsa</option>
            `;
        } else if (species === 'donkey') {
            breedSelect.innerHTML = `
                <option value="nubian">Nubian</option>
                <option value="somali_wild_ass">Somali Wild Ass</option>
                <option value="andalusian">Andalusian</option>
                <option value="mammoth_jackstock">Mammoth Jackstock</option>
                <option value="burro">Burro</option>
                <option value="poitou">Poitou</option>
                <option value="pygmy">Pygmy Donkey</option>
                <option value="minature_donkey">Miniature Donkey</option>
                <option value="ethiopian">Ethiopian</option>
                <option value="jerusalem">Jerusalem</option>
                <option value="sardinian">Sardinian</option>
                <option value="catria">Catria</option>
                <option value="greek">Greek Donkey</option>
                <option value="portuguese">Portuguese Donkey</option>
                <option value="tunisian">Tunisian Donkey</option>
            `;
        } else if (species === 'rabbit') {
            breedSelect.innerHTML = `
                <option value="californian">Californian</option>
                <option value="new_zealand_white">New Zealand White</option>
                <option value="flemish_giant">Flemish Giant</option>
                <option value="dutch">Dutch</option>
                <option value="rex">Rex</option>
                <option value="mini_rex">Mini Rex</option>
                <option value="lionhead">Lionhead</option>
                <option value="english_angora">English Angora</option>
                <option value="himalayan">Himalayan</option>
                <option value="french_lop">French Lop</option>
                <option value="english_lop">English Lop</option>
                <option value="harlequin">Harlequin</option>
                <option value="holland_lop">Holland Lop</option>
                <option value="chinchilla">Chinchilla</option>
                <option value="tan">Tan</option>
            `;
        } else if (species === 'chicken') {
            breedSelect.innerHTML = `
                <option value="rhode_island_red">Rhode Island Red</option>
                <option value="leghorn">Leghorn</option>
                <option value="sussex">Sussex</option>
                <option value="poultry_hybrid">Poultry Hybrid</option>
                <option value="orpington">Orpington</option>
                <option value="silkie">Silkie</option>
                <option value="bantam">Bantam</option>
                <option value="australorp">Australorp</option>
                <option value="barred_rock">Barred Rock</option>
                <option value="brahma">Brahma</option>
                <option value="ameraucana">Ameraucana</option>
                <option value="wyandotte">Wyandotte</option>
                <option value="faverolles">Faverolles</option>
                <option value="cornish_cross">Cornish Cross</option>
                <option value="langshan">Langshan</option>
            `;
        } else if (species === 'duck') {
            breedSelect.innerHTML = `
                <option value="pekin">Pekin</option>
                <option value="khaki_campbell">Khaki Campbell</option>
                <option value="indian_runner">Indian Runner</option>
                <option value="muscovy">Muscovy</option>
                <option value="mallard">Mallard</option>
                <option value="rouen">Rouen</option>
                <option value="cayuga">Cayuga</option>
                <option value="buff_orpington">Buff Orpington</option>
                <option value="crested">Crested</option>
                <option value="call_duck">Call Duck</option>
                <option value="silver_appleyard">Silver Appleyard</option>
                <option value="swedish">Swedish</option>
                <option value="magpie">Magpie</option>
                <option value="ancona">Ancona</option>
                <option value="saxony">Saxony</option>
            `;
        }
        });

    </script>
</body>
</html>
