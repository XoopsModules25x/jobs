<?php
//
// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //
include 'header.php';
if (isset($_GET['comp_id'])) {
    $comp_id = intval($_GET['comp_id']);
} else {
    redirect_header("index.php", 3, _JOBS_VALIDATE_FAILED);
}
xoops_header();

global $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsDB, $xoops_footer, $xoopsLogger;
$currenttheme = $xoopsConfig['theme_set'];

$result      = $xoopsDB->query(
    "select comp_img FROM " . $xoopsDB->prefix("jobs_companies") . " WHERE comp_id = "
        . mysql_real_escape_string($comp_id) . ""
);
$recordexist = $xoopsDB->getRowsNum($result);

if ($recordexist) {
    list($comp_img) = $xoopsDB->fetchRow($result);
    echo "<center><img src=\"logo_images/$comp_img\" border=0></center>";
}

echo "<center><table><tr><td><a href=#  onClick='window.close()'>" . _JOBS_CLOSEF . "</a></td></tr></table></center>";

xoops_footer();
