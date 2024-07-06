<?php
require ('mysqli_connect.php');
$q= "SELECT COUNT(*) AS Total FROM newforest"; // Modify column_name and your_table accordingly

$r = mysqli_query($dbc, $q);

if ($r) {
    $row = mysqli_fetch_assoc($r);
    $Total = $row["Total"];
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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

	<!-- Vendor CSS Files -->
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
	<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

	<!-- Template Main CSS File -->
	<link href="assets/css/style.css" rel="stylesheet">
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
					<h1>Forest Tree - Experience Nature's Symphony</h1>
					<h2>Dive into the Wilderness with Our Forest Simulation!</h2>
					<a href="https://youtu.be/U49HdsYu4qo?si=1AoBUVg3oMeWUrr2" class="glightbox play-btn mb-4"></a>
				</div>
			</div>
		</div>
	</section>
	<!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
		<div class="container">
			<div class="text-center title">
				<h3>Tree Available Data</h3>
				<p>Malaysia Used to be the largest tropical timber and timber products exporter in the world</p>
			</div>

			<div class="row counters position-relative">
				<div class="col-lg-3 col-6 text-center">
					<span data-purecounter-start="0" data-purecounter-end="<?php echo $Total; ?>" data-purecounter-duration="1" class="purecounter"></span>
					<p>Total Trees</p>
				</div>

				<div class="col-lg-3 col-6 text-center">
					<span data-purecounter-start="0" data-purecounter-end="6" data-purecounter-duration="1" class="purecounter"></span>
					<p>Total Species Group</p>
				</div>

				<div class="col-lg-3 col-6 text-center">
					<span data-purecounter-start="0" data-purecounter-end="318" data-purecounter-duration="1" class="purecounter"></span>
					<p>Total Species</p>
				</div>

				<div class="col-lg-3 col-6 text-center">
					<span data-purecounter-start="0" data-purecounter-end="45" data-purecounter-duration="1" class="purecounter"></span>
					<p>Cutting Regime</p>
				</div>
			</div>
		</div>
    </section>
	<main id="main">
		<!-- ======= Tree Species ======= -->
		<section id="portfolio" class="portfolio">
			<div class="container">
				<div class="section-title">
				<h2>Tree Species</h2>
				<p>A Mixed Dipterocarp Forest is a type of tropical rainforest that is characteristic of Southeast Asia. It's named after the dominant tree family found in these forests, which is Dipterocarpaceae. These forests are incredibly diverse in terms of flora and fauna and are considered one of the most biologically rich ecosystems on the planet.</p>
				</div>
				<div class="row portfolio-container">
					<div class="col-lg-4 col-md-6 portfolio-item filter-app">
						<img src="assets/img/pokok/mersawa.jpg" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Mersawa</h4>
							<p>Anisoptera spp. Mersawa belongs to the Dipterocarpaceae family and 
								is valued for its timber, which is used in construction,
								furniture making, and woodworking.</p>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 portfolio-item filter-web">
						<img src="assets/img/pokok/keruing.png" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Keruing</h4>
							<p>Dipterocarpus spp. Keruing is another hardwood used in 
								various applications, including boat-building, flooring, and outdoor furniture due 
								to its durability and resistance to decay.</p>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 portfolio-item filter-app">
						<img src="assets/img/pokok/Dipterocarp Commercial.jpg" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Dipterocarp Commercial</h4>
							<p>Refers to commercially valuable trees belonging to the Dipterocarpaceae family. These trees 
								are primarily harvested for their high-quality timber, which is used in construction, furniture 
								making, and other wood products.</p>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 portfolio-item filter-card">
						<img src="assets/img/pokok/Dipterocarp Non Commercial.jpeg" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Dipterocarp Non Commercial</h4>
							<p>This category are not extensively harvested for 
								commercial purposes, perhaps due to inferior wood quality or limited distribution.</p>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 portfolio-item filter-web">
						<img src="assets/img/pokok/Non Dipterocarp Commercial.jpg" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Non Dipterocarp Commercial</h4>
							<p>This category  include various hardwoods and softwoods used in 
								construction, furniture making, and other industries.</p>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 portfolio-item filter-app">
						<img src="assets/img/pokok/Non Dipterocarp Non Commercial.jpg" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Non Dipterocarp Non Commercial</h4>
							<p>These include a wide range of tree species with different ecological roles and uses, such 
								as fruit trees, ornamentals, or species with limited economic value.</p>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 portfolio-item filter-card">
						<img src="assets/img/pokok/Shorea robusta.jpg" class="img-fluid" alt="">
						<div class="portfolio-info">
							<h4>Shorea robusta</h4>
							<p>It is widely used in construction for building materials such as beams, rafters, and 
								door frames. Sal timber is also utilized in furniture making, flooring, railway sleepers, 
								and boat-building. </p>
						</div>
					</div>

					
				</div>
			</div>
		</section>
	</main>

	<footer id="footer">
		<div class="copyright">
			&copy; Copyright <strong><span>Forest Tree</span></strong>. All Rights Reserved
		</div>
	</footer>

	<div id="preloader"></div>
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

	<!-- Vendor JS Files -->
	<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="assets/vendor/php-email-form/validate.js"></script>

	<!-- Template Main JS File -->
	<script src="assets/js/main.js"></script>
</body>

</html>