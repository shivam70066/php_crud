<?php
include 'connection.php';
include 'api/checklogin.php';
if (isset($_POST['delete_btn_set'])) {

    $delete_id = $_POST['delete_id'];


    // Update msg 
    $qry = "select * from users where id = $delete_id";
    $data = mysqli_query($con, $qry);
    $row = mysqli_fetch_array($data);
    $name = $row['name'];
    $email = $row['email'];
    $designation = $row['designation'];
    $number = $row['number'];
    $currentDateTime = date("Y-m-d H:i:s");
    $msg = "$name details has been deleted on $currentDateTime. His email is $email, designation is $designation, and number is $number.";
    $qry = "INSERT INTO `msg`(`msg`,`action`) VALUES ('$msg','delete')";
    mysqli_query($con, $qry);

    // qry for delete 
    $qry = "DELETE FROM `users` WHERE id = '$delete_id'";
    $data = mysqli_query($con, $qry);
}
