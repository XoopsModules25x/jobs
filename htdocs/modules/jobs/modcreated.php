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

if (!empty($_POST['submit'])) {

    if (!$xoopsGTicket->check(TRUE, 'token')) {
        redirect_header(XOOPS_URL . "/modules/$mydirname/index.php", 3, $xoopsGTicket->getErrors());
    }
    if ($xoopsModuleConfig['jobs_resume_options'] == 'dhtmltextarea'
        || $xoopsModuleConfig['jobs_form_options'] == 'dhtml'
    ) {
        $made_resume = $myts->displayTarea($_POST['made_resume'], 0, 0, 0, 0, 0);
    } else {
        $made_resume = $myts->displayTarea($_POST['made_resume'], 1, 1, 1, 1, 1);
    }

//    $lid=$_POST['lid'];
    $lid = !isset($_REQUEST['lid']) ? NULL : $_REQUEST['lid'];

    $xoopsDB->query(
        "update " . $xoopsDB->prefix("jobs_created_resumes") . " set made_resume='$made_resume' where lid="
            . mysql_real_escape_string($lid) . ""
    );

    redirect_header("myresume.php?lid=$lid", 3, _JOBS_RES_MOD);
    //redirect_header("myresume.php", 3, _JOBS_RES_MOD);
    exit();

} else {

    include(XOOPS_ROOT_PATH . "/header.php");
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    $lid = !isset($_REQUEST['lid']) ? NULL : $_REQUEST['lid'];

    $result = $xoopsDB->query(
        "SELECT res_lid, lid, made_resume, date, usid from " . $xoopsDB->prefix("jobs_created_resumes") . " WHERE lid="
            . mysql_real_escape_string($lid) . ""
    );
    list($res_lid, $lid, $made_resume, $date, $usid) = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $calusern = $xoopsUser->uid();
        if ($usid == $calusern) {

            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _JOBS_EDIT_RESUME . "</legend>";

            if ($xoopsModuleConfig['jobs_resume_options'] == 'dhtmltextarea'
                || $xoopsModuleConfig['jobs_form_options'] == 'dhtml'
            ) {
                $made_resume = $myts->undoHtmlSpecialChars($myts->displayTarea($made_resume, 0, 0, 0, 0, 0));
            } else {
                $made_resume = $myts->displayTarea($made_resume, 1, 1, 1, 1, 1);
            }

            $dates = formatTimestamp($date, "s");

            echo "<form action=\"modcreated.php\" method=post enctype=\"multipart/form-data\">
        <table class=\"outer\"><tr>
        <td class=\"odd\" width=\"35%\">" . _JOBS_NUMANNN . " </td><td class=\"odd\">$lid " . _JOBS_DU . " $dates</td>
        </tr><tr>
        <td class=\"even\" width=\"35%\">" . _JOBS_RESUME . " </td><td class=\"even\">";

            $wysiwyg_text_area = resume_getEditor(_JOBS_RESUME, "made_resume", $made_resume, '100%', '200px', 'small');
            echo $wysiwyg_text_area->render();

            echo "</td></tr><tr>";
            echo "<td colspan=2><br /><br /><input type=\"submit\" value=\"" . _JOBS_RES_MODIFANN . "\"></td>
        </tr></table>";
            echo "<input type=\"hidden\" name=\"submit\" value=\"1\" />";
            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />";
            echo "<input type=\"hidden\" name=\"date\" value=\"$date\" />
        " . $GLOBALS['xoopsGTicket']->getTicketHtml(__LINE__, 1800, 'token') . "";
            echo "</form><br />";
            echo "</fieldset><br />";
        }
    }

    include(XOOPS_ROOT_PATH . "/footer.php");
}
