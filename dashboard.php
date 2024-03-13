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
$currentMonth = date('m')-1;
$currentYear = date('Y');

$query = "SELECT MONTH(createdAt) AS month, DAY(createdAt) AS day, COUNT(*) AS user_count
          FROM users
          WHERE MONTH(createdAt) = $currentMonth AND YEAR(createdAt) = $currentYear
          GROUP BY MONTH(createdAt), DAY(createdAt)
          ORDER BY MONTH(createdAt), DAY(createdAt)";


$qryResult = mysqli_query($con, $query);
$result = mysqli_num_rows($qryResult);


while ($row = mysqli_fetch_assoc($qryResult)) {
	$data[] = array((string)((string)$row['day']), (int)$row['user_count']);
}




$query = "SELECT MONTH(modifiedAt) AS month, DAY(modifiedAt) AS day, COUNT(*) AS user_count
          FROM users
          WHERE MONTH(modifiedAt) = $currentMonth AND YEAR(modifiedAt) = $currentYear
          GROUP BY MONTH(modifiedAt), DAY(modifiedAt)
          ORDER BY MONTH(modifiedAt), DAY(modifiedAt)";


$qryResult = mysqli_query($con, $query);
$result = mysqli_num_rows($qryResult);


while ($row = mysqli_fetch_assoc($qryResult)) {
	$data1[] = array((string)((string)$row['day']), (int)$row['user_count']);
}


$query = "SELECT 
		designation,
		COUNT(*) AS designation_count,
		(COUNT(*) * 100 / (SELECT COUNT(*) FROM users)) AS percentage
		FROM 
		users
		GROUP BY 
		designation;";


$qryResult = mysqli_query($con, $query);

// $data2 = array();
while ($row = mysqli_fetch_assoc($qryResult)) {
	$data2[] = array($row['designation'], $row['designation_count']);
}


$query = "SELECT 
		designation,
		SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS male_count,
		SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS female_count
		FROM 
		users
		GROUP BY 
		designation;
		";


$qryResult = mysqli_query($con, $query);

// $data3 = array();
while ($row = mysqli_fetch_assoc($qryResult)) {
	$data3[] = array($row['designation'], $row['male_count'], $row['female_count']);
}






// JSON encode the data for Highcharts

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
	<style>
		li.selected>h1 {
			font-size: 14px;
			padding: 5px;
		}

		li.selected>p {
			font-family: "OpenSansSemibold";
			padding: 5px;
			font-size: 50px;
		}
	</style>
</head>

<body>
	<?php include 'header.php'; ?>

	<div class="content">
		<div class="wrapper">

			<?php include'leftside.php'; ?>

			<div class="right_side_content" style="height:600px; overflow:auto;">
				<h1>Dashboard</h1>
				<div style="height: 400px;">

					<div class="tab" style="display: flex; flex-direction:column; width:732px;overflow:hiden;">

						<ul>
							<li class="selected">
								<h1>Total No. of Users </h1>
								<p> <?php echo $totalUsers ?></p>
							</li>
							<li class="selected">
								<h1>No. of Users added today </h1>
								<p> <?php echo $createdToday ?></p>
							</li>
							<li class="selected">
								<h1>No. of Users updated today </h1>
								<p> <?php echo $updateToday ?></p>
							</li>
							<li class="selected">
								<h1>No. of Users subscribed </h1>
								<p> <?php echo $subUsers; ?></p>
							</li>


						</ul>
						<div id="curve_chart" style="margin-top: 40px;"></div>
						<div id="container" style=" height: 500px;"></div>
						<div id="container1" style=" height: 500px;margin-top:50px;"></div>
						<div id="container3" style=" height: 500px;margin-top:50px;"></div>


					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include 'footer.php' ?>
	<script>
		var chart = Highcharts.chart('container', {
			chart: {
				type: 'line'
			},
			title: {
				text: 'User Details'
			},
			xAxis: {
				title: {
					text: 'Dates'
				},
			},
			yAxis: {
				title: {
					text: 'No. of Users'
				}
			},
			series: [{
					name: 'No. of Users Added',
					data: [<?php foreach ($data as $row) : ?>[<?php echo $row[0]; ?>, <?php echo $row[1]; ?>],<?php endforeach; ?>]
				}, {
					name: 'No. of Users Modified',
					data: [<?php foreach ($data1 as $row) : ?>[<?PHP echo $row[0] ?>, <?php echo $row[1]; ?>],
						<?php endforeach; ?>
					]
				}

			],
			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					},
					pointStart: 1
				}
			},
			tooltip: {
				formatter: function() {
					return '<b>' + this.series.name + '</b><br/>' +
						'Date: ' + this.x + '-02-2024<br/>' +
						'Users: ' + this.y;
				}
			}

		});

		Highcharts.chart('container1', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Distribution of Designations'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<span style="font-size: 1.2em"><b>{point.name}</b></span><br>' +
							'<span style="opacity: 0.3">{point.percentage:.1f} %</span>',
						connectorColor: 'rgba(128,128,128,0.5)'
					}
				}
			},
			series: [{
				name: 'Share',
				data: [
					<?php foreach ($data2 as $row) : ?> {
							name: '<?PHP echo $row[0] ?>',
							y: <?php echo $row[1]; ?>
						},
					<?php endforeach; ?>
				]
			}]
		});

		Highcharts.chart('container3', {
			chart: {
				type: 'bar'
			},
			title: {
				text: 'Diversity Ratio'
			},
			xAxis: {
				categories: [<?php foreach ($data3 as $row) : ?>'<?PHP echo $row[0]; ?>',
	<?php endforeach; ?>]
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Total Counts: <?php echo $totalUsers ?>'
				}
			},
			legend: {
				reversed: true
			},
			plotOptions: {
				series: {
					stacking: 'normal',
					dataLabels: {
						enabled: true
					}
				}
			},
			series: [{
				name: 'Males',
				data: [<?php foreach ($data3 as $row) : ?><?PHP echo $row[1]; ?>,
	<?php endforeach; ?>]
			}, {
				name: 'Females',
				data: [<?php foreach ($data3 as $row) : ?><?PHP echo $row[2]; ?>,
	<?php endforeach; ?>]
			}]
		});
	</script>
</body>

</html>