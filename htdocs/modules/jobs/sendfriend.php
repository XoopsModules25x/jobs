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
include 'header.php';

if (empty($xoopsUser)) {
    redirect_header(XOOPS_URL . "/user.php", 2, _NOPERM);
    exit();
}

$mydirname = basename(dirname(__FILE__));
require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
include(XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");

function SendJob($lid = 0)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModuleConfig, $xoopsTheme, $xoopsLogger;

    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    $result = $xoopsDB->query(
        "select lid, title, type FROM " . $xoopsDB->prefix("jobs_listing") . " where lid="
            . mysql_real_escape_string($lid) . ""
    );
    list($lid, $title, $type) = $xoopsDB->fetchRow($result);

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo "<b>" . _JOBS_SENDTO . " " . $lid . " \"<b>$title </b>\" " . _JOBS_FRIEND . "<br /><br />
    <form action=\"sendfriend.php\" method=\"post\">
    <input type=\"hidden\" name=\"lid\" value=$lid>";
    if ($xoopsUser) {
        $idd  = $iddds = $xoopsUser->getVar("name", "E");
        $idde = $iddds = $xoopsUser->getVar("email", "E");
    }
    echo "<table width='100%' class='outer' cellspacing='1'>
    <tr>
    <td class='head' width='30%'>" . _JOBS_NAME . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"yname\" value=\"$idd\"></td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_MAIL . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"ymail\" value=\"$idde\"></td>
    </tr>
    <tr>
    <td colspan=2 class='even'>&nbsp;</td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_NAMEFR . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"fname\"></td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_MAILFR . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"fmail\"></td>
    </tr>";

    if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
        echo "<tr><td class='head'>" . _JOBS_CAPTCHA . " </td><td class='even'>";
        $jlm_captcha = "";
        $jlm_captcha = (new XoopsFormCaptcha(_JOBS_CAPTCHA, "xoopscaptcha", FALSE));
        echo $jlm_captcha->render();
        echo "</td></tr>";
    }
    echo "</table><br />
    <input type=\"hidden\" name=\"op\" value=\"MailJob\" />
    <input type=\"submit\" value=\"" . _JOBS_SENDFR . "\" />
    </form>     ";
    echo "</td></tr></table>";
}

function MailJob($lid = 0, $yname, $ymail, $fname, $fmail)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $myts, $xoopsLogger, $mydirname;

    if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
        $x24plus = jobs_isX24plus();
        if ($x24plus) {
            xoops_load("xoopscaptcha");
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                redirect_header(
                    XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname')
                        . "/index.php", 3, $xoopsCaptcha->getMessage()
                );
            }
        } else {
            xoops_load("captcha");
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                redirect_header(
                    XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname')
                        . "/index.php", 3, $xoopsCaptcha->getMessage()
                );
            }
        }
    }

    $result = $xoopsDB->query(
        "select lid, title, type, company, desctext, requirements, tel, price, typeprice, contactinfo, date, email, submitter, town, state, photo FROM "
            . $xoopsDB->prefix("jobs_listing") . " where lid=" . mysql_real_escape_string($lid) . ""
    );
    list($lid, $title, $type, $company, $desctext, $requirements, $tel, $price, $typeprice, $contactinfo, $date, $email, $submitter, $town, $state, $photo) = $xoopsDB->fetchRow($result);

    $title        = $myts->addSlashes($title);
    $type         = $myts->addSlashes($type);
    $company      = $myts->addSlashes($company);
    $desctext     = $myts->displayTarea($desctext, 1, 1, 1, 1, 1);
    $requirements = $myts->displayTarea($requirements, 1, 1, 1, 1, 1);
    $tel          = $myts->addSlashes($tel);
    $price        = $myts->addSlashes($price);
    $typeprice    = $myts->addSlashes($typeprice);
    $contactinfo  = $myts->addSlashes($contactinfo);
    $submitter    = $myts->addSlashes($submitter);
    $town         = $myts->addSlashes($town);
    $state        = $myts->addSlashes($state);

    $tags                       = array();
    $tags['YNAME']              = $yname;
    $tags['YMAIL']              = $ymail;
    $tags['FNAME']              = stripslashes($fname);
    $tags['FMAIL']              = $fmail;
    $tags['HELLO']              = _JOBS_HELLO;
    $tags['LID']                = $lid;
    $tags['LISTING_NUMBER']     = _JOBS_LISTING_NUMBER;
    $tags['TITLE']              = $title;
    $tags['TYPE']               = $type;
    $tags['DESCTEXT']           = $desctext;
    $tags['PRICE']              = $xoopsModuleConfig['jobs_money'] . " " . $price . "";
    $tags['TYPEPRICE']          = $typeprice;
    $tags['TEL']                = $tel;
    $tags['TOWN']               = $town;
    $tags['STATE']              = $state;
    $tags['OTHER']              = _JOBS_INTERESS . " " . $xoopsConfig['sitename'] . "";
    $tags['LISTINGS']           = "" . XOOPS_URL . "/modules/$mydirname/";
    $tags['LINK_URL']           = "" . XOOPS_URL . "/modules/$mydirname/viewjobs.php?lid=" . addslashes($lid) . "";
    $tags['THINKS_INTERESTING'] = _JOBS_MESSAGE;
    $tags['YOU_CAN_VIEW_BELOW'] = _JOBS_YOU_CAN_VIEW_BELOW;
    $tags['WEBMASTER']          = _JOBS_WEBMASTER;
    $tags['NO_REPLY']           = _JOBS_NOREPLY;
    $subject                    = _JOBS_SUBJET . "" . $xoopsConfig['sitename'] . "";

    $xoopsMailer =& xoops_getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$mydirname/language/english/mail_template/");
    $xoopsMailer->setTemplate("jobs_send_friend.tpl");
    $xoopsMailer->setFromEmail($ymail);
    $xoopsMailer->setToEmails($fmail);
    $xoopsMailer->setSubject($subject);
    $xoopsMailer->multimailer->isHTML(TRUE);
    $xoopsMailer->assign($tags);
    $xoopsMailer->send();
    echo $xoopsMailer->getErrors();

    redirect_header("index.php", 3, _JOBS_JOBSEND);
    exit();
}

function SendResume($lid = 0)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $xoopsTheme, $xoopsLogger;

    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    $result = $xoopsDB->query(
        "select lid, name, title FROM " . $xoopsDB->prefix("jobs_resume") . " where lid="
            . mysql_real_escape_string($lid) . ""
    );
    list($lid, $name, $title) = $xoopsDB->fetchRow($result);

    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo "<b>" . _JOBS_SENDTO . " " . $lid . " \"<b>$title </b>\" " . _JOBS_FRIEND . "<br /><br />
    <form action=\"sendfriend.php\" method=\"post\">
    <input type=\"hidden\" name=\"lid\" value=$lid>";
    if ($xoopsUser) {
        $idd  = $iddds = $xoopsUser->getVar("name", "E");
        $idde = $iddds = $xoopsUser->getVar("email", "E");
    }
    echo "<table width='100%' class='outer' cellspacing='1'>
    <tr>
    <td class='head' width='30%'>" . _JOBS_NAME . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"yname\" value=\"$idd\"></td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_MAIL . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"ymail\" value=\"$idde\"></td>
    </tr>
    <tr>
    <td colspan=2 class='even'>&nbsp;</td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_NAMEFR . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"fname\"></td>
    </tr>
    <tr>
    <td class='head'>" . _JOBS_MAILFR . " </td>
    <td class='even'><input class=\"textbox\" type=\"text\" name=\"fmail\"></td>
    </tr>";

    if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
        echo "<tr><td class='head'>" . _JOBS_CAPTCHA . " </td><td class='even'>";
        $jlm_captcha = "";
        $jlm_captcha = (new XoopsFormCaptcha(_JOBS_CAPTCHA, "xoopscaptcha", FALSE));
        echo $jlm_captcha->render();
        echo "</td></tr>";
    }

    echo "</table><br />
    <input type=\"hidden\" name=\"op\" value=\"MailResume\" />
    <input type=\"submit\" value=\"" . _JOBS_SENDFR . "\" />
    </form>     ";
    echo "</td></tr></table>";
}

function MailResume($lid = 0, $yname, $ymail, $fname, $fmail)
{
    global $xoopsConfig, $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $myts, $xoopsLogger, $mydirname;

    if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
        $x24plus = jobs_isX24plus();
        if ($x24plus) {
            xoops_load("xoopscaptcha");
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                redirect_header(
                    XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname')
                        . "/index.php", 3, $xoopsCaptcha->getMessage()
                );
            }
        } else {
            xoops_load("captcha");
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                redirect_header(
                    XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname')
                        . "/index.php", 3, $xoopsCaptcha->getMessage()
                );
            }
        }
    }

    $result = $xoopsDB->query(
        "select lid, cid, name, title, exp, expire, private, tel, salary, typeprice, date, email, submitter, usid, town, state, valid, resume, rphoto, view FROM "
            . $xoopsDB->prefix("jobs_resume") . " where lid=" . mysql_real_escape_string($lid) . ""
    );
    list($lid, $cid, $name, $title, $exp, $expire, $private, $tel, $salary, $typeprice, $date, $email, $submitter, $usid, $town, $state, $valid, $resume, $rphoto, $view) = $xoopsDB->fetchRow($result);

    $name      = $myts->addSlashes($name);
    $title     = $myts->addSlashes($title);
    $exp       = $myts->addSlashes($exp);
    $expire    = $myts->addSlashes($expire);
    $private   = $myts->addSlashes($private);
    $tel       = $myts->addSlashes($tel);
    $salary    = $myts->addSlashes($salary);
    $typeprice = $myts->addSlashes($typeprice);
    $submitter = $myts->addSlashes($submitter);
    $town      = $myts->addSlashes($town);
    $state     = $myts->addSlashes($state);

    $tags                       = array();
    $tags['YNAME']              = $yname;
    $tags['YMAIL']              = $ymail;
    $tags['FNAME']              = stripslashes($fname);
    $tags['FMAIL']              = $fmail;
    $tags['HELLO']              = _JOBS_HELLO;
    $tags['LID']                = $lid;
    $tags['LISTING_NUMBER']     = _JOBS_LISTING_NUMBER;
    $tags['TITLE']              = $title;
    $tags['NAME']               = $name;
    $tags['PRICE']              = $xoopsModuleConfig['jobs_money'] . " " . $salary . "";
    $tags['TYPEPRICE']          = $typeprice;
    $tags['TEL']                = $tel;
    $tags['TOWN']               = $town;
    $tags['STATE']              = $state;
    $tags['OTHER']              = _JOBS_RES_INTERESS . " " . $xoopsConfig['sitename'] . "";
    $tags['LISTINGS']           = "" . XOOPS_URL . "/modules/$mydirname/";
    $tags['LINK_URL']           = "" . XOOPS_URL . "/modules/$mydirname/viewresume.php?lid=" . addslashes($lid) . "";
    $tags['THINKS_INTERESTING'] = _JOBS_RES_MESSAGE;
    $tags['YOU_CAN_VIEW_BELOW'] = _JOBS_YOU_CAN_VIEW_BELOW;
    $tags['WEBMASTER']          = _JOBS_WEBMASTER;
    $tags['NO_REPLY']           = _JOBS_NOREPLY;
    $subject                    = _JOBS_RES_SUBJET . "" . $xoopsConfig['sitename'] . "";

    $xoopsMailer =& xoops_getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/$mydirname/language/english/mail_template/");
    $xoopsMailer->setTemplate("jobs_send_resume.tpl");
    $xoopsMailer->setFromEmail($ymail);
    $xoopsMailer->setToEmails($fmail);
    $xoopsMailer->setSubject($subject);
    $xoopsMailer->multimailer->isHTML(TRUE);
    $xoopsMailer->assign($tags);
    $xoopsMailer->send();
    echo $xoopsMailer->getErrors();

    redirect_header("index.php", 3, _JOBS_RESSEND);
    exit();
}

##############################################################
$yname = !empty($_POST['yname']) ? $myts->addSlashes($_POST['yname']) : "";
$ymail = !empty($_POST['ymail']) ? $myts->addSlashes($_POST['ymail']) : "";
$fname = !empty($_POST['fname']) ? $myts->addSlashes($_POST['fname']) : "";
$fmail = !empty($_POST['fmail']) ? $myts->addSlashes($_POST['fmail']) : "";

if (!isset($_POST['lid']) && isset($_GET['lid'])) {
    $lid = intval($_GET['lid']);
} else {
    $lid = intval($_POST['lid']);
}

$op = '';
if (!empty($_GET['op'])) {
    $op = $_GET['op'];
} elseif (!empty($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {

case "SendJob":
    include(XOOPS_ROOT_PATH . "/header.php");
    SendJob($lid);
    include(XOOPS_ROOT_PATH . "/footer.php");
    break;

case "SendResume":
    include(XOOPS_ROOT_PATH . "/header.php");
    SendResume($lid);
    include(XOOPS_ROOT_PATH . "/footer.php");
    break;

case "MailJob":
    MailJob($lid, $yname, $ymail, $fname, $fmail);
    break;

case "MailResume":
    MailResume($lid, $yname, $ymail, $fname, $fmail);
    break;

default:
    redirect_header("index.php", 3, "" . _RETURN . "");
    break;

}
