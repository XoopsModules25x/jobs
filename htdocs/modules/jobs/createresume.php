<?php
// ---------------------     Jobs Module for Xoops 2.4.x   ---------------------  //
//                         By John Mordo - jlm69 at Xoops                         //                                                                   //                                                                                //
//                                                                                //
//                                                                                //
//                                                                                //
// -----------------------------------------------------------------------------  //

$mydirname = basename(dirname(__FILE__));
include 'header.php';
require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php";
//include_once XOOPS_ROOT_PATH . "/class/module.errorhandler.php";
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/resume_functions.php");
//$erh    = new ErrorHandler;
$mytree = new JobTree($xoopsDB->prefix("xdir_cat"), "cid", "pid");

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
if (!$gperm_handler->checkRight("jobs_submit", $perm_itemid, $groups, $module_id)) {
//    redirect_header(XOOPS_URL."/user.php", 3, _NOPERM);
    redirect_header(XOOPS_URL . "/modules/jobs/resumes.php", 3, _NOPERM);
    exit();
}
if (!$gperm_handler->checkRight("jobs_premium", $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

if (!empty($_POST['lid'])) {
    $lid = intval($_POST['lid']);
} else {
    $lid = isset($_GET['lid']) ? intval($_GET['lid']) : 0;
}

if (!empty($_POST['submit'])) {

    if (!$xoopsGTicket->check(TRUE, 'token')) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

// Check if Title exist

//    if ($_POST["resume"] == "") {
//        $erh->show("1001");
//    }

    $notify      = !empty($_POST['notify']) ? 1 : 0;
    $member_usid = $xoopsUser->getVar("uid", "E");

    if ($xoopsModuleConfig['jobs_resume_options'] == 'dhtmltextarea'
        || $xoopsModuleConfig['jobs_form_options'] == 'dhtml'
    ) {
        $made_resume = $myts->displayTarea($_POST["resume"], 0, 0, 0, 0, 0);
    } else {
        $made_resume = $myts->displayTarea($_POST["resume"], 1, 0, 1, 1, 1);
    }
    $date = time();

    $newid = $xoopsDB->genId($xoopsDB->prefix("jobs_created_resumes") . "_res_lid_seq");

    $sql = sprintf("INSERT INTO %s (res_lid, lid, made_resume, date, usid) VALUES (%u, '%s', '%s', '%s', '%s')", $xoopsDB->prefix("jobs_created_resumes"), $newid, $lid, $made_resume, $date, $member_usid);
    $xoopsDB->query($sql);

    $sql2 = "UPDATE " . $xoopsDB->prefix("jobs_resume") . " SET resume='created' where lid=" . $_POST['lid'] . "";
    $xoopsDB->query($sql2);

    redirect_header("viewresume.php?lid=" . addslashes($lid) . "", 3, _JOBS_RES_ADDED);
    exit();
} else {
    $xoopsOption['template_main'] = 'jobs_create_resume.html';
    include XOOPS_ROOT_PATH . "/header.php";
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php");
    $mytree = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");

    $member_usid  = $xoopsUser->getVar("uid", "E");
    $member_email = $xoopsUser->getVar("email", "E");
    $member_uname = $xoopsUser->getVar("uname", "E");

    ob_start();
    $form = new XoopsThemeForm(_JOBS_CREATE_RESUME, 'createform', 'createresume.php');
    $form->setExtra('enctype="multipart/form-data"');

    $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');
    $form->addElement(resume_getEditor(_JOBS_RESUME, "resume", "", 5, 40), TRUE);
    $form->addElement(new XoopsFormHidden('lid', $lid));
    $form->addElement(new XoopsFormButton('', 'submit', _JOBS_SUBMIT, 'submit'));
    $form->display();
    $xoopsTpl->assign('submit_form', ob_get_contents());
    ob_end_clean();

    include XOOPS_ROOT_PATH . '/footer.php';
}
