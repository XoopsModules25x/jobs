<?php
/**
 * $Id: permissions.php 11821 2013-07-09 22:55:24Z beckmi $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
include_once '../../../include/cp_header.php';
xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation('permissions.php');

//wfdownloads_adminMenu(9, _AM_WFD_PERM_MANAGEMENT);

    echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_PERM_CPERMISSIONS . "</legend>\n
		<div style='padding: 2px;'>\n";

$cat_form = new XoopsGroupPermForm('', $xoopsModule->getVar('mid'), 'jobs_category', _AM_WFD_PERM_CSELECTPERMISSIONS,'admin/permissions.php');

$category_handler = xoops_getmodulehandler('jobs_categories');
$categories = $category_handler->getObjects();
if (count($categories) > 0)
{
    foreach (array_keys($categories) as $i)
    {
        $cat_form->addItem($categories[$i]->getVar('cid'), $categories[$i]->getVar('title'), $categories[$i]->getVar('pid'));
    }
    echo $cat_form->render();
}
else
{
    echo "<div><b>" . _AM_WFD_PERM_CNOCATEGORY . "</b></div>";
}
echo "</div></fieldset><br />";
unset ($cat_form);

echo _AM_WFD_PERM_PERMSNOTE;

//xoops_cp_footer();
include_once "admin_footer.php";
