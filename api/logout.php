
<?php
session_start();
$isFrontEndLogin = $_SESSION['LoginFromFrontEnd'];
if ($isFrontEndLogin) {
    session_destroy();
    header('Location: http://localhost/uber/login.php');
}
else {
session_destroy();
header('Location: http://localhost/arcsfrontend/login.php?logout=true'); }
?>
