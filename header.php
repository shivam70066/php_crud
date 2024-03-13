<?php
include 'api/checklogin.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<div class="header">
		<div class="wrapper">
			<div class="logo"><a href="#"><img src="images/logo.png"></a></div>


			<div class="right_side">
				<!-- <p style="margin-bottom: 10px;">Time: <?php echo time();?></p> -->
				<ul>
					<li>Welcome <strong> <?php echo $_SESSION['uname']; ?> </strong></li>
					<li><a href="api/logout.php">Log Out</a></li>
				</ul>
			</div>
			<div class="nav_top">
				<ul>
					<li><a href="dashboard.php ">Dashboard</a></li>
					<li><a href=" list-users.php" class="listusers">Users</a></li>
					<li><a href="changepassword.php">Change Password</a></li>
					<li><a href="request.php" class="managerequest">Contact Request</a></li>
					<li><a href="comingsoon.php">Settings</a></li>
				</ul>

			</div>
		</div>
	</div>

</body>

</html>