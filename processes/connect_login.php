<?php
require_once 'database.php';
session_start();
$errors = '';
if (isset($_SESSION['prev_loc'])) {
    $prev_loc = $_SESSION['prev_loc'];
} else {
    $prev_loc = "index.php";
};

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $errors = [];
    if (empty($username)) {
        $errors = "Username empty, why?";
        $_SESSION['corsmsg'] = $errors;
    }
    if (empty($password)) {
        $errors = "Where is yo password?";
        $_SESSION['corsmsg'] = $errors;
    }
    if (empty($errors)) {
        $stmt_check_username = $connects->prepare("SELECT userState FROM user WHERE username = ?");
        $stmt_check_username->bind_param("s", $username);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();
        if ($result_check_username->num_rows == 1) {
            $value = $result_check_username->fetch_assoc();
            $current_state = $value['userState'];
            $state = "approved";
            if ($current_state != $state) {
                $errors = "your account currently still in review";
                $_SESSION['corsmsg'] = $errors;
                header('location: ../forum-connect/connect_it.php?state=login');
                exit;
            }
            $stmt_check_password = $connects->prepare("SELECT * FROM user WHERE username = ? AND password = MD5(?) AND userState = ?");
            $stmt_check_password->bind_param("sss", $username, $password, $state);
            $stmt_check_password->execute();
            $result_check_password = $stmt_check_password->get_result();
            if ($result_check_password->num_rows == 1) {
                $value = $result_check_password->fetch_assoc();
                $_SESSION['profileTags'] = $value['profileTags'];
                $_SESSION['corsmsg'] = 'Login Successful';
                header('location: ../' . $prev_loc);
                exit;
            } else {
                $errors = "Password Invalid, try again";
                $_SESSION['corsmsg'] = $errors;
                header('location: ../forum-connect/connect_it.php?state=login');
                exit;
            }
            $stmt_check_password->close();
        } else {
            $errors = "User data inaccessible";
            $_SESSION['corsmsg'] = $errors;
            header('location: ../forum-connect/connect_it.php?state=login');
            exit;
        }
        $stmt_check_username->close();
    }
}
?>
