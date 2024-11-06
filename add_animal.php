<?php
include 'db_connect.php';

$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $animal_name = filter_input(INPUT_POST, 'animal_name', FILTER_SANITIZE_STRING);
    $species = filter_input(INPUT_POST, 'species', FILTER_SANITIZE_STRING);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $breed = filter_input(INPUT_POST, 'breed', FILTER_SANITIZE_STRING);
    $weight = filter_input(INPUT_POST, 'weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Get the current timestamp for created_at
    $created_at = date('Y-m-d H:i:s');

    // Insert into database
    $sql = "INSERT INTO animals (animal_name, species, breed, age, weight, created_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdss", $animal_name, $species, $breed, $age, $weight, $created_at);

    if ($stmt->execute()) {
        $message = "New animal added successfully!";
    } else {
        $message = "An error occurred while adding the animal.";
        // Log the detailed error for debugging
        error_log("Database error: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Animal - Livestock Information System</title>
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
        form {
            display: flex;
            flex-direction: column;
        }
        input, select, button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #81C784;
        }
        label {
            font-weight: bold;
        }
        .image-preview {
            margin-top: 10px;
            display: none;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }  /* Navigation Bar Styles */
        
       
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            position: relative;
            z-index: 1000;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }.button {
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
</head>
<body>
<header class="header">
    <img src="https://i.im.ge/2024/07/12/VNRnsD.image-2024-07-11-224909046-removebg-preview.png" alt="Lenku Livestock Logo">
    <span>Lenku Livestock</span>
    <h1>Add Livestock</h1>
</header>

      <!-- Navigation Bar -->
    
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>


    <div class="container">
        <h2>Fill in the  Livestock Details</h2>
  <div id="message" style="color: red; margin-bottom: 15px;">
    <?php echo htmlspecialchars($message); ?>
</div>


        <form method="POST" action="add_animal.php" enctype="multipart/form-data">
            <label for="animal_name">Livestock Name</label>
            <input type="text" name="animal_name" placeholder="Livestock Name" required>
            <label for="species">Species</label>
            <select id="species" name="species" required>
                <option value="">Select Species</option>
                <option value="cattle">Cattle</option>
                <option value="goat">Goat</option>
                <option value="sheep">Sheep</option>
                <option value="camel">Camel</option>
                <option value="pig">Pig</option>
                <option value="donkey">Donkey</option>
                <option value="rabbit">Rabbit</option>
                <option value="chicken">Chicken</option>
                <option value="duck">Duck</option>
            </select>

            <label for="breed">Breed</label>
            <select id="breed" name="breed" required>
                <option value="">Select Breed</option>
            </select>


            <label for="age">Age(yrs)</label>
            <input type="number" name="age" placeholder="Age" required>



            <label for="weight">Weight(kg)</label>
            <input type="number" name="weight" placeholder="Weight" required>


            <button type="submit">Add Livestock </button>
        </form>  

  <a href="view_animals.php" class="button">View Livestocks</a>

    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Livestock Information System. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Breed Selection and Image Preview -->
    <script>
        const speciesSelect = document.getElementById('species');
        const breedSelect = document.getElementById('breed');

        speciesSelect.addEventListener('change', function() {
            const species = this.value;
            breedSelect.innerHTML = ''; // Clear existing options

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

        // Image preview function
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('imagePreview');

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
