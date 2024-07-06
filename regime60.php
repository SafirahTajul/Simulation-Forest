<?php
require('mysqli_connect.php');

// Function to calculate Growth30 based on the given diameter increment rules
function calculateGrowth30($speciesGroup, $dbh) {
    $increments = [
        'Mersawa' => [0.6, 0.5, 0.5, 0.7], // Example increments for Mersawa
        'Keruing' => [0.6, 0.5, 0.5, 0.7], // Example increments for Keruing
        'Dip Marketable' => [0.4, 0.6, 0.5, 0.5, 0.7], // Example increments for Dip Marketable
        'Dip Non Market' => [0.4, 0.6, 0.5, 0.5, 0.7], // Example increments for Dip Non Market
        'Non Dip Market' => [0.4, 0.6, 0.5, 0.5, 0.7], // Example increments for Non Dip Market
        'Non Dip Non Market' => [0.4, 0.6, 0.5, 0.5, 0.7], // Example increments for Non Dip Non Market
        'Others' => [0.4, 0.6, 0.5, 0.5, 0.7] // Example increments for Others
    ];

    $increment = isset($increments[$speciesGroup]) ? $increments[$speciesGroup] : [0.4, 0.6, 0.5, 0.5, 0.7];
    $growth30 = $dbh;
    foreach ($increment as $inc) {
        $growth30 += $inc;
    }
    return $growth30;
}

// Fetch all records from the newforest database with DBH of 60
$q = "SELECT Species AS SpeciesGroup, SUM(Volume) AS Volume, COUNT(*) AS Number, SUM(Production) AS Production, SUM(DamageCrown + DamageStem) AS Damage, AVG(DBH) AS AvgDBH FROM newforest WHERE DBH = 60 GROUP BY Species";
$r = mysqli_query($dbc, $q);

if (!$r) {
    die("Query failed: " . mysqli_error($dbc));
}

$stand_table_data = [];
while ($row = mysqli_fetch_assoc($r)) {
    $row['Growth30'] = calculateGrowth30($row['SpeciesGroup'], $row['AvgDBH']);
    $stand_table_data[] = $row;
}

mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Forest Tree</title>
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
                <h1>rREGIME 60 - STAND TABLE</h1>
                <a href="#about" class="glightbox play-btn mb-4"></a>
            </div>
        </div>
    </div>
</section>

<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Stand Table - Year 30</h2>
                <p>Summary of forest stand characteristics for Year 30 (Diameter 60 only).</p>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Species Group</th>
                                <th>Volume</th>
                                <th>Number</th>
                                <th>Production</th>
                                <th>Damage</th>
                                <th>Growth30</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stand_table_data as $data) : ?>
                                <tr>
                                    <td><?php echo $data['SpeciesGroup']; ?></td>
                                    <td><?php echo $data['Volume']; ?></td>
                                    <td><?php echo $data['Number']; ?></td>
                                    <td><?php echo $data['Production']; ?></td>
                                    <td><?php echo $data['Damage']; ?></td>
                                    <td><?php echo number_format($data['Growth30'], 2); ?></td>
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
