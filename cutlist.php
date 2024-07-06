<?php
// Connect to the database
require('mysqli_connect.php');

// Function to get list of trees to cut
function getTreesToCut($dbc, $speciesGroups, $minDiameter) {
    $treesToCut = [];

    $query = "SELECT * FROM newforest 
              WHERE SpeciesGroup IN (" . implode(',', $speciesGroups) . ") 
              AND DBH >= $minDiameter";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $treesToCut[] = $row;
    }

    return $treesToCut;
}

// Define species groups to cut and minimum diameter
$speciesGroupsToCut = [1, 2, 3, 5];
$minDiameter = 45;

// Get trees to cut
$treesToCut = getTreesToCut($dbc, $speciesGroupsToCut, $minDiameter);

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

        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        .page-item {
            margin: 5px;
        }

        .page-link {
            padding: 5px 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #fff;
            color: #007bff;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .page-link:hover {
            background-color: #007bff;
            color: #fff;
        }

        .page-item.active .page-link {
            background-color: #007bff;
            color: #fff;
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
                <h1>Tree Cut List</h1>
                <a href="#about" class="glightbox play-btn mb-4"></a>
            </div>
        </div>
    </div>
</section>

<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Tree Cut List</h2>
                <p>List of trees to be cut based on diameter and species group.</p>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>BlockX</th>
                                <th>BlockY</th>
                                <th>CoordinateX</th>
                                <th>CoordinateY</th>
                                <th>SpeciesGroup</th>
                                <th>Species</th>
                                <th>DBH</th>
                                <th>Height</th>
                                <th>Volume</th>
                                <th>Cutting Direction</th>
                                <th>Damage Crown</th>
                                <th>Damage Stem</th>
                                <th>Production</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($treesToCut as $tree) : ?>
                                <tr>
                                    <td><?php echo $tree['BlockX']; ?></td>
                                    <td><?php echo $tree['BlockY']; ?></td>
                                    <td><?php echo $tree['CoordinateX']; ?></td>
                                    <td><?php echo $tree['CoordinateY']; ?></td>
                                    <td><?php echo $tree['SpeciesGroup']; ?></td>
                                    <td><?php echo $tree['Species']; ?></td>
                                    <td><?php echo $tree['DBH']; ?></td>
                                    <td><?php echo $tree['Height']; ?></td>
                                    <td><?php echo $tree['Volume']; ?></td>
                                    <td><?php echo $tree['CutAngle']; ?></td>
                                    <td><?php echo $tree['DamageCrown']; ?></td>
                                    <td><?php echo $tree['DamageStem']; ?></td>
                                    <td><?php echo $tree['Production']; ?></td>
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
