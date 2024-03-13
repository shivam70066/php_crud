<?php
include 'connection.php';
include 'api/checklogin.php';

$query = "SELECT * FROM `users`";
$qryResult = mysqli_query($con, $query);
$totalUsers = mysqli_num_rows($qryResult);
// echo $totalUsers;


$query = "SELECT * FROM users
        WHERE DATE(createdAt) = CURDATE()";
$qryResult = mysqli_query($con, $query);
$createdToday = mysqli_num_rows($qryResult);
// echo $createdToday;

$query = "SELECT * FROM users
        WHERE DATE(modifiedAt) = CURDATE()";
$qryResult = mysqli_query($con, $query);
$updateToday = mysqli_num_rows($qryResult);
// echo $updateToday;

$query = "SELECT * FROM users
        WHERE subscription = 'true'";
$qryResult = mysqli_query($con, $query);
$subUsers = mysqli_num_rows($qryResult);
// echo $subUsers;
$currentMonth = date('m');
$currentYear = date('Y');

$query = "SELECT DATE(createdAt) AS day, COUNT(*) AS user_count
          FROM users
          WHERE MONTH(createdAt) = $currentMonth AND YEAR(createdAt) = $currentYear
          GROUP BY  DATE(createdAt)
          ORDER BY  DATE(createdAt)";

// $query = "SELECT CONCAT(MONTH(createdAt), '-', DAY(createdAt)) AS day,
//                  COUNT(*) AS user_count
//           FROM users
//           WHERE MONTH(createdAt) = $currentMonth AND YEAR(createdAt) = $currentYear
//           GROUP BY CONCAT(MONTH(createdAt), '-', DAY(createdAt))
//           ORDER BY MONTH(createdAt), DAY(createdAt)";


$qryResult = mysqli_query($con, $query);
$result = mysqli_num_rows($qryResult);
$days = [];
$userCounts = [];


while ($row = mysqli_fetch_assoc($qryResult)) {
	$data[] = array((string)((string)$row['day']), (int)$row['user_count']);
}
$json_data = json_encode($data);



// echo $json_data;
// Fetch data and populate arrays
// while ($row = $result->fetch_assoc()) {
// 	$days[] = $row['day'];
// 	$userCounts[] = $row['user_count'];
// }

// // Close the database connection
// $conn->close();
// 
?>



<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>

	<!-- Bootstrap -->
	<link href="css/dashboard.css" rel="stylesheet" type="text/css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
		li.selected>a>h1 {
			font-size: 14px;
			padding: 5px;
		}

		li.selected>a>p {
			font-family: "OpenSansSemibold";
			padding: 10px;
			font-size: 50px;
		}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		google.charts.load('current', {
			'packages': ['corechart']
		});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Day', 'User Count'],
				<?php foreach ($data as $row) : ?>['<?php echo $row[0]; ?>', <?php echo $row[1]; ?>],
				<?php endforeach; ?>
			]);

			var options = {
				title: 'Users Joined',
				curveType: 'function',
				legend: {
					position: 'top'
				}, // Position of the legend
				colors: ['#214139'], // Color of the line
				hAxis: {
					title: 'Date'
				}, // Label for the horizontal axis
				vAxis: {
					title: 'No. of Users'
				}, // Label for the vertical axis
				// titleTextStyle: { // Style for the title
				// 	color: '#FF5733', // Title color
				// 	fontSize: 18 // Title font size
				// },
				legendTextStyle: { // Style for the legend text
					color: '#666', // Legend text color
					fontSize: 14 // Legend text font size
				}
			};

			var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

			chart.draw(data, options);
		}
	</script>
</head>

<body>
	<div class="header">
		<div class="wrapper">
			<div class="logo"><a href="#"><img src="images/logo.png"></a></div>


			<div class="right_side">
				<ul>
					<li>Welcome Admin</li>
					<li><a href="">Log Out</a></li>
				</ul>
			</div>
			<div class="nav_top">
				<ul>
					<li class="active"><a href=" home.php ">Dashboard</a></li>
					<li><a href=" settings.php ">Users</a></li>
					<li><a href=" agentloclist.php ">Setting</a></li>
					<li><a href=" geoloclist.php ">Configuration</a></li>
				</ul>

			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="clear"></div>
	<div class="content">
		<div class="wrapper">
			<div class="left_sidebr">
				<ul>
					<li><a href="" class="dashboard">Dashboard</a></li>
					<li><a href="" class="user">Users</a>
						<ul class="submenu">
							<li><a href="">Manage Users</a></li>

						</ul>

					</li>
					<li><a href="" class="Setting">Setting</a>
						<ul class="submenu">
							<li><a href="">Change Password</a></li>
							<li><a href="">Mange Contact Request</a></li>
							<li><a href="#">Manage Login Page</a></li>

						</ul>

					</li>
					<li><a href="" class="social">Configuration</a>
						<ul class="submenu">
							<li><a href="">Payment Settings</a></li>
							<li><a href="">Manage Email Content</a></li>
							<li><a href="#">Manage Limits</a></li>
						</ul>

					</li>
				</ul>
			</div>
			<div class="right_side_content">
				<h1>Dashboard</h1>
				<div class="tab" style="display: flex; flex-direction:column;">

					<ul>
						<li class="selected"><a href="list-users.php">
								<h1>Total No. of Users </h1>
								<p> <?php echo $totalUsers ?></p>
							</a></li>
						<li class="selected"><a href="list-users.php">
								<h1>No. of Users added today </h1>
								<p> <?php echo $createdToday ?></p>
							</a></li>
						<li class="selected"><a href="list-users.php">
								<h1>No. of Users updated today </h1>
								<p> <?php echo $updateToday ?></p>
							</a></li>
						<li class="selected"><a href="list-users.php">
								<h1>No. of Users subscribed </h1>
								<p> <?php echo $subUsers; ?></p>
							</a></li>


					</ul>
					<!-- <div id="curve_chart" style="margin-top: 40px;"></div> -->
					<div id="container" style="margin-top:40px; height: 500px;"></div>
				</div>
			</div>
		</div>
	</div>

	<?php include 'footer.php' ?>
	<script>
		const data = '<?php echo $json_data ?>'
		const json = JSON.parse(data);
		const config = {
			chart: {
				type: 'spline'
			},
			title: {
				text: 'User Registration'
			},
			xAxis: {
				categories:  json.map(value => value[0])
				// accessibility: {
				// 	rangeDescription: 'Range: 1 to 2'
				// }
			},
			yAxis: {
				title: {
					text: 'No. of Users'
				}
			},
			series: [{
					name: 'Dates',
					data: json.map(value => value[1])
				},

			],

		}
		Highcharts.chart('container', config);
	</script>
</body>

</html>