<?php
include 'connection.php';
include 'api/checklogin.php';

$id = $_GET['id'];
$query = "SELECT * FROM `contact` where id = $id";
$qryResult = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($qryResult);
$numsOfRow = mysqli_num_rows($qryResult);
$errmsg = "";
$content = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(empty($_POST['content'])){
        $errmsg = "Reply Can't be empty <br>";
    }
    else{
        $content = $_POST['content'];
    }
}
?>




<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message</title>


    <link href="css/dashboard.css" rel="stylesheet">
    <style>
        nav {
            display: flex;
            justify-content: space-around;
        }

        td {
            min-width: 70px;
        }

        #backBtn , .submitBtn {
            border: none;
            text-align: center;
            background: #ff651b;
            padding: 7px 18px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 20px;
            color: white;
            width: 10%;
            margin-top: 20px ;
        }
        #backBtn{
            padding: 5px 20px;
        }
        a{
            font-size: 16px;
        }
        #buttons{
            display: block;
            margin: auto;
        }

        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 200px;
        }
        #msg{
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content">
                <h1>Message Details </h1>
                <div class="list-content">
                    <div style="min-height: 430px;">
                        <div>
                            <table width="100%">
                                <tr>
                                    <td>Name : </td>
                                    <td><?php echo $row['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Email : </td>
                                    <td><?php echo $row['email'] ?></td>
                                </tr>
                                <tr>
                                    <td>Number : </td>
                                    <td><?php echo $row['number'] ?></td>
                                </tr>
                                <tr>
                                    <td>Subject : </td>
                                    <td><?php echo $row['subject'] ?></td>
                                </tr>
                                <tr>
                                    <td>Message : </td>
                                    <td><?php echo $row['message'] ?></td>
                                </tr>
                                <tr>
                                    <td style="min-width: 90px;">Recieved date : </td>
                                    <td><?php echo $row['recieveAt'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <p style="margin: 20px 5px;">Reply</p>
                        <form method="post">
                            <textarea name="content" id="content" cols="30" rows="10" style="height:1px;" >
                        </textarea>
                            <span style="color: red;"><?php echo $errmsg; ?></span>
                            <div id="buttons">
                                <button type="submit" class="submitBtn">Reply</button>
                                <a id="backBtn" href="request.php">Back</a>
                            </div>
                        </form>

                        <?php if(!empty($content)){ ?>
                        <div id="msg">
                            <p>Message: <?php echo $content ?></p>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'footer.php' ?>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    </script>

</body>

</html>