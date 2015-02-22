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

function resume_show($options)
{
    global $xoopsDB, $blockdirname, $block_lang;

    $block = array();
    $myts  =& MyTextSanitizer::getInstance();

    $blockdirname = basename(dirname(dirname(__FILE__)));
    $block_lang   = '_MB_' . strtoupper($blockdirname);

    include_once (XOOPS_ROOT_PATH . "/modules/$blockdirname/include/resume_functions.php");

    $block['title'] = "" . constant($block_lang . "_TITLE") . "";

    $result = $xoopsDB->query(
        "SELECT lid, cid, name, title, exp, expire, private, tel, salary, typeprice, date, email, submitter, usid, town, state, valid, resume, rphoto, view FROM "
            . $xoopsDB->prefix("" . $blockdirname . "_resume") . " WHERE valid='1' and status!='0' ORDER BY "
            . $options[0] . " DESC", $options[1], 0
    );

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $a_item    = array();
        $cat_name  = resume_getResCatNameFromId($myrow["cid"]);
        $name      = $myts->undoHtmlSpecialChars($myrow["name"]);
        $title     = $myts->undoHtmlSpecialChars($myrow["title"]);
        $exp       = $myts->htmlSpecialChars($myrow["exp"]);
        $expire    = $myts->htmlSpecialChars($myrow["expire"]);
        $private   = $myts->htmlSpecialChars($myrow["private"]);
        $tel       = $myts->htmlSpecialChars($myrow["tel"]);
        $salary    = $myts->htmlSpecialChars($myrow["salary"]);
        $typeprice = $myts->htmlSpecialChars($myrow["typeprice"]);
        $email     = $myts->htmlSpecialChars($myrow["email"]);
        $submitter = $myts->htmlSpecialChars($myrow["submitter"]);
        $usid      = $myts->htmlSpecialChars($myrow["usid"]);
        $town      = $myts->htmlSpecialChars($myrow["town"]);
        $state     = $myts->htmlSpecialChars($myrow["state"]);
        $resume    = $myts->htmlSpecialChars($myrow["resume"]);
        $view      = $myts->htmlSpecialChars($myrow["view"]);

        if (!XOOPS_USE_MULTIBYTES) {
            if (strlen($myrow['title']) >= $options[2]) {
                $title = $myts->htmlSpecialChars(substr($myrow['title'], 0, ($options[2] - 1))) . "...";
            }
        }
        $a_item['title']     = $title;
        $a_item['cat_name']  = $cat_name;
        $a_item['name']      = $name;
        $a_item['exp']       = $exp;
        $a_item['expire']    = $expire;
        $a_item['private']   = $private;
        $a_item['tel']       = $tel;
        $a_item['salary']    = $salary;
        $a_item['typeprice'] = $typeprice;
        $a_item['email']     = $email;
        $a_item['submitter'] = $submitter;
        $a_item['town']      = $town;
        $a_item['state']     = $state;
        $a_item['resume']    = $resume;
        $a_item['view']      = $view;
        $a_item['id']        = $myrow['lid'];
        $a_item['cid']       = $myrow['cid'];
        $a_item['link']
                             =
            "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/viewresume.php?lid=" . addslashes($myrow['lid'])
                . "\"><b>$title</b></a>";
        $a_item['date']      = formatTimestamp($myrow['date'], "s");

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
        "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/resumes.php\"><b>" . constant($block_lang . "_ALL_LISTINGS")
            . "</b></a>";
    $block['add']
                             =
        "<a href=\"" . XOOPS_URL . "/modules/$blockdirname/resumes.php\"><b>" . constant($block_lang . "_ADDRESNOW")
            . "</b></a>";

    return $block;
}

function resume_edit($options)
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
    $form .= "<option value='hits'";
    if ($options[0] == 'hits') {
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
