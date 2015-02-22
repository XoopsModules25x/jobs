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
('90', '0', '---Italy---', 'IT'),
('91', '90', 'Abruzzo', 'Abruzzo'),
('92', '90', 'Lombardy', 'Lombardy'),
('93', '90', 'Amalfi Coast', 'Amalfi Coast'),
('94', '90', 'Marche', 'Marche'),
('95', '90', 'Aosta', 'Aosta'),
('96', '90', 'Molise', 'Molise'),
('97', '90', 'Basilicata', 'Basilicata'),
('98', '90', 'Piemonte', 'Piemonte'),
('99', '90', 'Calabria', 'Calabria'),
('100', '90', 'Puglia - Apulia', 'Puglia - Apulia'),
('101', '90', 'Campania', 'Campania'),
('102', '90', 'Trentino - Alto Adige', 'Trentino - Alto Adige'),
('103', '90', 'Emilia Romagna', 'Emilia Romagna'),
('104', '90', 'Tuscany', 'Tuscany'),
('105', '90', 'Umbria', 'Umbria'),
('106', '90', 'Lazio', 'Lazio'),
('107', '90', 'Veneto', 'Veneto'),
('108', '90', 'Liguria', 'Liguria')"
    );

    if (!$xoopsDB->queryF($sql)) {
        $errors = mysql_error();

        redirect_header(
            "../region.php", 3, _AM_JOBS_UPDATEFAILED . "
" . _AM_JOBS_ERROR . "$errors"
        );

        exit();
    } else {
        redirect_header("../region.php", 3, _AM_JOBS_ITALY_ADDED);
        exit();
    }
} else {
    redirect_header("../../index.php", 3, _NO_PERM);
}

xoops_cp_footer();
