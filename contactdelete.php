<?php
include 'connection.php';
if (isset($_POST['delete_btn_set'])) {

    $delete_id = $_POST['delete_id'];
    echo $delete_id;
    $qry = "DELETE FROM `contact` WHERE id = $delete_id";
    $data = mysqli_query($con, $qry);
}
