<?php
//                     Jobs Module for Xoops                                 //
//      Redesigned by John Mordo user jlm69 at www.xoops.org                 //
//          Find it or report problems at www.jlmzone.com                    //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////

include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
$doc_lang  = '_DOC_' . strtoupper($mydirname);

if (file_exists("../language/" . $xoopsConfig['language'] . "/docs.php")) {
    include '../language/'. $xoopsConfig['language'] . "/docs.php");
} else {
    include '../language/english/docs.php';
}

global $mytree, $xoopsDB, $xoopsModuleConfig, $mydirname;

include 'admin_header.php';
xoops_cp_header();
//loadModuleAdminMenu(4, "");
echo"<fieldset style='padding: 5px;'><legend style='font-weight: bold; color: #900;'>" . constant(
    $doc_lang . "_DOCUMENTATION"
) . " <br /><br /></legend>";
echo "<b>" . constant($doc_lang . "_VERSION") . "</b><br /><br />
<b>" . constant($doc_lang . "_COMPANY_DOCS") . "</b><br /><br />
<br />" . constant($doc_lang . "_DOC_1") . "
<br />
<br />" . constant($doc_lang . "_DOC_2") . "
<br />
<br />
" . constant($doc_lang . "_DOC_3") . "
<br /><br />
" . constant($doc_lang . "_DOC_4") . "<br />
<br />

<br /><br />
<br />
<br /><br /><br />";

echo "<br /></fieldset><br />";
xoops_cp_footer();
