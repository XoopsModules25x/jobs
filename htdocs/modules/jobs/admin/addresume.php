<?php
//  -----------------------------------------------------------------------  //
//                          Jobs Module for Xoops                            //
//                       John Mordo - jlm69 at Xoops                         //
//                          Licence Type   : GPL                             //
// ------------------------------------------------------------------------- //
include 'admin_header.php';
include_once '../../../include/cp_header.php';
$mydirname = basename(dirname(dirname(__FILE__)));
include_once (XOOPS_ROOT_PATH . "/modules/$mydirname/include/resume_functions.php");
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/class/restree.php";
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$myts =& MyTextSanitizer::getInstance();

$module_id = $xoopsModule->getVar('mid');

if (isset($_POST["cid"])) {
    $cid = intval($_POST["cid"]);
} else {
    if (isset($_GET["cid"])) {
        $cid = intval($_GET["cid"]);
    }
}

$member_usid = $xoopsUser->getVar("uid", "E");

if (!empty($_POST['submit'])) {

    $resumesize  = $xoopsModuleConfig['jobs_resumesize'];
    $photomax    = $xoopsModuleConfig['jobs_maxfilesize'];
    $destination = XOOPS_ROOT_PATH . "/modules/$mydirname/resumes";

    $_SESSION['name']        = $_POST['name'];
    $_SESSION['title']       = $_POST['title'];
    $_SESSION['status']      = $_POST['status'];
    $_SESSION['exp']         = $_POST['exp'];
    $_SESSION['expire']      = $_POST['expire'];
    $_SESSION['private']     = $_POST['private'];
    $_SESSION['tel']         = $_POST['tel'];
    $_SESSION['salary']      = $_POST['salary'];
    $_SESSION['typeprice']   = $_POST['typeprice'];
    $_SESSION['submitter']   = $_POST['submitter'];
    $_SESSION['town']        = $_POST['town'];
    $_SESSION['state']       = $_POST['state'];
    $_SESSION['make_resume'] = $_POST['make_resume'];
    $_SESSION['email']       = $_POST['email'];

    $name        = $myts->addSlashes($_POST['name']);
    $title       = $myts->addSlashes($_POST['title']);
    $status      = $myts->addSlashes($_POST['status']);
    $exp         = $myts->addSlashes($_POST['exp']);
    $expire      = $myts->addSlashes($_POST['expire']);
    $private     = $myts->addSlashes($_POST['private']);
    $tel         = $myts->addSlashes($_POST['tel']);
    $salary      = $myts->addSlashes($_POST['salary']);
    $typeprice   = $myts->addSlashes($_POST['typeprice']);
    $submitter   = $myts->addSlashes($_POST['submitter']);
    $town        = $myts->addSlashes($_POST['town']);
    $state       = $myts->addSlashes($_POST['state']);
    $make_resume = $myts->addSlashes($_POST['make_resume']);
    $valid       = $myts->addSlashes($_POST["valid"]);
    $email       = $myts->addSlashes($_POST["email"]);
    $usid        = $myts->addSlashes($member_usid);
    $date        = time();

    $filename = '';

    if (!empty($_FILES['resume']['name'])) {
        include_once XOOPS_ROOT_PATH . "/class/uploader.php";
        $updir             = $destination;
        $allowed_mimetypes = array('application/msword', 'application/pdf');
        $uploader          = new XoopsMediaUploader($updir, $allowed_mimetypes, $resumesize);
        $uploader->setTargetFileName($date . '_' . $_FILES['resume']['name']);
        $uploader->fetchMedia('resume');
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();
            redirect_header("addresume.php?cid=" . addslashes($cid) . "", 3, $errors);

            return FALSE;
            exit();
        } else {
            $filename = $uploader->getSavedFileName();
        }
    }

    $xoopsDB->query(
        "INSERT INTO " . $xoopsDB->prefix("jobs_resume")
            . " values ('', '$cid', '$name', '$title', '$status', '$exp', '$expire', '$private', '$tel', '$salary', '$typeprice', '$date', '$email', '$submitter', '$usid',  '$town',  '$state',  '$valid', '', '$filename', '0')"
    );

    unset ($_SESSION['cid']);
    unset ($_SESSION['name']);
    unset ($_SESSION['title']);
    unset ($_SESSION['status']);
    unset ($_SESSION['exp']);
    unset ($_SESSION['expire']);
    unset ($_SESSION['private']);
    unset ($_SESSION['tel']);
    unset ($_SESSION['salary']);
    unset ($_SESSION['typeprice']);
    unset ($_SESSION['submitter']);
    unset ($_SESSION['town']);
    unset ($_SESSION['state']);
    unset ($_SESSION['make_resume']);
    unset ($_SESSION['email']);

    $lid = $xoopsDB->getInsertId();
    if ($valid == '1') {

        $notification_handler =& xoops_gethandler('notification');

        $tags                     = array();
        $tags['TITLE']            = $title;
        $tags['EXP']              = $exp;
        $tags['NAME']             = $name;
        $tags['HELLO']            = _AM_JOBS_HELLO;
        $tags['WEBMASTER']        = _AM_JOBS_WEBMASTER;
        $tags['ADDED_TO_RES_CAT'] = _AM_JOBS_ADDED_TO_RES_CAT;
        $tags['FOLLOW_LINK']      = _AM_JOBS_FOLLOW_LINK;
        $tags['RECIEVING_NOTIF']  = _AM_JOBS_RECIEVING_NOTIF;
        $tags['ERROR_NOTIF']      = _AM_JOBS_ERROR_NOTIF;
        $tags['LINK_URL']
                                  =
            XOOPS_URL . '/modules/' . $mydirname . '/main.php?pa=viewlistings' . '&lid=' . addslashes($lid);
        $sql                      = "SELECT title FROM " . $xoopsDB->prefix("jobs_res_categories") . " WHERE cid="
            . mysql_real_escape_string($cid) . "";
        $result                   = $xoopsDB->query($sql);
        $row                      = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_TITLE']   = $row['title'];
        $tags['CATEGORY_URL']
                                  =
            XOOPS_URL . '/modules/' . $mydirname . '/main.php?pa=viewResume&cid="' . addslashes($cid);
        $notification_handler     =& xoops_gethandler('notification');
        $notification_handler->triggerEvent('res_global', 0, 'new_resume', $tags);
        $notification_handler->triggerEvent('resume_category', $cid, 'new_resume_cat', $tags);
        $notification_handler->triggerEvent('resume_listing', $lid, 'new_resume', $tags);
    }

    if ($make_resume != "0") {
        redirect_header("../createresume.php?lid=" . addslashes($lid) . "", 4, _AM_JOBS_RES_ADDED_PLUS);
    } else {
        redirect_header("resumes.php", 3, _AM_JOBS_RES_ADDED);
        exit();
    }

} else {

    xoops_cp_header();
    //loadModuleAdminMenu(0, "");
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('resumes.php');
    $indexAdmin->addItemButton(_AM_JOBS_MAN_RESUME, 'resumes.php', 'list');
    echo $indexAdmin->renderButton('left', '');

    $mytree = new ResTree($xoopsDB->prefix("jobs_res_categories"), "cid", "pid");

    if (isset($_POST["cid"])) {
        $cid = intval($_POST["cid"]);
    } else {
        if (isset($_GET["cid"])) {
            $cid = intval($_GET["cid"]);
        } else {
            $cid = 0;
        }
    }

    $member_id = $xoopsUser->getVar("uid", "E");

    $resdays     = $xoopsModuleConfig['jobs_res_days'];
    $resumesize  = $xoopsModuleConfig['jobs_resumesize'];
    $resumesize1 = $xoopsModuleConfig['jobs_resumesize'] / 1024;
    $photomax    = $xoopsModuleConfig['jobs_maxfilesize'];
    $photomax1   = $xoopsModuleConfig['jobs_maxfilesize'] / 1024;

    list($numrows) = $xoopsDB->fetchRow(
        $xoopsDB->query(
            "select cid, title, affprice from " . $xoopsDB->prefix("jobs_res_categories") . ""
        )
    );

    if ($numrows > 0) {

        if ($xoopsUser) {
            $iddd = $xoopsUser->getVar("uid", "E");
            $idd  = $xoopsUser->getVar("name", "E"); // Real name
            $idde = $xoopsUser->getVar("email", "E");
            $iddn = $xoopsUser->getVar("uname", "E"); // user name
        }
        $time = time();

        $_SESSION['cid']         = !empty($_SESSION['cid']) ? $_SESSION['cid'] : "";
        $_SESSION['name']        = !empty($_SESSION['name']) ? $_SESSION['name'] : "";
        $_SESSION['title']       = !empty($_SESSION['title']) ? $_SESSION['title'] : "";
        $_SESSION['status']      = !empty($_SESSION['status']) ? $_SESSION['status'] : "";
        $_SESSION['exp']         = !empty($_SESSION['exp']) ? $_SESSION['exp'] : "";
        $_SESSION['expire']      = !empty($_SESSION['expire']) ? $_SESSION['expire'] : "";
        $_SESSION['private']     = !empty($_SESSION['private']) ? $_SESSION['private'] : "";
        $_SESSION['tel']         = !empty($_SESSION['tel']) ? $_SESSION['tel'] : "";
        $_SESSION['salary']      = !empty($_SESSION['salary']) ? $_SESSION['salary'] : "";
        $_SESSION['typeprice']   = !empty($_SESSION['typeprice']) ? $_SESSION['typeprice'] : "";
        $_SESSION['submitter']   = !empty($_SESSION['submitter']) ? $_SESSION['submitter'] : "";
        $_SESSION['town']        = !empty($_SESSION['town']) ? $_SESSION['town'] : "";
        $_SESSION['state']       = !empty($_SESSION['state']) ? $_SESSION['state'] : "";
        $_SESSION['make_resume'] = !empty($_SESSION['make_resume']) ? $_SESSION['make_resume'] : "";
        $_SESSION['email']       = !empty($_SESSION['email']) ? $_SESSION['email'] : "";

        $result  = $xoopsDB->query("select nom_type from " . $xoopsDB->prefix("jobs_type") . " order by nom_type");
        $result1 = $xoopsDB->query("select nom_price from " . $xoopsDB->prefix("jobs_price") . " order by id_price");
        $result2 = $xoopsDB->query("select rid, name from " . $xoopsDB->prefix("jobs_region") . " order by rid");

        ob_start();
        $form = new XoopsThemeForm(_AM_JOBS_ADD_RESUME, 'submitform', 'addresume.php');
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(
            new XoopsFormText(_AM_JOBS_RES_PRIVATE, 'private', 10, 10, "" . $_SESSION['private'] . ""), FALSE
        );

        $cat_form = (new XoopsFormSelect(_AM_JOBS_CAT, 'cid', $_SESSION['cid']));
        $cattree  = $mytree->resume_getChildTreeArray(0, "title ASC");
        $cat_form->addOption('', _AM_JOBS_SELECTCAT);
        foreach ($cattree as $branch) {
            $branch['prefix'] = substr($branch['prefix'], 0, -1);
            $branch['prefix'] = str_replace(".", "--", $branch['prefix']);
            $cat_form->addOption($branch['cid'], $branch['prefix'] . $branch['title']);
        }
        $form->addElement($cat_form, TRUE);

        $radio        = new XoopsFormRadio(_AM_JOBS_STATUS, 'status', "" . $_SESSION['status'] . "1");
        $options["1"] = _AM_JOBS_ACTIVE;
        $options["0"] = _AM_JOBS_INACTIVE;
        $radio->addOptionArray($options);
        $form->addElement($radio, TRUE);

        $form->addElement(new XoopsFormText(_AM_JOBS_RES_NAME, "name", 40, 50, "" . $_SESSION['name'] . ""), TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_RES_HOW_LONG, "expire", 40, 50, $resdays), TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_TITLE2, "title", 40, 50, "" . $_SESSION['title'] . ""), TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_RES_EXP, "exp", 40, 50, "" . $_SESSION['exp'] . ""), TRUE);
        $form->addElement(new XoopsFormText(_AM_JOBS_PRICE2, "salary", 40, 50, "" . $_SESSION['salary'] . ""), FALSE);
        $sel_form = new XoopsFormSelect(_AM_JOBS_SALARYTYPE, "typeprice", "" . $_SESSION['typeprice'] . "", "1", FALSE);
        while (list($nom_price) = $xoopsDB->fetchRow($result1)) {
            $sel_form->addOption($nom_price, $nom_price);
        }
        $form->addElement($sel_form);

        if ($idd) {
            $form->addElement(new XoopsFormLabel(_AM_JOBS_RES_UNAME, $idd));
        } else {
            $form->addElement(new XoopsFormLabel(_AM_JOBS_SENDBY, $iddn));
        }
        $form->addElement(new XoopsFormLabel(_AM_JOBS_EMAIL, $idde));
        $form->addElement(new XoopsFormText(_AM_JOBS_TEL, "tel", 40, 50, "" . $_SESSION['tel'] . ""), FALSE);
        $form->addElement(new XoopsFormText(_AM_JOBS_TOWN, "town", 40, 50, "" . $_SESSION['town'] . ""), FALSE);

        $state_form = new XoopsFormSelect(_AM_JOBS_STATE, "state", "" . $_SESSION['state'] . "", "0", FALSE);
        while (list($rid, $name) = $xoopsDB->fetchRow($result2)) {
            $state_form->addOption('', _AM_JOBS_SELECT_STATE);
            $state_form->addOption($rid, $name);
        }
        $form->addElement($state_form, TRUE);

        $form->addElement(new XoopsFormFile(_AM_JOBS_NEWRES, 'resume', 0), FALSE);

        $res_radio    = new XoopsFormRadio(_AM_JOBS_Q_NO_RESUME, 'make_resume', "" . $_SESSION['make_resume'] . "");
        $options["0"] = _AM_JOBS_DONT_MAKE;
        $options["1"] = _AM_JOBS_MAKE_RESUME;
        $res_radio->addOptionArray($options);
        $form->addElement($res_radio, TRUE);

        if ($xoopsModuleConfig['jobs_moderate_resume'] == 0) {
            $form->addElement(new XoopsFormHidden("valid", "1"), FALSE);
        } else {
            $form->addElement(new XoopsFormHidden("valid", "0"), FALSE);
        }

        $form->addElement(new XoopsFormHidden("usid", $iddd), FALSE);
        $form->addElement(new XoopsFormHidden("email", $idde), FALSE);
        $form->addElement(new XoopsFormHidden("submitter", $iddn), FALSE);

        $form->addElement(new XoopsFormHidden("date", $time), FALSE);
        $form->addElement(new XoopsFormButton('', 'submit', _AM_JOBS_SUBMIT, 'submit'));
        $form->display();
        $submit_form = ob_get_contents();
        ob_end_clean();
        echo $submit_form;
    }

    xoops_cp_footer();

}
