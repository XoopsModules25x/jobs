<?php
//  -----------------------------------------------------------------------  //
//                           Jobs Module for Xoops 2.4.x                     //
//                         By John Mordo - jlm69 at Xoops                    //
//                                                                           //
// ------------------------------------------------------------------------- //
include 'header.php';

$mydirname = basename(dirname(__FILE__));
$main_lang = '_' . strtoupper($mydirname);
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
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/class/restree.php");
$mytree    = new ResTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");
$statetree = new ResTree($xoopsDB->prefix("jobs_region"), "rid", "pid");

ExpireResume();

if ($xoopsModuleConfig['jobs_show_resume'] != '1') {
    redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
    exit();
}

$GLOBALS['xoopsOption']['template_main'] = "jobs_index2.html";
include(XOOPS_ROOT_PATH . "/header.php");
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
$xoopsTpl->assign('add_from', _JOBS_ADDFROM . " " . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _JOBS_RESUME_TITLE);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('intro_resume', _JOBS_RES_INTRO);
$xoopsTpl->assign('back_to_jobs', "<a href=\"index.php\">" . _JOBS_RES_BACKTO . "</a>");
$xoopsTpl->assign('employers', _JOBS_EMPLOYERS);

if ($xoopsModuleConfig['jobs_resume_search'] == '1') { // added ‘if’ block: controls search section in template
    $xoopsTpl->assign('resume_search', TRUE);
    $xoopsTpl->assign('search_listings', _JOBS_SEARCH_LISTINGS);
    $xoopsTpl->assign('all_words', _JOBS_ALL_WORDS);
    $xoopsTpl->assign('any_words', _JOBS_ANY_WORDS);
    $xoopsTpl->assign('exact_match', _JOBS_EXACT_MATCH);
    $xoopsTpl->assign('bystate', _JOBS_SEARCH_BYSTATE);
    $xoopsTpl->assign('bycategory', _JOBS_SEARCH_BYCATEGORY);
    $xoopsTpl->assign('keywords', _JOBS_SEARCH_KEYWORDS);

    ob_start();
    $mytree->resume_makeMySearchSelBox("title", "title", "", 1, "by_cat");
    $by_cat = ob_get_contents();
    ob_end_clean();
    $xoopsTpl->assign('by_cat', $by_cat);

    if ($xoopsModuleConfig['jobs_countries'] == "1") {
        ob_start();
        $statetree->resume_makeMyStateSelBox("name", "rid", "", 1, "by_state");
        $by_state = ob_get_contents();
        ob_end_clean();
        $xoopsTpl->assign('by_state', $by_state);

    } else {

        ob_start();
        $statetree->resume_makeStateSelBox("name", "rid", "", 1, "by_state");
        $by_state = ob_get_contents();
        ob_end_clean();
        $xoopsTpl->assign('by_state', $by_state);
    }
}

$index_banner = xoops_getbanner();
$xoopsTpl->assign('index_banner', $index_banner);
$index_code_place = $xoopsModuleConfig['jobs_index_code_place'];
$use_extra_code   = $xoopsModuleConfig['jobs_use_index_code'];
$jobs_use_banner  = $xoopsModuleConfig['jobs_use_banner'];
$index_extra_code = $xoopsModuleConfig['jobs_index_code'];
$xoopsTpl->assign('use_extra_code', $use_extra_code);
$xoopsTpl->assign('jobs_use_banner', $jobs_use_banner);
$xoopsTpl->assign('index_extra_code', "<html>" . $index_extra_code . "</html>");
$xoopsTpl->assign('index_code_place', $index_code_place);

if ($xoopsUser) {
    $member_usid = $xoopsUser->getVar("uid", "E");

    $your_res = $xoopsDB->query(
        "select lid,title,usid FROM " . $xoopsDB->prefix("jobs_resume") . " WHERE usid ="
            . mysql_real_escape_string($member_usid) . ""
    );
    while ($myrow = $xoopsDB->fetchArray($your_res)) {
        $a_res    = array();
        $istheirs = "";
        if ($myrow['usid'] == $member_usid) {
            $istheirs = TRUE;
            $xoopsTpl->assign('istheirs', $istheirs);
        }
        $a_res['title'] = "<a href='viewresume.php?lid=" . addslashes($myrow['lid']) . "'>" . $myrow['title'] . "</a>";
        $xoopsTpl->append('your_resumes', $a_res);
        $xoopsTpl->assign('your_resume', _YOUR_RESUME);
    }
}
$xoopsTpl->assign('is_resume', '1');
if ($xoopsModuleConfig['jobs_moderated'] == '1') {
    $result = $xoopsDB->query("select  COUNT(*)  FROM " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0'");
    list($propo) = $xoopsDB->fetchRow($result);
    $xoopsTpl->assign('moderated', TRUE);

    if ($xoopsUser) {

        $member_usid = $xoopsUser->getVar("uid", "E");
        if ($xoopsUser->isAdmin()) {
            $xoopsTpl->assign('admin_block', _JOBS_ADMINCADRE);
            if ($propo == 0) {
                $xoopsTpl->assign('confirm_resume', _JOBS_NO_JOBS);
            } else {
                $xoopsTpl->assign(
                    'confirm_resume',
                    _JOBS_THEREIS . " $propo  " . _JOBS_WAIT . "<br /><a href=\"admin/resumes.php\">" . _JOBS_SEEIT
                        . "</a>"
                );
            }
        }
    }
}
if ($xoopsModuleConfig['jobs_moderate_resume'] == '1') {
    $result1 = $xoopsDB->query("select  COUNT(*)  FROM " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0'");
    list($res_propo) = $xoopsDB->fetchRow($result1);
    $xoopsTpl->assign('res_moderated', TRUE);
    if ($xoopsUser) {
        if ($xoopsUser->isAdmin()) {
            $xoopsTpl->assign('admin_block', _JOBS_ADMINCADRE);
            if ($res_propo == 0) {
                $xoopsTpl->assign('confirm_resume', _JOBS_RES_NO_JOBS);
            } else {
                $xoopsTpl->assign(
                    'confirm_resume',
                    _JOBS_RES_THEREIS . " $res_propo  " . _JOBS_RES_WAIT . "<br /><a href=\"admin/resumes.php\">"
                        . _JOBS_RES_SEEIT . "</a>"
                );
            }
        }
    }
}

$sql = 'SELECT cid, title, img FROM ' . $xoopsDB->prefix("" . $mydirname . "_res_categories") . ' WHERE pid = 0 ';

$categories = resume_MygetItemIds("resume_view");
if (is_array($categories) && count($categories) > 0) {
    $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
} else { // User can't see any category
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
    exit();
}
$sql .= 'ORDER BY title';
$result = $xoopsDB->query($sql);

$count   = 1;
$content = '';
while ($myrow = $xoopsDB->fetchArray($result)) {
    $title = $myts->undoHtmlSpecialChars($myrow['title']);

    if ($myrow['img'] && $myrow['img'] != 'http://') {

        $cat_img = $myts->htmlSpecialChars($myrow['img']);
        $img     = "<a href=\"resumecat.php?cid=" . $myrow['cid'] . "\"><img src='" . XOOPS_URL
            . "/modules/$mydirname/images/cat/" . $cat_img . "' align='middle' alt='' /></a>";
    } else {
        $img = '';
    }
    $totallisting = resume_getTotalResumes($myrow['cid'], 1);
    $content .= $title . ' ';

    // get child category objects
    $arr = array();
    if (in_array($myrow['cid'], $categories)) {
        $arr           = $mytree->resume_getFirstChild(
            $myrow['cid'], "" . $xoopsModuleConfig['jobs_cat_sortorder'] . ""
        );
        $space         = 0;
        $chcount       = 0;
        $subcategories = '';
        if ($xoopsModuleConfig['jobs_display_subcat'] == 1) {
            foreach ($arr as $ele) {
                if (in_array($ele['cid'], $categories)) {
                    $chtitle = $myts->undoHtmlSpecialChars($ele['title']);
                    if ($chcount > $xoopsModuleConfig['jobs_subcat_num']) {
                        $subcategories .= ', ...';
                        break;
                    }
                    if ($space > 0) {
                        $subcategories .= '<br />';
                    }
                    $subcategories
                        .= "<a href=\"" . XOOPS_URL . "/modules/$mydirname/jobscat.php?cid=" . $ele['cid'] . "\">"
                        . $chtitle . "</a>";
                    $space++;
                    $chcount++;
                    $content .= $ele['title'] . ' ';
                }
            }
        }
        $xoopsTpl->append('categories', array('image' => $img, 'id' => $myrow['cid'], 'title' => $myts->undoHtmlSpecialChars($myrow['title']), 'new' => resume_categorynewgraphic($myrow['cid']), 'subcategories' => $subcategories, 'totallisting' => $totallisting, 'count' => $count));
        $count++;
    }
}
$cat_perms = "";
if (is_array($categories) && count($categories) > 0) {
    $cat_perms  = ' AND cid IN (' . implode(',', $categories) . ') ';
    $cat_perms2 = ' WHERE cid IN (' . implode(',', $categories) . ') ';
}

$xoopsTpl->assign('cat_count', $count - 1);

list($ann) = $xoopsDB->fetchRow(
    $xoopsDB->query(
        "select  COUNT(*)  FROM " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='1'$cat_perms"
    )
);
//	list($catt) = $xoopsDB->fetchRow($xoopsDB->query("select  COUNT(*)  FROM ".$xoopsDB->prefix("jobs_res_categories")." $cat_perms2"));

$xoopsTpl->assign('clickbelow', _JOBS_CLICKBELOW);
$xoopsTpl->assign('add_resume', "<a href='addresume.php'>" . _JOBS_RES_ADDRESUME . "</a>");
$xoopsTpl->assign('show_resume', "<a href='resumes.php'>" . _JOBS_RESUME2 . "</a>");
$xoopsTpl->assign('total_listings', _JOBS_ACTUALY . " $ann " . _JOBS_RES_LISTINGS . " " . _JOBS_DATABASE);
if ($xoopsModuleConfig['jobs_moderate_resume'] == '1') {
    $xoopsTpl->assign('total_confirm', _JOBS_AND . " $res_propo " . _JOBS_WAIT3);
}
if ($xoopsModuleConfig['jobs_new_jobs'] == 1) {
    $result = $xoopsDB->query(
        "select lid, name, title, status, exp, expire, private, salary, typeprice, date, usid, town, state, valid, view FROM "
            . $xoopsDB->prefix("jobs_resume") . " WHERE valid='1' $cat_perms ORDER BY date DESC LIMIT "
            . $xoopsModuleConfig['jobs_new_jobs_count'] . ""
    );
    if ($result) {
        $xoopsTpl->assign(
            'last_res_head', _JOBS_THE . " " . $xoopsModuleConfig['jobs_new_jobs_count'] . " " . _JOBS_RES_LASTADD
        );
        $xoopsTpl->assign('last_res_head_experience', _JOBS_RES_EXP);
        $xoopsTpl->assign('last_res_head_title', _JOBS_TITLE);
        $xoopsTpl->assign('last_res_head_salary', _JOBS_PRICE);
        $xoopsTpl->assign('last_res_head_date', _JOBS_DATE);
        $xoopsTpl->assign('last_res_head_local', _JOBS_LOCAL2);
        $xoopsTpl->assign('last_res_head_views', _JOBS_VIEW);
        $rank = 1;

        while (list($lid, $name, $title, $status, $exp, $expire, $private, $salary, $typeprice, $date, $usid, $town, $state, $valid, $vu) = $xoopsDB->fetchRow($result)) {
            $name       = $myts->htmlSpecialChars($name);
            $title      = $myts->htmlSpecialChars($title);
            $status     = $myts->htmlSpecialChars($status);
            $exp        = $myts->htmlSpecialChars($exp);
            $private    = $myts->htmlSpecialChars($private);
            $salary     = $myts->htmlSpecialChars($salary);
            $typeprice  = $myts->htmlSpecialChars($typeprice);
            $date       = $myts->htmlSpecialChars($date);
            $town       = $myts->htmlSpecialChars($town);
            $state      = $myts->htmlSpecialChars($state);
            $a_item     = array();
            $useroffset = "";
            if ($xoopsUser) {
                $timezone = $xoopsUser->timezone();
                if (isset($timezone)) {
                    $useroffset = $xoopsUser->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }

            $date          = ($useroffset * 3600) + $date;
            $a_item['new'] = resume_listingnewgraphic($date);
            $date          = formatTimestamp($date, "s");

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    $a_item['admin']
                        = "<a href='admin/modresume.php?lid=" . addslashes($lid) . "'><img src=" . $pathIcon16
                        . "/edit.png alt='" . _JOBS_MODRESADMIN . "' title='" . _JOBS_MODRESADMIN . "'></a>";
                }
            }

            $a_item['title']   = "<a href='viewresume.php?lid=" . addslashes($lid) . "'>$title</a>";
            $a_item['private'] = $private;
            $a_item['exp']     = $exp;
            $a_item['name']    = $name;
            if ($salary > 0) {
                $a_item['salary'] = "" . $xoopsModuleConfig['jobs_money'] . " $salary";
                // Add $price_typeprice by Tom
                $a_item['price_typeprice'] = "$typeprice";
            } else {
                $a_item['salary']          = "";
                $a_item['price_typeprice'] = "$typeprice";
            }

            $a_item['date'] = $date;
            $a_item['town'] = '';
            if ($town) {
                $a_item['town'] = $town;
            }
            $a_item['state'] = '';
            if ($state) {
                $state_name      = resume_getStateNameFromId($state);
                $a_item['state'] = $state_name;
            }
            $a_item['views'] = $vu;

            $rank++;
            $xoopsTpl->append('items', $a_item);
        }
    }
}

include(XOOPS_ROOT_PATH . "/footer.php");
