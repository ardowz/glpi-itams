<?php
/*
 * @version $Id: notificationtemplate.form.php 14684 2011-06-11 06:32:40Z remi $
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

$notificationtemplate = new NotificationTemplate();
if (isset($_POST["add"])) {
   $notificationtemplate->check(-1,'w',$_POST);

   $newID = $notificationtemplate->add($_POST);
   Event::log($newID, "notificationtemplates", 4, "notification",
              $_SESSION["glpiname"]." ".$LANG['log'][20]." :  ".$_POST["name"].".");

   $language = new NotificationTemplateTranslation();
   $url = getItemTypeFormURL('NotificationTemplateTranslation',true);
   $url.="?notificationtemplates_id=$newID";
   glpi_header($url);

} else if (isset($_POST["delete"])) {
   $notificationtemplate->check($_POST["id"],'d');
   $notificationtemplate->delete($_POST);

   Event::log($_POST["id"], "notificationtemplates", 4, "notification",
              $_SESSION["glpiname"] ." ".$LANG['log'][22]);
   $notificationtemplate->redirectToList();

} else if (isset($_POST["delete_languages"])) {
   $notificationtemplate->check(-1,'d');
   $language = new NotificationTemplateTranslation;
   if (isset($_POST['languages'])) {
      foreach ($_POST['languages'] as $key =>$val) {
         if ($val=='on') {
            $input['id'] = $key;
            $language->delete($input);
         }
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["update"])) {
   $notificationtemplate->check($_POST["id"],'w');

   $notificationtemplate->update($_POST);
   Event::log($_POST["id"], "notificationtemplates", 4, "notification",
              $_SESSION["glpiname"]." ".$LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   commonHeader($LANG['mailing'][113],$_SERVER['PHP_SELF'],"config","mailing",
                "notificationtemplate");
   $notificationtemplate->showForm($_GET["id"]);
   commonFooter();
}

?>