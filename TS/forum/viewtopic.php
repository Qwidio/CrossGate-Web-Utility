<?php
require_once '../../processes/database.php';
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
} else {
    header ('location: ../../index.php');
    exit;
};
$topicIds = $_GET['topicIds'];
$topicIds = htmlspecialchars($topicIds, ENT_QUOTES, 'UTF-8');
$page = "viewtopic";
$paramsubpage = "topicIds";
$subpage = $topicIds;
$State = "Publics";
if (isset($_GET['item']) && isset($_GET['onsearch'])) {
    $searchTrigger = $_GET['onsearch'];
    $requestedItem = $_GET['item'];
} else {
    $requestedItem = "empty";
};
$requestedItem = htmlspecialchars($requestedItem, ENT_QUOTES, 'UTF-8');
$stmt_check_Topic = $connects->prepare("SELECT * FROM topics WHERE TopicState = ? AND TopicIds = ?;");
$stmt_check_Topic->bind_param("ss", $State, $topicIds);
$stmt_check_Topic->execute();
$result_check_Topic = $stmt_check_Topic->get_result();
if ($result_check_Topic->num_rows == 1) {
    $value = $result_check_Topic->fetch_assoc();
    $TopicIds = $value['topicIds'];
    $Ttitles = $value['topicTitles'];
    $dates = $value['topicDates'];
    $descs = $value['topicContents'];
    $attachs = $value['topicAttachs'];
} else {
    $_SESSION['corsmsg'] = "the topic you tried to open does not exists";
    header ('location: topic.php');
    exit;
};
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
    <title><?php echo $Ttitles;?></title>
</head>
<body>
<!-- the nav -->
    <div class="posr pad-n-s w100p minh10 flex gap-s bg-4 blurbg z4">
        <a href="index.php" class="vertiMg pad-s txt-l semibold">CROSSGATE</a>
        <div class="posr w60p flex gap-s">
            <?php
            if (isset($aidis)) {
                ?>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">MARKOUT</h2>
                <a href="markout.php" class="link-cover">.</a>
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
                <a href="category.php" class="link-cover">.</a>
            </div>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">FORUM</h2>
                <a href="../../TS/forum/dashboard.php" class="link-cover">.</a>
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
<!-- topic container -->
    <div class="pad-n w100p minh100 flex fld">
        <h1 class="sideMg w95p txt-b"><?php echo $Ttitles;?></h1>
        <p class="sideMg w95p txt-s"><?php echo $dates;?></p>
        <?php
        if ($attachs != "empty.png" && isset($attachs)) {
        ?>
        <h2 class="sideMg topMg-s10 w95p"><?php echo $descs;?></h2>
        <img src="../ArchFiles/<?php echo $attachs;?>" alt="<?php echo $titles;?>" class="sideMg topMg-s10 w50p r16-9 objfit border-1">
        <?php
        } else {
        ?>
        <h2 class="sideMg topMg-s10 w95p minh30"><?php echo $descs;?></h2>
        <?php
        };
        ?>
        <div class="sideMg bottomMg w95p minh50 flex wrap gap-s acjc z1">
        <?php
        if (isset($requestedItem) && isset($searchTrigger)) {
        $stmt_check_forumtopics = $connects->prepare("SELECT * FROM forums WHERE ForumTopics = ? AND ForumTitles LIKE '%$requestedItem%' ORDER BY ForumDates DESC;");
        $stmt_check_forumtopics->bind_param("s", $TopicIds);
        } else {
        $stmt_check_forumtopics = $connects->prepare("SELECT * FROM forums WHERE ForumTopics = ? ORDER BY ForumDates DESC;");
        $stmt_check_forumtopics->bind_param("s", $TopicIds);
        };
        $stmt_check_forumtopics->execute();
        $result_check_forumtopics = $stmt_check_forumtopics->get_result();
        if ($result_check_forumtopics->num_rows > 0) {
            $uniqueItem = [];
            while ($value = $result_check_forumtopics->fetch_assoc()) {
                $ids= $value['ForumIds'];
                $creators = $value['ForumCreator'];
                $titles = $value['ForumTitles'];
                $topics = $value['ForumTopics'];
                $dates = $value['ForumDates'];
                $contents = $value['ForumContents'];
                if (!in_array($ids, $uniqueItem)) {
        ?>
        <div class="posr pad-s w30p h40 flex fld bg-blue bora-n z2">
            <h2 class="txt-n"><?php echo $titles;?></h2>
            <div class="bottomMg-s5 w100p flex space-between">
                <p class="txt-s"><?php echo $creators;?></p>
                <p class="txt-s"><?php echo $dates;?></p>
            </div>
            <p class="forum-content"><?php echo $contents;?>
            </p>
            <a href="forum.php?ids=<?php echo $ids;?>" class="link-cover">.</a>
        </div>
        <?php
                };
            };
        } else {
        ?>
                <h2 class="posr pad-s w30p h40 txtc z2">no forum for this topic yet</h2>
        <?php
        };
        ?>
        </div>
    </div>
</div>
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