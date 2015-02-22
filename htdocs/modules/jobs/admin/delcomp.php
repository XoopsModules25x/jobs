<?php
//                 Jobs for Xoops 2.3.3b and up  by John Mordo - jlm69 at Xoops              //
//                                                                                           //
//   ----------------------------------------------------------------------------------------//
//include("admin_header.php");
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php");

$myts =& MyTextSanitizer::getInstance();

xoops_cp_header();

if (!empty($_POST['comp_id'])) {
    $comp_id = intval($_POST['comp_id']);
} elseif (!empty($_GET['comp_id'])) {
    $comp_id = intval($_GET['comp_id']);
} else {
    $comp_id = "";
}
if (!empty($_POST['comp_name'])) {
    $comp_name = $_POST['comp_name'];
} else {
    $comp_name = "";
}
$result = $xoopsDB->query(
    "select comp_name, comp_usid, comp_img FROM " . $xoopsDB->prefix("jobs_companies") . " where comp_id="
        . mysql_real_escape_string($comp_id) . ""
);
list($comp_name, $comp_usid, $photo) = $xoopsDB->fetchRow($result);

$result1 = $xoopsDB->query(
    "select company, usid FROM " . $xoopsDB->prefix("jobs_listing") . " where usid="
        . mysql_real_escape_string($comp_usid) . ""
);
list($their_company, $usid) = $xoopsDB->fetchRow($result1);

$ok = !isset($_REQUEST['ok']) ? NULL : $_REQUEST['ok'];

if ($ok == 1) {

// Delete Company
    $xoopsDB->queryf(
        "delete from " . $xoopsDB->prefix("jobs_companies") . " where comp_id=" . mysql_real_escape_string($comp_id)
            . ""
    );

// Delete all listing by Company
    if ($comp_name == $their_company) {
        $xoopsDB->queryf(
            "delete from " . $xoopsDB->prefix("jobs_listing") . " where usid=" . mysql_real_escape_string($comp_usid)
                . ""
        );
    }

// Delete Company logo

    if ($photo) {
        $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/logo_images";
        if (file_exists("$destination/$photo")) {
            unlink("$destination/$photo");
        }
    }
    redirect_header("company.php", 13, _AM_JOBS_COMPANY_DEL);
    exit();
} else {
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo "<br /><center>";
    echo "<b>" . _AM_JOBS_SURECOMP . "" . $comp_name . "" . _AM_JOBS_SURECOMPEND . "</b><br /><br />";
//	}
    echo"[ <a href=\"delcomp.php?comp_id=$comp_id&amp;ok=1\">" . _AM_JOBS_YES . "</a> | <a href=\"index.php\">"
        . _AM_JOBS_NO . "</a> ]<br /><br />";
    echo "</td></tr></table>";
}

xoops_cp_footer();
