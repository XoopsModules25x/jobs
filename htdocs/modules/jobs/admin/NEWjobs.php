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
echo $index_admin->addNavigation("jobs.php");
$index_admin->addItemButton(_AM_JOBS_ADD_LINK, 'submitlisting.php', 'add', '');
echo $index_admin->renderButton('left', '');
//loadModuleAdminMenu(3, "");

include XOOPS_ROOT_PATH . '/class/pagenav.php';

$countresult = $xoopsDB->query("select COUNT(*) FROM " . $xoopsDB->prefix("jobs_listing") . "");
list($crow) = $xoopsDB->fetchRow($countresult);
$crows = $crow;

$nav = '';
if ($crows > "0") {
// shows number of jobs per page default = 15
    $showonpage = 15;
    $show       = "";
    $show       = (intval($show) > 0) ? intval($show) : $showonpage;

    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    if (!isset($max)) {
        $max = $start + $show;
    }

    $sql
        = "select lid, title, date, status, expire, submitter, valid, premium from " . $xoopsDB->prefix("jobs_listing")
        . " ORDER BY lid";

    $result1 = $xoopsDB->query($sql, $show, $start);
    echo "<table border=1 width=100% cellpadding=2 cellspacing=0 border=0><td><tr>";

    
    
    
           if ($crows > 0) {
            $nav = new XoopsPageNav($crows, $showonpage, $start, 'start', 'op=Region');
            echo "<br />" . _AM_JOBS_THEREIS . " <b>$crows</b> " . _AM_JOBS_JOBLISTINGS . "<br /><br />";
            echo $nav->renderNav();

            
            
            
            
            echo "<br /><br /><table width=100% cellpadding=2 cellspacing=0 border=0>";
            $rank = 1;
        }
    
    echo "<table width='100%' cellspacing='1' class='outer'>
 				<tr>
 					<th align=\"center\">" . _AM_JOBS_NUMANN . "</th>
 					<th align=\"center\">" . _AM_JOBS_TITLE2 . "</th>
 						<th align=\"center\">" . _AM_JOBS_SUBMITTED_ON . "</th>
 						<th align=\"center\">" . _AM_JOBS_ACTIVE . "</th>
 						<th align=\"center\">" . _AM_JOBS_EXPIRES . "</th>
 						<th align=\"center\">" . _AM_JOBS_SENDBY . "</th>
 						<th align=\"center\">" . _AM_JOBS_PUBLISHEDCAP . "</th>
 						<th align=\"center\">" . _AM_JOBS_PREMIUM . "</th>

 					<th align='center' width='10%'>" . _AM_JOBS_ACTIONS . "</th>
 				</tr>";

    $class   = "odd";
    $result1 = $xoopsDB->query($sql, $show, $start);
    
    
    
    
    
    
    
    while (list($lid, $title, $date, $status, $expire, $submitter, $valid, $premium) = $xoopsDB->fetchRow($result1)) {
        $title = $myts->htmlSpecialChars($title);
        $date2 = formatTimestamp($date, "s");
        // $expire2     = formatTimestamp($expire, "s");

        echo "<tr class='" . $class . "'>";
        $class = ($class == "even") ? "odd" : "even";
        echo "<td align=\"center\">" . $lid."</td>";
        echo "<td align=\"center\">" . $title . "</td>";
        echo "<td align=\"center\">" . $date2 . "</td>";
        echo "<td align=\"center\">" . $status . "</td>";
        echo "<td align=\"center\">" . $expire . "</td>";
        echo "<td align=\"center\">" . $submitter . "</td>";
        echo "<td align=\"center\">" . $valid . "</td>";
        echo "<td align=\"center\">" . $premium . "</td>";

        echo "<td align='center' width='10%'>
 						<a href='modjobs.php?lid=" . $lid . "'><img src=" . $pathIcon16 . "/edit.png alt='"
            . _EDIT . "' title='" . _EDIT . "'></a>
 						<a href='../deljob.php?lid=" . $lid . "'><img src=" . $pathIcon16 . "/delete.png alt='"
            . _DELETE . "' title='" . _DELETE . "'></a>
 						</td>";
        echo "</tr>";

    }
    echo "</table><br /><br />";
        echo $nav->renderNav();
//    echo "</fieldset><br />";
} else {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_JOB . "</legend>";
    echo "<br /> " . _AM_JOBS_NO_JOB . "<br /><br />";
    echo "</fieldset>";

//	echo "<fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_ADD_COMPANY . "</legend>";
//    echo "<a href=\"addcomp.php\">" . _AM_JOBS_ADD_COMPANY . "</a></fieldset>
//	</table<br />";
}

//include_once 'category3.php';
//$action='';
//if ($action === false) {
//	$action = $_SERVER["REQUEST_URI"];
//}
//$title='stnihsathsthsths';
//$form = new myTableForm($title, "form", $action, "post", true);
//$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
//$form->display();

include_once 'job_categories.php';

xoops_cp_footer();

?>