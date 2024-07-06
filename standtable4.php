<?php
// Database connection
require('mysqli_connect.php');

// Query to get species group, count of trees, and sum of volumes within diameter ranges
$q = "SELECT nf.SpeciesGroup,
    SUM(CASE WHEN DBH >= 45 AND DBH < 60 THEN Volume ELSE 0 END) AS `45-60 Volume`,
    SUM(CASE WHEN DBH >= 60 THEN Volume ELSE 0 END) AS `60+ Volume`,
    SUM(CASE WHEN DBH >= 45 AND DBH < 60 THEN 1 ELSE 0 END) AS `45-60 Num`,
    SUM(CASE WHEN DBH >= 60 THEN 1 ELSE 0 END) AS `60+ Num`
    FROM newforest nf 
    GROUP BY nf.SpeciesGroup";

$r = mysqli_query($dbc, $q);

if (!$r) {
    die("Query failed: " . mysqli_error($dbc));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
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
	<!-- ======= Hero Section ======= -->
	<section id="hero" class="d-flex flex-column justify-content-center">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-8">
					<h1>Stand Table</h1>
					<a href="https://youtu.be/U49HdsYu4qo?si=1AoBUVg3oMeWUrr2" class="glightbox play-btn mb-4"></a>
				</div>
			</div>
		</div>
	</section>
    
    <main id="main">
		<section id="about" class="about">
			<div class="container" data-aos="fade-up">
				<div class="section-title">
					<h2>Stand  Table</h2>
					<p>Table showing data of trees in stand 1.</p>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">Species Group</th>
                                <th colspan="2">5-15</th>
                                <th colspan="2">15-30</th>
                                <th colspan="2">30-45</th>
                                <th colspan="2">45-60</th>
                                <th colspan="2">60+</th>
                                <th colspan="2">Total</th>
                            </tr>
                            <tr>
                                <th>Volume</th>
                                <th>Num</th>
                                <th>Volume</th>
                                <th>Num</th>
                                <th>Volume</th>
                                <th>Num</th>
                                <th>Volume</th>
                                <th>Num</th>
                                <th>Volume</th>
                                <th>Num</th>
                                <th>Volume</th>
                                <th>Num</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total_vol_515 = $total_num_515 = $total_vol_1530 = $total_num_1530 = 0;
                        $total_vol_3045 = $total_num_3045 = $total_vol_4560 = $total_num_4560 = 0;
                        $total_vol_60 = $total_num_60 = $grand_total_vol = $grand_total_num = 0;

                        while ($row = mysqli_fetch_assoc($r)) {
                            $row_total_vol = $row['45-60 Volume'] + $row['60+ Volume'];
                            $row_total_num = $row['45-60 Num'] + $row['60+ Num'];

                            echo "<tr>
                                <td>{$row['SpeciesGroup']}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>{$row['45-60 Volume']}</td>
                                <td>{$row['45-60 Num']}</td>
                                <td>{$row['60+ Volume']}</td>
                                <td>{$row['60+ Num']}</td>
                                <td>{$row_total_vol}</td>
                                <td>{$row_total_num}</td>
                            </tr>";

                            $total_vol_515 += 0;
                            $total_num_515 += 0;
                            $total_vol_1530 += 0;
                            $total_num_1530 += 0;
                            $total_vol_3045 += 0;
                            $total_num_3045 += 0;
                            $total_vol_4560 += $row['45-60 Volume'];
                            $total_num_4560 += $row['45-60 Num'];
                            $total_vol_60 += $row['60+ Volume'];
                            $total_num_60 += $row['60+ Num'];
                            $grand_total_vol += $row_total_vol;
                            $grand_total_num += $row_total_num;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th><?php echo $total_vol_4560; ?></th>
                                <th><?php echo $total_num_4560; ?></th>
                                <th><?php echo $total_vol_60; ?></th>
                                <th><?php echo $total_num_60; ?></th>
                                <th><?php echo $grand_total_vol; ?></th>
                                <th><?php echo $grand_total_num; ?></th>
                            </tr>
                        </tfoot>
						</table>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

</body>
</html>
