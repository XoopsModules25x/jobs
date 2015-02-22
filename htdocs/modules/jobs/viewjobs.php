<?php
//  -----------------------------------------------------------------------  //
//                           Jobs Module for Xoops 2.4.x                     //
//                         By John Mordo - jlm69 at Xoops                    //
//                                                                           //                                                                           //                                                                           //
//                                                                           //
// ------------------------------------------------------------------------- //
include 'header.php';

$mydirname = basename(dirname(__FILE__));

require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");

$myts      =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

$gperm_handler =& xoops_gethandler('groupperm');

if (isset($_GET['item_id'])) {
    $perm_itemid = intval($_GET['item_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight("jobs_view", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/modules/profile/", 3, _NOPERM);
    exit();
}
// Check submit rights – added line - recommended by GreenFlatDog
$jobs_submitter = $gperm_handler->checkRight("jobs_submit", $perm_itemid, $groups, $module_id);

include(XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php");
include(XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");

$mytree = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");

ExpireJob();

$GLOBALS['xoopsOption']['template_main'] = "jobs_item.html";

include XOOPS_ROOT_PATH . "/header.php";

if ($xoopsModuleConfig['jobs_show_company'] == '1') {
    $xoopsTpl->assign('show_company', '1');
} else {
    $xoopsTpl->assign('show_company', "");
}
if ($xoopsModuleConfig['jobs_show_state'] == '1') {
    $xoopsTpl->assign('show_state', '1');
} else {
    $xoopsTpl->assign('show_state', "");
}

$lid = intval($_GET['lid']);

$result      = $xoopsDB->query(
    "select lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, usid, town, state, valid, photo, view  FROM "
        . $xoopsDB->prefix("jobs_listing") . " WHERE lid = " . mysql_real_escape_string($lid) . " AND status!='0'"
);
$recordexist = $xoopsDB->getRowsNum($result);

$xoopsTpl->assign('add_from', _JOBS_ADDFROM . " " . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _JOBS_ADDFROM);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('ad_exists', $recordexist);

$count = 0;
$x     = 0;
$i     = 0;

$result1 = $xoopsDB->query(
    "select cid from " . $xoopsDB->prefix("jobs_listing") . " where  lid=" . mysql_real_escape_string($lid) . ""
);
list($cid) = $xoopsDB->fetchRow($result1);

$result2 = $xoopsDB->query(
    "select cid, pid, title from " . $xoopsDB->prefix("jobs_categories") . " where  cid="
        . mysql_real_escape_string($cid) . ""
);
list($ccid, $pid, $title) = $xoopsDB->fetchRow($result2);

$title      = $myts->undoHtmlSpecialChars($title);
$varid[$x]  = $ccid;
$varnom[$x] = $title;

list($trows) = $xoopsDB->fetchRow(
    $xoopsDB->query(
        "select COUNT(*) FROM " . $xoopsDB->prefix("jobs_listing") . " where valid='1' AND cid="
            . mysql_real_escape_string($cid) . ""
    )
);

if ($pid != 0) {
    $x = 1;
    while ($pid != 0) {
        $result3 = $xoopsDB->query(
            "select cid, pid, title from " . $xoopsDB->prefix("jobs_categories") . " where cid=" . addslashes($pid) . ""
        );
        list($ccid, $pid, $title) = $xoopsDB->fetchRow($result3);

        $title = $myts->undoHtmlSpecialChars($title);

        $varid[$x]  = $ccid;
        $varnom[$x] = $title;
        $x++;
    }
    $x = $x - 1;
}

$subcats = '';
$arrow   = "&nbsp;<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/arrow.gif\" alt=\"&raquo;\" />";
while ($x != -1) {
    $subcats .= " $arrow <a href=\"jobscat.php?cid=" . $varid[$x] . "\">" . $varnom[$x] . "</a>";
    $x = $x - 1;
}
$xoopsTpl->assign('nav_main', "<a href=\"index.php\">" . _JOBS_MAIN . "</a>");
$xoopsTpl->assign('nav_sub', $subcats);
$xoopsTpl->assign('nav_subcount', $trows);

if ($recordexist) {
    list($lid, $cid, $title, $status, $expire, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $contactinfo1, $contactinfo2, $date, $email, $submitter, $usid, $town, $state, $valid, $photo, $view) = $xoopsDB->fetchRow($result);

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
            "UPDATE " . $xoopsDB->prefix("jobs_listing") . " SET view=view+1 WHERE lid = "
                . mysql_real_escape_string($lid) . ""
        );
    }

    $date         = ($useroffset * 3600) + $date;
    $date2        = $date + ($expire * 86400);
    $date         = formatTimestamp($date, "s");
    $date2        = formatTimestamp($date2, "s");
    $title        = $myts->undoHtmlSpecialChars($title);
    $status       = $myts->htmlSpecialChars($status);
    $expire       = $myts->htmlSpecialChars($expire);
    $type         = $myts->htmlSpecialChars($type);
    $company      = $myts->undoHtmlSpecialChars($company);
    $desctext     = $myts->displayTarea($desctext, 1, 0, 1, 1, 1);
    $requirements = $myts->displayTarea($requirements, 1, 0, 1, 1, 1);
    $tel          = $myts->htmlSpecialChars($tel);
    $price        = $myts->htmlSpecialChars($price);
    $typeprice    = $myts->htmlSpecialChars($typeprice);
    $contactinfo  = $myts->displayTarea($contactinfo, 1, 1, 1);
    $contactinfo1 = $myts->displayTarea($contactinfo1, 1, 1, 1);
    $contactinfo2 = $myts->displayTarea($contactinfo2, 1, 1, 1);
    $submitter    = $myts->htmlSpecialChars($submitter);
    $town         = $myts->htmlSpecialChars($town);
    $state        = $myts->htmlSpecialChars($state);
    $usid         = intval($usid);

    $imprD = "<a href=\"print.php?op=Jprint&amp;lid=" . addslashes($lid)
        . "\" target=\"_blank\"><img src=\"images/print.gif\" border=\"0\" alt=\"" . _JOBS_PRINT
        . "\" width=\"15\" height=\"11\" /></a>&nbsp;";

    if ($usid > 0) {
        $xoopsTpl->assign(
            'submitter', _JOBS_SUBMITTED_BY . " <a href='" . XOOPS_URL . "/userinfo.php?uid=" . addslashes($usid)
            . "'>$submitter</a>"
        );
    } else {
        $xoopsTpl->assign('submitter', _JOBS_SUBMITTED_BY . " $submitter");
    }

    if ($xoopsModuleConfig['jobs_show_company'] == '1') {

        $comp_id = jobs_getCompIdFromName(addslashes($company));
        $result4 = $xoopsDB->query(
            "select comp_id, comp_name, comp_img, comp_usid, comp_user1, comp_user2, comp_user1_contact, comp_user2_contact FROM "
                . $xoopsDB->prefix("jobs_companies") . " where comp_id=" . mysql_real_escape_string($comp_id) . " and "
                . mysql_real_escape_string($usid) . " IN (comp_usid, comp_user1, comp_user2)"
        );
        list($comp_id, $comp_name, $comp_img, $comp_usid, $comp_user1, $comp_user2, $comp_user1_contact, $comp_user2_contact) = $xoopsDB->fetchRow($result4);
        $comp_id = intval($comp_id);
        if ($result4) {
            $comp_id = intval($comp_id);
            $xoopsTpl->assign(
                'all_jobs',
                _JOBS_VIEW_MY_JOBS . "<a href='members.php?comp_id=" . addslashes($comp_id) . "'>$company</a>"
            );
        }

        if ($comp_img) {
            $xoopsTpl->assign('photo', "<img src=\"logo_images/$comp_img\" alt=\"$comp_name\" />");
        }
    }

    $xoopsTpl->assign('read', "$view " . _JOBS_VIEW2);

    if ($xoopsUser) {
        $member_id = $xoopsUser->uid();

        if ($xoopsModuleConfig['jobs_show_company'] == '1') {

            $comp_users = array($comp_usid, $comp_user1, $comp_user2);
            if (in_array($member_id, $comp_users)) {
                //$xoopsTpl->assign('modify', "<a href=\"modjob.php?lid=".addslashes($lid)."\"><img src=\"images/modif.gif\" border=0 alt=\""._JOBS_MODIFANN."\"></a>&nbsp;<a href=\"deljob.php?lid=".addslashes($lid)."\"><img src=\"images/del.gif\" border=0 alt=\""._JOBS_DEL_JOB."\" /></a>");
                $xoopsTpl->assign(
                    'modify',
                    "<a href='modjob.php?lid=" . addslashes($lid) . "'><img src=" . $pathIcon16 . "/edit.png alt='"
                        . _JOBS_MODIFANN . "' title='" . _JOBS_MODIFANN . "'></a>&nbsp;<a href='deljob.php?lid="
                        . addslashes($lid) . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _JOBS_DEL_JOB
                        . "' title='" . _JOBS_DEL_JOB . "'></a>"
                );
            }

        } else {

            if ($member_id == $usid) {
                //$xoopsTpl->assign('modify', "<a href=\"modjob.php?lid=".addslashes($lid)."\"><img src=\"images/modif.gif\" border=0 alt=\""._JOBS_MODIFANN."\"></a>&nbsp;<a href=\"deljob.php?lid=".addslashes($lid)."\"><img src=\"images/del.gif\" border=0 alt=\""._JOBS_DEL_JOB."\" /></a>");
                $xoopsTpl->assign(
                    'modify',
                    "<a href='modjob.php?lid=" . addslashes($lid) . "'><img src=" . $pathIcon16 . "/edit.png alt='"
                        . _JOBS_MODIFANN . "' title='" . _JOBS_MODIFANN . "'></a>&nbsp;<a href='deljob.php?lid="
                        . addslashes($lid) . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _JOBS_DEL_JOB
                        . "' title='" . _JOBS_DEL_JOB . "'></a>"
                );
            }
        }

        if ($xoopsUser->isAdmin()) {
            $xoopsTpl->assign(
                'admin', "<a href=\"admin/main.php?op=ModJob&amp;lid=" . addslashes($lid) . "\"><img src=" . $pathIcon16
                . "/edit.png alt='" . _JOBS_MODADMIN . "' title='" . _JOBS_MODADMIN . "'></a>"
            );
        }
    }

    $state_name = jobs_getStateNameFromId($state);

    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('status', $status);
    $xoopsTpl->assign('expire', $expire);
    $xoopsTpl->assign('type', $type);
    $xoopsTpl->assign('company', $company);
    $xoopsTpl->assign('desctext', $desctext);
    $xoopsTpl->assign('requirements', $requirements);
    $xoopsTpl->assign('company_head', _JOBS_COMPANY2);
    $xoopsTpl->assign('desctext_head', _JOBS_DESC2);
    $xoopsTpl->assign('requirements_head', _JOBS_REQUIRE);
    $xoopsTpl->assign('local_town', "$town");
    $xoopsTpl->assign('state', $state_name);
    $xoopsTpl->assign('local_head', _JOBS_LOCAL);
    $xoopsTpl->assign('state_head', _JOBS_STATE);
    $xoopsTpl->assign('job_mustlogin', _JOBS_MUSTLOGIN);
    $xoopsTpl->assign('job_for', _JOBS_FOR);
    $xoopsTpl->assign('xoops_pagetitle', "$company - $title - $type ");
    $xoopsTpl->assign('or', _JOBS_OR);
    $xoopsTpl->assign('contactinfo1', $contactinfo1);
    $xoopsTpl->assign('contactinfo2', $contactinfo2);
    
    if (is_numeric($price)) {
    if ($price > 0) {
        $xoopsTpl->assign(
            'price', '<b>' . _JOBS_PRICE2 . "</b> $price " . $xoopsModuleConfig['jobs_money'] . " - $typeprice"
        );
        $xoopsTpl->assign('price_head', _JOBS_PRICE2);
        $xoopsTpl->assign('price_price', "" . $xoopsModuleConfig['jobs_money'] . " $price");
        $xoopsTpl->assign('price_typeprice', "$typeprice");
    }
    } else {
        if ($price != "") {
                $xoopsTpl->assign('price_head', _JOBS_PRICE2);
                $xoopsTpl->assign('price_price', " $price");
            $xoopsTpl->assign(
            'price', '<b>' . _JOBS_PRICE2 . '</b>'. $price.'');
    }
    }
    $xoopsTpl->assign('contactinfo', "$contactinfo");
    $xoopsTpl->assign('contactinfo_head', _JOBS_CONTACTINFO);
    $contact
        = '<b>' . _JOBS_CONTACT . "</b> <a href=\"contact.php?lid=" . addslashes($lid) . "\">" . _JOBS_BYMAIL2 . "</a>";
    $xoopsTpl->assign('contact_head', _JOBS_CONTACT);
    $xoopsTpl->assign(
        'contact_email', "<a href=\"contact.php?lid=" . addslashes($lid) . "\">" . _JOBS_BYMAIL2 . "</a>"
    );
    if ($tel) {
        $xoopsTpl->assign('contact_tel_head', _JOBS_TEL);
        $xoopsTpl->assign('contact_tel', "$tel");
    }
    $contact = "<br /><b>" . _JOBS_TOWN . "</b> $town";

        
    $xoopsTpl->assign(
        'date',
        _JOBS_DATE2 . " $date " . _JOBS_DISPO . " $date2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $imprD"
    );

    $result8 = $xoopsDB->query(
        "select title from " . $xoopsDB->prefix("jobs_categories") . " where cid=" . mysql_real_escape_string($cid) . ""
    );
    list($ctitle) = $xoopsDB->fetchRow($result8);
    $ctitle = $myts->undoHtmlSpecialChars($ctitle);

    $xoopsTpl->assign('link_main', "<a href=\"../$mydirname/\">" . _JOBS_MAIN . "</a>");
    $xoopsTpl->assign(
        'friend', "<a href=\"../$mydirname/sendfriend.php?op=SendJob&amp;lid=" . addslashes($lid)
        . "\"><img src=\"../$mydirname/images/friend.gif\" border=\"0\" alt=\"" . _JOBS_SENDTOFRIEND
        . "\" width=\"15\" height=\"11\" /></a>"
    );
    $xoopsTpl->assign(
        'link_cat', "<a href=\"jobscat.php?cid=" . addslashes($cid) . "\">" . _JOBS_GORUB . " $ctitle</a>"
    );
} else {
    $xoopsTpl->assign('all_jobs', "");
    $xoopsTpl->assign('no_ad', _JOBS_NOLISTING);
}

include(XOOPS_ROOT_PATH . "/footer.php");
