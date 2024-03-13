<?php
include 'connection.php';
if (isset($_POST["submit"])) {
    include 'api/settings.php';
}
$qry = "select * from users where role_id = 1";
$data = mysqli_query($con, $qry);
$row = mysqli_fetch_array($data);
if($_SESSION['role_id'] != 1){
    header('Location: http://localhost/arcsfrontend/dashboard.php');
}
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Admin</title>

    <!-- Bootstrap -->
    <link href="css/dashboard.css" rel="stylesheet">
    <style>
        #submit-btn-div {
            width: 100%;
        }

        .submit-btn {
            display: block;
            margin-left: 20%;
            width: 20%;
            margin: auto;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content" style="height:300px">
                <div>
                    <h1 style="">Settings</h1>
                </div>
                <div style="min-height: 430px;">
                    <form method="post" onsubmit="return validate()">
                        <div>

                            <table width="100%">
                                <tr>
                                    <td>Super Admin Details : </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>Name:</td>
                                                <td><input class="form-select search-box" id="name" name="name" type="text" placeholder="Name" value="<?php echo $row['name']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td><input class="form-select search-box" id="email" type="text" name="email" placeholder="Email" value="<?php echo $row['email']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>Mobile Number:</td>
                                                <td><input class="form-select search-box" type="text" id="number" name="number" maxlength="10" oninput="this.value = this.value.slice(0, this.maxLength);" placeholder="Number" value="<?php echo $row['number']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td>Location:</td>
                                                <td><input class="form-select search-box" type="text" id="location" name="location" placeholder="Location" value="<?php echo $row['location']; ?>"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Number of users shown per page: </td>
                                    <td>
                                        <div class="select_option" style="width: 50%;">
                                            <?php
                                            $qry2 = "SELECT * FROM user_settings";

                                            $data2 = mysqli_query($con, $qry2);
                                            $row2 = mysqli_fetch_array($data2);
                                            $rows_per_page = $row2['rows_per_page'];
                                            $format_date = $row2['time_format'];
                                            ?>
                                            <select name="records_per_page" id="records_per_page" class="form-select search-box">
                                                <option value="5" <?php if ($rows_per_page == 5) echo "selected"; ?>>5</option>
                                                <option value="10" <?php if ($rows_per_page == 10) echo "selected"; ?>>10</option>
                                                <option value="15" <?php if ($rows_per_page == 15) echo "selected"; ?>>15</option>
                                                <option value="20" <?php if ($rows_per_page == 20) echo "selected"; ?>>20</option>
                                                <option value="25" <?php if ($rows_per_page == 25) echo "selected"; ?>>25</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Link expired (Forgot Password):</td>
                                    <td><input class="form-select search-box" style="width: 50%;" name="expire_time" id="expire_time" maxlength="5" oninput="this.value = this.value.slice(0, this.maxLength);" type="number" placeholder="Time (in Mins)" value="<?php echo $row2['expiry_time']; ?>"> <span style="position:relative;top:10px"> &nbsp; ( in Minutes )</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Select Date Format:</td>
                                    <td><select name="date_format" id="date_format" class="form-select search-box" style="width: 50%;">
                                            <option value="Y-m-d" <?php if ($format_date == "Y-m-d") echo "selected"; ?>>(2024-03-12)</option>
                                            <option value="d-m-Y" <?php if ($format_date == "d-m-Y") echo "selected"; ?>>(12-03-2024)</option>
                                            <option value="m/d/Y" <?php if ($format_date == "m/d/Y") echo "selected"; ?>>(03/12/2024)</option>
                                            <option value="Y/m/d" <?php if ($format_date == "Y/m/d") echo "selected"; ?>>(2024/03/12)</option>
                                            <option value="F j, Y" <?php if ($format_date == "F j, Y") echo "selected"; ?>>(March 12, 2024)</option>
                                            <option value="j F Y" <?php if ($format_date == "j F Y") echo "selected"; ?>>(12 March 2024)</option>
                                        </select></td>
                                </tr>
                            </table>
                            <div id="submit-btn-div">
                                <button id="submit" value="submit" class="submit-btn" name="submit" style="display: block; width:20%; margin:15px auto;">Update</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="js/settingsValidation.js"></script>
    <script>
        <?php
        if (isset($_SESSION['status'])) {

        ?>
            Swal.fire({
                title: "Updated",
                icon: "success",
                iconColor: "#ff651b",
                showCloseButton: true,
                confirmButtonText: `Okay`,
                confirmButtonColor: "#ff651b",
            }).then((result) => {
                <?php
                unset($_SESSION['status']);
                ?>
            });
        <?php } ?>
    </script>
</body>

</html>