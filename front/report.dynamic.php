<?php
/*
 * @version $Id: report.dynamic.php 14684 2011-06-11 06:32:40Z remi $
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

checkCentralAccess();

if (isset($_GET["item_type"]) && isset($_GET["display_type"])) {
   if ($_GET["display_type"] < 0) {
      $_GET["display_type"] = -$_GET["display_type"];
      $_GET["export_all"] = 1;
   }

   // PDF case
   if ($_GET["display_type"] == PDF_OUTPUT_LANDSCAPE
       || $_GET["display_type"] == PDF_OUTPUT_PORTRAIT) {

      include (GLPI_ROOT . "/lib/ezpdf/class.ezpdf.php");
   }

   switch ($_GET["item_type"]) {
      case 'KnowbaseItem' :
         KnowbaseItem::showList($_GET, $_GET["is_faq"]);
         break;

      case 'Stat' :
         if (isset($_GET["item_type_param"])) {
            $params = unserialize(stripslashes($_GET["item_type_param"]));
            switch ($params["type"]) {
               case "comp_champ" :
                  $val = Stat::getItems($params["date1"], $params["date2"], $params["dropdown"]);
                  Stat::show($params["type"], $params["date1"], $params["date2"], $params["start"],
                             $val, $params["dropdown"]);
                  break;

               case "device" :
                  $val = Stat::getItems($params["date1"], $params["date2"], $params["dropdown"]);
                  Stat::show($params["type"], $params["date1"], $params["date2"], $params["start"],
                             $val, $params["dropdown"]);
                  break;

               default :
                  $val = Stat::getItems($params["date1"], $params["date2"], $params["type"]);
                  Stat::show($params["type"], $params["date1"], $params["date2"], $params["start"],
                             $val);
            }
         } else if (isset($_GET["type"]) && $_GET["type"] == "hardwares") {
            Stat::showItems("",$_GET["date1"], $_GET["date2"], $_GET['start']);
         }
         break;

      default :
         // Plugin case
         if ($plug = isPluginItemType($_GET["item_type"])) {
            if (doOneHook($plug['plugin'], 'dynamicReport', $_GET)) {
               exit();
            }
         }
         Search::manageGetValues($_GET["item_type"]);
         Search::showList($_GET["item_type"],$_GET);
   }
}
?>
