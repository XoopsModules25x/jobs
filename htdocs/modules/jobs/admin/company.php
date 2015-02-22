<?php
//                 Jobs for Xoops 2.3.3b and up  by John Mordo - jlm69 at Xoops              //
//                                                                                           //
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

include_once(XOOPS_ROOT_PATH . "/modules/$mydirname/class/jobtree.php");

$myts =& MyTextSanitizer::getInstance();

include 'admin_header.php';
xoops_cp_header();
$index_admin = new ModuleAdmin();
echo $index_admin->addNavigation("company.php");
$index_admin->addItemButton(_AM_JOBS_ADD_COMPANY, 'addcomp.php', 'add', '');
echo $index_admin->renderButton('left', '');
//loadModuleAdminMenu(3, "");

include XOOPS_ROOT_PATH . '/class/pagenav.php';

$countresult = $xoopsDB->query("select COUNT(*) FROM " . $xoopsDB->prefix("jobs_companies") . "");
list($crow) = $xoopsDB->fetchRow($countresult);
$crows = $crow;

$nav = '';
if ($crows > "0") {
// shows number of companies per page default = 15
    $showonpage = 15;
    $show       = "";
    $show       = (intval($show) > 0) ? intval($show) : $showonpage;

    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    if (!isset($max)) {
        $max = $start + $show;
    }

    $sql
        = "select comp_id, comp_name, comp_date_added, comp_city, comp_state, comp_usid from "
        . $xoopsDB->prefix("jobs_companies") . " ORDER BY comp_name";

    $result1 = $xoopsDB->query($sql, $show, $start);
    echo "<table border=1 width=100% cellpadding=2 cellspacing=0 border=0><td><tr>";

    echo "<table width='100%' cellspacing='1' class='outer'>
                 <tr>
                     <th align=\"center\">" . _AM_JOBS_COMP_NUMBER . "</th>
                     <th align=\"center\">" . _AM_JOBS_COMP_NAME . "</th>
                         <th align=\"center\">" . _AM_JOBS_SUBMITTER . "</th>
                         <th align=\"center\">" . _AM_JOBS_SUBMITTED_ON . "</th>
                         <th align=\"center\">" . _AM_JOBS_COMP_CITY . "</th>
                         <th align=\"center\">" . _AM_JOBS_COMP_STATE . "</th>

                     <th align='center' width='10%'>" . _AM_JOBS_ACTIONS . "</th>
                 </tr>";

    $class   = "odd";
    $result1 = $xoopsDB->query($sql, $show, $start);
    while (list($comp_id, $comp_name, $comp_date_added, $comp_city, $comp_state, $comp_usid) = $xoopsDB->fetchRow($result1)) {
        $comp_name = $myts->htmlSpecialChars($comp_name);
        $date2     = formatTimestamp($comp_date_added, "s");

        echo "<tr class='" . $class . "'>";
        $class = ($class == "even") ? "odd" : "even";
        echo "<td align=\"center\">$comp_id</td>";
        echo "<td align=\"center\">" . $comp_name . "</td>";
        echo "<td align=\"center\">" . $comp_usid . "</td>";
        echo "<td align=\"center\">" . $date2 . "</td>";
        echo "<td align=\"center\">" . $comp_city . "</td>";
        echo "<td align=\"center\">" . $comp_state . "</td>";

        echo "<td align='center' width='10%'>
                         <a href='modcomp.php?comp_id=" . $comp_id . "'><img src=" . $pathIcon16 . "/edit.png alt='"
            . _EDIT . "' title='" . _EDIT . "'></a>
                         <a href='delcomp.php?comp_id=" . $comp_id . "&comp_name=" . $comp_name . "'><img src="
            . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                         </td>";
        echo "</tr>";

    }
    echo "</table><br /><br />";

//    echo "</fieldset><br />";
} else {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_COMPANY . "</legend>";
    echo "<br /> " . _AM_JOBS_NOCOMPANY . "<br /><br />";
    echo "</fieldset>";

//	echo "<fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_ADD_COMPANY . "</legend>";
//    echo "<a href=\"addcomp.php\">" . _AM_JOBS_ADD_COMPANY . "</a></fieldset>
//	</table<br />";
}
xoops_cp_footer();
