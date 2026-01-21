<?php
require_once "../processes/database.php";
$state = $_GET['state'];
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    header ('location: ../TS/forum/dashboard.php');
    exit;
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styling/pallate.css">
    <link rel="stylesheet" href="../styling/Mindex.css">
<?php
if ($state === 'login') {
?>
    <title>Login || CrossGate</title>
</head>
<body class="w100p minh100 bg-white">
    <form class="autoMg pad-b-s pad-n-v minw200 w30p flex fld acjc gap10 bgc-white border-1" action="../processes/connect_login.php" method="post">
        <h1 class="sideMg txtc txt-b c-black">LOGIN</h1>
        <div class="form-input-row sideMg w88p flex fld">
            <label for="username" class="c-black">Username</label>
            <input class="inptxt border-b" type="text" id="username" name="username" placeholder="Use your account username" autocomplete="off" tabindex="1" required>
        </div>
        <div class="form-input-row sideMg w88p flex fld">
            <label for="password" class="c-black">Password</label>
            <input class="inptxt border-b" type="password" id="password" name="password" placeholder="Give the correct password" autocomplete="off" tabindex="2" required>
        </div>
        <div class="form-input-row sideMg w88p flex fld">
            <button type="submit" class="pad-s bgc-gold txt-n c-black" name="Login" tabindex="3">Sign in</button>
        </div>
        <div class="form-input-row sideMg w88p flex fld">
            <p class="pad-s txtc c-black">Don't have an Account? <a href="connect_it.php?state=register" class="c-blue" tabindex="4">Register here</a></p>
        </div>
    </form>
<?php
} else if ($state === 'register') {
?>
  <title>Register new account || CrossGate</title>
</head>
<body class="w100p minh100 bg-white">
    <form class="autoMg pad-b-s pad-n-v minw200 w30p flex fld acjc gap10 bgc-white border-1" action="../processes/connect_regist.php" method="post">
        <h1 class="sideMg txtc txt-b c-black">REGISTER</h1>
        <div class="form-input-row sideMg w88p flex fld">
          <label for="email" class="c-black">Email</label>
          <input class="inptxt" type="text" id="email" name="email" placeholder="Your mail for validation" autocomplete="off" tabindex="1" required>
        </div>
        <div class="form-input-row sideMg w88p flex fld">
          <label for="username" class="c-black">Username</label>
          <input class="inptxt" type="text" id="username" name="username" placeholder="Write the desired username" autocomplete="off" tabindex="3" required>
        </div>
          <div class="form-input-row sideMg w88p flex fld">
          <label for="password" class="c-black">Password</label>
        <input class="inptxt" type="password" id="password" name="password" placeholder="Choose a good password" autocomplete="off" tabindex="4" required>
        </div>
          <div class="form-input-row sideMg w88p flex fld">
          <button type="submit" class="pad-s bgc-gold txt-n c-black" name="Register" tabindex="4">Register</button>
        </div>
        <div class="form-input-row sideMg w88p flex fld">
          <p class="pad-s txtc c-black">Already have Account? <a href="connect_it.php?state=login" class="c-blue" tabindex="7">then Log-In</a></p>
        </div>
    </form>
<?php
} else {
    echo "<p>wtf?</p>";
}
?>
  <div id="alertcard">
      <p id="alertcontent"></p>
      <div id="borderanimate"></div>
  </div>
  <script src="../scriptstuff/alert.js"></script>
  <?php
  if (!empty($_SESSION['corsmsg'])) {
      $corsmsg = $_SESSION['corsmsg'];
      echo "<script> ";
      echo "alerter('" . $corsmsg . "')";
      echo "</script>";
      $_SESSION['corsmsg'] = "";
  }
  ?>
</body>
</html>