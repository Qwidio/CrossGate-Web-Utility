<?php
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
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
    <title>Documentation</title>
</head>
<body class="wh100p flex fld gap10 ovh-s ovs-v z1">
    <div class="posr pad-n-s w100p minh10 flex gap-s bg-4 blurbg z4">
        <a href="../index.php" class="vertiMg pad-s txt-l semibold">CROSSGATE</a>
        <div class="posr w60p flex gap-s">
            <?php
            if (isset($aidis)) {
                ?>
            <div class="posr pad-s flex fld acjc">
                <h2 class="txt-n txtc semibold">MARKOUT</h2>
                <a href="../markout.php" class="link-cover">.</a>
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
    <section class="posa l0 t10 w20 h100 flex fld border-r gap-s z15">
        <a href="#" class="pad-n txt-n border-b semibold">Introduction</a>
        <div class="pad-n-s pad-st w100p flex fld border-b">
            <h2 class="pad-sb w100p txt-n">Library & Forum</h2>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">MarkOut</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Forum posting</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Session</a>
        </div>
        <div class="pad-n-s pad-st w100p flex fld border-b">
            <h2 class="pad-sb w100p txt-n">CrossGate client</h2>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Instalation</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Auth</a>
        </div>
    </section>
    <section class="leftMg pad-nl w79p h80 flex fld gap-s">
        <div class="pad-n-v w100p flex fld border-b">
            <p class="txt-30">CrossGate Documentation</p>
        </div>
        <div class="bottomMg pad-s-v leftMg w100p flex fld">
            <h2 class="bottomMg-s5 txt-b">Introduction</h2>
            <p class="txt-s">Work in Progress documentation for CrossGate website, client and all of it's plugin, to be honest i'm not sure this documentation will get finished or not</p>
        </div>
    </section>
    <section class="leftMg pad-nl w79p flex fld acjc gap5">
        <h2 class="pad-s-v w100p txt-b border-t">Reference link</h2>
        <div class="w100p flex wrap gap5">
            <a href="#" class="txt-s txt-hlb">Forum</a>
            <a href="#" class="txt-s txt-hlb">Library</a>
        </div>
    </section>
    <?php include_once '../footer.php';?>
</body>
</html>