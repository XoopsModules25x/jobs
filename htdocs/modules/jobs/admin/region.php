<?php
//  -----------------------------------------------------------------------------------------//
//                             Jobs for Xoops 2.3.3b and up                                  //
//                            by John Mordo - jlm69 at Xoops                                 //                              //                                                                                           //
//  -----------------------------------------------------------------------------------------//
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php");
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$myts =& MyTextSanitizer::getInstance();
#  function Index
#####################################################
function Region()
{
    global $hlpfile, $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts, $mydirname;

    include 'admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(4, "");
    $index_admin = new ModuleAdmin();
    echo $index_admin->addNavigation("region.php");
    $index_admin->addItemButton(_AM_JOBS_ADD_REGION, 'addregion.php', 'add', '');
    $index_admin->addItemButton(_AM_JOBS_LISTS, 'lists.php', 'list', '');
    echo $index_admin->renderButton('left', '');

    include XOOPS_ROOT_PATH . '/class/pagenav.php';

    $countresult = $xoopsDB->query("select COUNT(*) FROM " . $xoopsDB->prefix("jobs_region") . "");
    list($crow) = $xoopsDB->fetchRow($countresult);
    $crows = $crow;

    $nav = '';
    if ($crows > "0") {
// shows number of companies per page default = 15
        $showonpage = 50;
        $show       = "";
        $show       = (intval($show) > 0) ? intval($show) : $showonpage;

        $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
        if (!isset($max)) {
            $max = $start + $show;
        }

        $sql = "select rid, pid, name, abbrev from " . $xoopsDB->prefix("jobs_region") . " ORDER BY rid";

        $result1 = $xoopsDB->query($sql, $show, $start);
        echo "<table border=1 width=100% cellpadding=2 cellspacing=0 border=0><td><tr>";
        if ($crows > 0) {
            $nav = new XoopsPageNav($crows, $showonpage, $start, 'start', 'op=Region');
//	echo "<fieldset><legend style='font-weight: bold; color: #900;'>"._AM_JOBS_MAN_REGION."</legend>";
//	echo "<br />"._AM_JOBS_THEREIS." <b>$crows</b> "._AM_JOBS_REGIONS."<br /><br />";
//	echo "<fieldset><legend style='font-weight: bold; color:#900;'>"._AM_JOBS_ADD_REGION."</legend>";
//	echo "<br /><a href=\"addregion.php\">"._AM_JOBS_ADD_REGION."</a>
//
//
//<br /><br /><br /><br /><a href=\"lists.php\">"._AM_JOBS_LISTS."</a><br />";
//echo "</td></tr>
//</fieldset>";
            echo "<br />" . _AM_JOBS_THEREIS . " <b>$crows</b> " . _AM_JOBS_REGIONS . "<br /><br />";
//	echo "</td></tr></table>";
            echo $nav->renderNav();

            echo "<br /><br /><table width=100% cellpadding=2 cellspacing=0 border=0>";
            $rank = 1;
        }
        while (list($rid, $pid, $name, $abbrev) = $xoopsDB->fetchRow($result1)) {

            $name = $myts->htmlSpecialChars($name);

            if (is_integer($rank / 2)) {
                $color = "even";
            } else {
                $color = "odd";
            }

            echo "<tr class='$color'><td><a href=\"region.php?op=ModRegion&amp;rid=$rid\">$name</a></td></tr>";
            $rank++;
        }

        echo "</table><br />";
        echo "</fieldset><br />";
        echo $nav->renderNav();
    } else {
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MAN_REGION . "</legend>";
        echo "<br /> " . _AM_JOBS_NOREGION . "<br /><br />";

        echo "<br /> " . _AM_JOBS_INSTALL_NOW . "<br /><br />";
        echo "<a href=\"include/usstates.php\">" . _AM_JOBS_US_STATES . "</a><br />";
        echo "<a href=\"include/canada.php\">" . _AM_JOBS_CANADA_STATES . "</a><br />";
        echo "<a href=\"include/france.php\">" . _AM_JOBS_FRANCE . "</a><br />";
        echo "<a href=\"include/italy.php\">" . _AM_JOBS_ITALY . "</a><br />";
        echo "<a href=\"include/england.php\">" . _AM_JOBS_ENGLAND . "</a><br />";
        echo "</fieldset>

    <fieldset><legend style='font-weight: bold; color:#900;'>" . _AM_JOBS_ADD_REGION . "</legend>";
        echo "<a href=\"addregion.php\">" . _AM_JOBS_ADD_REGION . "</a></fieldset>
    </table<br />";
    }
    xoops_cp_footer();
}

#  function ModRegion
#####################################################
function ModRegion($rid = 0)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $myts, $mydirname;

    include 'admin_header.php';
    xoops_cp_header();
    //loadModuleAdminMenu(4, "");

    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_JOBS_MOD_REGION . "</legend>";

    $result = $xoopsDB->query(
        "select rid, pid, name, abbrev from " . $xoopsDB->prefix("jobs_region") . " where rid=$rid"
    );

    while (list($rid, $pid, $name, $abbrev) = $xoopsDB->fetchRow($result)) {

        $name   = $myts->htmlSpecialChars($name);
        $abbrev = $myts->htmlSpecialChars($abbrev);

        echo "<form action=\"region.php\" method=\"post\">
    <table class=\"outer\" border=\"1\"><tr>
    <td class=\"head\">" . _AM_JOBS_REGION_NUMBER . " </td><td class=\"head\">$rid</td>
    </tr><tr>
    <td class=\"head\">" . _AM_JOBS_REGION_NAME . " </td><td class=\"head\"><input type=\"text\" name=\"name\" size=\"30\" value=\"$name\"></td>
    </tr><tr>
    <td class=\"head\">" . _AM_JOBS_REGION_ABBREV . " </td><td class=\"head\"><input type=\"text\" name=\"abbrev\" size=\"30\" value=\"$abbrev\"></td>
    </tr>";

        $time = time();

        echo "<tr>
    <td>&nbsp;</td><td>

    <select name=\"op\">
    <option value=\"ModRegionS\"> " . _AM_JOBS_MODIF . "
    <option value=\"RegionDel\"> " . _AM_JOBS_DEL . "
    </select><input type=\"submit\" value=\"" . _AM_JOBS_GO . "\"></td>
    </tr></table>";
        echo "<input type=\"hidden\" name=\"pid\" value=\"0\">
    <input type=\"hidden\" name=\"rid\" value=\"$rid\">";

        echo "</form><br />";
    }
    echo "</fieldset><br />";
    xoops_cp_footer();
}

#  function ModRegionS
#####################################################
function ModRegionS($rid = 0, $pid = 0, $name, $abbrev)
{
    global $xoopsDB, $xoopsConfig, $xoopsModuleConfig, $myts, $mydirname;

    $date = time();

    $pid    = $myts->addSlashes($pid);
    $name   = $myts->addSlashes($name);
    $abbrev = $myts->addSlashes($abbrev);

    $xoopsDB->query(
        "update " . $xoopsDB->prefix("" . $mydirname . "_region")
            . " set pid='$pid', name='$name', abbrev='$abbrev' where rid=$rid"
    );

    redirect_header("region.php", 3, _AM_JOBS_REGION_MODIFIED);
    exit();
}

function RegionDel($rid = 0, $ok = 0)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsTheme, $xoopsLogger, $mydirname;

    $result = $xoopsDB->query(
        "select name, abbrev FROM " . $xoopsDB->prefix("jobs_region") . " where rid=" . mysql_real_escape_string($rid)
            . ""
    );
    list($name, $abbrev) = $xoopsDB->fetchRow($result);

    $ok          = !isset($_REQUEST['ok']) ? NULL : $_REQUEST['ok'];
    $member_usid = $xoopsUser->getVar("uid", "E");
    if ($ok == 1) {

// Delete Region
        $xoopsDB->queryf(
            "delete from " . $xoopsDB->prefix("jobs_region") . " where rid=" . mysql_real_escape_string($rid) . ""
        );

        redirect_header("region.php", 3, _AM_JOBS_REGION_DEL);
        exit();
    } else {
        xoops_cp_header();
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='8'><tr class='bg4'><td valign='top'>\n";
        echo "<br /><center>";
        echo "<b>" . _AM_JOBS_SURDELREGION . "</b><br /><br />";
    }
    echo"[ <a href=\"region.php?op=RegionDel&amp;rid=" . addslashes($rid) . "&amp;ok=1\">" . _AM_JOBS_YES
        . "</a> | <a href=\"index.php\">" . _AM_JOBS_NO . "</a> ]<br /><br />";
    echo "</td></tr></table>";
    xoops_cp_footer();
}

#####################################################
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if (!isset($_POST['rid']) && isset($_GET['rid'])) {
    $rid = $_GET['rid'];
}
if (!isset($_POST['ok']) && isset($_GET['ok'])) {
    $ok = $_GET['ok'];
}
if (!isset($_POST['op']) && isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (!isset($op)) {
    $op = '';
}

switch ($op) {

case "ModRegion":
    ModRegion($rid);
    break;

case "ModRegionS":
    ModRegionS($rid, $pid, $name, $abbrev);
    break;

case "RegionDel":
    RegionDel($rid);
    break;

default:
    Region();
    break;
}
