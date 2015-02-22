<?php
//  -----------------------------------------------------------------------  //
//                           Jobs for Xoops 2.4.x                             //
//                  By John Mordo from the myAds 2.04 Module                 //
//                    All Original credits left below this                   //
//                                                                           //
// _________________________________________________________________________ //
//                                                                           //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller                                    //
// Author Website : pascal.e-xoops@perso-search.com                          //
// Licence Type   : GPL                                                      //
// ------------------------------------------------------------------------- //
if (isset($_POST['submit'])) {
    // Define Variables for register_globals Off
    $id        = !isset($_REQUEST['id']) ? NULL : $_REQUEST['id'];
    $date      = !isset($_REQUEST['date']) ? NULL : $_REQUEST['date'];
    $namep     = !isset($_REQUEST['namep']) ? NULL : $_REQUEST['namep'];
    $ipnumber  = !isset($_REQUEST['ipnumber']) ? NULL : $_REQUEST['ipnumber'];
    $message   = !isset($_REQUEST['message']) ? NULL : $_REQUEST['message'];
    $typeprice = !isset($_REQUEST['typeprice']) ? NULL : $_REQUEST['typeprice'];
    $price     = !isset($_REQUEST['price']) ? NULL : $_REQUEST['price'];
    $private   = !isset($_REQUEST['private']) ? NULL : $_REQUEST['private'];
    $messtext  = !isset($_REQUEST['messtext']) ? NULL : $_REQUEST['messtext'];
    $tele      = !isset($_REQUEST['tele']) ? NULL : $_REQUEST['tele'];
    $post      = !isset($_REQUEST['post']) ? NULL : $_REQUEST['post'];
    $resume    = !isset($_REQUEST['resume']) ? NULL : $_REQUEST['resume'];
    $company   = !isset($_REQUEST['company']) ? NULL : $_REQUEST['company'];
    $listing   = !isset($_REQUEST['listing']) ? NULL : $_REQUEST['listing'];
    // end define vars

    include 'header.php';

    $mydirname = basename(dirname(__FILE__));

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
    if (!$gperm_handler->checkRight("" . $mydirname . "_view", $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
        exit();
    }

    require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");

    global $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $myts, $mydirname;

    if (!$xoopsGTicket->check(TRUE, 'token')) {
        redirect_header(
            XOOPS_URL . "/modules/$mydirname/viewresume.php?lid=" . addslashes($id) . "", 3, $xoopsGTicket->getErrors()
        );
    }

//  if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
//	$x24plus = jobs_isX24plus();
//	if ($x24plus) {
//	xoops_load("xoopscaptcha");
//	$xoopsCaptcha = XoopsCaptcha::getInstance();
//	if ( !$xoopsCaptcha->verify() ) {
    //       redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, $xoopsCaptcha->getMessage() );
//	}
//	} else {
//	xoops_load("captcha");
//	$xoopsCaptcha = XoopsCaptcha::getInstance();
//	if ( !$xoopsCaptcha->verify() ) {
//        redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, $xoopsCaptcha->getMessage() );
//	}
//	}
//	}
    $result = $xoopsDB->query(
        "select name, email, submitter, title FROM  " . $xoopsDB->prefix("jobs_resume") . " WHERE lid = "
            . mysql_real_escape_string($id) . ""
    );

    while (list($name, $email, $submitter, $title) = $xoopsDB->fetchRow($result)) {
        $name      = $myts->addSlashes($name);
        $title     = $myts->addSlashes($title);
        $submitter = $myts->addSlashes($submitter);

        $message .= "$name " . _JOBS_RES_REPLY . " " . _JOBS_FROMANNOF . " " . $xoopsConfig['sitename'] . "\n\n";
        $message .= "" . _JOBS_MESSFROM . " $namep " . _JOBS_FOR . " $company \n\n";
        $message .= "\n";
        $message .= stripslashes("$messtext\n\n");
        $message .= "   " . _JOBS_ENDMESS . "\n\n";
        if ($listing != "0") {
            $message .= "" . _JOBS_RES_LISTING . "\n\n";
            $message .= "$listing\n\n";
        }
        $message .= "" . _JOBS_CANJOINT . " $namep " . _JOBS_TO . " $post " . _JOBS_ORAT . " $tele \n\n";
        $message .= "End of message \n\n";

        $subject = "" . _JOBS_RES_CONTACTAFTER . "";
        $mail    =& xoops_getMailer();
        $mail->useMail();
        $mail->setFromEmail($post);
        $mail->setToEmails($email);
        $mail->setSubject($subject);
        $mail->setBody($message);
        $mail->send();
        echo $mail->getErrors();

        if ($xoopsModuleConfig['jobs_admin_mail'] = 1) {

            $message .= "\n" . $_SERVER['REMOTE_ADDR'] . "\n";
            $adsubject   = $xoopsConfig['sitename'] . " Job Reply ";
            $xoopsMailer =& xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
            $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
            $xoopsMailer->setFromName($xoopsConfig['sitename']);
            $xoopsMailer->setSubject($adsubject);
            $xoopsMailer->setBody($message);
            $xoopsMailer->send();
        }
    }
    redirect_header("resumes.php", 3, _JOBS_MESSEND);
    exit();

} else {

    $lid = isset($_GET['lid']) ? intval($_GET['lid']) : '';

    include 'header.php';

    $mydirname = basename(dirname(__FILE__));
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
    if (!$gperm_handler->checkRight("" . $mydirname . "_view", $perm_itemid, $groups, $module_id)) {
        redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
        exit();
    }

    require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    global $xoopsConfig, $xoopsUser, $xoopsDB, $myts, $mydirname;

    include(XOOPS_ROOT_PATH . "/header.php");
    echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
    echo "<script type=\"text/javascript\">
          function verify()
          {
                var msg = \"" . _JOBS_VALIDERORMSG . "\\n__________________________________________________\\n\\n\";
                var errors = \"FALSE\";

                if (document.cont.namep.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"" . _JOBS_VALIDSUBMITTER . "\\n\";
                }
                if (document.cont.post.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"" . _JOBS_VALIDEMAIL . "\\n\";
                }
                if (document.cont.messtext.value == \"\") {
                        errors = \"TRUE\";
                        msg += \"" . _JOBS_VALIDMESS . "\\n\";
                }
                if (errors == \"TRUE\") {
                        msg += \"__________________________________________________\\n\\n" . _JOBS_VALIDMSG . "\\n\";
                        alert(msg);

                        return false;
                }
          }
          </script>";

    if ($xoopsUser) {
        echo "<b>" . _JOBS_RES_CONTACTHEAD . "</b><br /><br />";
        echo "" . _JOBS_RES_TOREPLY . "<br />";
        echo "<form onsubmit=\"return verify();\" method=\"post\" action=\"contactresume.php\" name=\"cont\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"$lid\" />";
        echo "<input type=\"hidden\" name=\"submit\" value=\"1\" />";

        $idd  = $xoopsUser->getVar("uname", "E");
        $idde = $xoopsUser->getVar("email", "E");

        $result1 = $xoopsDB->query(
            "select lid, cid, title, company, email, submitter FROM  " . $xoopsDB->prefix("jobs_listing")
                . " WHERE email = '$idde' and lid = " . mysql_real_escape_string($lid) . ""
        );
        list($lid, $cid, $title, $company, $email, $submitter) = $xoopsDB->fetchRow($result1);

        $title = $myts->addSlashes($title);

        echo "<table width='100%' class='outer' cellspacing='1'>
    <tr>
      <td class='head'>" . _JOBS_RES_NAME . "</td>
      <td class='even'><input type=\"text\" name=\"namep\" size=\"40\" /></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_COMPANY . "</td>
      <td class='even'><input type=\"text\" name=\"company\" size=\"40\" /></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_RES_UNAME . "</td>
      <td class='even'>$idd</td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOUREMAIL . "</td>
      <td class='even'><input type=\"text\" name=\"post\" size=\"40\" value=\"$idde\" /></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_YOURPHONE . "</td>
      <td class='even'><input type=\"text\" name=\"tele\" size=\"40\" /></td>
    </tr>
    <tr>
      <td class='head'>" . _JOBS_RES_YOURMESSAGE . "</td>
      <td class='even'><textarea rows=\"5\" name=\"messtext\" cols=\"40\"></textarea></td>
    </tr>";
        if ($result1 >= 1) {
            echo "<tr>
      <td class='head'>" . _JOBS_YOURLISTING . "</td>
      <td class='odd'><select name=\"listing\"><option value=\"0\">" . _JOBS_RES_JOBSELECT . "</option>";

            $dropdown = $xoopsDB->query(
                "select lid, title, date, email FROM  " . $xoopsDB->prefix("jobs_listing")
                    . " WHERE email = '$idde' ORDER BY date DESC"
            );
            while (list($lid, $title, $date, $email) = $xoopsDB->fetchRow($dropdown)) {
                echo"<option value=\"" . XOOPS_URL . "/modules/" . $mydirname . "/viewjobs.php?lid=$lid\">" . $title
                    . "</option>";
            }
            echo "</select></td></tr>";
        }

//	if ($xoopsModuleConfig["jobs_use_captcha"] == '1') {
//		echo "<tr><td class='head'>"._JOBS_CAPTCHA." </td><td class='even'>";
//	$jlm_captcha = "";
//	$jlm_captcha = (new XoopsFormCaptcha(_JOBS_CAPTCHA, "xoopscaptcha", false));
//	echo $jlm_captcha->render();
//	}
        echo "</td></tr></table>
    <table class='outer'><tr><td>" . _JOBS_YOUR_IP . "&nbsp;
        <img src=\"" . XOOPS_URL . "/modules/$mydirname/ip_image.php\" alt=\"\" /><br />" . _JOBS_IP_LOGGED . "
        </td></tr></table>
    <br />
      <p><input type=\"submit\" name=\"submit\" value=\"" . _JOBS_SENDFR . "\" /></p>"
            . $GLOBALS['xoopsGTicket']->getTicketHtml(__LINE__, 1800, 'token') . "
    </form>";
    }
    echo "</td></tr></table>";
    include(XOOPS_ROOT_PATH . "/footer.php");
}
