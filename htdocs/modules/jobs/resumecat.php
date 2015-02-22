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
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/class/restree.php");
$mytree = new ResTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");

ExpireResume();

$cid     = intval($_GET['cid']);
$min     = !isset($_REQUEST['min']) ? NULL : $_REQUEST['min'];
$show    = !isset($_REQUEST['show']) ? NULL : $_REQUEST['show'];
$orderby = !isset($_REQUEST['orderby']) ? NULL : $_REQUEST['orderby'];

$GLOBALS['xoopsOption']['template_main'] = "jobs_res_category.html";
include(XOOPS_ROOT_PATH . "/header.php");
$default_sort = $xoopsModuleConfig['jobs_resume_sortorder'];

$cid  = (intval($cid) > 0) ? intval($cid) : 0;
$min  = (intval($min) > 0) ? intval($min) : 0;
$show = (intval($show) > 0) ? intval($show) : $xoopsModuleConfig["" . $mydirname . "_resume_perpage"];
$max  = $min + $show;
if (isset($orderby)) {
    $xoopsTpl->assign('sort_active', $orderby); // added for compact sort
    $orderby = resume_convertorderbyin($orderby);
} else {
//   To change the red arrows in the active sort to another option
//   you need to change dateD below to your choice, Default is 'dateD'
//   options are -  'dateD'   'dateA'  'titleA'   'titleD'   'viewA'   'viewD'   'townA'   'townD'   'stateA'   'stateD'
    $xoopsTpl->assign('show_active', 'dateD');
    $orderby = $default_sort;
}

$orderbyTrans = resume_convertorderbytrans($orderby);
$xoopsTpl->assign('cid', $cid);
$xoopsTpl->assign('add_from', _JOBS_RES_ADDFROM . " " . $xoopsConfig['sitename']);
$xoopsTpl->assign('add_from_title', _JOBS_RESUME_TITLE);
$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
$xoopsTpl->assign('nav_jobs', "<a href=\"index.php\">" . _JOBS_RES_BACKTO . "</a>");
$xoopsTpl->assign('add_resume', "<a href='addresume.php?cid=" . addslashes($cid) . "'>" . _JOBS_RES_ADDRESUME . "</a>");

$resume_banner = xoops_getbanner();
$xoopsTpl->assign('resume_banner', $resume_banner);
$index_code_place = $xoopsModuleConfig['jobs_index_code_place'];
$use_extra_code   = $xoopsModuleConfig['jobs_resume_code'];
$jobs_use_banner  = $xoopsModuleConfig['jobs_use_banner'];
$index_extra_code = $xoopsModuleConfig['jobs_index_code'];
$xoopsTpl->assign('use_extra_code', $use_extra_code);
$xoopsTpl->assign('jobs_use_banner', $jobs_use_banner);
$xoopsTpl->assign('index_extra_code', $index_extra_code);
$xoopsTpl->assign('index_code_place', $index_code_place);
$xoopsTpl->assign('perpage', $xoopsModuleConfig["" . $mydirname . "_resume_perpage"]);

$categories = resume_MygetItemIds("resume_view");
if (is_array($categories) && count($categories) > 0) {
    if (!in_array($cid, $categories)) {
        redirect_header(XOOPS_URL . "/modules/$mydirname/resume.php", 3, _NOPERM);
        exit();
    }
} else { // User can't see any category
    redirect_header(XOOPS_URL . '/resume.php', 3, _NOPERM);
    exit();
}

$backarrow = "<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/backarrow.gif\" alt=\"&laquo;\" />";
$arrow     = "<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/arrow.gif\" alt=\"&raquo;\" />";
$pathstring
           = "<a href='index.php'>" . $mydirname . "</a> " . $backarrow . " <a href='resumes.php'>" . _JOBS_RES_LISTINGS
    . "</a>";
$pathstring .= $mytree->resume_getNicePathFromId($cid, 'title', 'resumecat.php?');
$xoopsTpl->assign('module_name', "$mydirname");
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);

$cat_perms = "";
if (is_array($categories) && count($categories) > 0) {
    $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
}

$countresult = $xoopsDB->query(
    "select COUNT(*) FROM " . $xoopsDB->prefix("jobs_resume") . " where valid='1' AND cid="
        . mysql_real_escape_string($cid) . "$cat_perms"
);
list($trow) = $xoopsDB->fetchRow($countresult);
$trows = $trow;

$result = $xoopsDB->query(
    "select cid, pid, title from " . $xoopsDB->prefix("jobs_res_categories") . " where  cid="
        . mysql_real_escape_string($cid) . "$cat_perms"
);
list($ccid, $pid, $title) = $xoopsDB->fetchRow($result);

$xoopsTpl->assign('xoops_pagetitle', $title);
$xoopsTpl->assign('all_resumes', _JOBS_RESUMES);
$xoopsTpl->assign('cat_title', $title);
$xoopsTpl->assign('resumes_all', _JOBS_ALL);

//	$categories = resume_MygetItemIds("".$mydirname."_view");

$arr = array();
$arr = $mytree->resume_getFirstChild($cid, "title");
if (count($arr) > 0) {
    $scount = 1;
    foreach ($arr as $ele) {
        if (in_array($ele['cid'], $categories)) {
            $sub_arr         = array();
            $sub_arr         = $mytree->resume_getFirstChild($ele['cid'], 'title');
            $space           = 0;
            $chcount         = 0;
            $infercategories = "";
            foreach ($sub_arr as $sub_ele) {

                if (in_array($sub_ele['cid'], $categories)) {
                    $chtitle = $myts->htmlSpecialChars($sub_ele['title']);
                    if ($chcount > 5) {
                        $infercategories .= "...";
                        break;
                    }
                    if ($space > 0) {
                        $infercategories .= ", ";
                    }
                    $infercategories
                        .= "<a href=\"" . XOOPS_URL . "/modules/$mydirname/resumecat.php?cid=" . $sub_ele['cid'] . "\">"
                        . $chtitle . "</a>";
                    $infercategories .= "&nbsp;(" . resume_getTotalResumes($sub_ele['cid']) . ")";
                    $infercategories .= "&nbsp;" . resume_categorynewgraphic($sub_ele['cid']) . "";
                    $space++;
                    $chcount++;
                }
            }

            $xoopsTpl->append(
                'subcategories', array(
                    'title' => $myts->htmlSpecialChars($ele['title']), 'id' => $ele['cid'], 'infercategories' => $infercategories, 'totallisting' => resume_getTotalResumes($ele['cid'], 1), 'count' => $scount, 'new' =>
                    "&nbsp;" . resume_categorynewgraphic($ele['cid']) . ""
                )
            );
            $scount++;
            $xoopsTpl->assign('lang_subcat', _JOBS_AVAILAB);
        }
    }
} else {
    $xoopsTpl->assign('there_are_listings', TRUE);
}

$pagenav = '';

if ($trows == "0") {
    $xoopsTpl->assign('show_nav', FALSE);
    $xoopsTpl->assign('no_resumes_to_show', _JOBS_NOANNINCAT);
} elseif ($trows > "0") {
    $xoopsTpl->assign(
        'last_res_head', _JOBS_THE . " " . $xoopsModuleConfig['jobs_new_jobs_count'] . " " . _JOBS_LASTADD
    );
    $xoopsTpl->assign('last_res_head_exp', _JOBS_RES_EXP);
    $xoopsTpl->assign('last_res_head_title', _JOBS_TITLE);
    $xoopsTpl->assign('last_res_head_salary', _JOBS_PRICE);
    $xoopsTpl->assign('last_res_head_date', _JOBS_DATE);
    $xoopsTpl->assign('last_res_head_local', _JOBS_LOCAL2);
    $xoopsTpl->assign('last_res_head_views', _JOBS_VIEW);
    $xoopsTpl->assign('min', $min);

    $sql
             =
        "select lid, cid, name, title, exp, expire, private, salary, typeprice, date, town, state, valid, view from "
            . $xoopsDB->prefix("jobs_resume") . " where valid='1' AND cid=" . mysql_real_escape_string($cid)
            . " order by $orderby";
    $result1 = $xoopsDB->query($sql, $show, $min);

    if ($trows > "1") {
        $xoopsTpl->assign('show_nav', TRUE);
        $xoopsTpl->assign('lang_sortby', _JOBS_SORTBY);
        $xoopsTpl->assign('lang_title', _JOBS_TITLE);
        $xoopsTpl->assign('lang_titleatoz', _JOBS_TITLEATOZ);
        $xoopsTpl->assign('lang_titleztoa', _JOBS_TITLEZTOA);
        $xoopsTpl->assign('lang_date', _JOBS_DATE);
        $xoopsTpl->assign('lang_dateold', _JOBS_DATEOLD);
        $xoopsTpl->assign('lang_datenew', _JOBS_DATENEW);
        $xoopsTpl->assign('lang_exp', _JOBS_EXP);
        $xoopsTpl->assign('lang_expltoh', _JOBS_EXPLTOH);
        $xoopsTpl->assign('lang_exphtol', _JOBS_EXPHTOL);
        $xoopsTpl->assign('lang_local', _JOBS_LOCAL2);
        $xoopsTpl->assign('lang_localatoz', _JOBS_LOCALATOZ);
        $xoopsTpl->assign('lang_localztoa', _JOBS_LOCALZTOA);
        $xoopsTpl->assign('lang_popularity', _JOBS_POPULARITY);
        $xoopsTpl->assign('lang_popularityleast', _JOBS_POPULARITYLTOM);
        $xoopsTpl->assign('lang_popularitymost', _JOBS_POPULARITYMTOL);
        $xoopsTpl->assign('lang_cursortedby', sprintf(_JOBS_CURSORTEDBY, resume_convertorderbytrans($orderby)));
    }

    $rank = 1;
    while (list($lid, $cid, $name, $title, $exp, $expire, $private, $salary, $typeprice, $date, $town, $state, $valid, $vu) = $xoopsDB->fetchRow($result1)) {
        $a_item     = array();
        $name       = $myts->htmlSpecialChars($name);
        $title      = $myts->htmlSpecialChars($title);
        $exp        = $myts->htmlSpecialChars($exp);
        $expire     = $myts->htmlSpecialChars($expire);
        $private    = $myts->htmlSpecialChars($private);
        $salary     = $myts->htmlSpecialChars($salary);
        $town       = $myts->htmlSpecialChars($town);
        $state      = resume_getStateNameFromId($state);
        $useroffset = "";

        if ($xoopsUser) {
            $timezone = $xoopsUser->timezone();
            if (isset($timezone)) {
                $useroffset = $xoopsUser->timezone();
            } else {
                $useroffset = $xoopsConfig['default_TZ'];
            }
        }
        $date      = ($useroffset * 3600) + $date;
        $startdate = (time() - (86400 * $xoopsModuleConfig['jobs_countday']));
        if ($startdate < $date) {
            $newitem       = "<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/newred.gif\" />";
            $a_item['new'] = $newitem;
        }

        $date = formatTimestamp($date, "s");
        if ($xoopsUser) {
            if ($xoopsUser->isAdmin()) {
                $a_item['admin'] = "<a href='admin/modresume.php?lid=" . addslashes($lid) . "'><img src=" . $pathIcon16
                    . "/edit.png alt='" . _JOBS_MODRESADMIN . "' title='" . _JOBS_MODRESADMIN . "'></a>";
            }
        }

        $a_item['title']   = "<a href='viewresume.php?lid=" . addslashes($lid) . "'>$title</a>";
        $a_item['name']    = $name;
        $a_item['exp']     = $exp;
        $a_item['private'] = $private;
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
        if ($state) {

            $a_item['state'] = $state;
        }
        $a_item['views'] = $vu;
        $rank++;
        $xoopsTpl->append('items', $a_item);
    }

    $cid     = (intval($cid) > 0) ? intval($cid) : 0;
    $orderby = resume_convertorderbyout($orderby);

    //Calculates how many pages exist.  Which page one should be on, etc...
    $linkpages = ceil($trows / $show);
    //Page Numbering
    if ($linkpages != 1 && $linkpages != 0) {
        $prev = $min - $show;
        if ($prev >= 0) {
            $pagenav .= "<a href='resumecat.php?cid=$cid&min=$prev&orderby=$orderby&show=$show'><b><u>&laquo;</u></b></a> ";
        }
        $counter     = 1;
        $currentpage = ($max / $show);
        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;
            if ($counter == $currentpage) {
                $pagenav .= "<b>($counter)</b> ";
            } else {
                $pagenav .= "<a href='resumecat.php?cid=$cid&min=$mintemp&orderby=$orderby&show=$show'>$counter</a> ";
            }
            $counter++;
        }
        if ($trows > $max) {
            $pagenav .= "<a href='resumecat.php?cid=$cid&min=$max&orderby=$orderby&show=$show'>";
            $pagenav .= "<b><u>&raquo;</u></b></a>";
        }
    }
    $xoopsTpl->assign('nav_page', $pagenav);
}

include(XOOPS_ROOT_PATH . "/footer.php");
