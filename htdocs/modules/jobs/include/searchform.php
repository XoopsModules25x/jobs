<?php
// $Id: searchform.php 2 2005-11-02 18:23:29Z skalpa $
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
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

//  jlm69
include_once XOOPS_ROOT_PATH . "/modules/jobs/class/jobtree.php";
include_once XOOPS_ROOT_PATH . "/modules/jobs/class/restree.php";
include_once XOOPS_ROOT_PATH . "/modules/jobs/include/functions.php";
include_once XOOPS_ROOT_PATH . "/modules/jobs/include/resume_functions.php";

$mytree       = new JobTree($xoopsDB->prefix("jobs_categories"), "cid", "pid");
$restree      = new ResTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");
$staterestree = new ResTree($xoopsDB->prefix("jobs_region"), "rid", "pid");
$statetree    = new JobTree($xoopsDB->prefix("jobs_region"), "rid", "pid");
$xmid         = $xoopsModule->getVar('mid');

if (!empty($_GET['is_resume'])) {
    $is_resume = intval($_GET['is_resume']);
} elseif (!empty($_POST['is_resume'])) {
    $is_resume = intval($_POST['is_resume']);
} else {
    $is_resume = "";
}
// end jlm69
// create form
$search_form = new XoopsThemeForm(_SR_SEARCH, "search", "search.php", 'get');

// create form elements
$search_form->addElement(new XoopsFormText(_SR_KEYWORDS, "query", 30, 255, htmlspecialchars(stripslashes(implode(" ", $queries)), ENT_QUOTES)), TRUE);
$type_select = new XoopsFormSelect(_SR_TYPE, "andor", $andor);
$type_select->addOptionArray(array("AND"=> _SR_ALL, "OR"=> _SR_ANY, "exact"=> _SR_EXACT));
$search_form->addElement($type_select);
//  jlm69
if (!empty($is_resume)) {

    ob_start();
    $restree->resume_makeMySearchSelBox("title", "title", $by_cat, "1", "by_cat");
    $search_form->addElement(new XoopsFormLabel(_JOBS_CAT, ob_get_contents()));
    ob_end_clean();

    if ($xoopsModuleConfig['jobs_show_state'] == '1') {
        if ($xoopsModuleConfig['jobs_countries'] == "1") {
            ob_start();
            $staterestree->resume_makeMyStateSelBox("name", "rid", $by_state, "1", "by_state");
            $search_form->addElement(new XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        } else {
            ob_start();
            $staterestree->resume_makeStateSelBox("name", "rid", $by_state, "1", "by_state");
            $search_form->addElement(new XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        }
    } else {
        $search_form->addElement(new XoopsFormHidden('state', ""));
    }

} else {

    ob_start();
    $mytree->makeMySearchSelBox("title", "title", $by_cat, "1", "by_cat");
    $search_form->addElement(new XoopsFormLabel(_JOBS_CAT, ob_get_contents()));
    ob_end_clean();

    if ($xoopsModuleConfig['jobs_show_state'] == '1') {
        if ($xoopsModuleConfig['jobs_countries'] == '1') {
            ob_start();
            $statetree->makeMyStateSelBox("name", "rid", $by_state, "1", "by_state");
            $search_form->addElement(new XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        } else {
            $statetree->makeStateSelBox("name", "rid", $by_state, "1", "by_state");
            $search_form->addElement(new XoopsFormLabel(_JOBS_STATE, ob_get_contents()));
            ob_end_clean();
        }
    } else {
        $search_form->addElement(new XoopsFormHidden('state', ""));
    }

}

//	if (!empty($mids)) {
//	$mods_checkbox = new XoopsFormCheckBox(_SR_SEARCHIN, "mids[]", $mids);
//	} else {
$mods_checkbox = new XoopsFormCheckBox(_SR_SEARCHIN, "mids[]", $mid);
//	}
//end jlm69
if (empty($modules)) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
    if (!empty($available_modules)) {
        $criteria->add(new Criteria('mid', $xmid));
    }
    $module_handler =& xoops_gethandler('module');
    $mods_checkbox->addOptionArray($module_handler->getList($criteria));
} else {
    foreach ($modules as $mid => $module) {
        $module_array[$mid] = $module->getVar('name');
    }
    $mods_checkbox->addOptionArray($module_array);
}
//jlm69
//	$search_form->addElement($mods_checkbox);
//$search_form->addElement(new XoopsFormHidden("mods_checkbox","array(mods_checkbox => $mods_checkbox)"));
// end jlm69
if ($xoopsConfigSearch['keyword_min'] > 0) {
    $search_form->addElement(new XoopsFormLabel(_SR_SEARCHRULE, sprintf(_SR_KEYIGNORE, $xoopsConfigSearch['keyword_min'])));
}

$search_form->addElement(new XoopsFormHidden("issearch", "1"));
$search_form->addElement(new XoopsFormHidden("is_resume", $is_resume));
$search_form->addElement(new XoopsFormHidden("action", "results"));
$search_form->addElement(new XoopsFormHiddenToken('id'));
$search_form->addElement(new XoopsFormButton("", "submit", _SR_SEARCH, "submit"));
