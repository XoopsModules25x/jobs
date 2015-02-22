<?php
//  -----------------------------------------------------------------------  //
//                           Jobs Module for Xoops 2.4.x                     //
//                     By John Mordo - jlm69at Xoops                         //
//                         Licence Type   : GPL                              //
// ------------------------------------------------------------------------- //
include 'header.php';

$mydirname = basename(dirname(__FILE__));
require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
$myts      =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

$gperm_handler =& xoops_gethandler('groupperm');

if (isset($_POST['item_id'])) {
    $perm_itemid = intval($_POST['item_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight("jobs_submit", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$mydirname/index.php", 3, _NOPERM);
    exit();
}
include XOOPS_ROOT_PATH . "/header.php";

$r_lid = isset($_GET['r_lid']) ? intval($_GET['r_lid']) : 0;
$ok    = isset($_GET['ok']) ? intval($_GET['ok']) : 0;

$result = $xoopsDB->query(
    "select lid, r_usid FROM " . $xoopsDB->prefix("jobs_replies") . " where r_lid=" . mysql_real_escape_string($r_lid)
        . ""
);
list($lid, $usid) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    $member_id = $xoopsUser->getVar("uid", "E");

    $request1 = $xoopsDB->query(
        "select comp_usid, comp_user1, comp_user2 FROM " . $xoopsDB->prefix("jobs_companies") . " where " . $member_id
            . " IN (comp_usid, comp_user1, comp_user2)"
    );
    list($comp_usid, $comp_user1, $comp_user2) = $xoopsDB->fetchRow($request1);
    $comp_users = array($comp_usid, $comp_user1, $comp_user2);
    if (in_array($member_id, $comp_users)) {

        if ($ok == 1) {
            $xoopsDB->queryf(
                "delete from " . $xoopsDB->prefix("jobs_replies") . " where r_lid=" . mysql_real_escape_string($r_lid)
                    . ""
            );

            redirect_header("index.php", 3, _JOBS_JOBDEL);
            exit();
        } else {
            echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
            echo "<br /><center>";
            echo "<b>" . _JOBS_SURDELREPLY . "</b><br /><br />";
        }
        echo"[ <a href=\"delreply.php?r_lid=" . addslashes($r_lid) . "&amp;ok=1\">" . _JOBS_OUI
            . "</a> | <a href=\"replies.php?lid=" . addslashes($lid) . "\">" . _JOBS_NON . "</a> ]<br /><br />";
        echo "</td></tr></table>";
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
