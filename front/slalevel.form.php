<?php
/*
 * @version $Id: slalevel.form.php 14754 2011-06-23 13:48:01Z moyo $
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

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");


$item = new SlaLevel();

if (isset($_POST["update"])) {
   $item->check($_POST["id"], 'w');

   $item->update($_POST);

   Event::log($_POST["id"], "slas", 4, "config", $_SESSION["glpiname"]." ".$LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["add"])) {
   $item->check(-1, 'w', $_POST);

   if ($item->add($_POST)) {
      Event::log($_POST["slas_id"], "slas", 4, "config", $_SESSION["glpiname"]." ".$LANG['log'][32]);
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {

   if (isset($_POST["item"]) && count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            if ($item->can($key, 'w')) {
               $item->delete(array('id' => $key));
            }
         }
      }
      Event::log($_POST["slas_id"], "slas", 4, "config", $_SESSION["glpiname"]." ".$LANG['log'][22]);
   } else if (isset($_POST['id'])) {
      $item->check($_POST['id'], 'd');
      $ok = $item->delete($_POST);
      if ($ok) {
         Event::log($_POST["id"], "slas", 4, "config",
                  $_SESSION["glpiname"]." ".$LANG['log'][22]." ".$item->getField('name'));
      }
      $item->redirectToList();
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["add_action"])) {
   $item->check($_POST['slalevels_id'], 'w');

   $action = new SlaLevelAction();
   $action->add($_POST);

   // Can't do this in SlaLevelAction, so do it here
   $item->update(array('id'       => $_POST['slalevels_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete_action"])) {
   $item->check($_POST['slalevels_id'], 'w');

   $action = new SlaLevelAction();

   if (count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         $input["id"] = $key;
         $action->delete($input);
      }
   }
   // Can't do this in RuleAction, so do it here
   $item->update(array('id'       => $_POST['slalevels_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   glpi_header($_SERVER['HTTP_REFERER']);

 } else {//print computer informations
   commonHeader($LANG['sla'][6], $_SERVER['PHP_SELF'], "config", "sla");
   //show computer form to add
   $item->showForm($_GET["id"]);
   commonFooter();
}

?>
