<?php
include 'connection.php';
include 'api/checklogin.php';
$start = 0;
$rows_per_page = 10;
$orderBy = '';
$qryName = "";
$pageNo = 1;

$query = "SELECT * FROM `contact`";
$qryResult = mysqli_query($con, $query);
$numsOfRow = mysqli_num_rows($qryResult);
$pages = ceil($numsOfRow / $rows_per_page);
// echo $pages;
if (isset($_GET['pno'])) {
    $page = $_GET['pno'] - 1;
    $_SESSION["pageNo"] = $page + 1;
    $start = $page * $rows_per_page;
}



if (isset($_GET['order']) && $_GET['order'] != '') {
    $orderBy = isset($_GET['order']) ? $_GET['order'] : 'name';
    $sortOrder = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'asc';
    $nextOrder = $sortOrder === 'asc' ? 'desc' : 'asc';

    $readQuery = "SELECT * FROM (SELECT * FROM `contact` LIMIT $start,$rows_per_page) AS result ORDER BY $orderBy $sortOrder";
} else $readQuery = "SELECT * FROM `contact` LIMIT $start,$rows_per_page";

$_SESSION['checkOrder'] = $_GET['order'];
if (isset($_GET['pno'])) $pnoo = $_GET['pno'];
else $pnoo = 1;


$result = mysqli_query($con, $readQuery);

$nums = mysqli_num_rows($result);
?>




<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>

    <!-- Bootstrap -->
    <link href="css/dashboard.css" rel="stylesheet">
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        nav {
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="content">
        <div class="wrapper">
            <?php include 'leftside.php'; ?>
            <div class="right_side_content">
                <h1>Contact Request</h1>
                <div class="list-content">
                    <div class="form-left">
                        <div class="form">
                        </div>


                    </div>
                    <div style="min-height: 430px;">

                        <table width="100%" cellspacing="0">
                            <tbody>
                                <tr>
                                    <?php if (isset($_GET['pno'])) $pageNo =  $_GET['pno'];

                                    echo "<th><a href=\"?pno=$pageNo&order=name&orderBy=$nextOrder\">Name</a></th>";
                                    echo "<th><a href=\"?pno=$pageNo&order=email&orderBy=$nextOrder\">E-mail</a></th>";
                                    echo "<th><a href=\"?pno=$pageNo&order=number&orderBy=$nextOrder\">Number</a></th>";
                                    echo "<th><a href=\"?pno=$pageNo&order=subject&orderBy=$nextOrder\">Subject</a></th>";
                                    echo "<th style='width : 10ch; text-overflow: ellipsis;'><a href=\"?pno=$pageNo&order=message&orderBy=$nextOrder\">Message</a></th>";
                                    echo "<th><a href=\"?pno=$pageNo&order=recieveAt&orderBy=$nextOrder\">Date</a></th>";

                                    ?>
                                    <th width="40px">Delete</th>

                                </tr>
                                <?php
                                if ($numsOfRow == 0) {
                                    echo "<td rowspan='9' colspan='7' style='background-color:FF651B;text-align:center; color:white; height:350px;font-size:24px'>No Results</td>";
                                    die();
                                } else
                                    while ($res = mysqli_fetch_array($result)) {
                                        $id = $res['id'];
                                        echo "<tr style='max-height:20px;'  >";

                                        echo 
                                                "<td style='cursor:pointer;' onclick=\"location.href='msgpage.php?id=$id';\">" . $res['name'] . "</td>
                                                <td style='cursor:pointer;' onclick=\"location.href='msgpage.php?id=$id';\">" . $res['email'] . "</td>
											<td style='cursor:pointer;' onclick=\"location.href='msgpage.php?id=$id';\">" . $res['number'] . "</td>
											<td style='cursor:pointer;' onclick=\"location.href='msgpage.php?id=$id';\">" . $res['subject'] . "</td>
											<td style='white-space: nowrap;
                                            overflow: hidden ;
                                            max-width: 80px;
                                            text-overflow: ellipsis; cursor: pointer;'' onclick=\"location.href='msgpage.php?id=$id';\">" . $res['message'] . "</td>
											<td style='cursor:pointer;' onclick=\"location.href='msgpage.php?id=$id';\">" . $res['recieveAt'] . "</td>
                                            
											<td style='text-align:center;'>
                                                <input type='hidden' class='delete_id_val' value=" . $id . ">
												<a href='javascript:void(0)' class='delete_btn_ajax delete'>
                                                <img src='images/cross.png'</a>
                                            </td>
                                                
                                                
                                            
										</tr>";
                                    }

                                ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="paginaton-div">
                        <ul>
                            <?php if ($_GET['pno'] > 1) { ?>
                            <a
                                href="?pno=<?php echo 1; ?>&order=<?php echo $_GET['order']; ?>&search=<?php echo $qryName; ?>">First</a>

                            <!-- prev page -->
                            <?php
                            }
                            if (isset($_GET['pno']) && $_GET['pno'] > 1) {
                            ?>
                            <a href="?pno=<?php echo $_GET['pno'] - 1; ?>&order=<?php echo $_GET['order']; ?>&search=<?php echo $qryName; ?>"
                                style="padding:5px;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                            <?php } ?>

                            <!-- prEVIOUS page nUMBER -->
                            <?php
                            if (!($page + 1 == 1)) {
                            ?>
                            <a
                                href="?pno=<?php echo $page; ?>&order=<?php echo $_GET['order']; ?>&search=<?php echo $qryName; ?>">
                                <?php echo $page; ?>
                            </a>
                            <?php

                            }
                            ?>


                            <!-- current page  -->
                            <button disabled style="background-color: #ff651b;color:white;padding: 2px 8px;">
                                <?php echo $page + 1; ?>
                            </button>

                            <!-- NEXT page nUMBER -->
                            <?php

                            if (!($page + 1 == $pages)) {
                            ?>
                            <a
                                href="?pno=<?php echo $page + 2; ?>&order=<?php echo $_GET['order']; ?>&search=<?php echo $qryName; ?>">
                                <?php echo $page + 2; ?>
                            </a>
                            <?php

                            }
                            ?>

                            <!-- next page  -->
                            <?php
                            if ((isset($_GET['pno']) && $_GET['pno'] < $pages) || !isset($_GET['pno'])) {
                                if ($pages != 1) {
                            ?>

                            <a href="?pno=<?php if (isset($_GET['pno'])) echo $_GET['pno'] + 1;
                                                    else echo 2; ?>&order=<?php echo $_GET['order']; ?>&search=<?php echo $qryName; ?>"
                                style="padding:5px;"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                            <?php
                                }
                            }
                            if ($pages > $_GET['pno']) {
                                if ($pages != 1) {
                                ?>
                            <a
                                href="?pno=<?php echo $pages; ?>&order=<?php echo $_GET['order']; ?>&search=<?php echo $qryName; ?>">Last</a>
                            <?php }
                                                                                                                                                } ?>
                        </ul>
                        <p style="margin-top: 8px;font-family:OpenSansSemibold;color:#333333;">Total records:
                            <?php echo $numsOfRow ?>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php include 'footer.php' ?>

    <script>
        $(document).ready(function () {
            $('.delete_btn_ajax').click(function (e) {
                e.preventDefault();
                var deleteid = $(this).closest("tr").find('.delete_id_val').val();
                Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this record",
                    icon: "warning",
                    showCancelButton: true,
                    iconColor: "#ff651b",
                    confirmButtonColor: "#ff651b",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    cancelButtonColor: "#214139",
                    allowOutsideClick: true,
                    backdrop: "rgba(0,0,0,0.8)"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "contactdelete.php",
                            data: {
                                "delete_btn_set": 1,
                                "delete_id": deleteid,
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: "Record Deleted",
                                    icon: "success",
                                    iconColor: "#ff651b",
                                    confirmButtonColor: "#ff651b",
                                    allowOutsideClick: true,
                                    backdrop: "rgba(0,0,0,0.8)"
                                })
                                    .then(() => {
                                        location.reload();
                                    });
                            }
                        });
                    }
                });
            })
        })
    </script>
</body>

</html>