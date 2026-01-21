<?php
require_once '../processes/database.php';
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
    if (isset($_POST['bioedits']) && isset($_POST['submit'])) {
        $profileTags = $aidis;
        $Bios = $_POST['bioedits'];
        $Bios = htmlspecialchars($Bios, ENT_QUOTES, 'UTF-8');
        $stmt_bios = $connects->prepare("UPDATE profiles SET profileBios = ? WHERE profileTags = ?");
        $stmt_bios->bind_param("ss", $Bios, $profileTags);
        if($stmt_bios->execute()){
            $_SESSION['corsmsg'] = 'Bio got updated';
            header ('location: ../profile.php?user=self');
            exit;
        }else{
            $_SESSION['corsmsg'] = 'Failed to send updated bio';
            header ('location: ../profile.php?user=self');
            exit;
        };
        $stmt_bios->close();
    } else {
        $_SESSION['corsmsg'] = 'edited bio is empty';
        header ('location: ../profile.php?user=self');
        exit;
    };
} else {
    header ('location: ../index.php');
    exit;
};
?>