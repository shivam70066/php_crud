<?php
if ($_SESSION['isLogin'] == true) {
	header('Location: http://localhost/arcsfrontend/list-users.php?pno=1');
} else {
	header('Location: http://localhost/arcsfrontend/login.php');
}
die();
