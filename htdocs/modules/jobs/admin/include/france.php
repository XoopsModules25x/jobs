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
('67', '0', '---FRANCE---', 'FR'),
('68', '67', 'Alsace', 'Alsace'),
('69', '67', 'Aquitaine', 'Aquitaine'),
('70', '67', 'Auvergne', 'Auvergne'),
('71', '67', 'Bretagne', 'Bretagne'),
('72', '67', 'Bourgogne', 'Bourgogne'),
('73', '67', 'Centre', 'Centre'),
('74', '67', 'Champagne-Ardenne', 'Champagne-Ardenne'),
('75', '67', 'Corse', 'Corse'),
('76', '67', 'Franche-Comté', 'Franche-Comté'),
('77', '67', 'Languedoc-Roussillon', 'Languedoc-Roussillon'),
('78', '67', 'Limousin', 'Limousin'),
('79', '67', 'Lorraine', 'Lorraine'),
('80', '67', 'Basse-Normandie', 'Basse-Normandie'),
('81', '67', 'Midi-Pyrénées', 'Midi-Pyrénées'),
('82', '67', 'Nord-Pas-de-Calais', 'Nord-Pas-de-Calais'),
('83', '67', 'Île-de-France', 'Île-de-France'),
('84', '67', 'Pays-de-la-Loire', 'Pays-de-la-Loire'),
('85', '67', 'Picardie', 'Picardie'),
('86', '67', 'Poitou-Charentes', 'Poitou-Charentes'),
('87', '67', 'Provence-Alpes-Côte d\'Azur', 'Provence-Alpes-Côte d\'Azur'),
('88', '67', 'Rhône-Alpes', 'Rhône-Alpes'),
('89', '67', 'Haute-Normandie', 'Haute-Normandie')"
    );

    if (!$xoopsDB->queryF($sql)) {
        $errors = mysql_error();

        redirect_header(
            "../region.php", 3, _AM_JOBS_UPDATEFAILED . "
" . _AM_JOBS_ERROR . "$errors"
        );

        exit();
    } else {
        redirect_header("../region.php", 3, _AM_JOBS_FRANCE_ADDED);
        exit();
    }
} else {
    redirect_header("../../index.php", 3, _NO_PERM);
}

xoops_cp_footer();
