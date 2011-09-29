<?php
/*
 * @version $Id: dropdownUsers.php 14684 2011-06-11 06:32:40Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"dropdownUsers.php")) {
   $AJAX_INCLUDE = 1;
   define('GLPI_ROOT','..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

checkLoginUser();

if (!isset($_POST['right'])) {
   $_POST['right'] = "all";
}

// Default view : Nobody
if (!isset($_POST['all'])) {
   $_POST['all'] = 0;
}

$used = array();

if (isset($_POST['used'])) {
   if (is_array($_POST['used'])) {
      $used = $_POST['used'];
   } else {
      $used = unserialize(stripslashes($_POST['used']));
   }
}

if (isset($_POST["entity_restrict"])
    && !is_numeric($_POST["entity_restrict"])
    && !is_array($_POST["entity_restrict"])) {

   $_POST["entity_restrict"] = unserialize(stripslashes($_POST["entity_restrict"]));
}

$result = User::getSqlSearchResult(false, $_POST['right'], $_POST["entity_restrict"],
                                   $_POST['value'], $used, $_POST['searchText']);

$users = array();

if ($DB->numrows($result)) {
   while ($data=$DB->fetch_array($result)) {
      $users[$data["id"]] = formatUserName($data["id"], $data["name"], $data["realname"],
                                           $data["firstname"]);
      $logins[$data["id"]] = $data["name"];
   }
}

asort($users);

echo "<select id='dropdown_".$_POST["myname"].$_POST["rand"]."' name='".$_POST['myname']."'";
if (isset($_POST["auto_submit"]) && $_POST["auto_submit"]==1) {
   echo " onChange='submit()'";
}
echo ">";

if ($_POST['searchText']!=$CFG_GLPI["ajax_wildcard"]
    && $DB->numrows($result)==$CFG_GLPI["dropdown_max"]) {

   echo "<option value='0'>--".$LANG['common'][11]."--</option>";
}

if ($_POST['all']==0) {
   echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>";
} else if ($_POST['all']==1) {
   echo "<option value='0'>[".$LANG['common'][66]."]</option>";
}

if (isset($_POST['value'])) {
   $output = getUserName($_POST['value']);

   if (!empty($output) && $output!="&nbsp;") {
      echo "<option selected value='".$_POST['value']."'>".$output."</option>";
   }
}

if (count($users)) {
   foreach ($users as $ID => $output) {
      echo "<option value='$ID' title=\"".cleanInputText($output." - ".$logins[$ID])."\">".
             utf8_substr($output, 0, $_SESSION["glpidropdown_chars_limit"])."</option>";
   }
}
echo "</select>";

if (isset($_POST["comment"]) && $_POST["comment"]) {
   $paramscomment = array('value' => '__VALUE__',
                          'table' => "glpi_users");

   if (isset($_POST['update_link'])) {
      $paramscomment['withlink'] = "comment_link_".$_POST["myname"].$_POST["rand"];
   }
   ajaxUpdateItemOnSelectEvent("dropdown_".$_POST["myname"].$_POST["rand"],
                               "comment_".$_POST["myname"].$_POST["rand"],
                               $CFG_GLPI["root_doc"]."/ajax/comments.php",
                               $paramscomment, false);
}

commonDropdownUpdateItem($_POST);
?>