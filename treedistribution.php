<?php
require('mysqli_connect.php');

// Fetch all records from the newforest database
$q = "SELECT CoordinateX, CoordinateY, DBH, Status, Species AS SpeciesGroup FROM newforest";
$r = mysqli_query($dbc, $q);

if (!$r) {
    die("Query failed: " . mysqli_error($dbc));
}

$tree_data = [];
while ($row = mysqli_fetch_assoc($r)) {
    $tree_data[] = $row;
}

// For debugging purposes
// Uncomment the line below to see the fetched data
// var_dump($tree_data); exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Tree Distribution</title>
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
    
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        #plot {
            width: 80%;
            height: 600px;
            margin: auto;
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
                <h1>Tree Distribution</h1>
                <a href="#about" class="glightbox play-btn mb-4"></a>
            </div>
        </div>
    </div>
</section>

<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Tree Distribution</h2>
                <p>Visual representation of tree distribution within the forest.</p>
            </div>

            <div id="plot"></div>

            <script>
                // Get tree data from PHP
                const treeData = <?php echo json_encode($tree_data); ?>;

                console.log(treeData);  // Debugging: Log the tree data to the console

                // Extract coordinates, status, diameter, and labels
                const xCut = treeData.filter(tree => tree.Status === 'Cut').map(tree => tree.CoordinateX);
                const yCut = treeData.filter(tree => tree.Status === 'Cut').map(tree => tree.CoordinateY);
                const diameterCut = treeData.filter(tree => tree.Status === 'Cut').map(tree => tree.DBH);
                const speciesCut = treeData.filter(tree => tree.Status === 'Cut').map(tree => tree.SpeciesGroup);

                const xKeep = treeData.filter(tree => tree.Status === 'Keep').map(tree => tree.CoordinateX);
                const yKeep = treeData.filter(tree => tree.Status === 'Keep').map(tree => tree.CoordinateY);
                const diameterKeep = treeData.filter(tree => tree.Status === 'Keep').map(tree => tree.DBH);
                const speciesKeep = treeData.filter(tree => tree.Status === 'Keep').map(tree => tree.SpeciesGroup);

                const xVictim = treeData.filter(tree => tree.Status === 'Victim').map(tree => tree.CoordinateX);
                const yVictim = treeData.filter(tree => tree.Status === 'Victim').map(tree => tree.CoordinateY);
                const diameterVictim = treeData.filter(tree => tree.Status === 'Victim').map(tree => tree.DBH);
                const speciesVictim = treeData.filter(tree => tree.Status === 'Victim').map(tree => tree.SpeciesGroup);

                // Create traces for Plotly
const traceCut = {
    x: xCut,
    y: yCut,
    mode: 'markers',
    type: 'scatter',
    text: speciesCut.map((species, idx) => `Species: ${species}<br>Diameter: ${diameterCut[idx]} cm`),
    marker: { size: diameterCut.map(d => Math.sqrt(d)), color: 'red' },
    name: 'Cut'
};

const traceKeep = {
    x: xKeep,
    y: yKeep,
    mode: 'markers',
    type: 'scatter',
    text: speciesKeep.map((species, idx) => `Species: ${species}<br>Diameter: ${diameterKeep[idx]} cm`),
    marker: { size: diameterKeep.map(d => Math.sqrt(d)), color: 'green' }, // Change color to green
    name: 'Keep'
};

const traceVictim = {
    x: xVictim,
    y: yVictim,
    mode: 'markers',
    type: 'scatter',
    text: speciesVictim.map((species, idx) => `Species: ${species}<br>Diameter: ${diameterVictim[idx]} cm`),
    marker: { size: diameterVictim.map(d => Math.sqrt(d)), color: 'purple' },
    name: 'Victim'
};

                const layout = {
                    title: 'Tree Distribution',
                    xaxis: { title: 'Coordinate X', range: [0, 100] },
                    yaxis: { title: 'Coordinate Y', range: [0, 100] }
                };

                // Render the plot
                Plotly.newPlot('plot', [traceCut, traceKeep, traceVictim], layout);
            </script>
        </div>
    </section>
</main>
</body>
</html>
