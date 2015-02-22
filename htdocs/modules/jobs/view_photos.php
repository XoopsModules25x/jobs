<?php
// $Id: index.php,v 1.4 2007/08/26 17:32:19 marcellobrandao Exp $
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
$mydirname = basename(dirname(__FILE__));
$main_lang = '_' . strtoupper($mydirname);

/**
 * Xoops header
 */
include_once '../../mainfile.php';
$xoopsOption['template_main'] = 'jobs_view_photos.html';
include_once '../../header.php';

/**
 * Module classes
 */
include 'class/pictures.php';
if (isset($_GET['lid'])) {
    $lid = $_GET['lid'];
} else {

    header("Location: " . XOOPS_URL . "/modules/$mydirname/index.php");
}
/**
 * Is a member looking ?
 */
if (!empty($xoopsUser)) {
    /**
     * If no $_GET['uid'] then redirect to own
     */
    if (isset($_GET['uid'])) {
        $uid = $_GET['uid'];
    } else {

        header("Location: " . XOOPS_URL . "/modules/$mydirname/index.php");
    }

    /**
     * Is the user the owner of the album ?
     */

    $isOwner = ($xoopsUser->getVar('uid') == $_GET['uid']) ? TRUE : FALSE;

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
    if (!$gperm_handler->checkRight("jobs_premium", $perm_itemid, $groups, $module_id)) {
        $permit = "0";
    } else {
        $permit = "1";
    }

    /**
     * If it is an anonym
     */
} else {
    if (isset($_GET['uid'])) {
        $uid = $_GET['uid'];
    } else {
        header("Location: " . XOOPS_URL . "/modules/$mydirname/index.php");
        $isOwner = FALSE;
    }
}

/**
 * Filter for search pictures in database
 */
$criteria_lid = new criteria('lid', $lid);
$criteria_uid = new criteria('uid', $uid);
/**
 * Creating a factory of pictures
 */
$album_factory = new Xoopsjlm_picturesHandler($xoopsDB);

/**
 * Fetch pictures from the factory
 */
$pictures_object_array = $album_factory->getObjects($criteria_lid, $criteria_uid);

/**
 * How many pictures are on the user album
 */
$pictures_number = $album_factory->getCount($criteria_lid, $criteria_uid);

/**
 * If there is no pictures in the album
 */
if ($pictures_number == 0) {
    $nopicturesyet = _JOBS_NOTHINGYET;
    $xoopsTpl->assign('lang_nopicyet', $nopicturesyet);
} else {

    /**
     * Lets populate an array with the data from the pictures
     */
    $i = 0;
    foreach ($pictures_object_array as $picture) {
        $pictures_array[$i]['url']     = $picture->getVar("url", "s");
        $pictures_array[$i]['desc']    = $picture->getVar("title", "s");
        $pictures_array[$i]['cod_img'] = $picture->getVar("cod_img", "s");
        $pictures_array[$i]['lid']     = $picture->getVar("lid", "s");
        $xoopsTpl->assign('pics_array', $pictures_array);

        ++$i;
    }
}

/**
 * Show the form if it is the owner and he can still upload pictures
 */
if (!empty($xoopsUser)) {
    if ($isOwner && $xoopsModuleConfig["jobs_nb_pict"] > $pictures_number) {
        $maxfilebytes = $xoopsModuleConfig["jobs_maxfilesize"];
        $album_factory->renderFormSubmit($uid, $lid, $maxfilebytes, $xoopsTpl);
    }
}

/**
 * Let's get the user name of the owner of the album
 */
$owner      = new XoopsUser();
$identifier = $owner->getUnameFromId($uid);

/**
 * Adding to the module js and css of the lightbox and new ones
 */

if ($xoopsModuleConfig["" . $mydirname . "_lightbox"] == 1) {

    $header_lightbox
        = '<script type="text/javascript" src="lightbox/js/prototype.js"></script>
<script type="text/javascript" src="lightbox/js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="lightbox/js/lightbox.js"></script>
<link rel="stylesheet" href="include/yogurt.css" type="text/css" media="screen" />
<link rel="stylesheet" href="lightbox/css/lightbox.css" type="text/css" media="screen" />';

} else {

    $header_lightbox = '<link rel="stylesheet" href="include/yogurt.css" type="text/css" media="screen" />';
}

/**
 * Assigning smarty variables
 */


$sql    = "SELECT name FROM " . $xoopsDB->prefix("jobs_resume") . " where lid=" . addslashes($lid) . "";
$result = $xoopsDB->query($sql);
while (list($name) = $xoopsDB->fetchRow($result)) {
    $xoopsTpl->assign('lang_gtitle', "<a href='viewresume.php?lid=" . addslashes($lid) . "'>" . $name . "</a>");
    $xoopsTpl->assign('lang_showcase', _JOBS_SHOWCASE);
}

$xoopsTpl->assign('lang_not_premium', sprintf(_JOBS_BMCANHAVE, $xoopsModuleConfig["jobs_not_premium"]));

$xoopsTpl->assign('lang_no_prem_nb', sprintf(_JOBS_PREMYOUHAVE, $pictures_number));

$upgrade = "<a href=\"premium.php\"><b> " . _JOBS_UPGRADE_NOW . "</b></a>";
$xoopsTpl->assign('lang_upgrade_now', $upgrade);
$xoopsTpl->assign('lang_max_nb_pict', sprintf(_JOBS_YOUCANHAVE, $xoopsModuleConfig["jobs_nb_pict"]));
$xoopsTpl->assign('lang_nb_pict', sprintf(_JOBS_YOUHAVE, $pictures_number));
$xoopsTpl->assign(
    'lang_albumtitle', sprintf(
        _JOBS_ALBUMTITLE, "<a href=" . XOOPS_URL . "/userinfo.php?uid=" . addslashes($uid) . ">" . $identifier . "</a>"
    )
);
$xoopsTpl->assign('path_uploads', $xoopsModuleConfig["jobs_link_upload"]);
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . " - " . $identifier . "'s album");
$xoopsTpl->assign('nome_modulo', $xoopsModule->getVar('name'));
$xoopsTpl->assign('lang_delete', _JOBS_DELETE);
$xoopsTpl->assign('lang_editdesc', _JOBS_EDITDESC);
$xoopsTpl->assign('isOwner', $isOwner);
$xoopsTpl->assign('permit', $permit);
$xoopsTpl->assign('xoops_module_header', $header_lightbox);

/**
 * Check if using Xoops or XoopsCube (by jlm69)
 */

$xCube = FALSE;
if (preg_match("/^XOOPS Cube/", XOOPS_VERSION)) { // XOOPS Cube 2.1x
    $xCube = TRUE;
}

/**
 * Verify Ticket (by jlm69)
 * If your site is XoopsCube it uses $xoopsGTicket for the token.
 * If your site is Xoops it uses xoopsSecurity for the token.
 */

if ($xCube) {
    $xoopsTpl->assign('token', $GLOBALS['xoopsGTicket']->getTicketHtml(__LINE__));
} else {
    $xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());
}

/**
 * Closing the page
 */
include '../../footer.php';
