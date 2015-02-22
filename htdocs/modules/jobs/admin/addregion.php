<?php
//                 Jobs for Xoops 2.3.3b and up  by John Mordo - jlm69 at Xoops              //
//                                                                                           //
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$myts =& MyTextSanitizer::getInstance();

if (!empty($_POST['submit'])) {

    $name   = $myts->addSlashes($_POST['name']);
    $pid    = $myts->addSlashes($_POST['pid']);
    $abbrev = $myts->addSlashes($_POST['abbrev']);

    $newid = $xoopsDB->genId($xoopsDB->prefix("jobs_region") . "_rid_seq");

    $sql = sprintf(
        "INSERT INTO " . $xoopsDB->prefix("jobs_region")
            . " (rid, pid, name, abbrev) VALUES ('$newid', '$pid', '$name', '$abbrev')"
    );
    $xoopsDB->query($sql);

    redirect_header("region.php", 4, _AM_JOBS_REGION_ADDED);
    exit();

} else {

    include 'admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(4, "");
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("region.php");
    
    
    

    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    ob_start();
    $form = new XoopsThemeForm(_AM_JOBS_ADD_REGION, 'regionform', 'addregion.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new XoopsFormText(_AM_JOBS_REGION_NAME, "name", 20, 50, ""), TRUE);
    $form->addElement(new XoopsFormText(_AM_JOBS_REGION_ABBREV, "abbrev", 2, 4, ""), FALSE);
    $form->addElement(new XoopsFormButton('', 'submit', _AM_JOBS_ADDREGION, 'submit'));
    $form->addElement(new XoopsFormHidden('pid', "0"));
    $form->display();
    $submit_form = ob_get_contents();
    ob_end_clean();
    echo $submit_form;
}

xoops_cp_footer();
