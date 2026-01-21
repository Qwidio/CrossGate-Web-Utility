<?php
require_once 'database.php';
session_start();
$errors = '';
if (empty($_SESSION['prev_loc'])) {
    $prev_loc = $_SESSION['prev_loc'];
} else {
    $prev_loc = "index.php";
};

if (isset($_POST['Register'])) {
    function getRandomWord($len = 10) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
    $username = $_POST['username'];
    $password = $_POST['password'];
    $Email = $_POST['email'];
    $errors = [];
    if (empty($username)) {
        $errors[] = "Missing Username";
        $_SESSION['corsmsg'] = $errors;
    }
    if (empty($password)) {
        $errors[] = "You can't have an account without password";
        $_SESSION['corsmsg'] = $errors;
    }
    if (empty($Email)) {
        $errors[] = "Email is empty, I need it to prevent bot account :(";
        $_SESSION['corsmsg'] = $errors;
    }
    if (empty($errors)) {
        $stmt_check = $connects->prepare("SELECT username FROM user WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows == 0) {
            $rnum = random_int(1000, 9897);
            $rword = getRandomWord();
            $profileTags = $username . "_" . $rword . "_" . $rnum;
            $stmt_insert = $connects->prepare("INSERT INTO user (profileTags, username, password, Email) VALUES (?, ?, MD5(?), ?)");
            $stmt_insert->bind_param("ssss", $profileTags, $username, $password, $Email);
            if ($stmt_insert->execute()) {
                $_SESSION['corsmsg'] = 'Your registered data have been uploaded, your account will be reviewed for approval';
                header('location: ../forum-connect/connect_it.php?state=login');
                exit;
            } else {
                $errors = "Registration failed: " . $stmt_insert->error;
                $_SESSION['corsmsg'] = $errors;
                header('location: ../forum-connect/connect_it.php?state=register');
                exit;
            }
            $stmt_insert->close();
        } else {
            $errors = "Username taken, choose another";
            $_SESSION['corsmsg'] = $errors;
            header('location: ../forum-connect/connect_it.php?state=register');
            exit;
        }
        $stmt_check->close();
    }
}

?>