<?php
//                     Jobs Module for Xoops                                 //
//        By John Mordo user jlm69 at www.xoops.org                          //
//          Find it or report problems at www.jlmzone.com                    //
//                                                                           //
//                    Licence Type   : GPL                                   //
// ------------------------------------------------------------------------- //

include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
$doc_lang  = '_AM_' . strtoupper($mydirname);

global $mytree, $xoopsDB, $xoopsModuleConfig, $mydirname;

include 'admin_header.php';
xoops_cp_header();
//loadModuleAdminMenu(4, "");

echo"<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . constant(
    $doc_lang . "_DOCUMENTATION"
) . " <br /><br /></legend>";
echo "<b>" . constant($doc_lang . "_VERSION") . "</b><br /><br />";
echo "" . constant($doc_lang . "_INCOMPLETE") . "<br /><br />";
echo "<a href=\"jobs_doc_1.php\"><b>" . constant($doc_lang . "_COMPANY_DOCS") . "</b><br /><br /></a>";

echo "<br /></fieldset><br />";
xoops_cp_footer();
