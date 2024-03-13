<?php
include '../connection.php';
$value = $_POST['selectedValue'];
$user_id = $_SESSION['id'];

// Check if the user ID exists in the table
$qry_check = "SELECT COUNT(*) as count FROM `user_settings` WHERE `user_id` = ?";
$stmt_check = mysqli_prepare($con, $qry_check);
mysqli_stmt_bind_param($stmt_check, "i", $user_id);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_bind_result($stmt_check, $count);
mysqli_stmt_fetch($stmt_check);
mysqli_stmt_close($stmt_check);

if ($count > 0) {
    // User ID exists, update the existing record
    $qry = "UPDATE `user_settings` SET `rows_per_page` = ? WHERE `user_id` = ?";
    $stmt = mysqli_prepare($con, $qry);
    mysqli_stmt_bind_param($stmt, "ii", $value, $user_id);
    $data = mysqli_stmt_execute($stmt);
} else {
    // User ID does not exist, insert a new record
    $qry = "INSERT INTO `user_settings`(`user_id`, `rows_per_page`) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $qry);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $value);
    $data = mysqli_stmt_execute($stmt);
}


// Close the statement
mysqli_stmt_close($stmt);
