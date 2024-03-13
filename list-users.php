<?php
include 'connection.php';
include 'api/checklogin.php';
include 'api/checkPreviousUri.php';

$qry2 = "SELECT * FROM user_settings";

$data2 = mysqli_query($con, $qry2);
$row2 = mysqli_fetch_array($data2);
$rows_per_page = $row2['rows_per_page'];
if (empty($rows_per_page)) {
	$rows_per_page = 15;
}


$start = 0;
$orderBy = '';
$qryName = "";
$pageNo = 1;
if (isset($_GET['search']) || $_GET['search'] == "") {

	$qryName = trim($_GET['search']);
	$qryName = str_replace("'", '"', $qryName);
	$qryName = str_replace('"', "", $qryName);
	$qryName = str_replace('<', "", $qryName);
	$qryname = htmlspecialchars($qryName, ENT_QUOTES, 'UTF-8');
}
if ($_SESSION['role_id'] == 1) {
	// If user's role_id is 1, include all conditions
	$whereCondition = "WHERE (name LIKE '%$qryName%' 
	OR designation LIKE '%$qryName%' 
	OR email LIKE '%$qryName%' 
	OR number LIKE '%$qryName%' 
	OR id LIKE '%$qryName%' 
	OR age LIKE '%$qryName%' 
	OR roles.role_name LIKE '%$qryName%')
	AND users.role_id > 1";
} else {
	// If user's role_id is greater than 1, include condition for role_id greater than 1
	$whereCondition = "WHERE (name LIKE '%$qryName%' 
					OR designation LIKE '%$qryName%' 
					OR email LIKE '%$qryName%' 
					OR number LIKE '%$qryName%' 
					OR id LIKE '%$qryName%' 
					OR age LIKE '%$qryName%' 
					OR roles.role_name LIKE '%$qryName%')
					AND users.role_id > 2";
}
$query = "SELECT users.*, roles.* FROM `users` INNER JOIN roles ON users.role_id = roles.role_id " . $whereCondition . ";";

$qryResult = mysqli_query($con, $query);
$numsOfRow = mysqli_num_rows($qryResult);
$pages = ceil($numsOfRow / $rows_per_page);

// echo $pages;
if (isset($_GET['pno'])) {
	$page = $_GET['pno'] - 1;
	$_SESSION["pageNo"] = $page + 1;
	$start = $page * $rows_per_page;
}





$orderBy = isset($_GET['order']) ? $_GET['order'] : 'id';
if ($_SESSION['prevOrder'] == $orderBy) {
	$sortOrder = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'asc';
} else {
	$sortOrder = 'asc';
	$_SESSION['prevOrder'] = $_GET['order'];
}

// $sortOrder = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'asc';
$nextOrder = $sortOrder === 'asc' ? 'desc' : 'asc';



$readQuery = "SELECT * 
FROM (
	SELECT users.*, roles.role_name 
	FROM `users` 
	INNER JOIN roles ON users.role_id = roles.role_id
	" . $whereCondition . "
	ORDER BY $orderBy $sortOrder
	LIMIT $start, $rows_per_page
) AS result;";
// $_SESSION['checkOrder'] = $_GET['order'];
if (isset($_GET['pno'])) $pnoo = $_GET['pno'];
else $pnoo = 1;






$result = mysqli_query($con, $readQuery);

$nums = mysqli_num_rows($result);



$qryfordateformat = "SELECT * FROM user_settings";
$getdata = mysqli_query($con, $qryfordateformat);
$getData = mysqli_fetch_array($getdata);
$format_date = $getData['time_format'];
?>




<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>

	<!-- Bootstrap -->
	<link href="css/dashboard.css" rel="stylesheet">
	<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
		nav {
			display: flex;
			justify-content: space-around;
		}
	</style>
</head>

<body>

	<?php include 'header.php'; ?>
	<div class="wrapper">
		<div class="content">

			<?php include 'leftside.php'; ?>
			<div class="right_side_content">
				<h1>List Users</h1>
				<div class="list-contet">
					<div class="form-left">
						<div class="form">
							<form role="form">
								<form action="list-users.php" method="get">
									<input type="text" name="search" placeholder="Search.." id="searchInput" class="search-box search-upper" value="<?php echo $qryName ?>">
									<input type="hidden" name="pno" value='1'>
									<button type="submit" id="searchBtn" class="submit-btn">Search</button>
								</form>
							</form>
						</div>
						<?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2) { ?>
							<a href="adduser.php" class="submit-btn add-user">Add User</a>
						<?php } ?>
					</div>
					<?php $hieght = 35 * $rows_per_page; ?>
					<!-- height 430px static best for 10 rows -->
					<div style="min-height:400px;">

						<table width="100%" cellspacing="0">
							<tbody>
								<tr>
									<?php if (isset($_GET['pno'])) $pageNo =  $_GET['pno'];

									echo "<th><a href=\"?pno=$pageNo&order=name&search=$qryName&orderBy=$nextOrder\">Name ";
									if ($orderBy == 'name') {
										if ($sortOrder == 'desc') echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
										else echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
									}
									echo '';
									echo "</a></th>";


									echo "<th><a href=\"?pno=$pageNo&order=role_id&search=$qryName&orderBy=$nextOrder\">Role ";
									if ($orderBy == 'role_id') {
										if ($sortOrder == 'desc') echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
										else echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
									}
									echo '';
									echo "</a></th>";


									echo "<th><a href=\"?pno=$pageNo&order=email&search=$qryName&orderBy=$nextOrder\">E-mail ";
									if ($orderBy == 'email') {
										if ($sortOrder == 'desc') echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
										else echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
									}
									echo '';
									echo "</a></th>";


									echo "<th><a href=\"?pno=$pageNo&order=number&search=$qryName&orderBy=$nextOrder\">Number ";
									if ($orderBy == 'number') {
										if ($sortOrder == 'desc') echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
										else echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
									}
									echo '';
									echo "</a></th>";


									echo "<th><a href=\"?pno=$pageNo&order=age&search=$qryName&orderBy=$nextOrder\">Age ";
									if ($orderBy == 'age') {
										if ($sortOrder == 'desc') echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
										else echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
									}
									echo '';
									echo "</a></th>";

									echo "<th><a href=\"?pno=$pageNo&order=createdAt&search=$qryName&orderBy=$nextOrder\">Created Date";
									if ($orderBy == 'createdAt') {
										if ($sortOrder == 'desc') echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
										else echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
									}
									echo '';
									echo "</a></th>";
									if ($_SESSION['role_id'] <= 2) {
									?>



										<th width="20px">Action</th>
									<?php } ?>
								</tr>
								<?php
								if ($numsOfRow == 0) {
									echo "<td rowspan='9' colspan='7' style='background-color:#FF651B; text-align:center; color:white; height:350px; font-size:24px'>No Results</td>";
									die();
								} else {
									while ($res = mysqli_fetch_array($result)) {
										$id = $res['id'];
										$date = date($format_date, strtotime($res['createdAt']));
										echo "<tr style='max-height:20px'>
										<td>" . $res['name'] . "</td>
										<td>" . $res['role_name'] . "</td>
										<td>" . $res['email'] . "</td>
										<td>" . $res['number'] . "</td>
										<td>" . $res['age'] . "</td>
										<td>" . $date . "</td>";
										if ($_SESSION['role_id'] <= 2) {
											echo "<td>
											<a href='update.php?id=" . $id . "' id='update' style='margin-right:10px'><img src='images/edit-icon.png' onclick='myfunc()'></a>
											<input type='hidden' class='delete_id_val' value=" . $id . ">
											<a href='javascript:void(0)' class='delete_btn_ajax delete'><img src='images/cross.png'></a>
										</td>";
										}
										echo "</tr>";
									}
								}
								?>


							</tbody>
						</table>
					</div>
					<div class="paginaton-div">
						<ul>
							<?php if ($_GET['pno'] > 1) { ?>
								<a href="?pno=<?php echo 1; ?>&order=<?php echo $orderBy; ?>&search=<?php echo $qryName; ?>&orderBy=<?php echo $sortOrder; ?>">First</a>

								<!-- prev page -->
							<?php
							}
							if (isset($_GET['pno']) && $_GET['pno'] > 1) {
							?>
								<a href="?pno=<?php echo $_GET['pno'] - 1; ?>&order=<?php echo $orderBy; ?>&search=<?php echo $qryName; ?>&orderBy=<?php echo $sortOrder; ?>" style="padding:5px;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
							<?php } ?>

							<!-- prEVIOUS page nUMBER -->
							<?php
							if (!($page + 1 == 1)) {
							?>
								<a href="?pno=<?php echo $page; ?>&order=<?php echo $orderBy; ?>&search=<?php echo $qryName; ?>&orderBy=<?php echo $sortOrder; ?>"><?php echo $page; ?> </a>
							<?php

							}
							?>


							<!-- current page  -->
							<button disabled style="background-color: #ff651b;color:white;padding: 2px 8px;"> <?php echo $page + 1; ?> </button>

							<!-- NEXT page nUMBER -->
							<?php

							if (!($page + 1 == $pages)) {
							?>
								<a href="?pno=<?php echo $page + 2; ?>&order=<?php echo $orderBy; ?>&search=<?php echo $qryName; ?>&orderBy=<?php echo $sortOrder; ?>"> <?php echo $page + 2; ?> </a>
							<?php

							}
							?>

							<!-- next page  -->
							<?php
							if ((isset($_GET['pno']) && $_GET['pno'] < $pages) || !isset($_GET['pno'])) {
								if ($pages != 1) {
							?>

									<a href="?pno=<?php if (isset($_GET['pno'])) echo $_GET['pno'] + 1;
													else echo 2; ?>&order=<?php echo $orderBy; ?>&search=<?php echo $qryName; ?>&orderBy=<?php echo $sortOrder; ?>" style="padding:5px;"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
								<?php
								}
							}
							if ($pages > $_GET['pno']) {
								if ($pages != 1) {
								?>
									<a href="?pno=<?php echo $pages; ?>&order=<?php echo $orderBy; ?>&search=<?php echo $qryName; ?>&orderBy=<?php echo $sortOrder; ?>">Last</a> <?php }
																																											} ?>
						</ul>
						<p style="margin-top: 8px;font-family:OpenSansSemibold;color:#333333;">Total records: <?php echo $numsOfRow ?></p>
					</div>

				</div>
			</div>

		</div>
	</div>
	</div>

	<?php include 'footer.php' ?>

	<script>
		$(document).ready(function() {
			$('.delete_btn_ajax').click(function(e) {
				e.preventDefault();
				var deleteid = $(this).closest("tr").find('.delete_id_val').val();
				Swal.fire({
					title: "Are you sure?",
					text: "Once deleted, you will not be able to recover this record.",
					icon: "warning",
					showCancelButton: true,
					iconColor: "#ff651b",
					confirmButtonColor: "#ff651b",
					confirmButtonText: "Yes, delete it!",
					cancelButtonText: "No, cancel!",
					cancelButtonColor: "#214139",
					allowOutsideClick: true,
					backdrop: "rgba(0,0,0,0.8)"
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							type: "POST",
							url: "deleteid.php",
							data: {
								"delete_btn_set": 1,
								"delete_id": deleteid,
							},
							success: function(response) {
								Swal.fire({
										title: "Record Deleted",
										icon: "success",
										iconColor: "#ff651b",
										confirmButtonColor: "#ff651b",
										allowOutsideClick: true,
										backdrop: "rgba(0,0,0,0.8)"
									})
									.then(() => {
										location.reload();
									});
							}
						});
					}
				});
			})
		})

		<?php
		if (isset($_SESSION['status'])) {

		?>
			Swal.fire({
				title: "Updated",
				icon: "success",
				iconColor: "#ff651b",
				showCloseButton: true,
				confirmButtonText: `Okay`,
				confirmButtonColor: "#ff651b",
			}).then((result) => {
				<?php
				unset($_SESSION['status']);
				?>
				window.location.href = "<?php unset($_SESSION['pvsURI']);
										echo $prevURL; ?>";
			});
		<?php }

		?>

		$(document).ready(function() {

			$('.submit-btn').click(function() {
				var searchValue = $('#searchInput').val();

				// Do something with the search value
				console.log("Search value:", searchValue);
				window.location.href = "http://localhost/arcsfrontend/list-users.php?search=shivam";
			});


		});

		function myfunc() {
			<?php $_SESSION['pvsURI'] = $_SERVER['REQUEST_URI']; ?>
		}
	</script>
</body>

</html>