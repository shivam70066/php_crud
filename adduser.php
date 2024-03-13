<?php
include 'connection.php';
include 'checkLogin.php';
if ($_SESSION['role_id'] > 2) {
    header("location: dashboard.php");
}
$nameErr = "";
$invalidemail = "";
$numbererror = "";
$ageerror = "";
$designationErr = "";
$gendererr = "";

if (isset($_POST['submit'])) {


    $sub = "";
    if (isset($_POST['chkbox']) && $_POST['chkbox'] === 'true1') {
        // Checkbox is checked
        $sub = "true";
    } else {
        // Checkbox is not checked
        $sub = "false";
    }

    $role_id = $_POST['user_role'];


    $iscorrect = "true";

    if ($_POST['country'] != "" && $_POST['state'] != "") {
        $location = $_POST['state'] . ", " . $_POST['country'];
    } else {
        $iscorrect = "false";
        $locerr = "Please enter a valid location.";
    }


    $name = trim($_POST['name']);
    if (empty($name)) {
        $nameErr = "Please Enter your Name";
        $iscorrect = "false";
    } else if (!(preg_match("/^([a-zA-Z' ]+)$/", $name))) {
        $nameErr = "Please Enter a valid Name.";
        $iscorrect = "false";
    }


    if (isset($_POST["gender"]) && !empty($_POST["gender"])) {
        $gender = $_POST["gender"];
    } else {
        $gendererr = "Please select a gender.";
        $iscorrect = "false";
    }



    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $invalidemail = "Please enter a valid E-mail.";
        $iscorrect = "false";
    }


    $number = (int) $_POST['number'];
    $num_length = strlen((string)$number);
    if (empty($number)) {
        $numbererror = "Please enter your number.";
        $iscorrect = "false";
    } else if (($num_length != 10)) {
        $numbererror = "Please enter valid 10 digits number.";
        $iscorrect = "false";
    }


    $designation = $_POST['designation'];
    if (empty($designation)) {
        $designationErr = "Please Enter your Designation.";
        $iscorrect = "false";
    }

    $age = (int) $_POST['age'];
    if (($age < 15 || $age > 100)) {
        $ageerror = "Please Enter your valid age Between 15 - 100.";
        $iscorrect = "false";
    }

    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
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
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($iscorrect == "true") {
        $qry = "INSERT INTO `users`(`name`, `designation`, `email`,`number`,`age`,`gender`,`location`,`subscription`,`createdAt`,`role_id`,`password`) VALUES ('$name','$designation','$email','$number','$age','$gender','$location','$sub',CURDATE(),$role_id,'$hashedPassword')";
        $success = mysqli_query($con, $qry);

        $currentDateTime = date("Y-m-d H:i:s");
        $msg = "$name has been registered on $currentDateTime. His email is $email, designation is $designation, and number is $number.";
        $qry = "INSERT INTO `msg`(`msg`,`action`) VALUES ('$msg','add')";
        mysqli_query($con, $qry);
        if ($success) {
            $_SESSION['status'] = "success";
            include 'emailSend.php';
        }
    }
}
?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User</title>

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
            border: 2px solid #ff651b;
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
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content">
                <h1>Add User</h1>
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
                        <div class="form-row">

                            <div class="form-label">
                                <label for="password">Password : </label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="search-box" placeholder="Enter your password" id="password" class="password" name="password" onblur="validatePassword()" />
                                <span class="error-ms" id="passmsg"><?php echo $passerr ?></span>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-label">
                                <label for="cpassword">Confirm Password : </label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="search-box" placeholder="Enter your password again" id="cpassword" value="<?php echo $cpassword ?>" class="cpassword" name="cpassword" onblur="validateConfirmPassword()" />
                                <span class="error-ms" id="cpassmsg"><?php echo $cpasserr ?></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label for="designation">Designation: </label>
                            </div>
                            <div class="input-field">
                                <input type="text" id="designation" name="designation" class="search-box" placeholder="Enter your designation" value="<?php echo $designation ?>" onblur="validateDesignation()" />
                                <span class="error-ms" id="designationmsg"><?php echo $designationErr ?></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label for="number">Mobile Number: </label>
                            </div>
                            <div class="input-field">
                                <input type="number" id="number" class="search-box number" placeholder="Enter your number" maxlength="10" oninput="this.value = this.value.slice(0, this.maxLength);" name="number" value="<?php echo $number ?>" onblur="validateMobileNumber()" />
                                <span class="error-ms" id="numbermsg"><?php echo $numbererror ?></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label for="age">Age: </label>
                            </div>
                            <div class="input-field">
                                <input type="number" id="age" class="search-box age" placeholder="Enter your age" name="age" value="<?php echo $age ?>" onblur="validateAge()" />
                                <span class="error-ms" id="agemsg"><?php echo $ageerror ?></span>
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-label">
                                <label for="gender">Gender: </label>
                            </div>
                            <div class="input-field">
                                <label>
                                    <input type="radio" name="gender" value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'male') echo 'checked="checked"'; ?> onblur="validateGender()">
                                    Male
                                </label>
                                <label>
                                    <input type="radio" name="gender" value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'female') echo 'checked="checked"'; ?> onblur="validateGender()">
                                    Female
                                </label>
                                <span class="error-ms" id="genderError" style="color: red;"><?php echo $gendererr ?></span>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex;align-items:center;">
                            <div class="form-label">
                                <label>Role: </label>
                            </div>
                            <div class="input-field">
                                <div class="select_option">
                                    <select name="user_role" id="role" class="form-select search-box" onblur="validateRole()">
                                        <option selected value="0">Choose Role</option>
                                        <?php if ($_SESSION['role_id'] == 1) { ?>
                                            <option value="1">Super Admin</option>
                                            <option value="2">Admin</option>
                                        <?php } ?>
                                        <option value="3">Team Lead</option>
                                        <option value="4">Manager</option>
                                        <option value="5">Employee</option>
                                    </select>
                                </div>
                                <span class="error-ms" id="rolemsg"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">
                                <label>Location: </label>
                            </div>
                            <div class="input-field">

                                <div class="select_option">
                                    <select class="form-select country search-box" name="country" id="countrySelect" style="margin-bottom:4px; " onchange="loadStates()">
                                        <option selected value="select">Select Country</option>
                                    </select> <br>
                                    <select class="form-select state search-box" name="state" style="margin-bottom:4px;" id="stateSelect">
                                        <option selected value="select">Select State</option>
                                    </select>
                                </div>
                                <span class="error-ms" id="locmsg"><?php echo $locerr; ?></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-label">
                                <p style="font-size: 1px;"> .</p>
                            </div>
                            <div class="input-field">
                                <label>
                                    <input type="checkbox" value="true1" name="chkbox" style="background: #ff651b;"> Subscribe to our newsletter.
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
                                <button type="submit" class="submit-btn" name="submit">Add User</button>
                            </div>
                        </div>



                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php include 'footer.php' ?>

    <!-- <script src="addUser.js"></script> -->
    <script src="api/contries.js"></script>
    <script>
        $(document).ready(function() {
            var timeoutId; // Initialize a variable to hold the timeout ID

            $('#email').on('input', function() {
                clearTimeout(timeoutId); // Clear the previous timeout

                // Start a new timeout
                timeoutId = setTimeout(function() {
                    var email = $(this).val();
                    var emailInput = document.getElementById("email");
                    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                    if (!emailRegex.test(email)) {
                        document.getElementById("emailmsg").innerHTML = "Please enter a valid email.";
                        return;
                    }

                    // Execute the AJAX request after debouncing
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
                }.bind(this), 500); // Set the debounce time to 500 milliseconds (adjust as needed)
            });
        });


        <?php
        if (isset($_SESSION['status'])) {

        ?>
            Swal.fire({
                title: "Registered",
                icon: "success",
                showCloseButton: true,
                confirmButtonText: `Okay`,
                confirmButtonColor: "#ff651b",
            }).then((result) => {
                <?php
                unset($_SESSION['status']);
                ?>
                window.location.href = "list-users.php";
            });
        <?php }

        ?>





        function validateForm() {
            var nameValid = validateName();
            var emailValid = validateEmail();
            var designationValid = validateDesignation();
            var mobileValid = validateMobileNumber();
            var ageValid = validateAge();

            var genderValid = validateGender();
            var locValid = validateLocation();
            var roleValid = validateRole();
            var passwordValid = validatePassword();
            var confirmPasswordValid = validateConfirmPassword();

            var flag = nameValid && emailValid && mobileValid && designationValid && ageValid && genderValid && locValid && roleValid && passwordValid && confirmPasswordValid;
            if (!flag) {
                document.getElementById("divmsg").style.display = "block";
            }
            return flag;
        }

        function validatePassword() {
            var passwordInput = document.getElementById("password");
            var password = passwordInput.value;

            // Regular expression to match password requirements
            var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z]).{8,}$/;

            if (!passwordRegex.test(password)) {
                document.getElementById("passmsg").innerHTML = "Password: 8+ chars, special char, number, uppercase.";
                return false;
            } else {
                document.getElementById("passmsg").innerHTML = "";
                return true;
            }
        }

        function validateConfirmPassword() {
            var passwordInput = document.getElementById("password");
            var confirmPasswordInput = document.getElementById("cpassword");
            var password = passwordInput.value;
            var confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                document.getElementById("cpassmsg").innerHTML = "Passwords do not match.";
                return false;
            } else {
                document.getElementById("cpassmsg").innerHTML = "";
                return true;
            }
        }

        function validateRole() {
            var roleSelect = document.getElementById("role");

            var roleValue = roleSelect.value;

            // Check if any of the selects have default value
            if (roleValue == "0") {
                document.getElementById("rolemsg").innerHTML =
                    "Please select a valid role.";
                return false;
            } else {
                document.getElementById("rolemsg").innerHTML =
                    "";
                return true;
            }
        }




        function validateLocation() {
            var countrySelect = document.getElementById("countrySelect");
            var stateSelect = document.getElementById("stateSelect");

            var countryValue = countrySelect.value;
            var stateValue = stateSelect.value;

            // Check if any of the selects have default value
            if (countryValue == "select" || stateValue == "select") {
                document.getElementById("locmsg").innerHTML =
                    "Please enter a valid location.";
                return false;
            } else {
                document.getElementById("locmsg").innerHTML =
                    "";
                return true;
            }
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