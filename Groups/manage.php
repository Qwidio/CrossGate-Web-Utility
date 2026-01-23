<?php
// test things
// $new_members = ['new_member1', 'new_member2']; // New members you want to add
// // Fetch current members from the database
// $check_orgs = $connects->prepare("SELECT members FROM ogroups WHERE identification = ?;");
// $check_orgs->bind_param("s", $oGroups);
// $check_orgs->execute();
// $result_check_orgs = $check_orgs->get_result();
// if ($result_check_orgs->num_rows == 1) {
//     $value = $result_check_orgs->fetch_assoc();
//     $members = json_decode($value['members'], true); // Decode the current members JSON array
//     // Check if members already exist
//     foreach ($new_members as $new_member) {
//         if (!in_array($new_member, $members)) {
//             $members[] = $new_member;  // Add if not already present
//         }
//     }

//     // Encode the updated members array back into JSON
//     $updated_members_json = json_encode($members)
//     // Update the members in the database
//     $update_query = $connects->prepare("UPDATE ogroups SET members = ? WHERE identification = ?;");
//     $update_query->bind_param("ss", $updated_members_json, $oGroups);
//     $update_query->execute();
//     if ($update_query->affected_rows > 0) {
//         echo "New members were successfully added.";
//     } else {
//         echo "No changes made, possibly because the group identification doesn't exist.";
//     }
// } else {
//     echo "Group not found.";
// }



// $check_orgs = $connects->prepare("SELECT JSON_EXTRACT(members, '$[0]') AS first_member FROM ogroups WHERE identification = ?;");
// $check_orgs->bind_param("s", $oGroups);
// $check_orgs->execute();
// $result_check_orgs = $check_orgs->get_result();

// if ($result_check_orgs->num_rows == 1) {
//     $value = $result_check_orgs->fetch_assoc();
//     $first_member = $value['first_member'];
//     echo "First member: " . $first_member;
// }


// $users_to_check = ['taka21', 'C0rals', 'S4nders']; // Example user list
// $found_users = [];

// foreach ($users_to_check as $user_to_check) {
//     $user_to_check_json = json_encode($user_to_check);

//     $check_orgs = $connects->prepare("SELECT 1 FROM ogroups WHERE identification = ? AND JSON_CONTAINS(members, ?) = 1;");
//     $check_orgs->bind_param("ss", $oGroups, $user_to_check_json);
//     $check_orgs->execute();
//     $result_check_orgs = $check_orgs->get_result();

//     if ($result_check_orgs->num_rows > 0) {
//         $found_users[] = $user_to_check;
//     }
// }

// if (!empty($found_users)) {
//     echo "Found users: " . implode(', ', $found_users);
// } else {
//     echo "No users found in the group.";
// }


// $check_orgs = $connects->prepare("SELECT members FROM ogroups WHERE identification = ?;");
// $check_orgs->bind_param("s", $oGroups);
// $check_orgs->execute();
// $result_check_orgs = $check_orgs->get_result();

// if ($result_check_orgs->num_rows == 1) {
//     $value = $result_check_orgs->fetch_assoc();
//     $members = json_decode($value['members'], true);

//     if (in_array('taka21', $members)) {
//         echo "User 'taka21' exists in the group.";
//     } else {
//         echo "User 'taka21' does not exist in the group.";
//     }
// }

// the real thing
require_once '../processes/database.php';
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
} else {
    header ('location: ../index.php');
    exit;
};
if (!isset($_GET['gids'])) {
    $_SESSION['corsmsg'] = "denied request";
    header ('location: ../index.php');
    exit;
}
$gids = $_GET['gids'];

$prebind = '"' . $aidis . '"';
$check_orgs = $connects->prepare("SELECT names, about, founded, founder, admins, members, logo, banner FROM ogroup WHERE identification = ? AND JSON_CONTAINS(members, ?);");
$check_orgs->bind_param("ss", $gids ,$prebind);
$check_orgs->execute();
$result_check_orgs = $check_orgs->get_result();
if ($result_check_orgs->num_rows > 0) {
    while ($value = $result_check_orgs->fetch_assoc()) {
        $names = $value['names'];
        $about = $value['about'];
        $founded = $value['founded'];
        $admins = $value['admins'];
        $members = $value['members'];
        $logo = $value['logo'];
        $banner = $value['banner'];
        if ($admins != $aidis) {
            $_SESSION['corsmsg'] = "You are not allowed to make group changes";
            header('location: ../index.php');
            exit;
        }
    }
} else {
    $_SESSION['corsmsg'] = "You are not allowed to access this page";
    header('location: ../index.php');
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
    <link rel="stylesheet" href="../styling/footer.css">
    <title>Groups Management</title>
</head>
<body class="w100p minh100 gap10">
    <div class="posr pad-n-s w100p minh10 flex gap-s bg-4 blurbg z4">
        <a href="../index.php" class="vertiMg pad-s txt-l semibold">CROSSGATE</a>
        <div class="posr w60p flex gap-s">
            <?php
            if (isset($aidis)) {
                ?>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">MARKOUT</h2>
                <a href="../Library/core/markout.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">PROFILE</h2>
                <a href="../profile.php?user=self" class="link-cover">.</a>
            </div>
            <?php
            }
            ?>
            <div class="posr pad-s flex fld acjc">   
                <h2 class="txt-n txtc semibold">CATEGORY</h2>
                <a href="../Library/core/category.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">FORUM</h2>
                <a href="../TS/forum/dashboard.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">DOCS</h2>
                <a href="../documentation/docs.php" class="link-cover">.</a>
            </div>
        </div>
        <?php
        if (!isset($aidis)) {
        ?>
        <div class="leftMg flex acjc gap10">
            <p class="posr pad-n-s pad-s-v txtc txt-n bg-1 border-1 bora-s border-hover-white">LOGIN
                <a href="../forum-connect/connect_it.php?state=login" class="link-cover">.</a>
            </p>
        </div>
        <?php
        };
        ?>
    </div>
    <div class="minh100"></div>
    <?php include_once '../footer.php';?>
<!-- another messages passer -->
    <div id="alertcard">
        <p id="alertcontent"></p>
        <div id="borderanimate"></div>
    </div>
    <script src="../scriptstuff/script.js"></script>
    <script src="../scriptstuff/alert.js"></script>
    <?php
    if (!empty($errors)) {
        echo "<script> ";
        echo "alerter('"; foreach ($errors as $error) {echo $error .";";} echo "')";
        echo "</script>";
    }
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