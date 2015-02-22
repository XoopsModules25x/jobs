<?php
// $Id: modcompany.php,v 4.2 2010/03/14 09:12:57 jlm69 Exp $
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
if (!$gperm_handler->checkRight("jobs_premium", $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

include_once XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php";

$comp_id     = !isset($_REQUEST['comp_id']) ? NULL : $_REQUEST['comp_id'];
$member_usid = $xoopsUser->uid();

if ($xoopsUser) {

    $xoopsOption['template_main'] = 'jobs_choose_company.html';
    include XOOPS_ROOT_PATH . "/header.php";

    $result = $xoopsDB->query(
        "select comp_id, comp_name FROM " . $xoopsDB->prefix("jobs_companies") . " WHERE " . $member_usid
            . " IN (comp_usid, comp_user1, comp_user2)"
    );
    while ($myrow = $xoopsDB->fetchArray($result)) {

        $a_comp   = array();
        $istheirs = TRUE;
        $xoopsTpl->assign('istheirs', $istheirs);
        $xoopsTpl->assign('comp_listurl', "addlisting.php?comp_id=");
        $a_comp['comp_id']   = $myrow['comp_id'];
        $a_comp['comp_name'] = $myrow['comp_name'];
        $xoopsTpl->append('companies', $a_comp);
        $xoopsTpl->assign('choose_company', _JOBS_MUST_CHOOSE);
    }
}
include(XOOPS_ROOT_PATH . "/footer.php");
