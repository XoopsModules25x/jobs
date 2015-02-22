<?php
//  -----------------------------------------------------------------------  //
//                           Jobs Module for Xoops 2.4.x                     //
//                         By John Mordo - jlm69 at Xoops                    //
//                                                                           //
//                                                                           //
//                                                                           //
// ------------------------------------------------------------------------- //
include 'header.php';

$mydirname = basename(dirname(__FILE__));
require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
$myts      =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
$lid       = !isset($_REQUEST['lid']) ? NULL : $_REQUEST['lid'];

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

include XOOPS_ROOT_PATH . '/header.php';
$result = $xoopsDB->query(
    "select usid, resume FROM " . $xoopsDB->prefix("jobs_resume") . " where lid=" . mysql_real_escape_string($lid) . ""
);
list($usid, $resume) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    $ok       = !isset($_REQUEST['ok']) ? NULL : $_REQUEST['ok'];
    $calusern = $xoopsUser->getVar("uid", "E");
    if ($usid == $calusern || $xoopsUser->isAdmin()) {
        if ($ok == 1) {
            if ($resume == 'created') {
                $xoopsDB->queryf(
                    "delete from " . $xoopsDB->prefix("jobs_created_resumes") . " where lid="
                        . mysql_real_escape_string($lid) . ""
                );
            } else {
                $xoopsDB->queryf(
                    "delete from " . $xoopsDB->prefix("jobs_resume") . " where lid=" . mysql_real_escape_string($lid)
                        . ""
                );
            }

            if ($resume) {
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
        echo"[ <a href=\"delresume.php?lid=" . addslashes($lid) . "&amp;ok=1\">" . _JOBS_OUI
            . "</a> | <a href=\"resumes.php\">" . _JOBS_NON . "</a> ]<br /><br />";
        echo "</td></tr></table>";
    }
}

include XOOPS_ROOT_PATH . '/footer.php';
