<?php
include 'connection.php';
$pageName = $_GET['name'];
$qry = "select * from templates where template_slug = '$pageName'";
$data = mysqli_query($con, $qry);
$row = mysqli_fetch_array($data);
$errmsg = "";




if (isset($_POST['submit'])) {

    $iscorrect = true;


    if (empty($_POST['body']) || empty($_POST['subject'])) {
        $errmsg = "You're not allowed to save empty subject or empty body.";
        $iscorrect = false;
    } else {
        $subject = $_POST['subject'];
        $body = $_POST['body'];
        if (strlen($subject) < 11) {
            $errmsg = "Subject cannot be less then 10 characters.";
            $iscorrect = false;
        }
        if (strlen($body) < 11) {
            $errmsg = "Content cannot be less then 100 characters.";
            $iscorrect = false;
        }
    }

    if ($iscorrect) {
        $subject = str_replace('"', "qutttt", $subject); // Replace " with ''
        $subject = str_replace("'", "''", $subject);
        $subject = str_replace('qutttt', "''", $subject);

        $body = str_replace('"', "qutttt", $body); // Replace " with ''
        $body = str_replace("'", "''", $body);
        $body = str_replace('qutttt', "''", $body);
        $qry = "UPDATE templates SET template_subject='$subject', template_body='$body', template_modifiedAt=CURDATE() WHERE template_slug = '$pageName'";

        $data = mysqli_query($con, $qry);
        $_SESSION['status'] = 'true';
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
    }
}
$data = mysqli_query($con, $qry);
$row = mysqli_fetch_array($data);
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


    <style>
        #backBtn,
        .submitBtn {
            border: none;
            text-align: center;
            background: #ff651b;
            padding: 7px 18px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 20px;
            color: white;
            width: 10%;
            margin-top: 20px;
        }

        #backBtn {
            padding: 5px 20px;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content">
                <h1><?php echo $row['template_name']; ?></h1>
                <div class="list-content">
                    <form method="post">

                        <div>
                            <table width="100%">
                                <tr>
                                    <td>Subject: </td>
                                    <td><input type="text" name="subject" class="search-box" placeholder="Enter subject" value="<?php echo $row['template_subject']; ?>"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Content: <br>
                                        <textarea name="body" id="body" cols="30" rows="10" ;"> <?php echo $row['template_body']; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                </div>

                <span style="color: red;"><?php echo $errmsg; ?></span>
                <div id="buttons">
                    <button type="submit" name='submit' value="submit" class="submitBtn">Save</button>
                    <a id="backBtn" href="emailtemplates.php">Back</a>
                </div>
                </form>
                <!-- <button id="previewButton" name="preview" value="preview">Preview</button> -->

            </div>

        </div>

    </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php
        if (isset($_SESSION['status'])) {

        ?>
            Swal.fire({
                title: "Saved",
                icon: "success",
                showCloseButton: true,
                confirmButtonText: `Okay`,
                confirmButtonColor: "#ff651b",
            }).then((result) => {
                <?php
                unset($_SESSION['status']);
                ?>
                // window.location.href = "list-users.php";
            });
        <?php }

        ?>
        ClassicEditor
            .create(document.querySelector('#body'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#footer'))
            .catch(error => {
                console.error(error);
            });


        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("previewButton");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {

            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>