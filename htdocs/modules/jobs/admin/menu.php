<?php

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu              = array();
$i                      = 0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']  = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
//++$i;
//$adminmenu[$i]['title'] = _MI_JOBS_ADMENU2;
//$adminmenu[$i]['link']  = "admin/map.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU6;
$adminmenu[$i]['link']  = "admin/company.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/addlink.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU8;
$adminmenu[$i]['link']  = "admin/jobs.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/cash_stack.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU9;
$adminmenu[$i]['link']  = "admin/resumes.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/identity.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU1;
$adminmenu[$i]['link']  = "admin/main.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU3;
$adminmenu[$i]['link']  = "admin/groupperms.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/permissions.png';
++$i;
$adminmenu[$i]['title'] = _MI_JOBS_ADMENU7;
$adminmenu[$i]['link']  = "admin/region.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/languages.png';
++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
