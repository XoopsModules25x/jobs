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

include_once '../../../include/cp_header.php';
xoops_cp_header();
$mydirname = $xoopsModule->getVar('dirname');
include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/functions.php';

if (!@include_once(
    XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/language/" . $xoopsConfig['language']
        . "/main.php")
) {
    include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/language/english/main.php");
}

$myts = &MyTextSanitizer::getInstance();

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;
    // 1) Create the resume table IF it does not exist
    if (!JobTableExists($xoopsDB->prefix('jobs_resume'))) {
        $sql1 = "CREATE TABLE " . $xoopsDB->prefix('jobs_resume') . " (
     lid int(11) NOT NULL auto_increment,
     cid int(11) NOT NULL default '0',
     name varchar(100) NOT NULL default '',
     title varchar(100) NOT NULL default '',
     status int(3) NOT NULL default '1',
     exp varchar(100) NOT NULL default '',
     expire char(3) NOT NULL default '',
     private varchar(6) NOT NULL default '',
     tel varchar(20) NOT NULL default '',
     salary varchar(100) NOT NULL default '',
     typeprice varchar(100) NOT NULL default '',
     date int(10)NOT NULL default '0',
     email varchar(100) NOT NULL default '',
     submitter varchar(60) NOT NULL default '',
     usid varchar(6) NOT NULL default '',
     town varchar(100) NOT NULL default '',
     state varchar(100) NOT NULL default '',
     valid varchar(11) NOT NULL default '',
     resume varchar(100) NOT NULL default '',
     view varchar(10) NOT NULL default '0',
     PRIMARY KEY  (lid)
    ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql1)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

    // 2) Create the jobs_res_categories table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_res_categories'))) {
        $sql2 = "CREATE TABLE " . $xoopsDB->prefix('jobs_res_categories') . " (
      cid int(11) NOT NULL auto_increment,
      pid int(5) unsigned NOT NULL default '0',
      title varchar(50) NOT NULL default '',
      img varchar(150) NOT NULL default '',
      ordre int(5) NOT NULL default '0',
      affprice int(5) NOT NULL default '0',
      PRIMARY KEY  (cid)
    ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql2)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

    // 3) Create the jobs_replies table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_replies'))) {
        $sql3 = "CREATE TABLE " . $xoopsDB->prefix('jobs_replies') . " (
      r_lid int(11) NOT NULL auto_increment,
      lid int(5) unsigned NOT NULL default '0',
      title varchar(50) NOT NULL default '',
      date int(10) NOT NULL default '0',
      submitter varchar(60) NOT NULL default '',
      message text NOT NULL default '',
      resume varchar(60) NOT NULL default '',
      tele varchar(20) NOT NULL default '0',
      email varchar(100) NOT NULL default '',
      r_usid int(11) NOT NULL default '0',
      company varchar(100) NOT NULL default '',
      PRIMARY KEY  (r_lid)
    ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql3)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

// 4) Create the jobs_companies table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_companies'))) {
        $sql4 = "CREATE TABLE " . $xoopsDB->prefix('jobs_companies') . " (
    comp_id int(11) NOT NULL auto_increment,
    comp_pid int(5) unsigned NOT NULL default '0',
    comp_name varchar(50) NOT NULL default '',
    comp_address varchar(100) NOT NULL default '',
    comp_address2 varchar(100) NOT NULL default '',
    comp_city varchar(100) NOT NULL default '',
    comp_state varchar(100) NOT NULL default '',
    comp_zip varchar(20) NOT NULL default '',
    comp_phone varchar(50) NOT NULL default '0',
    comp_fax varchar(50) NOT NULL default '',
    comp_url varchar(150) NOT NULL default '',
    comp_img varchar(100) NOT NULL default '',
    comp_usid varchar(6) NOT NULL default '',
    comp_user1 varchar(6) NOT NULL default '',
    comp_user2 varchar(6) NOT NULL default '',
    comp_contact text NOT NULL,
    comp_user1_contact text NOT NULL,
    comp_user2_contact text NOT NULL,
    comp_date_added int(10) NOT NULL default '0',
      PRIMARY KEY  (comp_id),
      KEY comp_name (comp_name)
    ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql4)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

// 5) Create the jobs_created_resumes table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_created_resumes'))) {
        $sql5 = "CREATE TABLE " . $xoopsDB->prefix('jobs_created_resumes') . " (
    res_lid int(11) NOT NULL auto_increment,
    lid int(11) NOT NULL default '0',
    made_resume text NOT NULL,
    date int(10) NOT NULL default '0',
    usid int(11) NOT NULL default '0',
      PRIMARY KEY  (res_lid),
          KEY lid (lid)
    ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql5)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

// 6) Create the jobs_pictures table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_pictures'))) {
        $sql6 = "CREATE TABLE " . $xoopsDB->prefix('jobs_pictures') . " (

    cod_img int(11) NOT NULL auto_increment,
    title varchar(255) NOT NULL,
    date_added int(10) NOT NULL default '0',
    date_modified int(10) NOT NULL default '0',
    lid int(11) NOT NULL default '0',
    uid_owner varchar(50) NOT NULL,
    url text NOT NULL,
      PRIMARY KEY  (cod_img)
    ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql6)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

// 6) Create the jobs_region table if it does NOT exist
    if (!JobTableExists($xoopsDB->prefix('jobs_region'))) {
        $sql7 = "CREATE TABLE " . $xoopsDB->prefix('jobs_region') . " (

      rid int(11) NOT NULL auto_increment,
      pid int(5) unsigned NOT NULL default '0',
      name CHAR(50) NOT NULL,
      abbrev CHAR(2) NOT NULL,
    PRIMARY KEY  (rid)
      ) TYPE=MyISAM;";

        if (!$xoopsDB->queryF($sql7)) {
            echo '<br />' . _AM_JOBS_UPGRADEFAILED . ' ' . _AM_JOBS_UPGRADEFAILED1;
            $errors++;
        }
    }

// 7) Add the status field to the jobs_listing table
    if (!JobFieldExists('status', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("status INT(3) NOT NULL default '1' AFTER `title`", $xoopsDB->prefix('jobs_listing'));
    }

// 8) Add the expire field to the jobs_listing table
    if (!JobFieldExists('expire', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("expire VARCHAR(6) NOT NULL default '14' AFTER `title`", $xoopsDB->prefix('jobs_listing'));
    }

// 8) Add the expire field to the jobs_listing table
    if (!JobFieldExists('contactinfo1', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("contactinfo1 MEDIUMTEXT NOT NULL AFTER `contactinfo`", $xoopsDB->prefix('jobs_listing'));
    }

// 8) Add the expire field to the jobs_listing table
    if (!JobFieldExists('contactinfo2', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("contactinfo2 MEDIUMTEXT NOT NULL AFTER `contactinfo1`", $xoopsDB->prefix('jobs_listing'));
    }

// 9) Add the state field to the jobs_listing table
    if (!JobFieldExists('state', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("state VARCHAR(100) NOT NULL default '' AFTER `town`", $xoopsDB->prefix('jobs_listing'));
    }

// 10) Add the premium field to the jobs_listing table
    if (!JobFieldExists('premium', $xoopsDB->prefix('jobs_listing'))) {
        JobAddField("premium TINYINT(2) NOT NULL default '0' AFTER `valid`", $xoopsDB->prefix('jobs_listing'));
    }

// 11) Add the comp_date_added field to the jobs_companies table
    if (!JobFieldExists('comp_pid', $xoopsDB->prefix('jobs_companies'))) {
        JobAddField("comp_pid INT(5) unsigned NOT NULL default '0' AFTER `comp_id`", $xoopsDB->prefix('jobs_companies'));
    }

// 11) Add the comp_date_added field to the jobs_companies table
    if (!JobFieldExists('comp_date_added', $xoopsDB->prefix('jobs_companies'))) {
        JobAddField("comp_date_added INT(10) NOT NULL default '0' AFTER `comp_user2_contact`", $xoopsDB->prefix('jobs_companies'));
    }
// 12) Add the status field to the jobs_resume table
    if (!JobFieldExists('status', $xoopsDB->prefix('jobs_resume'))) {
        JobAddField("status INT(3) NOT NULL default '0' AFTER `title`", $xoopsDB->prefix('jobs_resume'));
    }
// 13) Add the state field to the jobs_resume table
    if (!JobFieldExists('state', $xoopsDB->prefix('jobs_resume'))) {
        JobAddField("state VARCHAR(100) NOT NULL default '' AFTER `town`", $xoopsDB->prefix('jobs_resume'));
    }
// 14) Add the company field to the jobs_replies table
    if (!JobFieldExists('company', $xoopsDB->prefix('jobs_replies'))) {
        JobAddField("company VARCHAR(100) NOT NULL default '' AFTER `r_usid`", $xoopsDB->prefix('jobs_replies'));
    }
// At the end, if there were errors, show them or redirect user to the module's upgrade page
    if ($errors) {
        echo "<H1>" . _AM_JOBS_UPGRADEFAILED . "</H1>";
        echo "<br />" . _AM_JOBS_UPGRADEFAILED0;
    } else {
        echo"" . _AM_JOBS_UPDATECOMPLETE . " - <a href='" . XOOPS_URL
            . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $mydirname . "'>" . _AM_JOBS_UPDATEMODULE
            . "</a>";
    }
} else {
    printf("<H2>%s</H2>\n", _AM_JOBS_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
