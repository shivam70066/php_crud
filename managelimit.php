<?php
include 'connection.php';
$id = 0;
$passerr = "";
$cpasserr = "";
$id = $_SESSION['id'];
$sql = "SELECT * FROM emp WHERE `id`='$id';";
$result = mysqli_query($con, $sql);
$password = "";


if (isset($_POST['submit'])) {
    $iscorrect = "true";
    $password = $_POST['opassword'];
    while ($row = mysqli_fetch_assoc($result)) {
        if (!password_verify($password, $row['pwd'])) {
            $iscorrect = false;
            $opasserr = "Please enter correct password";
        }
    }
    echo $password;
    if (strlen($password) < 8) {
        $passerr = "Password should be at atleast 8 characters";
        $iscorrect = "false";
    } else {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars) {
            $iscorrect = "false";
            $passerr = 'Password sshould include at least one upper case letter, one number, and one special character.';
        }
    }

    $npassword = $_POST['npassword'];
    $cpassword = $_POST['cpassword'];
    if ($cpassword != $password || $_POST['cpassword'] == NULL) {
        $iscorrect = "false";
        // $cpasserr = "Both passwords are not matched ";
    }
    $hash = password_hash($npassword, PASSWORD_DEFAULT);


    if ($iscorrect == "true") {
        $timee = time();
        date_default_timezone_set('Asia/Kolkata');
        $ttime = date('Y-m-d H:i:s', $timee);
        $sql = "UPDATE `emp` SET `pwd`='$hash',`modifiedTime`='$ttime',`token`=NULL WHERE id=$id";
        echo "<br>" . $sql;
        $result = mysqli_query($con, $sql);
        if ($result) {
            $_SESSION['status'] = "success";
        }
    }
}
?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>

    <!-- Bootstrap -->
    <link href="css/dashboard.css" rel="stylesheet">

</head>

<body>

    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content">
                <h1>Update User</h1>
                <div class="list-contet">
                    <div class="error-message-div error-msg" id="divmsg"><img src="images/unsucess-msg.png"><strong>Please!</strong> Correct all the errors. </div>

                    <form class="form-edit" method="POST">
                        <div class="form-row">

                            <div class="form-label">
                                <label for="selectValues">Select:</label>
                            </div>
                            <div class="input-field">
                                <select id="selectValues" name="selectValues">
                                    <option value="5" <?php echo ($_SESSION['selectedValue'] == '5') ? 'selected' : ''; ?>>5</option>
                                    <option value="10" <?php echo ($_SESSION['selectedValue'] == '10') ? 'selected' : ''; ?>>10</option>
                                    <option value="20" <?php echo ($_SESSION['selectedValue'] == '20') ? 'selected' : ''; ?>>20</option>
                                    <option value="50" <?php echo ($_SESSION['selectedValue'] == '50') ? 'selected' : ''; ?>>50</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-label">
                                <label><span></span> </label>
                            </div>
                            <div class="input-field">
                                <!-- <button type="submit" value="submit" name="submit" id='btn'>Submit</button>     -->
                                <button type="submit" class="submit-btn" name="submit">Change</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script>
        document.getElementById("selectValues").addEventListener("change", function() {
            var selectedValue = this.value;
            console.log("Selected value:", selectedValue);
            // Make an AJAX call to update the session value
            // Example: You can use fetch or jQuery AJAX to call a PHP script to update the session
        });
    </script>
</body>

</html>