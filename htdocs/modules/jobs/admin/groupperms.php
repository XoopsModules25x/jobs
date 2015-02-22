<?php
// $Id: groupperms.php,v 1.7 2004/07/26 17:51:25 hthouzard Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System            			    //
// Copyright (c) 2000 XOOPS.org                           		    //
// <http://www.xoops.org/>                             			    //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// 									    //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// 									    //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// 									    //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //

$mydirname   = basename(dirname(dirname(__FILE__)));
$cloned_lang = '_MI_' . strtoupper($mydirname);
include_once '../../../include/cp_header.php';
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php");
//include_once XOOPS_ROOT_PATH . "/modules/$mydirname/class/grouppermform.php";
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
// xoops_cp_header();
//include( './mymenu.php' ) ;
/***** grandoc 13/10/08 ******/
include 'admin_header.php';
xoops_cp_header();
$index_admin = new ModuleAdmin();
echo $index_admin->addNavigation("groupperms.php");
//loadModuleAdminMenu(2, "");
/***** end grandoc 13/10/08 ******/

echo "<br /><br />";
global $xoopsDB;
$countresult = $xoopsDB->query("select COUNT(*) FROM " . $xoopsDB->prefix("" . $mydirname . "_categories") . "");
list($cat_row) = $xoopsDB->fetchRow($countresult);
$cat_rows = $cat_row;
if ($cat_rows == "0") {
    echo "" . constant($cloned_lang . "_MUST_ADD_CAT") . "";
} else {

    $permtoset                = isset($_POST['permtoset']) ? intval($_POST['permtoset']) : 1;
    $selected                 = array('', '', '', '', '');
    $selected[$permtoset - 1] = ' selected';
    echo "<form method='post' name='jselperm' action='groupperms.php'><table border=0><tr><td>
<select name='permtoset' onChange='javascript: document.jselperm.submit()'>
<option value='1'" . $selected[0] . ">" . constant($cloned_lang . "_VIEWFORM") . "</option>
<option value='2'" . $selected[1] . ">" . constant($cloned_lang . "_SUBMITFORM") . "</option>
<option value='3'" . $selected[2] . ">" . constant($cloned_lang . "_VIEW_RESUMEFORM") . "</option>
<option value='4'" . $selected[3] . ">" . constant($cloned_lang . "_RESUMEFORM") . "</option>
<option value='5'" . $selected[4] . ">" . constant($cloned_lang . "_PREMIUM") . "</option>
</select></td><td></tr></table></form>";
    $module_id = $xoopsModule->getVar('mid');

    switch ($permtoset) {
    case 1:
        $title_of_form = constant($cloned_lang . "_VIEWFORM");
        $perm_name     = "" . $mydirname . "_view";
        $perm_desc     = constant($cloned_lang . "_VIEWFORM_DESC");
        $anonymous     = TRUE;
        $permform      = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
        $cattree       = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");
        $allcats       =& $cattree->getCategoryList();

        break;
    case 2:
        $title_of_form = constant($cloned_lang . "_SUBMITFORM");
        $perm_name     = "" . $mydirname . "_submit";
        $perm_desc     = constant($cloned_lang . "_SUBMITFORM_DESC");
        $anonymous     = FALSE;
        $permform      = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
        $cattree       = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");
        $allcats       =& $cattree->getCategoryList();

        break;
    case 3:
        $title_of_form = constant($cloned_lang . "_VIEW_RESUMEFORM");
        $perm_name     = "resume_view";
        $perm_desc     = constant($cloned_lang . "_VIEW_RESUMEFORM_DESC");
        $anonymous     = TRUE;
        $permform      = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
        $cattree       = new JobTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");
        $allcats       =& $cattree->getCategoryList();

        break;
    case 4:
        $title_of_form = constant($cloned_lang . "_RESUMEFORM");
        $perm_name     = "resume_submit";
        $perm_desc     = constant($cloned_lang . "_RESUMEFORM_DESC");
        $anonymous     = FALSE;
        $permform      = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
        $cattree       = new JobTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");
        $allcats       =& $cattree->getCategoryList();

        break;
    case 5:
        $title_of_form = constant($cloned_lang . "_PREMIUM");
        $perm_name     = "" . $mydirname . "_premium";
        $perm_desc     = constant($cloned_lang . "_PREMIUM_DESC");
        $anonymous     = FALSE;
        $permform      = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php', $anonymous);
        $cattree       = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");
        $allcats       =& $cattree->getCategoryList();

        break;
    }

    foreach ($allcats as $cid => $category) {
        $permform->addItem($cid, $category['title'], $category['pid']);
    }
    echo $permform->render();
    echo "<br /><br /><br /><br />\n";
    unset ($permform);
}

xoops_cp_footer();
