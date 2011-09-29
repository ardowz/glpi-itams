<?php
/*
 * @version $Id: ocsng.import.php 14684 2011-06-11 06:32:40Z remi $
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

checkRight("ocsng","w");

commonHeader($LANG['ocsng'][0], $_SERVER['PHP_SELF'], "utils", "ocsng", "import");

$display_list = true;
//First time this screen is displayed : set the import mode to 'basic'
if (!isset($_SESSION["change_import_mode"])) {
   $_SESSION["change_import_mode"] = false;
}

//Changing the import mode
if (isset($_GET["change_import_mode"])) {

   if ($_GET["change_import_mode"] == "false") {
      $_SESSION["change_import_mode"] = false;
   } else {
      $_SESSION["change_import_mode"] = true;
   }
}

if (isset($_SESSION["ocs_import"]["id"])) {
   if ($count = count($_SESSION["ocs_import"]["id"])) {
      $percent = min(100,
                     round(100*($_SESSION["ocs_import_count"]-$count)/$_SESSION["ocs_import_count"],
                           0));

      $key = array_pop($_SESSION["ocs_import"]["id"]);

      if (isset($_SESSION["ocs_import"]["entities_id"][$key])) {
         $entity = $_SESSION["ocs_import"]["entities_id"][$key];
      } else {
         $entity = -1;
      }

      if (isset($_SESSION["ocs_import"]["locations_id"][$key])) {
         $location = $_SESSION["ocs_import"]["locations_id"][$key];
      } else {
         $location = -1;
      }

      $conf   = OcsServer::getConfig($_SESSION["ocsservers_id"]);
      $action = OcsServer::processComputer($key, $_SESSION["ocsservers_id"], 0, $entity, $location);
      OcsServer::manageImportStatistics($_SESSION["ocs_import"]['statistics'], $action['status']);
      OcsServer::showStatistics($_SESSION["ocs_import"]['statistics']);
      displayProgressBar(400, $percent);
      glpi_header($_SERVER['PHP_SELF']);

   } else {
      //displayProgressBar(400, 100);
      OcsServer::showStatistics($_SESSION["ocs_import"]['statistics'],true);
      unset($_SESSION["ocs_import"]);

      echo "<div class='center b'><br>";
      echo "<a href='".$_SERVER['PHP_SELF']."'>".$LANG['buttons'][13]."</a></div>";
      $display_list = false;
   }
}

if (!isset($_POST["import_ok"])) {
   if (!isset($_GET['check'])) {
      $_GET['check'] = 'all';
   }
   if (!isset($_GET['start'])) {
      $_GET['start'] = 0;
   }
   if (isset($_SESSION["ocs_import"])) {
      unset($_SESSION["ocs_import"]);
   }
   OcsServer::manageDeleted($_SESSION["ocsservers_id"]);
   if ($display_list) {
      OcsServer::showComputersToAdd($_SESSION["ocsservers_id"], $_SESSION["change_import_mode"],
                                    $_GET['check'], $_GET['start'], $_SESSION['glpiactiveentities']);
   }

} else {
   if (count($_POST['toimport']) >0) {
      $_SESSION["ocs_import_count"] = 0;

      foreach ($_POST['toimport'] as $key => $val) {
         if ($val == "on") {
            $_SESSION["ocs_import"]["id"][] = $key;

            if (isset($_POST['toimport_entities'])) {
               $_SESSION["ocs_import"]["entities_id"][$key] = $_POST['toimport_entities'][$key];
            }
            if (isset($_POST['toimport_locations'])) {
               $_SESSION["ocs_import"]["locations_id"][$key] = $_POST['toimport_locations'][$key];
            }
            $_SESSION["ocs_import_count"]++;
         }
      }
   }
   glpi_header($_SERVER['PHP_SELF']);
}

commonFooter();

?>