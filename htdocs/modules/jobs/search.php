<?php
// $Id: search.php 1290 2008-02-10 13:09:25Z phppp $
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

$xoopsOption['pagetype'] = "search";

include '../../mainfile.php';
$mydirname         = basename(dirname(__FILE__));
$search_lang       = '_' . strtoupper($mydirname);
$xmid              = $xoopsModule->getVar('mid');
$config_handler    =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);

if ($xoopsConfigSearch['enable_search'] != 1) {
    header('Location: ' . XOOPS_URL . '/index.php');
    exit();
}

$action = "search";
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
} elseif (!empty($_POST['action'])) {
    $action = $_POST['action'];
}
$query = "";
if (!empty($_GET['query'])) {
    $query = $_GET['query'];
} elseif (!empty($_POST['query'])) {
    $query = $_POST['query'];
}
$andor = "AND";
if (!empty($_GET['andor'])) {
    $andor = $_GET['andor'];
} elseif (!empty($_POST['andor'])) {
    $andor = $_POST['andor'];
}
$mid = $uid = $start = 0;
if (!empty($_GET['mid'])) {
    $mid = intval($_GET['mid']);
} elseif (!empty($_POST['mid'])) {
    $mid = intval($_POST['mid']);
}
if (!empty($_GET['uid'])) {
    $uid = intval($_GET['uid']);
} elseif (!empty($_POST['uid'])) {
    $uid = intval($_POST['uid']);
}
if (!empty($_GET['start'])) {
    $start = intval($_GET['start']);
} elseif (!empty($_POST['start'])) {
    $start = intval($_POST['start']);
}

if (!empty($_GET['is_resume'])) {
    $is_resume = intval($_GET['is_resume']);
} elseif (!empty($_POST['is_resume'])) {
    $is_resume = intval($_POST['is_resume']);
}

if (!empty($_GET['by_state'])) {
    $by_state = $_GET['by_state'];
} elseif (!empty($_POST['by_state'])) {
    $by_state = $_POST['by_state'];
} else {
    $by_state = "";
}

if (!empty($_GET['by_cat'])) {
    $by_cat = $_GET['by_cat'];
} elseif (!empty($_POST['by_cat'])) {
    $by_cat = $_POST['by_cat'];
} else {
    $by_cat = "";
}

if (!empty($_GET['issearch'])) {
    $issearch = intval($_GET['issearch']);
} else {
    if (!empty($_POST['issearch'])) {
        $issearch = intval($_POST['issearch']);
    } else {
        $issearch = "";
    }
}
$state_name = "";
$cat_name   = "";
if (!empty($is_resume)) {

    if (!empty($by_state)) {
        include_once (XOOPS_ROOT_PATH . "/modules/jobs/include/resume_functions.php");
        $state_name = resume_getStateNameFromId($by_state);
    }
    if (!empty($by_cat)) {
        include_once (XOOPS_ROOT_PATH . "/modules/jobs/include/resume_functions.php");
        $cat_name = resume_getResCatNameFromId($by_cat);
    }
} else {

    if (!empty($by_state)) {
        include_once (XOOPS_ROOT_PATH . "/modules/jobs/include/functions.php");
        $state_name = jobs_getStateNameFromId($by_state);
    }
    if (!empty($by_cat)) {
        include_once (XOOPS_ROOT_PATH . "/modules/jobs/include/functions.php");
        $cat_name = jobs_getCatNameFromId($by_cat);
    }
}

$queries = array();

if ($action == "results") {
    if ($query == "") {
        redirect_header("search.php", 1, _SR_PLZENTER);
        exit();
    }
} elseif ($action == "showall") {
    if ($query == "" || empty($mid)) {
        redirect_header("search.php", 1, _SR_PLZENTER);
        exit();
    }
} elseif ($action == "showallbyuser") {
    if (empty($mid) || empty($uid)) {
        redirect_header("search.php", 1, _SR_PLZENTER);
        exit();
    }
} elseif ($action == "showstate") {
    if (empty($mid) || empty($uid)) {
        redirect_header("search.php", 1, _SR_PLZENTER);
        exit();
    }
}

$groups            = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gperm_handler     = & xoops_gethandler('groupperm');
$available_modules = $gperm_handler->getItemIds('module_read', $groups);

if ($action == 'search') {
    include XOOPS_ROOT_PATH . '/header.php';
//  $issearch = "1";
    include 'include/searchform.php';
    $search_form->display();
    include XOOPS_ROOT_PATH . '/footer.php';
    exit();
}

if ($andor != "OR" && $andor != "exact" && $andor != "AND") {
    $andor = "AND";
}

$myts =& MyTextSanitizer::getInstance();
if ($action != 'showallbyuser') {
    if ($andor != "exact") {
        $ignored_queries = array(); // holds kewords that are shorter than allowed minmum length
        $temp_queries    = preg_split('/[\s,]+/', $query);
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch['keyword_min']) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (count($queries) == 0) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
            exit();
        }
    } else {
        $query = trim($query);
        if (strlen($query) < $xoopsConfigSearch['keyword_min']) {
            redirect_header('search.php', 2, sprintf(_SR_KEYTOOSHORT, $xoopsConfigSearch['keyword_min']));
            exit();
        }
        $queries = array($myts->addSlashes($query));
    }
}
switch ($action) {
case "results":
    $module_handler =& xoops_gethandler('module');
    $criteria       = new CriteriaCompo(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
    $criteria->add(new Criteria('mid', $xmid));
    $modules = $module_handler->getObjects($criteria, TRUE);
    $mids    = isset($_REQUEST['mids']) ? $_REQUEST['mids'] : array();
    if (empty($mids) || !is_array($mids)) {
        unset($mids);
        $mids = array_keys($modules);
    }

    if ((!empty($by_state)) && (!empty($by_cat))) {
        $xoopsOption['xoops_pagetitle']
            = _SR_SEARCHRESULTS . ': ' . $state_name . ' - ' . $cat_name . ' - ' . implode(' ', $queries);
    } elseif (!empty($by_state)) {
        $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . $state_name . ' - ' . implode(' ', $queries);
    } elseif (!empty($by_cat)) {
        $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . $cat_name . ' - ' . implode(' ', $queries);
    } else {
        $xoopsOption['xoops_pagetitle'] = _SR_SEARCHRESULTS . ': ' . implode(' ', $queries);
    }

    include XOOPS_ROOT_PATH . "/header.php";

    echo "<h3>" . _SR_SEARCHRESULTS . "</h3>\n";
    echo _SR_KEYWORDS . ':';
    if ($andor != 'exact') {
        foreach ($queries as $q) {
            echo ' <b>' . htmlspecialchars(stripslashes($q)) . '</b>';
        }
        if (!empty($ignored_queries)) {
            echo '<br />';
            printf(_SR_IGNOREDWORDS, $xoopsConfigSearch['keyword_min']);
            foreach ($ignored_queries as $q) {
                echo ' <b>' . htmlspecialchars(stripslashes($q)) . '</b>';
            }
        }
    } else {
        echo ' "<b>' . htmlspecialchars(stripslashes($queries[0])) . '</b>"';
    }
    echo '<br />';

    foreach ($mids as $mid) {
        $mid = intval($mid);
        if (in_array($mid, $available_modules)) {
            $module  =& $modules[$mid];
            $results = $module->search($queries, $andor, 5, 0);
            echo "<h3>" . $myts->htmlSpecialChars($module->getVar('name')) . "</h3>";

            if ($is_resume == 1) {
                echo "<h4>Resumes</h4>";
            }

            $count = count($results);
            if (!is_array($results) || $count == 0) {

                if (!empty($by_state)) {
                    echo "" . _JOBS_INSTATE . "<b> $state_name</b><br /><br />";
                }

                if (!empty($by_cat)) {
                    echo "" . _JOBS_INCATEGORY . "<b> $cat_name</b><br /><br />";
                }

                echo "<p>" . _SR_NOMATCH . "</p>";
            } else {

                if (!empty($by_state)) {
                    echo "" . _JOBS_INSTATE . "<b> $state_name</b><br /><br />";
                }

                if (!empty($by_cat)) {
                    echo "" . _JOBS_INCATEGORY . "<b> $cat_name</b><br /><br />";
                }

                for ($i = 0; $i < $count; ++$i) {

                    if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                        $results[$i]['link'] = "" . $results[$i]['link'];
                    }

                    if ($is_resume != "1") {
                        echo"<br /><b>" . $myts->undoHtmlSpecialChars($results[$i]['company']) . " - "
                            . $myts->undoHtmlSpecialChars($results[$i]['type']) . " - ";
                    }
                    echo"<a href='" . $results[$i]['link'] . "'>" . $myts->htmlSpecialChars($results[$i]['title'])
                        . "</a></b><br />&nbsp; " . $myts->undoHtmlSpecialChars($results[$i]['town']) . ", "
                        . $results[$i]['state'] . "<br />\n";

                    echo "<small>";
                    $results[$i]['uid'] = @intval($results[$i]['uid']);
                    if (!empty($results[$i]['uid'])) {
                        $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                        echo"&nbsp;&nbsp;<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $results[$i]['uid'] . "'>"
                            . $uname . "</a>\n";
                    }
                    echo !empty($results[$i]['time']) ? " (" . formatTimestamp(intval($results[$i]['time'])) . ")" : "";
                    echo "</small><br />\n";
                }
                if ($count >= 5) {
                    $search_url
                        =
                        XOOPS_URL . "/modules/jobs/search.php?query=" . urlencode(stripslashes(implode(' ', $queries)));
                    $search_url .= "&mid=$mid&action=showall&andor=$andor&is_resume=$is_resume&by_state=$by_state&by_cat=$by_cat&issearch=1";
                    echo '<br /><a href="' . htmlspecialchars($search_url) . '">' . _SR_SHOWALLR . '</a></p>';
                }
            }
        }
        unset($results);
        unset($module);
    }
    include 'include/searchform.php';
    $search_form->display();
    break;
case "showall":
case 'showallbyuser':
    include XOOPS_ROOT_PATH . "/header.php";
    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->get($mid);
    $results        =& $module->search($queries, $andor, 20, $start, $uid);
    $count          = count($results);
    if (is_array($results) && $count > 0) {
        $next_results =& $module->search($queries, $andor, 1, $start + 20, $uid, $is_resume);
        $next_count   = count($next_results);
        $has_next     = FALSE;
        if (is_array($next_results) && $next_count == 1) {
            $has_next = TRUE;
        }

        echo "<h4>" . _SR_SEARCHRESULTS . "</h4>\n";
        if ($action == 'showall') {
            echo _SR_KEYWORDS . ':';
            if ($andor != 'exact') {
                foreach ($queries as $q) {
                    echo ' <b>' . htmlspecialchars(stripslashes($q)) . '</b>';
                }
            } else {
                echo ' "<b>' . htmlspecialchars(stripslashes($queries[0])) . '</b>"';
            }
            echo '<br />';
        }
        printf(_SR_SHOWING, $start + 1, $start + $count);
        echo "<h5>" . $myts->htmlSpecialChars($module->getVar('name')) . "</h5>";

        if ($is_resume == 1) {
            echo "<h4>Resumes</h4>";
        }

        if (!empty($by_state)) {
            echo "" . _JOBS_INSTATE . "<b> $state_name</b><br /><br />";
        }

        if (!empty($by_cat)) {
            echo "" . _JOBS_INCATEGORY . "<b> $cat_name</b><br /><br />";
        }

        for ($i = 0; $i < $count; ++$i) {

            if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                $results[$i]['link'] = "modules/" . $module->getVar('dirname') . "/" . $results[$i]['link'];
            }
            if ($is_resume != "1") {
                echo"<br /><b>" . $myts->undoHtmlSpecialChars($results[$i]['company']) . " - "
                    . $myts->undoHtmlSpecialChars($results[$i]['type']) . " - ";
            }
            echo"<a href='" . $results[$i]['link'] . "'>" . $myts->htmlSpecialChars($results[$i]['title'])
                . "</a></b><br />&nbsp; " . $myts->undoHtmlSpecialChars($results[$i]['town']) . ", "
                . $myts->undoHtmlSpecialChars($results[$i]['state']) . "<br />\n";
            echo "<small>";
            $results[$i]['uid'] = @intval($results[$i]['uid']);
            if (!empty($results[$i]['uid'])) {
                $uname = XoopsUser::getUnameFromId($results[$i]['uid']);
                echo"&nbsp;&nbsp;<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $results[$i]['uid'] . "'>" . $uname
                    . "</a>\n";
            }
            echo !empty($results[$i]['time']) ? " (" . formatTimestamp(intval($results[$i]['time'])) . ")" : "";
            echo "</small><br />\n";
        }
        echo '<table><tr>';
        $search_url = XOOPS_URL . '/modules/jobs/search.php?query=' . urlencode(stripslashes(implode(' ', $queries)));
        $search_url .= "&mid=$mid&action=$action&andor=$andor";
        if ($action == 'showallbyuser') {
            $search_url .= "&uid=$uid";
        }
        if ($start > 0) {
            $prev = $start - 20;
            echo '<td align="left">
            ';
            $search_url_prev = $search_url . "&start=$prev";
            echo '<a href="' . htmlspecialchars($search_url_prev) . '">' . _SR_PREVIOUS . '</a></td>
            ';
        }
        echo '<td>&nbsp;&nbsp;</td>';
        if (FALSE != $has_next) {
            $next            = $start + 20;
            $search_url_next = $search_url . "&start=$next";
            echo '<td align="right"><a href="' . htmlspecialchars($search_url_next) . '">' . _SR_NEXT . '</a></td>
            ';
        }
        echo '</tr></table>';
    } else {

        if (!empty($by_state)) {
            echo "" . _JOBS_INSTATE . "<b> $state_name</b><br /><br />";
        }

        if (!empty($by_cat)) {
            echo "" . _JOBS_INCATEGORY . "<b> $cat_name</b><br /><br />";
        }

        echo '<p>' . _SR_NOMATCH . '</p>';
    }
    include 'include/searchform.php';
    $search_form->display();
    echo '</p>
    ';
    break;
}
include XOOPS_ROOT_PATH . "/footer.php";
