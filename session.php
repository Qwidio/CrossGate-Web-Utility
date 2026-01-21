<?php
require_once 'processes/database.php';
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
} else {
    header ('location: index.php');
    exit;
};
$allowNewSession = true;
$tempSessions = array();
$check_session = $connects->prepare("SELECT sessiontokens, addrss, osids, expirationDate, lastlogs FROM sessionlogs WHERE profileTags = ?;");
$check_session->bind_param("s", $aidis);
$check_session->execute();
$result_check_session = $check_session->get_result();
if ($result_check_session->num_rows >= 2) {
    $allowNewSession = false;
}
if ($result_check_session->num_rows > 0) {
    while ($value = $result_check_session->fetch_assoc()) {
        $sessiontokens = $value['sessiontokens'];
        $addrss = $value['addrss'];
        $osids = $value['osids'];
        $expirationDate = $value['expirationDate'];
        $lastlogs = $value['lastlogs'];
        if (!in_array($sessiontokens, $tempSessions)) {
            $tempSessions[$sessiontokens] = [
            "sessiontokens" => "$sessiontokens",
            "addrss" => "$addrss",
            "osids" => "$osids",
            "expirationDate" => "$expirationDate",
            "lastlogs" => "$lastlogs",
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="styling/pallate.css">
    <link rel="stylesheet" href="styling/Mindex.css">
    <link rel="stylesheet" href="styling/footer.css">
    <script>
    function loadSession(ReqstData) {
        const form = document.forms.EDITSESSION;
        const values = ReqstData.dataset;
        Object.keys(values).forEach((key) => {
            if (form[key]) 
                form[key].value = values[key];
        });
    };
    </script>
    <title>Session Manager</title>
</head>
<body>
    <!-- nav -->
    <div class="posr pad-n-s w100p minh10 flex gap-s bg-4 blurbg z4">
        <a href="index.php" class="vertiMg pad-s txt-l semibold">CROSSGATE</a>
        <div class="posr w60p flex gap-s">
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">MARKOUT</h2>
                <a href="Library/core/markout.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">PROFILE</h2>
                <a href="profile.php?user=self" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">   
                <h2 class="txt-n txtc semibold">CATEGORY</h2>
                <a href="Library/core/category.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">FORUM</h2>
                <a href="TS/forum/dashboard.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">DOCS</h2>
                <a href="documentation/docs.php" class="link-cover">.</a>
            </div>
        </div>
    </div>
    <div class="topMg-10 w88 flex">
        <a href="processes/session_add.php" class="leftMg pad-s txt-n bg-half-gray c-white border-1 hover-white"><?php if ($allowNewSession == true) {echo "Add new session";} else { echo "Maximum session allowed";};?></a>
    </div>
    <div class="posr topMg-s10 bottomMg-10 w88 flex fld border-1">
        <div class="posr pad-n-s w100p flex border-1 gap10 z4">
            <h2 class="pad-s-v w30p txt-n border-r ovh">session token</h2>
            <p class="pad-s-v w20p txt-n border-r ovh">address</p>
            <p class="pad-s-v w20p txt-n border-r ovh">device</p>
            <p class="pad-s-v w20p txt-n border-r ovh">expiration</p>
            <p class="pad-s-v w20p txt-n border-r ovh">last login</p>
            <div class="posr pad-m-v pad-ml r1-1 flex">
                <p class="posr wh100p objfit link-cover">.</p>
            </div>
            <div class="posr pad-m r1-1 flex">
                <p class="posr wh100p objfit link-cover">.</p>
            </div>
        </div>
    <?php
    foreach ($tempSessions as $id => $value) {
        $sessiontokens = $value['sessiontokens'];
        $addrss = $value['addrss'];
        $osids = $value['osids'];
        $expirationDate = $value['expirationDate'];
        $lastlogs = $value['lastlogs'];
    ?>
        <div class="posr pad-n-s w100p flex border-1 gap10 z4">
            <input type="text" class="pad-s-v w30p txt-n bg-transparent border-none border-r ovh" id="<?php echo $sessiontokens;?>" value="<?php echo $sessiontokens;?>" disabled>
            <p class="posr pad-s-v w20p txt-n border-r ovh"><?php echo $addrss;?><span class="blur-censor">.</span></p>
            <p class="pad-s-v w20p txt-n border-r ovh"><?php echo $osids;?></p>
            <p class="pad-s-v w20p txt-n border-r ovh"><?php echo $expirationDate;?></p>
            <p class="pad-s-v w20p txt-n border-r ovh"><?php echo $lastlogs;?></p>
            <div class="posr pad-m-v pad-ml r1-1 flex">
                <img src="img/copy.svg" alt="" class="posr wh100p objfit points" onclick="copy('<?php echo $sessiontokens;?>');">
            </div>
            <div class="posr pad-m r1-1 flex">
                <img src="img/trash-outline.svg" alt="" class="posr wh100p objfit points" onclick="SetDialog('edit'); loadSession(this);" data-token="<?php echo $sessiontokens;?>">
            </div>
        </div>
    <?php
    };
    ?>
    </div>
    <dialog id="edit-dialog" class="posf pad-n c0 pad-b-v minw20 maxh50 dp-none fld bg-1 border-1 bora-s z999">
        <form class="wh100p flex fld" name="EDITSESSION" action="processes/session_out.php" method="post">
            <h2 class="w100p txt-b txtc">Confirm Delete This Session?</h2>
            <input class="hiddeninp" type="text" name="token" hidden>
            <input class="topMg-s10 pad-s-v w100p txt-n txtc bg-red border-1 border-hover-white" type="submit" name="submit" value="YES">
        </form>
        <button class="topMg-s5 pad-s-v w100p txt-n txtc c-black border-1 border-hover-white" onclick="SetDialog('edit')">NO</button>
    </dialog>
    <div class="posr minh40">
        <p class="link-cover">.</p>
    </div>
    <!-- lil bit of messages passer -->
    <div id="alertcard">
        <p id="alertcontent"></p>
        <div id="borderanimate"></div>
    </div>
    <?php include_once 'footer.php';?>
    <script src="scriptstuff/script.js"></script>
    <script src="scriptstuff/alert.js"></script>
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