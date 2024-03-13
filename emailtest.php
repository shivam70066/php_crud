<!DOCTYPE html>
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
    <div class="container">
        <img src="https://arcsinfotech.com/wp-content/uploads/2024/01/black-logo.png" height="100px" alt="">
        <h1>Password Change Request.</h1>
        <p>Dear {{$name}},</p>
        <p>You requested for password was changed on
            {{date}} at {{time}}
        </p>
        <p>You can change your password from here:  <a href="{{link}}" class="login-link">here</a>.
        </p>
        <p>Best regards,</p>
        <p>Arcs Infotech Team</p>

    </div>
    <div class="footer">
        <p>&copy; 2024 Arcs Infotech. All rights reserved.</p>
    </div>
</body>

</html>