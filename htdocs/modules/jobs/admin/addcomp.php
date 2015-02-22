<?php
// $Id: addcompany.php,v 4.1 2010/02/6 08:11:07 jlm69 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$myts =& MyTextSanitizer::getInstance();

$module_id = $xoopsModule->getVar('mid');

if (!empty($_POST['submit'])) {
    $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/logo_images";
    $photomax    = $xoopsModuleConfig['jobs_maxfilesize'];
    $maxwide     = $xoopsModuleConfig['jobs_resized_width'];
    $maxhigh     = $xoopsModuleConfig['jobs_resized_height'];
    $date        = time();

    $_SESSION['comp_name']          = $_POST['comp_name'];
    $_SESSION['comp_address']       = $_POST['comp_address'];
    $_SESSION['comp_address2']      = $_POST['comp_address2'];
    $_SESSION['comp_city']          = $_POST['comp_city'];
    $_SESSION['comp_state']         = $_POST['comp_state'];
    $_SESSION['comp_zip']           = $_POST['comp_zip'];
    $_SESSION['comp_phone']         = $_POST['comp_phone'];
    $_SESSION['comp_fax']           = $_POST['comp_fax'];
    $_SESSION['comp_url']           = $_POST['comp_url'];
    $_SESSION['comp_usid']          = $_POST['comp_usid'];
    $_SESSION['comp_user1']         = $_POST['comp_user1'];
    $_SESSION['comp_user2']         = $_POST['comp_user2'];
    $_SESSION['comp_contact']       = $_POST['comp_contact'];
    $_SESSION['comp_user1_contact'] = $_POST['comp_user1_contact'];
    $_SESSION['comp_user2_contact'] = $_POST['comp_user2_contact'];

    $comp_name     = $myts->addSlashes($_POST["comp_name"]);
    $comp_address  = $myts->addSlashes($_POST["comp_address"]);
    $comp_address2 = $myts->addSlashes($_POST["comp_address2"]);
    $comp_city     = $myts->addSlashes($_POST["comp_city"]);
    $comp_state    = $myts->addSlashes($_POST["comp_state"]);
    $comp_zip      = $myts->addSlashes($_POST["comp_zip"]);
    $comp_phone    = $myts->addSlashes($_POST["comp_phone"]);
    $comp_fax      = $myts->addSlashes($_POST["comp_fax"]);
    $comp_url      = $myts->addSlashes($_POST["comp_url"]);
    $comp_usid     = $myts->addSlashes($_POST["comp_usid"]);
    $comp_user1    = $myts->addSlashes($_POST["comp_user1"]);
    $comp_user2    = $myts->addSlashes($_POST["comp_user2"]);
    $comp_contact  = $myts->addSlashes($_POST["comp_contact"]);
// START  - check new entries for company users are OK - GreenFlatDog

    $comp_users = array();
// get user id for the name entered for company user 1
    if (empty($_POST["comp_user1"])) {
        $comp_userid1 = "";
    } else {
        $comp_userid1 = jobs_getIdFromUname($_POST["comp_user1"]);
        // put name, id, what's entered and problem into an array
        $comp_users[$comp_user1]['name']  = $_POST["comp_user1"];
        $comp_users[$comp_user1]['id']    = $comp_userid1;
        $comp_users[$comp_user1]['entry'] = "?cuser1=";
        $comp_users[$comp_user1]['prob']  = "&prob1=";
    }
// get user id for the name entered for company user 2
    if (empty($_POST["comp_user2"])) {
        $comp_userid2 = "";
    } else {
        $comp_userid2 = jobs_getIdFromUname($_POST["comp_user2"]);
        // put name, id, what's entered and problem into an array
        $comp_users[$comp_user2]['name']  = $_POST["comp_user2"];
        $comp_users[$comp_user2]['id']    = $comp_userid2;
        $comp_users[$comp_user2]['entry'] = "?cuser2=";
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
            redirect_header("addcomp.php$errs", 5, "Correction required");
            exit();
        }
    }
// END  - check new entries for company users are OK - GreenFlatDog

    $comp_user1_contact = $myts->addSlashes($_POST["comp_user1_contact"]);
    $comp_user2_contact = $myts->addSlashes($_POST["comp_user2_contact"]);

    $filename = "";
    if (!empty($_FILES['comp_img']['name'])) {
        include_once XOOPS_ROOT_PATH . "/class/uploader.php";
        $updir             = '../logo_images/';
        $allowed_mimetypes = array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
        $uploader          = new XoopsMediaUploader($updir, $allowed_mimetypes, $photomax, $maxwide, $maxhigh);
        $uploader->setTargetFileName($date . '_' . $_FILES['comp_img']['name']);
        $uploader->fetchMedia('comp_img');
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();
            redirect_header("addcomp.php", 3, $errors);

            return FALSE;
            exit();
        } else {
            $filename = $uploader->getSavedFileName();
        }
    }

    $newid = $xoopsDB->genId($xoopsDB->prefix("jobs_companies") . "_comp_id_seq");

    $sql = sprintf("INSERT INTO %s (comp_id, comp_name, comp_address, comp_address2, comp_city, comp_state, comp_zip, comp_phone, comp_fax, comp_url, comp_img, comp_usid, comp_user1, comp_user2, comp_contact, comp_user1_contact, comp_user2_contact, comp_date_added) VALUES (%u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%u', '%s', '%s', '%s', '%s', '%s', '%s')", $xoopsDB->prefix("jobs_companies"), $newid, $comp_name, $comp_address, $comp_address2, $comp_city, $comp_state, $comp_zip, $comp_phone, $comp_fax, $comp_url, $filename, $comp_usid, $comp_userid1, $comp_userid2, $comp_contact, $comp_user1_contact, $comp_user2_contact, $date);
    $xoopsDB->query($sql) or $erh->show("0013");

    unset ($_SESSION['comp_name']);
    unset ($_SESSION['comp_address']);
    unset ($_SESSION['comp_address2']);
    unset ($_SESSION['comp_city']);
    unset ($_SESSION['comp_state']);
    unset ($_SESSION['comp_zip']);
    unset ($_SESSION['comp_phone']);
    unset ($_SESSION['comp_fax']);
    unset ($_SESSION['comp_url']);
    unset ($_SESSION['comp_usid']);
    unset ($_SESSION['comp_user1']);
    unset ($_SESSION['comp_user2']);
    unset ($_SESSION['comp_contact']);
    unset ($_SESSION['comp_user1_contact']);
    unset ($_SESSION['comp_user2_contact']);

    redirect_header("company.php", 3, _AM_JOBS_COMPANY_ADDED);
    exit();

} else {

    include 'admin_header.php';
    xoops_cp_header();
//	loadModuleAdminMenu(3, "");
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation("company.php");
    $indexAdmin->addItemButton(_AM_JOBS_MAN_COMPANY, 'company.php', 'list');
    echo $indexAdmin->renderButton('left', '');

    $alert_message = "";
    if (isset($_GET['cuser1']) && $_GET['cuser1'] != "") {
        $cuser1        = $_GET['cuser1'];
        $prob1         = $_GET['prob1'];
        $alert_message = _AM_JOBS_PLS_CORRECT;
    }
    if (isset($_GET['cuser2']) && $_GET['cuser2'] != "") {
        $cuser2        = $_GET['cuser2'];
        $prob2         = $_GET['prob2'];
        $alert_message = _AM_JOBS_PLS_CORRECT;
    }

    $alert1 = "";
    $alert2 = "";

    $_SESSION['comp_name']          = !empty($_SESSION['comp_name']) ? $_SESSION['comp_name'] : "";
    $_SESSION['comp_address']       = !empty($_SESSION['comp_address']) ? $_SESSION['comp_address'] : "";
    $_SESSION['comp_address2']      = !empty($_SESSION['comp_address2']) ? $_SESSION['comp_address2'] : "";
    $_SESSION['comp_city']          = !empty($_SESSION['comp_city']) ? $_SESSION['comp_city'] : "";
    $_SESSION['comp_state']         = !empty($_SESSION['comp_state']) ? $_SESSION['comp_state'] : "";
    $_SESSION['comp_zip']           = !empty($_SESSION['comp_zip']) ? $_SESSION['comp_zip'] : "";
    $_SESSION['comp_phone']         = !empty($_SESSION['comp_phone']) ? $_SESSION['comp_phone'] : "";
    $_SESSION['comp_fax']           = !empty($_SESSION['comp_fax']) ? $_SESSION['comp_fax'] : "";
    $_SESSION['comp_url']           = !empty($_SESSION['comp_url']) ? $_SESSION['comp_url'] : "";
    $_SESSION['comp_usid']          = !empty($_SESSION['comp_usid']) ? $_SESSION['comp_usid'] : "";
    $_SESSION['comp_user1']         = !empty($_SESSION['comp_user1']) ? $_SESSION['comp_user1'] : "";
    $_SESSION['comp_user2']         = !empty($_SESSION['comp_user2']) ? $_SESSION['comp_user2'] : "";
    $_SESSION['comp_contact']       = !empty($_SESSION['comp_contact']) ? $_SESSION['comp_contact'] : "";
    $_SESSION['comp_user1_contact'] = !empty($_SESSION['comp_user1_contact']) ? $_SESSION['comp_user1_contact'] : "";
    $_SESSION['comp_user2_contact'] = !empty($_SESSION['comp_user2_contact']) ? $_SESSION['comp_user2_contact'] : "";

    $result = $xoopsDB->query("select rid, name from " . $xoopsDB->prefix("jobs_region") . " order by rid ASC");
    echo "<span style='display:block; text-align:center; color:#f00'>" . $alert_message . "</span>";
    ob_start();
    $form = new XoopsThemeForm(_AM_JOBS_ADD_COMPANY, 'companyform', 'addcomp.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_NAME, "comp_name", 20, 50, "" . $_SESSION['comp_name'] . ""), TRUE
    );
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_ADDRESS, "comp_address", 50, 50, "" . $_SESSION['comp_address'] . ""), TRUE
    );
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_ADDRESS2, "comp_address2", 50, 50,
            "" . $_SESSION['comp_address2'] . ""), FALSE
    );
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_CITY, "comp_city", 50, 50, "" . $_SESSION['comp_city'] . ""), TRUE
    );

    $state_form = new XoopsFormSelect(_AM_JOBS_STATE, "comp_state", "" . $_SESSION['comp_state'] . "", "0", FALSE);
    while (list($rid, $name) = $xoopsDB->fetchRow($result)) {
        $state_form->addOption('', _AM_JOBS_SELECT_STATE);
        $state_form->addOption($rid, $name);
    }
    $form->addElement($state_form, TRUE);

    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_ZIP, "comp_zip", 50, 50, "" . $_SESSION['comp_zip'] . ""), TRUE
    );
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_PHONE, "comp_phone", 30, 30, "" . $_SESSION['comp_phone'] . ""), TRUE
    );
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_FAX, "comp_fax", 30, 30, "" . $_SESSION['comp_fax'] . ""), FALSE
    );
    $form->addElement(
        new XoopsFormText(_AM_JOBS_COMPANY_SITEURL, "comp_url", 50, 50, "" . $_SESSION['comp_fax'] . ""), FALSE
    );
    $form->addElement(new XoopsFormFile(_AM_JOBS_COMPANY_LOGO, 'comp_img', 0), FALSE);
    $form->addElement(
        new XoopsFormTextArea(_AM_JOBS_COMPANY_CONTACT, 'comp_contact',
            "" . $_SESSION['comp_contact'] . "", 6, 40), FALSE
    );
    $form->insertBreak();
    $form->insertBreak(_AM_JOBS_COMPANY_OTHERS, "head");
    $form->insertBreak();
// START - check new entries for company users are OK - contributed by GreenFlatDog
    $alert = "<br /><span style='color:#f00'>%s%s</span>";
    if (isset($_GET['cuser1']) && $_GET['cuser1'] != "") {
        if ($cuser1) {
            $prob   = ($prob1 == "n") ? _AM_JOBS_COMP_USER_NOTTHERE : _AM_JOBS_COMP_USER_NOPERM;
            $alert1 = sprintf($alert, $cuser1, $prob);
            unset($prob);
        }
    }

    if ($alert1) {
        $form->addElement(
            new XoopsFormText(
                _AM_JOBS_COMPANY_USER1 . $alert1, "comp_user1", 50, 50, "" . $_SESSION['comp_user1'] . ""), FALSE
        );
    } else {
        $form->addElement(
            new XoopsFormText(_AM_JOBS_COMPANY_USER1, "comp_user1", 50, 50, "" . $_SESSION['comp_user1'] . ""), FALSE
        );
    }

    $form->addElement(
        new XoopsFormTextArea(_AM_JOBS_USER1_CONTACT, 'comp_user1_contact',
            "" . $_SESSION['comp_user1_contact'] . "", 6, 40), FALSE
    );

    if (isset($_GET['cuser2']) && $_GET['cuser2'] != "") {
        if ($cuser2) {
            $prob   = ($prob2 == "n") ? _AM_JOBS_COMP_USER_NOTTHERE : _AM_JOBS_COMP_USER_NOPERM;
            $alert2 = sprintf($alert, $cuser2, $prob);
            unset($prob);
        }
    }
    if ($alert2) {
        $form->addElement(
            new XoopsFormText(
                _AM_JOBS_COMPANY_USER2 . $alert2, "comp_user2", 50, 50, "" . $_SESSION['comp_user2'] . ""), FALSE
        );
    } else {
        $form->addElement(
            new XoopsFormText(_AM_JOBS_COMPANY_USER2, "comp_user2", 50, 50, "" . $_SESSION['comp_user2'] . ""), FALSE
        );
    }
    $form->addElement(
        new XoopsFormTextArea(_AM_JOBS_USER2_CONTACT, 'comp_user2_contact',
            "" . $_SESSION['comp_user2_contact'] . "", 6, 40), FALSE
    );
    $form->addElement(new XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
    $form->addElement(new XoopsFormHidden('comp_usid', $xoopsUser->getVar('uid')));
    $form->display();
    $submit_form = ob_get_contents();
    ob_end_clean();
    echo $submit_form;

    xoops_cp_footer();
}
