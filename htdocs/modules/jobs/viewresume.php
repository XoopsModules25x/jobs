<?php
//  -----------------------------------------------------------------------  //
//                           Jobs Module for Xoops 2.4.x                     //
//                         By John Mordo - jlm69 at Xoops                    //
//                                                                           //
// ------------------------------------------------------------------------- //
include 'header.php';

$mydirname = basename(dirname(__FILE__));

$myts      =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$gperm_handler =& xoops_gethandler('groupperm');
if (isset($_POST['item_id'])) {
    $perm_itemid = intval($_POST['item_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight("resume_view", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
    exit();
}

include(XOOPS_ROOT_PATH . "/modules/$mydirname/include/resume_functions.php");
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php");
$mytree = new JobTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");

ExpireResume();

$GLOBALS['xoopsOption']['template_main'] = "jobs_resume.html";
include(XOOPS_ROOT_PATH . "/header.php");

if (isset($_POST['unlock'])) {
    $unlock = trim($_POST['unlock']);
} elseif (isset($_GET['unlock'])) {
    $unlock = trim($_GET['unlock']);
} else {
    $unlock = "";
}
$xoopsTpl->assign('unlocked', $unlock);

$lid = intval($_GET['lid']);
$xoopsTpl->assign('id', $lid);

$result      = $xoopsDB->query(
    "select r.lid, r.cid, r.name, r.title, r.exp, r.expire, r.private, r.tel, r.salary, r.typeprice, r.date, r.email, r.submitter, r.usid, r.town, r.state, r.valid, r.resume, r.rphoto, r.view, c.res_lid, c.lid, c.made_resume, c.date, c.usid, p.cod_img, p.lid, p.uid_owner, p.url FROM "
        . $xoopsDB->prefix("jobs_resume") . " r LEFT JOIN " . $xoopsDB->prefix("jobs_created_resumes")
        . " c ON c.lid = r.lid  LEFT JOIN " . $xoopsDB->prefix("jobs_pictures") . " p ON r.lid = p.lid WHERE r.lid = "
        . mysql_real_escape_string($lid) . ""
);
$recordexist = $xoopsDB->getRowsNum($result);

$updir = $xoopsModuleConfig["jobs_link_upload"];
$xoopsTpl->assign('add_from', _JOBS_RES_ADDFROM . " " . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _JOBS_RESUME_TITLE);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);

$xoopsTpl->assign('ad_exists', $recordexist);
$count = 0;
$x     = 0;
$i     = 0;

$result1 = $xoopsDB->query(
    "select cid from " . $xoopsDB->prefix("jobs_resume") . " where  lid=" . mysql_real_escape_string($lid) . ""
);
list($cid) = $xoopsDB->fetchRow($result1);

$result2 = $xoopsDB->query(
    "select cid, pid, title from " . $xoopsDB->prefix("jobs_res_categories") . " where  cid="
        . mysql_real_escape_string($cid) . ""
);
list($ccid, $pid, $title) = $xoopsDB->fetchRow($result2);

$title      = $myts->htmlSpecialChars($title);
$varid[$x]  = $ccid;
$varnom[$x] = $title;

list($res) = $xoopsDB->fetchRow(
    $xoopsDB->query(
        "select COUNT(*) FROM " . $xoopsDB->prefix("jobs_resume") . " where valid='1' AND cid="
            . mysql_real_escape_string($cid) . ""
    )
);

if ($pid != 0) {
    $x = 1;
    while ($pid != 0) {
        $result3 = $xoopsDB->query(
            "select cid, pid, title from " . $xoopsDB->prefix("jobs_res_categories") . " where cid=" . addslashes($pid)
                . ""
        );
        list($ccid, $pid, $title) = $xoopsDB->fetchRow($result3);

        $title      = $myts->htmlSpecialChars($title);
        $varid[$x]  = $ccid;
        $varnom[$x] = $title;
        $x++;
    }
    $x = $x - 1;
}

$subcats   = '';
$arrow     = "&nbsp;<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/arrow.gif\" alt=\"&raquo;\" />";
$backarrow = "&nbsp;<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/backarrow.gif\" alt=\"&laquo;\" />";
while ($x != -1) {
    $subcats .= " $arrow <a href=\"resumecat.php?cid=" . $varid[$x] . "\">" . $varnom[$x] . "</a>";
    $x = $x - 1;
}
$xoopsTpl->assign('nav_jobs', "<a href=\"index.php\">" . _JOBS_RES_BACKTO . "</a>$backarrow");
$xoopsTpl->assign('nav_main', "<a href=\"resumes.php\">" . _JOBS_MAIN . "</a>");
$xoopsTpl->assign('nav_sub', $subcats);
$xoopsTpl->assign('nav_subcount', $res);

if ($recordexist) {
    list($lid, $cid, $name, $title, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $resume, $rphoto, $view, $res_lid, $rlid, $made_resume, $rdate, $rusid, $cod_img, $pic_lid, $uid_owner, $url) = $xoopsDB->fetchRow($result);

    //	Specification for Japan: add  $viewcount_judge for view count up judge
    $viewcount_judge = TRUE;
    $useroffset      = "";
    if ($xoopsUser) {
        $timezone = $xoopsUser->timezone();
        if (isset($timezone)) {
            $useroffset = $xoopsUser->timezone();
        } else {
            $useroffset = $xoopsConfig['default_TZ'];
        }
        //	Specification for Japan: view count up judge
        if (($xoopsUser->getVar("uid") == 1) || ($xoopsUser->getVar("uid") == $usid)) {
            $viewcount_judge = FALSE;
        }
    }
    //	Specification for Japan: view count up judge
    if ($viewcount_judge == TRUE) {
        $xoopsDB->queryF(
            "UPDATE " . $xoopsDB->prefix("jobs_resume") . " SET view=view+1 WHERE lid = "
                . mysql_real_escape_string($lid) . ""
        );
    }

    if ($valid == "0") {
        $xoopsTpl->assign('not_yet_approved', "<font style=\"color:red;\">" . _JOBS_NOT_APPROVED . "</font>");
    }

    $date           = ($useroffset * 3600) + $date;
    $date2          = $date + ($expire * 86400);
    $date           = formatTimestamp($date, "s");
    $date2          = formatTimestamp($date2, "s");
    $rdate          = formatTimestamp($rdate, "s");
    $name           = $myts->htmlSpecialChars($name);
    $title          = $myts->htmlSpecialChars($title);
    $exp            = $myts->htmlSpecialChars($exp);
    $expire         = $myts->htmlSpecialChars($expire);
    $private        = $myts->htmlSpecialChars($private);
    $tel            = $myts->htmlSpecialChars($tel);
    $salary         = $myts->htmlSpecialChars($salary);
    $typeprice      = $myts->htmlSpecialChars($typeprice);
    $submitter      = $myts->htmlSpecialChars($submitter);
    $town           = $myts->htmlSpecialChars($town);
    $state          = $myts->htmlSpecialChars($state);
    $made_resume    = $myts->htmlSpecialChars($made_resume);
    $created_resume = "<a href=\"myresume.php?lid=$lid\">" . _JOBS_VIEWRESUME . "</a>";

    $imprD = "<a href=\"print.php?op=Rprint&amp;lid=" . addslashes($lid)
        . "\" target=\"_blank\"><img src=\"images/print.gif\" border=\"0\" alt=\"" . _JOBS_RPRINT
        . "\" width=\"15\" height=\"11\" /></a>&nbsp;";

    if ($usid > 0) {
        $xoopsTpl->assign(
            'submitter', _JOBS_SUBMITTED_BY . " <a href='" . XOOPS_URL . "/userinfo.php?uid=" . addslashes($usid)
            . "'>$submitter</a>"
        );
    } else {
        $xoopsTpl->assign('submitter', _JOBS_SUBMITTED_BY . " $submitter");
    }

    $xoopsTpl->assign('read', "$view " . _JOBS_VIEW2);

    if ($xoopsUser) {
        $calusern = $xoopsUser->getVar("uid", "E");
        if ($usid == $calusern) {
            $xoopsTpl->assign(
                'modify',
                "<a href=\"modresume.php?lid=" . addslashes($lid) . "\"><img src=" . $pathIcon16 . "/edit.png alt='"
                    . _JOBS_RES_MODIFANN . "' title='" . _JOBS_RES_MODIFANN . "'></a>&nbsp;<a href=\"delresume.php?lid="
                    . addslashes($lid) . "\"><img src=" . $pathIcon16 . "/delete.png alt='" . _JOBS_DEL_RESUME
                    . "' title='" . _JOBS_DEL_RESUME . "'></a>"
            );

            $xoopsTpl->assign(
                'add_photos',
                "<a href=\"../$mydirname/view_photos.php?lid=" . addslashes($lid) . "&uid=" . addslashes($usid) . "\">"
                    . _JOBS_ADD_PHOTOS . "</a>"
            );

        }
        if ($xoopsUser->isAdmin()) {
            $xoopsTpl->assign(
                'admin', "<a href=\"admin/modresume.php?lid=" . addslashes($lid) . "\"><img src=" . $pathIcon16
                . "/edit.png alt='" . _JOBS_MODRESADMIN . "' title='" . _JOBS_MODRESADMIN . "'></a>"
            );
        }
    }

    $state_name = resume_getStateNameFromId($state);

    if (!empty($private) && $unlock != $private) {
        $xoopsTpl->assign('name', _JOBS_NAME_PRIVATE);
    } else {
        $xoopsTpl->assign('name', $name);
    }
    $xoopsTpl->assign('private', $private);
    $xoopsTpl->assign('access', _JOBS_RES_ACCESS);
    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('exp', $exp);
    $xoopsTpl->assign('res_experience_head', _JOBS_RES_EXP);
    $xoopsTpl->assign('local_town', "$town");
    $xoopsTpl->assign('state', $state_name);
    $xoopsTpl->assign('local_head', _JOBS_LOCAL);
    $xoopsTpl->assign('job_mustlogin', _JOBS_RES_MUSTLOGIN);
    $xoopsTpl->assign('job_for', _JOBS_FOR);
    $xoopsTpl->assign('xoops_pagetitle', "$title - $exp");

    if ($salary > 0) {
        $xoopsTpl->assign(
            'salary', '<b>' . _JOBS_RES_SALARY . "</b> $salary " . $xoopsModuleConfig['jobs_money'] . " - $typeprice"
        );
        $xoopsTpl->assign('price_head', _JOBS_RES_SALARY);
        $xoopsTpl->assign('price_price', "" . $xoopsModuleConfig['jobs_money'] . " $salary");
        $xoopsTpl->assign('price_typeprice', "$typeprice");
    }

    $xoopsTpl->assign('contact_head', _JOBS_CONTACT);
    $xoopsTpl->assign(
        'contact_email', "<a href=\"contactresume.php?lid=" . addslashes($lid) . "\">" . _JOBS_BYMAIL2 . "</a>"
    );

    if ($resume != "") {

        if (!empty($private) && $unlock != $private) {

            $xoopsTpl->assign('resume', _JOBS_RES_IS_PRIVATE);
            $xoopsTpl->assign('show_private', _JOBS_RES_PRIVATE_DESC);
        } elseif ($resume != "created") {
            $xoopsTpl->assign('resume', "<a href=\"../$mydirname/resumes/$resume\">" . _JOBS_VIEWRESUME . "</a>");
        } else {
            $xoopsTpl->assign('resume', "$created_resume");
        }
    } else {
        $xoopsTpl->assign('noresume', _JOBS_RES_NORESUME);
    }
    if ($rphoto != "") {
        $xoopsTpl->assign(
            'photo', "<a href=\"view_photos.php?lid=" . addslashes($lid) . "&uid=" . addslashes($uid_owner)
            . "\" target=_self><img src=\"$updir/$url\" alt=\"$title\" width=\"130px\">"
        );
        if ($rphoto > "1") {
            $xoopsTpl->assign('more_photos', _JOBS_MORE_PHOTOS);
        }
        $xoopsTpl->assign('pic_lid', $pic_lid);
        $xoopsTpl->assign('pic_owner', $uid_owner);
    } else {
        $xoopsTpl->assign('rphoto', '');
    }

    $xoopsTpl->assign(
        'date',
        _JOBS_RES_DATE2 . " $date " . _JOBS_DISPO . " $date2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $imprD"
    );

    $result4 = $xoopsDB->query(
        "select title from " . $xoopsDB->prefix("jobs_res_categories") . " where cid=" . mysql_real_escape_string($cid)
            . ""
    );
    list($ctitle) = $xoopsDB->fetchRow($result4);

    $xoopsTpl->assign('link_main', "<a href=\"../$mydirname/resumes.php\">" . _JOBS_MAIN . "</a>");
    $xoopsTpl->assign(
        'friend', "<a href=\"../$mydirname/sendfriend.php?op=SendResume&amp;lid=" . addslashes($lid)
        . "\"><img src=\"../$mydirname/images/friend.gif\" border=\"0\" alt=\"" . _JOBS_SENDTOFRIEND
        . "\" width=\"15\" height=\"11\" /></a>"
    );
    $xoopsTpl->assign(
        'link_cat', "<a href=\"resumecat.php?cid=" . addslashes($cid) . "\">" . _JOBS_GORUB . " $ctitle</a>"
    );
} else {
    $xoopsTpl->assign('no_ad', _JOBS_RES_NOLISTING);
}

include(XOOPS_ROOT_PATH . "/footer.php");
