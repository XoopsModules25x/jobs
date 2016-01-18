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
 * test1 module for xoops
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         test1
 * @since           2.5.x
 * @author          XOOPS Development Team ( http://xoops.org )
 * @version         $Id: const_entete.php 9860 2012-07-13 10:41:41Z txmodxoops $
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class jobs_res_categories extends XoopsObject
{
    //Constructor
    function __construct()
    {
        parent::__construct();
        $this->initVar("cid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("pid", XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar("title", XOBJ_DTYPE_TXTBOX, null, false, 255);
//		$this->initVar("category_desc", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("img", XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar("ordre", XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar("affprice", XOBJ_DTYPE_INT, null, false, 5);

    }

    function getForm($action = false)
    {
        global $xoopsDB, $xoopsModuleConfig;

        if ($action === false) {
            $action = $_SERVER["REQUEST_URI"];
        }

        $title = $this->isNew() ? sprintf(_AM_JOBS_CATEGORY_ADD) : sprintf(_AM_JOBS_CATEGORY_EDIT);

        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

        $form = new XoopsThemeForm($title, "form", $action, "post", true);
        $form->setExtra('enctype="multipart/form-data"');

            include_once(XOOPS_ROOT_PATH."/class/tree.php");
            $categoryHandler =& xoops_getModuleHandler("jobs_res_categories", "jobs");
            $arr = $categoryHandler->getall();
            //$mytree = new XoopsObjectTree($arr, "category_id", "category_pid");
            $mytree = new XoopsObjectTree($arr, "cid", "pid");

            $form->addElement(new XoopsFormLabel(_AM_JOBS_CATEGORY_PID, $mytree->makeSelBox("pid", "title","-", $this->getVar("pid"),true)));
        $form->addElement(new XoopsFormText(_AM_JOBS_CATEGORY_TITLE, "title", 50, 255, $this->getVar("title")), true);
        //$form->addElement(new XoopsFormTextArea(_AM_TEST1_CATEGORY_DESC, "category_desc", $this->getVar("category_desc"), 4, 47), true);

            $img = $this->getVar("img") ? $this->getVar("img") : 'default.gif';
            $uploadirectory_category_img = '/modules/jobs/images/cat';
            $imgtray_category_img = new XoopsFormElementTray(_AM_JOBS_IMGCAT,'<br />');
            $imgpath_category_img = sprintf(_AM_JOBS_FORMIMAGE_PATH, $uploadirectory_category_img);
            $imageselect_category_img = new XoopsFormSelect($imgpath_category_img, 'img', $img);
            $image_array_category_img = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH.$uploadirectory_category_img );
            foreach ($image_array_category_img as $image_category_img) {
                $imageselect_category_img->addOption("$image_category_img", $image_category_img);
            }
            $imageselect_category_img->setExtra( "onchange='showImgSelected(\"image_category_img\", \"img\", \"".$uploadirectory_category_img."\", \"\", \"".XOOPS_URL."\")'" );
            $imgtray_category_img->addElement($imageselect_category_img, false);
            $imgtray_category_img->addElement( new XoopsFormLabel( '', "<br /><img src='".XOOPS_URL."/".$uploadirectory_category_img."/".$img."' name='image_category_img' id='image_category_img' alt='' />" ) );

            $fileseltray_category_img = new XoopsFormElementTray('','<br />');
            $fileseltray_category_img->addElement(new XoopsFormFile(_AM_JOBS_FORMUPLOAD , "img", $xoopsModuleConfig["jobs_maxfilesize"]),false);
            $fileseltray_category_img->addElement(new XoopsFormLabel(''), false);
            $imgtray_category_img->addElement($fileseltray_category_img);
            $form->addElement($imgtray_category_img);

        $form->addElement(new XoopsFormText(_AM_JOBS_ORDRE, "ordre", 50, 255, $this->getVar("ordre")), false);
        //$form->addElement(new XoopsFormText(_AM_JOBS_PAYMENT, "affprice",  50, 255, $this->getVar("affprice")), false);
        $form->addElement(new XoopsFormRadioYN(_AM_JOBS_PAYMENT, 'affprice', 1, _YES, _NO));

        $form->addElement(new XoopsFormHidden("op", "save_category"));
        $form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));

        return $form;
    }
}
class jobsJobs_res_categoriesHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "jobs_res_categories", "jobs_res_categories", "cid", "title");
    }
}
