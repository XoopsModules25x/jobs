<?php
//                 Jobs for Xoops 2.3.3b and up  by John Mordo - jlm69 at Xoops              //
//                                                                                           //
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");

include_once XOOPS_ROOT_PATH . "/modules/jobs/admin/header.php";
xoops_cp_header();
//    loadModuleAdminMenu(3, "");

echo "<fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_LISTS . "</legend>";
echo "<br /> " . _AM_JOBS_INSTALL_NOW . "<br /><br />";
echo "<a href=\"include/usstates.php\">" . _AM_JOBS_US_STATES . "</a><br />";
echo "<a href=\"include/canada.php\">" . _AM_JOBS_CANADA_STATES . "</a><br />";
echo "<a href=\"include/france.php\">" . _AM_JOBS_FRANCE . "</a><br />";
echo "<a href=\"include/italy.php\">" . _AM_JOBS_ITALY . "</a><br />";
echo "<a href=\"include/england.php\">" . _AM_JOBS_ENGLAND . "</a><br />";
echo "</fieldset>";

xoops_cp_footer();
