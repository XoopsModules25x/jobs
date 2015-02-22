<?php
// $Id$ update jobs to version 4.1
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

include_once '../../../../include/cp_header.php';
xoops_cp_header();
$mydirname = $xoopsModule->getVar('dirname');
include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;

    $sql = sprintf(
        "INSERT INTO " . $xoopsDB->prefix("jobs_region") . " (rid, pid, name, abbrev) VALUES
('109', '0', '---England---', 'ENG'),
('110', '109', 'East Midlands', ''),
('111', '109', 'East of England', ''),
('112', '109', 'Greater London', ''),
('113', '109', 'North East England', ''),
('114', '109', 'North West England', ''),
('115', '109', 'South East England', ''),
('116', '109', 'South West England', ''),
('117', '109', 'West Midlands', ''),
('118', '109', 'Yorkshire and the Humber', '')"
    );

    if (!$xoopsDB->queryF($sql)) {
        $errors = mysql_error();

        redirect_header(
            "../region.php", 3, _AM_JOBS_UPDATEFAILED . "
" . _AM_JOBS_ERROR . "$errors"
        );

        exit();
    } else {
        redirect_header("../region.php", 3, _AM_JOBS_ENGLAND_ADDED);
        exit();
    }
} else {
    redirect_header("../../index.php", 3, _NO_PERM);
}

xoops_cp_footer();
