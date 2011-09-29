<?php
/*
 * @version $Id: dropdownAllItems.php 14684 2011-06-11 06:32:40Z remi $
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

define('GLPI_ROOT','..');
include (GLPI_ROOT."/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

checkCentralAccess();

// Make a select box
if ($_POST["idtable"] && class_exists($_POST["idtable"])) {
   $table = getTableForItemType($_POST["idtable"]);

   // Link to user for search only > normal users
   $link = "dropdownValue.php";

   if ($_POST["idtable"] == 'User') {
      $link = "dropdownUsers.php";
   }

   $rand     = mt_rand();
   $use_ajax = false;

   if ($CFG_GLPI["use_ajax"] && countElementsInTable($table)>$CFG_GLPI["ajax_limit_count"]) {
      $use_ajax = true;
   }

   $paramsallitems = array('searchText'          => '__VALUE__',
                           'table'               => $table,
                           'itemtype'            => $_POST["idtable"],
                           'rand'                => $rand,
                           'myname'              => $_POST["myname"],
                           'displaywith'         => array('otherserial', 'serial'),
                           'display_emptychoice' => true);

   if (isset($_POST['value'])) {
      $paramsallitems['value'] = $_POST['value'];
   }
   if (isset($_POST['entity_restrict'])) {
      $paramsallitems['entity_restrict'] = $_POST['entity_restrict'];
   }
   if (isset($_POST['condition'])) {
      $paramsallitems['condition'] = stripslashes($_POST['condition']);
   }

   $default = "<select name='".$_POST["myname"]."'><option value='0'>".DROPDOWN_EMPTY_VALUE.
              "</option></select>";
   ajaxDropdown($use_ajax, "/ajax/$link", $paramsallitems, $default, $rand);

}

?>