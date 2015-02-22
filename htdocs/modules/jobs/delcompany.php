<?php
// $Id: editcomp.php,v 3.0 2010/02/06 09:12:57 jlm69 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include 'header.php';

$mydirname = basename(dirname(__FILE__));

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
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php";

$comp_id = (intval($_GET['comp_id']) > 0) ? intval($_GET['comp_id']) : 0;

include(XOOPS_ROOT_PATH . "/header.php");

$result = $xoopsDB->query(
    "select comp_name, comp_usid, comp_img FROM " . $xoopsDB->prefix("jobs_companies") . " where comp_id="
        . mysql_real_escape_string($comp_id) . ""
);
list($comp_name, $comp_usid, $photo) = $xoopsDB->fetchRow($result);

$result1 = $xoopsDB->query(
    "select company FROM " . $xoopsDB->prefix("jobs_listing") . " where usid=" . mysql_real_escape_string($comp_usid)
        . ""
);
list($my_company) = $xoopsDB->fetchRow($result1);

if ($xoopsUser) {
    $ok = !isset($_REQUEST['ok']) ? NULL : $_REQUEST['ok'];

    $member_usid = $xoopsUser->getVar("uid", "E");
    if ($comp_usid == $member_usid) {
        if ($ok == 1) {
// Delete Company
            $xoopsDB->queryf(
                "delete from " . $xoopsDB->prefix("jobs_companies") . " where comp_id="
                    . mysql_real_escape_string($comp_id) . ""
            );

// Delete all listing by Company
            if ($comp_name == $my_company) {
                $xoopsDB->queryf(
                    "delete from " . $xoopsDB->prefix("jobs_listing") . " where usid="
                        . mysql_real_escape_string($comp_usid) . ""
                );
            }
// Delete Company logo
            if ($photo) {
                $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/logo_images";
                if (file_exists("$destination/$photo")) {
                    unlink("$destination/$photo");
                }
            }
            redirect_header("index.php", 3, _JOBS_COMPANY_DEL);
            exit();
        } else {
            echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
            echo "<br /><center>";
            echo "<b>" . _JOBS_SURDELCOMP . "</b><br /><br />";
        }
        echo"[ <a href=\"delcompany.php?comp_id=" . addslashes($comp_id) . "&amp;ok=1\">" . _JOBS_OUI
            . "</a> | <a href=\"members.php?comp_id=" . addslashes($comp_id) . "\">" . _JOBS_NON . "</a> ]<br /><br />";
        echo "</td></tr></table>";
    }
}

include(XOOPS_ROOT_PATH . "/footer.php");
