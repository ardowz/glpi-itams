<?php
/*
 * @version $Id: notificationtemplatetranslation.form.php 14684 2011-06-11 06:32:40Z remi $
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

$language = new NotificationTemplateTranslation();

if (isset($_POST["add"])) {
   $language->check(-1,'w',$_POST);
   $newID = $language->add($_POST);

   Event::log($newID, "notificationtemplates", 4, "notification",
              $_SESSION["glpiname"]." ".$LANG['log'][20]." : ".$_POST["language"].".");
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $language->check($_POST["id"],'d');
   $language->delete($_POST);

   Event::log($_POST["id"], "notificationtemplates", 4, "notification",
              $_SESSION["glpiname"] ." ".$LANG['log'][22]);
   $language->redirectToList();

} else if (isset($_POST["update"])) {
   $language->check($_POST["id"],'w');
   $language->update($_POST);

   Event::log($_POST["id"], "notificationtemplates", 4, "notification", $_SESSION["glpiname"]." ".
              $LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   commonHeader($LANG['mailing'][113], $_SERVER['PHP_SELF'], "config", "mailing",
                "notificationtemplate");

   if ($_GET["id"] == '') {
      $options = array("notificationtemplates_id" => $_GET["notificationtemplates_id"]);
   } else {
      $options = array();
   }
   $language->showForm($_GET["id"], $options);
   commonFooter();
}

?>