<?php
require_once '../processes/database.php';
$errors = array();
session_start();
if (isset($_SESSION['profileTags'])) {
    $aidis = $_SESSION['profileTags'];
} else {
    header ('location: ../index.php');
    exit;
};

$State = "publics";
$tempLibsArr = array();
$prebind = '"' . $aidis . '"';
$check_orgs = $connects->prepare("SELECT identification, names, about, founded, founder, admins, logo, banner FROM ogroup WHERE JSON_CONTAINS(members, ?);");
$check_orgs->bind_param("s", $prebind);
$check_orgs->execute();
$result_check_orgs = $check_orgs->get_result();
if ($result_check_orgs->num_rows > 0) {
    while ($value = $result_check_orgs->fetch_assoc()) {
        $groupId = $value['identification'];
    };
};

$stmt_check_software = $connects->prepare("SELECT * FROM libslist WHERE libsPublisher = ? AND libsState = ? ;");
$stmt_check_software->bind_param("ss", $groupId, $State);
$stmt_check_software->execute();
$result_check_software = $stmt_check_software->get_result();
if ($result_check_software->num_rows > 0) {
    $uniqueItem = [];
    while ($value = $result_check_software->fetch_assoc()) {
        $ids = $value['libsIds'];
        $attachs = $value['libsAttachs'];
        $titles = $value['libsTitles'];
        $Desc = $value['libsDesc'];
        $addedDates = $value['addedDates'];
        $cltNumbs = $value['cltNumbs'];
        $category = $value['libsCategorys'];
        $fdrLibs = $value['fdrLibs'];
        if (!in_array($ids, $uniqueItem)) {
            $tempLibsArr[$ids] = [
            "libsIds"        => "$ids",
            "libsAttachs"    => "$attachs",
            "libsTitles"     => "$titles",
            "libsDesc"       => "$Desc",
            "libsCategorys"  => "$category",
            "addedDates"     => "$addedDates",
            "cltNumbs"       =>  $cltNumbs,
            "fdrLibs"        => "$fdrLibs"
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
    <link rel="shortcut icon" href="../logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styling/pallate.css">
    <link rel="stylesheet" href="../styling/Mindex.css">
    <link rel="stylesheet" href="../styling/footer.css">
    <title>Management Dashboard</title>
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
    <section class="posa l0 t10 pad-s w20 flex fld gap-s bg-tricol z2">
        <div class="pad-n-s pad-s-v w100p flex fld border-b">
            <button onclick="SetDialog('add');" class="pad-s w100p txtc txt-s bg-gold c-black">New Publish</button>
        </div>
        <div class="pad-n-s pad-st w100p flex fld border-b">
            <h2 class="pad-sb w100p txt-n">Product</h2>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Published</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Archived</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Annoucement</a>
        </div>
        <div class="pad-n-s pad-st w100p flex fld border-b">
            <h2 class="pad-sb w100p txt-n">utility</h2>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Statistics</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Feedbacks</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Achievement</a>
            <a href="#" class="pad-s-s pad-r pad-sb txt-s">Plugin</a>
        </div>
    </section>
    <section class="leftMg pad-s-v w79p flex gap-s z2">
    <?php
        ?>
    <?php
    // the published software
    if (!empty($tempLibsArr)) {
        foreach ($tempLibsArr as $id => $value) {
            $ids = $value['libsIds'];
            $attachs = $value['libsAttachs'];
            $titles = $value['libsTitles'];
            $desc = $value['libsDesc'];
            $addedDates = $value['addedDates'];
            $cltNumbs = $value['cltNumbs'];
            $category = $value['libsCategorys'];
            $fdrLibs = $value['fdrLibs'];
    ?>
        <div class="w30p flex fld bg-5 bora-s gap-s">
            <img src="../Library/libsImg/<?php echo $attachs;?>" alt="" class="w100p r16-9">
            <h2 class="pad-s txt-n"><?php echo $titles;?></h2>
            <div class="sideMg w100p flex">
                <button onclick="" class="autoMg pad-s w40p txt-s txtc bg-blue points z4">View</button>
                <button onclick="SetDialog('edit'); reloadFile('../LibrarylibsImg/<?php echo $attachs;?>'); LoadPublishs(this);" class="autoMg pad-s w40p txt-s txtc bg-red points z4" data-titles="<?php echo $titles;?>" data-desc="<?php echo $desc;?>" data-categoryIds="<?php echo $category;?>">Edit</button>
                <button onclick="SetDialog('update'); LoadPublishtoArchive('../LibrarylibsImg/<?php echo $attachs;?>', this);" data-titles="<?php echo $titles;?>" data-desc="<?php echo $desc;?>" data-categoryIds="<?php echo $category;?>" class="autoMg pad-s w40p txt-s txtc bg-green points z4">Archive</button>
            </div>
        </div>
    <?php
        };
    } else {
    ?>
        <p class="posr pad-b-v w100p txtc txt-n">Publish your software/games now!</p>
    <?php
    };
    ?>
    </section>
    <section class="leftMg pad-l w79p flex fld acjc">
    <!-- publish new dialog -->
        <dialog id="add-dialog" class="posr w100p h80 fld acjc bg-half-white ovs-v">
            <div class="posa lt0 w100p flex"><h2 class="rightMg pad-s txt-b">Add New Software/Game</h2><p class="pad-s-v pad-n-s txt-b red-hover" onclick="SetDialog('add')">X</p></div>
            <form class="w100p flex flex-r wrap" action="../component/post_out.php" method="post" enctype="multipart/form-data">
                <div class="posr r16-9 w50p flex fld acjc gap5">
                    <img id="prev" class="posr sideMg wh100p objfit">
                    <input class="posa c0 w100p txtc" type="file" name="file" accept="image/*" onchange="loadFile(event)" required>
                </div>
                <div class="form-input-container pad-s-v w50p flex fld gap5">
                    <div class="form-input-row sideMg w88p flex fld">
                        <label for="titles">Title</label>
                        <input type="text" name="titles" class="inptxt" placeholder="what's title?" auto-complete="off" maxlength="255" required>
                    </div>
                    <div class="form-input-row sideMg w88p flex fld">
                        <label for="desc">Description</label>
                        <input type="text" name="desc" class="inptxt" placeholder="Your best description of it" auto-complete="off" maxlength="255" required>
                    </div>
                    <div class="form-input-row sideMg w88p flex fld">
                        <label for="categoryids">category</label>
                        <select name="categoryids" class="inpselect" required>
                            <option value="" selected disabled>Select one category</option>
                            <?php
                            $stmt_get_categoryss = $connects->prepare("SELECT * FROM categorys WHERE categoryState = ?;");
                            $stmt_get_categoryss->bind_param("s", $State);
                            $stmt_get_categoryss->execute();
                            $result_get_categoryss = $stmt_get_categoryss->get_result();
                            if ($result_get_categoryss->num_rows > 0) {
                                $uniqueT = [];
                                while ($values =  $result_get_categoryss->fetch_assoc()) {
                                    $categoryIds = $values['categoryIds'];
                                    $categoryTitles = $values['categoryTitles'];
                                    if (!in_array($categoryIds, $uniqueT)) {
                                        echo "<option name='categoryids' value='$categoryIds' required>$categoryTitles</option>";
                                        $uniqueT[] = $topicIds;
                                    };
                                };
                            };
                            ?>
                        </select>
                    </div>
                    <div class="form-input-row sideMg w88p flex fld">
                        <input class="txt-n c-black" type="submit" name="submit" value="Publish">
                    </div>
                </div>
            </form>
        </dialog>
    <!-- the edit publishes dialog -->
        <dialog id="edit-dialog" class="posr w100p h80 fld acjc bg-half-white ovs-v">
            <div class="posa lt0 w100p flex"><h2 class="rightMg pad-s txt-b">Edit Publish</h2><p class="pad-s-v pad-n-s txt-b red-hover" onclick="SetDialog('edit')">X</p></div>
            <form class="w100p flex flex-r wrap" name="EDITSTUFF" action="../component/post_out.php" method="post" enctype="multipart/form-data">
                <div class="posr r16-9 w50p flex fld acjc gap5">
                    <img id="prevs" name="prevs" class="posr sideMg wh100p objfit">
                    <input class="posa ins0 wh100p txtc" type="file" name="file" accept="image/*" onchange="loadFiles(event)" required>
                </div>
                <div class="form-input-container pad-s-v w50p flex fld gap5">
                    <div class="form-input-row sideMg w88p flex fld">
                        <label for="titles">Title</label>
                        <input type="text" name="titles" class="inptxt" placeholder="what's title?" auto-complete="off" maxlength="255" required>
                    </div>
                    <div class="form-input-row sideMg w88p flex fld">
                        <label for="desc">Description</label>
                        <input type="text" name="desc" class="inptxt" placeholder="Your best description of it" auto-complete="off" maxlength="255" required>
                    </div>
                    <div class="form-input-row sideMg w88p flex fld">
                        <label for="categoryids">category</label>
                        <select name="categoryids" class="inpselect" required>
                            <option value="" selected disabled>Select one category</option>
                            <?php
                            $stmt_get_categoryss = $connects->prepare("SELECT * FROM categorys WHERE categoryState = ?;");
                            $stmt_get_categoryss->bind_param("s", $State);
                            $stmt_get_categoryss->execute();
                            $result_get_categoryss = $stmt_get_categoryss->get_result();
                            if ($result_get_categoryss->num_rows > 0) {
                                $uniqueT = [];
                                while ($values =  $result_get_categoryss->fetch_assoc()) {
                                    $categoryIds = $values['categoryIds'];
                                    $categoryTitles = $values['categoryTitles'];
                                    if (!in_array($categoryIds, $uniqueT)) {
                                        echo "<option name='categoryids' value='$categoryIds' required>$categoryTitles</option>";
                                        $uniqueT[] = $topicIds;
                                    };
                                };
                            };
                            ?>
                        </select>
                    </div>
                    <div class="form-input-row sideMg w88p flex fld">
                        <input class="txt-n c-black" type="submit" name="submit" value="Publish">
                    </div>
                </div>
            </form>
        </dialog>
    <!-- the archiving publish dialog -->
        <dialog id="update-dialog" class="posf ins0 wh100 fld acjc bg-half-white ovs-v z15">
            <div class="posa lt0 w100p flex"><h2 class="rightMg pad-s txt-b">Archive it?</h2><p class="pad-s-v pad-n-s txt-b red-hover" onclick="SetDialog('update')">X</p></div>
            <form class="w100p flex flex-r wrap" name="ARCHIVES" action="../component/post_out.php" method="post" enctype="multipart/form-data">
                <div class="posr r16-9 w50p flex fld acjc gap5">
                    <img id="previ" class="posr sideMg wh100p objfit">
                    <input class="posa ins0 wh100p txtc" type="file" name="file" accept="image/*" onchange="loadAFile(event)" required>
                </div>
                <div class="pad-s-v w50p flex fld gap5">
                    <div class="sideMg w88p flex fld">
                        <label for="titles">Title</label>
                        <input type="text" name="titles" class="inptxt" placeholder="what's title?" auto-complete="off" maxlength="255" required>
                    </div>
                    <div class="sideMg w88p flex fld">
                        <label for="desc">Description</label>
                        <input type="text" name="desc" class="inptxt" placeholder="Your best description of it" auto-complete="off" maxlength="255" required>
                    </div>
                    <div class="sideMg w88p flex fld">
                        <label for="categoryids">category</label>
                        <select name="categoryids" class="inpselect" required>
                            <option value="" selected disabled>Select one category</option>
                            <?php
                            $stmt_get_categoryss = $connects->prepare("SELECT * FROM categorys WHERE categoryState = ?;");
                            $stmt_get_categoryss->bind_param("s", $State);
                            $stmt_get_categoryss->execute();
                            $result_get_categoryss = $stmt_get_categoryss->get_result();
                            if ($result_get_categoryss->num_rows > 0) {
                                $uniqueT = [];
                                while ($values =  $result_get_categoryss->fetch_assoc()) {
                                    $categoryIds = $values['categoryIds'];
                                    $categoryTitles = $values['categoryTitles'];
                                    if (!in_array($categoryIds, $uniqueT)) {
                                        echo "<option name='categoryids' value='$categoryIds' required>$categoryTitles</option>";
                                        $uniqueT[] = $topicIds;
                                    };
                                };
                            };
                            ?>
                        </select>
                    </div>
                    <div class="sideMg w88p flex fld">
                        <input class="txt-n c-black" type="submit" name="submit" value="Publish">
                    </div>
                </div>
            </form>
        </dialog>
    </section>
    <?php include_once '../extra/footers.php';?>
    <script src="../scriptstuff/script.js"></script>
</body>
</html>