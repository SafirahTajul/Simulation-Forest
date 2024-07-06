<?php
// Connect to the database
require('mysqli_connect.php');

// Function to calculate volume
function calculateVolume($dbh, $height) {
    return 3.142 * pow(($dbh / 200), 2) * $height * 0.50;
}

// Function to determine damage based on cutting angle and position
function calculateDamage($dbh, $height, $cutAngle, $x, $y) {
    $damageCrown = 0;
    $damageStem = 0;

    // Example calculation for crown damage (modify based on actual requirements)
    $radiusCrown = $height * 0.2; // Assuming crown radius is 20% of height
    $x1 = $x + $radiusCrown * cos(deg2rad($cutAngle));
    $y1 = $y + $radiusCrown * sin(deg2rad($cutAngle));

    // Check if crown damage occurs (within 5m of the target)
    if (sqrt(pow($x1 - $x, 2) + pow($y1 - $y, 2)) <= 5) {
        $damageCrown = rand(50, 100); // Example calculation, replace with actual formula
    }

    // Example calculation for stem damage (modify based on actual requirements)
    $radiusStem = $dbh / 2; // Assuming stem radius is half of dbh
    $x2 = $x + $radiusStem * cos(deg2rad($cutAngle));
    $y2 = $y + $radiusStem * sin(deg2rad($cutAngle));

    // Check if stem damage occurs (within 5m of the target)
    if (sqrt(pow($x2 - $x, 2) + pow($y2 - $y, 2)) <= 5) {
        $damageStem = rand(20, 50); // Example calculation, replace with actual formula
    }

    return [$damageCrown, $damageStem];
}

// Generate random forest data
function generateForest($numTrees, $hectares, $speciesGroups) {
    $forest = [];
    $cutTreeCount = 0;
    $maxCutTrees = 23000; // At least 23,000 trees need to be cut

    foreach ($hectares as $hectare) {
        $blockX = $hectare['BlockX'];
        $blockY = $hectare['BlockY'];

        for ($i = 0; $i < $numTrees; $i++) {
            $x = rand(0, 99) + rand() / getrandmax();
            $y = rand(0, 99) + rand() / getrandmax();
            $speciesGroup = array_rand($speciesGroups);
            $species = $speciesGroups[$speciesGroup][array_rand($speciesGroups[$speciesGroup])];
            $dbh = rand(5, 60); // Diameter at breast height
            $height = rand(10, 30); // Merchantable height
            $volume = calculateVolume($dbh, $height);

            // Determine status
            if (in_array($speciesGroup, [1, 2, 3, 5]) && $dbh >= 45 && $cutTreeCount < $maxCutTrees) {
                $status = 'Cut';
                $cutTreeCount++;
            } else {
                $status = 'Keep';
            }

            // Additional attributes
            $cutAngle = rand(0, 360);
            list($damageCrown, $damageStem) = calculateDamage($dbh, $height, $cutAngle, $x, $y);
            $production = $volume; // Production matches volume

            $forest[] = [
                'x' => $x,
                'y' => $y,
                'blockX' => $blockX,
                'blockY' => $blockY,
                'speciesGroup' => $speciesGroup,
                'species' => $species,
                'dbh' => $dbh,
                'height' => $height,
                'volume' => $volume,
                'cutAngle' => $cutAngle,
                'damageCrown' => $damageCrown,
                'damageStem' => $damageStem,
                'production' => $production,
                'status' => $status
            ];
        }
    }
    return $forest;
}

// Define species groups
$speciesGroups = [
    1 => ['Mersawa', 'PHDEAK'],
    2 => ['Keruing', 'CHHOETEAL BANGKOUNEANGO', 'CHHOETEAL BRENG', 'CHHOETEAL CHHNGAR', 'CHHOETEAL MOSAU'],
    3 => ['Dipterocarpus Commercial', 'CHORCHONG', 'CHRASMAS', 'KOKI MOSAU', 'KOKI PHNONG\KAMNHAN'],
    4 => ['Dipterocarpus Non Commercial', 'KCHOV/KAMLENG', 'KOKI DEK', 'KOKI KHASAC', 'KOKI THMOR'],
    5 => ['Non Dipterocarpus Commercial', 'ANGKOT KHMAU', 'ATITH/NEANG PHOR EK', 'BENG', 'BOSNEAK'],
    6 => ['Non Dipterocarpus Non Commercial', 'ANGKANH', 'ANGKAT TMAAT', 'ANGKRANG PHNOM', 'ATEANG/ROTEANG']
];

// Define hectares (blocks of forest)
$hectares = [
    ['BlockX' => 1, 'BlockY' => 1],
    ['BlockX' => 1, 'BlockY' => 2],
    ['BlockX' => 2, 'BlockY' => 1],
    ['BlockX' => 2, 'BlockY' => 2],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_data'])) {
    // Generate forest
    $numTreesPerHectare = 50000 / count($hectares); // Number of trees per hectare
    $forest = generateForest($numTreesPerHectare, $hectares, $speciesGroups);

    // Insert forest data into the database
    foreach ($forest as $tree) {
        $query = "INSERT INTO newforest (BlockX, BlockY, CoordinateX, CoordinateY, SpeciesGroup, Species, DBH, Height, Volume, CutAngle, DamageCrown, DamageStem, Production, Status)
              VALUES ('{$tree['blockX']}', '{$tree['blockY']}', '{$tree['x']}', '{$tree['y']}', '{$tree['speciesGroup']}', '{$tree['species']}', '{$tree['dbh']}', '{$tree['height']}', '{$tree['volume']}', '{$tree['cutAngle']}', '{$tree['damageCrown']}', '{$tree['damageStem']}', '{$tree['production']}', '{$tree['status']}')";
        mysqli_query($dbc, $query);
    }

    // Determine victims based on cut trees
    $cutTreesQuery = "SELECT * FROM newforest WHERE Status = 'Cut'";
    $cutTreesResult = mysqli_query($dbc, $cutTreesQuery);

    while ($cutTree = mysqli_fetch_assoc($cutTreesResult)) {
        $cutAngle = $cutTree['CutAngle'];
        $x0 = $cutTree['CoordinateX'];
        $y0 = $cutTree['CoordinateY'];
        $height = $cutTree['Height'];

        $angleMin = deg2rad($cutAngle - 1);
        $angleMax = deg2rad($cutAngle + 1);

        $x1 = $x0 + $height * cos($angleMin);
        $y1 = $y0 + $height * sin($angleMin);
        $x2 = $x0 + $height * cos($angleMax);
        $y2 = $y0 + $height * sin($angleMax);

        // Fetch trees within the bounding box of the triangle
        $victimQuery = "SELECT * FROM newforest WHERE Status = 'Keep' AND 
                CoordinateX BETWEEN LEAST($x0, $x1, $x2) AND GREATEST($x0, $x1, $x2) AND 
                CoordinateY BETWEEN LEAST($y0, $y1, $y2) AND GREATEST($y0, $y1, $y2) AND 
                BlockX = {$cutTree['BlockX']} AND BlockY = {$cutTree['BlockY']}";
        $victimResult = mysqli_query($dbc, $victimQuery);

        while ($victimTree = mysqli_fetch_assoc($victimResult)) {
            if (isWithinTriangle($x0, $y0, $x1, $y1, $x2, $y2, $victimTree['CoordinateX'], $victimTree['CoordinateY'])) {
                // Update the victim tree's status
                $updateVictimQuery = "UPDATE newforest SET Status = 'Victim' WHERE TreeNum = {$victimTree['TreeNum']}";
                mysqli_query($dbc, $updateVictimQuery);
            }
        }
    }

    $message = "Forest data generated successfully.";
}

// Function to check if a point is within a triangle
function isWithinTriangle($x0, $y0, $x1, $y1, $x2, $y2, $px, $py) {
    $d1 = sign($px, $py, $x0, $y0, $x1, $y1);
    $d2 = sign($px, $py, $x1, $y1, $x2, $y2);
    $d3 = sign($px, $py, $x2, $y2, $x0, $y0);

    $hasNeg = ($d1 < 0) || ($d2 < 0) || ($d3 < 0);
    $hasPos = ($d1 > 0) || ($d2 > 0) || ($d3 > 0);

    return !($hasNeg && $hasPos);
}

// Helper function to calculate the sign of the vector cross-product
function sign($px, $py, $x1, $y1, $x2, $y2) {
    return ($px - $x2) * ($y1 - $y2) - ($x1 - $x2) * ($py - $y2);
}

mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Random Data</title>
    <link rel="icon" type="image/x-icon" href="icon.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: url(assets/img/Background.jpg) center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: sans-serif;
        }
        nav.navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000; 
            font-family: montserrat;
            margin: 10px 5px;
        }

        .company-name {
            font-size: 30px;
            font-family: times new roman;
            color: white;
            text-decoration: none;
            font-weight: bold;
            background-color: #141a14;
            padding: 10px 20px;
        }
        .navbar-left a {
            color: #fff;
            text-decoration: none;
            font-size: 24px;
        }
        .navbar-right a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin-left: 20px;
        }
        .navbar-right a:hover {
            color: #ccc;
        }
        main.table {
            margin-top: 70px;
            width: 82vw;
            height: 90vh;
            background-color: #fff5;
            backdrop-filter: blur(7px);
            box-shadow: 0 .4rem .8rem #000500;
            border-radius: .8rem;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
        }
        .left-section {
            text-align: left;
            margin-right: auto;
        }
        .right-section {
            text-align: center;
            max-width: 50%;
        }
        h1 {
            font-family: sans-serif;
            margin: 0;
            color: #00BF63;
            font-size: 90px;
            display: inline-block;
            margin-left: 40px;
        }
        h2 {
            color: #0097B2;
            font-family: sans-serif;
            margin: 0;
            font-size: 90px;
            margin-left: 40px;
        }
        .desc {
            font-family: sans-serif;
            font-size: 30px;
            text-align: center;
            margin-bottom: 20px;
            max-width: 90%;
            text-align: justify;
            line-height: 1.5;
        }
        .generate-button {
            background-color: white;
            color: black;
            border: 1px solid #0097B2;
            padding: 10px 20px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px;
            border-width: 2px;
            cursor: pointer;  
        }
        .generate-button:hover {
            background-color: #0097B2;
            color: white;
        }
        .success-message {
            font-family: sans-serif;
            font-size: 20px;
            color: #00BF63;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-left">
        <a href="index.php" class="company-name">New Forest</a>
    </div>
    <div class="navbar-right">
        <a href="index.php" class="nav-item">Home</a>
        <a href='forest.php' class="nav-item">Data</a>
    </div>
</nav>
<main class="table">
    <section class="left-section">
        <h1>SAVE</h1>
        <h2>FOREST</h2>
    </section>
    <section class="right-section">
        <p class="desc">Welcome to our Forest Data Explorer! Dive into the fascinating world of forestry with our interactive platform. Explore various tree species, their characteristics, and distribution. Learn about tree diameters, heights, and more through our extensive database.</p>
        <form action="" method="post">
            <input type="hidden" name="generate_data" value="1">
            <input class='generate-button' type="submit" value="Generate Data">
        </form>
        <?php if (isset($message)): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
