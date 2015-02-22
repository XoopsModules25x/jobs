<?php
function b_waiting_jobs()
{
    $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = array();

    // jobs listings
    $block  = array();
    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("jobs_listing") . " WHERE valid='0'");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/jobs/admin/index.php";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_JOBS;
    }

    $ret[] = $block;

    $block = array();

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("jobs_resume") . " WHERE valid='0'");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/jobs/admin/index.php";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_RESUMES;
    }

    $ret[] = $block;

    return $ret;

}
