<?php
///////////////////////////////////////////////////////////////////////////////
//               Classified Ads Module for Xoops                             //
//      Redesigned by John Mordo user jlm69 at www.xoops.org                 //
//          Find it or report problems at www.jlmzone.com                    //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

function jobs_block_premium_show($options)
{
    global $xoopsDB, $blockdirname, $block_lang;

    $block = array();
    $myts  =& MyTextSanitizer::getInstance();

    $blockdirname = basename(dirname(dirname(__FILE__)));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    include_once (XOOPS_ROOT_PATH . "/modules/$blockdirname/include/functions.php");

    $block['title'] = "" . constant($block_lang . "_TITLE3") . "";

// Contribution by mboyden
    $module_handler   = & xoops_gethandler('module');
    $thisModule       = $module_handler->getByDirname($blockdirname);
    $config_handler   =& xoops_gethandler('config');
    $thisModuleConfig =& $config_handler->getConfigsByCat(0, $thisModule->mid());
// End

    $block['sponsored'] = "" . constant($block_lang . "_SPONSORED_LISTINGS") . "";

    $cat_perms  = "";
    $categories = jobs_MygetItemIds('jobs_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND l.cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query(
        "SELECT c.comp_id, c.comp_name, c.comp_img, l.lid, l.title, l.type, l.company, l.typeprice, l.town, l.state, l.date, l.view FROM "
            . $xoopsDB->prefix("jobs_companies") . " AS c LEFT OUTER JOIN " . $xoopsDB->prefix("jobs_listing")
            . " AS l on c.comp_name = l.company WHERE l.valid = '1' and l.status!='0' and l.premium='1' $cat_perms"
    );

    while (list($comp_id, $comp_name, $comp_img, $lid, $title, $type, $company, $typeprice, $town, $state, $date, $view) = $xoopsDB->fetchRow($result)) {

        $comp_name = $myts->undoHtmlSpecialChars($comp_name);
        $title     = $myts->undoHtmlSpecialChars($title);
        $type      = $myts->htmlSpecialChars($type);
        $company   = $myts->undoHtmlSpecialChars($company);
        $typeprice = $myts->htmlSpecialChars($typeprice);
        $town      = $myts->htmlSpecialChars($town);
        $state     = $myts->htmlSpecialChars($state);
        $view      = $myts->htmlSpecialChars($view);
        $comp_img  = $myts->htmlSpecialChars($comp_img);

        $a_item = array();

        if ($thisModuleConfig['jobs_show_company'] == '0') {
            $a_item['show_company'] = 1;
        } else {
            $a_item['show_company'] = "";
        }

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($comp_name) >= $options[2]) {
                $title = $myts->htmlSpecialChars(substr($comp_name, 0, ($options[2] - 1))) . "...";
            }
        }
        $a_item['title']     = $title;
        $a_item['company']   = $company;
        $a_item['type']      = $type;
        $a_item['typeprice'] = $typeprice;
        $a_item['town']      = $town;
        $a_item['state']     = $state;
        $a_item['id']        = $lid;

        $a_item['ltitle']
            = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/viewjobs.php?lid=$lid\"><b>$title</b></a>";

        if ($comp_img != "") {
            $a_item['logo_link'] = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/members.php?comp_id=" . $comp_id
                . "\"  /><img src=\"" . XOOPS_URL
                . "/modules/$blockdirname/logo_images/$comp_img\" alt=\"$comp_name\"  width=\"120px\" /></a>";
        } else {
            $a_item['logo_link'] = "";
        }

        $a_item['link'] = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/viewjobs.php?lid=$lid\">$title</a>";
        $a_item['date'] = formatTimestamp($date, "s");
        $a_item['hits'] = $view;

        $block['items'][] = $a_item;
    }
    $block['lang_title']     = constant($block_lang . "_ITEM");
    $block['lang_company']   = constant($block_lang . "_COMPANY");
    $block['lang_price']     = constant($block_lang . "_SALARY");
    $block['lang_typeprice'] = constant($block_lang . "_TYPEPRICE");
    $block['lang_date']      = constant($block_lang . "_DATE");
    $block['lang_local']     = constant($block_lang . "_LOCAL2");
    $block['lang_hits']      = constant($block_lang . "_HITS");
    $block['link']
                             =
        "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/\"><b>" . constant($block_lang . "_ALLANN2")
            . "</b></a></div>";
    $block['add']
                             =
        "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/\"><b>" . constant($block_lang . "_ADDNOW")
            . "</b></a></div>";

    return $block;
}

function jobs_block_premium_edit($options)
{
    global $xoopsDB;
    $blockdirname = basename(dirname(dirname(__FILE__)));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    $form = constant($block_lang . "_ORDER") . "&nbsp;<select name='options[]'>";
    $form .= "<option value='date'";
    if ($options[0] == 'date') {
        $form .= " selected='selected'";
    }
    $form .= '>' . constant($block_lang . "_DATE") . "</option>\n";
    $form .= "<option value='view'";
    if ($options[0] == 'view') {
        $form .= " selected='selected'";
    }
    $form .= '>' . constant($block_lang . "_HITS") . '</option>';
    $form .= "</select>\n";
    $form
        .=
        '&nbsp;' . constant($block_lang . "_DISP") . "&nbsp;<input type='text' name='options[]' value='" . $options[1]
            . "'/>&nbsp;" . constant($block_lang . "_LISTINGS");
    $form
        .= "&nbsp;<br /><br />" . constant($block_lang . "_CHARS") . "&nbsp;<input type='text' name='options[]' value='"
        . $options[2] . "'/>&nbsp;" . constant($block_lang . "_LENGTH") . '<br /><br />';

    return $form;
}
