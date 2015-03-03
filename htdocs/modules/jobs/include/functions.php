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

function ExpireJob()
{
    global $xoopsDB, $xoopsConfig, $xoopsModuleConfig, $myts, $meta, $mydirname;

    $datenow = time();

    $result5 = $xoopsDB->query(
        "select lid, title, expire, type, company, desctext, requirements, contactinfo, date, email, submitter, usid, photo, view FROM "
            . $xoopsDB->prefix("jobs_listing") . " WHERE valid='1'"
    );

    while (list($lids, $title, $expire, $type, $company, $desctext, $requirements, $contactinfo, $dateann, $email, $submitter, $usid, $photo, $lu) = $xoopsDB->fetchRow($result5)) {
        $title        = $myts->addSlashes($title);
        $expire       = $myts->addSlashes($expire);
        $type         = $myts->addSlashes($type);
        $company      = $myts->addSlashes($company);
        $desctext     = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
        $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
        $contactinfo  = $myts->addSlashes($contactinfo);
        $submitter    = $myts->addSlashes($submitter);
        $usid         = intval($usid);

        $supprdate = $dateann + ($expire * 86400);
        if ($supprdate < $datenow) {
            $xoopsDB->queryF(
                "delete from " . $xoopsDB->prefix("jobs_listing") . " where lid=" . mysql_real_escape_string($lids) . ""
            );

            $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/logo_images";

            if ($photo) {
                if (file_exists("$destination/$photo")) {
                    unlink("$destination/$photo");
                }
            }

            $comp_id     = jobs_getCompIdFromName($company);
            $extra_users = jobs_getCompany($comp_id, $usid);

            $extra_user1 = $extra_users['comp_user1'];
            $extra_user2 = $extra_users['comp_user2'];

            if ($extra_user1) {
                $result = $xoopsDB->query("select email from " . $xoopsDB->prefix("users") . " where uid=$extra_user1");
                list($extra_user1_email) = $xoopsDB->fetchRow($result);
                $extra_user1_email = $extra_user1_email;
            } else {
                $extra_user1_email = "";
            }

            if ($extra_user2) {
                $result = $xoopsDB->query("select email from " . $xoopsDB->prefix("users") . " where uid=$extra_user2");
                list($extra_user2_email) = $xoopsDB->fetchRow($result);
                $extra_user2_email = $extra_user2_email;
            } else {
                $extra_user2_email = "";
            }

            if ($email) {
                $tags                = array();
                $tags['TITLE']       = $title;
                $tags['TYPE']        = $type;
                $tags['COMPANY']     = $company;
                $tags['DESCTEXT']    = $desctext;
                $tags['MY_SITENAME'] = $xoopsConfig['sitename'];
                $tags['REPLY_ON']    = _JOBS_REMINDANN;
                $tags['DESCRIPT']    = _JOBS_DESC;
                $tags['TO']          = _JOBS_TO;
                $tags['SUBMITTER']   = $submitter;
                $tags['EMAIL']       = _JOBS_EMAIL;
                $tags['HELLO']       = _JOBS_HELLO;
                $tags['YOUR_JOB']    = _JOBS_YOUR_JOB;
                $tags['THANKS']      = _JOBS_THANK;
                $tags['WEBMASTER']   = _JOBS_WEBMASTER;
                $tags['AT']          = _JOBS_AT;
                $tags['SENDER_IP']   = $_SERVER['REMOTE_ADDR'];
                $tags['HITS']        = $lu;
                $tags['TIMES']       = _JOBS_TIMES;
                $tags['VIEWED']      = _JOBS_VIEWED;
                $tags['ON']          = _JOBS_ON;
                $tags['EXPIRED']     = _JOBS_EXPIRED;

                $subject = "" . _JOBS_STOP2 . "" . _JOBS_STOP3 . "";
                $mail    =& xoops_getMailer();

                if (is_dir("language/" . $xoopsConfig['language'] . "/mail_template/")) {
                    $mail->setTemplateDir(
                        XOOPS_ROOT_PATH . "/modules/$mydirname/language/" . $xoopsConfig['language'] . "/mail_template/"
                    );
                } else {
                    $mail->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$mydirname/language/english/mail_template/");
                }

                $mail->setTemplate("jobs_listing_expired.tpl");
                $mail->useMail();
                $mail->setFromEmail($xoopsConfig['adminmail']);
                $mail->setToEmails(array($email, $extra_user1_email, $extra_user2_email));
                $mail->setSubject($subject);
                $mail->multimailer->isHTML(TRUE);
                $mail->assign($tags);
                $mail->send();
                echo $mail->getErrors();
            }
        }
    }
}

function jobs_getTotalItems($sel_id, $status = "")
{
    global $xoopsDB, $mytree, $mydirname;
    $categories = jobs_MygetItemIds("" . $mydirname . "_view");
    $count      = 0;
    $arr        = array();
    if (in_array($sel_id, $categories)) {
        $query
            = "select count(*) from " . $xoopsDB->prefix("" . $mydirname . "_listing") . " where cid=" . intval($sel_id)
            . " and valid='1' and status!='0'";

        $result = $xoopsDB->query($query);
        list($thing) = $xoopsDB->fetchRow($result);
        $count = $thing;
        $arr   = $mytree->getAllChildId($sel_id);
        $size  = count($arr);
        for ($i = 0; $i < $size; ++$i) {
            if (in_array($arr[$i], $categories)) {
                $query2 = "select count(*) from " . $xoopsDB->prefix("" . $mydirname . "_listing") . " where cid="
                    . intval($arr[$i]) . " and valid='1' and status!='0'";

                $result2 = $xoopsDB->query($query2);
                list($thing) = $xoopsDB->fetchRow($result2);
                $count += $thing;
            }
        }
    }

    return $count;
}

function JobsShowImg()
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
function jobs_convertorderbyin($orderby)
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
    case "townA":
        $orderby = "town ASC";
        break;
    case "stateA":
        $orderby = "state ASC";
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
    case "townD":
        $orderby = "town DESC";
        break;
    case "stateD":
        $orderby = "state DESC";
        break;
    case "dateD":
    default:
        $orderby = "date DESC";
        break;
    }

    return $orderby;
}

function jobs_convertorderbytrans($orderby)
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
    if ($orderby == "town ASC") {
        $orderbyTrans = "" . _JOBS_LOCALATOZ . "";
    }
    if ($orderby == "town DESC") {
        $orderbyTrans = "" . _JOBS_LOCALZTOA . "";
    }
    if ($orderby == "state ASC") {
        $orderbyTrans = "" . _JOBS_STATEATOZ . "";
    }
    if ($orderby == "state DESC") {
        $orderbyTrans = "" . _JOBS_STATEZTOA . "";
    }

    return $orderbyTrans;
}

function jobs_convertorderby($orderby)
{
    if ($orderby == "title ASC") {
        $orderby = "titleA";
    }
    if ($orderby == "date ASC") {
        $orderby = "dateA";
    }
    if ($orderby == "company ASC") {
        $orderby = "companyA";
    }
    if ($orderby == "town ASC") {
        $orderby = "townA";
    }
    if ($orderby == "state ASC") {
        $orderby = "stateA";
    }
    if ($orderby == "view ASC") {
        $orderby = "viewA";
    }
    if ($orderby == "title DESC") {
        $orderby = "titleD";
    }
    if ($orderby == "date DESC") {
        $orderby = "dateD";
    }
    if ($orderby == "company DESC") {
        $orderby = "companyD";
    }
    if ($orderby == "town DESC") {
        $orderby = "townD";
    }
    if ($orderby == "state DESC") {
        $orderby = "stateD";
    }
    if ($orderby == "view DESC") {
        $orderby = "viewD";
    }

    return $orderby;
}

function JobTableExists($tablename)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

function JobFieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

function JobAddField($field, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("ALTER TABLE " . $table . " ADD $field");

    return $result;
}

function jobs_getEditor($caption, $name, $value = "", $width = '99%', $height = '200px', $supplemental = '')
{
    global $xoopsModuleConfig;

    if ($xoopsModuleConfig['jobs_form_options'] == 'dhtmltextarea') {
        $nohtml = "1";
    } else {
        $nohtml = "0";
    }

    $editor_configs           = array();
    $editor_configs["name"]   = $name;
    $editor_configs["value"]  = $value;
    $editor_configs["rows"]   = 25;
    $editor_configs["cols"]   = 70;
    $editor_configs["width"]  = "95%";
    $editor_configs["height"] = "12%";
    $editor_configs["editor"] = strtolower($xoopsModuleConfig['jobs_form_options']);
    if (is_readable(XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php')) {
        require_once(XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php');
        $editor = new XoopsFormEditor($caption, $name, $editor_configs, $nohtml, $onfailure = 'textarea');

        return $editor;
    }
}

function jobs_getIdFromUname($uname)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $sql = "SELECT uid FROM " . $xoopsDB->prefix("users") . " WHERE uname = '$uname'";

    if (!$result = $xoopsDB->query($sql)) {
        return FALSE;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return FALSE;
    }

    $uid = $arr['uid'];

    return $uid;
}

function jobs_getCompCount($usid)
{
    global $xoopsDB, $xoopsUser;

    $sql    = "SELECT count(*) as count FROM " . $xoopsDB->prefix("jobs_companies") . " WHERE " . $usid
        . " IN (comp_usid, comp_user1, comp_user2)";
    $result = $xoopsDB->query($sql);
    if (!$result) {
        return 0;
        ;
    } else {
        list($count) = $xoopsDB->fetchRow($result);

        return $count;
    }
}

function jobs_getCompany($usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql
        =
        "SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM "
            . $xoopsDB->prefix("jobs_companies") . " WHERE " . $usid . " IN (comp_usid, comp_user1, comp_user2)";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $company = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $company = $row;
    }

    return $company;
}

function jobs_getPriceType()
{
    global $xoopsDB;
    $sql = "SELECT nom_type FROM " . $xoopsDB->prefix("jobs_price") . " ORDER BY nom_type";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    } else {
        $rows = array();
        while ($row = $xoopsDB->fetchArray($result)) {
            $rows[] = $row;
        }

        return ($rows);
    }
}

function jobs_MygetItemIds($permtype)
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

function jobs_getCatNameFromId($cid)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser, $mydirname;

    $sql = "SELECT title FROM " . $xoopsDB->prefix("jobs_categories") . " WHERE cid = '$cid'";

    if (!$result = $xoopsDB->query($sql)) {
        return FALSE;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return FALSE;
    }

    $title = $arr['title'];

    return $title;
}

function jobs_getStateNameFromId($rid)
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

function jobs_getCompIdFromName($name)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $sql = "SELECT comp_id FROM " . $xoopsDB->prefix("jobs_companies") . " WHERE comp_name = '$name'";

    if (!$result = $xoopsDB->query($sql)) {
        return FALSE;
    }
    if (!$arr = $xoopsDB->fetchArray($result)) {
        return FALSE;
    }

    $comp_id = $arr['comp_id'];

    return $comp_id;
}

function jobs_getCompanyWithListing($usid)
{
    global $xoopsDB, $xoopsUser;
    $sql
        =
        "SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM "
            . $xoopsDB->prefix("jobs_companies")
            . " WHERE comp_usid = '$usid' OR comp_user1 = '$usid' OR  comp_user2 = '$usid' order by comp_id";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $companies = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $companies = $row;
    }

    return $companies;
}

function jobs_getPremiumListings($cid)
{
    global $xoopsDB, $xoopsUser;

    $sql
        =
        "SELECT lid, cid, title, status, expire, type, company, price, typeprice, date, town, state, valid, premium, photo, view from "
            . $xoopsDB->prefix("jobs_listing") . " WHERE cid=" . mysql_real_escape_string($cid)
            . " AND valid='1' AND premium='1' AND status!='0' order by date";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $premium_listings = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $premium_listings = $row;
    }

    return $premium_listings;
}

function jobs_getAllCompanies()
{
    global $xoopsDB, $xoopsUser;
    $sql
        =
        "SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM "
            . $xoopsDB->prefix("jobs_companies") . "";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $companies = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $companies = $row;
    }

    return $companies;
}

function jobs_categorynewgraphic($cat)
{
    global $xoopsDB, $mydirname, $xoopsUser, $xoopsModuleConfig;

    $newresult = $xoopsDB->query(
        "select date from " . $xoopsDB->prefix("jobs_listing") . " where cid=" . mysql_real_escape_string($cat)
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

function jobs_subcatnew($cid)
{
    global $xoopsDB, $mydirname;

    $newresult = $xoopsDB->query(
        "select date from " . $xoopsDB->prefix("jobs_listing") . " where cid=" . mysql_real_escape_string($cid)
            . " and valid = '1' order by date desc limit 1"
    );
    list($timeann) = $xoopsDB->fetchRow($newresult);

    $count     = 1;
    $startdate = (time() - (86400 * $count));

    if ($startdate < $timeann) {
        return TRUE;
    }
}

function jobs_listingnewgraphic($date)
{
    global $xoopsDB, $mydirname, $xoopsModuleConfig;

    $days_new  = $xoopsModuleConfig['jobs_countday'];
    $startdate = (intval(time()) - (86400 * intval($days_new)));

    if ($startdate < $date) {
        return "<img src=\"" . XOOPS_URL . "/modules/$mydirname/images/newred.gif\" />";
    }
}

function jobs_getCompNameFromId($comp_id)
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $sql = "SELECT comp_name FROM " . $xoopsDB->prefix("jobs_companies") . " WHERE comp_id = '$comp_id'";

    if (!$result = $xoopsDB->query($sql)) {
        return FALSE;
    }

    if (!$arr = $xoopsDB->fetchArray($result)) {
        return FALSE;
    }

    $comp_name = $arr['comp_name'];

    return $comp_name;
}

function jobs_getCompanyUsers($comp_id = 0, $usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = "SELECT comp_id, comp_name, comp_usid, comp_user1, comp_user2 FROM " . $xoopsDB->prefix("jobs_companies")
        . " WHERE comp_id = '$comp_id' AND  " . $usid . " IN (comp_usid, comp_user1, comp_user2)";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $their_comp = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $their_comp = $row;
    }

    return $their_comp;
}

function jobs_getXtraUsers($comp_id = 0, $member_usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = "SELECT comp_id, comp_name, comp_user1, comp_user2 FROM " . $xoopsDB->prefix("jobs_companies")
        . " WHERE comp_id = '$comp_id' AND " . $member_usid . " IN (comp_user1, comp_user2)";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $xtra_users = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $xtra_users = $row;
    }

    return $xtra_users;
}

function jobs_getAllUserCompanies($member_usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql = "SELECT comp_id, comp_name, comp_usid, comp_user1, comp_user2 FROM " . $xoopsDB->prefix("jobs_companies")
        . " WHERE " . $member_usid . " IN (comp_usid, comp_user1, comp_user2)";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $xtra_users = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $xtra_users = $row;
    }

    return $xtra_users;
}

function jobs_getThisCompany($comp_id, $usid = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql
        =
        "SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM "
            . $xoopsDB->prefix("jobs_companies") . " WHERE comp_id = '$comp_id' AND " . $usid
            . " IN ( comp_usid, comp_user1, comp_user2)";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $thiscompany = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $thiscompany = $row;
    }

    return $thiscompany;
}

function jobs_getACompany($comp_id = 0)
{
    global $xoopsDB, $xoopsUser;
    $sql
        =
        "SELECT comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact FROM "
            . $xoopsDB->prefix("jobs_companies") . " WHERE comp_id='$comp_id'";
    if (!$result = $xoopsDB->query($sql)) {
        return 0;
    }
    $company = array();
    while ($row = $xoopsDB->fetchArray($result)) {
        $company = $row;
    }

    return $company;
}

function jobs_isX24plus()
{
    $x24plus = FALSE;
    $xv      = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) >= '4') {
        $x24plus = TRUE;
    }

    return $x24plus;
}

/**
* Do some basic file checks and stuff.
* Author: Andrew Mills  Email:  ajmills@sirium.net
* from amReviews module
*/
function jobs_filechecks()
{
global $xoopsModule, $xoopsConfig;

    echo "<fieldset>";
    echo "<legend style=\"color: #990000; font-weight: bold;\">" . _AM_JOBS_FILECHECKS . "</legend>";
/*
    $photodir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo";
    $photothumbdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo/thumbs";
    $photohighdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/photo/midsize";
    $cachedir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/resumes";
    $tmpdir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/rphoto";
    $logo_images_dir = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/logo_images";

    if (file_exists($photodir)) {
        if (!is_writable($photodir)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photodir . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $photodir . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $photodir . " does NOT exist!<br />";
    }
    // photothumbdir
    if (file_exists($photothumbdir)) {
        if (!is_writable($photothumbdir)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photothumbdir . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $photothumbdir . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $photothumbdir . " does NOT exist!<br />";
    }
    // photohighdir
    if (file_exists($photohighdir)) {
        if (!is_writable($photohighdir)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $photohighdir . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $photohighdir . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $photohighdir . " does NOT exist!<br />";
    }
    // cachedir
    if (file_exists($cachedir)) {
        if (!is_writable($cachedir)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $cachedir . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $cachedir . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $cachedir . " does NOT exist!<br />";
    }
    // tmpdir
    if (file_exists($tmpdir)) {
        if (!is_writable($tmpdir)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $tmpdir . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $tmpdir . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $tmpdir . " does NOT exist!<br />";
    }
    if(file_exists($logo_images_dir)) {
        if (!is_writable($logo_images_dir)) {
            echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> I am unable to write to: " . $logo_images_dir . "<br />";
        } else {
            echo "<span style=\" color: green; font-weight: bold;\">OK:</span> " . $logo_images_dir . "<br />";
        }
    } else {
        echo "<span style=\" color: red; font-weight: bold;\">Warning:</span> " . $logo_images_dir . " does NOT exist!<br />";
    }
*/

    /**
    * Some info.
    */
    $uploads = (ini_get('file_uploads')) ? _AM_JOBS_UPLOAD_ON : _AM_JOBS_UPLOAD_OFF;
//	echo "<br />";
    echo "<ul>";
    echo "<li>" . _AM_JOBS_UPLOADMAX ."<b>". ini_get('upload_max_filesize') . "</b></li>";
    echo "<li>" . _AM_JOBS_POSTMAX ."<b>". ini_get('post_max_size') . "</b></li>";
    echo "<li>" . _AM_JOBS_UPLOADS ."<b>". $uploads . "</b></li>";

    $gdinfo = gd_info();
    if (function_exists('gd_info')) {
        echo "<li>" . _AM_JOBS_GDIMGSPPRT  ."<b>". _AM_JOBS_GDIMGON ."</b></li>";
        echo "<li>". _AM_JOBS_GDIMGVRSN ."<b>". $gdinfo['GD Version'] . "</b></li>";
    } else {
        echo "<li>" . _AM_JOBS_GDIMGSPPRT  ."<b>". _AM_JOBS_GDIMGOFF ."</b></li>";
    }
    echo "</ul>";

    //$inithingy = ini_get_all();
    //print_r($inithingy);

    echo "</fieldset>";

} // end function

//----------------------------------------------------------------------------//

function jobs_summary()
{
global $xoopsDB;

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

    $summary = array();

    /**
    * As many of these will be "joined" at some point.
    */

    /**
       * Waiting JOB validation.
       */

//    $result2  = $xoopsDB->query(
//        "select lid, title, date from " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0' order by lid"
//    );

       $result = $xoopsDB->query("SELECT COUNT(lid) AS waitJobValidation FROM " .$xoopsDB->prefix('jobs_listing') . " WHERE valid='0'");
       list($waitJobValidation) = $xoopsDB->fetchRow($result);// {

           if ($waitJobValidation < 1) { $summary['waitJobValidation'] = "<span style=\"font-weight: bold;\">0</span>"; } else { $summary['waitJobValidation'] = "<span style=\"font-weight: bold; color: red;\">" . $waitJobValidation . "</span>"; }

//       $result1  = $xoopsDB->query("select lid, title, date from " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0' order by lid");
//       $numrows1 = $xoopsDB->getRowsNum($result1);
//

    /**
       * Waiting RESUME validation.
       */

//    $result2  = $xoopsDB->query(
//        "select lid, title, date from " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0' order by lid"
//    );

       $result = $xoopsDB->query("SELECT COUNT(lid) AS waitResumeValidation FROM " .$xoopsDB->prefix('jobs_resume') . " WHERE valid='0'");
       list($waitResumeValidation) = $xoopsDB->fetchRow($result);// {

           if ($waitResumeValidation < 1) { $summary['waitResumeValidation'] = "<span style=\"font-weight: bold;\">0</span>"; } else { $summary['waitResumeValidation'] = "<span style=\"font-weight: bold; color: red;\">" . $waitResumeValidation . "</span>"; }

//       $result1  = $xoopsDB->query("select lid, title, date from " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0' order by lid");
//       $numrows1 = $xoopsDB->getRowsNum($result1);

    /**
    * Jobs Published count (total)
    */

    $result = $xoopsDB->query("SELECT COUNT(lid) AS jobPublished FROM " .$xoopsDB->prefix('jobs_listing') . " WHERE valid='1'");
       list($jobPublished) = $xoopsDB->fetchRow($result);// {

     if (!$result) { $summary['jobPublished'] = 0; } else { $summary['jobPublished'] = "<span style=\"font-weight: bold; color: green;\">" .$jobPublished. "</span>"; }

    /**
    * Job Category count (total)
    */
    $result = $xoopsDB->query("SELECT COUNT(cid) AS jobCategoryCount FROM " .$xoopsDB->prefix('jobs_categories') . " ");
    list($jobCategoryCount) = $xoopsDB->fetchRow($result);// {

        if (!$result) { $summary['jobCategoryCount'] = 0; } else { $summary['jobCategoryCount'] = "<span style=\"font-weight: bold; color: green;\">" .$jobCategoryCount. "</span>"; }
    unset($result);

    /**
       * Resumes Published count (total)
       */

       $result = $xoopsDB->query("SELECT COUNT(lid) AS resumePublished FROM " .$xoopsDB->prefix('jobs_resume') . " WHERE valid='1'");
          list($resumePublished) = $xoopsDB->fetchRow($result);// {

        if (!$result) { $summary['resumePublished'] = 0; } else { $summary['resumePublished'] = "<span style=\"font-weight: bold; color: green;\">" .$resumePublished. "</span>"; }

    /**
       * Resume Category count (total)
       */
       $result = $xoopsDB->query("SELECT COUNT(cid) AS resumeCategoryCount FROM " .$xoopsDB->prefix('jobs_res_categories') . " ");
       list($resumeCategoryCount) = $xoopsDB->fetchRow($result);// {

           if (!$result) { $summary['resumeCategoryCount'] = 0; } else { $summary['resumeCategoryCount'] ="<span style=\"font-weight: bold; color: green;\">" . $resumeCategoryCount. "</span>"; }
       unset($result);

    /**
    * Company count (total)
    */
    $result = $xoopsDB->query("SELECT COUNT(comp_id) AS companies FROM " .$xoopsDB->prefix('jobs_companies') . " ");
    list($companies) = $xoopsDB->fetchRow($result);// {

        if (!$result) { $summary['companies'] = 0; } else { $summary['companies'] = "<span style=\"font-weight: bold; color: green;\">" .$companies. "</span>"; }
    unset($result);

    /**
    * Published (total)

    $result = $xoopsDB->query("SELECT count(id) AS published FROM " .$xoopsDB->prefix('amreview_reviews') . " WHERE showme='1' AND validated='1'");
    list($published) = $xoopsDB->fetchRow($result);// {

        if (!$result) { $summary['published'] = 0; } else { $summary['published'] = $published; }
    unset($result);

    * Hidden (total)

    $result = $xoopsDB->query("SELECT count(id) AS hidden FROM " .$xoopsDB->prefix('amreview_reviews') . " WHERE showme='0' OR validated='0'");
    list($hidden) = $xoopsDB->fetchRow($result);// {

        if (!$result) { $summary['hidden'] = 0; } else { $summary['hidden'] = $hidden; }
    unset($result);
*/

    //print_r($summary);
    return $summary;

} // end function

//----------------------------------------------------------------------------//
