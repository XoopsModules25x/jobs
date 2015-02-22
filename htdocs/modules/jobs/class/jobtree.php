<?php
// $Id: xoopstree.php 185 2006-01-22 23:49:01Z skalpa $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

class JobTree
{
    var $table; //table with parent-child structure
    var $id; //name of unique id for records in table $table
    var $pid; // name of parent id used in table $table
    var $order; //specifies the order of query results
    var $title; // name of a field in table $table which will be used when  selection box and paths are generated
    var $db;

    //constructor of class JobTree
    //sets the names of table, unique id, and parend id
    function JobTree($table_name, $id_name, $pid_name)
    {
        $this->db    =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $table_name;
        $this->id    = $id_name;
        $this->pid   = $pid_name;
    }

    // returns an array of first child objects for a given id($sel_id)
    function getFirstChild($sel_id, $order = "")
    {
        global $mydirname;
        $arr = array();
        $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= " AND " . $this->pid . " IN (" . implode(',', $categories) . ") ";
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $arr;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            array_push($arr, $myrow);
        }

        return $arr;
    }

    // returns an array of all FIRST child ids of a given id($sel_id)
    function getFirstChildId($sel_id)
    {
        global $mydirname;
        $idarray = array();
        $result  = $this->db->query(
            "SELECT " . $this->id . " FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . ""
        );

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $result .= ' AND ' . $this->pid . ' IN (' . implode(',', $categories) . ') ';
        }

        $count = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list($id) = $this->db->fetchRow($result)) {
            array_push($idarray, $id);
        }

        return $idarray;
    }

    //returns an array of ALL child ids for a given id($sel_id)
    function getAllChildId($sel_id, $order = "", $idarray = array())
    {
        global $mydirname;
        $sql = "SELECT " . $this->id . " FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= ' AND ' . $this->pid . ' IN (' . implode(',', $categories) . ') ';
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $idarray;
        }
        while (list($r_id) = $this->db->fetchRow($result)) {
            array_push($idarray, $r_id);
            $idarray = $this->getAllChildId($r_id, $order, $idarray);
        }

        return $idarray;
    }

    //returns an array of ALL parent ids for a given id($sel_id)
    function getAllParentId($sel_id, $order = "", $idarray = array())
    {
        global $mydirname;
        $sql = "SELECT " . $this->pid . " FROM " . $this->table . " WHERE " . $this->id . "=" . $sel_id . "";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= ' AND ' . $this->pid . ' IN (' . implode(',', $categories) . ') ';
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        list($r_id) = $this->db->fetchRow($result);
        if ($r_id == 0) {
            return $idarray;
        }
        array_push($idarray, $r_id);
        $idarray = $this->getAllParentId($r_id, $order, $idarray);

        return $idarray;
    }

    //generates path from the root id to a given id($sel_id)
    // the path is delimetered with "/"
    function getPathFromId($sel_id, $title, $path = "")
    {
        global $mydirname;
        $result = $this->db->query(
            "SELECT " . $this->pid . ", " . $title . " FROM " . $this->table . " WHERE " . $this->id . "=$sel_id"
        );

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $result .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list($parentid, $name) = $this->db->fetchRow($result);
        $myts =& MyTextSanitizer::getInstance();
        $name = $myts->htmlSpecialChars($name);
        $path = "/" . $name . $path . "";
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getPathFromId($parentid, $title, $path);

        return $path;
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeMyCatBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";

        $categories = jobs_MygetItemIds("jobs_submit");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>-----</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . $myts->htmlSpecialChars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeMySelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";

        $categories = jobs_MygetItemIds("jobs_submit");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>" . _JOBS_SELECTCAT . "</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . $myts->htmlSpecialChars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeMyAdminSelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>" . _AM_JOBS_SELECTCAT . "</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . $myts->htmlSpecialChars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeMySearchSelBox(
        $title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "", $by_cat = 0
    ) {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>" . _JOBS_ALL_CATEGORIES . "</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel  = "";
            $name = stripslashes($name);
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . stripslashes($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeMyStateSelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>" . _JOBS_ALL_STATES . "</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getStateTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . $myts->htmlSpecialChars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //makes a nicely ordered selection box
    //$preset_id is used to specify a preselected item
    //set $none to 1 to add a option with value 0
    function makeStateSelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . "";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>" . _JOBS_ALL_STATES . "</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
        }
        echo "</select>\n";
    }

    function makeStateBox(
        $title, $order = "", $preset_name = "", $none = 0, $sel_name = "", $onchange = "", $by_state = 0, $issearch = 1
    ) {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", `" . $title . "` FROM " . $this->table . "";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {

            if ($issearch == "1") {
                echo "<option value='0'>" . _JOBS_ALL_STATES . "</option>\n";
            } else {

                echo "<option value='0'>" . _JOBS_SELECT_STATE . "</option>\n";
            }
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($name == $preset_name) {
                $sel = " selected='selected'";
            }
            echo "<option value=$name'$sel>$name</option>\n";
        }
        echo "</select>\n";
    }

    function makeAdminStateBox(
        $title, $order = "", $preset_name = "", $none = 0, $sel_name = "", $onchange = "", $by_state = 0, $issearch = 0
    ) {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . "";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {

            if ($issearch == "1") {
                echo "<option value='0'>" . _AM_JOBS_ALL_STATES . "</option>\n";
            } else {

                echo "<option value='0'>" . _AM_JOBS_SELECT_STATE . "</option>\n";
            }
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($name == $preset_name) {
                $sel = " selected='selected'";
            }
            echo "<option value='$name'$sel>$name</option>\n";
        }
        echo "</select>\n";
    }

    //set $none to 1 to add a option with value 0
    function makeMyAdminCompBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $mydirname;
        if ($sel_name == "") {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='" . $sel_name . "'";
        if ($onchange != "") {
            echo " onchange='" . $onchange . "'";
        }
        echo ">\n";
        $sql = "SELECT " . $this->id . ", " . $title . " FROM " . $this->table . " WHERE " . $this->pid . "=0";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            echo "<option value='0'>" . _AM_JOBS_SELECTCOMPANY . "</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = "";
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getStateTreeArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", "--", $option['prefix']);
                $catpath          = $option['prefix'] . "&nbsp;" . $myts->htmlSpecialChars($option[$title]);
                if ($option[$this->id] == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    //generates nicely formatted linked path from the root id to a given id
    function getNicePathFromId($sel_id, $title, $funcURL, $path = "")
    {
        global $mydirname;
        $sql    = "SELECT " . $this->pid . ", " . $title . " FROM " . $this->table . " WHERE " . $this->id . "=$sel_id";
        $result = $this->db->query($sql);
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list($parentid, $name) = $this->db->fetchRow($result);
        $myts =& MyTextSanitizer::getInstance();
        $name = $myts->undoHtmlSpecialChars($name);

        $arrow = "<img src=\"" . XOOPS_URL . "/modules/jobs/images/arrow.gif\" alt=\"&raquo;\" />";

        $path = "&nbsp;<a href='" . $funcURL . "" . $this->id . "=" . mysql_real_escape_string($sel_id) . "'>&nbsp;"
            . $arrow . "&nbsp;&nbsp;" . $name . "</a>
        " . $path . "";
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

        return $path;
    }

    //generates id path from the root id to a given id
    // the path is delimetered with "/"
    function getIdPathFromId($sel_id, $path = "")
    {
        $result = $this->db->query(
            "SELECT " . $this->pid . " FROM " . $this->table . " WHERE " . $this->id . "=$sel_id"
        );
        if ($this->db->getRowsNum($result) == 0) {
            return $path;
        }
        list($parentid) = $this->db->fetchRow($result);
        $path = "/" . $sel_id . $path . "";
        if ($parentid == 0) {
            return $path;
        }
        $path = $this->getIdPathFromId($parentid, $path);

        return $path;
    }

    function getAllChild($sel_id = 0, $order = "", $parray = array())
    {
        global $mydirname;
        $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= " AND " . $this->pid . " IN (" . implode(',', $categories) . ") ";
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            array_push($parray, $row);
            $parray = $this->getAllChild($row[$this->id], $order, $parray);
        }

        return $parray;
    }

    function getChildTreeArray($sel_id = 0, $order = "", $parray = array(), $r_prefix = "")
    {
        global $mydirname;
        $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= " AND cid IN (" . implode(',', $categories) . ") ";
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            $row['prefix'] = $r_prefix . ".";
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }

    function getStateTreeArray($sel_id = 0, $order = "", $parray = array(), $r_prefix = "")
    {
        global $mydirname;
        $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $this->db->fetchArray($result)) {
            $row['prefix'] = $r_prefix . ".";
            array_push($parray, $row);
            $parray = $this->getStateTreeArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }

    function makeJobSelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $xoopsModuleConfig, $xoopsDB, $mydirname;
        $mydirname = basename(dirname(dirname(__FILE__)));

        $myts =& MyTextSanitizer::getInstance();
        require_once(XOOPS_ROOT_PATH . "/modules/jobs/include/gtickets.php");

        if ($sel_name == "") {
            $sel_name = $this->id;
        }

        $sql = "select " . $this->id . ", " . $title . ", ordre FROM " . $this->table . " WHERE " . $this->pid . "=0";
        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result2 = $xoopsDB->query($sql);
        while (list($catid, $name, $ordre) = $xoopsDB->fetchRow($result2)) {

            echo"<p><a href=\"category.php?op=NewCat&amp;cid=$catid\"><img src=\"" . XOOPS_URL
                . "/modules/jobs/images/plus.gif\" border=0 width=10 height=10 alt=\"" . _AM_JOBS_ADDSUBCAT
                . "\"></a>&nbsp;<a href=\"category.php?op=ModCat&amp;cid=$catid\" title=\"" . _AM_JOBS_MODIFCAT
                . "\">$name</a> ";
            if ($xoopsModuleConfig['jobs_cat_sortorder'] == "ordre") {
                echo "($ordre)";
            }
            echo "<br />\n";
            $arr = $this->getChildTreeMapArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", " --->", $option['prefix']);
                $catpath
                                  =
                    $option['prefix'] . "&nbsp;<a href=\"category.php?op=NewCat&amp;cid=" . $option[$this->id]
                        . "\"><img src=\"" . XOOPS_URL
                        . "/modules/jobs/images/plus.gif\" border=0 width=10 height=10 alt=\"" . _AM_JOBS_ADDSUBCAT
                        . "\"></a>&nbsp;<a href=\"category.php?op=ModCat&amp;cid=" . $option[$this->id] . "\" title=\""
                        . _AM_JOBS_MODIFCAT . "\">" . $myts->htmlSpecialChars($option[$title]);
                $ordreS           = $option['ordre'];
                echo "$catpath</a> ";
                if ($xoopsModuleConfig['jobs_cat_sortorder'] == "ordre") {
                    echo "($ordreS)";
                }
                echo "<br />\n";

            }
        }
    }

    function makeResSelBox($title, $order = "", $preset_id = 0, $none = 0, $sel_name = "", $onchange = "")
    {
        global $xoopsModuleConfig, $xoopsDB, $mydirname;
        $myts =& MyTextSanitizer::getInstance();
        // for "Duplicatable"
        $mydirname = basename(dirname(dirname(__FILE__)));

        if ($sel_name == "") {
            $sel_name = $this->id;
        }

        $sql = "select " . $this->id . ", " . $title . ", ordre FROM " . $this->table . " WHERE " . $this->pid . "=0";

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $xoopsDB->query($sql);
        while (list($catid, $name, $ordre) = $xoopsDB->fetchRow($result)) {

            echo"<p><a href=\"category.php?op=NewResCat&amp;cid=$catid\"><img src=\"" . XOOPS_URL
                . "/modules/jobs/images/plus.gif\" border=0 width=10 height=10 alt=\"" . _AM_JOBS_ADDSUBCAT
                . "\"></a>&nbsp;<a href=\"category.php?op=ModResCat&amp;cid=$catid\" title=\"" . _AM_JOBS_MODIFCAT
                . "\">" . $name . "</a> ";
            if ($xoopsModuleConfig['jobs_cat_sortorder'] == "ordre") {
                echo "($ordre)";
            }
            echo "<br />\n";
            $arr = $this->getChildTreeMapArray($catid, $order);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace(".", " --->", $option['prefix']);
                $catpath
                                  =
                    $option['prefix'] . "&nbsp;<a href=\"category.php?op=NewResCat&amp;cid=" . $option[$this->id]
                        . "\"><img src=\"" . XOOPS_URL
                        . "/modules/jobs/images/plus.gif\" border=0 width=10 height=10 alt=\"" . _AM_JOBS_ADDSUBCAT
                        . "\"></a>&nbsp;<a href=\"category.php?op=ModResCat&amp;cid=" . $option[$this->id]
                        . "\" title=\"" . _AM_JOBS_MODIFCAT . "\">" . $myts->htmlSpecialChars($option[$title]);
                $ordreS           = $option['ordre'];
                echo "$catpath</a> ";
                if ($xoopsModuleConfig['jobs_cat_sortorder'] == "ordre") {
                    echo "($ordreS)";
                }
                echo "<br />\n";

            }
        }
    }

    function getChildTreeMapArray($sel_id = 0, $order = "", $parray = array(), $r_prefix = "")
    {
        global $xoopsDB, $mydirname;
        $sql = "select * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";

        $categories = jobs_MygetItemIds("jobs_view");
        if (is_array($categories) && count($categories) > 0) {
            $sql .= " AND " . $this->pid . " IN (" . implode(',', $categories) . ") ";
        }

        if ($order != "") {
            $sql .= " ORDER BY $order";
        }
        $result = $xoopsDB->query($sql);
        $count  = $xoopsDB->getRowsNum($result);
        if ($count == 0) {
            return $parray;
        }
        while ($row = $xoopsDB->fetchArray($result)) {
            $row['prefix'] = $r_prefix . ".";
            array_push($parray, $row);
            $parray = $this->getChildTreeMapArray($row[$this->id], $order, $parray, $row['prefix']);
        }

        return $parray;
    }

    function getCategoryList()
    {
        $result = $this->db->query('SELECT cid, pid, title FROM ' . $this->table);
        $ret    = array();
        $myts   =& MyTextSanitizer::getInstance();
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[$myrow['cid']] = array('title' => $myts->htmlspecialchars($myrow['title']), 'pid' => $myrow['pid']);
        }

        return $ret;
    }
}
