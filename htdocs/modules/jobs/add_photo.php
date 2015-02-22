<?php
// $Id: submit.php,v 1.3 2007/08/26 14:43:50 marcellobrandao Exp $
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
$lid       = !isset($_REQUEST['lid']) ? NULL : $_REQUEST['lid'];

/**
 * Xoops header ...
 */
include_once '../../mainfile.php';
$xoopsOption['template_main'] = "jobs_view_photos.html";
include_once '../../header.php';

/**
 * Modules class includes
 */
include 'class/pictures.php';

/**
 * Factory of pictures created
 */
$album_factory = new Xoopsjlm_picturesHandler($xoopsDB);

/**
 * Getting the title
 */
$title = $_POST['caption'];

/**
 * Getting parameters defined in admin side
 */

$path_upload   = $xoopsModuleConfig["jobs_path_upload"];
$pictwidth     = $xoopsModuleConfig["jobs_resized_width"];
$pictheight    = $xoopsModuleConfig["jobs_resized_height"];
$thumbwidth    = $xoopsModuleConfig["jobs_thumb_width"];
$thumbheight   = $xoopsModuleConfig["jobs_thumb_height"];
$maxfilebytes  = $xoopsModuleConfig["jobs_maxfilesize"];
$maxfileheight = $xoopsModuleConfig["jobs_max_original_height"];
$maxfilewidth  = $xoopsModuleConfig["jobs_max_original_width"];

/**
 * If we are receiving a file
 */
if ($_POST['xoops_upload_file'][0] == 'sel_photo') {

    /**
     * Check if using Xoops or XoopsCube (by jlm69)
     */

    $xCube = FALSE;
    if (preg_match("/^XOOPS Cube/", XOOPS_VERSION)) { // XOOPS Cube 2.1x
        $xCube = TRUE;
    }
    if ($xCube) {
        if (!$xoopsGTicket->check(TRUE, 'token')) {
            redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
        }
    } else {
        if (!($GLOBALS['xoopsSecurity']->check())) {
            redirect_header($_SERVER['HTTP_REFERER'], 3, constant($main_lang . "_TOKENEXPIRED"));
        }
    }
    /**
     * Try to upload picture resize it insert in database and then redirect to index
     */
    if ($album_factory->receivePicture($title, $path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes, $maxfilewidth, $maxfileheight)) {
        header(
            "Location: " . XOOPS_URL . "/modules/$mydirname/view_photos.php?lid=$lid&uid=" . $xoopsUser->getVar('uid')
        );

        $xoopsDB->queryF(
            "UPDATE " . $xoopsDB->prefix("jobs_resume") . " SET rphoto=rphoto+1 WHERE lid = "
                . mysql_real_escape_string($lid) . ""
        );

    } else {
        redirect_header(
            XOOPS_URL . "/modules/$mydirname/view_photos.php?uid=" . $xoopsUser->getVar('uid'), 3, constant(
                $main_lang . "_NOCACHACA"
            )
        );
    }
}

/**
 * Close page
 */
include '../../footer.php';
