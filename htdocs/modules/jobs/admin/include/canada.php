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
('53', '0', '---CANADA---', 'CA'),
('54', '53', 'Alberta', 'AB'),
('55',' 53', 'British Columbia', 'BC'),
('56', '53', 'Manitoba', 'MB'),
('57', '53', 'New Brunswick', 'NB'),
('58', '53', 'Newfoundland and Labrador', 'NL'),
('59', '53', 'Northwest Territories', 'NT'),
('60', '53', 'Nova Scotia', 'NS'),
('61', '53', 'Nunavut', 'NU'),
('62', '53', 'Ontario', 'ON'),
('63', '53', 'Prince Edward Island', 'PE'),
('64', '53', 'Québec', 'QC'),
('65', '53', 'Saskatchewan', 'SK'),
('66', '53', 'Yukon', 'YT')"
    );

    if (!$xoopsDB->queryF($sql)) {
        $errors = mysql_error();

        redirect_header(
            "../region.php", 2, _AM_JOBS_UPDATEFAILED . "
" . _AM_JOBS_ERROR . "$errors"
        );

        exit();
    } else {
        redirect_header("../region.php", 2, _AM_JOBS_CANADA_ADDED);
        exit();
    }
} else {
    redirect_header("../../index.php", 2, _NO_PERM);
}

xoops_cp_footer();
