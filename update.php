<?php
include 'connection.php';
include 'api/checklogin.php';
if($_SESSION['role_id'] > 2){
	header("location: dashboard.php");
}
$nameErr = "";
$invalidemail = "";
$numbererror = "";
$ageerror = "";
$designationErr = "";
// echp geoip_country_name_by_name("IN");
$id = $_GET['id'];
$qry = "select * from users where id = $id";
$data = mysqli_query($con, $qry);
$row = mysqli_fetch_array($data);
$name = $row['name'];
$role_id = $row['role_id'];
$email = $row['email'];
$designation = $row['designation'];
$number = $row['number'];
$age = $row['age'];
$gender = $row['gender'];
$subscription = $row['subscription'];
$prevURL = "http://localhost" . $_SESSION['pvsURI'];
if (isset($_POST['submit'])) {

    $iscorrect = "true";


    $sub = "";
    if (isset($_POST['chkbox']) && $_POST['chkbox'] === 'true1') {
        // Checkbox is checked
        $sub = "true";
    } else {
        // Checkbox is not checked
        $sub = "false";
    }

    // if ($_POST['country'] != "" && $_POST['state'] != "") {
    //     $location = $_POST['state'] . ", " . $_POST['country'];
    //     echo $location;
    // } else {
    //     $iscorrect = "false";
    //     $locerr = "Please enter a valid location.";
    // }



    if (isset($_POST["gender"]) && !empty($_POST["gender"])) {
        $gender = $_POST["gender"];
    } else {
        $gendererr = "Please select a gender.";
        $iscorrect = "false";
    }

    $name = $_POST['name'];
    $name = trim($name);
    if (empty($name)) {
        $nameErr = "Please enter a name.";
        $iscorrect = "false";
    } else if (!(preg_match("/^([a-zA-Z' ]+)$/", $name))) {
        $nameErr = "Enter Valid Name.";
        $iscorrect = "false";
    }


    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $invalidemail = "Enter Valid E-mail.";
        $iscorrect = "false";
    }


    $number = (int) $_POST['number'];
    $num_length = strlen((string)$number);
    if (empty($number)) {
        $numbererror = "Number is required";
        $iscorrect = "false";
    } else if (($num_length != 10)) {
        $numbererror = "Enter Valid Number of 10 Digits";
        $iscorrect = "false";
    }


    $designation = $_POST['designation'];
    if (empty($designation)) {
        $designationErr = "Please Enter Designation";
        $iscorrect = "false";
    }

    $age = (int) $_POST['age'];

    if (($age < 15 || $age > 100)) {
        $ageerror = "Enter Age Between 15 - 100";
        $iscorrect = "false";
    }
    $role_id= $_POST['user_role'];

    if ($iscorrect == "true") {
        $qry = "UPDATE `users` SET `name`='$name',`designation`='$designation',`email`='$email',`number`='$number',`age`=$age,`subscription`='$sub',`location`='$location',`gender`='$gender',`modifiedAt`=CURDATE(),`role_id`='$role_id' WHERE id =$id";
        $success = mysqli_query($con, $qry);


        $currentDateTime = date("Y-m-d H:i:s");
        $msg = "Details of $name has been updated on $currentDateTime.";
        $qry = "INSERT INTO `msg`(`msg`,`action`) VALUES ('$msg','update')";
        mysqli_query($con, $qry);
        if ($success) {
            $_SESSION['status'] = "success";
        }
        header("location: ".$prevURL);
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

        input[type="radio"] {
            /* Hide the default radio button */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            /* Define custom styles for the radio button */
            width: 10px;
            height: 10px;
            margin-bottom: 2px;
            border: 1px solid #ff651b;
            /* Border color */
            border-radius: 50%;
            /* Make it round */
            outline: none;
            /* Remove focus outline */
            cursor: pointer;
            margin-right: 5px;
            /* Optional: Add some spacing between the radio button and the label */
        }

        /* Define styles for checked radio button */
        input[type="radio"]:checked {
            background-color: #ff651b;
            /* Background color when checked */
        }

        input[type=checkbox] {
            accent-color: #ff651b;
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
                                <label for="name">Name : </label>
                            </div>
                            <div class="input-field">
                                <input type="text" class="search-box" placeholder="Enter Your name" id="name" value="<?php echo $name ?>" class="name" name="name" onkeyup="validateName()" />
                                <span class="error-ms" id="namemsg"><?php echo $nameErr ?></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">
                                <label for="email">Email: </label>
                            </div>
                            <div class="input-field">
                                <input id="email" type="email" class="search-box email" placeholder="Enter your email" name="email" value="<?php echo $email ?>" onkeyup="validateEmail()" />
                                <span class="error-ms" id="emailmsg"><?php echo $invalidemail ?></span>
                            </div>
                        </div>
                        <div class="form-row" style="display: flex;align-items:center;">
                            <div class="form-label">
                                <label>Role: </label>
                            </div>
                            <div class="input-field">
                                <div class="select_option">
                                    <select name="user_role" id="role" class="form-select search-box">
                                        <?php if ($_SESSION['role_id'] == 1) { ?>
                                            <option value="1" <?php if($role_id==1) echo "selected"; ?>>Super Admin</option>
                                            <option value="2" <?php if($role_id==2) echo "selected"; ?>>Admin</option>
                                        <?php } ?>
                                        <option value="3" <?php if($role_id==3) echo "selected"; ?>>Team Lead</option>
                                        <option value="4" <?php if($role_id==4) echo "selected"; ?>>Manager</option>
                                        <option value="5" <?php if($role_id==5) echo "selected"; ?>>Employee</option>
                                    </select>
                                </div>
                                <span class="error-ms" id="rolemsg"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label for="designation">Designation: </label>
                            </div>
                            <div class="input-field">
                                <input type="text" id="designation" name="designation" class="search-box" placeholder="Enter your designation" value="<?php echo $designation ?>" onkeyup="validateDesignation()" />
                                <span class="error-ms" id="designationmsg"><?php echo $designationErr ?></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label for="number">Mobile Number: </label>
                            </div>
                            <div class="input-field">
                                <input type="number" id="number" class="search-box number" placeholder="Enter your number" maxlength="10" oninput="this.value = this.value.slice(0, this.maxLength);" name="number" value="<?php echo $number ?>" onkeyup="validateMobileNumber()" />
                                <span class="error-ms" id="numbermsg"><?php echo $numbererror ?></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label for="age">Age: </label>
                            </div>
                            <div class="input-field">
                                <input type="number" id="age" class="search-box age" placeholder="Enter your age" name="age" value="<?php echo $age ?>" onkeyup="validateAge()" />
                                <span class="error-ms" id="agemsg"><?php echo $ageerror ?></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">
                                <label for="gender">Gender: </label>
                            </div>
                            <div class="input-field">
                                <label>
                                    <input type="radio" name="gender" value="Male" <?php if ($gender == 'Male') echo 'checked="checked"'; ?>>
                                    Male
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="Female" <?php if ($gender == 'Female') echo 'checked="checked"'; ?>>
                                    Female
                                </label>
                                <span class="error-ms" id="genderError" style="color: red;"><?php echo $gendererr ?></span>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-label">
                                <p style="font-size: 1px;"> .</p>
                            </div>
                            <div class="input-field">
                                <label>
                                    <input type="checkbox" value="true1" name="chkbox" <?php if ($subscription == 'true') echo 'checked="checked"'; ?>> Subscribe to our newsletter.
                                </label>
                                <!-- <span class="error-ms" id="locmsg"></span> -->
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">
                                <label><span></span> </label>
                            </div>
                            <div class="input-field">
                                <!-- <button type="submit" value="submit" name="submit" id='btn'>Submit</button>     -->
                                <button type="submit" class="submit-btn" name="submit">Update</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php include 'footer.php' ?>
    <!-- <script src="api/contries.js"></script> -->
    <!-- <script src="addUser.js"></script> -->
    <script>
        $(document).ready(function() {
            var debounceTimer;

            $('#email').on('input', function() {
                clearTimeout(debounceTimer); // Clear previous timer

                var email = $(this).val();
                var emailInput = document.getElementById("email");
                var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (!emailRegex.test(email)) {
                    document.getElementById("emailmsg").innerHTML = "Please enter a valid email.";
                    return;
                }

                // Set a timer to delay the AJAX request by 1500 ms (1.5 seconds)
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: 'checkEmailUsers.php',
                        type: 'POST',
                        data: {
                            email: email
                        },
                        success: function(response) {
                            $('#emailmsg').html(response);
                        }
                    });
                }, 1500); // 1500 ms delay
            });
        });





        function validateForm() {
            var nameValid = validateName();
            var emailValid = validateEmail();
            var designationValid = validateDesignation();
            // var confirmPasswordValid = validateConfirmPassword();
            var mobileValid = validateMobileNumber();
            var ageValid = validateAge();

            var genderValid = validateGender();

            var flag = nameValid && emailValid && mobileValid && designationValid && ageValid && genderValid && locValid;
            if (!flag) {
                document.getElementById("divmsg").style.display = "block";
            }
            return flag;
        }





        function validateGender() {
            var genderOptions = document.getElementsByName('gender');
            var genderError = document.getElementById('genderError');
            var genderSelected = false;

            for (var i = 0; i < genderOptions.length; i++) {
                if (genderOptions[i].checked) {
                    genderSelected = true;
                    break;
                }
            }

            if (!genderSelected) {
                genderError.textContent = "Please select a gender.";
                return false;
            } else {
                genderError.textContent = "";
                return true;
            }
        }

        function validateAge() {
            // Get the value from the age input field
            var ageValue = document.getElementById("age").value;

            // Convert the value to a number
            var age = parseInt(ageValue);

            // Check if age is less than 18 or greater than 100
            if (age < 18 || age > 100 || isNaN(age)) {
                // Invalid age
                document.getElementById("agemsg").innerHTML =
                    "Please enter ageeen 18 and 100.";
                return false;
            } else {
                // Valid age
                document.getElementById("agemsg").innerHTML = ""; // Clear error message if any
                return true;
            }
        }

        function validateEmail() {
            var emailInput = document.getElementById("email");
            var email = emailInput.value.trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === "") {
                document.getElementById("emailmsg").innerHTML =
                    "Please enter an email address.";
                return false;
            } else if (!emailRegex.test(email)) {
                // document.getElementById("emailmsg").innerHTML = "Please enter a valid email address.";
                return false;
            } else {
                // document.getElementById("emailmsg").innerHTML = "";
                return true;
            }
        }

        function validateName() {
            var nameInput = document.getElementById("name");
            var name = nameInput.value.trim();
            console.log(name)
            var nameRegex = /^[a-zA-Z ]+$/;

            if (name === "") {
                document.getElementById("namemsg").innerHTML = "Please enter a name.";
                return false;
            } else if (!nameRegex.test(name)) {
                document.getElementById("namemsg").innerHTML =
                    "Please enter a valid name(letters and spaces only).";
                return false;
            } else {
                document.getElementById("namemsg").innerHTML = "";
                return true;
            }
        }

        function validateDesignation() {
            var designationValue = document.getElementById("designation").value;

            // Check if the length of the designation is greater than two
            if (designationValue.length > 1) {
                // Designation is valid
                document.getElementById("designationmsg").innerHTML = "";
                return true;
            } else {
                // Designation is invalid
                document.getElementById("designationmsg").innerHTML =
                    "Please Enter a valid Designation.";
                return false;
            }
        }

        function validateConfirmPassword() {
            var passwordInput = document.getElementById("password");
            var confirmPasswordInput = document.getElementById("cpassword");
            var password = passwordInput.value;
            var confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                document.getElementById("cpasswordmsg").innerHTML =
                    "Passwords do not match.";
                return false;
            } else {
                document.getElementById("cpasswordmsg").innerHTML = "";
                return true;
            }
        }

        function validateMobileNumber() {
            var mobileInput = document.getElementById("number");
            var mobileNumber = mobileInput.value;

            if (mobileNumber.length !== 10 || isNaN(mobileNumber)) {
                document.getElementById("numbermsg").innerHTML =
                    "Please enter a valid mobile number.";
                return false;
            } else {
                document.getElementById("numbermsg").innerHTML = "";
                return true;
            }
        }
    </script>
</body>

</html>