<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * jobs module for xoops
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         jobs
 * @since           2.5.x
 * @author          XOOPS Development Team ( http://xoops.org )
 * @version         $Id: const_entete.php 9860 2012-07-13 10:41:41Z txmodxoops $
 */

include_once 'admin_header.php';
//It recovered the value of argument op in URL$
//$op = test1_CleanVars($_REQUEST, 'op', 'list', 'string');
//xoops_cp_header();
if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'list';
}

switch ($op) {
    case "list":
    default:

        $indexAdmin = new ModuleAdmin();
        //echo $indexAdmin->addNavigation('jobs.php');
        $indexAdmin->addItemButton(_AM_JOBS_ADDSUBCAT, 'job_categories.php?op=new_category', 'add');
        echo $indexAdmin->renderButton('left', '');

        $criteria = new CriteriaCompo();
        $criteria->setSort("cid");
        $criteria->setOrder("ASC");
        $numrows      = $jobsJobs_categoriesHandler->getCount();
        $category_arr = $jobsJobs_categoriesHandler->getall($criteria);

        //Function that allows display child categories
        function jobsCategoryDisplayChildren($cid = 0, $category_arr, $prefix = "", $order = "", &$class)
        {
            global $pathIcon16;
            $jobsJobs_categoriesHandler =& xoops_getModuleHandler("Jobs_categories", "jobs");
            $prefix                     = $prefix . "<img src='" . XOOPS_URL . "/modules/jobs/images/arrow.gif'>";
            foreach (array_keys($category_arr) as $i) {
                $cid   = $category_arr[$i]->getVar("cid");
                $pid   = $category_arr[$i]->getVar("pid");
                $title = $category_arr[$i]->getVar("title");
                $img   = $category_arr[$i]->getVar("img");

                $order    = $category_arr[$i]->getVar("ordre");
                $affprice = $category_arr[$i]->getVar("affprice");

                echo "<tr class='" . $class . "'>";
                echo "<td align=\"left\">" . $prefix . "&nbsp;" . $category_arr[$i]->getVar("title") . "</td>";

                echo "<td align=\"center\"><img src=\"" . XOOPS_URL . "/modules/jobs/images/cat/" . $category_arr[$i]->getVar("img")
                    . "\" height=\"16px\" title=\"img\" alt=\"img\"></td>";

                echo "<td align=\"center\">" . $category_arr[$i]->getVar("ordre") . "</td>";
                echo "<td align=\"center\">" . $category_arr[$i]->getVar("affprice") . "</td>";

                echo "<td align='center' width='10%'>
                        <a href='job_categories.php?op=edit_category&cid=" . $category_arr[$i]->getVar("cid") . "'><img src=" . $pathIcon16
                    . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='job_categories.php?op=delete_category&cid=" . $category_arr[$i]->getVar("cid") . "'><img src=" . $pathIcon16
                    . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                      </td>
                    </tr>";
                $class    = ($class == "even") ? "odd" : "even";
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria("pid", $category_arr[$i]->getVar("cid")));
                $criteria->setSort("title");
                $criteria->setOrder("ASC");
                $pid     = $jobsJobs_categoriesHandler->getall($criteria);
                $num_pid = $jobsJobs_categoriesHandler->getCount();
                if ($num_pid != 0) {
                    jobsCategoryDisplayChildren($cid, $pid, $prefix, $order, $class);
                }
            }
        }

        //Table View
        if ($numrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                    <tr>
                    <th align=\"center\">" . _AM_JOBS_CATEGORY . "</th>
                    <th align=\"center\">" . _AM_JOBS_IMGCAT . "</th>
                    <th align=\"center\">" . _AM_JOBS_ORDRE . "</th>
                    <th align=\"center\">" . _AM_JOBS_PAYMENT . "</th>
                    <th align='center' width='10%'>" . _AM_JOBS_ACTIONS . "</th>
                    </tr>";
            $class  = "odd";
            $prefix = "<img src='" . XOOPS_URL . "/modules/jobs/images/arrow.gif'>";
            foreach (array_keys($category_arr) as $i) {
                if ($category_arr[$i]->getVar("pid") == 0) {
                    $cid   = $category_arr[$i]->getVar("cid");
                    $img   = $category_arr[$i]->getVar("img");
                    $title = $category_arr[$i]->getVar("title");
                    $order = $category_arr[$i]->getVar("ordre");
                    echo "<tr class='" . $class . "'>";
                    echo "<td align=\"left\">" . $prefix . "&nbsp;" . $category_arr[$i]->getVar("title") . "</td>";

                    echo "<td align=\"center\"><img src=\"" . XOOPS_URL . "/modules/jobs/images/cat/" . $category_arr[$i]->getVar("img")
                        . "\" height=\"16px\" title=\"img\" alt=\"img\"></td>";

                    echo "<td align=\"center\">" . $category_arr[$i]->getVar("ordre") . "</td>";

                    echo "<td align=\"center\">" . $category_arr[$i]->getVar("affprice") . "</td>";

                    echo "<td align='center' width='10%'>
                            <a href='job_categories.php?op=edit_category&cid=" . $category_arr[$i]->getVar("cid") . "'><img src=" . $pathIcon16
                        . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                            <a href='job_categories.php?op=delete_category&cid=" . $category_arr[$i]->getVar("cid") . "'><img src=" . $pathIcon16
                        . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>
                    </tr>";
                    $class    = ($class == "even") ? "odd" : "even";
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria("pid", $cid));
                    $criteria->setSort("title");
                    $criteria->setOrder("ASC");
                    $pid     = $jobsJobs_categoriesHandler->getall($criteria);
                    $num_pid = $jobsJobs_categoriesHandler->getCount();

                    if ($num_pid != 0) {
                        jobsCategoryDisplayChildren($cid, $pid, $prefix, "title", $class);
                    }
                }
            }
            echo "</table><br /><br />";
        }

        break;

    case "new_category":
        xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation("jobs.php");
        //$indexAdmin->addItemButton(_AM_JOBS_RES_CATEGORYLIST, 'job_categories.php?op=list', 'list');
        $indexAdmin->addItemButton(_AM_JOBS_CATEGORYLIST, 'jobs.php', 'list');
        echo $indexAdmin->renderButton('left', '');

        $obj  =& $jobsJobs_categoriesHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case "save_category":
        if (!$GLOBALS["xoopsSecurity"]->check()) {
            redirect_header("jobs.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
        }
        if (isset($_REQUEST["cid"])) {
            $obj =& $jobsJobs_categoriesHandler->get($_REQUEST["cid"]);
        } else {
            $obj =& $jobsJobs_categoriesHandler->create();
        }

        //Form category_pid
        $obj->setVar("pid", $_REQUEST["pid"]);
        //Form category_title
        $obj->setVar("title", $_REQUEST["title"]);
        //Form category_desc
        //mb $obj->setVar("category_desc", $_REQUEST["category_desc"]);
        //Form category_img
        include_once XOOPS_ROOT_PATH . "/class/uploader.php";
        $uploaddir         = XOOPS_UPLOAD_PATH . "/jobs/images/";
        $maxwide           = $xoopsModuleConfig['jobs_resized_width'];
        $maxhigh           = $xoopsModuleConfig['jobs_resized_height'];
        $allowed_mimetypes = array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
        $uploader          = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, null, $maxwide, $maxhigh);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix("category_img_");
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header("javascript:history.go(-1)", 3, $errors);
            } else {
                $obj->setVar("img", $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar("img", $_REQUEST["img"]);
        }

        //Form category_weight
        $obj->setVar("ordre", $_REQUEST["ordre"]);
        //Form category_color
        $obj->setVar("affprice", $_REQUEST["affprice"]);

        if ($jobsJobs_categoriesHandler->insert($obj)) {
            redirect_header("jobs.php", 2, _AM_JOBS_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
        $form->display();
        break;

    case "edit_category":
        xoops_cp_header();
        $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation("jobs.php");
//    $indexAdmin->addItemButton(_AM_JOBS_NEWCATEGORY, 'job_categories.php?op=new_category', 'add');
        $indexAdmin->addItemButton(_AM_JOBS_CATEGORYLIST, 'jobs.php', 'list');
        echo $indexAdmin->renderButton('left', '');
        $obj  = $jobsJobs_categoriesHandler->get($_REQUEST["cid"]);
        $form = $obj->getForm();
        $form->display();
        break;

    case "delete_category":
        xoops_cp_header();
        $obj =& $jobsJobs_categoriesHandler->get($_REQUEST["cid"]);
        if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == 1) {
            if (!$GLOBALS["xoopsSecurity"]->check()) {
                redirect_header("jobs.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
            }
            if ($jobsJobs_categoriesHandler->delete($obj)) {
                redirect_header("jobs.php", 3, _AM_JOBS_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array("ok" => 1, "cid" => $_REQUEST["cid"], "op" => "delete_category"), $_SERVER["REQUEST_URI"], sprintf(_AM_JOBS_FORMSUREDEL, $obj->getVar("category")));
        }
        break;
}
include_once 'admin_footer.php';
