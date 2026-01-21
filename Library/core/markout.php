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
$page = "markout";
$State = "publics";
$requestedItem = "empty";
if (isset($_GET['item'])) {
    $requestedItem = $_GET['item'];
} else {
    $requestedItem = "empty";
};
$requestedItem = htmlspecialchars($requestedItem, ENT_QUOTES, 'UTF-8');
$sources = "../../drx/$aidis.json";
$jsonData = file_get_contents($sources);
$data = json_decode($jsonData, true);
$profileTag = $data['profileTags'];
$markedData = $data['marked'];
$marked = [];
foreach ($markedData as $markedIndex => $info) {
    $marked[$markedIndex] = [
        "libsIds"  => $info['libsIds'],
        "Hours"    => (int)$info['Hours'],
        "lastLog"  => $info['lastLog'],
    ];
}
$usrDatTemp[] = [
    "profileTags" => $profileTag,
    "marked"      => $marked
];

$tempLibsArr = array();
$check_software = $connects->prepare("SELECT libsIds, libsAttachs, JSON_EXTRACT(libsBanners, '$[0]') AS libsBanners, libsTitles, libsDesc, libsForum FROM libslist WHERE libsState = ? ;");
$check_software->bind_param("s", $State);
$check_software->execute();
$result_check_software = $check_software->get_result();
if ($result_check_software->num_rows > 0) {
    while ($value = $result_check_software->fetch_assoc()) {
        $ids = $value['libsIds'];
        $attachs = $value['libsAttachs'];
        $libsBanners = $value['libsBanners'];
        $libsBanners = str_replace('"', "", $libsBanners);
        $titles = $value['libsTitles'];
        $Desc = $value['libsDesc'];
        $libsForum = $value['libsForum'];
        if (!in_array($ids, $tempLibsArr)) {
            $tempLibsArr[$ids] = [
            "libsIds"        => "$ids",
            "libsAttachs"    => "$attachs",
            "libsBanners"    => "$libsBanners",
            "libsTitles"     => "$titles",
            "libsDesc"       => "$Desc",
            "libsForum"      => "$libsForum"
            ];
        };
    };
};

$tempForums = array();
$check_forum = $connects->prepare("SELECT * FROM forums WHERE ForumState = ? ORDER BY ForumDates ASC;");
$check_forum->bind_param("s", $State);
$check_forum->execute();
$result_check_forum = $check_forum->get_result();
if ($result_check_forum->num_rows > 0) {
    $uniqueItem = [];
    while ($value = $result_check_forum->fetch_assoc()) {
        $id = $value['ForumIds'];  
        $creators = $value['ForumCreator'];
        $title = $value['ForumTitles'];
        $topics = $value['ForumTopics'];
        $date = $value['ForumDates'];
        $descs = $value['ForumContents'];
        $attaches = $value['ForumAttachment'];
        if (!in_array($id, $tempForums)) {
            $tempForums[$topics] = [
            "ForumIds"        => "$id",
            "ForumCreator"    => "$creators",
            "ForumTitles"     => "$title",
            "ForumTopics"     => "$topics",
            "ForumDates"      => "$date",
            "ForumContents"   => "$descs",
            "ForumAttachment" => "$attaches",
            ];
        };
    };
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
    <title>MarkOut Software</title>
</head>
<body class="wh100p bg-2 flex fld">
<!-- the nav -->
    <div class="posr pad-n-s w100p minh10 flex gap-s bg-4 blurbg z4">
        <a href="index.php" class="vertiMg pad-s txt-l semibold">CROSSGATE</a>
        <div class="posr w60p flex gap-s">
            <?php
            if (isset($aidis)) {
                ?>
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
    <section class="posa l0 t10 pad-s w20 minh100 flex fld gap-s bg-tricol z2">
        <div class="pad-n-s pad-st w100p flex fld border-b">
            <h2 class="pad-sb w100p txt-n">Titles</h2>
            <?php
            $tempCopy = $usrDatTemp[0]['marked'];
            uasort($tempCopy, function ($b, $a) {
                $timeA = strtotime($a['lastLog']);
                $timeB = strtotime($b['lastLog']);
                return $timeB <=> $timeA;
            });
            foreach ($tempCopy as $id => $value) {
                $LibIds = $value['libsIds'];
                $hour = $value['Hours'];
                $titles = $tempLibsArr[$LibIds]['libsTitles'];
                $attachs = $tempLibsArr[$LibIds]['libsAttachs'];
            ?>
            <div class="posr pad-s-s pad-r pad-m-v w100p flex">
                <img src="../libsimg/<?php echo $attachs;?>" alt="<?php echo $attachs;?>" class="vertiMg rightMg-s10 icon-rs objfit">
                <h2 class="w100p txt-s"><?php echo $titles;?></h2>
                <a href="view.php?type=clts&ids=<?php echo $LibIds;?>" class="link-cover hover-white">.</a>
            </div>
            <?php
            };
            ?>
        </div>
    </section>
    <section class="leftMg pad-n w79 flex fld">
        <h2 class="leftMg bottomMg-s5 w100p">Launched recently</h2>
        <div class="h100p flex gap-s ovs-s ovh-v">
        <?php
        $tempCopy = $usrDatTemp[0]['marked'];
        uasort($tempCopy, function ($b, $a) {
            $timeA = strtotime($a['lastLog']);
            $timeB = strtotime($b['lastLog']);
            return $timeB <=> $timeA;
        });
        foreach ($tempCopy as $id => $value) {
            $LibIds = $value['libsIds'];
            $hour = $value['Hours'];
            $titles = $tempLibsArr[$LibIds]['libsTitles'];
            $attachs = $tempLibsArr[$LibIds]['libsAttachs'];
            $banners = $tempLibsArr[$LibIds]['libsBanners'];
        ?>
            <div class="posr h30 r16-9 bg-1 flex fld border-1 z1">
                <img src="../libsimg/<?php echo $banners;?>" alt="<?php echo $banners;?>" class="posa ins0 wh100p bg-3 z2">
                <h2 class="topMg pad-s-s pad-m-v w100p txt-s bg-half-gray z3"><?php echo $titles;?></h2>
                <p class="pad-s-s pad-sb w100p txt-s bg-half-gray z3">Total time: <?php echo $hour;?> hrs</p>
                <a href="view.php?type=clts&ids=<?php echo $LibIds;?>" class="link-cover hover-white">.</a>
            </div>
        <?php
        };
        ?>
    </section>
    <section class="leftMg pad-n w79 flex fld">
        <h2 class="leftMg bottomMg-s5 w100p">Publisher Announcement</h2>
        <div class="h100p flex gap-s ovs-s ovh-v">
        <?php
        uasort($tempForums, function ($a, $b) {
            $timeA = strtotime($a['ForumDates']);
            $timeB = strtotime($b['ForumDates']);
            return $timeB <=> $timeA;
        });
        foreach ($tempCopy as $id => $value) {
            $LibIds = $value['libsIds'];
            $libsForum = $tempLibsArr[$LibIds]['libsForum'];
            $ForumIds = $tempForums[$libsForum]['ForumIds'];
            $ForumAttachment = $tempForums[$libsForum]['ForumAttachment'];
            $ForumTopics = $tempForums[$libsForum]['ForumTopics'];
            $ForumTitles = $tempForums[$libsForum]['ForumTitles'];
            $ForumContents = $tempForums[$libsForum]['ForumContents']; 
        ?>
            <div class="posr pad-s w30 r16-9 flex fld border-2 z1">
                <?php
                if ($ForumAttachment != "empty.png" && isset($ForumAttachment)) {
                ?>
                <img src="../../TS/ArchFiles/<?php echo $ForumAttachment;?>" alt="" class="posa ins0 r16-9 wh100p opacity5 z2">
                <?php
                } else {
                ?>
                <img src="#" alt="" class="posa ins0 r16-9 wh100p bg-1 z2">
                <?php
                };
                ?>
                <h2 class="bottomMg txt-n z3"><?php echo $ForumTitles;?></h2>
                <p class="txt-s z3"><?php echo $ForumContents;?></p>
                <a href="../../TS/forum/forum.php?ids=<?php echo $ForumIds;?>" class="link-cover hover-white">.</a>
            </div>
        <?php
        };
        ?>
        </div>
    </section>
    <section class="leftMg pad-s w79 h20"></section>
<!-- messages passer --> 
    <div id="alertcard">
        <p id="alertcontent"></p>
        <div id="borderanimate"></div>
    </div>
    <?php include_once '../../extra/footer.php';?>
    <script src="../../scriptstuff/script.js"></script>
    <script src="../libsSys/IntakeSFT.js"></script>
    <script src="../../scriptstuff/alert.js"></script>
    <?php
    if (!empty($errors)) {
        echo "<script> ";
        echo "alerter('"; foreach ($errors as $error) {echo $error .";";} echo "')";
        echo "</script>";
    };
    if (!empty($_SESSION['corsmsg'])) {
        $corsmsg = $_SESSION['corsmsg'];
        echo "<script> ";
        echo "alerter('" . $corsmsg . "')";
        echo "</script>";
        $_SESSION['corsmsg'] = "";
    };
    ?>
</body>
</html>