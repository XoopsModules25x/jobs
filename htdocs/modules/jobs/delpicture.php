<?php
// $Id: delpicture.php,v 1.3 2007/08/26 14:43:50 marcellobrandao Exp $
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
$mydirname = basename(dirname(dirname(__FILE__)));
$main_lang = '_' . strtoupper($mydirname);

/**
 * Xoops Header
 */
include_once '../../mainfile.php';
$xoopsOption['template_main'] = 'jobs_index2.html';
include_once '../../header.php';
include_once '../../class/criteria.php';

/**
 * Module classes
 */

include 'class/pictures.php';

/**
 * Check if using Xoops or XoopsCube (by jlm69)
 * Right now Xoops does not have a directory called preload, Xoops Cube does.
 * If this finds a directory called preload in the Xoops Root folder $xCube=true.
 * This will need to change if Xoops adds a Directory called preload.
 */

$xCube = FALSE;
if (preg_match("/^XOOPS Cube/", XOOPS_VERSION)) { // XOOPS Cube 2.1x
    $xCube = TRUE;
}

/**
 * Verify Ticket for Xoops Cube (by jlm69)
 * If your site is XoopsCube it uses $xoopsGTicket for the token.

 */

if ($xCube) {

    if (!$xoopsGTicket->check(TRUE, 'token')) {
        redirect_header($_SERVER['HTTP_REFERER'], 3, $xoopsGTicket->getErrors());
    }
} else {
    /**
     * Verify TOKEN for Xoops
     * If your site is Xoops it uses xoopsSecurity for the token.
     */
    if (!($GLOBALS['xoopsSecurity']->check())) {
        redirect_header($_SERVER['HTTP_REFERER'], 3, constant($main_lang . "_TOKENEXPIRED"));
    }
}

/**
 * Receiving info from get parameters
 */
$cod_img = $_POST['cod_img'];

/**
 * Creating the factory  and the criteria to delete the picture
 * The user must be the owner
 */
$album_factory = new Xoopsjlm_picturesHandler($xoopsDB);
$criteria_img  = new Criteria ('cod_img', $cod_img);
$uid           = $xoopsUser->getVar('uid');
$criteria_uid  = new Criteria ('uid_owner', $uid);
$criteria_lid  = new Criteria ('lid', $lid);
$criteria      = new CriteriaCompo ($criteria_img);
$criteria->add($criteria_uid);

/**
 * Try to delete
 */
if ($album_factory->deleteAll($criteria)) {

    $lid = $_POST['lid'];
    $xoopsDB->queryF(
        "UPDATE " . $xoopsDB->prefix("" . $mydirname . "_resume") . " SET rphoto=rphoto-1 WHERE lid='$lid'"
    );
    redirect_header("view_photos.php?lid=" . $lid . "&uid=" . $uid . "", 3, constant($main_lang . "_DELETED"));
} else {
    redirect_header("view_photos.php?lid=" . $lid . "&uid=" . $uid . "", 3, constant($main_lang . "_NOCACHACA"));
}

/**
 * Close page
 */
include '../../footer.php';
