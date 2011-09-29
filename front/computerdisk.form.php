<?php
/*
 * @version $Id: computerdisk.form.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------


define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}
if (!isset($_GET["computers_id"])) {
   $_GET["computers_id"] = "";
}

$disk = new ComputerDisk();
if (isset($_POST["add"])) {
   $disk->check(-1,'w',$_POST);

   if ($newID = $disk->add($_POST)) {
      Event::log($_POST['computers_id'], "computers", 4, "inventory",
               $_SESSION["glpiname"]." ".$LANG['log'][21]);
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $disk->check($_POST["id"],'w');

   if ($disk->delete($_POST)) {
      Event::log($disk->fields['computers_id'], "computers", 4, "inventory",
               $_SESSION["glpiname"]." ".$LANG['log'][21]);
   }
   $computer = new Computer();
   $computer->getFromDB($disk->fields['computers_id']);
   glpi_header(getItemTypeFormURL('Computer').'?id='.$disk->fields['computers_id'].
               ($computer->fields['is_template']?"&withtemplate=1":""));

} else if (isset($_POST["update"])) {
   $disk->check($_POST["id"],'w');

   if ($disk->update($_POST)) {
      Event::log($disk->fields['computers_id'], "computers", 4, "inventory",
               $_SESSION["glpiname"]." ".$LANG['log'][21]." ".$_POST["id"]);
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   commonHeader($LANG['Menu'][0],$_SERVER['PHP_SELF'],"inventory","computer");
   $disk->showForm($_GET["id"], array('computers_id' => $_GET["computers_id"]));
   commonFooter();
}

?>
