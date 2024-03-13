<div class="left_sidebr">
	<ul class="submenu">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li><a href="list-users.php" class="listusers"><?php if ($_SESSION['role_id'] <= 2) echo "Manage Users";
														else echo "Users"; ?></a></li>
		<li><a href="changepassword.php">Change Password</a></li>
		<li><a href="request.php" class="managerequest">Manage Contact Request</a></li>
		<?php if ($_SESSION['role_id'] < 3)
			echo '<li><a href="emailtemplates.php" class="template">Manage Email Templates</a></li>';
		?>

		<?php if ($_SESSION['role_id'] == 1)
			echo '<li><a href="comingsoon.php">Settings</a></li>';
		?>


		</li>
	</ul>
</div>
<script>
	var navLinks = document.querySelectorAll('ul li a');
	const lastActiveLink = window.location.href.split('?')[0]
	console.log(lastActiveLink);


	navLinks.forEach(function(link) {
		if (link.href === lastActiveLink) {
			link.classList.add('active');
		}

		link.addEventListener('click', function() {
			navLinks.forEach(function(link) {
				link.classList.remove('active');
			});

			this.classList.add('active');
		});
	});
	if (lastActiveLink == "http://localhost/arcsfrontend/adduser.php" || lastActiveLink == "http://localhost/arcsfrontend/update.php") {


		var elements = document.getElementsByClassName('listusers');
		for (var i = 0; i < elements.length; i++) {
			elements[i].classList.add('active');
		}
	}
	if (lastActiveLink == "http://localhost/arcsfrontend/msgpage.php") {


		var elements = document.getElementsByClassName('managerequest');
		for (var i = 0; i < elements.length; i++) {
			elements[i].classList.add('active');
		}
	}
	if (lastActiveLink == "http://localhost/arcsfrontend/updateTemplateFrontend.php") {


		var elements = document.getElementsByClassName('template');
		for (var i = 0; i < elements.length; i++) {
			elements[i].classList.add('active');
		}
	}
</script>