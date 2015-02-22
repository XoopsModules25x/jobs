<?php
//  -----------------------------------------------------------------------  //
//                           Jobs Module for Xoops 2.4.x                     //
//                         By John Mordo - jlm69 at Xoops                    //
//                                                                           //
//  -----------------------------------------------------------------------  //
include 'header.php';

$mydirname = basename(dirname(__FILE__));
include(XOOPS_ROOT_PATH . "/modules/$mydirname/include/resume_functions.php");
$myts                         =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$xoopsOption['template_main'] = 'jobs_view_created.html';
include XOOPS_ROOT_PATH . "/header.php";

$id  = isset($_GET['lid']) ? $_GET['lid'] : '';
$lid = trim($id);

$xoopsTpl->assign('id', $lid);
$xoopsTpl->assign('nav_main', "<a href=\"resumes.php\">" . _JOBS_MAIN . "</a>");
$xoopsTpl->assign('mydirname', $mydirname);
$xoopsTpl->assign('no_listing', _JOBS_NO_LISTING);

$sql
        =
    "SELECT r.lid, r.cid, r.name, r.title, r.exp, r.expire, r.private, r.tel, r.salary, r.typeprice, r.date, r.email, r.submitter, r.usid, r.town, r.state, r.valid, r.resume, r.view, c.res_lid, c.lid, c.made_resume, c.date, c.usid FROM "
        . $xoopsDB->prefix("jobs_resume") . " r LEFT JOIN " . $xoopsDB->prefix("jobs_created_resumes")
        . " c ON c.lid = r.lid WHERE r.lid = " . mysql_real_escape_string($lid) . "";
$result = $xoopsDB->query($sql);

$xoopsTpl->assign('listing_exists', $result);
$xoopsTpl->assign('add_from_title', _JOBS_RESUME_TITLE);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('ad_exists', $result);

list($lid, $cid, $name, $title, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $resume, $hits, $res_lid, $rlid, $made_resume, $rdate, $rusid) = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    $member_usid = $xoopsUser->getVar("uid", "E");
    if ($usid == $member_usid) {
        $istheirs = 1;
        $xoopsTpl->assign(
            'modify_link', "<a href=\"modcreated.php?lid=" . addslashes($lid) . "\">" . _JOBS_EDIT_RESUME . "</a>"
        );
    } else {
        $istheirs = '';
        $xoopsTpl->assign('modify_link', "");
    }
}

$date2   = $date + ($expire * 86400);
$date    = formatTimestamp($date, "s");
$date2   = formatTimestamp($date2, "s");
$rdate   = formatTimestamp($rdate, "s");
$mresume = $myts->displayTarea($made_resume, 1, 0, 1, 1, 1);
$xoopsTpl->assign('id', $lid);
$xoopsTpl->assign('cid', $cid);
$xoopsTpl->assign('name', $name);
$xoopsTpl->assign('title', $title);
$xoopsTpl->assign('exp', $exp);
$xoopsTpl->assign('expire', $expire);
$xoopsTpl->assign('private', $private);
$xoopsTpl->assign('resume', $resume);
$xoopsTpl->assign('made_resume', $mresume);

include XOOPS_ROOT_PATH . '/footer.php';
