<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 * @version      $Id $
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';
include_once dirname(__FILE__) . '/admin_header.php';
include_once dirname(dirname(__FILE__)) . '/include/directorychecker.php';

xoops_cp_header();

$indexAdmin = new ModuleAdmin();

//-----------------------
/*
* jobs
 * validate
 * published
 * total jobs
 * Categories

* resumes
 * validate
 * published
 * total jobs
 * Categories

 * payment type
 * job type
 *
* Companies
*
* */

$summary = jobs_summary();

$indexAdmin->addInfoBox(_AM_JOBS_SUMMARY);
//$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   "------ JOBS -----------------",  'Red');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   sprintf(_AM_JOBS_WAITVA_JOB,$summary['waitJobValidation']),  'Green');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   sprintf(_AM_JOBS_PUBLISHED, $summary['jobPublished']), 'Red');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   sprintf(_AM_JOBS_CATETOT, $summary['jobCategoryCount']),  'Green');

//$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   "</br>  "."------ RESUMES -----------------",  'Red');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   "</br>  ".sprintf(_AM_JOBS_WAITVA_RESUME,$summary['waitResumeValidation']),  'Green');
//$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY, "<b>"._AM_JOBS_VIEWSCAP  ."</b>  ". sprintf(_AM_JOBS_VIEWS, $summary['views']),  'Green');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   sprintf(_AM_JOBS_RESUME_PUBLISHED, $summary['resumePublished']),  'Green');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   sprintf(_AM_JOBS_RESUME_CAT_TOTAL, $summary['resumeCategoryCount']),  'Green');

//$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,   "</br>  "."------ COMPANIES -----------------",  'Red');
//$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,  "</br>  "."<b>"._AM_JOBS_COMPANY_TOTCAP ."</b>  ". sprintf(_AM_JOBS_WAITVA_RESUME,$summary['waitResumeValidation']),  'Green');
$indexAdmin->addInfoBoxLine(_AM_JOBS_SUMMARY,    "</br>  ".sprintf(_AM_JOBS_COMPANY_TOT, $summary['companies']),  'Green');

$photodir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo";
$photothumbdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo/thumbs";
$photohighdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo/midsize";
$cachedir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/resumes";
$tmpdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/rphoto";

//------ check directories ---------------

$indexAdmin->addConfigBoxLine('');
$redirectFile = $_SERVER['PHP_SELF'];

$languageConstants = array(_AM_JOBS_AVAILABLE,_AM_JOBS_NOTAVAILABLE, _AM_JOBS_CREATETHEDIR, _AM_JOBS_NOTWRITABLE, _AM_JOBS_SETMPERM, _AM_JOBS_DIRCREATED,_AM_JOBS_DIRNOTCREATED,_AM_JOBS_PERMSET,_AM_JOBS_PERMNOTSET);

$path =  $photodir ;
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path,0777,$languageConstants,$redirectFile));

$path = $photothumbdir;
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path,0777,$languageConstants,$redirectFile));

$path = $photohighdir;
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path,0777,$languageConstants,$redirectFile));

$path = $cachedir;
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path,0777,$languageConstants,$redirectFile));

$path = $tmpdir;
$indexAdmin->addConfigBoxLine(DirectoryChecker::getDirectoryStatus($path,0777,$languageConstants,$redirectFile));
//---------------------------

echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();

jobs_filechecks();

include 'admin_footer.php';
