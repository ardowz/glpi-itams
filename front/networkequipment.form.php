<?php
/*
 * @version $Id: networkequipment.form.php 14684 2011-06-11 06:32:40Z remi $
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
if (!isset($_GET["sort"])) {
   $_GET["sort"] = "";
}
if (!isset($_GET["order"])) {
   $_GET["order"] = "";
}
if (!isset($_GET["withtemplate"])) {
   $_GET["withtemplate"] = "";
}

$netdevice = new NetworkEquipment();
if (isset($_POST["add"])) {
   $netdevice->check(-1,'w',$_POST);

   if($newID = $netdevice->add($_POST)){
       
       $queryid = "SELECT id FROM glpi_networkequipments ORDER BY Id DESC LIMIT 1";
        $result = $DB->query($queryid);
//      $resultid = $DB->query($queryid);
        if ($DB->query($queryid)) {
           while ($data=$DB->fetch_array($result)) {
              $lastid = $data["id"] + 0;
             }
         }
       
       //setting params for input in database
       $sidebinput = array('asset_id' => $lastid, 
                            'dateadd' => date("Y-m-d H:i:s"), 
                            'life' => $_POST['life'],
                            'type' => "monitor"
       );
       $netdevice->addSidebCustom($sidebinput, "usefullife");
       
   }
   
   
   Event::log($newID, "networkequipment", 4, "inventory",
              $_SESSION["glpiname"]." ".$LANG['log'][20]." :  ".$_POST["name"].".");
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $netdevice->check($_POST["id"],'d');
   $netdevice->delete($_POST);

   Event::log($_POST["id"], "networkequipment", 4, "inventory",
              $_SESSION["glpiname"] ." ".$LANG['log'][22]);

   $netdevice->redirectToList();

} else if (isset($_POST["restore"])) {
   $netdevice->check($_POST["id"],'d');

   $netdevice->restore($_POST);
   Event::log($_POST["id"], "networkequipment", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][23]);
   $netdevice->redirectToList();

} else if (isset($_REQUEST["purge"])) {

   $netdevice->check($_REQUEST["id"],'d');

   $netdevice->delete($_REQUEST,1);
   Event::log($_REQUEST["id"], "networkequipment", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][24]);
   $netdevice->redirectToList();

} else if (isset($_POST["update"])) {
   $netdevice->check($_POST["id"],'w');

   $netdevice->update($_POST);
   Event::log($_POST["id"], "networkequipment", 4, "inventory", $_SESSION["glpiname"]." ".$LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);

} else {
   commonHeader($LANG['title'][6],$_SERVER['PHP_SELF'],"inventory","networking");
   $netdevice->showForm($_GET["id"], array('withtemplate' => $_GET["withtemplate"]));
   commonFooter();
}

?>