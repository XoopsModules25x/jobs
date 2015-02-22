<?php
//  -----------------------------------------------------------------------  //
//                           Jobs for Xoops 2.4.x                            //
//                  By John Mordo - jlm69 at Xoops                           //
//                       Licence Type   : GPL                                //
// ------------------------------------------------------------------------- //
include 'header.php';
$mydirname = basename(dirname(__FILE__));
require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
$myts =& MyTextSanitizer::getInstance();
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/resume_functions.php");
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
if (!$gperm_handler->checkRight("resume_submit", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/$mydirname/resumes.php", 3, _NOPERM);
    exit();
}

$result = $xoopsDB->query(
    "select made_resume, usid FROM " . $xoopsDB->prefix("jobs_created_resumes") . " where lid="
        . mysql_real_escape_string($lid) . ""
);
list($made_resume, $usid) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    include(XOOPS_ROOT_PATH . "/header.php");
    $calusern = $xoopsUser->getVar("uid", "E");
    if ($usid == $calusern) {
        if ($ok == 1) {
            $xoopsDB->queryf(
                "delete from " . $xoopsDB->prefix("jobs_created_resumes") . " where lid="
                    . mysql_real_escape_string($lid) . ""
            );
            if ($made_resume) {
                $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/resumes";
                if (file_exists("$destination/$resume")) {
                    unlink("$destination/$resume");
                }
            }
            redirect_header("resumes.php", 3, _JOBS_RES_JOBDEL);
            exit();
        } else {
            echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
            echo "<br /><center>";
            echo "<b>" . _JOBS_SURDELRES . "</b><br /><br />";
        }
        echo"[ <a href=\"editresume.php?op=CreatedDel&amp;lid=" . addslashes($lid) . "&amp;ok=1\">" . _JOBS_OUI
            . "</a> | <a href=\"resumes.php\">" . _JOBS_NON . "</a> ]<br /><br />";
        echo "</td></tr></table>";
    }
}

include(XOOPS_ROOT_PATH . "/footer.php");
