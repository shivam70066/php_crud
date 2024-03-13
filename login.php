<?php
include 'connection.php';
if ($_SESSION['isLogin'] == true) {
  header('Location: http://localhost/arcsfrontend/dashboard.php');
  die();
}
$invalidemail = "";
$isEmailTrue = true;
$isPasstrue = true;



if (isset($_POST['submit'])) {

  $iscorrect = "true";


  // // email
  $email = $_POST['email'];
  if (!(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))) {
    $invalidemail = "Please enter a valid email.";
    $iscorrect = "false";
  }


  $password = $_POST['password'];
  if (strlen($password) < 8) {
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
      $iscorrect = "false";
      $passWrong = '';
    }
  }



  $password = $_POST['password'];
  if ($invalidemail == "") {
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 0) {
      $invalidemail = "Email is not registered.";
      $isEmailTrue = false;
      $isPasstrue = false;
    } else {
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
          session_start();
          $_SESSION['uname'] = $row['name'];
          $_SESSION['id']  = $row['id'];
          $_SESSION['email'] = $row['email'];
          $_SESSION['role_id'] = $row['role_id'];
          $_SESSION['isLogin'] = "true";
          header('Location: http://localhost/arcsfrontend/dashboard.php');
          die();
        } else {
          $isPasstrue = false;
          $passWrong = "Wrong Password";
        }
      }
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet"> 
    <style>
      #passwordmsg, #emailmsg{
        color: red;
        font-size: 12px;
      }
    </style>
</head>

<body>
  <div class="login_section">
    <div class="wrapper relative">
      <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div>
      <div class="heading-top">
        <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
      </div>
      <div class="box">
        <div class="outer_div">

          <h2 style="margin-top:5px;">Admin <span>Login</span></h2>
          <div class="error-message-div error-msg" style="display: none;"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username or password </div>
          <form class="margin_bottom" style="margin-top:10px;" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
              <label for="username">Email</label>
              <input type="email" class="form-control" <?php if ($isEmailTrue == false) { ?> style="border: 1px solid red;" <?php } ?> placeholder="Email" id="email" name="email" onkeyup="validateEmail()" value="<?php echo $_POST['email'] ?>">
              <span id="emailmsg"><?PHP echo $invalidemail; ?></span>
            </div>
            <div class="form-group">

              <input type="password" class="form-control" placeholder="Password" <?php if ($isPasstrue == false) { ?> style="border: 1px solid red;" <?php } ?> id="password" name="password" value="<?php echo $_POST['password'] ?>" onkeyup="validatePassword()">
              <span id="passwordmsg"><?PHP echo $passWrong; ?></span>
            </div>
            <button class="btn_login" name="submit">Login</button>
          </form>
        </div>
      </div>
    </div>

    <script src="js/validateLoginForm.js"></script>
    <script>function validatePassword() {
    var passwordInput = document.getElementById("password");
    var password = passwordInput.value;



    if (password.length < 1) {
        document.getElementById("passwordmsg").innerHTML = "Please enter a password.";
        return false;
    } else {
        document.getElementById("passwordmsg").innerHTML = "";
        return true;
    }
}</script>


</body>

</html>