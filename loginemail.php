<?php 
    include 'connection.php';

    if(isset($_POST['email'])){
        $email = $_POST['email'];
        if (!(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))) {
            // $invalidemail = "Please enter a valid E-mail.";
            echo "<span style='color: red;'>Please enter a valid email.</span>";
        }
        else{
        $qry ="Select * FROM `users` WHERE email = '$email'";
        $qryResult = mysqli_query($con, $qry);
        $num_rows = mysqli_num_rows($qryResult);
        if ($num_rows == 0) {
            // Email does not exist in database
            echo "<span style='color: red;'>Email is not registered</span>";
        }}
    }
?>