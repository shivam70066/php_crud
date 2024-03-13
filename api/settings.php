<?php
$records_per_page = $_POST['records_per_page'];
$expire_time = $_POST['expire_time'];
$date_format = $_POST['date_format'];

$qry = "UPDATE `user_settings` 
            SET `rows_per_page` = $records_per_page, `expiry_time` = $expire_time, `time_format` = '$date_format'";

$data = mysqli_query($con, $qry);

$name= $_POST['name'];
$number = $_POST['number'];
$email = $_POST['email'];
$location = $_POST['location'];

$qry = "UPDATE `users` SET `name`='$name',`email`='$email',`number`='$number',`location`='$location',`modifiedAt`=CURDATE() WHERE role_id =1";
$data = mysqli_query($con, $qry);
if ($data) {
    $_SESSION['status'] = "success";
}

