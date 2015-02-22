<?php
//  -----------------------------------------------------------------------  //
//                           Jobs for Xoops 2.0x                             //
//                  By John Mordo from the myAds 2.04 Module                 //
//                    All Original credits left below this                   //
//                                                                           //
//                                                                           //
//                                                                           //
//
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //

function jobs_show($options)
{
    global $xoopsDB, $blockdirname, $block_lang;

    $block = array();
    $myts  =& MyTextSanitizer::getInstance();

    $blockdirname = basename(dirname(dirname(__FILE__)));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    include_once (XOOPS_ROOT_PATH . "/modules/$blockdirname/include/functions.php");

    $block['title'] = "" . constant($block_lang . "_TITLE") . "";

    $cat_perms  = "";
    $categories = jobs_MygetItemIds('jobs_view');
    if (is_array($categories) && count($categories) > 0) {
        $cat_perms .= ' AND cid IN (' . implode(',', $categories) . ') ';
    }

    $result = $xoopsDB->query(
        "SELECT lid, cid, title, status, expire, type, company, desctext, requirements, tel, price, typeprice, contactinfo, date, email, submitter, usid, town, state, valid, photo, view FROM "
            . $xoopsDB->prefix("" . $blockdirname . "_listing")
            . " WHERE valid='1' and status!='0' $cat_perms ORDER BY " . $options[0] . " DESC", $options[1], 0
    );

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $a_item = array();

        $cat_id    = jobs_getCompIdFromName($myrow["company"]);
        $cat_name  = jobs_getCatNameFromId($myrow["cid"]);
        $title     = $myts->undoHtmlSpecialChars($myrow["title"]);
        $status    = $myts->htmlSpecialChars($myrow["status"]);
        $expire    = $myts->htmlSpecialChars($myrow["expire"]);
        $type      = $myts->htmlSpecialChars($myrow["type"]);
        $company   = $myts->undoHtmlSpecialChars($myrow["company"]);
        $price     = $myts->htmlSpecialChars($myrow["price"]);
        $typeprice = $myts->htmlSpecialChars($myrow["typeprice"]);
        $submitter = $myts->htmlSpecialChars($myrow["submitter"]);
        $town      = $myts->htmlSpecialChars($myrow["town"]);
        $state     = $myts->htmlSpecialChars($myrow["state"]);
        $view      = $myts->htmlSpecialChars($myrow["view"]);

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = $myts->htmlSpecialChars(substr($myrow['title'], 0, ($options[2] - 1))) . "...";
            }
        }

        $a_item['title']        = $title;
        $a_item['cat_name']     = $cat_name;
        $a_item['company']      = $company;
        $a_item['type']         = $type;
        $a_item['expire']       = $expire;
        $a_item['price']        = $price;
        $a_item['typeprice']    = $typeprice;
        $a_item['typeprice']    = $typeprice;
        $a_item['submitter']    = $submitter;
        $a_item['town']         = $town;
        $a_item['state']        = $state;
        $a_item['view']         = $view;
        $a_item['id']           = $myrow['lid'];
        $a_item['cid']          = $myrow['cid'];
        $a_item['company_link'] = "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/members.php?comp_id=" . $cat_id
            . "\"><b>$company</b></a>";
        $a_item['link']
                                =
            "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/viewjobs.php?lid=" . addslashes($myrow['lid'])
                . "\"><b>$title</b></a>";
        $a_item['date']         = formatTimestamp($myrow['date'], "s");

        $block['items'][] = $a_item;
    }
    $block['lang_title']     = constant($block_lang . "_ITEM");
    $block['lang_salary']    = constant($block_lang . "_SALARY");
    $block['lang_typeprice'] = constant($block_lang . "_TYPEPRICE");
    $block['lang_date']      = constant($block_lang . "_DATE");
    $block['lang_local']     = constant($block_lang . "_LOCAL2");
    $block['lang_hits']      = constant($block_lang . "_HITS");
    $block['link']
                             =
        "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/index.php\"><b>" . constant($block_lang . "_ALLANN2")
            . "</b></a></div>";
    $block['add']
                             =
        "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/index.php\"><b>" . constant($block_lang . "_ADDNOW")
            . "</b></a></div>";

    return $block;
}

function jobs_edit($options)
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
