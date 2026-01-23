<?php
require_once '../../processes/database.php';
$errors = array();
session_start();
$_SESSION['prev_loc'] = "Library/core/category.php";
if (isset($_SESSION['profileTags'])) {
    $signed = true;
    $aidis = $_SESSION['profileTags'];
} else {
    $signed = false;
}
$SearchEnabled = "yes";
$page = "category";
$State = "publics";
$requestedItem = "empty";
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
    <title>Category list</title>
</head>
<body class="bg-2">
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
    <div class="posr w100p r4-1 flex fld acjc bg-3 border-1">
        <h2 class="w100p txtc txt-30 bold">CATEGORY</h2>
        <p class="w100p txtc txt-s">Where everything must be categorized</p>
    </div>
    <section class="topMg-5 bottomMg-10 w100p minh100 flex wrap acjc gap">
        <?php
        if (isset($requestedItem) && isset($searchTrigger)) {
        $stmt_check_category = $connects->prepare("SELECT * FROM categorys WHERE categoryState = ? AND categoryTitles LIKE '%$requestedItem%' ORDER BY categoryTitles DESC;");
        $stmt_check_category->bind_param("s", $State);
        } else {
        $stmt_check_category = $connects->prepare("SELECT * FROM categorys WHERE categoryState = ?;");
        $stmt_check_category->bind_param("s", $State);
        };
        $stmt_check_category->execute();
        $result_check_category = $stmt_check_category->get_result();
        if ($result_check_category->num_rows > 0) {
            $uniqueItem = [];
            while ($value = $result_check_category->fetch_assoc()) {
                $cgids = $value['categoryIds'];
                $titles = $value['categoryTitles'];
                if (!in_array($cgids, $uniqueItem)) {
        ?>
        <div class="posr w20p r16-9 flex acjc bg-1 border-1 bora-s">
            <p class="txtc txt-n semibold"><?php echo $titles;?></p>
            <a href="view.php?type=category&ids=<?php echo $cgids;?>" class="link-cover hover-white">.</a>
        </div>
        <?php
                };
            };
        } else {
        ?>
            <h2 class="autoMg w100p txtc">nothing found on the category list</h2>
        <?php
        };
        ?>
    </section>
    <?php include_once '../../extra/footer.php';?>
    <script src="../../scriptstuff/script.js"></script>
    <script src="../../scriptstuff/alert.js"></script>
</body>
</html>