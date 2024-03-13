<?php
include('smtp/PHPMailerAutoload.php');
include 'connection.php';
$qry = "select * from templates where template_slug = 'pass_change'";
$data = mysqli_query($con, $qry);
$row = mysqli_fetch_array($data);
$body = $row['template_body'];
$body = str_replace("{{name}}", $_SESSION['uname'], $body);
$body = str_replace("{{date}}", date("Y-m-d"), $body);
$body = str_replace("{{time}}", date("H:i"), $body);

$html = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Website!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
        }

        .footer {

            background-color: rgb(0, 183, 255);
            max-width: 600px;
            display: block;
            margin: 20px auto;
            align-items: center;
            padding: 8px;
            margin-top: 20px;
            text-align: center;
        }

        .footer p {
            color: white;
        }

        .login-link {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">' . $body . '<div class="footer">
	<p>&copy; 2024 Arcs Infotech. All rights reserved.</p>
</div>
</body>

</html>';
echo smtp_mailer($_SESSION['email'], $row['template_subject'], $html);
function smtp_mailer($to, $subject, $msg)
{
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587;
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	//$mail->SMTPDebug = 2; 
	$mail->Username = "donotreply.xorosoft@gmail.com";
	$mail->Password = "fjpbxtobarjyqsrc";
	$mail->SetFrom("donotreply.xorosoft@gmail.com");
	$mail->Subject = $subject;
	$mail->Body = $msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions = array('ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => false
	));
	if (!$mail->Send()) {
		echo $mail->ErrorInfo;
	} else {
		return '';
	}
}
