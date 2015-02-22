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

function ExpireResume()
{
    global $xoopsDB, $xoopsConfig, $xoopsModuleConfig, $myts, $meta, $mydirname;

    $datenow = time();

    $result5 = $xoopsDB->query(
        "select lid, name, title, expire, private, date, email, submitter, resume, view FROM "
            . $xoopsDB->prefix("jobs_resume") . " WHERE valid='1'"
    );

    while (list($lids, $name, $title, $expire, $private, $dateann, $email, $submitter, $resume, $lu) = $xoopsDB->fetchRow($result5)) {

        $name      = $myts->htmlSpecialChars($name);
        $title     = $myts->htmlSpecialChars($title);
        $expire    = $myts->htmlSpecialChars($expire);
        $private   = $myts->htmlSpecialChars($private);
        $submitter = $myts->htmlSpecialChars($submitter);
        $supprdate = $dateann + ($expire * 86400);
        if ($supprdate < $datenow) {
            $xoopsDB->queryF(
                "delete from " . $xoopsDB->prefix("jobs_resume") . " where lid=" . mysql_real_escape_string($lids) . ""
            );

            $destination  = XOOPS_ROOT_PATH . "/modules/$mydirname/resumes";
            $destination2 = XOOPS_ROOT_PATH . "/modules/$mydirname/rphoto";

            if ($resume) {
                if (file_exists("$destination/$resume")) {
                    unlink("$destination/$resume");
                }
            }

            if ($email) {
                $message = "$submitter " . _JOBS_HELLO . " \n\n" . _JOBS_STOP2 . "\n $type : $title\n $desctext\n"
                    . _JOBS_STOP3 . "\n\n" . _JOBS_VU . " $lu " . _JOBS_VU2 . "\n\n" . _JOBS_OTHER . " " . XOOPS_URL
                    . "/modules/jobs\n\n" . _JOBS_THANK . "\n\n" . _JOBS_TEAM . " " . $meta['title'] . "\n" . XOOPS_URL
                    . "";
                $subject = "" . _JOBS_STOP . "";
                $mail    =& getMailer();
                $mail->useMail();
                $mail->setFromName($meta['title']);
                $mail->setFromEmail($xoopsConfig['adminmail']);
                $mail->setToEmails($email);
                $mail->setSubject($subject);
                $mail->setBody($message);
                $mail->send();
                echo $mail->getErrors();
            }
        }
    }
}

function resume_getTotalResumes($sel_id, $status = "")
{
    global $xoopsDB, $mytree, $mydirname;
    $categories = resume_MygetItemIds("resume_view");
    $count      = 0;
    $arr        = array();
    if (in_array($sel_id, $categories)) {
        $query
            = "select count(*) from " . $xoopsDB->prefix("" . $mydirname . "_resume") . " where cid=" . intval($sel_id)
            . " and valid='1' and status!='0'";

        $result = $xoopsDB->query($query);
        list($thing) = $xoopsDB->fetchRow($result);
        $count = $thing;
        $arr   = $mytree->resume_getAllChildId($sel_id);
        $size  = count($arr);
        for ($i = 0; $i < $size; ++$i) {
            if (in_array($arr[$i], $categories)) {
                $query2 = "select count(*) from " . $xoopsDB->prefix("" . $mydirname . "_resume") . " where cid="
                    . intval($arr[$i]) . " and valid='1' and status!='0'";

                $result2 = $xoopsDB->query($query2);
                list($thing) = $xoopsDB->fetchRow($result2);
                $count += $thing;
            }
        }
    }

    return $count;
}

function resume_ShowResImg()
{
    global $mydirname;

    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n\n";
    echo "function showimage() {\n";
    echo "if (!document.images)\n";
    echo "return\n";
    echo "document.images.avatar.src=\n";
    echo"'" . XOOPS_URL
        . "/modules/$mydirname/images/cat/' + document.imcat.img.options[document.imcat.img.selectedIndex].value\n";
    echo "}\n\n";
    echo "//-->\n";
    echo "</script>\n";
}

//Reusable Link Sorting Functions
function resume_convertorderbyin($orderby)
{
    switch (trim($orderby)) {
    case "titleA":
        $orderby = "title ASC";
        break;
    case "dateA":
        $orderby = "date ASC";
        break;
    case "viewA":
        $orderby = "view ASC";
        break;
    case "companyA":
        $orderby = "company ASC";
        break;
    case "expA":
        $orderby = "exp ASC";
        break;
    case "titleD":
        $orderby = "title DESC";
        break;
    case "viewD":
        $orderby = "view DESC";
        break;
    case "companyD":
        $orderby = "company DESC";
        break;
    case "expD":
        $orderby = "exp DESC";
        break;
    case "dateD":
    default:
        $orderby = "date DESC";
        break;
    }

    return $orderby;
}

function resume_convertorderbytrans($orderby)
{
    if ($orderby == "view ASC") {
        $orderbyTrans = "" . _JOBS_POPULARITYLTOM . "";
    }
    if ($orderby == "view DESC") {
        $orderbyTrans = "" . _JOBS_POPULARITYMTOL . "";
    }
    if ($orderby == "title ASC") {
        $orderbyTrans = "" . _JOBS_TITLEATOZ . "";
    }
    if ($orderby == "title DESC") {
        $orderbyTrans = "" . _JOBS_TITLEZTOA . "";
    }
    if ($orderby == "date ASC") {
        $orderbyTrans = "" . _JOBS_DATEOLD . "";
    }
    if ($orderby == "date DESC") {
        $orderbyTrans = "" . _JOBS_DATENEW . "";
    }
    if ($orderby == "company ASC") {
        $orderbyTrans = "" . _JOBS_COMPANYATOZ . "";
    }
    if ($orderby == "company DESC") {
        $orderbyTrans = "" . _JOBS_COMPANYZTOA . "";
    }
    if ($orderby == "exp ASC") {
        $orderbyTrans = "" . _JOBS_EXPLTOH . "";
    }
    if ($orderby == "exp DESC") {
        $orderbyTrans = "" . _JOBS_EXPHTOL . "";
    }

    return $orderbyTrans;
}

function resume_convertorderbyout($orderby)
{
    if ($orderby == "title ASC") {
        $orderby = "titleA";
    }
    if ($orderby == "date ASC") {
        $orderby = "dateA";
    }
    if ($orderby == "view ASC") {
        $orderby = "viewA";
    }
    if ($orderby == "company ASC") {
        $orderby = "companyA";
    }
    if ($orderby == "exp ASC") {
        $orderby = "expA";
    }
    if ($orderby == "title DESC") {
        $orderby = "titleD";
    }
    if ($orderby == "date DESC") {
        $orderby = "dateD";
    }
    if ($orderby == "view DESC") {
        $orderby = "viewD";
    }
    if ($orderby == "company DESC") {
        $orderby = "companyD";
    }
    if ($orderby == "exp DESC") {
        $orderby = "expD";
    }
}

function resume_getEditor($caption, $name, $value = "", $width = '99%', $height = '200px', $supplemental = '')
{
    global $xoopsModuleConfig;

    $editor_configs           = array();
    $editor_configs["name"]   = $name;
    $editor_configs["value"]  = $value;
    $editor_configs["rows"]   = 25;
    $editor_configs["cols"]   = 70;
    $editor_configs["width"]  = "95%";
    $editor_configs["height"] = "12%";
    $editor_configs["editor"] = strtolower($xoopsModuleConfig['jobs_resume_options']);
    if (is_readable(XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php')) {
        require_once(XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php');
        $editor = new XoopsFormEditor($caption, $name, $editor_configs, $nohtml = FALSE, $onfailure = 'textarea');

        return $editor;
    }
}

function resume_getIdFromUname($uname)
{
    global $xoopsConfig, $myts, $db;

    $sql = "SELECT uid FROM " . $db->prefix("users") . " WHERE uname = '$uname'";

    if (!$result = $db->query($sql)) {
        return FALSE;
    }
    if (!$arr = $db->fetch_array($result)) {
        return FALSE;
    }
    $uid = $arr['uid'];

    return $uid;
}

function resume_getResumeCount($usid)
{
    global $xoopsDB, $xoopsUser;

    $sql = "SELECT count(*) as count FROM " . $xoopsDB->prefix("jobs_resume") . " WHERE usid = '$usid'";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    } else {
        $row = $xoopsDB->fetchArray($result);

        return ($row);
    }
}

function resume_getResume($usid)
{
    global $xoopsDB, $xoopsUser;
    $sql
        =
        "SELECT lid, cid, name, title, status, exp, expire, private, tel, salary, typeprice, date, email, submitter, usid, town, state, valid, rphoto, resume, view FROM "
            . $xoopsDB->prefix("jobs_resume") . " WHERE usid = " . mysql_real_escape_string($usid) . "";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $resume = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $resume = $row;
    }

    return $resume;
}

function resume_MygetItemIds($permtype)
{
    global $xoopsUser, $mydirname;
    static $permissions = array();
    if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
        return $permissions[$permtype];
    }

    $module_handler         =& xoops_gethandler('module');
    $myModule               =& $module_handler->getByDirname("jobs");
    $groups                 = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler          =& xoops_gethandler('groupperm');
    $categories             = $gperm_handler->getItemIds($permtype, $groups, $myModule->getVar('mid'));
    $permissions[$permtype] = $categories;

    return $categories;
}

function resume_getResCatNameFromId($cid)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser, $mydirname;

    $sql = "SELECT title FROM " . $xoopsDB->prefix("jobs_res_categories") . " WHERE cid = '$cid'";

    if (!$result = $xoopsDB->query($sql)) {
        return FALSE;
    }
    if (!$arr = $xoopsDB->fetchArray($result)) {
        return FALSE;
    }
    $title = $arr['title'];

    return $title;
}

function resume_getStateNameFromId($rid)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser, $mydirname;

    $sql = "SELECT name FROM " . $xoopsDB->prefix("jobs_region") . " WHERE rid = '$rid'";

    if (!$result = $xoopsDB->query($sql)) {
        return FALSE;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return FALSE;
    }

    $name = $arr['name'];

    return $name;
}

/**
 *  function categorynewgraphic
 **/
function resume_categorynewgraphic($cat)
{
    global $xoopsDB, $mydirname, $xoopsModuleConfig, $xoopsUser;

    $newresult = $xoopsDB->query(
        "select date from " . $xoopsDB->prefix("jobs_resume") . " where cid=" . mysql_real_escape_string($cat)
            . " and valid = '1' order by date desc limit 1"
    );
    list($date) = $xoopsDB->fetchRow($newresult);

    $useroffset = "";
    if ($xoopsUser) {
        $timezone = $xoopsUser->timezone();
        if (isset($timezone)) {
            $useroffset = $xoopsUser->timezone();
        } else {
            $useroffset = $xoopsConfig['default_TZ'];
        }
    }
    $date = ($useroffset * 3600) + $date;

    $days_new  = $xoopsModuleConfig['jobs_countday'];
    $startdate = (time() - (86400 * $days_new));

    if ($startdate < $date) {
        return "<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/newred.gif\" />";
    }
}

function resume_listingnewgraphic($date)
{
    global $xoopsDB, $mydirname, $xoopsModuleConfig;

    $days_new  = $xoopsModuleConfig['jobs_countday'];
    $startdate = (intval(time()) - (86400 * intval($days_new)));

    if ($startdate < $date) {
        return "<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/newred.gif\" />";
    }
}

function resume_isX24plus()
{
    $x24plus = FALSE;
    $xv      = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) >= '4') {
        $x24plus = TRUE;
    }

    return $x24plus;
}
