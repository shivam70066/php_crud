<?php
include 'api/checklogin.php';
if ($_SESSION['role_id'] > 2)
    header("location: dashboard.php")
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
        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 200px;
        }

        tr>td:first-child {
            width: 40%;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content">
                <h1>Email Templates</h1>
                <div class="list-contet">
                    <div>
                        <table width="100%">
                            <tr>
                                <td>Registration template(Frontend): </td>
                                <td><a href="updateTemplateFrontend.php?name=reg_template_frontend">Link</a></td>
                            </tr>
                            <tr>
                                <td>Registration template(Admin panel): </td>
                                <td><a href="updateTemplateFrontend.php?name=reg_template_backend">Link</a></td>
                            </tr>
                            <tr>
                                <td>Change Password template: </td>
                                <td><a href="updateTemplateFrontend.php?name=pass_change">Link</a></td>
                            </tr>
                            <tr>
                                <td>Change Password Request template: </td>
                                <td><a href="updateTemplateFrontend.php?name=pass_change_link">Link</a></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="js/validatechangePass.js"></script>

</body>

</html>