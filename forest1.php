<?php
require('mysqli_connect.php');

// Check database connection
if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the selected filter value
$selected_status = isset($_GET['status']) ? $_GET['status'] : 'All';

// Calculate the total number of records
$total_records_sql = "SELECT COUNT(*) as total_records FROM newforest";
if ($selected_status !== 'All') {
    $total_records_sql .= " WHERE Status='$selected_status'";
}
$total_records_result = mysqli_query($dbc, $total_records_sql);

if (!$total_records_result) {
    die("Query failed: " . mysqli_error($dbc));
}

$total_records_row = mysqli_fetch_assoc($total_records_result);
$total_records = $total_records_row['total_records'];

// Calculate the number of pages
$records_per_page = 20; // Adjust this value to display more or fewer records per page
$total_pages = ceil($total_records / $records_per_page);

// Get the current page number
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the LIMIT clause
$offset = ($current_page - 1) * $records_per_page;

// Fetch the records for the current page, grouped by hectare
$q = "SELECT BlockX, BlockY, COUNT(*) as trees_per_hectare FROM newforest";
if ($selected_status !== 'All') {
    $q .= " WHERE Status='$selected_status'";
}
$q .= " GROUP BY BlockX, BlockY ORDER BY BlockX ASC, BlockY ASC LIMIT $records_per_page OFFSET $offset";
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
    <link href="assets/img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
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
        .filter-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .filter-form select {
            padding: 5px;
            margin-right: 10px;
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
                <h1>Forest Tree - Forest All Data</h1>
                <a href="#about" class="glightbox play-btn mb-4"></a>
            </div>
        </div>
    </div>
</section>
<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2>Forest Data</h2>
                <p>Table showing data of all generated data.</p>
            </div>
            <div class="filter-form">
                <form method="GET" action="">
                    <select name="status" onchange="this.form.submit()">
                        <option value="All" <?php if ($selected_status == 'All') echo 'selected'; ?>>All</option>
                        <option value="Keep" <?php if ($selected_status == 'Keep') echo 'selected'; ?>>Keep</option>
                        <option value="Victim" <?php if ($selected_status == 'Victim') echo 'selected'; ?>>Victim</option>
                        <option value="Cut" <?php if ($selected_status == 'Cut') echo 'selected'; ?>>Cut</option>
                    </select>
                </form>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Block X</th>
                                <th>Block Y</th>
                                <th>Trees per Hectare</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                echo '<tr>
                                    <td align="left">' . $row['BlockX'] . '</td>
                                    <td align="left">' . $row['BlockY'] . '</td>
                                    <td align="left">' . $row['trees_per_hectare'] . '</td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination navigation -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php
                            // Determine the number of pages to show in the pagination
                            $pages_to_show = 5;
                            // Calculate the start and end page numbers based on the current page
                            $start_page = max(1, $current_page - floor($pages_to_show / 2));
                            $end_page = min($total_pages, $start_page + $pages_to_show - 1);
                            // Only show the page numbers within the start and end range
                            if ($start_page > 1) {
                                echo '<li class="page-item"><a href="' . $_SERVER['PHP_SELF'] . '?page=1&status=' . $selected_status . '" class="page-link">&laquo;</a></li>';
                                echo '<li class="page-item"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($current_page - 1) . '&status=' . $selected_status . '" class="page-link">&lt;</a></li>';
                            }
                            for ($i = $start_page; $i <= $end_page; $i++) {
                                echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '&status=' . $selected_status . '" class="page-link">' . $i . '</a></li>';
                            }
                            if ($end_page < $total_pages) {
                                echo '<li class="page-item"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($current_page + 1) . '&status=' . $selected_status . '" class="page-link">&gt;</a></li>';
                                echo '<li class="page-item"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . $total_pages . '&status=' . $selected_status . '" class="page-link">&raquo;</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
