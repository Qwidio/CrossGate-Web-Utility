<?php
session_start();
unset($_SESSION["profileTags"]);
unset($_SESSION["username"]);

header('Location: ../index.php');
exit;
?>
