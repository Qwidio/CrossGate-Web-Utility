<?php
require_once '../../processes/database.php';
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
} else {
    header ('location: ../../index.php');
    exit;
}
$requestedItem = "empty";
$page = "dashboard";
$UploadEnabled = "yes";
$State = "Publics";
if (isset($_GET['item']) && isset($_GET['onsearch'])) {
    $searchTrigger = $_GET['onsearch'];
    $requestedItem = $_GET['item'];
} else {
    $requestedItem = "empty";
};
$requestedItem = htmlspecialchars($requestedItem, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../styling/pallate.css">
    <link rel="stylesheet" href="../../styling/Mindex.css">
    <link rel="stylesheet" href="../../styling/footer.css">
    <title>Dashboard</title>
</head>
<body class="w100p minh100">
<!-- the nav -->
    <div class="posr pad-n-s w100p minh10 flex gap-s bg-4 blurbg z4">
        <a href="index.php" class="vertiMg pad-s txt-l semibold">CROSSGATE</a>
        <div class="posr w60p flex gap-s">
            <?php
            if (isset($aidis)) {
                ?>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">MARKOUT</h2>
                <a href="../../Library/core/markout.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">PROFILE</h2>
                <a href="../../profile.php?user=self" class="link-cover">.</a>
            </div>
            <?php
            }
            ?>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">CATEGORY</h2>
                <a href="../../Library/core/category.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">DOCS</h2>
                <a href="../../documentation/docs.php" class="link-cover">.</a>
            </div>
        </div>
        <?php
        if (!isset($aidis)) {
        ?>
        <div class="leftMg flex acjc gap10">
            <p class="posr pad-n-s pad-s-v txtc txt-n bg-1 border-1 bora-s border-hover-white">LOGIN
                <a href="../../forum-connect/connect_it.php?state=login" class="link-cover">.</a>
            </p>
        </div>
        <?php
        };
        ?>
    </div>
<div class="posr bottomMg-s10 w100p flex">
<!-- topic on right of the page -->
    <div class="posr pad-s w20p flex fld gap-s border-r z2">
        <div class="pad-n-s pad-st w100p flex fld border-b">
            <h2 class="pad-sb w100p txt-n semibold">Highlight</h2>
            <div class="posr pad-s-s pad-r pad-sb w100p flex fld">
                <h2 class="w100p txt-s ovh">Hlighted</h2>
                <a href="#" class="link-cover">.</a>
            </div>
        </div>
        <div class="pad-n-s pad-st w100p maxh30 flex fld border-b ovs-v">
            <h2 class="pad-sb w100p txt-n semibold points" onclick="linker('topic')">Topic</h2>
            <?php
            $stmt_check_topic = $connects->prepare("SELECT * FROM topics WHERE topicState = ?;");
            $stmt_check_topic->bind_param("s", $State);
            $stmt_check_topic->execute();
            $result_check_topic = $stmt_check_topic->get_result();
            if ($result_check_topic->num_rows > 0) {
                $uniqueItem = [];
                while ($value = $result_check_topic->fetch_assoc()) {
                    $ids = $value['topicIds'];
                    $titles = $value['topicTitles'];
                    if (!in_array($ids, $uniqueItem)) {
            ?>
            <div class="posr pad-s-s pad-r pad-sb w100p flex fld">
                <h2 class="w100p txt-s ovh"><?php echo $titles;?></h2>
                <a href="viewtopic.php?topicIds=<?php echo $ids;?>" class="link-cover">.</a>
            </div>
            <?php
                    };
                };
            } else {
            ?>
            <div class="posr pad-s-s pad-r pad-sb w100p flex fld">
                <h2 class="w100p txt-s">Error retrieving</h2>
                <a href="#" class="link-cover">.</a>
            </div>
            <?php
            };
            ?>
        </div>
        <div class="topMg minh10"></div>
    </div>
<!-- forum there -->
    <div class="leftMg-s10 rightMg-s10 w50p minh50 flex wrap gap10 acjc z1">
    <?php
    if ($requestedItem === "empty") {
    ?>
    <?php
        $stmt_check_HForum = $connects->prepare("SELECT * FROM forums WHERE ForumState = ? AND ForumHighlight = 'YES' ORDER BY ForumDates ASC;");
        $stmt_check_HForum->bind_param("s", $State);
        $stmt_check_HForum->execute();
        $result_check_HForum = $stmt_check_HForum->get_result();
        if ($result_check_HForum->num_rows > 0) {
            $uniqueItem = [];
            while ($value = $result_check_HForum->fetch_assoc()) {
                $Hids = $value['ForumIds'];
                $Hcreators = $value['ForumCreator'];
                $Htitles = $value['ForumTitles'];
                $Htopics = $value['ForumTopics'];
                $Hdates = $value['ForumDates'];
                $Hcontents = $value['ForumContents'];
                if (!in_array($Hids, $uniqueItem)) {
        ?>
        <div class="posr pad-s-v pad-n-s w100p h40 flex fld border-1 bora-s z2">
            <h2 class="sideMg bottomMg-s10 w90p txt-b"><?php echo $Htitles;?></h2>
            <div class="bottomMg-s5 w100p flex space-between">
                <p class="txt-s"><?php echo $Hcreators;?></p>
                <p class="txt-s"><?php echo $Hdates;?></p>
            </div>
            <p class="forum-content"><?php echo $Hcontents;?></p>
            <a href="forum.php?ids=<?php echo $Hids;?>" class="link-cover hover-white">.</a>
        </div>
    <?php
                };
            };
        };
    ?>
    <?php
    };
    if (isset($requestedItem) && isset($searchTrigger)) {
    $check_Forum = $connects->prepare("SELECT * FROM forums WHERE ForumState = ? AND ForumTitles LIKE '%$requestedItem%' ORDER BY ForumDates DESC;");
    $check_Forum->bind_param("s", $State);
    } else {
    $check_Forum = $connects->prepare("SELECT * FROM forums WHERE ForumState = ? AND ForumHighlight = 'NOs' ORDER BY ForumDates DESC;");
    $check_Forum->bind_param("s", $State);
    };
    $check_Forum->execute();
    $result_check_Forum = $check_Forum->get_result();
    if ($result_check_Forum->num_rows > 0) {
        $uniqueItem = [];
        while ($value = $result_check_Forum->fetch_assoc()) {
            $ids = $value['ForumIds'];
            $creators = $value['ForumCreator'];
            $titles = $value['ForumTitles'];
            $topics = $value['ForumTopics'];
            $dates = $value['ForumDates'];
            $contents = $value['ForumContents'];
            if (!in_array($ids, $uniqueItem)) {
    ?>
        <div class="posr pad-s-v pad-n-s w100p h40 flex fld border-1 bora-s z2">
            <h2 class="txt-n"><?php echo $titles;?></h2>
            <div class="bottomMg-s5 w100p flex space-between">
                <p class="txt-s"><?php echo $creators;?></p>
                <p class="txt-s"><?php echo $dates;?></p>
            </div>
            <p class="forum-content"><?php echo $contents;?>
            </p>
            <a href="forum.php?ids=<?php echo $ids;?>" class="link-cover hover-white">.</a>
        </div>
    <?php
            };
        };
    } else {
    ?>
        <p class="posr pad-s-v pad-n-s w100p h40 txtc z2">No forum found, got something wrong in there</p>
    <?php
    };
    ?>
    </div>
<!-- recommend post -->
    <div class="w30p minh20 flex border-l"></div>
</div>
<!-- forum create dialog -->
        <dialog id="add-dialog" class="posf lt0 wh100 flex fld acjc bg-white ovs-v z15">
            <div class="posa lt0 w100p flex"><h2 class="rightMg pad-s txt-b">Make New Forum</h2><p class="pad-s-v pad-n-s txt-b red-hover" onclick="SetDialog('add')">X</p></div>
                <form class="w100p flex flex-r wrap" action="../component/post_out.php" method="post" enctype="multipart/form-data">
                    <div class="posr r16-9 w50p flex fld acjc gap5">
                        <img id="prevs" class="posr sideMg wh100p objfit">
                        <input class="posa ins0 wh100p txtc" type="file" name="file" accept="image/*" onchange="loadFile(event)" required>
                    </div>
                    <div class="form-input-container">
                        <div class="form-input-row">
                            <label for="ForumTitles">Forum Titles</label>
                            <input type="text" name="ForumTitles" class="inptxt" placeholder="Make title for the forum" auto-complete="off" maxlength="255" required>
                        </div>
                        <div class="form-input-row">
                            <label for="ForumDescription">Forum Description</label>
                            <input type="text" name="ForumDescription" class="inptxt" placeholder="The description for the why or what start this forum " auto-complete="off" maxlength="255" required>
                        </div>
                        <div class="form-input-row">
                            <label for="ForumTopics">Topic</label>
                            <select name="ForumTopics" class="inpselect" required>
                                <option value="" selected disabled>Select Topic</option>
                                <?php
                                $stmt_get_topics = $connects->prepare("SELECT * FROM topics WHERE topicState = ?;");
                                $stmt_get_topics->bind_param("s", $State);
                                $stmt_get_topics->execute();
                                $result_get_topics = $stmt_get_topics->get_result();
                                if ($result_get_topics->num_rows > 0) {
                                    $uniqueT = [];
                                    while ($values =  $result_get_topics->fetch_assoc()) {
                                        $ForumTopics = $values['topicIds'];
                                        $topicTitles = $values['topicTitles'];
                                        if (!in_array($ForumTopics, $uniqueT)) {
                                            echo "<option name='ForumTopics' value='$ForumTopics' required>$topicTitles</option>";
                                            $uniqueT[] = $ForumTopics;
                                        };
                                    };
                                };
                                ?>
                            </select>
                        </div>
                        <div class="form-input-row">
                            <input class="post-button" type="submit" name="submit" value="Post">
                        </div>
                    </div>
                </form>
            </div>
    </dialog>
<!-- lil bit of messages passer -->
    <div id="alertcard">
        <p id="alertcontent"></p>
        <div id="borderanimate"></div>
    </div>
    <?php include_once '../../extra/footer.php';?>
    <script src="../../scriptstuff/script.js"></script>
    <script src="../../scriptstuff/alert.js"></script>
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