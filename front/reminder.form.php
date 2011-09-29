<?php
/*
 * @version $Id: reminder.form.php 14684 2011-06-11 06:32:40Z remi $
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

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}
$remind = new Reminder();
checkLoginUser();
if (isset($_POST["add"])) {
   $remind->check(-1,'w',$_POST);

   $newID = $remind->add($_POST);
   Event::log($newID, "reminder", 4, "tools", $_SESSION["glpiname"]." added ".$_POST["name"].".");
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $remind->check($_POST["id"],'w');

   $remind->delete($_POST);
   Event::log($_POST["id"], "reminder", 4, "tools", $_SESSION["glpiname"]." ".$LANG['log'][22]);
   $remind->redirectToList();

} else if (isset($_POST["update"])) {
   $remind->check($_POST["id"],'w');   // Right to update the reminder
   $remind->check(-1,'w',$_POST);      // Right when entity change

   $remind->update($_POST);
   Event::log($_POST["id"], "reminder", 4, "tools", $_SESSION["glpiname"]." ".$LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   if ($_SESSION["glpiactiveprofile"]["interface"] == "helpdesk") {
      helpHeader($LANG['title'][40],'',$_SESSION["glpiname"]);
   } else {
      commonHeader($LANG['title'][40],'',"utils","reminder");
   }

   $remind->showForm($_GET["id"]);

   if ($_SESSION["glpiactiveprofile"]["interface"] == "helpdesk") {
      helpFooter();
   } else {
      commonFooter();
   }
}

?>
