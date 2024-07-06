<?php
require('mysqli_connect.php');

// Fetch records for Year 0
$q = "SELECT DBH, Height, SpeciesGroup FROM newforest WHERE Status='Keep'";
$r = mysqli_query($dbc, $q);

if (!$r) {
    die("Query failed: " . mysqli_error($dbc));
}

$growth_rates = [
    1 => 0.6,
    2 => 0.6,
    3 => 0.5,
    5 => 0.5
];

$summary_data = [];

while ($row = mysqli_fetch_assoc($r)) {
    $dbh = $row['DBH'];
    $height = $row['Height'];
    $species_group = $row['SpeciesGroup'];
    $growth_rate = isset($growth_rates[$species_group]) ? $growth_rates[$species_group] : 0;

    $dbh_30 = $dbh + ($growth_rate * 30);
    $volume_0 = 0.00007854 * $dbh ** 2 * $height;
    $volume_30 = 0.00007854 * $dbh_30 ** 2 * $height;
    $damage = $dbh * 0.05; // Example damage calculation formula

    $summary_data[] = [
        'SpeciesGroup' => $species_group,
        'DBH_0' => $dbh,
        'Height' => $height,
        'Volume_0' => $volume_0,
        'DBH_30' => $dbh_30,
        'Volume_30' => $volume_30,
        'Damage' => $damage
    ];
}

mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Summary Data - Year 30</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/icon.png" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .table {
            width: 80%;
            font-size: 12px;
            margin: 0 auto;
        }

        .table th,
        .table td {
            padding: 8px;
        }
    </style>
</head>

<body>
<header id="header" class="fixed-top ">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-xl-9 d-flex align-items-center justify-content-lg-between">
					<h1 class="logo me-auto me-lg-0"><a href="index.php">Forest Tree</a></h1>

					<nav id="navbar" class="navbar order-last order-lg-0">
					<ul>
					<li><a class="nav-link scrollto active" href="index.php">Home</a></li>
					<li class="dropdown"><a href="#"><span>View Forest</span> <i class="bi bi-chevron-down"></i></a>
					<ul>
					<li><a href='forest.php'>View Forest Data</a></li>
					<li><a href='forest1.php'>Trees Per Hectare</a></li>
					<li><a href='standtable1.php'>Stand Table</a></li>
					</ul>
					<li class="dropdown"><a href="#"><span>Year 0</span> <i class="bi bi-chevron-down"></i></a>
					<ul>
					<li><a href='standtable2.php'>Stand Table</a></li>
					<li><a href='cutlist.php'>Tree Cut List</a></li>
					<li><a href='victimlist.php'>Victim List</a></li>
					<li><a href='production0.php'>Production Data - Time 0</a></li>
					<li><a href='analysis0.php'>Analysis</a></li>
					</ul>

					</li>
					<li class="dropdown"><a href="#"><span>Year 30</span> <i class="bi bi-chevron-down"></i></a>
					<ul>
					<li><a href='standtable4.php'>Stand Table 1</a></li>
					<li><a href='standtable3.php'>Stand Table 2</a></li>
					<li><a href='analysis30.php'>Analysis</a></li>
					<li><a href='treestatus.php'>Tree Status</a></li>
					<li><a href='Summary30.php'>Summary Data - Year 30</a></li>
					</ul>
					</li>

					<li class="dropdown"><a href="#"><span>Regime</span> <i class="bi bi-chevron-down"></i></a>
					<ul>
					<li><a href='regime45.php'>Regime 45</a></li>
					<li><a href='regime50.php'>Regime 50</a></li>
					<li><a href='regime55.php'>Regime 55</a></li>
					<li><a href='regime60.php'>Regime 60</a></li>
					</ul>
					</li>
					<li><a class="nav-link scrollto" href='treedistribution.php'>Tree Distibution</a></li>
					<li><a class="nav-link scrollto" href='about.php'>About Us</a></li>
					</ul>
					
					</li>
					
					</ul>
					<i class="bi bi-list mobile-nav-toggle"></i>
					</nav>

					<a href="random.php" class="get-started-btn scrollto">Generate Data</a>
				</div>
			</div>
		</div>
	</header>
    
    <section id="hero" class="d-flex flex-column justify-content-center">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-8">
					<h1>Summary Data - Year 30</h1>
					<a href="#about" class="glightbox play-btn mb-4"></a>
				</div>
			</div>
		</div>
	</section>

<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Summary Data - Year 30</h2>
                <p>Summary of production, damage, growth, and production for Year 30.</p>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Species Group</th>
                                <th>DBH (Time 0)</th>
                                <th>Height</th>
                                <th>Volume (Time 0)</th>
                                <th>DBH (Year 30)</th>
                                <th>Volume (Year 30)</th>
                                <th>Damage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($summary_data as $data) : ?>
                                <tr>
                                    <td><?php echo $data['SpeciesGroup']; ?></td>
                                    <td><?php echo $data['DBH_0']; ?></td>
                                    <td><?php echo $data['Height']; ?></td>
                                    <td><?php echo $data['Volume_0']; ?></td>
                                    <td><?php echo $data['DBH_30']; ?></td>
                                    <td><?php echo $data['Volume_30']; ?></td>
                                    <td><?php echo $data['Damage']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
