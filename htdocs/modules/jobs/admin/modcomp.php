<?php
//                 Jobs for Xoops 2.4.4 up  by John Mordo - jlm69 at Xoops                   //
//                                                                                           //
include 'admin_header.php';
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$myts =& MyTextSanitizer::getInstance();

$comp_id     = !isset($_REQUEST['comp_id']) ? NULL : $_REQUEST['comp_id'];
$member_usid = $xoopsUser->uid();

if (!empty($_POST['del_old'])) {
    $del_old = TRUE;
} else {
    $del_old = FALSE;
}

if (!empty($_POST['submit'])) {

    if (!$GLOBALS['xoopsSecurity']->check(TRUE, $_REQUEST['token'])) {
        redirect_header(
            XOOPS_URL . "/modules/$mydirname/index.php", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())
        );
    }
    if (!empty($_POST['comp_img_old'])) {
        $comp_img_old = $_POST['comp_img_old'];
    } else {
        $comp_img_old = "";
    }

    $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/logo_images";
    $photomax    = $xoopsModuleConfig['jobs_maxfilesize'];
    $maxwide     = $xoopsModuleConfig['jobs_resized_width'];
    $maxhigh     = $xoopsModuleConfig['jobs_resized_height'];
    $date        = time();

    if ($del_old == TRUE) {
        if (file_exists("$destination/$comp_img_old")) {
            unlink("$destination/$comp_img_old");
        }
        $comp_img_old = "";
    }
    $comp_user1 = $_POST["comp_user1"];
    $comp_user2 = $_POST["comp_user2"];

// START - check new entries for company users are OK - contributed by GreenFlatDog
//	$comp_userid1 = jobs_getIdFromUname($comp_user1);
//	$comp_userid2 = jobs_getIdFromUname($comp_user2);
    $comp_users = array();
// get user id for the name entered for company user 1
    if (empty($_POST["comp_user1"])) {
        $comp_userid1 = "";
    } else {
        $comp_userid1 = jobs_getIdFromUname($comp_user1);
        // put name, id, what's entered and problem into an array
        $comp_users[$comp_user1]['name']  = $_POST["comp_user1"];
        $comp_users[$comp_user1]['id']    = $comp_userid1;
        $comp_users[$comp_user1]['entry'] = "&cuser1=";
        $comp_users[$comp_user1]['prob']  = "&prob1=";
    }
// get user id for the name entered for company user 2
    if (empty($_POST["comp_user2"])) {
        $comp_userid2 = "";
    } else {
        $comp_userid2 = jobs_getIdFromUname($_POST["comp_user2"]);
        // put name, id, what's entered and problem into an array
        $comp_users[$comp_user2]['name']  = $comp_user2;
        $comp_users[$comp_user2]['id']    = $comp_userid2;
        $comp_users[$comp_user2]['entry'] = "&cuser2=";
        $comp_users[$comp_user2]['prob']  = "&prob2=";
    }
    if (!empty($comp_users)) {
        // we have checks to make
        $gperm_handler =& xoops_gethandler('groupperm');
        $errs          = "";
        foreach ($comp_users as $u) {
            if ($u['id']) {
                // we have user id for name entered
                $xu   = new XoopsUser($u['id']);
                $grps = $xu->getGroups();
                if (!$gperm_handler->checkRight("jobs_submit", 0, $grps, $module_id)) {
                    // no submit permission
                    $errs .= $u['entry'] . $u['name'] . $u['prob'] . "p";
                }
            } else {
                // no user id for name entered
                $errs .= $u['entry'] . $u['name'] . $u['prob'] . "n";
            }
        }
        if ($errs) {
            // we are going to re-open the form and request corrections
            // add to the query string the comp user(s) with their usernames and what problems they have e.g.
            // name1=xyz and prob1=p (no submit permission) or prob1=n (not there)
            redirect_header("modcomp.php?comp_id=" . addslashes($comp_id . $errs) . "", 5, "Correction required");
            exit();
        }
    }
// END - check new entries for company users are OK - GreenFlatDog

    $comp_name          = $myts->addSlashes($_POST["comp_name"]);
    $comp_address       = $myts->addSlashes($_POST["comp_address"]);
    $comp_address2      = $myts->addSlashes($_POST["comp_address2"]);
    $comp_city          = $myts->addSlashes($_POST["comp_city"]);
    $comp_state         = $myts->addSlashes($_POST["comp_state"]);
    $comp_zip           = $myts->addSlashes($_POST["comp_zip"]);
    $comp_phone         = $myts->addSlashes($_POST["comp_phone"]);
    $comp_fax           = $myts->addSlashes($_POST["comp_fax"]);
    $comp_url           = $myts->addSlashes($_POST["comp_url"]);
    $comp_usid          = $myts->addSlashes($_POST["comp_usid"]);
    $comp_contact       = $myts->addSlashes($_POST["comp_contact"]);
    $comp_user1_contact = $myts->addSlashes($_POST["comp_user1_contact"]);
    $comp_user2_contact = $myts->addSlashes($_POST["comp_user2_contact"]);
    $comp_date_added    = $myts->addSlashes($_POST["comp_date_added"]);

    if (!empty($_FILES['comp_img']['name'])) {
        include_once XOOPS_ROOT_PATH . "/class/uploader.php";
        $updir             = $destination;
        $allowed_mimetypes = array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/x-png');
        $uploader          = new XoopsMediaUploader($updir, $allowed_mimetypes, $photomax, $maxwide, $maxhigh);
        $uploader->setTargetFileName($date . '_' . $_FILES['comp_img']['name']);
        $uploader->fetchMedia('comp_img');
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();
            redirect_header("modcompany.php?comp_id=" . addslashes($comp_id) . "", 5, $errors);
            exit();
        } else {
            if ($comp_img_old) {
                if (@file_exists("$destination/$comp_img_old")) {
                    unlink("$destination/$comp_img_old");
                }
            }
            $comp_img_old = $uploader->getSavedFileName();
        }
    }

    $xoopsDB->query(
        "update " . $xoopsDB->prefix("jobs_companies")
            . " set comp_id='$comp_id', comp_name='$comp_name', comp_address='$comp_address', comp_address2='$comp_address2', comp_city='$comp_city', comp_state='$comp_state', comp_zip='$comp_zip', comp_phone='$comp_phone', comp_fax='$comp_fax', comp_img='$comp_img_old',  comp_url='$comp_url', comp_usid='$comp_usid', comp_user1='$comp_userid1', comp_user2='$comp_userid2', comp_contact='$comp_contact', comp_user1_contact='$comp_user1_contact', comp_user2_contact='$comp_user2_contact', comp_date_added='$comp_date_added' where comp_id="
            . mysql_real_escape_string($comp_id) . ""
    );

    redirect_header("company.php", 4, _AM_JOBS_COMPVALID);
    exit();

} else {

    xoops_cp_header();
    //loadModuleAdminMenu(3, "");
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation("company.php");
    $indexAdmin->addItemButton(_AM_JOBS_MAN_COMPANY, 'company.php', 'list');
    echo $indexAdmin->renderButton('left', '');

//	echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_AM_JOBS_MOD_COMPANY."</legend>";

    $token   = $GLOBALS['xoopsSecurity']->createToken();
    $comp_id = (intval($_GET['comp_id']) > 0) ? intval($_GET['comp_id']) : 0;

    echo "<script language=\"javascript\">\nfunction CLA(CLA) { var MainWindow = window.open (CLA, \"_blank\",\"width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no\");}\n</script>";

    $photomax  = $xoopsModuleConfig['jobs_maxfilesize'];
    $maxwide   = $xoopsModuleConfig['jobs_resized_width'];
    $maxhigh   = $xoopsModuleConfig['jobs_resized_height'];
    $photomax1 = $xoopsModuleConfig['jobs_maxfilesize'] / 1024;

    $result = $xoopsDB->query(
        "select comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact, comp_date_added from "
            . $xoopsDB->prefix("jobs_companies") . " where comp_id=" . mysql_real_escape_string($comp_id) . ""
    );
    list($comp_id, $comp_name, $comp_address, $comp_address2, $comp_city, $comp_state, $comp_zip, $comp_phone, $comp_fax, $comp_url, $comp_img_old, $comp_usid, $comp_user1, $comp_user2, $comp_contact, $comp_user1_contact, $comp_user2_contact, $comp_date_added) = $xoopsDB->fetchRow($result);

    if ($xoopsUser) {
        $member_id  = $xoopsUser->uid();
        $comp_users = array($comp_usid, $comp_user1, $comp_user2);
        if (in_array($member_id, $comp_users)) {
            $comp_name     = $myts->addSlashes($comp_name);
            $comp_address  = $myts->addSlashes($comp_address);
            $comp_address2 = $myts->addSlashes($comp_address2);
            $comp_city     = $myts->addSlashes($comp_city);
            $comp_state    = $myts->addSlashes($comp_state);
            $comp_zip      = $myts->addSlashes($comp_zip);
            $comp_phone    = $myts->addSlashes($comp_phone);
            $comp_fax      = $myts->addSlashes($comp_fax);
            $comp_url      = $myts->addSlashes($comp_url);
            $comp_usid     = $myts->addSlashes($comp_usid);

            if ($comp_user1 != 0) {
                xoops_load("xoopsuserutility");
                $comp_username1 = XoopsUser::getUnameFromId($comp_user1);
            } else {
                $comp_username1 = "";
            }

            if ($comp_user2 != 0) {
                xoops_load("xoopsuserutility");
                $comp_username2 = XoopsUser::getUnameFromId($comp_user2);
            } else {
                $comp_username2 = "";
            }
            $comp_contact       = $myts->addSlashes($comp_contact);
            $comp_user1_contact = $myts->addSlashes($comp_user1_contact);
            $comp_user2_contact = $myts->addSlashes($comp_user2_contact);
            xoops_load("xoopsuserutility");
            $added_by   = XoopsUser::getUnameFromId($comp_usid);
            $useroffset = "";
            if ($xoopsUser) {
                $timezone = $xoopsUser->timezone();
                if (isset($timezone)) {
                    $useroffset = $xoopsUser->timezone();
                } else {
                    $useroffset = $xoopsConfig['default_TZ'];
                }
            }
            $dates = ($useroffset * 3600) + $comp_date_added;
            $dates = formatTimestamp($comp_date_added, "s");
            $date  = time();

            $alert_message = "";
            $alert1        = "";
            $alert2        = "";
// START - check new entries for company users are OK - contributed by GreenFlatDog
            if (isset($_GET['cuser1']) && $_GET['cuser1'] != "") {
                $cuser1        = $_GET['cuser1'];
                $prob1         = $_GET['prob1'];
                $alert_message = _AM_JOBS_PLS_CORRECT;
            } else {
                $cuser1 = "";
            }
            if (isset($_GET['cuser2']) && $_GET['cuser2'] != "") {
                $cuser2        = $_GET['cuser2'];
                $prob2         = $_GET['prob2'];
                $alert_message = _AM_JOBS_PLS_CORRECT;
            } else {
                $cuser2 = "";
            }

// END - check new entries for company users are OK - contributed by GreenFlatDog
            $result = $xoopsDB->query("select rid, name from " . $xoopsDB->prefix("jobs_region") . " order by rid");
            echo "<span style='display:block; text-align:center; color:#f00'>" . $alert_message . "</span>";
            ob_start();
            $form = new XoopsThemeForm(_AM_JOBS_MOD_COMPANY, 'modify_form', 'modcomp.php');
            $form->setExtra('enctype="multipart/form-data"');

            $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');

            $form->addElement(new XoopsFormLabel(_AM_JOBS_NUMANN, $comp_id . " " . _AM_JOBS_ADDED . " " . $dates));
            $form->addElement(new XoopsFormLabel(_AM_JOBS_SENDBY, $added_by));
            $form->addElement(new XoopsFormLabel(_AM_JOBS_COMPANY2, $comp_name));
            $form->addElement(new XoopsFormHidden('comp_name', $comp_name));
            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY_ADDRESS, "comp_address", 30, 100, $comp_address), FALSE);
            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY_ADDRESS2, "comp_address2", 30, 100, $comp_address2), FALSE);
            $form->addElement(new XoopsFormText(_AM_JOBS_TOWN, 'comp_city', 30, 50, $comp_city), FALSE);

            $state_form = new XoopsFormSelect(_AM_JOBS_STATE, "comp_state", $comp_state, "0", FALSE);
            while (list($rid, $name) = $xoopsDB->fetchRow($result)) {
                $state_form->addOption('', _AM_JOBS_SELECT_STATE);
                $state_form->addOption($rid, $name);
            }
            $form->addElement($state_form, TRUE);

            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY_ZIP, "comp_zip", 30, 30, $comp_zip), FALSE);
            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY_PHONE, "comp_phone", 30, 30, $comp_phone), FALSE);
            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY_FAX, "comp_fax", 30, 30, $comp_fax), FALSE);
            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY_SITEURL, "comp_url", 30, 30, $comp_url), FALSE);
            $form->addElement(new XoopsFormTextArea(_AM_JOBS_CONTACTINFO, "comp_contact", $comp_contact, 6, 35), FALSE);

// START - check new entries for company users are OK - contributed by GreenFlatDog
            $alert = "<br /><span style='color:#f00'>%s%s</span>";
            if ($cuser1) {
                $prob   = ($prob1 == "n") ? _AM_JOBS_COMP_USER_NOTTHERE : _AM_JOBS_COMP_USER_NOPERM;
                $alert1 = sprintf($alert, $cuser1, $prob);
                unset($prob);
            }
            $form->addElement(
                new XoopsFormText(_AM_JOBS_COMPANY_USER1 . $alert1, "comp_user1", 30, 30, $comp_username1), FALSE
            );
            $form->addElement(new XoopsFormTextArea(_AM_JOBS_USER1_CONTACT, "comp_user1_contact", $comp_user1_contact, 6, 35), FALSE);

            if ($cuser2) {
                $prob   = ($prob2 == "n") ? _AM_JOBS_COMP_USER_NOTTHERE : _AM_JOBS_COMP_USER_NOPERM;
                $alert2 = sprintf($alert, $cuser2, $prob);
                unset($prob);
            }

            $form->addElement(
                new XoopsFormText(_AM_JOBS_COMPANY_USER2 . $alert2, "comp_user2", 30, 30, $comp_username2), FALSE
            );
            $form->addElement(new XoopsFormTextArea(_AM_JOBS_USER2_CONTACT, "comp_user2_contact", $comp_user2_contact, 6, 35), FALSE);

// END - check new entries for company users are OK - contributed by GreenFlatDog

            if ($comp_img_old) {

                $comp_logo_link = "<a href=\"javascript:CLA('../display-logo.php?comp_id=" . addslashes($comp_id)
                    . "')\">$comp_img_old</a>";

                $form->addElement(new XoopsFormLabel(_AM_JOBS_ACTUALPICT, $comp_logo_link));

                $del_checkbox = new XoopsFormCheckBox(_AM_JOBS_DELPICT, 'del_old', $del_old);
                $del_checkbox->addOption(1, "Yes");
                $form->addElement($del_checkbox);

                $form->addElement(new XoopsFormFile(_AM_JOBS_NEWPICT, 'comp_img', $xoopsModuleConfig['jobs_maxfilesize']), FALSE);

            } else {
                $form->addElement(new XoopsFormFile(_AM_JOBS_COMPANY_LOGO, 'comp_img', $xoopsModuleConfig['jobs_maxfilesize']), FALSE);
            }
            $form->addElement(new XoopsFormHidden('comp_img_old', $comp_img_old));
            $form->addElement(new XoopsFormHidden('token', $token));
            $form->addElement(new XoopsFormHidden('submit', "1"));
            $form->addElement(new XoopsFormHidden('comp_name', $comp_name));
            $form->addElement(new XoopsFormHidden('comp_usid', $comp_usid));
            $form->addElement(new XoopsFormHidden('comp_id', $comp_id));
            $form->addElement(new XoopsFormHidden('comp_date_added', $date));
            $form->addElement(new XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
            $form->display();
            $submit_form = ob_get_contents();
            ob_end_clean();
            echo $submit_form;
        }
    }
}
xoops_cp_footer();
