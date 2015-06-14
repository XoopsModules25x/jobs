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
include 'admin_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
$myts      =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

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
    $premium = "0";
} else {
    $premium = "1";
}
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php";
$mytree      = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");
$companytree = new JobTree($xoopsDB->prefix("jobs_companies"), "comp_id", "comp_pid");

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
    } else {
        $comp_id = "";
    }
}

$member_usid = $xoopsUser->getVar("uid", "E");

if (!empty($_POST['submit'])) {

    $jobsdays = $xoopsModuleConfig['jobs_days'];

    $title   = $myts->addSlashes($_POST["title"]);
    $status  = $myts->addSlashes($_POST["status"]);
    $expire  = $myts->addSlashes($_POST["expire"]);
    $type    = $myts->addSlashes($_POST["type"]);
    $company = $myts->addSlashes($_POST["company"]);
    if ($xoopsModuleConfig['jobs_form_options'] == 'dhtmltextarea') {
        $desctext = $myts->displayTarea($_POST["desctext"], 0, 0, 0, 0, 0);
    } else {
        $desctext = $myts->displayTarea($_POST["desctext"], 1, 1, 1, 1, 1);
    }
    if ($xoopsModuleConfig['jobs_form_options'] == 'dhtmltextarea') {
        $requirements = $myts->displayTarea($_POST["requirements"], 0, 0, 0, 0, 0);
    } else {
        $requirements = $myts->displayTarea($_POST["requirements"], 1, 1, 1, 1, 1);
    }

    $tel          = $myts->addSlashes($_POST["tel"]);
    $price        = $myts->addSlashes($_POST["price"]);
    $typeprice    = $myts->addSlashes($_POST["typeprice"]);
    $contactinfo  = $myts->addSlashes($_POST["contactinfo"]);
    $contactinfo1 = $myts->addSlashes($_POST["contactinfo1"]);
    $contactinfo2 = $myts->addSlashes($_POST["contactinfo2"]);
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

        $notification_handler    =& xoops_gethandler('notification');
        $lid                     = $xoopsDB->getInsertId();
        $tags                    = array();
        $tags['LID']             = $lid;
        $tags['TITLE']           = $title;
        $tags['TYPE']            = $type;
        $tags['DESCTEXT']        = $desctext;
        $tags['HELLO']           = _AM_JOBS_HELLO;
        $tags['ADDED_TO_CAT']    = _AM_JOBS_ADDED_TO_CAT;
        $tags['FOLLOW_LINK']     = _AM_JOBS_FOLLOW_LINK;
        $tags['RECIEVING_NOTIF'] = _AM_JOBS_RECIEVING_NOTIF;
        $tags['ERROR_NOTIF']     = _AM_JOBS_ERROR_NOTIF;
        $tags['WEBMASTER']       = _AM_JOBS_WEBMASTER;
        $tags['LINK_URL']        = XOOPS_URL . '/modules/' . $mydirname . '/viewjobs.php' . '?lid=' . addslashes($lid);
        $sql
                                 =
            "SELECT title FROM " . $xoopsDB->prefix("jobs_categories") . " WHERE cid=" . addslashes($cid);
        $result                  = $xoopsDB->query($sql);
        $row                     = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_TITLE']  = $row['title'];
        $tags['CATEGORY_URL']    = XOOPS_URL . '/modules/' . $mydirname . '/jobscat.php?cid="' . addslashes($cid);
        $notification_handler    =& xoops_gethandler('notification');
        $notification_handler->triggerEvent('global', 0, 'new_job', $tags);
        $notification_handler->triggerEvent('category', $cid, 'new_job_cat', $tags);
        $notification_handler->triggerEvent('listing', $lid, 'new_job', $tags);
    }
    redirect_header("jobs.php", 3, _AM_JOBS_JOBADDED);
    exit();

} elseif ((empty($_POST['select'])) && ($xoopsModuleConfig['jobs_show_company'] == "1")) {

    include 'admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('jobs.php');
    $indexAdmin->addItemButton(_AM_JOBS_MAN_JOB, 'jobs.php', 'list');
    echo $indexAdmin->renderButton('left', '');

    $iscompany = jobs_getAllCompanies();
    if (!$iscompany) {
    redirect_header("addcomp.php", 3, _AM_JOBS_MUSTADD_COMPANY);
    }
    
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    ob_start();
    $form = new XoopsThemeForm(_AM_JOBS_SELECTCOMPADD, 'select_form', 'submitlisting.php');
    $form->setExtra('enctype="multipart/form-data"');

    ob_start();
    $companytree->makeMyAdminCompBox("comp_name", "comp_name", "", 0, "comp_id");
    $form->addElement(new XoopsFormLabel(_AM_JOBS_SELECTCOMPANY, ob_get_contents()), TRUE);
    ob_end_clean();

    $form->addElement(new XoopsFormButton('', 'select', _AM_JOBS_CONTINUE, 'submit'));
    $form->display();
    $select_form = ob_get_contents();
    ob_end_clean();
    echo $select_form;

    xoops_cp_footer();

} else {

    include 'admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('jobs.php');
    $indexAdmin->addItemButton(_AM_JOBS_MAN_JOB, 'jobs.php', 'list');
    echo $indexAdmin->renderButton('left', '');

    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    $mytree = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");

    if (!empty($_POST['cid'])) {
        $cid = intval($_POST['cid']);
    } else {
        $cid = 0;
    }

    if (isset($_POST["comp_id"])) {
        $comp_id = intval($_POST["comp_id"]);
    } else {
        if (isset($_GET["comp_id"])) {
            $comp_id = intval($_GET["comp_id"]);
        } else {
            $comp_id = "";
        }
    }
    $member_usid  = $xoopsUser->uid();
    $member_email = $xoopsUser->getVar("email", "E");
    $member_uname = $xoopsUser->getVar("uname", "E");
    $email        = $member_email;

    if ($xoopsModuleConfig['jobs_show_company'] == '1') {
        $company = jobs_getACompany($comp_id);
    }
    $result = $xoopsDB->query("select rid, name from " . $xoopsDB->prefix("jobs_region") . " order by rid");
    $result1  = $xoopsDB->query("select nom_type from " . $xoopsDB->prefix("jobs_type") . " order by nom_type");
    $result2 = $xoopsDB->query("select nom_price from " . $xoopsDB->prefix("jobs_price") . " order by id_price");
    ob_start();
    $form = new XoopsThemeForm(_AM_JOBS_ADD_LISTING, 'submitform', 'submitlisting.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new XoopsFormLabel(_AM_JOBS_SUBMITTER, $member_uname));
    $form->addElement(new XoopsFormHidden('submitter', $member_uname));
    $form->addElement(new XoopsFormText(_AM_JOBS_EMAIL, 'email', 50, 100, $email), TRUE);
    if ($xoopsModuleConfig['jobs_show_company'] == '1') {

        if ($comp_id == "") {
            $form->addElement(new XoopsFormText(_AM_JOBS_COMPANY, 'company', 50, 50, '', TRUE));
        } else {
            $form->addElement(new XoopsFormLabel(_AM_JOBS_COMPANY, $company["comp_name"]));
            $form->addElement(new XoopsFormHidden('company', $company["comp_name"]));
        }

        $form->addElement(new XoopsFormText(_AM_JOBS_TOWN, 'town', 50, 50, $company["comp_city"]), FALSE);

        if ($xoopsModuleConfig['jobs_show_state'] == '1') {
            $state_form = new XoopsFormSelect(_AM_JOBS_STATE, "state", $company["comp_state"], "0", FALSE);
            while (list($rid, $name) = $xoopsDB->fetchRow($result)) {
                $state_form->addOption('', _AM_JOBS_SELECT_STATE);
                $state_form->addOption($rid, $name);
            }
            $form->addElement($state_form, TRUE);
        } else {
        $form->addElement(new XoopsFormHidden("state", ""));
        }

        $form->addElement(new XoopsFormText(_AM_JOBS_TEL, "tel", 30, 30, $company["comp_phone"]), FALSE);

        $sel_cat = (new XoopsFormSelect(_AM_JOBS_CAT, 'cid', ''));
        $cattree = $mytree->getChildTreeArray(0, "title ASC");
        $sel_cat->addOption('', _AM_JOBS_SELECTCAT);
        foreach ($cattree as $branch) {
            $branch['prefix'] = substr($branch['prefix'], 0, -1);
            $branch['prefix'] = str_replace(".", "--", $branch['prefix']);
            $sel_cat->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
        }
        $form->addElement($sel_cat, TRUE);

        $form->addElement(new XoopsFormText(_AM_JOBS_HOW_LONG, "expire", 3, 3, $xoopsModuleConfig['jobs_days']), TRUE);
      
      

        $type_form = new XoopsFormSelect(_AM_JOBS_TYPE, "type", "", "0", FALSE);
    while (list($nom_type) = $xoopsDB->fetchRow($result1)) {
            $type_form->addOption($nom_type, $nom_type);
        }
        $form->addElement($type_form);
        
        
        $radio        = new XoopsFormRadio(_AM_JOBS_STATUS, 'status', 1);
        $options["1"] = _AM_JOBS_ACTIVE;
        $options["0"] = _AM_JOBS_INACTIVE;
        $radio->addOptionArray($options);
        $form->addElement($radio, TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_TITLE, "title", 40, 50, ""), TRUE);
        $form->addElement(jobs_getEditor(_AM_JOBS_DESC, "desctext", "", "100%", "300px", ""), TRUE);
        $form->addElement(jobs_getEditor(_AM_JOBS_REQUIRE, "requirements", "", "100%", "300px", ""), TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_PRICE2, "price", 40, 50, ""), FALSE);
        $sel_form = new XoopsFormSelect(_AM_JOBS_SALARYTYPE, "typeprice", "", "1", FALSE);
        while (list($nom_price) = $xoopsDB->fetchRow($result2)) {
            $sel_form->addOption($nom_price, $nom_price);
        }
        $form->addElement($sel_form);
        $form->addElement(new XoopsFormText(_AM_JOBS_EMAIL, 'email', 50, 100, $email), TRUE);
        $form->addElement(
            new XoopsFormTextArea(_AM_JOBS_CONTACTINFO, "contactinfo", "" . $company["comp_contact"] . "", 6, 40), FALSE
        );
        if ($company["comp_user1_contact"]) {
            $form->addElement(
                new XoopsFormTextArea(_AM_JOBS_CONTACTINFO1, "contactinfo1",
                    "" . $company["comp_user1_contact"] . "", 6, 40), FALSE
            );
        } else {
            $form->addElement(new XoopsFormTextArea(_AM_JOBS_CONTACTINFO1, "contactinfo1", "", 6, 40), FALSE);
        }
        if ($company["comp_user2_contact"]) {
            $form->addElement(
                new XoopsFormTextArea(_AM_JOBS_CONTACTINFO2, "contactinfo2",
                    "" . $company["comp_user2_contact"] . "", 6, 40), FALSE
            );
        } else {
            $form->addElement(new XoopsFormTextArea(_AM_JOBS_CONTACTINFO2, "contactinfo2", "", 6, 40), FALSE);
        }
        $form->addElement(new XoopsFormHidden("valid", "1"), FALSE);
        $form->addElement(new XoopsFormHidden("comp_id", $company["comp_id"]), FALSE);
        $form->addElement(new XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
        $form->display();
        $submit_form = ob_get_contents();
        ob_end_clean();
        echo $submit_form;

    } else {

        $form->addElement(new XoopsFormText(_AM_JOBS_TOWN, 'town', 50, 50, ''), FALSE);
        if ($xoopsModuleConfig['jobs_show_state'] == '1') {
        $state_form = new XoopsFormSelect(_AM_JOBS_STATE, "state", "", "0", FALSE);
        while (list($rid,$name) = $xoopsDB->fetchRow($result)) {
            $state_form->addOption('', _AM_JOBS_SELECT_STATE);
            $state_form->addOption($rid, $name);
        }
        $form->addElement($state_form, TRUE);
    } else {
        $form->addElement(new XoopsFormHidden("state", ""));
        }
    
    

        $form->addElement(new XoopsFormText(_AM_JOBS_TEL, "tel", 30, 30, ''), FALSE);
        ob_start();
        $mytree->makeMyAdminSelBox("title", "title", "", "cid");
        $form->addElement(new XoopsFormLabel(_AM_JOBS_CAT, ob_get_contents()), TRUE);
        ob_end_clean();
        $form->addElement(new XoopsFormText(_AM_JOBS_HOW_LONG, "expire", 3, 3, $xoopsModuleConfig['jobs_days']), TRUE);
        $type_form = new XoopsFormSelect(_AM_JOBS_TYPE, "type", "", "1", FALSE);
        while (list($nom_type) = $xoopsDB->fetchRow($result1)) {
            $type_form->addOption($nom_type, $nom_type);
        }
        $form->addElement($type_form);
        $radio        = new XoopsFormRadio(_AM_JOBS_STATUS, 'status', 1);
        $options["1"] = _AM_JOBS_ACTIVE;
        $options["0"] = _AM_JOBS_INACTIVE;
        $radio->addOptionArray($options);
        $form->addElement($radio, TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_TITLE, "title", 40, 50, ""), TRUE);
        $form->addElement(jobs_getEditor(_AM_JOBS_DESC, "desctext", "", "100%", "300px", ""), TRUE);
        $form->addElement(jobs_getEditor(_AM_JOBS_REQUIRE, "requirements", "", "100%", "300px", ""), TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_PRICE2, "price", 40, 50, ""), FALSE);
        $sel_form = new XoopsFormSelect(_AM_JOBS_SALARYTYPE, "typeprice", "", "1", FALSE);
        while (list($nom_price) = $xoopsDB->fetchRow($result2)) {
            $sel_form->addOption($nom_price, $nom_price);
        }
        $form->addElement($sel_form);
        $form->addElement(new XoopsFormTextArea(_AM_JOBS_CONTACTINFO, "contactinfo", '', 6, 40), FALSE);
        $form->addElement(new XoopsFormHidden("valid", "1"), FALSE);
        $form->addElement(new XoopsFormHidden("comp_id", ""), FALSE);
        $form->addElement(new XoopsFormHidden("company", ""), FALSE);
        $form->addElement(new XoopsFormHidden("contactinfo1", ""), FALSE);
        $form->addElement(new XoopsFormHidden("contactinfo2", ""), FALSE);
        $form->addElement(new XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
        $form->display();
        $submit_form = ob_get_contents();
        ob_end_clean();
        echo $submit_form;
    }
}
xoops_cp_footer();
