<?php
include 'connection.php';
include 'api/checklogin.php';
$id = 0;
$passerr = "";
$cpasserr = "";
$id = $_SESSION['id'];

$sql = "SELECT * FROM users WHERE `id`='$id';";
$result = mysqli_query($con, $sql);
$password = "";


if (isset($_POST['submit'])) {
    $iscorrect = "true";
    $password = $_POST['opassword'];
    while ($row = mysqli_fetch_assoc($result)) {
        if (!password_verify($password, $row['password'])) {
            $iscorrect = false;
            $opasserr = "Please enter correct password";
        }
        // if (!password_verify($password, $row['pwd'])) {
        //     $iscorrect = false;
        //     $opasserr = "Please enter correct password";
        // }
    }
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
            $passerr = 'Password should include at least one upper case letter, one number, and one special character.';
        }
    }
    $npassword = $_POST['npassword'];
    $cpassword = $_POST['cpassword'];
    if ($cpassword != $npassword || $_POST['cpassword'] == NULL) {
        $iscorrect = "false";
        // $cpasserr = "Both passwords are not matched ";
    }
    $hash = password_hash($npassword, PASSWORD_DEFAULT);

    if ($iscorrect == "true") {
        $sql = "UPDATE `users` SET `password`='$hash',`modifiedAt`=CURDATE() WHERE id=$id";
        
        $result = mysqli_query($con, $sql);
        if ($result) {
            $_SESSION['status'] = "success";
            include 'emailChangePassword.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .swal2-close:hover {
            color: #ff651b;
        }
    </style>
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

                    <form class="form-edit" method="POST" onsubmit="return validateForm()">
                        <div class="form-row">

                            <div class="form-label">
                                <label for="opassword">Old Password : </label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="search-box" placeholder="Old password" id="opassword" value="<?php echo $password ?>" class="opassword" name="opassword" />
                                <span class="error-ms" id="opassmsg"><?php echo $opasserr ?></span>
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-label">
                                <label for="npassword">New Password : </label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="search-box" placeholder="New password" id="npassword" value="<?php echo $npassword ?>" class="npassword" name="npassword" />
                                <span class="error-ms" id="npassmsg"><?php echo $npasserr ?></span>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-label">
                                <label for="cpassword">Confirm Password : </label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="search-box" placeholder="Confirm password" onblur="validateForm()" id="cpassword" value="<?php echo $cpassword ?>" class="cpassword" name="cpassword" />
                                <span class="error-ms" id="cpassmsg"><?php echo $cpasserr ?></span>
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
    <script src="js/validatechangePass.js"></script>
    <script>
        $(document).ready(function() {
            $('#opassword').on('blur', function() {
                // var email = $(this).val();
                var opass = $(this).val();
                console.log(opass)
                var passRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z]).{8,}$/;
                if (!passRegex.test(opass)) {
                    document.getElementById("opassmsg").innerHTML = "Please enter a valid password";
                } else document.getElementById("opassmsg").innerHTML = "";
            });
        });


        <?php
        if (isset($_SESSION['status'])) {

        ?>
            Swal.fire({
                title: "Password changed.",
                icon: "success",
                iconColor: "#ff651b",
                showCloseButton: true,
                confirmButtonText: `Okay`,
                confirmButtonColor: "#ff651b",
            }).then((result) => {
                <?php
                unset($_SESSION['status']);
                ?>
                window.location.href = "http://localhost/arcsfrontend/list-users.php";
            });
        <?php }

        ?>
    </script>
</body>

</html>