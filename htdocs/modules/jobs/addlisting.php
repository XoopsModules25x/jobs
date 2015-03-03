<?php
// $Id: addcompany.php,v 1.12 2010/02/06 08:11:07 jlm69 Exp $
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
include 'header.php';
$mydirname = basename(dirname(__FILE__));
$myts      =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once(XOOPS_ROOT_PATH . "/modules/$mydirname/include/gtickets.php");
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php";
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");

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
if (!$gperm_handler->checkRight("jobs_submit", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . "/index.php", 3, _NOPERM);
    exit();
}
if (!$gperm_handler->checkRight("jobs_premium", $perm_itemid, $groups, $module_id)) {
    $premium = 0;
} else {
    $premium = 1;
}

$mytree = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");

if (isset($_POST["cid"])) {
    $cid = intval($_POST["cid"]);
} else {
    if (isset($_GET["cid"])) {
        $cid = intval($_GET["cid"]);
    }
}
if (isset($_POST["comp_id"])) {
    $comp_id = intval($_POST["comp_id"]);
} else {
    if (isset($_GET["comp_id"])) {
        $comp_id = intval($_GET["comp_id"]);
    }
}

if (empty($xoopsUser)) {
    redirect_header(XOOPS_URL . "modules/profile/", 3, _JOBS_MUSTREGFIRST);
    exit();
}

$member_usid = $xoopsUser->uid();
 if ($xoopsModuleConfig['jobs_show_company'] == '1') {
$all_comp = jobs_getCompany($member_usid);
if (!$all_comp) {
    redirect_header(XOOPS_URL . "/modules/$mydirname/addcompany.php", 2, _JOBS_MUSTADD_COMPANY);
}

if (empty($comp_id)) {
    $count = jobs_getCompCount($member_usid);
    if ($count > 1) {
        redirect_header(XOOPS_URL . "/modules/$mydirname/whatcompany.php", 1, _JOBS_WHAT_COMPANY);
    }
}
}
if (!empty($_POST['submit'])) {

    $jobsdays = $xoopsModuleConfig['jobs_days'];

    if (!$xoopsGTicket->check(TRUE, 'token')) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
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

    $title   = $myts->addSlashes($_POST["title"]);
    $status  = $myts->addSlashes($_POST["status"]);
    $expire  = $myts->addSlashes($_POST["expire"]);
    $type    = $myts->addSlashes($_POST["type"]);
    $company = $myts->addSlashes($_POST["company"]);
    if ($xoopsModuleConfig['jobs_form_options'] == 'dhtmltextarea' || $xoopsModuleConfig['jobs_form_options'] == 'dhtml'
    ) {
        $desctext = $myts->displayTarea($_POST["desctext"], 0, 0, 0, 0, 0);
    } else {
        $desctext = $myts->displayTarea($_POST["desctext"], 1, 1, 1, 1, 1);
    }
    if ($xoopsModuleConfig['jobs_form_options'] == 'dhtmltextarea' || $xoopsModuleConfig['jobs_form_options'] == 'dhtml'
    ) {
        $requirements = $myts->displayTarea($_POST["requirements"], 0, 0, 1, 0, 0);
    } else {
        $requirements = $myts->displayTarea($_POST["requirements"], 1, 1, 1, 1, 1);
    }

    $tel          = $myts->addSlashes($_POST["tel"]);
    $price        = $myts->addSlashes($_POST["price"]);
    $typeprice    = $myts->addSlashes($_POST["typeprice"]);
    $contactinfo  = $myts->displayTarea($_POST["contactinfo"], 0, 0, 0, 0, 0);
    $contactinfo1 = $myts->displayTarea($_POST["contactinfo1"], 0, 0, 0, 0, 0);
    $contactinfo2 = $myts->displayTarea($_POST["contactinfo2"], 0, 0, 0, 0, 0);
    $submitter    = $myts->addSlashes($_POST["submitter"]);
    $usid         = $myts->addSlashes($member_usid);
    $town         = $myts->addSlashes($_POST["town"]);
    $state        = $myts->addSlashes($_POST["state"]);
    $valid        = $myts->addSlashes($_POST["valid"]);
    $email        = $myts->addSlashes($_POST["email"]);
    $view         = 0;
    $photo        = '';
    $date         = time();

    $newid = $xoopsDB->genId($xoopsDB->prefix("jobs_listing") . "_lid_seq");

    $sql = sprintf(
        "INSERT INTO " . $xoopsDB->prefix("jobs_listing")
            . " (lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, contactinfo1, contactinfo2, date, email, submitter, usid, town, state, valid, photo, view) VALUES ('$newid', '$cid', '$title', '$status', '$expire', '$type', '$company', '$desctext', '$requirements', '$tel', '$price', '$typeprice', '$contactinfo', '$contactinfo1', '$contactinfo2', '$date', '$email', '$submitter', '$usid', '$town', '$state', '$valid', '$photo', '$view')"
    );
    $xoopsDB->query($sql);

    if ($valid == '1') {

        $notification_handler     =& xoops_gethandler('notification');
        $lid                      = $xoopsDB->getInsertId();
        $tags                     = array();
        $tags['LID']              = $lid;
        $tags['TITLE']            = $title;
        $tags['TYPE']             = $type;
        $tags['DESCTEXT']         = $desctext;
        $tags['COMPANY_TITLE']    = stripslashes($company);
        $tags['HELLO']            = _JOBS_HELLO;
        $tags['ADDED_TO_CAT']     = _JOBS_ADDED_TO_CAT;
        $tags['ADDED_BY_COMPANY'] = _JOBS_ADDED_BY_COMPANY;
        $tags['FOLLOW_LINK']      = _JOBS_FOLLOW_LINK;
        $tags['RECIEVING_NOTIF']  = _JOBS_RECIEVING_NOTIF;
        $tags['ERROR_NOTIF']      = _JOBS_ERROR_NOTIF;
        $tags['WEBMASTER']        = _JOBS_WEBMASTER;
        $tags['LINK_URL']         = XOOPS_URL . '/modules/' . $mydirname . '/viewjobs.php' . '?lid=' . addslashes($lid);
        $sql
                                  =
            "SELECT title FROM " . $xoopsDB->prefix("jobs_categories") . " WHERE cid=" . addslashes($cid);
        $result                   = $xoopsDB->query($sql);
        $row                      = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_TITLE']   = $row['title'];
        $tags['CATEGORY_URL']     = XOOPS_URL . '/modules/' . $mydirname . '/jobscat.php?cid="' . addslashes($cid);
        $notification_handler     =& xoops_gethandler('notification');
        $notification_handler->triggerEvent('global', 0, 'new_job', $tags);
        $notification_handler->triggerEvent('category', $cid, 'new_jobs_cat', $tags);
        $notification_handler->triggerEvent('company_listing', $comp_id, 'new_jobs_comp', $tags);
        $notification_handler->triggerEvent('job_listing', $lid, 'new_job', $tags);
    }
    redirect_header("index.php", 3, _JOBS_JOBADDED);
    exit();

} else {

    $xoopsOption['template_main'] = 'jobs_addlisting.html';
    include XOOPS_ROOT_PATH . "/header.php";

    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    if (isset($_POST["cid"])) {
        $cid = intval($_POST["cid"]);
    } else {
        if (isset($_GET["cid"])) {
            $cid = intval($_GET["cid"]);
        } else {
            $cid = 0;
        }
    }

    $member_usid  = $xoopsUser->uid();
    $member_email = $xoopsUser->getVar("email", "E");
    $member_uname = $xoopsUser->getVar("uname", "E");
    $email        = $member_email;
    $temp_premium = "0";

    if (empty($comp_id)) {
        $thiscompany = jobs_getCompany($member_usid);
    } else {
        $extra_user = jobs_getXtraUsers($comp_id, $member_usid);
        if (!empty($extra_user)) {
            $temp_premium = "1";
        }

        $thiscompany = jobs_getThisCompany($comp_id, $member_usid);
    }

    $result  = $xoopsDB->query("select nom_type from " . $xoopsDB->prefix("jobs_type") . " order by nom_type");
    $result1 = $xoopsDB->query("select nom_price from " . $xoopsDB->prefix("jobs_price") . " order by id_price");
    $result2 = $xoopsDB->query("select rid,name from " . $xoopsDB->prefix("jobs_region") . " order by rid");

    ob_start();
    $form = new XoopsThemeForm(_JOBS_ADD_LISTING, 'submit_form', 'addlisting.php');
    $form->setExtra('enctype="multipart/form-data"');
    $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');

    if (($premium == "1") || ($temp_premium == "1")) {
        echo "" . _JOBS_PREMIUM_MEMBER . " " . $xoopsModuleConfig['jobs_days'] . " " . _JOBS_PREMIUM2 . "";
    } else {
        echo "";
    }

    $form->addElement(new XoopsFormLabel(_JOBS_SUBMITTER, $member_uname));
    $form->addElement(new XoopsFormHidden('submitter', $member_uname));

    if ($xoopsModuleConfig['jobs_show_company'] == '1') {
        $form->addElement(new XoopsFormLabel(_JOBS_COMPANY, $thiscompany["comp_name"]));
        $form->addElement(new XoopsFormHidden('company', $thiscompany["comp_name"]));
    } else {
    $form->addElement(new XoopsFormHidden('company', ""));
    }
        
    $form->addElement(new XoopsFormText(_JOBS_EMAIL, 'email', 50, 100, $email), TRUE);
     if ($xoopsModuleConfig['jobs_show_company'] == '1') {
    $form->addElement(new XoopsFormText(_JOBS_TOWN, 'town', 50, 50, $thiscompany["comp_city"]), FALSE);
    } else {
    $form->addElement(new XoopsFormText(_JOBS_TOWN, 'town', 50, 50, ""), FALSE);
    }
    if ($xoopsModuleConfig['jobs_show_state'] == '1') {
        $state_form = new XoopsFormSelect(_JOBS_STATE, "state", $thiscompany["comp_state"], "0", FALSE);
        while (list($rid, $name) = $xoopsDB->fetchRow($result2)) {
            $state_form->addOption('', _JOBS_SELECT_STATE);
            $state_form->addOption($rid, $name);
        }
        $form->addElement($state_form, TRUE);
    } else {
    $form->addElement(new XoopsFormHidden('state', ""));
    }

    
    if ($xoopsModuleConfig['jobs_show_company'] == '1') {
    $form->addElement(new XoopsFormText(_JOBS_TEL, "tel", 30, 30, $thiscompany["comp_phone"]), FALSE);
    } else {
    $form->addElement(new XoopsFormText(_JOBS_TEL, "tel", 30, 30, ""), FALSE);
    }
    $cat_form = (new XoopsFormSelect(_JOBS_CAT, 'cid', ''));
    $cattree  = $mytree->getChildTreeArray(0, "title ASC");
    $cat_form->addOption('', _JOBS_SELECTCAT);
    foreach ($cattree as $branch) {
        $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".", "--", $branch['prefix']);
        $cat_form->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
    }
    $form->addElement($cat_form, TRUE);

    if (($premium == "1") || ($temp_premium == "1")) {

        $form->addElement(new XoopsFormText(_JOBS_HOW_LONG, "expire", 3, 3, $xoopsModuleConfig['jobs_days']), TRUE);

    } else {
        $form->addElement(new XoopsFormLabel(_JOBS_NON_HOW_LONG, $xoopsModuleConfig['jobs_days']));
        $form->addElement(new XoopsFormHidden('expire', $xoopsModuleConfig['jobs_days']));
    }

    $type_form = new XoopsFormSelect(_JOBS_JOB_TYPE, "type", "", "0", FALSE);
    while (list($nom_type) = $xoopsDB->fetchRow($result)) {
        $type_form->addOption($nom_type, $nom_type);
    }
    $form->addElement($type_form);

    if (($premium == "1") || ($temp_premium == "1")) {
        $radio        = new XoopsFormRadio(_JOBS_STATUS, 'status', "1");
        $options["1"] = _JOBS_ACTIVE;
        $options["0"] = _JOBS_INACTIVE;
        $radio->addOptionArray($options);
        $form->addElement($radio, TRUE);
    } else {
        $form->addElement(new XoopsFormHidden('status', "1"));
    }
    $form->addElement(new XoopsFormText(_JOBS_TITLE, "title", 40, 50, ""), TRUE);
    $form->addElement(jobs_getEditor(_JOBS_DESC, "desctext", "", "100%", "300px", ""), TRUE);
    $form->addElement(jobs_getEditor(_JOBS_REQUIRE, "requirements", "", "100%", "300px", ""), TRUE);
    $form->addElement(new XoopsFormText(_JOBS_PRICE2, "price", 40, 50, ""), FALSE);
    $sel_form = new XoopsFormSelect(_JOBS_SALARYTYPE, "typeprice", "", "1", FALSE);
    while (list($nom_price) = $xoopsDB->fetchRow($result1)) {
        $sel_form->addOption($nom_price, $nom_price);
    }
    $form->addElement($sel_form);
    if ($xoopsModuleConfig['jobs_show_company'] == '1') {
    $form->addElement(
        new XoopsFormTextArea(_JOBS_CONTACTINFO, "contactinfo",
            "" . $myts->undoHtmlSpecialChars($myts->displayTarea($thiscompany["comp_contact"], 0, 0, 0, 0, 0))
                . "", 6, 40), TRUE
    );
    } else {
    $form->addElement(
        new XoopsFormTextArea(_JOBS_CONTACTINFO, "contactinfo",
            "", 6, 40), TRUE
    );
    }
 

    if (($premium == "1") || ($temp_premium == "1")) {
    
    if (($xoopsModuleConfig['jobs_show_company'] == '1') && ($thiscompany["comp_user1_contact"])) {
        
    $form->addElement(new XoopsFormTextArea(_JOBS_CONTACTINFO1, "contactinfo1",
                    "" . $thiscompany["comp_user1_contact"] . "", 6, 40), FALSE
            );
        } else {
            $form->addElement(new XoopsFormTextArea(_JOBS_CONTACTINFO1, "contactinfo1", "", 6, 40), FALSE);
        }
    if (($xoopsModuleConfig['jobs_show_company'] == '1') && ($thiscompany["comp_user2_contact"])) {
            $form->addElement(
                new XoopsFormTextArea(_JOBS_CONTACTINFO2, "contactinfo2",
                    "" . $thiscompany["comp_user2_contact"] . "", 6, 40), FALSE
            );
        } else {
            $form->addElement(new XoopsFormTextArea(_JOBS_CONTACTINFO2, "contactinfo2", "", 6, 40), FALSE);
        }
    } else {
        $form->addElement(new XoopsFormHidden('contactinfo1', ""));
        $form->addElement(new XoopsFormHidden('contactinfo2', ""));
    }
//	if ($xoopsModuleConfig['jobs_use_captcha'] == '1') {
//        $form->addElement(new XoopsFormCaptcha(_JOBS_CAPTCHA, "xoopscaptcha", false), true);
//	}
    if ($xoopsModuleConfig['jobs_moderated'] == 0) {
        $form->addElement(new XoopsFormHidden("valid", "1"), FALSE);
    } else {
        $form->addElement(new XoopsFormHidden("valid", "0"), FALSE);
    }
    if ($xoopsModuleConfig['jobs_show_company'] == '1') {
    $form->addElement(new XoopsFormHidden("comp_id", $thiscompany["comp_id"]), FALSE);
    }
    $form->addElement(new XoopsFormButton('', 'submit', _JOBS_SUBMIT, 'submit'));
    $form->display();
    $xoopsTpl->assign('submit_form', ob_get_contents());
    ob_end_clean();
    $xoopsTpl->assign('lang_comp_city', _JOBS_COMPANY_CITY);
}
include XOOPS_ROOT_PATH . '/footer.php';
