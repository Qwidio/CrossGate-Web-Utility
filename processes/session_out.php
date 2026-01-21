<?php
require_once '../processes/database.php';
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
    if (isset($_POST['token']) && isset($_POST['submit'])) {
        $token = $_POST['token'];
        $stmt_bios = $connects->prepare("DELETE FROM sessionlogs WHERE sessiontokens = ? AND profileTags = ?");
        $stmt_bios->bind_param("ss", $token, $aidis);
        if($stmt_bios->execute()){
            $_SESSION['corsmsg'] = 'session token deleted';
            header ('location: ../session.php');
            exit;
        }else{
            $_SESSION['corsmsg'] = 'Failed to delete this tokens';
            header ('location: ../session.php');
            exit;
        };
        $stmt_bios->close();
    } else {
        $_SESSION['corsmsg'] = 'no token found';
        header ('location: ../session.php');
        exit;
    };
} else {
    header ('location: ../index.php');
    exit;
};
?>