<?php
//  -----------------------------------------------------------------------  //
//                           Jobs for Xoops 2.4.x                            //
//                  By John Mordo from the myAds 2.04 Module                 //
//                    All Original credits left below this                   //
//                                                                           //
//                                                                           //
//                                                                           //
//                                                                           //
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller                                    //
// Author Website : pascal.e-xoops@perso-search.com                          //
// Licence Type   : GPL                                                      //
// ------------------------------------------------------------------------- //

$mydirname = basename(dirname(dirname(__FILE__)));

require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");
include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/resume_functions.php");
$is_resume = !isset($_REQUEST['is_resume']) ? NULL : $_REQUEST['is_resume'];

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

function jobs_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser, $is_resume, $by_state, $by_cat, $state_name;

    if ($is_resume != 1) {

        $cat_perms  = "";
        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $cat_perms = ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        $sql =
            "SELECT  lid,cid,title,type,company,desctext,requirements,tel,price,contactinfo,town,state,usid,valid,date FROM "
                . $xoopsDB->prefix("jobs_listing") . " WHERE valid='1'  AND date<=" . time() . "$cat_perms";

        if ($userid != 0) {
            $sql .= " AND usid=" . $userid . " ";
        }

        if (($by_state != "") && ($by_cat != "")) {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((cid LIKE '$by_cat' AND state LIKE '$by_state')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(cid LIKE '$by_cat' AND state LIKE '$by_state')";
                }
                $sql .= ") ";
            }

        } elseif ($by_state != "") {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((state LIKE '$by_state')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(state LIKE '$by_state')";
                }
                $sql .= ") ";
            }

        } elseif ($by_cat != "") {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((cid LIKE '$by_cat')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(cid LIKE '$by_cat')";
                }
                $sql .= ") ";
            }

        } else {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((title LIKE '%$queryarray[0]%' OR type LIKE '%$queryarray[0]%' OR company LIKE '%$queryarray[0]%' OR desctext LIKE '%$queryarray[0]%' OR requirements LIKE '%$queryarray[0]%' OR tel LIKE '%$queryarray[0]%' OR price LIKE '%$queryarray[0]%' OR contactinfo LIKE '%$queryarray[0]%' OR town LIKE '%$queryarray[0]%' OR state LIKE '%$queryarray[0]%')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(title LIKE '%$queryarray[i]%' OR type LIKE '%$queryarray[i]%' OR company LIKE '%$queryarray[i]%' OR desctext LIKE '%$queryarray[i]%' OR requirements LIKE '%$queryarray[i]%' OR tel LIKE '%$queryarray[i]%' OR price LIKE '%$queryarray[i]%' OR contactinfo LIKE '%$queryarray[i]%' OR town LIKE '%$queryarray[i]%' OR state LIKE '%$queryarray[i]%')";
                }
                $sql .= ") ";
            }
        }
        $sql .= "ORDER BY date DESC";
        $result = $xoopsDB->query($sql, $limit, $offset);
        $ret    = array();
        $i      = 0;
        while ($myrow = $xoopsDB->fetchArray($result)) {

            $statename = jobs_getStateNameFromId($myrow['state']);

            $ret[$i]['image']   = "images/cat/default.gif";
            $ret[$i]['link']    = "viewjobs.php?lid=" . $myrow['lid'] . "";
            $ret[$i]['title']   = $myrow['title'];
            $ret[$i]['company'] = $myrow['company'];
            $ret[$i]['type']    = $myrow['type'];
            $ret[$i]['town']    = $myrow['town'];
            $ret[$i]['state']   = $statename;
            $ret[$i]['time']    = $myrow['date'];
            $ret[$i]['uid']     = $myrow['usid'];
            ++$i;
        }

    } else {

        $rescat_perms  = "";
        $rescategories = resume_MygetItemIds("resume_view");
        if (is_array($rescategories) && count($rescategories) > 0) {
            $rescat_perms = ' AND cid IN (' . implode(',', $rescategories) . ') ';
        }

        $sql =
            "SELECT lid, cid, name, title, exp, expire, private, salary, typeprice, date, usid, town, state, valid FROM "
                . $xoopsDB->prefix("jobs_resume") . " WHERE valid='1' and date<=" . time() . " $rescat_perms";

        if ($userid != 0) {
            $sql .= " AND usid=" . $userid . " ";
        }

        if (($by_state != "") && ($by_cat != "")) {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((cid LIKE '$by_cat' AND state LIKE '$by_state')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(cid LIKE '$by_cat' AND state LIKE '$by_state')";
                }
                $sql .= ") ";
            }

        } elseif ($by_state != "") {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((state LIKE '$by_state')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(state LIKE '$by_state')";
                }
                $sql .= ") ";
            }

        } elseif ($by_cat != "") {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((cid LIKE '$by_cat')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(cid LIKE '$by_cat')";
                }
                $sql .= ") ";
            }

        } else {

            // because count() returns 1 even if a supplied variable
            // is not an array, we must check if $querryarray is really an array
            if (is_array($queryarray) && $count = count($queryarray)) {
                $sql .= " AND ((title LIKE '%$queryarray[0]%' OR town LIKE '%$queryarray[0]%' OR state LIKE '%$queryarray[0]%')";
                for ($i = 1; $i < $count; ++$i) {
                    $sql .= " $andor ";
                    $sql .= "(title LIKE '%$queryarray[i]%' OR town LIKE '%$queryarray[i]%' OR state LIKE '%$queryarray[i]%')";
                }
                $sql .= ") ";
            }
        }
        $sql .= "ORDER BY date DESC";
        $result = $xoopsDB->query($sql, $limit, $offset);
        $ret    = array();
        $i      = 0;
        while ($myrow = $xoopsDB->fetchArray($result)) {

            $statename = resume_getStateNameFromId($myrow['state']);

            $ret[$i]['image'] = "images/cat/default.gif";
            $ret[$i]['link']  = "viewresume.php?lid=" . $myrow['lid'] . "";
            $ret[$i]['title'] = $myrow['title'];
            $ret[$i]['town']  = $myrow['town'];
            $ret[$i]['state'] = $statename;
            $ret[$i]['time']  = $myrow['date'];
            $ret[$i]['uid']   = $myrow['usid'];
            ++$i;
        }
    }

    return $ret;

}
