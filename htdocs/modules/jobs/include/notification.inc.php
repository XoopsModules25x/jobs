<?php
// $Id: notification.inc.php,v 1.1.1.1 2004/08/08 17:32:06 Administrator Exp $
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
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
$mydirname = basename(dirname(dirname(__FILE__)));
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");
function jobs_notify_iteminfo($category, $item_id)
{
    $mydirname = basename(dirname(dirname(__FILE__)));
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->getByDirname("$mydirname");

    $item_id = intval($item_id);

    if ($category == 'global') {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsDB, $mydirname;

    if ($category == 'category') {
        // Assume we have a valid topid id
        $sql = 'SELECT title  FROM ' . $xoopsDB->prefix("" . $mydirname . "_categories") . ' WHERE cid = ' . $item_id
            . ' limit 1';
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header("index.php", 2, _MD_ERRORFORUM);
            exit();
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $mydirname . '/jobscat.php?cid=' . $item_id;

        return $item;
    }
    if ($category == 'job_listing') {
        // Assume we have a valid post id
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix("" . $mydirname . "_listing") . ' WHERE lid = ' . $item_id
            . ' LIMIT 1';
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header("index.php", 2, _MD_ERROROCCURED);
            exit();
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . "$mydirname" . '/viewjobs.php?lid= ' . $item_id;

        return $item;
    }
    if ($category == 'company_listing') {

        $company_name = jobs_getCompNameFromId($item_id);

        // Assume we have a valid post id
//		$sql = 'SELECT company FROM ' . $xoopsDB->prefix("".$mydirname."_listing"). ' WHERE (`company` = '.$company_name.') LIMIT 1';
        if (!$company_name) {
            redirect_header("index.php", 12, _MD_ERROROCCURED);
            exit();
        }
//		$result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $company_name;
        $item['url']  = XOOPS_URL . '/modules/jobs/members.php?comp_id=' . $item_id;

        return $item;
    }

    if ($category == 'res_global') {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    if ($category == 'resume') {

        // Assume we have a valid topid id
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix("jobs_res_categories") . ' WHERE cid = ' . $item_id . ' limit 1';
//echo $sql;
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header("resumes.php", 2, _MD_ERROROCCURED);
            exit();
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $mydirname . '/resumecat.php?cid=' . $item_id;

        return $item;
    }

    if ($category == 'resume_listing') {
        // Assume we have a valid post id
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix("" . $mydirname . "_resume") . ' WHERE lid = ' . $item_id
            . ' LIMIT 1';
        if (!$result = $xoopsDB->query($sql)) {
            redirect_header("resumes.php", 2, _MD_ERROROCCURED);
            exit();
        }
        $result_array = $xoopsDB->fetchArray($result);
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $mydirname . '/viewresume.php?lid= ' . $item_id;

        return $item;
    }
}
