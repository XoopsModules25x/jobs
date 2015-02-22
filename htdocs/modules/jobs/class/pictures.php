<?php
// $Id: jlm_pictures.php,v 1.3 2007/08/26 15:53:07 marcellobrandao Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 * Protection against inclusion outside the site
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
$mydirname = basename(dirname(dirname(__FILE__)));
$main_lang = '_' . strtoupper($mydirname);
/**
 * Includes of form objects and uploader
 */
include_once XOOPS_ROOT_PATH . "/class/uploader.php";
include_once XOOPS_ROOT_PATH . "/kernel/object.php";
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/include/functions.php";

/**
 * jlm_pictures class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */

class jlm_pictures extends XoopsObject
{
    var $db;

// constructor
    function jlm_pictures($id = NULL, $lid = NULL)
    {
        $this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar("cod_img", XOBJ_DTYPE_INT, NULL, FALSE, 10);
        $this->initVar("title", XOBJ_DTYPE_TXTBOX, NULL, FALSE);
        $this->initVar("date_added", XOBJ_DTYPE_TXTBOX, NULL, FALSE);
        $this->initVar("date_modified", XOBJ_DTYPE_TXTBOX, NULL, FALSE);
        $this->initVar("lid", XOBJ_DTYPE_INT, NULL, FALSE, 10);
        $this->initVar("uid_owner", XOBJ_DTYPE_TXTBOX, NULL, FALSE);
        $this->initVar("url", XOBJ_DTYPE_TXTBOX, NULL, FALSE);
        if (!empty($lid)) {
            if (is_array($lid)) {
                $this->assignVars($lid);
            } else {
                $this->load(intval($lid));
            }
        } else {
            $this->setNew();
        }

    }

    function load($id)
    {
        global $mydirname;
        $sql   = 'SELECT * FROM ' . $this->db->prefix("jobs_pictures") . ' WHERE cod_img=' . $id . '';
        $myrow = $this->db->fetchArray($this->db->query($sql));
        $this->assignVars($myrow);
        if (!$myrow) {
            $this->setNew();
        }
    }

    function getAll_pictures(
        $criteria = array(), $asobject = FALSE, $sort = "cod_img", $order = "ASC", $limit = 0, $start = 0
    ) {
        global $mydirname;
        $db          =& XoopsDatabaseFactory::getDatabaseConnection();
        $ret         = array();
        $where_query = "";
        if (is_array($criteria) && count($criteria) > 0) {
            $where_query = " WHERE";
            foreach ($criteria as $c) {
                $where_query .= " $c AND";
            }
            $where_query = substr($where_query, 0, -4);
        } elseif (!is_array($criteria) && $criteria) {
            $where_query = " WHERE " . $criteria;
        }
        if (!$asobject) {
            $sql    = "SELECT cod_img FROM " . $db->prefix("jobs_pictures") . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = $myrow['jlm_pictures_id'];
            }
        } else {
            $sql    = "SELECT * FROM " . $db->prefix("jobs_pictures") . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = new jlm_pictures ($myrow);
            }
        }

        return $ret;
    }
}

// -------------------------------------------------------------------------
// ------------------jlm_pictures user handler class -------------------
// -------------------------------------------------------------------------
/**
 * jlm_pictureshandler class.
 * This class provides simple mechanism for jlm_pictures object and generate forms for inclusion etc
 */

class Xoopsjlm_picturesHandler extends XoopsObjectHandler
{
    /**
     * create a new jlm_pictures
     *
     * @param  bool   $isNew flag the new objects as "new"?
     * @return object jlm_pictures
     */
    function &create($isNew = TRUE) {
        $jlm_pictures = new jlm_pictures();
        if ($isNew) {
            $jlm_pictures->setNew();
        } else {
            $jlm_pictures->unsetNew();
        }

        return $jlm_pictures;
    }

    /**
     * retrieve a jlm_pictures
     *
     * @param  int   $id of the jlm_pictures
     * @return mixed reference to the {@link jlm_pictures} object, FALSE if failed
     */
    function &get($id, $lid) {

        global $mydirname;

        $sql
            = 'SELECT * FROM ' . $this->db->prefix("jobs_pictures") . ' WHERE cod_img=' . $id . ' and lid=' . $lid . '';
        if (!$result = $this->db->query($sql)) {
            return FALSE;
        }
        $numrows = $this->db->getRowsNum($result);
        if ($numrows == 1) {
            $jlm_pictures = new jlm_pictures();
            $jlm_pictures->assignVars($this->db->fetchArray($result));

            return $jlm_pictures;
        }

        return FALSE;
    }

    /**
     * insert a new jlm_pictures in the database
     *
     * @param  object $jlm_pictures reference to the {@link jlm_pictures} object
     * @param  bool   $force
     * @return bool   FALSE if failed, TRUE if already present and unchanged or successful
     */
    function insert(&$jlm_pictures, $force = FALSE)
    {
        global $xoopsConfig, $lid, $mydirname;
        if (get_class($jlm_pictures) != 'jlm_pictures') {
            return FALSE;
        }
        if (!$jlm_pictures->isDirty()) {
            return TRUE;
        }
        if (!$jlm_pictures->cleanVars()) {
            return FALSE;
        }
        foreach ($jlm_pictures->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = time();
        if ($jlm_pictures->isNew()) {
            // ajout/modification d'un jlm_pictures
            $jlm_pictures = new jlm_pictures();

            $format = "INSERT INTO %s (cod_img, title, date_added, date_modified, lid, uid_owner, url)";
            $format .= "VALUES (%u, %s, %s, %s, %s, %s, %s)";
            $sql   = sprintf(
                $format, $this->db->prefix("jobs_pictures"), $cod_img, $this->db->quoteString($title), $now, $now, $this->db->quoteString($lid), $this->db->quoteString($uid_owner), $this->db->quoteString($url)
            );
            $force = TRUE;
        } else {
            $format = "UPDATE %s SET ";
            $format .= "cod_img=%u, title=%s, date_added=%s, date_modified=%s, lid=%s, uid_owner=%s, url=%s";
            $format .= " WHERE cod_img = %u";
            $sql = sprintf(
                $format, $this->db->prefix("jobs_pictures"), $cod_img, $this->db->quoteString($title), $now, $now, $this->db->quoteString($lid), $this->db->quoteString($uid_owner), $this->db->quoteString($url), $cod_img
            );
        }
        if (FALSE != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return FALSE;
        }
        if (empty($cod_img)) {
            $cod_img = $this->db->getInsertId();
        }
        $jlm_pictures->assignVar('cod_img', $cod_img);
        $jlm_pictures->assignVar('url', $url);

        return TRUE;
    }

    /**
     * delete a jlm_pictures from the database
     *
     * @param  object $jlm_pictures reference to the jlm_pictures to delete
     * @param  bool   $force
     * @return bool   FALSE if failed.
     */
    function delete(&$jlm_pictures, $force = FALSE)
    {
        global $mydirname;

        if (get_class($jlm_pictures) != 'jlm_pictures') {
            return FALSE;
        }
        $sql = sprintf("DELETE FROM %s WHERE cod_img = %u", $this->db->prefix("jobs_pictures"), $jlm_pictures->getVar('cod_img'));
        if (FALSE != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * retrieve jlm_pictures from the database
     *
     * @param  object $criteria  {@link CriteriaElement} conditions to be met
     * @param  bool   $id_as_key use the UID as key for the array?
     * @return array  array of {@link jlm_pictures} objects
     */
    function &getObjects($criteria = NULL, $id_as_key = FALSE) {

        global $mydirname;

        $ret   = array();
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix("jobs_pictures");
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $jlm_pictures = new jlm_pictures();
            $jlm_pictures->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $jlm_pictures;
            } else {
                $ret[$myrow['cod_img']] =& $jlm_pictures;
            }
            unset($jlm_pictures);
        }

        return $ret;
    }

    /**
     * count jlm_pictures matching a condition
     *
     * @param  object $criteria {@link CriteriaElement} to match
     * @return int    count of jlm_pictures
     */
    function getCount($criteria = NULL)
    {
        global $mydirname;

        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix("jobs_pictures");
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * delete jlm_pictures matching a set of conditions
     *
     * @param  object $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    function deleteAll($criteria = NULL)
    {
        global $mydirname;
        $sql = 'DELETE FROM ' . $this->db->prefix("jobs_pictures");
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Render a form to send pictures
     *
     * @param  int    $maxbytes the maximum size of a picture
     * @param  object $xoopsTpl the one in which the form will be rendered
     * @return bool   TRUE
     *
     * obs: Some functions wont work on php 4 so edit lines down under acording to your version
     */
    function renderFormSubmit($uid, $lid, $maxbytes, $xoopsTpl)
    {
        global $mydirname, $main_lang, $xoopsUser;
        $form       = new XoopsThemeForm(_JOBS_SUBMIT_PIC_TITLE, "form_picture",
            "add_photo.php?lid=$lid&uid=" . $xoopsUser->getVar('uid') . "", "post", TRUE);
        $field_url  = new XoopsFormFile(_JOBS_SELECT_PHOTO, "sel_photo", 2000000);
        $field_desc = new XoopsFormText(_JOBS_CAPTION, "caption", 35, 55);
        $form->setExtra('enctype="multipart/form-data"');
        $button_send   = new XoopsFormButton("", "submit_button", _JOBS_UPLOADPICTURE, "submit");
        $field_warning = new XoopsFormLabel(sprintf(_JOBS_YOUCANUPLOAD, $maxbytes / 1024));
        $field_lid     = new XoopsFormHidden("lid", $lid);
        $field_uid     = new XoopsFormHidden("uid", $uid);
        /**
         * Check if using Xoops or XoopsCube (by jlm69)
         */

        $xCube = FALSE;
        if (preg_match("/^XOOPS Cube/", XOOPS_VERSION)) { // XOOPS Cube 2.1x
            $xCube = TRUE;
        }

        /**
         * Verify Ticket (by jlm69)
         * If your site is XoopsCube it uses $xoopsGTicket for the token.
         * If your site is Xoops it uses xoopsSecurity for the token.
         */

        if ($xCube) {
            $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');
        } else {
            $field_token = $GLOBALS['xoopsSecurity']->getTokenHTML();
        }
        $form->addElement($field_warning);
        $form->addElement($field_url, TRUE);
        $form->addElement($field_desc, TRUE);
        $form->addElement($field_lid, TRUE);
        $form->addElement($field_uid, TRUE);
        $form->addElement($field_token, TRUE);
        $form->addElement($button_send);
        if ((str_replace('.', '', PHP_VERSION)) > 499) {
            $form->assign($xoopsTpl);
        } else {
            $form->display();
        }

        return TRUE;
    }

    /**
     * Render a form to edit the description of the pictures
     *
     * @param  string $caption  The description of the picture
     * @param  int    $cod_img  the id of the image in database
     * @param  text   $filename the url to the thumb of the image so it can be displayed
     * @return bool   TRUE
     */
    function renderFormEdit($caption, $cod_img, $filename)
    {
        global $mydirname, $main_lang;

        $form       = new XoopsThemeForm(_JOBS_EDIT_CAPTION, "form_picture", "editdesc.php", "post", TRUE);
        $field_desc = new XoopsFormText($caption, "caption", 35, 55);
        $form->setExtra('enctype="multipart/form-data"');
        $button_send   = new XoopsFormButton(_JOBS_EDIT, "submit_button", "Submit", "submit");
        $field_warning = new XoopsFormLabel("<img src='" . $filename . "' alt='sssss'>");
        $field_cod_img = new XoopsFormHidden("cod_img", $cod_img);
        $field_lid     = new XoopsFormHidden("lid", $lid);
        $field_marker  = new XoopsFormHidden("marker", 1);


        /**
         * Check if using Xoops or XoopsCube (by jlm69)
         * Right now Xoops does not have a directory called preload, Xoops Cube does.
         * If this finds a diectory called preload in the Xoops Root folder $xCube=true.
         * This could change if Xoops adds a Directory called preload
         */

        $xCube   = FALSE;
        $preload = XOOPS_ROOT_PATH . "/preload";
        if (is_dir($preload)) {
            $xCube = TRUE;
        }

        /**
         * Verify Ticket (by jlm69)
         * If your site is XoopsCube it uses $xoopsGTicket for the token.
         * If your site is Xoops it uses xoopsSecurity for the token.
         */

        if ($xCube = TRUE) {
            $GLOBALS['xoopsGTicket']->addTicketXoopsFormElement($form, __LINE__, 1800, 'token');
        } else {
            $GLOBALS['xoopsSecurity']->getTokenHTML();
        }

        $form->addElement($field_warning);
        $form->addElement($field_desc);
        $form->addElement($field_cod_img);
        $form->addElement($field_marker);
        $form->addElement($button_send);
        $form->display();

        return TRUE;
    }

    /**
     * Upload the file and Save into database
     *
     * @param  text $title         A litle description of the file
     * @param  text $path_upload   The path to where the file should be uploaded
     * @param  int  $thumbwidth    the width in pixels that the thumbnail will have
     * @param  int  $thumbheight   the height in pixels that the thumbnail will have
     * @param  int  $pictwidth     the width in pixels that the pic will have
     * @param  int  $pictheight    the height in pixels that the pic will have
     * @param  int  $maxfilebytes  the maximum size a file can have to be uploaded in bytes
     * @param  int  $maxfilewidth  the maximum width in pixels that a pic can have
     * @param  int  $maxfileheight the maximum height in pixels that a pic can have
     * @return bool FALSE if upload fails or database fails
     */
    function receivePicture(
        $title, $path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes, $maxfilewidth,
        $maxfileheight
    ) {
        global $xoopsUser, $xoopsDB, $_POST, $_FILES, $lid;
        //busca id do user logado
        $uid = $xoopsUser->getVar('uid');
        $lid = $_POST['lid'];
        //create a hash so it does not erase another file
        $hash1 = time();
        $hash  = substr($hash1, 0, 4);
        // mimetypes and settings put this in admin part later
        $allowed_mimetypes = array('image/jpeg', 'image/pjpeg');
        $maxfilesize       = $maxfilebytes;
        // create the object to upload
        $uploader = new XoopsMediaUploader($path_upload, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        // fetch the media
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            //lets create a name for it
            $uploader->setPrefix('pic_' . $lid . '_');
            //now let s upload the file
            if (!$uploader->upload()) {
                // if there are errors lets return them
                echo
                    "<div style=\"color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center\"><p>"
                        . $uploader->getErrors() . "</p></div>";

                return FALSE;
            } else {
                // now let s create a new object picture and set its variables
                $picture = $this->create();
                $url     = $uploader->getSavedFileName();
                $picture->setVar("url", $url);
                $picture->setVar("title", $title);
                $uid = $xoopsUser->getVar('uid');
                $lid = $lid;
                $picture->setVar("lid", $lid);
                $picture->setVar("uid_owner", $uid);
                $this->insert($picture);
                $saved_destination = $uploader->getSavedDestination();
                $this->resizeImage($saved_destination, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload);
            }
        } else {
            echo
                "<div style=\"color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center\"><p>"
                    . $uploader->getErrors() . "</p></div>";

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Resize a picture and save it to $path_upload
     *
     * @param  text    $img         the path to the file
     * @param  text    $path_upload The path to where the files should be saved after resizing
     * @param  int     $thumbwidth  the width in pixels that the thumbnail will have
     * @param  int     $thumbheight the height in pixels that the thumbnail will have
     * @param  int     $pictwidth   the width in pixels that the pic will have
     * @param  int     $pictheight  the height in pixels that the pic will have
     * @return nothing
     */
    function resizeImage($img, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $path_upload)
    {
        $img2   = $img;
        $path   = pathinfo($img);
        $img    = imagecreatefromjpeg($img);
        $xratio = $thumbwidth / (imagesx($img));
        $yratio = $thumbheight / (imagesy($img));
        if ($xratio < 1 || $yratio < 1) {
            if ($xratio < $yratio) {
                $resized = imagecreatetruecolor($thumbwidth, floor(imagesy($img) * $xratio));
            } else {
                $resized = imagecreatetruecolor(floor(imagesx($img) * $yratio), $thumbheight);
            }
            imagecopyresampled(
                $resized, $img, 0, 0, 0, 0, imagesx($resized) + 1, imagesy($resized) + 1, imagesx($img), imagesy($img)
            );
            imagejpeg($resized, $path_upload . "/thumbs/thumb_" . $path["basename"]);
            imagedestroy($resized);
        } else {
            imagejpeg($img, $path_upload . "/thumbs/thumb_" . $path["basename"]);
        }
        imagedestroy($img);
        $path2   = pathinfo($img2);
        $img2    = imagecreatefromjpeg($img2);
        $xratio2 = $pictwidth / (imagesx($img2));
        $yratio2 = $pictheight / (imagesy($img2));
        if ($xratio2 < 1 || $yratio2 < 1) {
            if ($xratio2 < $yratio2) {
                $resized2 = imagecreatetruecolor(
                    $pictwidth, floor(
                        imagesy($img2) * $xratio2
                    )
                );
            } else {
                $resized2 = imagecreatetruecolor(floor(imagesx($img2) * $yratio2), $pictheight);
            }
            imagecopyresampled(
                $resized2, $img2, 0, 0, 0, 0, imagesx($resized2) + 1,
                imagesy($resized2) + 1, imagesx($img2), imagesy($img2)
            );
            imagejpeg($resized2, $path_upload . "/midsize/resized_" . $path2["basename"]);
            imagedestroy($resized2);
        } else {
            imagejpeg($img2, $path_upload . "/midsize/resized_" . $path2["basename"]);
        }
        imagedestroy($img2);
    }
}
